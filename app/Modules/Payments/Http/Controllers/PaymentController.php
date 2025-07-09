<?php

namespace App\Modules\Payments\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use App\Models\Order;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    /**
     * Handle the successful payment redirect from Stripe.
     */
    public function success(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $sessionId = $request->get('session_id');

        try {
            // **SECURITY**: Retrieve the session from Stripe to verify it's a valid request
            $session = Session::retrieve($sessionId);

            if (!$session) {
                return redirect()->route('payment.cancel')->with('error', 'Invalid session.');
            }

            // Retrieve the order using the metadata we stored
            $orderId = $session->metadata->order_id;
            $order = Order::find($orderId);

            if (!$order) {
                return redirect()->route('payment.cancel')->with('error', 'Order not found.');
            }

            // **BEST PRACTICE**: While we can update the order here, it's more reliable to
            // use webhooks, as the user might close the browser before this page loads.
            // The webhook provides the definitive "source of truth".
            // For now, we'll just show a success message. The webhook will handle the update.
            if ($order->payment_status === 'pending') {
                 // You might want a "processing" status here until the webhook confirms.
            }

            return view('payment.success', ['order' => $order]);

        } catch (\Exception $e) {
            return redirect()->route('payment.cancel')->with('error', 'Payment verification failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle the cancelled payment redirect from Stripe.
     */
    public function cancel()
    {
        // Optionally, find the related 'pending' order and mark it as 'failed' or delete it.
        return view('payment.cancel');
    }

    public function handleWebhook(Request $request)
{
    $payload = $request->getContent();
    $sigHeader = $request->server('HTTP_STRIPE_SIGNATURE');
    $webhookSecret = config('services.stripe.webhook.secret');
    $event = null;

    try {
        $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
    } catch (\UnexpectedValueException $e) {
        // Invalid payload
        return response()->json(['error' => 'Invalid payload'], 400);
    } catch (SignatureVerificationException $e) {
        // Invalid signature
        return response()->json(['error' => 'Invalid signature'], 400);
    }

    // Handle the event
    if ($event->type == 'checkout.session.completed') {
        $session = $event->data->object; // The session object

        // Retrieve order_id from metadata
        $orderId = $session->metadata->order_id ?? null;
        if (!$orderId) {
            $orderId = $session->payment_intent->metadata->order_id ?? null;
        }

        $order = Order::find($orderId);

        if ($order && $order->payment_status === 'pending') {
            // Update the order status to 'paid'
            $order->payment_status = 'paid';
            // Save the Stripe Payment Intent ID as the transaction ID
            $order->transaction_id = $session->payment_intent;
            $order->save();
        }
    }

    // Acknowledge receipt of the event
    return response()->json(['status' => 'success']);
}

public function charge(Request $request)
{
    $request->validate([
        'amount' => 'required|numeric|min:1',
        'payment_method_id' => 'required|string'
    ]);

    Stripe::setApiKey(config('services.stripe.secret'));

    try {
        \Stripe\Charge::create([
            'amount' => $request->amount, // amount in UGX
            'currency' => 'ugx',
            'payment_method' => $request->payment_method_id,
            'confirmation_method' => 'manual',
            'confirm' => true,
        ]);

        return redirect()->route('payment.success')->with('message', 'Payment successful!');
    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Stripe Error: ' . $e->getMessage()]);
    }
}
}
