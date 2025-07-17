<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Weekly Sales Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f3f3f3; }
    </style>
</head>
<body>
    <h2>{{ $role }} - Weekly Sales Report</h2>
    <p>Reporting Period: {{ $startOfWeek->format('M d') }} - {{ $endOfWeek->format('M d, Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity Sold</th>
                <th>Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($grouped as $product => $data)
                <tr>
                    <td>{{ $product }}</td>
                    <td>{{ $data['quantity'] }}</td>
                    <td>{{ number_format($data['revenue'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
