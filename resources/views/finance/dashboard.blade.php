<x-app-layout>
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Home</h1>
    </div>

    <h2 class="h4 mb-3">Overview</h2>
    {{--<div class="row">
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
    </div>--}}


</x-app-layout>