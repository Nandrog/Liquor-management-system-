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

    // Business logic check: ensure the order is in the correct state
    if ($order->status == OrderStatus::CONFIRMED && $order->payment_status == 'paid') {
        $order->status = OrderStatus::DELIVERING;
        $order->save();

        return redirect()->route('supplier.orders.show', $order)
                        ->with('success', 'Order has been marked as delivering.');
    }

    return redirect()->back()->with('error', 'This order is not in a state to be marked as delivering.');
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
}
