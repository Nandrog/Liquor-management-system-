<?php

namespace App\Modules\Payments\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Stripe;
use Stripe\Exception\CardException;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class PaymentController extends Controller
{
    public function index()
    {
        // --- The Core Query ---

        // Fetch orders where the status indicates they have been paid for.
        // 'delivered' is often included because delivery implies payment is complete.
        // Adjust the array ['paid', 'delivered'] to match your specific status workflow.
        $paidOrders = Order::whereIn('status', ['paid', 'delivered'])
                            ->with('user')
                            ->latest('paid_at')
                            ->paginate(15);

        return view('payment.index', [
            'orders' => $paidOrders, // The view can now access the data via the $orders variable.
            'pageTitle' => 'Payment History' // A dynamic title for the header.
        ]);
    }
    /**
     * Display the payment form for a specific order.
     *
     * @param Order $order The order that needs to be paid.
     * @return View|RedirectResponse
     */
    public function showPaymentForm(Order $order): View | RedirectResponse
    {
        // Optional: Add logic to ensure the user owns this order
        // and that it hasn't been paid already.
        if ($order->payment_status === 'paid') {
            return redirect()->route('payment.orders.show', $order)->with('info', 'This order has already been paid.');
        }

        // Pass the order object to the view.
        // The view will get the amount and ID from this object.
        return view('payment.payment', ['order' => $order]);
    }

    /**
     * Process the payment submitted from the Stripe Elements form.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function processPayment(Request $request): RedirectResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method_id' => 'required|string',
            'order_id' => 'required|exists:orders,id',
        ]);

        try {
            Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            $order = Order::findOrFail($request->order_id);
            $amount = $request->amount;

            // Create a PaymentIntent to start the payment process
            $paymentIntent = Stripe\PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'ugx',
                'payment_method' => $request->payment_method_id,
                'description' => 'Payment for Order #' . $order->id,
                'confirm' => true, // Attempt to confirm the payment immediately
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never' // Important for this custom form flow
                ],
                // **CRITICAL**: Store your internal order ID in metadata
                'metadata' => [
                    'order_id' => $order->id,
                ]
            ]);

            // Handle the result of the payment attempt
            if ($paymentIntent->status == 'succeeded') {
                // The payment was successful. The webhook will handle the DB update.
                // We redirect to a generic success page or the order details page.
                // You can flash a message confirming the payment is processing.
                return redirect()->route('payment.thankyou')->with('message', 'Payment successful! Your order is being processed.');
            } else {
                // The payment failed or requires another action.
                return back()->withErrors('Payment failed. Please try another card or contact support.');
            }
        } catch (CardException $e) {
            // Card was declined
            return back()->withErrors($e->getError()->message);
        } catch (\Exception $e) {
            // Any other exception
            return back()->withErrors('An unexpected error occurred. ' . $e->getMessage());
        }
    }

    /**
     * Display a generic "Thank You" page after a payment attempt.
     * The actual order update happens via webhook.
     */
    public function thankYou(): View
    {
        return view('payment.success'); // Or any view you prefer
    }

    /**
     * Handle incoming webhooks from Stripe to reliably update order status.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->server('HTTP_STRIPE_SIGNATURE');
        $webhookSecret = config('services.stripe.webhook.secret'); // Make sure you set this in .env
        $event = null;

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the 'payment_intent.succeeded' event
        if ($event->type == 'payment_intent.succeeded') {
            $paymentIntent = $event->data->object; // The PaymentIntent object

            // Retrieve the order_id from the metadata we stored earlier
            $orderId = $paymentIntent->metadata->order_id ?? null;

            if ($orderId) {
                $order = Order::find($orderId);

                // Only update if the order exists and is still pending
                if ($order && $order->payment_status === 'pending') {
                    $order->payment_status = 'paid';
                    $order->transaction_id = $paymentIntent->id; // Store the Stripe Payment Intent ID
                    $order->save();
                }
            }
        }

        // Acknowledge receipt of the event to Stripe
        return response()->json(['status' => 'success']);
    }
}
