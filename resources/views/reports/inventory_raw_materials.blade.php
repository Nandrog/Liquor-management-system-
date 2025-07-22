<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Raw Material Stock Status Report</title>
    <style>
        /* Reusing the same styles from the other procurement report */
        body { font-family: Arial, sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        h2, h3 { text-align: center; margin-bottom: 5px; }
        h3 { font-size: 1em; font-weight: normal; margin-top: 0; }
        .text-right { text-align: right; }
        
        .status-reorder { background-color: #ffeeba; font-weight: bold; color: #856404; }
        .status-out { background-color: #f5c6cb; font-weight: bold; color: #721c24; }
        .status-in-stock { background-color: #d4edda; color: #155724; }
    </style>
</head>
<body>

    <h2>Raw Material & Ingredient Stock Status</h2>
    <h3>As of: {{ $reportDate }}</h3>

    <table>
        <thead>
            <tr>
                <th>Ingredient / Material Name</th>
                <th>Category</th>
                <th class="text-right">Current Stock</th>
                <th class="text-right">Reorder Level</th>
                <th>Status</th>
                <th>Supplier</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
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
                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                    <td class="text-right">{{ $product->current_stock }}</td>
                    <td class="text-right">{{ $product->reorder_level }}</td>
                    <td>{{ $product->status }}</td>
                    <td>{{ $product->supplier->name ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">No raw materials or ingredients found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>