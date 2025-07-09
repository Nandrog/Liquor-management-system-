<?php
namespace App\Modules\Orders\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Http\Requests\StoreSupplierOrderRequest;
use Illuminate\Support\Facades\Auth;
use App\Enums\ProductType;
use App\Enums\OrderType;
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
        $orders = $supplier->orders()->where('type', OrderType::SUPPLIER_ORDER)->latest()->paginate(10);
        return view('supplier.orders.index', compact('orders'));
    }

    public function create()
    {
        $rawMaterials = Product::where('type', ProductType::RAW_MATERIAL)->get();
        return view('supplier.orders.create', compact('rawMaterials'));
    }

    public function store(StoreSupplierOrderRequest $request)
    {
        $supplier = Auth::user()->supplier;

        $order = Order::create([
            'supplier_id' => $supplier->id,
            'user_id' => Auth::id(),
            'type' => OrderType::SUPPLIER_ORDER,
            'status' => OrderStatus::PENDING_APPROVAL,
            'total_amount' => 0, // Calculate below
        ]);

        $total = 0;
        foreach ($request->products as $product) {
            $order->items()->create([
                'product_id' => $product['id'],
                'quantity' => $product['quantity'],
                'price' => $product['price'], // Price offered by supplier
            ]);
            $total += $product['quantity'] * $product['price'];
        }

        $order->update(['total_amount' => $total]);

        return redirect()->route('supplier.orders.index')->with('success', 'Order submitted for approval.');
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        return view('supplier.orders.show', compact('order'));
    }
}
