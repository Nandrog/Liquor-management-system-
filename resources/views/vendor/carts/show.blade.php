<!-- resources/views/vendor/carts/show.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="h3 font-semibold text-xl text-gray-800 leading-tight">
            View Customer Cart
        </h2>
    </x-slot>

    <div class="container py-5">
        {{-- Display success or error messages --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Customer Search Form Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h4>Customer Lookup</h4>
            </div>
            <div class="card-body">
                <p class="card-text text-muted">Enter a customer's name or email address to view their current shopping cart.</p>
                <form action="{{ route('vendor.carts.lookup') }}" method="GET" class="d-flex">
                    <input type="text" name="search_query" class="form-control me-2" placeholder="e.g., john.doe@example.com or John Doe" value="{{ request('search_query') }}" required>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
        </div>

        <!-- Customer Cart Details Card (only shown if a customer is found) -->
        @if ($customer)
            <div class="card">
                <div class="card-header">
                    <h4>Shopping Cart for: <span class="fw-bold">{{ $customer->name }} ({{ $customer->email }})</span></h4>
                </div>
                <div class="card-body">
                    @if ($cartItems->isEmpty())
                        <div class="text-center py-4">
                            <i class="bi bi-cart-x" style="font-size: 3rem;"></i>
                            <p class="mt-2">This customer's cart is currently empty.</p>
                        </div>
                    @else
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 15%;">Product</th>
                                    <th style="width: 55%;"></th>
                                    <th style="width: 15%;">Price</th>
                                    <th style="width: 15%;">Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cartItems as $item)
                                    @php
                                        // Determine the correct price (vendor's retail_price or product's base price)
                                        $vendorProduct = $item->product->vendorProducts->where('vendor_id', Auth::user()->vendor->id)->first();
                                        $price = $vendorProduct->retail_price ?? $item->product->unit_price;
                                    @endphp
                                    <tr>
                                        <td>
                                            <img src="{{ asset($item->product->image_url) }}" width="80" class="img-fluid" alt="{{ $item->product->name }}">
                                        </td>
                                        <td>{{ $item->product->name }}</td>
                                        <td>UGX {{ number_format($price, 0) }}</td>
                                        <td><strong>{{ $item->quantity }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
