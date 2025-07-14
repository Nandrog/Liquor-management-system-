<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Supplier Order #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{-- The form action points to the 'update' route and includes the order ID --}}
                    <form action="{{ route('supplier.orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- This is crucial for telling Laravel this is an update --}}

                        <h3 class="text-lg font-medium leading-6 text-gray-900">Order Items</h3>
                        <div class="mt-6 space-y-6">

                            {{-- Loop through the EXISTING items in the order --}}
                            @foreach($order->items as $item)
                            <div class="product-item border-t border-gray-200 pt-4">
                                <span class="ml-3 text-base font-medium text-gray-900">{{ $item->product->name }}</span>

                                <div class="ml-7 mt-4 space-y-4">
                                    <input type="hidden" name="items[{{ $item->id }}][id]" value="{{ $item->id }}">

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                        {{-- Pre-fill the input with the existing quantity --}}
                                        <input class="form-control mt-1 block w-full sm:w-1/2 rounded-md border-gray-300 shadow-sm" type="number" name="items[{{ $item->id }}][quantity]" value="{{ old('quantity', $item->quantity) }}" min="1">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Price per Unit</label>
                                        {{-- Pre-fill the input with the existing price --}}
                                        <input class="form-control mt-1 block w-full sm:w-1/2 bg-gray-100 rounded-md border-gray-300 shadow-sm" type="number" name="items[{{ $item->id }}][price]" value="{{ old('price', $item->price) }}" readonly>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div>

                        <div class="mt-8 pt-5">
                            <div class="flex justify-end">
                                <button class="auth-button-yellow auth-button" type="submit">Update Order</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
