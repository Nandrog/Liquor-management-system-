<x-app>
  <div class="container py-4">
    <h1 class="mb-4">🕒 SCHEDULE A SHIFT</h1>

    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('shifts.store') }}" method="POST" class="card p-4 shadow-sm">
      @csrf

      <div class="mb-3">
        <label for="employee_id" class="form-label">Employee</label>
        <select name="employee_id" id="employee_id" class="form-select">
          @foreach($employees as $emp)
            <option value="{{ $emp->id }}">{{ $emp->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label for="start_time" class="form-label">Start Time</label>
        <input type="datetime-local" name="start_time" id="start_time" class="form-control" value="{{ old('start_time') }}">
      </div>

      <div class="mb-3">
        <label for="end_time" class="form-label">End Time</label>
        <input type="datetime-local" name="end_time" id="end_time" class="form-control" value="{{ old('end_time') }}">
      </div>

      <div class="mb-3">
        <label for="break_hours" class="form-label">Break Hours</label>
        <input type="number" name="break_hours" id="break_hours" class="form-control" step="0.5" value="{{ old('break_hours') }}">
      </div>

      <button type="submit" class="btn btn-primary">Assign Shift</button>
    </form>
  </div>
</x-app>