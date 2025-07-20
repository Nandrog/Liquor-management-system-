@props(['ordersByStatus', 'type'])

@forelse ($ordersByStatus as $status => $orders)
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $type }}-{{ \Illuminate\Support\Str::slug($status) }}">
                <div class="d-flex justify-content-between w-100 me-3 align-items-center">
                    <span class="fw-bold text-uppercase">{{ \App\Enums\OrderStatus::tryFrom($status)?->label() ?? str_replace('_', ' ', $status) }}</span>
                    <span class="badge bg-secondary rounded-pill">{{ $orders->count() }} Orders</span>
                </div>
            </button>
        </h2>
        <div id="collapse-{{ $type }}-{{ \Illuminate\Support\Str::slug($status) }}" class="accordion-collapse collapse" data-bs-parent="#{{ $type }}OrdersAccordion">
            <div class="accordion-body">
                <table class="table table-sm table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>{{ ucfirst($type) }} Name</th>
                            <th class="text-end">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $order->order_number ?? $order->id }}</td>
                                <td>{{ $order->created_at->format('d M, Y') }}</td>
                                <td>
                                    @if($type === 'vendor')
                                        {{ optional($order->vendor)->name }}
                                    @else
                                        {{ optional($order->customer->user)->username }}
                                    @endif
                                </td>
                                <td class="text-end">Sh. {{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@empty
    <div class="card">
        <div class="card-body text-center text-muted">
            No orders found with these statuses.
        </div>
    </div>
@endforelse