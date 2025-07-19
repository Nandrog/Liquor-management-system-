<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Sales Forecast by Liquor</h2>
    </x-slot>

    <div class="p-6 space-y-6">
        <div class="bg-white shadow p-4 rounded">
            <h4 class="text-lg font-semibold mb-2">Forecast Summary</h4>
            <p><strong>Efficiency:</strong> {{ $efficiency }}%</p>
            <p><strong>Avg Fulfillment Days:</strong> {{ $fulfillment_days }}</p>
        </div>

        <div class="bg-white shadow p-4 rounded">
            <h4 class="text-lg font-semibold mb-4">Weekly Sales by Liquor</h4>
            <canvas id="liquorSalesChart" width="800" height="400"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('liquorSalesChart').getContext('2d');

        const labels = @json($weeks);
        const datasets = @json($datasets);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                stacked: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Weekly Sales by Liquor'
                    },
                    legend: {
                        position: 'top',
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Sales Amount (USD)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Week'
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
