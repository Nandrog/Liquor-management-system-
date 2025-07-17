<x-app>
  <div class="container py-4">
    <h1 class="mb-4">🕒 ALL SCHEDULED SHIFTS</h1>

    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Employee</th>
          <th>Start</th>
          <th>End</th>
          <th>Break Hours</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($shifts as $shift)
          <tr>
            <td>{{ $shift->id }}</td>
            <td>{{ $shift->employee->name ?? 'N/A' }}</td>
            <td>{{ \Carbon\Carbon::parse($shift->start_time)->format('M d, Y H:i') }}</td>
            <td>{{ \Carbon\Carbon::parse($shift->end_time)->format('M d, Y H:i') }}</td>
            <td>{{ $shift->break_hours ?? 0 }} hrs</td>
            <td>
              <form action="{{ route('shifts.destroy', $shift->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this shift?')">
                  Delete
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center">No shifts scheduled.</td>
          </tr>
        @endforelse
      </tbody>
    </table>

    <a href="{{ route('shifts.create') }}" class="btn btn-success">+ Assign New Shift</a>
  </div>
</x-app>