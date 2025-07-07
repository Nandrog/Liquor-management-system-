<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create a New Supplier Order
        </h2>
    </x-slot>

    <form action="{{ route('supplier.orders.store') }}" method="POST">
        @csrf
        <p>Select raw materials you can supply:</p>

        @foreach($rawMaterials as $product)
        <div>
            <label>
                <input type="checkbox" name="products[{{ $loop->index }}][id]" value="{{ $product->id }}">
                {{ $product->name }}
            </label>
            <input type="number" name="products[{{ $loop->index }}][quantity]" placeholder="Quantity" min="1">
            <input type="number" name="products[{{ $loop->index }}][price]" placeholder="Price per unit" step="0.01" min="0">
        </div>
        @endforeach

        <button type="submit">Submit Offer</button>
    </form>
</x-app-layout>