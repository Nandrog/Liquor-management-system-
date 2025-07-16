<x-app-layout>
    {{-- Page Header --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h3 font-semibold text-xl text-gray-800 leading-tight mb-0">
                {{ $pageTitle ?? 'Payment History' }}
            </h2>
            {{-- Optional: Add a button for reports or other actions --}}
            {{-- <a href="#" class="btn btn-sm btn-primary">
                <i class="fas fa-file-download me-1"></i> Download Report
            </a> --}}
        </div>
    </x-slot>

    @push('styles')
    <style>
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
        .status-badge {
            font-size: 0.85em;
            padding: 0.4em 0.7em;
        }
        .align-middle td {
            vertical-align: middle;
        }
    </style>
    @endpush

    {{-- Main Content Area --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div class="card shadow-none">
                        <div class="card-header bg-light border-bottom">
                            <h5 class="mb-0">Completed Payments</h5>
                        </div>
                        <div class="card-body p-0">
                            {{-- This check is important for user experience --}}
                            @if($orders->isEmpty())
                                <div class="text-center p-5">
                                    <i class="fas fa-receipt fa-4x text-muted mb-3"></i>
                                    <h4 class="text-muted">No Payment Records Found</h4>
                                    <p>There are currently no records of paid orders.</p>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col">Order #</th>
                                                <th scope="col">Supplier</th>
                                                <th scope="col" class="text-center">Payment Date</th>
                                                <th scope="col" class="text-end">Amount</th>
                                                <th scope="col" class="text-center">Status</th>
                                                <th scope="col" class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="align-middle">
                                            @foreach($orders as $order)
                                                <tr>
                                                    {{-- Order Number --}}
                                                    <td class="fw-bold">
                                                        <a href="{{ route('manufacturer.orders.show', $order) }}" class="text-decoration-none">
                                                            {{ $order->order_number ?? $order->id }}
                                                        </a>
                                                    </td>

                                                    {{-- Supplier Name --}}
                                                    <td>{{ $order->user->name ?? 'N/A' }}</td>

                                                    {{-- Payment Date --}}
                                                    <td class="text-center">
                                                        {{ $order->paid_at ? $order->paid_at->format('M d, Y') : ($order->delivered_at ? $order->delivered_at->format('M d, Y') : now()->format('M d, Y')) }}
                                                    </td>

                                                    {{-- Amount --}}
                                                    <td class="text-end">
                                                        {{-- Assumes you have a total_amount column or a relationship to calculate it --}}
                                                        UGX{{ number_format($order->total_amount, 2) }}
                                                    </td>

                                                    {{-- Status Badge --}}
                                                    <td class="text-center">
                                                        @if($order->status == 'paid')
                                                            <span class="badge rounded-pill bg-success status-badge">Paid</span>
                                                        @elseif($order->status == 'delivered')
                                                            <span class="badge rounded-pill bg-info text-dark status-badge">Delivered</span>
                                                        @else
                                                            <span class="badge rounded-pill bg-secondary status-badge">{{ $order->status }}</span>
                                                        @endif
                                                    </td>

                                                    {{-- Action Buttons --}}
                                                    <td class="text-center">
                                                        <a href="{{ route('supplier.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary" title="View Order Details">
                                                            <i class="fas fa-eye"></i> View
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        {{-- Pagination Links --}}
                        @if($orders->hasPages())
                            <div class="card-footer bg-light border-top">
                                {{ $orders->links() }}
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
