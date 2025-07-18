<x-app-layout>
    {{-- Page Header --}}
    <div class="mb-4">
        <h1 class="h2">Supplier Orders Financial Report</h1>
        <p class="text-muted">A summary of all purchase orders grouped by their current financial status.</p>
    </div>

    <div class="accordion" id="statusAccordion">
        {{-- Loop through each status group (e.g., 'pending', 'confirmed') --}}
        @forelse ($ordersByStatus as $status => $orders)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading-{{ $status }}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $status }}">
                        <div class="d-flex justify-content-between w-100 me-3">
                            {{-- Display the status name, capitalized --}}
                            <span class="fw-bold text-uppercase">{{ str_replace('_', ' ', $status) }}</span>
                            <div>
                                {{-- Display the count of orders and the total value for this status --}}
                                <span class="badge bg-secondary me-2">{{ $orders->count() }} Orders</span>
                                <span class="badge bg-success">Total Value: Sh. {{ number_format($statusTotals[$status], 2) }}</span>
                            </div>
                        </div>
                    </button>
                </h2>
                <div id="collapse-{{ $status }}" class="accordion-collapse collapse" data-bs-parent="#statusAccordion">
                    <div class="accordion-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Supplier</th>
                                        <th class="text-end">Total Amount</th>
                                        <th>Items</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td>{{ $order->created_at->format('d M, Y') }}</td>
                                            <td>{{ optional($order->supplier)->username }}</td>
                                            <td class="text-end fw-bold">Sh. {{ number_format($order->total_amount, 2) }}</td>
                                            <td>{{ $order->items->pluck('product.name')->implode(', ') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body text-center text-muted">
                    There are no supplier orders in the system yet.
                </div>
            </div>
        @endforelse
    </div>
</x-app-layout>