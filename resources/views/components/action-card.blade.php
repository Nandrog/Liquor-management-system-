@props(['title', 'description', 'icon', 'route'])

<a href="{{ $route }}" class="action-card text-decoration-none">
    <div class="action-card-icon">
        <i class="{{ $icon }}"></i>
    </div>
    <div class="action-card-body">
        <h5 class="action-card-title">{{ $title }}</h5>
        <p class="action-card-description">{{ $description }}</p>
    </div>
</a>