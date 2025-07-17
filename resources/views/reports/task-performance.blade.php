<x-app>
  <div class="container py-4">
    <h1>✅ Task Performance Report</h1>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Type</th>
          <th>Priority</th>
          <th>Status</th>
          <th>Deadline</th>
          <th>Employee</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($tasks as $task)
          <tr>
            <td>{{ $task->id }}</td>
            <td>{{ $task->type }}</td>
            <td>{{ $task->priority }}</td>
            <td>{{ $task->status }}</td>
            <td>{{ $task->deadline }}</td>
            <td>{{ $task->employee->name ?? '-' }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="6">No tasks found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</x-app>