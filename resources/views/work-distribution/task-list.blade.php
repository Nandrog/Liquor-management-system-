<x-app>
  <div class="container py-4">
    <h1 class="mb-4">📋 ALL ASSIGNED TASKS</h1>

    <table class="table table-bordered table-hover">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Task Type</th>
          <th>Priority</th>
          <th>Deadline</th>
          <th>Status</th>
          <th>Assigned Employee</th>
          <th>Stock Movement</th> <!-- ✅ new -->
        </tr>
      </thead>
      <tbody>
        @forelse ($tasks as $task)
          <tr>
            <td>{{ $task->id }}</td>
            <td>{{ $task->type }}</td>
            <td>{{ $task->priority }}</td>
            <td>{{ $task->deadline }}</td>
            <td>
              <span class="badge bg-primary">{{ $task->status }}</span>
            </td>
            <td>{{ $task->employee->name ?? 'N/A' }}</td>
            <td>
              @if ($task->stockMovement)
                #{{ $task->stockMovement->id }} — 
                {{ $task->stockMovement->product->name ?? '' }} | Qty: {{ $task->stockMovement->quantity }}
              @else
                —
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center">No tasks found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>

    <a href="{{ route('tasks.create') }}" class="btn btn-success">+ Assign New Task</a>
  </div>
</x-app>