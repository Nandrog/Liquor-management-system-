<x-app-layout>
    <x-slot name="header">
        <h2 class="h3 font-semibold text-xl text-gray-800 leading-tight">
            Your Shopping Cart
        </h2>
    </x-slot>

    <div class="container py-5">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(empty($cart))
            <div class="text-center">
                <i class="bi bi-cart-x" style="font-size: 5rem; color: #6c757d;"></i>
                <h3 class="mt-3">Your cart is empty.</h3>
                <p class="text-muted">Looks like you haven't added any spirits yet.</p>
                <a href="{{ route('storefront.cellar') }}" class="btn btn-primary mt-3">Continue Shopping</a>
            </div>
        @else
            <form action="{{ route('cart.update') }}" method="POST">
                @csrf
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
                        @php $total = 0 @endphp
                        @foreach($cart as $id => $details)
                            @php $total += $details['price'] * $details['quantity'] @endphp
                            <tr>
                                <td>
                                    <img src="{{ $details['image_url'] }}" width="100" height="100" class="img-fluid" style="object-fit: contain;" alt="{{ $details['name'] }}">
                                </td>
                                <td>{{ $details['name'] }}</td>
                                <td>UGX {{ number_format($details['price'], 0) }}</td>
                                <td>
                                    <input type="number" name="quantities[{{ $id }}]" value="{{ $details['quantity'] }}" class="form-control" style="width: 70px;" min="1">
                                </td>
                                <td class="text-end">UGX {{ number_format($details['price'] * $details['quantity'], 0) }}</td>
                                <td>
                                    {{-- Form for removing a single item --}}
                                    <form action="{{ route('cart.remove') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $id }}">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">×</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <button type="submit" class="btn btn-secondary">Update Cart</button>
                    <div class="text-end">
                        <h4>Total: <span class="fw-bold">UGX {{ number_format($total, 0) }}</span></h4>
                    </div>
                </div>
            </form>

            <div class="text-end mt-4">
                 {{-- This would link to your checkout process --}}
                <a href="#" class="btn btn-lg btn-success">Proceed to Checkout</a>
            </div>
        @endif
    </div>
</x-app-layout>
