<x-app-layout>
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Stock Levels by Warehouse</h1>
        
    </div>

    {{-- 1. FINISHED GOODS SECTION --}}
    <h3 class="mb-3">Finished Goods</h3>
    <div class="accordion mb-5" id="finishedGoodsAccordion">
        {{-- Loop through each warehouse that has finished goods --}}
        @forelse ($finishedGoodsByWarehouse as $warehouseName => $stockLevels)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading-fg-{{ Str::slug($warehouseName) }}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-fg-{{ Str::slug($warehouseName) }}">
                        <div class="d-flex justify-content-between w-100 me-3">
                            <span class="fw-bold">{{ $warehouseName }}</span>
                            <span class="badge bg-secondary rounded-pill">{{ $stockLevels->count() }} Item Types</span>
                        </div>
                    </button>
                </h2>
                <div id="collapse-fg-{{ Str::slug($warehouseName) }}" class="accordion-collapse collapse" data-bs-parent="#finishedGoodsAccordion">
                    <div class="accordion-body">
                        {{-- We can reuse our existing table structure here in a partial --}}
                        @include('manager.stock_levels.partials.stock-table', ['stockLevels' => $stockLevels])
                    </div>
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body text-center text-muted">No finished goods are currently in stock in any warehouse.</div>
            </div>
        @endforelse
    </div>

    {{-- 2. RAW MATERIALS SECTION --}}
    <h3 class="mb-3">Raw Materials</h3>
    <div class="accordion" id="rawMaterialsAccordion">
        {{-- Loop through each warehouse that has raw materials --}}
        @forelse ($rawMaterialsByWarehouse as $warehouseName => $stockLevels)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading-rm-{{ Str::slug($warehouseName) }}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-rm-{{ Str::slug($warehouseName) }}">
                        <div class="d-flex justify-content-between w-100 me-3">
                            <span class="fw-bold">{{ $warehouseName }}</span>
                            <span class="badge bg-secondary rounded-pill">{{ $stockLevels->count() }} Item Types</span>
                        </div>
                    </button>
                </h2>
                <div id="collapse-rm-{{ Str::slug($warehouseName) }}" class="accordion-collapse collapse" data-bs-parent="#rawMaterialsAccordion">
                    <div class="accordion-body">
                        @include('manager.stock_levels.partials.stock-table', ['stockLevels' => $stockLevels])
                    </div>
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body text-center text-muted">No raw materials are currently in stock in any warehouse.</div>
            </div>
        @endforelse
    </div>

</x-app-layout>