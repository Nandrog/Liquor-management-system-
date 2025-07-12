<x-app-layout>
    <div class="p-6">
        <h2 class="text-2xl font-bold mb-6">Analytics Dashboard</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Forecast Chart -->
            <div class="bg-white p-4 rounded-xl shadow">
                <h3 class="text-lg font-semibold mb-3">Sales Forecast (Next 3 Months)</h3>
                <canvas id="forecastChart"></canvas>
            </div>

            <!-- KPIs -->
            <div class="bg-white p-4 rounded-xl shadow">
                <h3 class="text-lg font-semibold mb-3">Performance KPIs</h3>
                <ul class="list-disc list-inside text-sm">
                    <li><strong>Fulfillment Time:</strong> {{ $forecast['fulfillment_days'] }} days</li>
                    <li><strong>Production Efficiency:</strong> {{ $forecast['efficiency'] }}%</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const forecastData = @json($forecast['predicted_sales']);
        new Chart(document.getElementById('forecastChart'), {
            type: 'line',
            data: {
                labels: ['Month 1', 'Month 2', 'Month 3'],
                datasets: [{
                    label: 'Forecasted Sales',
                    data: forecastData,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    tension: 0.3,
                    fill: true
                }]í
            }
        });
    </script>
</x-app-layout>
