<x-app>
  <div class="container py-4">
    <h1>🚚 Stock Movements Report</h1>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Product</th>
          <th>From Warehouse</th>
          <th>To Warehouse</th>
          <th>Quantity</th>
          <th>Moved At</th>
          <th>Employee</th>
          <th>Notes</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($movements as $move)
          <tr>
            <td>{{ $move->id }}</td>
            <td>{{ $move->product->name ?? '-' }}</td>
            <td>{{ $move->fromWarehouse->name ?? '-' }}</td>
            <td>{{ $move->toWarehouse->name ?? '-' }}</td>
            <td>{{ $move->quantity }}</td>
            <td>{{ $move->moved_at }}</td>
            <td>{{ $move->employee->name ?? '-' }}</td>
            <td>{{ $move->notes }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="8">No stock movements found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</x-app>