<x-app-layout>
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Purchase Orders</h1>
        {{-- This is where you would link to a "Create New Purchase Order" page --}}
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @if($orders->isEmpty())
                <p class="text-center text-muted">No purchase orders have been created yet.</p>
            @else
                <div class="accordion" id="purchaseOrdersAccordion">
                    @foreach($orders as $order)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $order->id }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $order->id }}">
                                    <div class="d-flex justify-content-between w-100 me-3">
                                        <span>Order #{{ $order->id }} - To: <span class="fw-bold">{{ optional($order->recipientSupplier)->username }}</span></span>
                                        <span class="text-muted">Order Date: {{ $order->created_at->format('d M, Y') }}</span>
                                        <x-order-status-badge :status="$order->status" />
                                    </div>
                                </button>
                            </h2>
                            <div id="collapse{{ $order->id }}" class="accordion-collapse collapse" data-bs-parent="#purchaseOrdersAccordion">
                                <div class="accordion-body">
                                    <h6 class="mb-3">Order Details</h6>
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Product Name</th>
                                                <th>SKU</th>
                                                <th class="text-end">Quantity Ordered</th>
                                                <th class="text-end">Price per Item</th>
                                                <th class="text-end">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($order->items as $item)
                                                <tr>
                                                    <td>{{ $item->product->name }}</td>
                                                    <td>{{ $item->product->sku }}</td>
                                                    <td class="text-end">{{ $item->quantity }}</td>
                                                    <td class="text-end">Sh. {{ number_format($item->price, 2) }}</td>
                                                    <td class="text-end">Sh. {{ number_format($item->quantity * $item->price, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-light">
                                                <td colspan="4" class="text-end fw-bold">Total Amount:</td>
                                                <td class="text-end fw-bold">Sh. {{ number_format($order->total_amount, 2) }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>