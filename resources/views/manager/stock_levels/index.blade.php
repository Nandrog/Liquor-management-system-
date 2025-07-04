<x-app-layout>
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Stock Levels</h1>
        {{-- You can add an "Adjust Stock" button here later --}}
        <a href="#" class="btn btn-primary">Adjust Stock</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Product Name</th>
                            <th scope="col">SKU</th>
                            <th scope="col">Warehouse</th>
                            <th scope="col" class="text-end">Quantity On Hand</th>
                            <th scope="col" class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stockLevels as $stockLevel)
                            <tr>
                                <td>{{ $stockLevel->product->name }}</td>
                                <td><span class="font-monospace">{{ $stockLevel->product->sku }}</span></td>
                                <td>{{ $stockLevel->warehouse->name }}</td>
                                <td class="text-end fw-bold">{{ $stockLevel->quantity }} {{ $stockLevel->product->unit_of_measure }}s</td>
                                <td class="text-center">
                                    {{-- BEST PRACTICE: Conditional styling for low stock --}}
                                    @if($stockLevel->quantity <= $stockLevel->product->reorder_level)
                                        <span class="badge bg-danger-subtle text-danger-emphasis rounded-pill">Low Stock</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success-emphasis rounded-pill">In Stock</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">No stock records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Render the pagination links --}}
            <div class="mt-3">
                {{ $stockLevels->links() }}
            </div>
        </div>
    </div>
</x-app-layout>