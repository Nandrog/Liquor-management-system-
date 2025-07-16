<style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 24px;
    }
    th, td {
        border: 1px solid #999;
        padding: 8px 12px;
        text-align: left;
    }
    th {
        background-color: #f0f0f0;
    }
    h2, h3 {
        margin-top: 20px;
        margin-bottom: 10px;
    }
</style>

<h2>Liquor Manager Inventory Report</h2>
<p>Period: {{ $startOfWeek }} to {{ $endOfWeek }}</p>

<h3>Finished Goods (Manufacturer View)</h3>
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Stock</th>
            <th>Unit Price</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($manufacturerProducts as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->stock }}</td>
                <td>{{ $product->unit_price }}</td>
            </tr>
        @empty
            <tr><td colspan="3">No products found</td></tr>
        @endforelse
    </tbody>
</table>

<h3>Supplied Products (Supplier View)</h3>
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Supplier</th>
            <th>Stock</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($supplierProducts as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->supplier?->name ?? 'N/A' }}</td>
                <td>{{ $product->stock }}</td>
            </tr>
        @empty
            <tr><td colspan="3">No products found</td></tr>
        @endforelse
    </tbody>
</table>

<h3>All Products (Finance View)</h3>
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Stock</th>
            <th>Unit Price</th>
            <th>SKU</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($financeProducts as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->stock }}</td>
                <td>{{ $product->unit_price }}</td>
                <td>{{ $product->sku }}</td>
            </tr>
        @empty
            <tr><td colspan="4">No products found</td></tr>
        @endforelse
    </tbody>
</table>

