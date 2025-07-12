<x-app-layout>
    {{-- Page Header --}}
    <div class="mb-4">
        <h1 class="h2">Dashboard</h1>
        <p class="text-muted">Welcome back, {{ Auth::user()->firstname }}!</p>
    </div>

    {{-- The grid of action cards --}}
    <div class="row">
        @forelse ($cards as $card)
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <x-dashboard-card
                    :title="$card['title']"
                    :description="$card['description']"
                    :icon="$card['icon']"
                    :route="$card['route']"
                    :count="$card['count']"
                    :countLabel="$card['count_label']"
                />
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <p class="mb-0">There are no actions available for your role at this time.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</x-app-layout>