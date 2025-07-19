<!-- resources/views/checkout/create.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="h3 font-semibold text-xl text-gray-800 leading-tight">
            Checkout
        </h2>
    </x-slot>

    <div class="container py-5">
        <form action="{{ route('customer.checkout.store') }}" method="POST">
            @csrf
            <div class="row">
                <!-- Left Column: Shipping Details -->
                <div class="col-md-7">
                    <h4>Shipping Address</h4>
                    <hr>
                    <div class="mb-3">
                        <label for="shipping_address" class="form-label">Address</label>
                        <input class="auth-input @error('shipping_address') is-invalid @enderror" type="text" class="form-control" id="shipping_address" name="shipping_address" required>
                        @error('shipping_address')
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">City</label>
                            <input class="auth-input  @error('city') is-invalid @enderror" type="text" class="form-control" id="city" name="city" required>
                            @error('city')
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input class="auth-input  @error('phone_number') is-invalid @enderror" type="tel" class="form-control" id="phone_number" name="phone_number" required>
                        @error('phone_number')
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror
                    </div>
                </div>

                <!-- Right Column: Order Summary -->
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header">
                            <h4>Order Summary</h4>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @foreach($cartItems as $item)
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>{{ $item->product->name }} (x{{ $item->quantity }})</span>
                                    <strong>UGX {{ number_format($item->product->unit_price * $item->quantity, 0) }}</strong>
                                </li>
                                @endforeach
                                <li class="list-group-item d-flex justify-content-between bg-light">
                                    <h5 class="mb-0">Grand Total</h5>
                                    <h5 class="mb-0">UGX {{ number_format($subtotal, 0) }}</h5>
                                </li>
                            </ul>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success w-100">Place Order</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
