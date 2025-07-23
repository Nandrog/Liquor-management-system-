<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Supplier Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        h1, h3 { text-align: center; margin-bottom: 5px; }
        h3 { font-size: 1em; font-weight: normal; margin-top: 0; color: #555; }
        .text-right { text-align: right; }
        
        .status-reorder { background-color: #ffeeba; } /* Yellow */
        .status-healthy { background-color: #d4edda; } /* Green */
    </style>
</head>
<body>

    <h1>Supplier Dashboard</h1>
    <h3>Inventory & Forecast for {{ $supplierName }} as of {{ $reportDate }}</h3>

    <table>
        <thead>
            <tr>
                <th>Your Product (Ingredient / Material)</th>
                <th class="text-right">Our Current Stock</th>
                <th class="text-right">Avg. Daily Usage (Last 7 Days)</th>
                <th class="text-right">Est. Days of Stock Remaining</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                @php
                    $statusClass = ($product->status === 'Reorder Anticipated') ? 'status-reorder' : 'status-healthy';
                @endphp
                <tr class="{{ $statusClass }}">
                    <td>{{ $product->name }}</td>
                    <td class="text-right">{{ $product->current_stock }} {{ $product->unit_of_measure }}</td>
                    <td class="text-right">{{ $product->avg_daily_usage }} {{ $product->unit_of_measure }}</td>
                    <td class="text-right" style="font-weight: bold;">
                        {{-- Show a clear warning for low stock --}}
                        @if(is_numeric($product->days_remaining) && $product->days_remaining < 10)
                            <span style="color: #721c24;">{{ $product->days_remaining }} days</span>
                        @else
                            {{ $product->days_remaining }}
                        @endif
                    </td>
                    <td>{{ $product->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">You are not currently assigned as the supplier for any raw materials.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
  <p style="font-size: 12px; color: #666; text-align: center;">
    This report provides an estimate of our current stock levels and usage to help you with your forecasting. "Avg. Daily Usage" is based on our production over the <strong>last 7 days</strong>.

</body>
</html>