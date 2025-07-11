<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Customer Segmentation</h2>
    </x-slot>

    <div class="p-6">
        <h4>Cluster Counts</h4>
        <ul>
            @foreach ($segments as $segment => $count)
                <li>Segment {{ $segment }}: {{ $count }} customers</li>
            @endforeach
        </ul>

        {{-- Optional: Add Chart.js bar chart --}}
        <canvas id="segmentChart"></canvas>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('segmentChart');
        const data = {
            labels: {!! json_encode(array_keys($segments)) !!},
            datasets: [{
                label: 'Customers per Segment',
                data: {!! json_encode(array_values($segments)) !!},
                backgroundColor: ['red', 'blue', 'green']
            }]
        };

        new Chart(ctx, {
            type: 'bar',
            data: data
        });
    </script>
    @endpush
</x-app-layout>
