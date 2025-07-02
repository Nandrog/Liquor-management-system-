<x-app-layout>
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Supplier Overview</h1>
        {{-- You can add a "Contact All Suppliers" button here later --}}
        <a href="#" class="btn btn-info">Contact Suppliers</a>
    </div>

    <div class="accordion" id="supplierAccordion">
        @forelse ($suppliers as $supplier)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading-{{ $supplier->id }}">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $supplier->id }}" aria-expanded="false" aria-controls="collapse-{{ $supplier->id }}">
                        <div class="d-flex justify-content-between w-100 me-3">
                            <span class="fw-bold">{{ $supplier->company_name }}</span>
                            <span class="text-muted">
                                Total Units Supplied: 
                                {{-- Calculate total quantity by summing up all items from all purchases --}}
                                <span class="badge bg-primary rounded-pill">
                                    {{ number_format($supplier->purchases->flatMap->items->sum('quantity')) }}
                                </span>
                            </span>
                        </div>
                    </button>
                </h2>
                <div id="collapse-{{ $supplier->id }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $supplier->id }}" data-bs-parent="#supplierAccordion">
                    <div class="accordion-body">
                        <h6 class="mb-3">Purchase History for {{ $supplier->company_name }}</h6>
                        @if($supplier->purchases->isEmpty())
                            <p class="text-muted">No purchase history found for this supplier.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Product Supplied</th>
                                            <th class="text-end">Quantity</th>
                                            <th>Destination Warehouse</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- We need to loop through purchases, and then loop through the items in each purchase --}}
                                        @foreach ($supplier->purchases as $purchase)
                                            @foreach ($purchase->items as $item)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M, Y') }}</td>
                                                    <td>{{ $item->product->name }}</td>
                                                    <td class="text-end">{{ $item->quantity }} {{ $item->product->unit_of_measure }}s</td>
                                                    <td>{{ $purchase->warehouse->name }}</td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body">
                    <p class="text-center mb-0">No suppliers found in the system.</p>
                </div>
            </div>
        @endforelse
    </div>

</x-app-layout>