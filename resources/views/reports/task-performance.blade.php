<x-app-layout>
    <div class="container py-4">
        <h1 class="mb-4">📊 Task Performance Report</h1>

        {{-- ✅ Bar Chart --}}
        <canvas id="taskStatusChart" height="100"></canvas>

        {{-- ✅ Task Table --}}
        <table class="table mt-5 table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Deadline</th>
                    <th>Employee</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                    <tr>
                        <td>{{ $task->id }}</td>
                        <td>{{ $task->type }}</td>
                        <td>{{ $task->status }}</td>
                        <td>{{ $task->priority }}</td>
                        <td>{{ $task->deadline }}</td>
                        <td>{{ $task->employee->name ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ✅ Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('taskStatusChart').getContext('2d');
    const taskStatusChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($taskStatusCounts->keys()) !!},
            datasets: [{
                label: 'Number of Tasks',
                data: {!! json_encode($taskStatusCounts->values()) !!},
                backgroundColor: '#007bff',
                borderRadius: 6,
                barThickness: 40,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: '',
                    font: {
                        size: 18,
                        weight: 'bold'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Employees',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    },
                    ticks: {
                        precision: 0,
                        stepSize: 1
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Tasks Distributed by Status',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    }
                }
            }
        }
    });
</script>
</x-app-layout>