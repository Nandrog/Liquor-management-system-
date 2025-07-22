<?php
namespace App\Modules\Orders\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreSupplierOrderRequest;
use Illuminate\Support\Facades\Auth;
use App\Enums\ProductType;
use App\Enums\OrderType;
use Illuminate\Http\Request;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SupplierOrderController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $supplier = Auth::user()->supplier;
        if (!$supplier) {
            abort(403, 'You are not registered as a supplier.');
        }
        $orders = $supplier->orders()->withCount('items')->where('type', OrderType::SUPPLIER_ORDER)->latest()->paginate(10);
        return view('supplier.orders.index', compact('orders'));
    }

    public function create()
    {
        $rawMaterials = Product::where('type', ProductType::RAW_MATERIAL)->get();
        return view('supplier.orders.create', compact('rawMaterials'));
    }

    public function store(StoreSupplierOrderRequest $request)
{
    // The StoreSupplierOrderRequest has already validated the incoming data.

    try {
        // Wrap the entire creation process in a database transaction.
        // If anything fails, all database changes will be automatically rolled back.
        $order = DB::transaction(function () use ($request) {

            $supplier = Auth::user()->supplier;

            // 1. Create the main order record
            $order = Order::create([
                'supplier_id' => $supplier->id,
                'user_id' => Auth::id(),
                'type' => OrderType::SUPPLIER_ORDER,
                'status' => OrderStatus::PENDING_APPROVAL,
                'total_amount' => 0, // We will calculate this in the loop
            ]);

            $totalAmount = 0;

            // 2. Loop through the products ONCE to create order items
            foreach ($request->validated()['products'] as $productData) {
                $order->items()->create([
                    'product_id' => $productData['id'],
                    'quantity' => $productData['quantity'],
                    'price' => $productData['price'], // Use the price submitted by the supplier
                ]);

                // Calculate the total amount based on the submitted data
                $totalAmount += $productData['quantity'] * $productData['price'];
            }

            // 3. Update the order with the final calculated total amount
            $order->total_amount = $totalAmount;
            $order->save();

            return $order;
        });

    } catch (\Exception $e) {
        // If the transaction fails, you can log the error and show a user-friendly message
        // Log::error('Failed to create supplier order: ' . $e->getMessage());
        return redirect()->back()->with('error', 'There was a problem creating the order. Please try again.');
    }

    // 4. Redirect on success
    return redirect()->route('supplier.orders.index')
        ->with('success', 'Order submitted for approval successfully.');
}

public function edit(Order $order)
{
    // You can add an authorization check here if you have a Policy
    // $this->authorize('update', $order);

    // Make sure the order has its items loaded, similar to your show() method
    $order->load('items.product');

    // We also need the list of all possible raw materials to potentially add more
    $rawMaterials = Product::where('type', ProductType::RAW_MATERIAL)->get();

    // Return the view, passing the specific order and the list of materials
    return view('supplier.orders.edit', compact('order', 'rawMaterials'));
}

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->load('items.product');
        return view('supplier.orders.show', compact('order'));
    }
    public function markAsDelivering(Request $request, Order $order)
{
    // Security check: ensure the order belongs to the authenticated supplier
    if ($order->supplier_id !== Auth::user()->supplier->id) {
        abort(403, 'Unauthorized action.');
    }

    if ($order->status !== OrderStatus::PAID) {
        return redirect()->back()->with('error', 'This order is not ready for delivery.');
    }

    $order->update([
        'status' => OrderStatus::DELIVERING,
        'shipped_at' => now(), // Record the time of shipment
    ]);

    // Business logic check: ensure the order is in the correct state
    if ($order->status == OrderStatus::CONFIRMED && $order->payment_status == 'paid') {
        $order->status = OrderStatus::DELIVERING;
        $order->save();

        return redirect()->route('supplier.orders.show', $order)
                        ->with('success', 'Order has been marked as delivering.');
    }

    return redirect()->back()->with('error', 'This order is not in a state to be marked as delivering.');
}

public function paidOrders()
{
    $supplier = Auth::user()->supplier;

    if (!$supplier) {
        abort(403, 'You are not registered as a supplier.');
    }if (!$supplier) {
        abort(403, 'You are not registered as a supplier.');
    }

    $paidOrders = Order::where('type', OrderType::SUPPLIER_ORDER)
                        ->where('status', OrderStatus::PAID)
                        ->latest()
                        ->paginate(15);

    return view('supplier.orders.paid', [
        'orders' => $paidOrders,
        'pageTitle' => 'Paid Orders'
    ]);
}

public function destroy(Order $order)
{
    // Optional, but recommended: Authorize that the user can delete this order
    // $this->authorize('delete', $order);

    // Perform the delete operation
    $order->delete();

    // Redirect back to the list of orders with a success message
    return redirect()->route('supplier.orders.index')
                    ->with('success', 'Order has been deleted successfully.');
}

public function readyForDelivery()
{

    $supplier = Auth::user()->supplier;
    if (!$supplier) {
        abort(403, 'You are not registered as a supplier.');
    }

    // Only show orders for the currently logged-in supplier that are 'Paid'.
    $ordersToDeliver =  Order::with('user')
                            ->where('supplier_id', $supplier->id)
                             ->where('status', OrderStatus::DELIVERING) // <-- The Key Change
                            ->latest()
                            ->paginate(15);


    return view('supplier.orders.delivery', [
        'orders' => $ordersToDeliver,
        'pageTitle' => 'Ready for Delivery'
    ]);
}

public function markAsDelivered(Request $request, Order $order)
{
    // 1. Security Check: Ensure the order belongs to the authenticated supplier
    $supplier = Auth::user()->supplier;
    if (!$supplier || $order->supplier_id !== $supplier->id) {
        abort(403, 'Unauthorized action.');
    }

    // 2. Business Logic Check: Only orders that are 'DELIVERING' can be marked as 'DELIVERED'.
    if ($order->status !== OrderStatus::DELIVERING) {
        return redirect()->back()->with('error', 'This order is not in transit and cannot be marked as delivered.');
    }

    // 3. Update the Order
    $order->update([
        'status' => OrderStatus::DELIVERED,
        'delivered_at' => now(), // Optional: record the exact time of delivery
    ]);

    // 4. Redirect back to the delivery page with a success message.
    // The order will now be gone from this list.
    return redirect()->route('supplier.orders.delivery')
                    ->with('success', "Order #{$order->id} has been successfully marked as delivered.");
}

    public function update(Request $request, $id)
    {
        // 1. Find the order you want to update
        $supplierOrder = Order::findOrFail($id);

        // 2. Validate the incoming data from the form/request
        $validatedData = $request->validate([
            'supplier_id' => 'sometimes|required|exists:suppliers,id',
            'status' => 'sometimes|required|string|max:50',
            // Add other validation rules here
        ]);

        // 3. Update the model with the validated data
        $supplierOrder->update($validatedData);

        // 4. Redirect back with a success message
        return redirect()->route('supplier.orders.show', $supplierOrder->id)
                        ->with('success', 'Supplier order updated successfully!');
    }


}
