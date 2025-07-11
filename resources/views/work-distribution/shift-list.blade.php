<x-app>
  <div class="container py-4">
    <h1 class="mb-4">🕒 ALL SCHEDULED SHIFTS</h1>

    <table class="table table-bordered table-hover">
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
        @forelse ($shifts as $shift)
          <tr>
            <td>{{ $shift->id }}</td>
            <td>{{ $shift->employee->name ?? 'N/A' }}</td>
            <td>{{ $shift->start_time }}</td>
            <td>{{ $shift->end_time }}</td>
            <td>{{ $shift->break_hours }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center">No shifts scheduled.</td>
          </tr>
        @endforelse
      </tbody>
    </table>

    <a href="{{ route('shifts.create') }}" class="btn btn-success">+ Assign New Shift</a>
  </div>
</x-app>