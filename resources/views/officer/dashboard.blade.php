<x-app-layout>
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Home</h1>
    </div>

    <h2 class="h4 mb-3">Overview</h2>

    {{-- Dashboard Cards Grid --}}
    <div class="row">
        <div class="col-md-4 mb-4">
            <x-dashboard-card title="Chats" value="{{ $stats['newChats'] }}" description="New Chats" icon="bi-chat-dots" />
        </div>
        <div class="col-md-4 mb-4">
            <x-dashboard-card title="Out of Stock" value="{{ $stats['outOfStock'] }}" description="Products" icon="bi-box-seam" />
        </div>
        <div class="col-md-4 mb-4">
            <x-dashboard-card title="View Contract" value="2 years" description="remaining" icon="bi-file-earmark-text" />
        </div>
        <div class="col-md-4 mb-4">
            <x-dashboard-card title="Orders" value="{{ $stats['unfulfilledOrders'] }}" description="Unfulfilled" icon="bi-arrow-repeat" />
        </div>
        <div class="col-md-4 mb-4">
            <x-dashboard-card title="Sales total" value="{{ $stats['salesTotal'] }}" description="" icon="bi-bar-chart-line" />
        </div>
        <div class="col-md-4 mb-4">
            <x-dashboard-card title="Trend Predictions" value="Rush hour" description="" icon="bi-wallet2" />
        </div>
    </div>
</x-app-layout>