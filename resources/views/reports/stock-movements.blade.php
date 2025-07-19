<x-app-layout>
    <div class="container py-4">
        <h1 class="mb-4">🚚 Stock Movement Report</h1>

        {{-- 📊 Line Chart --}}
        <canvas id="stockMovementChart" height="100"></canvas>

        {{-- 📋 Movement Table --}}
        <table class="table mt-5 table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Moved By</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($movements as $movement)
                    <tr>
                        <td>{{ $movement->id }}</td>
                        <td>{{ $movement->product->name ?? 'N/A' }}</td>
                        <td>{{ $movement->quantity }}</td>
                        <td>{{ $movement->fromWarehouse->name ?? 'N/A' }}</td>
                        <td>{{ $movement->toWarehouse->name ?? 'N/A' }}</td>
                        <td>{{ $movement->employee->name ?? 'N/A' }}</td>
                        <td>{{ $movement->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartLabels = Array.from(new Set(
            Object.values(@json($chartData)).flatMap(group => Object.keys(group))
        )).sort();

        const datasets = Object.entries(@json($chartData)).map(([warehouse, data]) => {
            return {
                label: warehouse,
                data: chartLabels.map(date => data[date] || 0),
                fill: false,
                borderColor: '#' + Math.floor(Math.random()*16777215).toString(16),
                tension: 0.3
            };
        });

        new Chart(document.getElementById('stockMovementChart'), {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: datasets
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: '📈 Stock Movements per Warehouse Over Time'
                    }
                },
                scales: {
                    y: {
                        title: {
                            display: true,
                            text: 'Number of Movements'
                        },
                        beginAtZero: true
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>