<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Shopping from {{ $vendor->name }}
        </h2>
    </x-slot>

    @if($vendorProducts->isEmpty())
        <p>This vendor is not currently selling any products.</p>
    @else
        <form action="{{ route('customer.orders.store') }}" method="POST">
            @csrf
            <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($vendorProducts as $vendorProduct)
                <div class="border p-4 rounded-lg shadow">
                    <h3 class="text-lg font-bold">{{ $vendorProduct->product->name }}</h3>
                    <p class="text-gray-600">{{ $vendorProduct->product->description }}</p>
                    <p class="text-2xl font-light my-2">UGX{{ number_format($vendorProduct->retail_price, 2) }}</p>

                    <div class="mt-4">
                        <label for="product_{{ $vendorProduct->product_id }}" class="block text-sm font-medium text-gray-700">Quantity:</label>
                        <input type="number"
                               id="product_{{ $vendorProduct->product_id }}"
                               name="products[{{ $vendorProduct->product_id }}][quantity]"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                               min="0"
                               value="0">
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                    Place Order
                </button>
            </div>
        </form>
    @endif
</x-app-layout>
