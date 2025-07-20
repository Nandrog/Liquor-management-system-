<x-app-layout>
    {{-- NEW: Add the styles for the order tracking progress bar --}}
    @push('styles')
    <style>
        .progress-track { display: flex; list-style-type: none; padding: 0; margin: 40px 0; justify-content: space-between; position: relative; }
        .progress-track::before { content: ''; background-color: #ddd; position: absolute; top: 50%; left: 0; transform: translateY(-50%); height: 4px; width: 100%; z-index: 1; }
        .progress-track::after { content: ''; background-color: #0d6efd; /* Blue color for progress */ position: absolute; top: 50%; left: 0; transform: translateY(-50%); height: 4px; width: var(--progress-width, 0%); z-index: 2; transition: width 0.5s ease; }
        .progress-step { position: relative; z-index: 3; text-align: center; width: 100px; }
        .progress-step .step-icon { width: 40px; height: 40px; border-radius: 50%; background: #ddd; border: 3px solid #ddd; display: inline-flex; justify-content: center; align-items: center; color: #fff; font-size: 1.2rem; }
        .progress-step.completed .step-icon { background: #0d6efd; border-color: #0d6efd; }
        .progress-step .step-label { margin-top: 10px; font-size: 0.9rem; color: #666; }
        .progress-step.completed .step-label { color: #000; font-weight: bold; }
    </style>
    @endpush

    <x-slot name="header">
        <h2 class="h3 font-semibold text-xl text-gray-800 leading-tight">
            Order Details
        </h2>
    </x-slot>

    <div class="container py-5">
        @if(session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Order #{{ $order->order_number ?? 'N/A' }}</h4>
                {{-- THE FIX: Access the ->value property of the Enum object --}}
                <span class="badge bg-primary fs-6">{{ Str::title($order->status->value) }}</span>
            </div>
            <div class="card-body">
                {{-- NEW: Visual Order Tracker --}}
                @php
                    $statuses = ['pending', 'processing', 'in_transit', 'delivered', 'cancelled'];
                    $currentStatusIndex = array_search($order->status->value, $statuses);
                    // Prevent division by zero if there's only one status
                    $progressWidth = (count($statuses) > 1) ? ($currentStatusIndex / (count($statuses) - 1)) * 100 : 100;
                    if ($order->status->value === 'cancelled') { $progressWidth = 0; }
                @endphp
                <ul class="progress-track" style="--progress-width: {{ $progressWidth }}%;">
                    @foreach ($statuses as $index => $status)
                        @if($status !== 'cancelled')
                            <li class="progress-step {{ $currentStatusIndex >= $index ? 'completed' : '' }}">
                                <div class="step-icon">
                                    @if($status === 'pending') <i class="bi bi-card-checklist"></i>
                                    @elseif($status === 'processing') <i class="bi bi-gear"></i>
                                    @elseif($status === 'in_transit') <i class="bi bi-truck"></i>
                                    @elseif($status === 'delivered') <i class="bi bi-house-check-fill"></i>
                                    @endif
                                </div>
                                <div class="step-label">{{ Str::title($status) }}</div>
                            </li>
                        @endif
                    @endforeach
                </ul>
                <hr class="my-4">

                <div class="row g-4">
                    {{-- Left Column: Order Items --}}
                    <div class="col-md-8">
                        <h5>Items Ordered</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-end">Unit Cost</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($order->items as $item)
                                        <tr>
                                            <td>
                                                @if($item->product)
                                                    <strong>{{ $item->product->name }}</strong><br>
                                                    <small class="text-muted">SKU: {{ $item->product->sku ?? 'N/A' }}</small>
                                                @else
                                                    <span class="text-danger"><em>Product not found (ID: {{ $item->product_id }})</em></span>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">UGX {{ number_format($item->product->unit_price, 0) }}</td>
                                            <td class="text-end fw-bold">UGX {{ number_format($item->product->unit_price * $item->quantity, 0) }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center text-muted">This order has no items.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Right Column: Summary and Payment --}}
                    <div class="col-md-4">
                        <div class="border p-3 rounded bg-light">
                            <h5 class="mb-3">Order Summary</h5>
                            <div class="d-flex justify-content-between">
                                <span>Total Amount:</span>
                                <strong class="fs-5">UGX {{ number_format($order->total_amount, 0) }}</strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Payment Status:</span>
                                {{-- THE FIX: Also access the ->value property here --}}
                                <span class="badge
                                    @if($order->payment_status->value === 'paid') bg-success
                                    @elseif($order->payment_status->value === 'pending') bg-warning text-dark
                                    @else bg-danger @endif">
                                    {{ Str::title($order->payment_status->value) }}
                                </span>
                            </div>
                            <hr>

                            {{-- Payment Action Block --}}
                            @if($order->payment_status->value === 'pending')
                                <p class="text-muted small">Your order is awaiting payment.</p>
                                @if (Route::has('vendor.orders.pay'))
                                    <form action="{{ route('vendor.orders.pay', $order) }}" method="POST">
                                        @csrf
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-success btn-lg">Pay Now (UGX {{ number_format($order->total_amount, 0) }})</button>
                                        </div>
                                    </form>
                                @else
                                    <p class="text-danger">Payment processing is currently unavailable.</p>
                                @endif
                            @elseif($order->payment_status->value === 'paid')
                                <p class="text-success fw-bold"><i class="bi bi-check-circle-fill"></i> This order has been paid and is being processed.</p>
                            @endif
                            {{-- In resources/views/vendor/orders/show.blade.php --}}

                                {{-- Payment Action Block --}}
                            @if($order->payment_status->value === 'pending')
                                <p class="text-muted small">Your order is awaiting payment. After payment confirmation, the products will be assigned to your catalog and stock dispatched.</p>

                                {{-- MODIFICATION: Changed this from a form to a simple link (styled as a button) --}}
                                <div class="d-grid">
                                    <a href="{{ route('vendor.orders.payment.create', $order) }}" class="btn btn-success btn-lg">
                                        Pay Now (UGX {{ number_format($order->total_amount, 0) }})
                                    </a>
                                </div>

                            @elseif($order->payment_status->value === 'paid')
                                <p class="text-success fw-bold"><i class="bi bi-check-circle-fill"></i> This order has been paid and is being processed.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
             <div class="card-footer text-muted">
                Order Placed: {{ optional($order->created_at)->format('F j, Y, g:i a') }}
            </div>
        </div>
    </div>
</x-app-layout>
