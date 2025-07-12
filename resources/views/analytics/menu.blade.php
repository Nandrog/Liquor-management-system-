{{-- resources/views/analytics/menu.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Analytics Menu') }}
        </h2>
    </x-slot>

    <div class="py-4 px-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h4 class="mb-3">Choose an Analytics Option</h4>
                <ul class="list-group">
                    <li class="list-group-item">
                        <a href="{{ route('analytics.forecast') }}">
                            <i class="bi bi-graph-up me-2"></i> Forecasting
                        </a>
                    </li>
                    <li class="list-group-item">
                        <a href="{{ route('analytics.segmentation') }}">
                            <i class="bi bi-people-fill me-2"></i> Customer Segmentation
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
