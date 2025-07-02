<x-app-layout>
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2">Factory Stock Levels</h1>
            <p class="text-muted mb-0">Displaying stock for your assigned warehouse: <span class="fw-bold">{{ $warehouse->name }}</span></p>
        </div>
        {{-- Manufacturers might not have permission to adjust stock directly --}}
        {{-- <a href="#" class="btn btn-primary">Request Stock Movement</a> --}}
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Product Name</th>
                            <th scope="col">SKU</th>
                            <th scope="col" class="text-end">Quantity On Hand</th>
                            <th scope="col" class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stockLevels as $stockLevel)
                            <tr>
                                <td>{{ $stockLevel->product->name }}</td>
                                <td><span class="font-monospace">{{ $stockLevel->product->sku }}</span></td>
                                <td class="text-end fw-bold">{{ $stockLevel->quantity }} {{ $stockLevel->product->unit_of_measure }}s</td>
                                <td class="text-center">
                                    @if($stockLevel->quantity <= $stockLevel->product->reorder_level)
                                        <span class="badge bg-danger-subtle text-danger-emphasis rounded-pill">Low Stock</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success-emphasis rounded-pill">In Stock</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">No stock records found for your assigned warehouse.</td>
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