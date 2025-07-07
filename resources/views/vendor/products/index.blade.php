<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Set Your Retail Prices
        </h2>
    </x-slot>

    <form action="{{ route('vendor.products.update', $vendor) }}" method="POST">
        @csrf
        @method('PUT') 
        
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Your Retail Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vendorProducts as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>
                        <input type="number" 
                               name="products[{{ $product->id }}][retail_price]" 
                               value="{{ $product->vendorProducts->first()->retail_price ?? '' }}"
                               step="0.01" 
                               min="0"
                               class="form-input rounded-md shadow-sm">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="mt-4 bg-blue-500 text-white p-2 rounded">Update Prices</button>
    </form>
</x-app-layout>