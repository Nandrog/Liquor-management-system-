<x-app-layout>
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2">Factory Stock Levels</h1>
            <p class="text-muted mb-0">Displaying stock for your assigned warehouse: <span class="fw-bold">{{ $warehouse->name }}</span></p>
        </div>
    </div>

    {{-- 1. FINISHED GOODS TABLE --}}
    <div class="card shadow-sm mb-5">
        <div class="card-header">
            <h5 class="mb-0">Finished Goods</h5>
        </div>
        <div class="card-body">
            {{-- Check if there are any finished goods to display --}}
            @if($finishedGoods->isNotEmpty())
                {{-- Reuse the same table partial we used for the manager --}}
                @include('manager.stock_levels.partials.stock-table', ['stockLevels' => $finishedGoods])
            @else
                <p class="text-center text-muted mb-0 py-3">No finished goods in stock at this warehouse.</p>
            @endif
        </div>
    </div>

    {{-- 2. RAW MATERIALS TABLE --}}
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Raw Materials</h5>
        </div>
        <div class="card-body">
            @if($rawMaterials->isNotEmpty())
                @include('manager.stock_levels.partials.stock-table', ['stockLevels' => $rawMaterials])
            @else
                <p class="text-center text-muted mb-0 py-3">No raw materials in stock at this warehouse.</p>
            @endif
        </div>
    </div>

</x-app-layout>