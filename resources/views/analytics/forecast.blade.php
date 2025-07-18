<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Sales Forecast</h2>
    </x-slot>

    <div class="p-6 space-y-6">
        {{-- Summary --}}
        <div class="bg-white shadow p-4 rounded">
            <h4 class="text-lg font-semibold mb-2">Next 3 Months (Predicted Sales)</h4>
            <ul class="list-disc list-inside text-gray-700">
                @foreach ($data['predicted_sales'] as $sale)
                    <li>${{ number_format($sale, 2) }}</li>
                @endforeach
            </ul>

            <p class="mt-4"><strong>Efficiency:</strong> {{ $data['efficiency'] }}%</p>
            <p><strong>Avg Fulfillment Days:</strong> {{ $data['fulfillment_days'] }}</p>
        </div>

        {{-- Chart --}}
        <div class="bg-white shadow p-4 rounded">
            <h4 class="text-lg font-semibold mb-4">Forecast vs Actual Sales</h4>
            <canvas id="forecastChart" width="600" height="300"></canvas>
        </div>
    </div>

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // 1) months labels
        const labels = [
            'Jan','Feb','Mar','Apr','May','Jun',
            'Jul','Aug','Sep','Oct','Nov','Dec',
            'Next 1','Next 2','Next 3'
        ];

        // 2) inject PHP arrays
        const actualSales    = @json($data['actual_sales']);
        const forecastedSales= @json($data['predicted_sales']);

        // 3) build Chart.js dataset
        const ctx = document
            .getElementById('forecastChart')
            .getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Actual Sales',
                        data: actualSales,
                        borderColor: 'rgb(37,99,235)',
                        fill: false,
                        tension: 0.3
                    },
                    {
                        label: 'Forecasted Sales',
                        data: [
                            ...Array(actualSales.length).fill(null),
                            ...forecastedSales
                        ],
                        borderColor: 'rgb(234,88,12)',
                        borderDash: [5,5],
                        fill: false,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: false }
                }
            }
        });
    </script>
</x-app-layout>
