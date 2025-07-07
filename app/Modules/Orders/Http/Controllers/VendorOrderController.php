<?php
namespace App\Modules\Orders\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Http\Requests\StoreVendorOrderRequest;
use Illuminate\Support\Facades\Auth;
use App\Enums\ProductType;
use App\Enums\OrderType;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class VendorOrderController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $orders = Auth::user()->vendor->orders()->where('type', OrderType::VENDOR_ORDER)->latest()->paginate(10);
        return view('vendor.orders.index', compact('orders'));
    }

    public function create()
    {
        $finishedGoods = Product::where('type', ProductType::FINISHED_GOOD)->get();
        return view('vendor.orders.create', compact('finishedGoods'));
    }

    public function store(StoreVendorOrderRequest $request)
    {
        $vendor = Auth::user()->vendor;
        $order = Order::create([
            'vendor_id' => $vendor->id,
            'user_id' => Auth::id(),
            'type' => OrderType::VENDOR_ORDER,
            'status' => OrderStatus::PENDING,
            'total_amount' => 0, // Calculate below
        ]);

        $total = 0;
        foreach ($request->products as $productId => $details) {
            $product = Product::find($productId);
            $order->items()->create([
                'product_id' => $productId,
                'quantity' => $details['quantity'],
                'price' => $product->price, // Use base product price
            ]);
            $total += $details['quantity'] * $product->price;
        }
        $order->update(['total_amount' => $total]);

        return redirect()->route('vendor.orders.index')->with('success', 'Order placed successfully.');
    }
    
    public function show(Order $order)
    {
        $this->authorize('view', $order);
        return view('vendor.orders.show', compact('order'));
    }
}