<?php
namespace App\Modules\Orders\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\VendorProduct;
use App\Http\Requests\StoreVendorOrderRequest;
use Illuminate\Support\Facades\Auth;
use App\Enums\ProductType;
use App\Enums\OrderType;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class VendorOrderController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $vendor = Auth::user()->vendor;
        $orders = $vendor->orders()->where('type', OrderType::VENDOR_ORDER)->latest()->paginate(10);
        $products = $vendor->vendorProducts()->with('product')->paginate(20);
        return view('vendor.orders.index', compact('orders','products', 'vendor'));
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

    public function update(Request $request, VendorProduct $product)
    {
        // Authorization: Ensure the product being updated belongs to the logged-in vendor.
        if ($product->vendor_id !== Auth::user()->vendor->id) {
            abort(403, 'UNAUTHORIZED ACTION');
        }

        $request->validate([
            'retail_price' => 'required|numeric|min:0',
        ]);

        $product->update([
            'retail_price' => $request->retail_price,
        ]);

        return back()->with('success', 'Price updated successfully!');
    }
}
