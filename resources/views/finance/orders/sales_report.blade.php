<x-app-layout>
    {{-- Page Header --}}
    <div class="mb-4">
        <h1 class="h2">Sales Orders Financial Report</h1>
        <p class="text-muted">A summary of revenue from Vendors and direct Customers.</p>
    </div>

    {{-- 1. Summary Cards Section --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body text-center">
                    <h6 class="card-title text-uppercase">Vendor Sales (Paid)</h6>
                    <p class="card-text h2">Sh. {{ number_format($vendorPaidTotal, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info">
                <div class="card-body text-center">
                    <h6 class="card-title text-uppercase">Customer Sales (Paid)</h6>
                    <p class="card-text h2">Sh. {{ number_format($customerPaidTotal, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body text-center">
                    <h6 class="card-title text-uppercase">Grand Total Sales (Paid)</h6>
                    <p class="card-text h2">Sh. {{ number_format($grandTotalSales, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Vendor Orders Section --}}
    <h3 class="mb-3">Vendor Orders</h3>
    <div class="accordion mb-5" id="vendorOrdersAccordion">
        @include('finance.orders.partials.status-accordion', ['ordersByStatus' => $vendorOrdersByStatus, 'type' => 'vendor'])
    </div>

    {{-- 3. Customer Orders Section --}}
    <h3 class="mb-3">Customer Orders</h3>
    <div class="accordion" id="customerOrdersAccordion">
        @include('finance.orders.partials.status-accordion', ['ordersByStatus' => $customerOrdersByStatus, 'type' => 'customer'])
    </div>

</x-app-layout>