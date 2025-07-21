<x-app-layout>
    <x-slot name="header">
        <h2 class="h3 font-semibold text-xl text-gray-800 leading-tight">
            Your Customer Orders
        </h2>
    </x-slot>

    <div class="container py-5">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-header">
                <h4>Incoming Orders</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('vendor.orders.show', $order) }}" class="fw-bold text-decoration-none">{{ $order->order_number ?? $order->id }}</a>
                                    </td>
                                    <td>
                                        {{-- Using optional() is safer in case a user record is ever missing --}}
                                        {{ optional($order->user)->name }}<br>
                                        <small class="text-muted">{{ optional($order->user)->email }}</small>
                                    </td>
                                    <td>
                                        {{ $order->created_at->format('M d, Y') }}
                                    </td>
                                    <td>
                                        UGX {{ number_format($order->total_amount, 0) }}
                                    </td>
                                    <td>
                                        <span class="badge
                                            {{-- MODIFICATION 1: Comparing the Enum's ->value is more reliable --}}
                                            @switch($order->status->value)
                                                @case('pending') bg-warning text-dark @break
                                                @case('processing') bg-info text-dark @break
                                                @case('in_transit') bg-primary @break
                                                @case('delivered') bg-success @break
                                                @case('cancelled') bg-danger @break
                                                @default bg-secondary
                                            @endswitch
                                        ">
                                            {{-- MODIFICATION 2: Using Str::title on the ->value gives consistent output --}}
                                            {{ Str::title(str_replace('_', ' ', $order->status->value)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('vendor.orders.show', $order) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                            <i class="bi bi-eye-fill"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <p class="mb-0">You have no customer orders yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
