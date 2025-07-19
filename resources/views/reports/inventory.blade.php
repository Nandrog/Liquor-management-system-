
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Weekly Inventory Report</h2>
    </x-slot>

    <div class="p-6">
        <h3>Report Duration: {{ $startOfWeek->toFormattedDateString() }} to {{ $endOfWeek->toFormattedDateString() }}</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Supplier</th>
                    <th>Vendor</th>
                    <th>Last Updated</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                        <td>{{ $product->supplier->name ?? 'N/A' }}</td>
                        <td>{{ $product->vendor->name ?? 'N/A' }}</td>
                        <td>{{ $product->updated_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
