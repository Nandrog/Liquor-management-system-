@props(['products'])

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Product Name</th>
                        <th>SKU</th>
                        <th>Supplier/Vendor</th>
                        <th class="text-end">Unit Price</th>
                        <th class="text-end">Total Stock</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products->groupBy('name') as $productName => $productGroup)
                        @foreach ($productGroup as $index => $product)
                            <tr>
                                @if ($loop->first)
                                    {{-- Show the product name only on the first row of the group --}}
                                    <td rowspan="{{ $productGroup->count() }}">{{ $productName }}</td>
                                @endif
                                
                                <td><span class="font-monospace">{{ $product->sku }}</span></td>
                                
                                {{-- Display Supplier or Vendor Name --}}
                                <td>
                                    @if($product->type === 'raw_material' && $product->supplier)
                                        <span class="badge bg-info-subtle text-info-emphasis">Supplier</span> {{ $product->supplier->username }}
                                    @elseif($product->type === 'finished_good' && $product->vendor)
                                        <span class="badge bg-secondary-subtle text-secondary-emphasis">Vendor</span> {{ $product->vendor->shop_name }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                
                                <td class="text-end">Sh. {{ number_format($product->unit_price, 2) }}</td>
                                
                                {{-- Calculate and display total stock --}}
                                <td class="text-end fw-bold">
                                    {{ $product->stockLevels->sum('quantity') }} 
                                    {{ $product->unit_of_measure }}s
                                </td>
                                
                                <td class="text-center">
                                    {{-- Links for CRUD actions --}}
                                    <a href="{{ route('manager.items.edit', $product->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    {{-- Delete requires a form for security --}}
                                    <form action="{{ route('manager.items.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No items found for this type.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>