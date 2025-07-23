<x-app-layout>
    <div class="container py-4">
        <h1 class="mb-4 font-bold text-xl">🚚 Stock Movement Report</h1>

        {{-- 📊 Line Chart --}}
        <canvas id="stockMovementChart" height="100" class="w-full"></canvas>

        {{-- 📋 Movement Table --}}
        <div class="table-responsive mt-5">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($movements as $movement)
                        <tr>
                            <td>{{ $movement->id }}</td>
                            <td>{{ $movement->product->name ?? 'N/A' }}</td>
                            <td>{{ $movement->quantity }}</td>
                            <td>{{ $movement->fromWarehouse->name ?? 'N/A' }}</td>
                            <td>{{ $movement->toWarehouse->name ?? 'N/A' }}</td>
                            <td>{{ $movement->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No stock movements available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const chartData = @json($chartData);

            const chartLabels = Array.from(
                new Set(Object.values(chartData).flatMap(group => Object.keys(group)))
            ).sort();

            const datasets = Object.entries(chartData).map(([warehouse, data]) => ({
                label: warehouse,
                data: chartLabels.map(date => data[date] ?? 0),
                fill: false,
                borderColor: '#' + Math.floor(Math.random() * 16777215).toString(16),
                tension: 0.3
            }));

            const ctx = document.getElementById('stockMovementChart').getContext('2d');
            new Chart(ctx, {
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
        });
    </script>
</x-app-layout>
