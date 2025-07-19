<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            📦 Inventory Category Chart
        </h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex justify-center">
            <div style="position: relative; width: 100%; max-width: 400px; height: 400px;">
                <canvas id="inventoryPieChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Chart.js and plugin --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <script>
        const ctx = document.getElementById('inventoryPieChart').getContext('2d');

        const data = {
            labels: {!! json_encode(array_keys($productCounts->toArray())) !!},
            datasets: [{
                label: 'Number of Products',
                data: {!! json_encode(array_values($productCounts->toArray())) !!},
                backgroundColor: [
                    '#4dc9f6',
                    '#f67019',
                    '#f53794',
                    '#537bc4',
                    '#acc236',
                    '#166a8f',
                    '#00a950',
                    '#58595b',
                    '#8549ba'
                ]
            }]
        };

        const config = {
            type: 'pie',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false, // Fix pie chart aspect ratio
                plugins: {
                    datalabels: {
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        },
                        anchor: 'end',
                        align: 'start',
                        offset: 10,
                        formatter: (value, context) => {
                            const total = context.chart.data.datasets[0].data.reduce((sum, val) => sum + val, 0);
                            return ((value / total) * 100).toFixed(1) + '%';
                        }
                    },
                    legend: {
                        position: 'top'
                    },
                    title: {
                        display: true,
                        text: 'Inventory Distribution by Category'
                    }
                }
            },
            plugins: [ChartDataLabels]
        };

        new Chart(ctx, config);
    </script>
</x-app-layout>
