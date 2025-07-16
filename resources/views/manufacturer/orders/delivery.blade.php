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

    <div class="container-fluid"> {{-- Or just place it inside your main content area --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <h5 class="alert-heading">Please fix the following errors:</h5>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

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

                        <p class="icon-text mb-2"><i class="fas fa-map-marker-alt text-muted"></i> <strong>Shipping Address:</strong> {{ $order->shipping_address ?? 'W/H' }}</p>
                        <p class="icon-text mb-2"><i class="fas fa-calendar-alt text-muted"></i> <strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                        <p class="icon-text mb-2"><i class="fas fa-shipping-fast text-muted"></i> <strong>Shipped On:</strong> {{ $order->delivering_at?->format('M d, Y') ?? $order->created_at->format('M d, Y')}}</p>
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
<div class="modal fade" id="markDeliveredModal-{{ $order->id }}" tabindex="-1" aria-labelledby="markDeliveredModalLabel-{{ $order->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="markDeliveredModalLabel-{{ $order->id }}">
                                Confirm Delivery for Order #{{ $order->order_number ?? $order->id }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <form action="{{ route('manufacturer.orders.markAsDelivered', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="modal-body">
                                <p>You are about to mark this order as 'Delivered'. This action is irreversible and will update your inventory stock levels.</p>
                                <hr>
                                <div class="mb-3">
                                    <label for="warehouse_id-{{ $order->id }}" class="form-label">
                                        <strong><span class="text-danger">*</span> Select Destination Warehouse:</strong>
                                    </label>
                                    <select class="form-select" name="warehouse_id" id="warehouse_id-{{ $order->id }}" required>
                                        <option value="" disabled selected>-- Choose a warehouse --</option>
                                        @if(isset($warehouses) && $warehouses->count() > 0)
                                            @foreach($warehouses as $warehouse)
                                                {{-- THIS IS THE CRITICAL FIX --}}
                                                <option value="{{ $warehouse->warehouse_id }}">{{ $warehouse->name }} ({{ $warehouse->location }})</option>
                                            @endforeach
                                        @else
                                            <option value="" disabled>No warehouses found.</option>
                                        @endif
                                    </select>
                                    @error('warehouse_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="alert alert-warning small p-2" role="alert">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    Ensure you select the correct warehouse. Stock will be added to its inventory.
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Yes, Confirm Delivery
                                </button>
                            </div>

                        </form>
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
