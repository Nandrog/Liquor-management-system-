<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('My Supplier Orders') }}
            </h2>
            <a href="{{ route('supplier.orders.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Offer New Supply') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Success Message --}}
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-300 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto table-responsive">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 table table-hover align-middle">
                            <thead class="bg-gray-50 dark:bg-gray-700 table-light">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Order ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date Submitted</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Items</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($orders as $order)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">#{{ $order->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @switch($order->status)
                                                    @case(App\Enums\OrderStatus::PENDING_APPROVAL) bg-yellow-100 text-yellow-800 @break
                                                    @case(App\Enums\OrderStatus::REJECTED) bg-red-100 text-red-800 @break
                                                    @case(App\Enums\OrderStatus::PAID) bg-green-100 text-green-800 @break
                                                    @default bg-gray-100 text-gray-800
                                                @endswitch
                                            ">
                                                {{ str_replace('_', ' ', Str::title($order->status->value)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $order->created_at->format('d M, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $order->items_count }}</td>

                                        {{-- ===== MODIFIED ACTIONS CELL ===== --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-4">

                                                <a href="{{ route('supplier.orders.show', $order) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900">View</a>

                                                {{-- Only show Edit and Delete if the order is still pending --}}
                                                @if ($order->status == App\Enums\OrderStatus::PENDING_APPROVAL)
                                                    <a href="{{ route('supplier.orders.edit', $order) }}" class="text-green-600 dark:text-green-400 hover:text-green-900">Edit</a>

                                                    {{-- The secure way to create a Delete button --}}
                                                    <form action="{{ route('supplier.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this order?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="auth-button-yellow auth-button">Delete</button>
                                                    </form>
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            You have not submitted any supply offers yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination Links --}}
                    <div class="mt-4">
                        {{ $orders->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
