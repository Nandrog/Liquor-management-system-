<x-app-layout>

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Stock Movements</h1>
    </div>

        {{-- Display Success Messages --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Display Error Messages from the 'catch' block --}}
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- Display ALL Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops! Something went wrong with your input.</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- 1. Warehouse Summary Table --}}
    <h3 class="h4 mb-3">Warehouse Summary</h3>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th>Warehouse Name</th>
                            <th>Location</th>
                            <th class="text-end">Total Quantity of All Items</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($warehouses as $warehouse)
                            <tr>
                                <td>{{ $warehouse->name }}</td>
                                <td>{{ $warehouse->location }}</td>
                                <td class="text-end fw-bold">{{ $warehouse->stockLevels->sum('quantity') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 2. Button to Toggle Transfer Form --}}
    <div class="text-center my-4">
        <button class="btn btn-primary btn-lg" type="button" data-bs-toggle="collapse" data-bs-target="#transferFormCollapse" aria-expanded="false" aria-controls="transferFormCollapse">
            <i class="bi bi-truck me-2"></i> Initiate New Stock Transfer
        </button>
    </div>

    {{-- Collapsible Transfer Form --}}
    <div class="collapse" id="transferFormCollapse">
        <div class="card card-body shadow-sm mb-5">
            <h4 class="mb-3">New Transfer Details</h4>
            {{-- Display validation errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('officer.stock_movements.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="product_id" class="form-label">Product to Move</label>
                        <select class="form-select" name="product_id" id="product_id" required>
                            <option value="">Select a product...</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="quantity" class="form-label">Quantity to Move</label>
                        <input type="number" class="form-control" name="quantity" id="quantity" required min="1">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="from_warehouse_id" class="form-label">From Warehouse</label>
                        <select class="form-select" name="from_warehouse_id" id="from_warehouse_id" required>
                            <option value="">Select source...</option>
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->warehouse_id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="to_warehouse_id" class="form-label">To Warehouse</label>
                        <select class="form-select" name="to_warehouse_id" id="to_warehouse_id" required>
                             <option value="">Select destination...</option>
                            @foreach ($warehouses as $warehouse)
                                <option value="{{ $warehouse->warehouse_id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="notes" class="form-label">Notes (Optional)</label>
                    <textarea class="form-control" name="notes" id="notes" rows="2"></textarea>
                </div>
                <button type="submit" class="btn btn-success">Confirm Transfer</button>
            </form>
        </div>
    </div>

    {{-- 3. Stock Movement History Log --}}
    <h3 class="h4 mb-3">Movement History</h3>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Product</th>
                            <th class="text-end">Quantity</th>
                            <th>From</th>
                            <th>To</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($movements as $movement)
                            <tr>
                                <td>{{ $movement->moved_at->format('d M Y, H:i') }}</td>
                                <td>{{ $movement->product->name }}</td>
                                <td class="text-end">{{ $movement->quantity }}</td>
                                <td>{{ $movement->fromWarehouse->name }}</td>
                                <td>{{ $movement->toWarehouse->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">No stock movements have been recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $movements->links() }}
            </div>
        </div>
    </div>
</x-app-layout>