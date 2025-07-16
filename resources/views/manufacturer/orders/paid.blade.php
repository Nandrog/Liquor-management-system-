<x-app-layout>
    {{-- Use the $pageTitle variable passed from the controller --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h3 font-semibold text-xl text-gray-800 leading-tight mb-0">
                {{ $pageTitle }}
            </h2>
            <a href="{{ route('manufacturer.orders.paid') }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-sync-alt fa-sm"></i> {{ __('Refresh') }}
            </a>
        </div>
    </x-slot>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Order #</th>
                            <th scope="col">Supplier</th>
                            <th scope="col">Order Date</th>
                            <th scope="col">Paid On</th>
                            <th scope="col">Items</th>
                            <th scope="col">Total Amount</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- The controller passes 'orders', so we loop through them --}}
                        @forelse ($orders as $order)
                            <tr>
                                <td><strong>{{ $order->order_number ?? $order->id }}</strong></td>
                                <td>{{ $order->user->name ?? 'N/A' }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>{{ $order->paid_at?->format('M d, Y') ?? now()->format('M d, Y') }}</td>
                                <td>{{ $order->orderItems->count() }}</td>
                                <td>${{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <a href="{{ route('manufacturer.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-eye fa-sm"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            {{-- This row will show if the $orders collection is empty --}}
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-file-invoice-dollar fa-2x text-muted mb-2"></i>
                                    <p class="mb-0">No paid orders found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination links will appear if there are more orders than the page limit --}}
    @if ($orders->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @endif

</x-app-layout>
