<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Enums\OrderType;
use App\Models\Warehouse;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Services\InventoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\log;

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
        $this->authorize('view', $warehouses = Warehouse::all());
        return view('manufacturer.orders.show',  [
            'order' => $order,
            'warehouses' => $warehouses,
        ]);
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

    return view('manufacturer.orders.index', [
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
}
