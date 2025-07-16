<x-app-layout>
    {{-- Page Title --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h3 font-semibold text-xl text-gray-800 leading-tight mb-0">
                {{ $pageTitle }}
            </h2>
            <a href="{{ route('supplier.orders.delivery') }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-sync-alt fa-sm"></i> {{ __('Refresh') }}
            </a>
        </div>
    </x-slot>
    <div class="container-fluid mt-n3">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Order #</th>
                            <th scope="col">Order Date</th>
                            <th scope="col">Items</th>
                            <th scope="col">Total Amount</th>
                            <th scope="col" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td><strong>{{ $order->order_number ?? $order->id }}</strong></td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>{{ $order->orderItems?->count() ?? 0 }}</td>
                                <td>UGX{{ number_format($order->total_amount, 2) }}</td>
                                <td class="text-center">
                                    <form action="{{ route('supplier.orders.markAsDelivered', $order) }}" method="POST" onsubmit="return confirm('Confirm that this order has been delivered?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success" title="Mark as Delivered">
                                        <i class="fas fa-check-circle"></i> Mark as Delivered
                                    </button>
                                </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-box-open fa-2x text-muted mb-2"></i>
                                    <p class="mb-0">No orders are awaiting delivery.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination Links --}}
    @if ($orders->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @endif

</x-app-layout>
