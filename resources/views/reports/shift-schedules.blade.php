<x-app>
  <div class="container py-4">
    <h1>🕒 Shift Schedule Report</h1>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Employee</th>
          <th>Start Time</th>
          <th>End Time</th>
          <th>Break Hours</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($shifts as $shift)
          <tr>
            <td>{{ $shift->id }}</td>
            <td>{{ $shift->employee->name ?? '-' }}</td>
            <td>{{ $shift->start_time }}</td>
            <td>{{ $shift->end_time }}</td>
            <td>{{ $shift->break_hours }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="5">No shifts found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</x-app>