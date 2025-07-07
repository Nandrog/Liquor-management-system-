<x-app-layout>
    {{-- Page Header --}}
    <div class="mb-4">
        <h1 class="h2">Product Creation</h1>
        <p class="text-muted">Convert raw materials into finished goods for: <span class="fw-bold">Uganda Waragi 750ml</span></p>
    </div>

    {{-- Session Feedback --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
            @if(session('errors'))
                <ul class="mb-0 mt-2">
                    @foreach(session('errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif

    <div class=class="card shadow-sm mb-5">
        <div class="card-body">
            <h5 class="card-title">New Production Run</h5>
            <p>Enter the number of crates you wish to produce. One crate contains 24 bottles.</p>
            
            <form action="{{ route('manufacturer.production.store') }}" method="POST">
@csrf
<div class="row align-items-end">
<div class="col-md-4">
<label for="product_id" class="form-label">Finished Good to Produce</label>
<select class="form-select" id="product_id" name="product_id" required>
<option value="">Choose item...</option>
@foreach($producibleItems as $item)
<option value="{{ $item->id }}">{{ $item->name }}</option>
@endforeach
</select>
</div>
<div class="col-md-3">
<label for="crates" class="form-label">Number of Crates</label>
<input type="number" class="form-control" id="crates" name="crates" min="1" required>
</div>
<div class="col-md-3">
<button type="submit" class="btn btn-primary">Start Production</button>
</div>
</div>
</form>
        </div>
    </div>

     <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Your Production History</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Date Completed</th>
                            <th>Manufacturer</th>
                            <th>Product Produced</th>
                            <th class="text-end">Quantity Produced</th>
                            <th class="text-end">Cost of Materials</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($productionRuns as $run)
                            <tr>
                                {{-- We use optional() to prevent errors if a related model was deleted --}}
                                <td>{{ \Carbon\Carbon::parse($run->completed_at)->format('d M, Y H:i A') }}</td>
                                <td>{{ optional($run->user)->username }}</td>
                                <td>{{ optional($run->product)->name }}</td>
                                <td class="text-end fw-bold">{{ number_format($run->quantity_produced) }} bottles</td>
                                <td class="text-end">Sh. {{ number_format($run->cost_of_materials, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">You have no production history yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Render pagination links for the history table --}}
            <div class="mt-3">
                {{ $productionRuns->links() }}
            </div>
        </div>
    </div>
</x-app-layout>