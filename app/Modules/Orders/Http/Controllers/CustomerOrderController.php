<?php

namespace App\Modules\Orders\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem; // Make sure to import OrderItem
use App\Models\Cart;      // Assuming you have a Cart model from our previous steps
use App\Models\Vendor;    // Import the Vendor model
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Exception;


class CustomerOrderController extends Controller
{
    use AuthorizesRequests;

 public function index()
    {

if (! Auth::user() || ! Auth::user()->hasRole('Customer')) {
    abort(403, 'You are not registered as a customer.');
}

        $orders = Order::where('customer_id', Auth::user()->customer->id)
            ->where('type', 'customer_order')
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

     public function browse(Vendor $vendor)
    {
        // Fetch products this vendor sells, along with their custom retail price.
        $vendorProducts = $vendor->vendorProducts()->with('product')->get();

        return view('customer.browse', compact('vendorProducts', 'vendor'));
    }


   // In CustomerOrderController.php
public function create()
{
    // CORRECT: Eager load the 'vendorProducts' (plural) relationship.
    $cartItems = Cart::where('user_id', Auth::id())->with('product.vendorProducts')->get();

    if ($cartItems->isEmpty()) {
        return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
    }

    $subtotal = $cartItems->sum(function ($item) {
        // CORRECT: Access the 'vendorProducts' collection and get the first result.
        // We assume the cart is for a single vendor, so ->first() is safe here.
        $vendorProduct = $item->product->vendorProducts->first();
        
        // Use the vendor's price if it exists, otherwise fall back to the base price.
        $vendorPrice = $vendorProduct->retail_price ?? $item->product->unit_price;
        
        return $vendorPrice * $item->quantity;
    });

    return view('checkout.create', compact('cartItems', 'subtotal'));
}
    /**
     * Store a new customer order in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:255',
            'city'             => 'required|string|max:255',
            'phone_number'     => 'required|string|max:20',
        ]);

        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->with('product.vendorProduct')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('storefront.index')->with('error', 'Your cart is empty.');
        }

        // Assuming all items in the cart are from one vendor.
        // In a real multi-vendor cart, this logic would be more complex.
        $vendorId = $cartItems->first()->product->vendorProduct->vendor_id ?? null;

        DB::beginTransaction();
        try {
            // 1. Create the Order
            $order = Order::create([
                'user_id'          => $user->id,
                'customer_id'      => $user->customer->id, // Assuming a 'customer' relationship on User model
                'vendor_id'        => $vendorId,
                'order_number'     => 'ORD-' . strtoupper(uniqid()),
                'type'             => 'customer_order',
                'status'           => 'pending', // Initial status
                'total_amount'     => 0, // Placeholder, will be updated
                'shipping_address' => $request->shipping_address,
                'city'             => $request->city,
                'phone_number'     => $request->phone_number,
                'payment_status'   => 'paid', // Assuming payment is handled
            ]);

            $total = 0;
            // 2. Create Order Items from Cart Items
            foreach ($cartItems as $cartItem) {
                // Get the vendor-specific price, with a fallback to the base product price
                $price = $cartItem->product->vendorProduct->retail_price ?? $cartItem->product->unit_price;
                $total += $price * $cartItem->quantity;

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity'   => $cartItem->quantity,
                    'price'      => $price, // Store the price at the time of purchase
                ]);
            }

            // 3. Update the final total on the order
            $order->update(['total_amount' => $total]);

            // 4. Clear the user's cart
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            return redirect()->route('customer.orders.show', $order)->with('success', 'Thank you! Your order has been placed successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong. Please try again. Error: ' . $e->getMessage());
        }
    }

    /**
     * Display a specific customer order with tracking status.
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order); // Your existing authorization is great!
        return view('orders.show', compact('order'));
    }

    // Your other methods like index() and browse() can remain as they are.
}
