<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\StockLevel;
use App\Enums\OrderType;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProcurementController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $orders = Order::where('type', OrderType::VENDOR_ORDER)->latest()->paginate(10);
        return view('procurement.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->load('items.product.stockLevels');
        return view('procurement.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);
        $request->validate(['status' => 'required|in:confirmed,rejected']);

        if ($request->status == 'confirmed') {
            // Check stock levels before confirming
            foreach ($order->items as $item) {
                $stock = StockLevel::where('product_id', $item->product_id)->first();
                if (!$stock || $stock->quantity < $item->quantity) {
                    return back()->withErrors(['stock' => 'Insufficient stock for product: ' . $item->product->name]);
                }
            }
            // Observer handles stock deduction
            $order->update(['status' => OrderStatus::CONFIRMED]); 
        } else {
            $order->update(['status' => OrderStatus::REJECTED]);
            // Trigger refund logic here
        }

        return redirect()->route('procurement.orders.index')->with('success', 'Order status updated.');
    }
}