<x-app-layout>
    <style>
        /* Your custom input styles can be applied here */
        label {
            letter-spacing: 0.01em;
            font-weight: 500; /* medium */
        }

        /* Base styles for the Stripe Element containers */
        .StripeElement {
            box-sizing: border-box;
            height: 44px; /* Consistent height */
            padding: 12px; /* p-3 */
            border: 2px solid #e5e7eb; /* border-gray-300 */
            border-radius: 0.375rem; /* rounded-md */
            background-color: white;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); /* shadow-sm */
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        /* Hover state for the element container */
        .StripeElement:hover {
            border-color: #16a34a; /* green-600 */
        }

        /* Focus state for the element container */
        .StripeElement--focus {
            border-color: #22c55e; /* green-500 */
            box-shadow: 0 0 0 2px #bbf7d0; /* green-200 */
        }

        /* Invalid state for the element container */
        .StripeElement--invalid {
            border-color: #ef4444; /* red-500 */
        }

        /* Read-only styles for your amount input */
        input[readonly] {
            background-color: #f3f4f6; /* gray-100 */
            color: #6b7280; /* gray-500 */
        }
    </style>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Stripe Payment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if(session('message'))
                    <div class="mb-4 text-green-600 font-medium">
                        {{ session('message') }}
                    </div>
                @endif

                <form id="payment-form" method="POST" action="{{ route('stripe.charge') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="amount" class="block text-sm text-gray-700">Amount (UGX)</label>
                        <input
                            type="text"
                            name="amount"
                            id="amount"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                            value="{{ number_format($order->total_amount, 2) }}"
                            readonly
                        >
                    </div>

                    <!-- Card Number Input -->
                    <div class="mb-4">
                        <label for="card-number-element" class="block text-sm text-gray-700">Card Number</label>
                        <div id="card-number-element" class="mt-1">
                            <!-- A Stripe Element will be inserted here. -->
                        </div>
                    </div>

                    <!-- Expiry and CVC Inputs in a row -->
                    <div class="mb-4 flex gap-4">
                        <div class="w-1/2">
                            <label for="card-expiry-element" class="block text-sm text-gray-700">Expiration Date</label>
                            <div id="card-expiry-element" class="mt-1">
                                <!-- A Stripe Element will be inserted here. -->
                            </div>
                        </div>
                        <div class="w-1/2">
                            <label for="card-cvc-element" class="block text-sm text-gray-700">CVC</label>
                            <div id="card-cvc-element" class="mt-1">
                                <!-- A Stripe Element will be inserted here. -->
                            </div>
                        </div>
                    </div>

                    <!-- Used to display form errors -->
                    <div id="card-errors" class="text-red-500 mt-2 text-sm" role="alert"></div>

                    <!-- Hidden input for the payment_method_id -->
                    <input type="hidden" name="payment_method_id" id="payment_method_id">

                    <div class="flex justify-end mt-6">
                        <button type="submit" id="submit" class="inline-flex items-center px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="button-text">Pay Now</span>
                            <svg id="spinner" class="animate-spin ml-2 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- =================================================================
    SCRIPT SECTION - THIS IS THE FIX
    ================================================================== -->
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // 1. Initialize Stripe with your publishable key
        const stripe = Stripe("{{ env('STRIPE_KEY') }}");

        // 2. Set up individual Stripe Elements
        const elements = stripe.elements();

        const style = {
            base: {
                color: '#32325d',
                fontFamily: 'Arial, sans-serif',
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

        const cardNumber = elements.create('cardNumber', { style: style });
        const cardExpiry = elements.create('cardExpiry', { style: style });
        const cardCvc = elements.create('cardCvc', { style: style });

        // 3. Mount each element to its corresponding div in the DOM
        cardNumber.mount('#card-number-element');
        cardExpiry.mount('#card-expiry-element');
        cardCvc.mount('#card-cvc-element');

        // 4. Handle real-time validation errors from the card Elements.
        const cardErrors = document.getElementById('card-errors');
        const elementsToListen = [cardNumber, cardExpiry, cardCvc];

        elementsToListen.forEach(function(element) {
            element.on('change', function(event) {
                if (event.error) {
                    cardErrors.textContent = event.error.message;
                } else {
                    cardErrors.textContent = '';
                }
            });
        });

        // 5. Handle form submission
        const form = document.getElementById('payment-form');
        const submitButton = document.getElementById('submit');
        const buttonText = document.getElementById('button-text');
        const spinner = document.getElementById('spinner');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            submitButton.disabled = true;
            spinner.classList.remove('hidden');
            buttonText.classList.add('hidden');

            // 6. Create the PaymentMethod. Stripe knows to find the expiry and CVC
            // elements because they were created by the same `elements` instance.
            const { paymentMethod, error } = await stripe.createPaymentMethod({
                type: 'card',
                card: cardNumber, // We only need to pass the cardNumber element
            });

            if (error) {
                cardErrors.textContent = error.message;
                submitButton.disabled = false;
                spinner.classList.add('hidden');
                buttonText.classList.remove('hidden');
            } else {
                // 7. Set the hidden input's value to the PaymentMethod ID
                document.getElementById('payment_method_id').value = paymentMethod.id;

                // 8. Submit the form to your backend
                form.submit();
            }
        });
    </script>
</x-app-layout>
