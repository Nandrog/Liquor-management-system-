<?php

namespace App\Modules\Orders\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\VendorProduct; // We will need this for the approval step
use App\Enums\ProductType;
use App\Enums\OrderType;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request; // Use the base Request for create()
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VendorOrderController extends Controller
{
    use AuthorizesRequests;

    // ... your index() and show() methods can stay the same for viewing past orders ...
    public function index()
    {
        $user = Auth::user();
        if (!$user || !$vendor = $user->vendor) {
            abort(403, 'Your vendor profile could not be found.');
        }
        $orders = $vendor->orders()->where('type', OrderType::VENDOR_ORDER)->latest()->paginate(10);
        return view('vendor.orders.index', compact('orders', 'vendor'));
    }

    // --- MODIFICATION START ---

    /**
     * Show the form for a vendor to request new products from the master catalog.
     */
    public function create()
    {
        // 1. Fetch ALL "Finished Goods" that the company offers.
        $finishedGoods = Product::where('type', ProductType::FINISHED_GOOD)->get();

        // 2. Get the list of products the vendor ALREADY sells.
        $vendor = Auth::user()->vendor;
        $existingProductIds = $vendor->vendorProducts->pluck('product_id')->toArray();

        // 3. Pass both lists to the view.
        return view('vendor.orders.create', compact('finishedGoods', 'existingProductIds'));
    }

    /**
     * Store the vendor's product request as a 'pending' order.
     */
    // In VendorOrderController.php

public function store(Request $request) // Use the base Request
{
    // 1. Validate the input
    $request->validate([
        'products' => 'required|array',
        'products.*.quantity' => 'required|integer|min:0', // Quantity is required but can be 0
    ]);

    $vendor = Auth::user()->vendor;

    // Use a database transaction for safety
    $order = DB::transaction(function () use ($request, $vendor) {

        $orderItemsData = [];
        $total = 0;

        // Loop through all submitted products to find those with a quantity > 0
        foreach ($request->products as $productId => $details) {

            // THE FIX: Check for a valid, positive quantity
            if (isset($details['quantity']) && is_numeric($details['quantity']) && $details['quantity'] > 0) {

                $product = Product::find($productId);
                if ($product) {
                    $quantity = (int)$details['quantity'];
                    // This is the cost price from the main company, not the vendor's retail price
                    $price = $product->unit_price;

                    $orderItemsData[] = [
                        'product_id' => $productId,
                        'quantity'   => $quantity,
                        'price'      => $price,
                    ];

                    $total += $quantity * $price;
                }
            }
        }

        // If no products had a valid quantity, there's no order to create.
        if (empty($orderItemsData)) {
            return redirect()->back()->withInput()->withErrors(['products' => 'You must order a quantity of at least 1 for one or more products.']);
        }

        // Create the main purchase order record
        $newOrder = Order::create([
            'vendor_id'      => $vendor->id,
            'user_id'        => Auth::id(),
           'order_number' => 'ORD-VND-' . strtoupper(uniqid()), // Example Purchase Order number
            'type'           => OrderType::VENDOR_ORDER,
            'status'         => OrderStatus::PENDING, // Now means "Pending Payment"
            'payment_status' => 'pending', // Explicitly set payment status
            'total_amount'   => $total,
        ]);

        // Create the associated order items
        $newOrder->items()->createMany($orderItemsData);

        return $newOrder; // Return the created order
    });

    // Handle the redirect if no items were ordered
    if ($order instanceof \Illuminate\Http\RedirectResponse) {
        return $order;
    }

    // --- MODIFICATION: REDIRECT TO THE PAYMENT/SUMMARY PAGE ---
    // Instead of going back to the index, we redirect the vendor
    // to a page where they can review and pay for their new order.
    return redirect()->route('vendor.orders.show', $order)
           ->with('success', 'Purchase Order created successfully. Please review and proceed with payment.');
}
    // --- MODIFICATION END ---

    /**
     * This method would be called by an ADMIN to approve the order.
     */
    public function approve(Order $order)
    {
        // Add Authorization: only an Admin can run this.
        // $this->authorize('approve', $order);

        // Ensure this is a vendor order that is pending.
        if ($order->type !== OrderType::VENDOR_ORDER || $order->status !== OrderStatus::PENDING) {
            return back()->with('error', 'This order cannot be approved.');
        }

        DB::transaction(function () use ($order) {
            $vendorId = $order->vendor_id;

            // Loop through the items in the approved order
            foreach ($order->items as $item) {
                // Use updateOrCreate to assign the product to the vendor.
                // This prevents errors if the product was already assigned.
                VendorProduct::updateOrCreate(
                    [
                        'vendor_id' => $vendorId,
                        'product_id' => $item->product_id,
                    ],
                    [
                        // We leave retail_price as null. The vendor must set it.
                    ]
                );
            }

            // Update the order status to show it has been processed.
            $order->update(['status' => 'approved']);

            // You could also trigger a notification to the vendor here.
        });

        return back()->with('success', 'Order has been approved and products have been assigned to the vendor.');
    }

    public function authorize(): bool
{
    // Get the currently authenticated user
    $user = Auth::user();

    // The user must be logged in AND have the 'Vendor' role.
    return $user && $user->hasRole('Vendor');
}
    /**
     * Show the details of a specific order.
     */
    public function show(Order $order)
    {
        // Ensure the order belongs to the vendor
        $this->authorize('view', $order);
        $vendor = Auth::user()->vendor;
        return view('vendor.orders.show', compact('order', 'vendor'));
}

 public function showPaymentPage(Order $order)
    {
        // Authorize that the vendor can view (and thus pay for) this order
        $this->authorize('view', $order);

        // Ensure the order is actually pending payment
        if ($order->payment_status->value !== 'pending') {
            return redirect()->route('vendor.orders.show', $order)->with('error', 'This order is not awaiting payment.');
        }

        return view('vendor.orders.payment', compact('order'));
    }

    /**
     * NEW METHOD: Process the payment for the order.
     * In a real app, this would contain logic for Stripe, PayPal, etc.
     * Here, we will just simulate a successful payment.
     */
    public function processPayment(Request $request, Order $order)
    {
        $this->authorize('view', $order);

        // Simulate a successful payment gateway interaction
        try {
            // Update the order status
            $order->update([
                'payment_status' => PaymentStatus::PAID,
                'paid_at' => now(), // Record the time of payment
                'transaction_id' => 'SIMULATED-' . Str::random(12), // Simulate a transaction ID
            ]);

            // In a real app, you would add logic here to:
            // 1. Dispatch stock from the main warehouse to the vendor.
            // 2. Update the VendorProduct stock levels.
            // 3. Send a confirmation email.

            return redirect()->route('vendor.orders.show', $order)->with('success', 'Payment successful! Your order is now being processed.');

        } catch (\Exception $e) {
            \Log::error('Payment processing failed for order ' . $order->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'There was a problem processing your payment. Please try again.');
        }
    }
}

