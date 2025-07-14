
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Order Details') }} <span class="text-gray-500 dark:text-gray-400">#{{ $order->id }}</span>
            </h2>
            <a href="{{ route('supplier.orders.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                {{ __('Back to Orders') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">

                    {{-- Order Summary Header --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Order ID</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">#{{ $order->id }}</dd>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date Submitted</dt>
                            <dd class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $order->created_at->format('M d, Y') }}</dd>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                            <dd class="mt-1">
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                                    @switch($order->status)
                                        @case(App\Enums\OrderStatus::PENDING_APPROVAL)
                                            bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300
                                            @break
                                        @case(App\Enums\OrderStatus::REJECTED)
                                            bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300
                                            @break
                                        @case(App\Enums\OrderStatus::PAID)
                                            bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300
                                            @break
                                        @default
                                            bg-gray-100 text-gray-800 dark:bg-gray-900/50 dark:text-gray-300
                                    @endswitch
                                ">
                                    {{ str_replace('_', ' ', Str::title($order->status->value)) }}
                                </span>
                            </dd>
                        </div>
                    </div>

                    {{-- Rejection Reason --}}
                    @if ($order->status == App\Enums\OrderStatus::REJECTED && !empty($order->rejection_reason))
                        <div class="mb-8 p-4 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 border-l-4 border-red-400 dark:border-red-600 rounded-r-lg">
                            <h4 class="font-bold">Reason for Rejection</h4>
                            <p class="mt-1">{{ $order->rejection_reason }}</p>
                        </div>
                    @endif

                    {{-- Items Table --}}
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Order Items</h3>
                    <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg table-response">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 table table-hover align-middle">
                            <thead class="bg-gray-50 dark:bg-gray-700 table-lights">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Product</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Quantity</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Unit Price</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item->product->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500 dark:text-gray-400">{{ $item->quantity }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 dark:text-gray-400">UGX{{ number_format($item->product->unit_price, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-700 dark:text-gray-200">UGX{{ number_format($item->product->unit_price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500 dark:text-gray-300 uppercase">Grand Total</td>
                                    <td class="px-6 py-3 text-right text-lg font-bold text-gray-900 dark:text-white">UGX{{ number_format($order->total_amount, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- Conditional Actions for Supplier --}}
                    @if ($order->status == App\Enums\OrderStatus::PENDING_APPROVAL)
                        <div class="mt-8 flex items-center justify-end space-x-4">
                            <a href="{{ route('supplier.orders.edit', $order) }}" class="auth-button-yellow auth-button">
                                Edit Order
                            </a>
                            <br>
                            <form action="{{ route('supplier.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this supply offer? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="auth-button-green auth-button">
                                    Cancel Offer
                                </button>
                            </form>
                        </div>
                    @endif
                    {{-- ... inside your view, where action buttons would go ... --}}

                @if($order->status == \App\Enums\OrderStatus::CONFIRMED && $order->payment_status == 'paid')
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-green-700">This order has been paid.</h3>
                        <p class="text-gray-600 mb-4">Please prepare the items for delivery and mark the order as delivering once it has been shipped.</p>

                        <form action="{{ route('supplier.orders.markAsDelivering', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="auth-button-blue auth-button">Mark as Delivering</button>
                        </form>
                    </div>
                @elseif($order->status == \App\Enums\OrderStatus::DELIVERING)
                    <div class="mt-6">
                        <p class="text-blue-700 font-semibold">You have marked this order as delivering. Awaiting confirmation from the manufacturer.</p>
                    </div>
                @elseif($order->status == \App\Enums\OrderStatus::DELIVERED)
                    <div class="mt-6">
                        <p class="text-green-800 font-bold">This order has been successfully delivered and completed.</p>
                    </div>
                @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
