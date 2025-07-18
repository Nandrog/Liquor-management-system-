<x-app-layout>
    <x-slot name="header">
        <h2 class="h3 font-semibold text-xl text-gray-800 leading-tight">
            Manage Your Product Prices
        </h2>
    </x-slot>

    <div class="container py-5">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h4>Your Product Catalog</h4>
            </div>
            <div class="card-body">
                {{-- This single form will submit all price changes at once. --}}
                <form action="{{ route('vendor.products.bulk-update') }}" method="POST">
                    @csrf
                    @method('PATCH') {{-- PATCH is appropriate for bulk updates --}}

                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th style="width: 30%;">Your Retail Price (UGX)</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- We loop through the VendorProduct models, not the base Product models --}}
                            @forelse($vendorProducts as $vendorProduct)
                                <tr>
                                    <td>
                                        {{-- Access the product's name via the relationship --}}
                                        <strong>{{ $vendorProduct->product->name }}</strong>
                                        <br>
                                        <small class="text-muted">Base Price: UGX {{ number_format($vendorProduct->product->unit_price, 0) }}</small>
                                    </td>
                                    <td>
                                        {{-- The name is an array input, keyed by the VendorProduct ID --}}
                                        <div class="input-group">
                                            <span class="input-group-text">UGX</span>
                                            <input type="number"
                                                   name="products[{{ $vendorProduct->id }}][retail_price]"
                                                   value="{{ old('products.'.$vendorProduct->id.'.retail_price', $vendorProduct->retail_price) }}"
                                                   step="0.01"
                                                   min="0"
                                                   class="form-control"
                                                   required>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center py-4">
                                        <p>You have not been assigned any products to sell yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if($vendorProducts->isNotEmpty())
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Update All Prices</button>
                        </div>
                    @endif
                </form>
            </div>
            <div class="card-footer">
                {{-- Add pagination links --}}
                {{ $vendorProducts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
