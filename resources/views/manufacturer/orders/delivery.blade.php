{{-- File: resources/views/manufacturer/orders/delivery.blade.php --}}
<x-app-layout>
    {{-- ... header and styles are fine ... --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h3 font-semibold text-xl text-gray-800 leading-tight mb-0">
                {{ $pageTitle }}
            </h2>
            <a href="{{ route('manufacturer.orders.delivery') }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-sync-alt fa-sm"></i> {{ __('Refresh') }}
            </a>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .order-card .card-header { background-color: #e3f2fd; }
        .order-card .table-responsive { max-height: 200px; }
        .order-card .card-footer { background-color: #f8f9fa; }
        .icon-text { display: flex; align-items: center; gap: 0.5rem; }
        .icon-text .fas { width: 1.2em; text-align: center; }
    </style>
    @endpush

    @forelse($orders as $order)
        <div class="card shadow-sm mb-4 order-card">
            {{-- ... card header is fine ... --}}
            <div class="card-header py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    Order #{{ $order->order_number ?? $order->id }}
                </h6>
                <div class="mt-2 mt-md-0">
                    <span class="badge bg-info text-white fs-6">
                        <i class="fas fa-truck me-1"></i> Delivering
                    </span>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4 mb-lg-0 border-end-lg">
                        <h5 class="mb-3">Shipment Details</h5>

                        {{-- CORRECTED THIS SECTION --}}
                        <p class="icon-text mb-2"><i class="fas fa-user-tie text-muted"></i> <strong>Supplier:</strong> {{ $order->user->name ?? 'N/A' }}</p>

                        <p class="icon-text mb-2"><i class="fas fa-map-marker-alt text-muted"></i> <strong>Shipping Address:</strong> {{ $order->shipping_address ?? 'N/A' }}</p>
                        <p class="icon-text mb-2"><i class="fas fa-calendar-alt text-muted"></i> <strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                        <p class="icon-text mb-2"><i class="fas fa-shipping-fast text-muted"></i> <strong>Shipped On:</strong> {{ $order->delivering_at?->format('M d, Y') ?? 'N/A' }}</p>
                    </div>

                    <div class="col-lg-8 col-md-6">
                         {{-- ... a all other details from the previous example (tracking, products table) ... --}}
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-end gap-2">
                {{-- UNCOMMENTED THIS LINK TO FIX THE 404 ERROR --}}
                <a href="{{ route('manufacturer.orders.show', $order) }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-eye me-1"></i> View Details
                </a>
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#markDeliveredModal-{{ $order->id }}">
                    <i class="fas fa-check-circle me-1"></i> Mark as Delivered
                </button>
            </div>
        </div>

        {{-- ... The rest of the file (modal, empty state, pagination) is correct ... --}}
        <!-- Mark as Delivered Confirmation Modal -->
        <div class="modal fade" id="markDeliveredModal-{{ $order->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Delivery</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to mark Order #<strong>{{ $order->order_number ?? $order->id }}</strong> as 'Delivered'?
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('manufacturer.orders.markAsDelivered', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Yes, Mark as Delivered</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                <h4>No Orders In Delivery</h4>
                <p class="text-muted">There are currently no orders in transit.</p>
            </div>
        </div>
    @endforelse

    @if ($orders->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @endif

</x-app-layout>
