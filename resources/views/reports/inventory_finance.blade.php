<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Weekly Inventory Movement Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        h2, h3 { text-align: center; margin-bottom: 5px; }
        h3 { font-size: 1em; font-weight: normal; margin-top: 0; }
        .text-right { text-align: right; }
    </style>
</head>
<body>

    <h2>Weekly Inventory Movement Report</h2>
    <h3>For the week of: {{ $weekStartDate }} to {{ $weekEndDate }}</h3>

    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th class="text-right">Beginning Stock</th>
                <th class="text-right">Stock In (Purchases)</th>
                <th class="text-right">Stock Out (Sales)</th>
                <th class="text-right">Ending Stock</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Ending Stock Value</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotalValue = 0; @endphp
            @forelse($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td class="text-right">{{ $product->beginning_stock }}</td>
                    <td class="text-right">{{ $product->stock_in_this_week }}</td>
                    <td class="text-right">{{ $product->stock_out_this_week }}</td>
                    <td class="text-right" style="font-weight: bold;">{{ $product->ending_stock }}</td>
                    <td class="text-right">{{ number_format($product->unit_price, 2) }}</td>
                    
                    @php $endingValue = $product->ending_stock * $product->unit_price; @endphp
                    <td class="text-right">{{ number_format($endingValue, 2) }}</td>
                </tr>
                @php $grandTotalValue += $endingValue; @endphp
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">No finished good products found.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #f2f2f2;">
                <td colspan="6" class="text-right">Grand Total Ending Inventory Value:</td>
                <td class="text-right">{{ number_format($grandTotalValue, 2) }}</td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
