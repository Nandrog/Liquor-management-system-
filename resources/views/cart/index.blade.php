<x-app-layout>
    <x-slot name="header">
        <h2 class="h3 font-semibold text-xl text-gray-800 leading-tight">
            Your Shopping Cart
        </h2>
    </x-slot>

    <div class="container py-5">
        {{-- Display success or error messages from the session --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Check if the cart is empty using the isEmpty() method on the collection --}}
        @if($cartItems->isEmpty())
            <div class="text-center">
                <i class="bi bi-cart-x" style="font-size: 5rem; color: #6c757d;"></i>
                <h3 class="mt-3">Your cart is empty.</h3>
                <p class="text-muted">Looks like you haven't added any spirits yet.</p>
                <a href="{{ route('storefront.index') }}" class="btn btn-primary mt-3">Continue Shopping</a>
            </div>
        @else
            {{-- A single, valid form for updating all quantities at once --}}
            <form action="{{ route('cart.update') }}" method="POST">
                @csrf
                @method('PATCH')
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 15%;">Product</th>
                            <th scope="col" style="width: 35%;"></th>
                            <th scope="col" style="width: 15%;">Price</th>
                            <th scope="col" style="width: 15%;">Quantity</th>
                            <th scope="col" style="width: 15%;" class="text-end">Subtotal</th>
                            <th scope="col" style="width: 5%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cartItems as $item)
                            <tr id="cart-row-{{ $item->id }}">
                                <td>
                                    <img src="{{ asset($item->product->image_url) }}" width="100" height="100" class="img-fluid" style="object-fit: contain;" alt="{{ $item->product->name }}">
                                </td>
                                <td>{{ $item->product->name }}</td>
                                <td>UGX {{ number_format($item->product->unit_price, 0) }}</td>
                                <td>
                                    <input type="number" name="quantities[{{ $item->id }}]" value="{{ $item->quantity }}" class="form-control" style="width: 70px;" min="0">
                                </td>
                                <td class="text-end">UGX {{ number_format($item->product->unit_price * $item->quantity, 0) }}</td>
                                <td>
                                    {{-- MODIFIED: This is now a button, not a form. It will be handled by JavaScript. --}}
                                    <button type="button" class="btn btn-sm btn-outline-danger js-remove-item-btn"
                                            title="Remove item"
                                            data-cart-id="{{ $item->id }}"
                                            data-action-url="{{ route('cart.remove') }}">
                                        {{-- MODIFIED: Using a better icon (Bootstrap Icons trash can) --}}
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <button type="submit" class="btn btn-secondary">Update Cart</button>
                    <div class="text-end">
                        <h4>Total: <span class="fw-bold">UGX {{ number_format($subtotal, 0) }}</span></h4>
                    </div>
                </div>
            </form>

            <div class="text-end mt-4">
                 <a href="{{ route('customer.checkout.create') }}" class="btn btn-lg btn-success">Proceed to Checkout</a>
            </div>
        @endif
    </div>

    {{-- NEW: Pushing a script to handle the remove button clicks --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Find all remove buttons and add a click listener
            document.querySelectorAll('.js-remove-item-btn').forEach(button => {
                button.addEventListener('click', function (event) {
                    event.preventDefault(); // Prevent any default button action

                    const cartId = this.dataset.cartId;
                    const actionUrl = this.dataset.actionUrl;
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    if (confirm('Are you sure you want to remove this item from your cart?')) {
                        fetch(actionUrl, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ cart_id: cartId })
                        })
                        .then(response => {
                            if (response.ok) {
                                // If successful, remove the table row from the page for instant feedback
                                document.getElementById(`cart-row-${cartId}`).remove();
                                // You could optionally add logic here to refresh the total price
                                alert('Item removed successfully.');
                                // If the cart becomes empty, reload the page to show the "empty cart" message
                                if (document.querySelectorAll('.js-remove-item-btn').length === 1) {
                                    window.location.reload();
                                }
                            } else {
                                throw new Error('Failed to remove item.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while removing the item.');
                        });
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
