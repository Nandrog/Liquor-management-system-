<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Enums\OrderType;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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
        return view('manufacturer.orders.show', compact('order'));
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
}