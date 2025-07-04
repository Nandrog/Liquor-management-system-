<x-app-layout>
    {{-- Page Header --}}
    <div class="mb-4">
        <h1 class="h2">Inventory</h1>
    </div>

    {{-- The grid of action cards --}}
    <div class="row">
        @forelse ($cards as $card)
            <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-4">
                <x-action-card
                    :title="$card['title']"
                    :description="$card['description']"
                    :icon="$card['icon']"
                    :route="$card['route']"
                />
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <p class="mb-0">You do not have any inventory functions assigned to your role.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</x-app-layout>