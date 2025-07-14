<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Review Supplier Order #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <!-- Order Summary Details -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <p><strong>Supplier:</strong> {{ $order->supplier->name }}</p>
                            <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                            <p><strong>Status:</strong>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    {{ ucwords(str_replace('_', ' ', $order->status->value)) }}
                                </span>
                            </p>
                        </div>
                        <div class="text-right">
                            <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</p>
                            <p><strong>Total Amount:</strong>
                                <span class="font-bold text-lg">UGX {{ number_format($order->total_amount, 2) }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <h3 class="text-lg font-semibold mb-4">Order Items</h3>
                    <div class="overflow-x-auto table-responsive">
                        <table class="min-w-full divide-y divide-gray-200 table table-hover align-middle">
                            <thead class="bg-gray-50 table-lights">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($order->items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->product->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->quantity }}</td>

                                    {{-- ======================= THE FINAL CORRECTED LINE ======================= --}}
                                    {{-- This now uses `unit_price` which matches your database schema exactly. --}}
                                    <td class="px-6 py-4 whitespace-nowrap">UGX {{ number_format($item->product->unit_price ?? 0, 2) }}</td>

                                    {{-- The original subtotal, using the `price` column from the `order_items` table --}}
                                    <td class="px-6 py-4 whitespace-nowrap">UGX {{ number_format($item->quantity * $item->product->unit_price ?? 0, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-6 py-3 text-right font-bold text-gray-700">Grand Total</td>
                                    <td class="px-6 py-3 font-bold text-gray-900">UGX {{ number_format($order->total_amount, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Action Buttons -->
                    @if($order->status->value === 'pending_approval')
                        <div class="mt-6 flex space-x-4">
                            <a href="{{ route('payment.form', ['order' => $order->id]) }}" class="auth-button-green auth-button">
                                Accept & Proceed to Pay
                            </a>
<br>
                            <form action="{{ route('manufacturer.orders.update', $order) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="auth-button-yellow auth-button">Reject Order</button>
                            </form>
                        </div>
                    @elseif($order->status->value === 'confirmed' && $order->payment_status === 'paid')
                        <div class="mt-6">
                            <p class="text-green-600 font-semibold">This order has been paid and is being processed by the supplier.</p>
                        </div>
                    @endif

                    @if($order->status->value === 'delivering')
                        <div class="mt-6 p-4 border rounded-md bg-gray-50">
                            <h3 class="text-lg font-semibold text-blue-700">Confirm Delivery & Allocate Stock</h3>
                            <p class="text-gray-600 mb-4">Select the warehouse where you have received these items.</p>

                            <form action="{{ route('manufacturer.orders.confirmDelivery', $order) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <div class="mb-4">
                                    <label for="warehouse_id" class="block text-sm font-medium text-gray-700">Receiving Warehouse</label>
                                    <select name="warehouse_id" id="warehouse_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                        <option value="" disabled selected>-- Select a Warehouse --</option>
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('warehouse_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit" class="auth-button-green auth-button">Confirm & Update Inventory</button>
                            </form>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
