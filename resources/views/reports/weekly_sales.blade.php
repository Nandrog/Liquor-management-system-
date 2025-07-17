<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl">Weekly Sales Report - {{ $role }}</h2>
    </x-slot>

    <div class="p-4">
        <p class="text-sm text-gray-600">Period: {{ $startOfWeek->format('M d') }} - {{ $endOfWeek->format('M d, Y') }}</p>

        <a href="{{ route('reports.sales.weekly.pdf') }}"
           class="bg-green-600 text-yellow px-4 py-2 rounded hover:bg-green-700 mb-4 inline-block"
           target="_blank">
            Download Weekly Sales Report (PDF)
        </a>

        <canvas id="salesChart" height="100" class="mb-6"></canvas>

        <table class="w-full table-auto border mt-6">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2">Product</th>
                    <th class="px-4 py-2">Quantity</th>
                    <th class="px-4 py-2">Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($grouped as $product => $data)
                    <tr>
                        <td class="border px-4 py-2">{{ $product }}</td>
                        <td class="border px-4 py-2">{{ $data['quantity'] }}</td>
                        <td class="border px-4 py-2">{{ number_format($data['revenue'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = @json($grouped->keys());
        const quantities = @json($grouped->pluck('quantity')->values());
        const revenues = @json($grouped->pluck('revenue')->values());

        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Quantity',
                        data: quantities,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    },
                    {
                        label: 'Revenue',
                        data: revenues,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    }
                ]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
</x-app-layout>