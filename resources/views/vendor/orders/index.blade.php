<x-app-layout>
    <x-slot name="header">
        <h2 class="h3 font-semibold text-xl text-gray-800 leading-tight">
            Your Vendor Orders
        </h2>
    </x-slot>

    <div class="container py-5">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-header">
                <h4>        Out-Going Orders</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>vendor Name</th>
                                <th>Status</th>
                                <th>Total Amount</th>
                                <th>Date Placed</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <td>
                                        @if (Route::has('vendor.orders.show'))
                                            <a href="{{ route('vendor.orders.show', $order) }}" class="fw-bold">{{ $order->order_number ?? 'N/A' }}</a>
                                        @else
                                            {{ $order->order_number ?? 'N/A' }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($order->user)
                                            {{ $order->user->name }}
                                        @else
                                            <span class="text-muted"><em>(Customer Not Found)</em></span>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- Using the corrected Enum value access with conditional styling --}}
                                        <span class="badge
                                            @if($order->status->value === 'delivered') bg-success
                                            @elseif($order->status->value === 'pending') bg-warning text-dark
                                            @elseif($order->status->value === 'processing') bg-info text-dark
                                            @elseif($order->status->value === 'in_transit') bg-primary
                                            @elseif($order->status->value === 'cancelled') bg-danger
                                            @else bg-secondary @endif">
                                            {{ Str::title($order->status->value) }}
                                        </span>
                                    </td>
                                    <td>UGX {{ number_format($order->total_amount, 0) }}</td>
                                    <td>
                                        {{ optional($order->created_at)->format('M d, Y') }}
                                    </td>
                                    <td class="text-end">
                                        @if (Route::has('vendor.orders.show'))
                                            {{-- THE FIX: The variable is now correctly named $order --}}
                                            <a href="{{ route('vendor.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">
                                                View Details
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <p>You have not placed any orders yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{-- Pagination links --}}
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
