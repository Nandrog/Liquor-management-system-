<x-app-layout>
    {{-- Page Header --}}
    <div class="mb-4">
        <h1 class="h2">Item Valuation</h1>
        <p class="text-muted">View and manage the monetary value of all inventory items.</p>
    </div>

    {{-- Session Feedback for price updates --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">Please check the highlighted fields for errors.</div>
    @endif

    {{-- THIS IS THE MISSING SECTION FOR THE SUMMARY CARDS --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Finished Goods Value</h5>
                    <p class="card-text h3">Sh. {{ number_format($finishedGoodsValue, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Raw Materials Expenditure</h5>
                    <p class="card-text h3">Sh. {{ number_format($rawMaterialsValue, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Finished Goods Table --}}
    <div class="mb-5">
        <h3 class="mb-3">Finished Goods</h3>
        @include('finance.items.partials.valuation-table', ['products' => $finishedGoods])
    </div>

    {{-- Raw Materials Table --}}
    <div>
        <h3 class="mb-3">Raw Materials</h3>
        @include('finance.items.partials.valuation-table', ['products' => $rawMaterials])
    </div>
</x-app-layout>