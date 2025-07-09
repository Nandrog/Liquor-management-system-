<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Customer Segmentation
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                <div class="mb-5">
                    <canvas id="segmentChart" width="400" height="200"></canvas>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2>Customer Segmentation</h2>
                    <button class="btn btn-sm btn-outline-secondary" id="resetFilter" style="display: none;">Show All Segments</button>
                </div>
                <table class="table table-bordered table-striped w-full">
                    <thead class="table-light">
                        <tr>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Total Spend</th>
                            <th>Purchases</th>
                            <th>Last Purchase</th>
                            <th>Segment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($segments as $s)
                        <tr data-segment="{{ $s['segment'] }}">
                            <td>{{ $s['customer']->company_name }}</td>
                            <td>{{ $s['customer']->phone_number }}</td>
                            <td>${{ number_format($s['total_spend'], 2) }}</td>
                            <td>{{ $s['purchase_count'] }}</td>
                            <td>{{ $s['last_purchase'] ? \Carbon\Carbon::parse($s['last_purchase'])->toFormattedDateString() : 'N/A' }}</td>
                            <td>
                                @php
                                    $badge = match($s['segment']) {
                                        'High Value' => 'success',
                                        'At Risk' => 'warning',
                                        'Low Engagement' => 'secondary',
                                        'Inactive' => 'danger',
                                        default => 'info'
                                    };
                                @endphp
                                <span class="badge bg-{{ $badge }}">{{ $s['segment'] }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        const ctx = document.getElementById('segmentChart').getContext('2d');
        const resetBtn = document.getElementById('resetFilter');

        const labels = {!! json_encode($segmentSummary->keys()) !!};
        const values = {!! json_encode($segmentSummary->values()) !!};

        const segmentChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Customer Segments',
                    data: values,
                    backgroundColor: ['#198754', '#ffc107', '#6c757d', '#dc3545', '#0dcaf0'],
                    borderWidth: 1
               }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                onClick: function (evt, elements) {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        const selectedSegment = labels[index];

                        // Hide all rows
                        document.querySelectorAll('tbody tr').forEach(row => {
                            if (row.getAttribute('data-segment') === selectedSegment) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        });

                        // Show reset filter button
                        resetBtn.style.display = 'inline-block';
                    }
                }
            }
        });

        resetBtn.addEventListener('click', () => {
            document.querySelectorAll('tbody tr').forEach(row => row.style.display = '');
            resetBtn.style.display = 'none';
        });
    </script>

</x-app-layout>
