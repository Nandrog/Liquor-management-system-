<x-app-layout>
    <x-slot name="header">
        <h2 class="h3 font-semibold text-xl text-gray-800 leading-tight">
            Create Purchase Order
        </h2>
    </x-slot>

    <div class="container py-5">
        <div class="card">
            <div class="card-header">
                <h4>Place a New Stock Order</h4>
                <p class="text-muted mb-0">Enter the quantity you wish to order for each product from the master catalog.</p>
            </div>
            <div class="card-body">
                <form action="{{ route('vendor.orders.store') }}" method="POST">
                    @csrf

                    @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 10%;">Image</th>
                                    <th>Product Name</th>
                                    <th>SKU</th>
                                    <th>Unit Cost</th>
                                    <th style="width: 20%;">Order Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Loop through the master catalog of all finished goods --}}
                                @forelse ($finishedGoods as $product)
                                    @php
                                        // Check if the vendor already sells this product to add a visual cue
                                        $alreadyAssigned = in_array($product->id, $existingProductIds);
                                    @endphp
                                    <tr>
                                        <td>
                                            <img src="{{ asset('images/' . $product->image_filename) }}" class="img-fluid rounded" style="width: 60px; height: 60px; object-fit: contain;" alt="{{ $product->name }}">
                                        </td>
                                        <td>
                                            <strong>{{ $product->name }}</strong>
                                            @if($alreadyAssigned)
                                                <br><span class="badge bg-success">Already In Your Catalog</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="font-monospace text-muted">{{ $product->sku ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <strong>UGX {{ number_format($product->unit_price, 0) }}</strong>
                                        </td>
                                        <td>
                                            {{-- A standard quantity input --}}
                                            <input type="number"
                                                   name="products[{{ $product->id }}][quantity]"
                                                   value="{{ old('products.'.$product->id.'.quantity', 0) }}"
                                                   min="0"
                                                   class="form-control"
                                                   placeholder="0">
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <p>There are no products available in the master catalog at this time.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="text-end mt-4">
                        <a href="{{ route('vendor.orders.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-lg">Review Purchase Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
