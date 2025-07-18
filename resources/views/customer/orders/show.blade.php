<!-- resources/views/orders/show.blade.php -->
<x-app-layout>
    @push('styles')
    <style>
        .progress-track {
            display: flex;
            list-style-type: none;
            padding: 0;
            margin: 40px 0;
            justify-content: space-between;
            position: relative;
        }
        .progress-track::before {
            content: '';
            background-color: #ddd;
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            height: 4px;
            width: 100%;
            z-index: 1;
        }
        .progress-track::after {
            content: '';
            background-color: #28a745; /* Green color */
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            height: 4px;
            width: var(--progress-width, 0%);
            z-index: 2;
            transition: width 0.5s ease;
        }
        .progress-step {
            position: relative;
            z-index: 3;
            text-align: center;
            width: 100px;
        }
        .progress-step .step-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ddd;
            border: 3px solid #ddd;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            font-size: 1.2rem;
        }
        .progress-step.active .step-icon {
            background: #28a745;
            border-color: #28a745;
        }
        .progress-step.completed .step-icon {
            background: #28a745;
            border-color: #28a745;
        }
        .progress-step .step-label {
            margin-top: 10px;
            font-size: 0.9rem;
            color: #666;
        }
        .progress-step.active .step-label,
        .progress-step.completed .step-label {
            color: #000;
            font-weight: bold;
        }
    </style>
    @endpush

    <x-slot name="header">
        <h2 class="h3 font-semibold text-xl text-gray-800 leading-tight">
            Order Details
        </h2>
    </x-slot>

    <div class="container py-5">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Order #{{ $order->order_number }}</span>
                <span>Status: <span class="badge bg-primary">{{ Str::title($order->status) }}</span></span>
            </div>
            <div class="card-body">
                <!-- Order Status Tracker -->
                @php
                    $statuses = ['pending', 'processing', 'in_transit', 'delivered'];
                    $currentStatusIndex = array_search($order->status, $statuses);
                    $progressWidth = ($currentStatusIndex / (count($statuses) - 1)) * 100;
                @endphp
                <ul class="progress-track" style="--progress-width: {{ $progressWidth }}%;">
                    <li class="progress-step {{ $currentStatusIndex >= 0 ? 'completed' : '' }}">
                        <div class="step-icon"><i class="bi bi-card-checklist"></i></div>
                        <div class="step-label">Pending</div>
                    </li>
                    <li class="progress-step {{ $currentStatusIndex >= 1 ? 'completed' : '' }} {{ $currentStatusIndex == 1 ? 'active' : '' }}">
                        <div class="step-icon"><i class="bi bi-gear"></i></div>
                        <div class="step-label">Processing</div>
                    </li>
                    <li class="progress-step {{ $currentStatusIndex >= 2 ? 'completed' : '' }} {{ $currentStatusIndex == 2 ? 'active' : '' }}">
                        <div class="step-icon"><i class="bi bi-truck"></i></div>
                        <div class="step-label">In Transit</div>
                    </li>
                    <li class="progress-step {{ $currentStatusIndex >= 3 ? 'completed' : '' }} {{ $currentStatusIndex == 3 ? 'active' : '' }}">
                        <div class="step-icon"><i class="bi bi-house-check-fill"></i></div>
                        <div class="step-label">Delivered</div>
                    </li>
                </ul>

                <hr class="my-5">

                <!-- Order Items and Shipping Details -->
                <div class="row">
                    <div class="col-md-8">
                        <h5>Order Items</h5>
                        <table class="table">
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td><img src="{{ asset($item->product->image_url) }}" width="60" alt=""></td>
                                    <td>{{ $item->product->name }}</td>
                                    <td>Quantity: {{ $item->quantity }}</td>
                                    <td class="text-end">UGX {{ number_format($item->price * $item->quantity, 0) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <h5>Shipping To</h5>
                        <address>
                            <strong>{{ $order->user->name }}</strong><br>
                            {{ $order->shipping_address }}<br>
                            {{ $order->city }}, {{ $order->postal_code }}<br>
                            Phone: {{ $order->phone_number }}
                        </address>
                        <h5 class="mt-4">Total</h5>
                        <h3>UGX {{ number_format($order->grand_total, 0) }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
