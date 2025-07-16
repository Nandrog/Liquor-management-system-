<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Enums\OrderType;
use App\Models\Warehouse;
use App\Models\StockLevel;
use App\Enums\OrderStatus;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\InventoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\log;
use Illuminate\Http\RedirectResponse;

class ManufacturerController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $orders = Order::where('type', OrderType::SUPPLIER_ORDER)->latest()->paginate(10);
        return view('manufacturer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $warehouses = Warehouse::all();
        return view('manufacturer.orders.show',  compact('order', 'warehouses'));
    }

    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);
        $request->validate(['status' => 'required|in:confirmed,rejected']);

        if ($request->status == 'confirmed') {
            // Note: Observer handles stock increase
            $order->update(['status' => OrderStatus::CONFIRMED]);
            // Here you would integrate a payment gateway or logic
            $order->update(['status' => OrderStatus::PAID]);
        } else {
            $order->update(['status' => OrderStatus::REJECTED]);
        }

        return redirect()->route('manufacturer.orders.index')->with('success', 'Order status updated.');
    }


    // Add this new method inside your ManufacturerController class

public function paidOrders()
{
    // You can add authorization here if needed, e.g.
    // $this->authorize('viewAny', Order::class);

    // Find all supplier orders with the 'paid' status
    $paidOrders = Order::where('type', OrderType::SUPPLIER_ORDER)
                        ->where('status', OrderStatus::PAID)
                        ->latest()
                        ->paginate(15);

    // Return a view, passing the orders and a title
    // You will need to create this view file later.
    return view('manufacturer.orders.paid', [
        'orders' => $paidOrders,
        'pageTitle' => 'Paid Orders'
    ]);
}
// ... other methods in your controller ...

/**
 * Display a listing of orders that are currently in delivery.
 */
public function deliveringOrders()
{
    $deliveringOrders = Order::where('type', OrderType::SUPPLIER_ORDER)
                             ->where('status', OrderStatus::DELIVERING) // <-- The key filter
                            ->latest()
                            ->paginate(15);

                            $warehouses = Warehouse::all();

    return view('manufacturer.orders.delivery', [
        'orders' => $deliveringOrders,
        'warehouses' => $warehouses,
        'pageTitle' => 'Orders In Delivery'
    ]);
}


public function confirmDelivery(Request $request, Order $order, InventoryService $inventoryService)
{
    // 1. Validate the request to ensure a valid warehouse was selected.
    $validated = $request->validate([
        'warehouse_id' => 'required|integer|exists:warehouses,id',
    ]);

    if ($order->status !== OrderStatus::DELIVERING) {
        return redirect()->back()->with('error', 'This order is not awaiting delivery confirmation.');
    }

    try {
        DB::transaction(function () use ($order, $inventoryService, $validated) {

            // 2. Pass the validated warehouse_id to the service.
            $inventoryService->addStockFromSupplierOrder($order, $validated['warehouse_id']);

            // 3. Update the order status as before.
            $order->status = OrderStatus::DELIVERED;
            $order->save();
        });

    } catch (\Exception $e) {
        Log::error("Failed to confirm delivery for Order #{$order->id}: " . $e->getMessage());
        return redirect()->back()->with('error', 'A problem occurred while updating inventory.');
    }

    return redirect()->route('manufacturer.orders.show', $order)
                    ->with('success', 'Delivery confirmed and inventory allocated successfully!');
}

    public function markAsDelivered(Request $request, Order $order)
{
    // 1. Authorize the action (optional but good practice)
    $this->authorize('update', $order);

    // 2. Validate the request: ensure a warehouse was selected.
    $validated = $request->validate([
        'warehouse_id' => 'required|integer|exists:warehouses,warehouse_id',
    ]);
    $warehouseId = $validated['warehouse_id'];

    //dd('Validation passed. Warehouse ID is:', $validated);

    // 3. Check if the order is in the correct state
    if ($order->status !== OrderStatus::DELIVERING) {
        return redirect()->back()->with('error', 'This order is not in a deliverable state.');
    }

    try {
    DB::transaction(function () use ($order, $warehouseId) {

        foreach ($order->products as $orderItem) {

            // Get the quantity and price from the pivot table
            $quantityReceived = $orderItem->pivot->quantity;
            $purchasePrice = $orderItem->pivot->price;

            // 5. Find the product by its ID from the relationship.
            $product = Product::find($orderItem->id);

            // If for some reason the product doesn't exist, skip it to prevent errors.
            if (!$product) {
                continue;
            }

            // 6. Find the stock level for this product in the selected warehouse, or create it.
            $stockLevel = StockLevel::firstOrCreate(
                [
                    'product_id'   => $product->id,
                    'warehouse_id' => $warehouseId,
                ],
                ['quantity' => 0] // This is only set if the record is new
            );

            // 7. Atomically increment the stock level.
            $stockLevel->increment('quantity', $quantityReceived);
        }

        // 8. Update the order's status to 'Delivered'.
        $order->status = OrderStatus::DELIVERED;
        $order->delivered_at = now();
        $order->save();
    });

    } catch (\Exception $e) {
        Log::error("Failed to process delivery for Order #{$order->id}: " . $e->getMessage());
        return redirect()->back()->with('error', 'An error occurred while updating inventory. The operation was cancelled.');

    }
//dd($e);
    return redirect()->route('manufacturer.orders.delivery')->with('success', "Order #{$order->order_number} marked as delivered. Inventory updated.");
}
}
