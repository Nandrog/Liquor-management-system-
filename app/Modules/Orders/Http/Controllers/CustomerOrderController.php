<?php

namespace App\Modules\Orders\Http\Controllers;

use App\Models\Vendor;
use App\Models\Order;
use App\Models\VendorProduct;
use App\Http\Requests\StoreCustomerOrderRequest;
use Illuminate\Support\Facades\Auth;
use App\Enums\OrderType;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CustomerOrderController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a list of all the customer's orders.
     */
    public function index()
    {
        $customer = Auth::user()->customer;
        if (!$customer) {
            // Handle case where user is not a customer
            return redirect('/dashboard')->withErrors('You are not registered as a customer.');
        }

        $orders = Order::where('customer_id', $customer->id)
            ->where('type', OrderType::CUSTOMER_ORDER)
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Show the storefront for a specific vendor.
     * Customers browse products here and add them to their cart/order form.
     */
    public function browse(Vendor $vendor)
    {
        // Fetch products this vendor sells, along with their custom retail price.
        $vendorProducts = $vendor->vendorProducts()->with('product')->get();

        return view('customer.browse', compact('vendorProducts', 'vendor'));
    }

    /**
     * Store a new customer order in the database.
     * This is called when the customer submits the form from the browse page.
     */
    public function store(StoreCustomerOrderRequest $request)
    {
        $customer = Auth::user()->customer;
        $vendor = Vendor::findOrFail($request->vendor_id);

        $order = Order::create([
            'customer_id' => $customer->id,
            'user_id' => Auth::id(),
            'vendor_id' => $vendor->id,
            'type' => OrderType::CUSTOMER_ORDER,
            'status' => OrderStatus::PAID, // Assuming payment is integrated and successful
            'total_amount' => 0, // Will be calculated below
        ]);

        $total = 0;
        foreach ($request->products as $productId => $details) {
            if (empty($details['quantity']) || $details['quantity'] < 1) {
                continue; // Skip items with no quantity
            }

            // CRITICAL: Fetch the price from the database, not the request, to ensure price integrity.
            $vendorProduct = VendorProduct::where('vendor_id', $vendor->id)
                                        ->where('product_id', $productId)
                                        ->firstOrFail();

            $quantity = (int)$details['quantity'];
            $price = $vendorProduct->retail_price;

            $order->items()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price, // Use the vendor's custom retail price
            ]);

            $total += $quantity * $price;
        }

        if ($total == 0) {
            // If nothing was actually ordered, clean up and go back.
            $order->delete();
            return back()->withErrors('You must select a quantity for at least one product.');
        }

        $order->update(['total_amount' => $total]);

        // In a real app, you would also deduct stock from the vendor's inventory here.
        // For now, this is handled by Procurement for B2B. A similar system for B2C would be needed.

        return redirect()->route('customer.orders.show', $order)->with('success', 'Your order has been placed successfully!');
    }

    /**
     * Display a specific customer order.
     */
    public function show(Order $order)
    {
        // Use the OrderPolicy to ensure the customer can only view their own orders.
        $this->authorize('view', $order);

        return view('customer.orders.show', compact('order'));
    }
}
