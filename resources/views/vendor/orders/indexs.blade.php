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
                                        <a href="{{ route('vendor.orders.show', $order) }}" class="fw-bold text-decoration-none">{{ $order->order_number }}</a>
                                    </td>
                                    <td>
                                        {{-- We can access the customer's name via the 'user' relationship --}}
                                        {{ $order->user->name }}<br>
                                        <small class="text-muted">{{ $order->user->email }}</small>
                                    </td>
                                    <td>
                                        {{ $order->created_at->format('M d, Y') }}
                                    </td>
                                    <td>
                                        UGX {{ number_format($order->total_amount, 0) }}
                                    </td>
                                    <td>
                                        {{-- A styled badge for the order status --}}
                                        <span class="badge
                                            {{-- MODIFICATION 1: Use the full Enum case for safer comparison --}}
                                            @switch($order->status)
                                                @case(App\Enums\OrderStatus::PENDING) bg-warning text-dark @break
                                                @case(App\Enums\OrderStatus::PROCESSING) bg-info text-dark @break
                                                @case(App\Enums\OrderStatus::IN_TRANSIT) bg-primary @break
                                                @case(App\Enums\OrderStatus::DELIVERED) bg-success @break
                                                @case(App\Enums\OrderStatus::CANCELLED) bg-danger @break
                                                @default bg-secondary
                                            @endswitch
                                        ">
                                            {{-- MODIFICATION 2: Access the string ->value of the Enum object --}}
                                            {{ Str::title($order->status->value) }}
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
                {{-- Render pagination links --}}
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
