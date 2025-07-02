@props(['products'])

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Product Name</th>
                        <th>SKU</th>
                        <th class="text-end">Total Stock</th>
                        <th class="text-end" style="width: 20%;">Unit Price (Sh.)</th>
                        <th class="text-end">Total Value (Sh.)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        @php
                            $totalQuantity = $product->stockLevels->sum('quantity');
                            $totalValue = $totalQuantity * $product->unit_price;
                        @endphp
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td><span class="font-monospace">{{ $product->sku }}</span></td>
                            <td class="text-end fw-bold">{{ $totalQuantity }} {{ $product->unit_of_measure }}s</td>
                            
                            {{-- INLINE EDIT FORM FOR PRICE --}}
                            <td class="text-end">
                                <form action="{{ route('finance.items.update_price', $product->id) }}" method="POST" class="d-flex align-items-center justify-content-end">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" step="0.01" name="unit_price" class="form-control form-control-sm me-2 @error('unit_price', 'update_price_'.$product->id) is-invalid @enderror" value="{{ old('unit_price', $product->unit_price) }}">
                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                            </td>

                            <td class="text-end fw-bold">{{ number_format($totalValue, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">No items found for this type.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>