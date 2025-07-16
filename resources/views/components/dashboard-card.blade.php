@props([
    'title',
    'description',
    'icon',
    'route',
    'count' => null,              // Default to null if not provided
    'countLabel' => null,         // Default to null
    'secondaryCount' => null,     // Default to null
    'secondaryCountLabel' => null,])

<a href="{{ $route }}" class="dashboard-action-card text-decoration-none d-block h-100">
    <div class="card h-100">
        <div class="card-body">
            <div class="d-flex align-items-start">
                <div class="card-icon me-3">
                    <i class="{{ $icon }}"></i>
                </div>
                <div class="flex-grow-1">
                    <h5 class="card-title">{{ $title }}</h5>
                    <p class="card-text text-muted">{{ $description }}</p>
                </div>
            </div>
        </div>
        @if(isset($count))
        <div class="card-footer bg-light border-top-0">
            <div class="d-flex justify-content-between align-items-center">
                <span class="small">{{ $countLabel }}</span>
                <span class="fw-bold h5 mb-0">{{ $count }}</span>
            </div>
        </div>
        @endif
    </div>
</a>