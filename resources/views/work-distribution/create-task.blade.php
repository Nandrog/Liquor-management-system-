<x-app>
  <div class="container py-4">
    <h1 class="mb-4">📝 ASSIGN A NEW TASK </h1>

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

    <form action="{{ route('tasks.store') }}" method="POST" class="card p-4 shadow-sm">
      @csrf

      <div class="mb-3">
        <label for="type" class="form-label">Task Type</label>
        <input type="text" name="type" id="type" class="form-control" value="{{ old('type') }}">
      </div>

      <div class="mb-3">
        <label for="priority" class="form-label">Priority</label>
        <input type="text" name="priority" id="priority" class="form-control" value="{{ old('priority') }}">
      </div>

      <div class="mb-3">
        <label for="deadline" class="form-label">Deadline</label>
        <input type="datetime-local" name="deadline" id="deadline" class="form-control" value="{{ old('deadline') }}">
      </div>

      <div class="mb-3">
        <label for="employee_id" class="form-label">Assign to Employee</label>
        <select name="employee_id" id="employee_id" class="form-select">
          @foreach($employees as $emp)
            <option value="{{ $emp->id }}">{{ $emp->name }}</option>
          @endforeach
        </select>
      </div>

      <button type="submit" class="btn btn-primary">Assign Task</button>
    </form>
  </div>
</x-app>