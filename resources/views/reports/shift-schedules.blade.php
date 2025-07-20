<x-app-layout>
    <div class="container py-4">
        <h1 class="mb-4">🕒 Shift Schedule Report</h1>

        {{-- ✅ Bar Chart --}}
        <canvas id="shiftChart" height="100"></canvas>

        {{-- ✅ Shift Table --}}
        <table class="table mt-5 table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Employee</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Break Hours</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($shifts as $shift)
                    <tr>
                        <td>{{ $shift->id }}</td>
                        <td>{{ $shift->employee->name ?? 'Unassigned' }}</td>
                        <td>{{ $shift->start_time }}</td>
                        <td>{{ $shift->end_time }}</td>
                        <td>{{ $shift->break_hours }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ✅ Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('shiftChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($shiftCounts->keys()) !!},
                datasets: [{
                    label: 'Number of Shifts',
                    data: {!! json_encode($shiftCounts->values()) !!},
                    backgroundColor: '#28a745',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Shifts Assigned Per Employee'
                    },
                    legend: { display: false }
                },
                scales: {
                    y: {
                        title: {
                            display: true,
                            text: 'Number of Shifts'
                        },
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Employee Name'
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>