<?php

namespace App\Modules\Orders\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $total = 0;

            //  Create order
            $order = Order::create([
                'user_id' => auth()->id, // Ensure the user is logged in
                'total_price' => 0, // Temporary value, will update later
                'status' => 'pending',
            ]);

            //  Loop through items and create order items
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Not enough stock for product: {$product->name}");
                }

                $price = $product->price * $item['quantity'];
                $total += $price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                ]);

                //  Reduce product stock
                $product->stock -= $item['quantity'];
                $product->save();
            }

            //  Update total price
            $order->total_price = $total;
            $order->save();

            DB::commit();

            return response()->json([
                'message' => 'Order placed successfully!',
                'order_id' => $order->id
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Order failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function create()
{
    return view('orders.create', [
        'users' => User::all(),
        'products' => Product::where('stock', '>', 0)->get(),
    ]);
}

public function index()
{
    $orders = Order::with('user', 'orderItems.product')->latest()->get();
    return view('orders.index', compact('orders'));
}

public function orders()
{
    //$orders = Order::with('user', 'orderItems.product')->latest()->get();
    return view('orders.orders');
}
}
