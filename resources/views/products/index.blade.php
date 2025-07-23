<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Product Stock') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    {{-- Display the success message after adding stock --}}
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-200 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

<table class="stock-table">
    <thead>
        <tr>
            <th>Product Name</th>
            <th class="text-right">Total Stock (All Warehouses)</th>
            <th>Add Stock to a Warehouse</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td class="text-right">{{ $product->total_stock }}</td>
                <td>
                    <form action="{{ route('products.add-stock', $product) }}" method="POST" class="add-stock-form">
                        @csrf
                        
                        {{-- 1. ADD THE WAREHOUSE DROPDOWN --}}
                        <select name="warehouse_id" required>
                            <option value="">Select Warehouse...</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->warehouse_id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>

                        <input 
                            type="number" 
                            name="quantity"
                            placeholder="Enter Qty" 
                            min="1"
                            required>
                            
                        <button type="submit" class="btn-add-stock">
                            Add Stock
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>