<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Customer Segmentation</h2>
    </x-slot>

    <div class="p-6 space-y-6">
        @php
            $clusterLabels = [
                0 => 'High',
                1 => 'Medium',
                2 => 'Low',
            ]
        @endphp
        {{-- Check if "segments" exists before using it --}}
        <div class="bg-white p-4 shadow rounded">
            <h4 class="text-lg font-semibold mb-2">Cluster Counts</h4>
            <ul>
                @if (!empty($segments['segments']))
                    @foreach ($segments['segments'] as $segment => $members)
                        <li>Segment {{ $clusterLabels[$segment] ?? $segment }}: {{ count($members) }} customers</li>
                    @endforeach
                @else
                    <li>No segment data available.</li>
                @endif
            </ul>
            <canvas id="segmentChart" class="mt-4"></canvas>
        </div>

        {{-- Check if "centroids" exists before using it --}}
        @if (isset($segments['centroids']) && is_array($segments['centroids']))
            @foreach ($segments['centroids'] as $index => $centroid)
                <div class="bg-white p-4 shadow rounded">
                    <h4 class="text-lg font-semibold mb-2">Segment {{ $clusterLabels[$index] ?? $index }}</h4>
                    <p><strong>Centroid - Frequency:</strong> {{ round($centroid[0], 2) }}</p>
                    <p><strong>Centroid - Monetary:</strong> UGX{{ round($centroid[1], 2) }}</p>

                    {{-- Safely access range info if available --}}
                    @php
                        $range = $segments['ranges'][$index] ?? null;
                    @endphp
                    @if ($range)
                        <p><strong>Frequency Range:</strong> {{ $range['frequency_range'][0] }}–{{ $range['frequency_range'][1] }}</p>
                        <p><strong>Monetary Range:</strong> UGX{{ $range['monetary_range'][0] }} – UGX{{ $range['monetary_range'][1] }}</p>
                    @else
                        <p>Range data not available.</p>
                    @endif

                    <div class="mt-4">
                        <h5 class="font-semibold">Customers:</h5>
                        <div class="overflow-x-auto mt-4">
                            <table class="min-w-full border text-sm text-left">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 border">Customer ID</th>
                                        <th class="px-4 py-2 border">Frequency</th>
                                        <th class="px-4 py-2 border">Monetary (UGX)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($segments['segments'][$index]))
                                        @foreach ($segments['segments'][$index] as $customer)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-2 border">{{ $customer['customer_id'] }}</td>
                                                <td class="px-4 py-2 border">{{ $customer['frequency'] }}</td>
                                                <td class="px-4 py-2 border">UGX{{ number_format($customer['monetary'], 2) }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3" class="px-4 py-2 border text-red-500">No customers for this segment.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-red-500">No centroid data returned from API.</p>
        @endif
    </div>

    {{-- Only run chart script if segments are available --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        @if (isset($segments['segments']))
            const ctx = document.getElementById('segmentChart');
            const segmentLabels = {!! json_encode(array_map(function($k) {
                return['High', 'Medium', 'Low'][$k] ?? $k;
            }, array_keys($segments['segments']))) !!};

            const segmentCounts = {!! json_encode(array_map(fn($s) => count($s), $segments['segments'])) !!};

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: segmentLabels,
                    datasets: [{
                        label: 'Customers per Segment',
                        data: segmentCounts,
                        backgroundColor: ['#e74c3c', '#3498db', '#2ecc71']
                    }]
                }
            });
        @endif
    </script>
    @endpush
</x-app-layout>
