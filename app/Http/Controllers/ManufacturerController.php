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

    return view('manufacturer.orders.delivery', [
        'orders' => $deliveringOrders,
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

    public function markAsDelivered(Order $order)
    {
        // Validation 1: Check order status
        if ($order->status !== 'delivering') {
            return redirect()->back()->with('error', 'This order is not in a deliverable state.');
        }

        // Validation 2: Ensure we know which warehouse to add stock to.
        // This assumes your 'orders' table has a 'warehouse_id' column.
        if (is_null($order->warehouse_id)) {
            return redirect()->back()->with('error', 'Cannot process delivery: Target warehouse is not specified on the order.');
        }

        try {
            DB::transaction(function () use ($order) {

                // Iterate through each product item from the supplier's order
                foreach ($order->products as $orderItem) {

                    // === PART A: FIND OR CREATE THE MASTER PRODUCT RECORD ===

                    $product = Product::updateOrCreate(
                        // 1. Find the product using its unique SKU
                        ['sku' => $orderItem->pivot->sku],

                        // 2. If not found, create it with this data.
                        //    (This data is ignored if the product already exists)
                        [
                            'name'            => $orderItem->pivot->product_name,
                            'description'     => 'New product automatically created from delivery of Order #' . $order->order_number,
                            'type'            => $orderItem->pivot->type ?? 'Raw Material', // Use a default if not specified
                            'unit_price'      => $orderItem->pivot->price,
                            'unit_of_measure' => $orderItem->pivot->unit_of_measure ?? 'unit',
                            'reorder_level'   => 10, // A sensible default
                            'category_id'     => $orderItem->pivot->category_id ?? null, // Assign a default or null
                            'user_id'         => auth()->id, // The manufacturer receiving the goods
                            'vendor_id'       => $order->user_id, // The supplier who sent the order
                        ]
                    );

                    // === PART B: UPDATE THE STOCK IN THE CORRECT WAREHOUSE ===

                    // Get the quantity received from the order's pivot table
                    $quantityReceived = $orderItem->pivot->quantity;

                    // Find the stock level for this specific product in this specific warehouse.
                    // If it doesn't exist, create it with an initial quantity of 0.
                    $stockLevel = StockLevel::firstOrCreate(
                        [
                            'product_id'   => $product->id,
                            'warehouse_id' => $order->warehouse_id,
                        ],
                        ['quantity' => 0] // Only sets quantity to 0 if creating for the first time
                    );

                    // Now, atomically increment the quantity. This is safe from race conditions.
                    $stockLevel->increment('quantity', $quantityReceived);
                }

                // === PART C: UPDATE THE ORDER STATUS ===
                $order->status = 'delivered';
                $order->delivered_at = now();
                $order->save();
            });

        } catch (\Exception $e) {
            // Log the detailed error for debugging purposes
            Log::error("Failed to mark order #{$order->id} as delivered: " . $e->getMessage());

            // Provide a user-friendly error message
            return redirect()->back()->with('error', 'An unexpected error occurred while processing the delivery. The operation was cancelled.');
        }

        return redirect()->route('manufacturer.orders.delivery')
                        ->with('success', "Order #{$order->order_number} marked as delivered. Inventory has been updated successfully.");
    }
}
