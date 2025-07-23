<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Procurement & Stock Status Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        h2, h3 { text-align: center; margin-bottom: 5px; }
        h3 { font-size: 1em; font-weight: normal; margin-top: 0; }
        .text-right { text-align: right; }
        
        /* Status Color Coding */
        .status-reorder { background-color: #ffeeba; font-weight: bold; color: #856404; } /* Yellow */
        .status-out { background-color: #f5c6cb; font-weight: bold; color: #721c24; } /* Red */
        .status-in-stock { background-color: #d4edda; color: #155724; } /* Green */
    </style>
</head>
<body>

    <h2>Procurement & Stock Status Report</h2>
    <h3>As of: {{ $reportDate }}</h3>

    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th class="text-right">Current Stock</th>
                <th class="text-right">Reorder Level</th>
                <th>Status</th>
                <th class="text-right">Sales Velocity (Avg/Day)</th>
                <th>Vendor</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                {{-- Assign a CSS class based on the status calculated in the controller --}}
                @php
                    $statusClass = match($product->status) {
                        'Reorder Now' => 'status-reorder',
                        'Out of Stock' => 'status-out',
                        'In Stock' => 'status-in-stock',
                        default => ''
                    };
                @endphp
                <tr class="{{ $statusClass }}">
                    <td>{{ $product->name }}</td>
                    <td class="text-right">{{ $product->current_stock }}</td>
                    <td class="text-right">{{ $product->reorder_level }}</td>
                    <td>{{ $product->status }}</td>
                    <td class="text-right">{{ $product->sales_velocity }}</td>
                    <td>{{ $product->vendor->name ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">No finished good products found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>