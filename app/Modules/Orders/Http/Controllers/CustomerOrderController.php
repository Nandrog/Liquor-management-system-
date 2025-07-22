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
// Add this import if OrderType exists in your app structure
// use App\Enums\OrderType;


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
    // In CustomerOrderController.php

public function store(Request $request)
{
    $request->validate([
        'shipping_address' => ['required', 'string', 'max:255'],
        'city'             => ['required', 'string', 'max:255'],
        'phone_number'     => ['required', 'string', 'max:20'],
        // You might want to validate postal_code here as well if it's on your form
    ]);

    $user = Auth::user();
    $cartItems = Cart::where('user_id', $user->id)->with('product.vendorProducts')->get();

    if ($cartItems->isEmpty()) {
        return redirect()->route('storefront.index')->with('error', 'Your cart is empty.');
    }

    // This logic correctly gets the vendor ID, assuming a single-vendor cart.
    $firstItemVendorProduct = $cartItems->first()->product->vendorProducts->first();
    $vendorId = $firstItemVendorProduct ? $firstItemVendorProduct->vendor_id : null;

    if (!$vendorId) {
        // This is a good safety check.
        return redirect()->back()->with('error', 'An item in your cart is not available from any vendor. Please remove it and try again.');
    }

    // Start a database transaction for safety.
    DB::beginTransaction();
    try {
        // 1. Create the Order
        $order = Order::create([
            'user_id'          => $user->id,
            'customer_id'      => $user->customer->id,
            'vendor_id'        => $vendorId, // <-- THE FIX: ADD THIS LINE
            'order_number'     => 'ORD-' . strtoupper(uniqid()),
            'type'             => 'customer_order', // No longer duplicated
            'status'           => 'pending',
            'total_amount'     => 0, // Placeholder
            'shipping_address' => $request->shipping_address,
            'city'             => $request->city,
            'phone_number'     => $request->phone_number,
            'payment_status'   => 'pending', // It's better to default to pending until payment is confirmed
        ]);

        $total = 0;
        // 2. Create Order Items from Cart Items
        foreach ($cartItems as $cartItem) {
            $vendorProduct = $cartItem->product->vendorProducts->first();
            // Fallback to base price if for some reason a retail price isn't set
            $price = $vendorProduct->retail_price ?? $cartItem->product->unit_price;

            $total += $price * $cartItem->quantity;

            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $cartItem->product_id,
                'quantity'   => $cartItem->quantity,
                'price'      => $price,
            ]);
        }

        // 3. Update the final total on the order
        $order->update(['total_amount' => $total]);

        // 4. Clear the user's cart
        Cart::where('user_id', $user->id)->delete();

        // 5. Commit all changes to the database
        DB::commit();

        // Redirect to payment or a success page
        return redirect()->route('payment.form', ['order' => $order->id])->with('success', 'Your order has been placed! Please proceed with payment.');

    } catch (Exception $e) {
        // If anything fails, undo all database changes and show an error
        DB::rollBack();
        return redirect()->back()->with('error', 'Something went wrong while placing your order. Please try again. Error: ' . $e->getMessage());
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
