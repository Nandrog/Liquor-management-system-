<!-- resources/views/vendor/orders/payment.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="h3 font-semibold text-xl text-gray-800 leading-tight">
            Complete Payment for Order #{{ $order->order_number }}
        </h2>
    </x-slot>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">Payment Details</h4>
                        <p class="text-center text-muted">You are about to pay for your stock order.</p>

                        <div class="alert alert-info">
                            In a real application, this page would contain the payment gateway interface (e.g., Stripe Elements for credit card input, a PayPal button, etc.). For this simulation, we will simply confirm the payment.
                        </div>

                        <hr>

                        <dl class="row">
                            <dt class="col-sm-4">Order Number</dt>
                            <dd class="col-sm-8">{{ $order->order_number }}</dd>

                            <dt class="col-sm-4">Total Amount Due</dt>
                            <dd class="col-sm-8 fs-4 fw-bold text-success">UGX {{ number_format($order->total_amount, 0) }}</dd>
                        </dl>

                        <hr>

                        <form action="{{ route('vendor.orders.payment.store', $order) }}" method="POST">
                            @csrf
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Confirm and Complete Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
