<x-app>
  <div class="container py-4">
    <h1 class="mb-4">📋 ALL ASSIGNED TASKS</h1>

    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Task Type</th>
          <th>Priority</th>
          <th>Deadline</th>
          <th>Status</th>
          <th>Assigned Employee</th>
          <th>Stock Movement</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($tasks as $task)
          <tr>
            <td>{{ $task->id }}</td>
            <td>{{ $task->type }}</td>
            <td>{{ $task->priority }}</td>
            <td>{{ \Carbon\Carbon::parse($task->deadline)->format('M d, Y H:i') }}</td>
            <td>
              <span class="badge bg-primary">{{ ucfirst($task->status) }}</span>
            </td>
            <td>{{ $task->employee->name ?? 'N/A' }}</td>
            <td>
              @if ($task->stockMovement)
                #{{ $task->stockMovement->id }} — 
                {{ $task->stockMovement->product->name ?? 'Product' }} | Qty: {{ $task->stockMovement->quantity }}
              @else
                —
              @endif
            </td>
            <td>
              <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger"
                  onclick="return confirm('Are you sure you want to delete this task?')">
                  Delete
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center">No tasks found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>

    <a href="{{ route('tasks.create') }}" class="btn btn-success">+ Assign New Task</a>
  </div>
</x-app>