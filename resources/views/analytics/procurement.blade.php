<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Procurement Analytics</h2>
    </x-slot>

    <div class="py-12 px-4">
        <canvas id="procurementChart"></canvas>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const data = {!! json_encode($data) !!};

        new Chart(document.getElementById('procurementChart'), {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Monthly Orders',
                    data: data.values,
                    borderColor: 'blue',
                    fill: false,
                    tension: 0.3
                }]
            }
        });
    </script>
    @endpush
</x-app-layout>
