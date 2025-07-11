<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Complete Payment for Order #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Display Backend Errors (e.g., Card Declined) --}}
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div class="grid md:grid-cols-2 gap-6">

                        {{-- 1. Order Summary --}}
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Order Summary</h3>
                            <dl class="text-gray-900 divide-y divide-gray-200">
                                @foreach($order->items as $item)
                                <div class="flex justify-between py-2">
                                    <dt class="text-sm font-medium text-gray-500">
                                        {{ $item->product->name }} (Qty: {{ $item->quantity }})
                                    </dt>
                                    <dd class="text-sm text-gray-900">
                                        UGX {{ number_format($item->quantity * $item->price, 2) }}
                                    </dd>
                                </div>
                                @endforeach
                            </dl>
                            <div class="flex justify-between pt-4 mt-4 border-t border-gray-200">
                                <dt class="text-lg font-bold text-gray-900">Total Amount Due</dt>
                                <dd class="text-lg font-bold text-indigo-600">
                                    UGX {{ number_format($order->total_amount, 2) }}
                                </dd>
                            </div>
                        </div>

                        {{-- 2. Payment Form --}}
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Payment Details</h3>

                            {{-- The form submits to your PaymentController@processPayment route --}}
                            <form action="{{ route('payment.process', $order->id) }}" method="POST" id="payment-form">
                                @csrf

                                <div class="mb-4">
                                    <label for="card-holder-name" class="block text-sm font-medium text-gray-700">Cardholder Name</label>
                                    <input type="text" id="card-holder-name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                </div>

                                <div class="mb-4">
                                    <label for="card-element" class="block text-sm font-medium text-gray-700 mb-1">Credit or debit card</label>

                                    <!-- Stripe Elements will inject the card input here -->
                                    <div id="card-element" class="p-3 border border-gray-300 rounded-md shadow-sm"></div>

                                    <!-- Used to display form errors from Stripe JS -->
                                    <div id="card-errors" role="alert" class="text-red-500 text-sm mt-2"></div>
                                </div>

                                <!-- This hidden input will store the Stripe payment method ID -->
                                <input type="hidden" name="payment_method" id="payment-method-id">

                                <button id="card-button" type="submit" class="w-full auth-button-green auth-button py-2 px-4 rounded-md shadow-sm text-sm font-medium text-white">
                                    Pay UGX {{ number_format($order->total_amount, 2) }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Stripe JavaScript Integration --}}
    @push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Stripe with your PUBLISHABLE key
            const stripe = Stripe('{{ env('STRIPE_KEY') }}');
            const elements = stripe.elements();

            // Style configuration for the card element
            const style = {
                base: {
                    color: '#32325d',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                }
            };

            // Create an instance of the card Element and mount it
            const cardElement = elements.create('card', {style: style});
            cardElement.mount('#card-element');

            // Handle real-time validation errors from the card Element.
            cardElement.addEventListener('change', function(event) {
                const displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });

            // Handle form submission.
            const form = document.getElementById('payment-form');
            const cardHolderName = document.getElementById('card-holder-name');
            const cardButton = document.getElementById('card-button');
            const paymentMethodInput = document.getElementById('payment-method-id');

            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                cardButton.disabled = true; // Disable button to prevent double clicks
                cardButton.textContent = 'Processing...';

                const { paymentMethod, error } = await stripe.createPaymentMethod(
                    'card', cardElement, {
                        billing_details: { name: cardHolderName.value }
                    }
                );

                if (error) {
                    // Inform the user if there was an error.
                    const errorElement = document.getElementById('card-errors');
                    errorElement.textContent = error.message;
                    cardButton.disabled = false;
                    cardButton.textContent = 'Pay UGX {{ number_format($order->total_amount, 2) }}';
                } else {
                    // Send the payment method ID to your server.
                    paymentMethodInput.value = paymentMethod.id;
                    form.submit();
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
