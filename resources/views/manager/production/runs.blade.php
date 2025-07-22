<x-app-layout>
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Production Run History by Factory</h1>
        
    </div>

    {{-- Main loop: Iterate through each factory's group of production runs --}}
    @forelse ($runsByFactory as $factoryId => $productionRuns)
        
        {{-- Get the factory name from the first run in the group --}}
        @php
            $factoryName = $productionRuns->first()->factory->name ?? 'Unknown Factory';
        @endphp

        <div class="card shadow-sm mb-5"> {{-- Add a margin between each factory's card --}}
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Factory: <span class="text-primary">{{ $factoryName }}</span></h5>
                <div class="fw-bold">
                    Total Material Cost: 
                    <span class="badge bg-success fs-6">
                        Sh. {{ number_format($factoryTotals[$factoryId], 2) }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Date Completed</th>
                                <th>Product Produced</th>
                                <th class="text-end">Quantity</th>
                                <th>Manufacturer</th>
                                <th class="text-end">Cost of Materials</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Nested loop: Iterate through the runs for THIS factory --}}
                            @foreach ($productionRuns as $run)
                                <tr>
                                    <td>{{ $run->completed_at->format('d M, Y H:i A') }}</td>
                                    <td>{{ optional($run->product)->name }}</td>
                                    <td class="text-end fw-bold">{{ number_format($run->quantity_produced) }} bottles</td>
                                    <td>{{ optional($run->user)->username }}</td>
                                    <td class="text-end">Sh. {{ number_format($run->cost_of_materials, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center py-5">
                <h4 class="text-muted">No production runs have been recorded yet.</h4>
            </div>
        </div>
    @endforelse

</x-app-layout>