{{-- File: resources/views/manufacturer/orders/paid.blade.php --}}
<x-app-layout>
    {{-- Use the $pageTitle variable passed from the controller --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h3 font-semibold text-xl text-gray-800 leading-tight mb-0">
                {{ $pageTitle }}
            </h2>
            <a href="{{ route('supplier.orders.paid') }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-sync-alt fa-sm"></i> {{ __('Refresh') }}
            </a>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .order-card .card-header { background-color: #d4edda; } /* Light green for success/paid */
        .order-card .card-footer { background-color: #f8f9fa; }
        .icon-text { display: flex; align-items: center; gap: 0.5rem; }
        .icon-text .fas { width: 1.2em; text-align: center; }
    </style>
    @endpush

    <a href="{{ route('supplier.orders.delivery') }}"><button type="button" class="btn btn-primary btn-sm">
                    <i class="fas fa-shipping-fast me-1"></i><b>View orders Ready for Delivery</b>
                </button></a>
    {{-- The controller passes the collection as 'orders', so we loop over that --}}
    @forelse($orders as $order)
        <div class="card shadow-sm mb-4 order-card">
            <div class="card-header py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    Order #{{ $order->order_number ?? $order->id }}
                </h6>
                <div class="mt-2 mt-md-0">
                    {{-- Changed the badge to "Paid" with a success color --}}
                    <span class="badge bg-success text-white fs-6">
                        <i class="fas fa-check-circle me-1"></i> Paid
                    </span>
                </div>
            </div>

            <div class="card-body">
                {{-- This part can remain the same, showing supplier and order details --}}
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4 mb-lg-0 border-end-lg">
                        <h5 class="mb-3">Order Details</h5>
                        <p class="icon-text mb-2"><i class="fas fa-user-tie text-muted"></i> <strong>Supplier:</strong> {{ $order->user->name ?? 'N/A' }}</p>
                        <p class="icon-text mb-2"><i class="fas fa-map-marker-alt text-muted"></i> <strong>Shipping Address:</strong> {{ $order->shipping_address ?? 'N/A' }}</p>
                        <p class="icon-text mb-2"><i class="fas fa-calendar-alt text-muted"></i> <strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                        <p class="icon-text mb-2"><i class="fas fa-dollar-sign text-muted"></i> <strong>Paid On:</strong> {{ $order->paid_at?->format('M d, Y') ?? 'N/A' }}</p>
                    </div>

                    <div class="col-lg-8 col-md-6">
                        {{-- You can add a table of products here later --}}
                        <p class="text-muted">Product details would go here.</p>
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-end gap-2">
                <a href="{{ route('supplier.orders.show', $order) }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-eye me-1"></i> View Details
                </a>
                {{-- A paid order might need to be shipped next --}}
                {{-- THIS IS THE NEW, CORRECT CODE --}}
                <form action="{{ route('supplier.orders.markAsDelivering', $order) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-shipping-fast me-1"></i> Mark as Delivering
                    </button>
                </form>
            </div>
        </div>

    @empty
        {{-- Changed the empty state message --}}
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-file-invoice-dollar fa-4x text-muted mb-3"></i>
                <h4>No Paid Orders</h4>
                <p class="text-muted">There are currently no orders awaiting Delivery.</p>
            </div>
        </div>
    @endforelse

    {{-- Pagination links --}}
    @if ($orders->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @endif

</x-app-layout>
