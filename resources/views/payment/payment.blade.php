<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Stripe Payment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if(session('message'))
                    <div class="mb-4 text-green-600">
                        {{ session('message') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 text-red-600">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form id="payment-form" method="POST" action="{{ route('stripe.charge') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="amount" class="block text-sm font-medium text-gray-700">Amount (UGX)</label>
                        <input
                            type="number"
                            name="amount"
                            id="amount"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                            required
                            min="1"
                            value="{{ request('amount', 0) }}"
                            readonly
                        >
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Card Number</label>
                        <div id="card-number-element" class="p-3 border border-gray-300 rounded-md"></div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Expiration Date</label>
                        <div id="card-expiry-element" class="p-3 border border-gray-300 rounded-md"></div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">CVC</label>
                        <div id="card-cvc-element" class="p-3 border border-gray-300 rounded-md"></div>
                    </div>

                    <div id="card-errors" class="text-red-500 mt-2 text-sm" role="alert"></div>

                    <input type="hidden" name="payment_method_id" id="payment_method_id">

                    <div class="flex justify-end">
                        <button type="submit" id="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            Pay
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ config('services.stripe.key') }}');
        const elements = stripe.elements();

        const cardNumber = elements.create('cardNumber');
        cardNumber.mount('#card-number-element');

        const cardExpiry = elements.create('cardExpiry');
        cardExpiry.mount('#card-expiry-element');

        const cardCvc = elements.create('cardCvc');
        cardCvc.mount('#card-cvc-element');

        const form = document.getElementById('payment-form');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const { paymentMethod, error } = await stripe.createPaymentMethod({
                type: 'card',
                card: cardNumber,
            });

            if (error) {
                document.getElementById('card-errors').textContent = error.message;
            } else {
                document.getElementById('payment_method_id').value = paymentMethod.id;
                form.submit();
            }
        });
    </script>
</x-app-layout>
