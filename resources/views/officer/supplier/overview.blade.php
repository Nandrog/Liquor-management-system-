<x-app-layout>
<div class="accordion" id="supplierAccordion">
    @forelse ($suppliers as $supplierUser) {{-- The variable is now a User object --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading-{{ $supplierUser->id }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $supplierUser->id }}">
                    <div class="d-flex justify-content-between w-100 me-3">
                        {{-- Display the user's name or username --}}
                        <span class="fw-bold">{{ $supplierUser->username }} ({{ $supplierUser->firstname }} {{ $supplierUser->lastname }})</span>
                        <span class="text-muted">
                            Total Units Supplied: 
                            <span class="badge bg-primary rounded-pill">
                                {{-- Use the new relationship name to calculate the total --}}
                                {{ number_format($supplierUser->suppliedPurchases->flatMap->items->sum('quantity')) }}
                            </span>
                        </span>
                    </div>
                </button>
            </h2>
            <div id="collapse-{{ $supplierUser->id }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $supplierUser->id }}">
                <div class="accordion-body">
                    <h6 class="mb-3">Purchase History for {{ $supplierUser->username }}</h6>
                    @if($supplierUser->suppliedPurchases->isEmpty()) {{-- Use the new relationship name --}}
                        <p class="text-muted">No purchase history found for this supplier.</p>
                    @else
                        {{-- ... table HTML ... --}}
                                <tbody>
                                    @foreach ($supplierUser->suppliedPurchases as $purchase) {{-- Use the new relationship name --}}
                                        @foreach ($purchase->items as $item)
                                            <tr>
                                                {{-- ... table data ... --}}
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                        {{-- ... --}}
                    @endif
                </div>
            </div>
        </div>
    @empty
        {{-- ... --}}
    @endforelse
</div>
</x-app-layout>