<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Interactive Sales Chart</title>
    <link rel="stylesheet" href="{{ asset('css/reports.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container">
    <h1>Sales Chart – {{ $monthName }} {{ $year }}</h1>

    <p>This interactive chart shows daily sales trends. It supports live decision-making for the manufacturer, supplier, and finance team.</p>

    <canvas id="salesChart" width="100%" height="40"></canvas>

    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($daysInMonth) !!},
                datasets: [{
                    label: 'Daily Sales (UGX)',
                    data: {!! json_encode($dailySales) !!},
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => 'UGX ' + value.toLocaleString()
                        }
                    }
                }
            }
        });
    </script>

    <h2>Summary</h2>
    <p>Total Sales: <strong>UGX {{ number_format($totalSales) }}</strong></p>
    <p>Total Orders: <strong>{{ $totalOrders }}</strong></p>
    <p>Forecast for next month: <strong>UGX {{ number_format($forecast) }}</strong></p>
</div>
</body>
</html>
