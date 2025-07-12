<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Liquor Manager Analytics</h2>
    </x-slot>

    <div class="py-12 px-4">
        <canvas id="liquorChart"></canvas>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const data = {!! json_encode($data) !!};

        new Chart(document.getElementById('liquorChart'), {
            type: 'pie',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Inventory Distribution',
                    data: data.values,
                    backgroundColor: ['red', 'orange', 'purple', 'blue']
                }]
            }
        });
    </script>
    @endpush
</x-app-layout>
