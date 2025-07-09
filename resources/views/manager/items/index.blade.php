<x-app-layout>
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Item Master</h1>
        {{-- Link to the create page (which we'll build later) --}}
        <a href="{{ route('manager.items.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Create New Item
        </a>
    </div>

    {{-- Summary Value Cards --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Total Finished Goods Value</h5>
                    <p class="card-text h3">Sh. {{ number_format($finishedGoodsValue, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Total Raw Materials Expenditure</h5>
                    <p class="card-text h3">Sh. {{ number_format($rawMaterialsValue, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Use a Blade Component for the tables to keep this file clean --}}
    <div class="mb-5">
        <h3 class="mb-3">Finished Goods</h3>
        <x-items-table :products="$finishedGoods" />
    </div>

    <div>
        <h3 class="mb-3">Raw Materials</h3>
        <x-items-table :products="$rawMaterials" />
    </div>

</x-app-layout>