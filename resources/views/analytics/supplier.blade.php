<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Supplier Analytics</h2>
    </x-slot>

    <div class="py-12 px-4">
        <canvas id="supplierChart"></canvas>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const data = {!! json_encode($data) !!};

        new Chart(document.getElementById('supplierChart'), {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Monthly Revenue',
                    data: data.values,
                    backgroundColor: 'green'
                }]
            }
        });
    </script>
    @endpush
</x-app-layout>
