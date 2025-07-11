<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vendor Purchase Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-300 rounded-md" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <p class="mb-6 text-gray-600">
                        This list shows all purchase orders submitted by vendors for finished goods. Please review each order to verify stock levels before confirming for dispatch.
                    </p>

                    <div class="overflow-x-auto border rounded-lg table-responsive">
                        <table class="min-w-full divide-y divide-gray-200 table table-hover align-middle">
                            <thead class="bg-gray-50 table-light">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Order ID
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Vendor
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Order Date
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total Amount
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($orders as $order)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            #{{ $order->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{-- NOTE: For performance, eager load the user in the controller ->with('user') --}}
                                            {{ $order->user->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $order->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            ${{ number_format($order->total_amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span @class([
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                // Vendor orders will typically be in 'pending_approval' not 'pending'
                                                'bg-yellow-100 text-yellow-800' => $order->status === \App\Enums\OrderStatus::PendingApproval || $order->status === \App\Enums\OrderStatus::Pending,
                                                'bg-green-100 text-green-800' => $order->status === \App\Enums\OrderStatus::Confirmed,
                                                'bg-red-100 text-red-800' => $order->status === \App\Enums\OrderStatus::Rejected,
                                                'bg-blue-100 text-blue-800' => $order->status === \App\Enums\OrderStatus::Paid,
                                                'bg-purple-100 text-purple-800' => $order->status === \App\Enums\OrderStatus::Shipped,
                                            ])>
                                                {{ ucwords(str_replace('_', ' ', $order->status->value)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('procurement.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                                Review
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                            There are no vendor orders requiring procurement review.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination Links --}}
                    @if ($orders->hasPages())
                        <div class="mt-4 px-2">
                            {{ $orders->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
