@props(['title', 'value', 'description', 'icon'])

<div class="card h-100 shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <div>
                <h5 class="card-title text-muted">{{ $title }}</h5>
                <p class="card-text h2 fw-bold">{{ $value }}</p>
                <p class="card-text"><small class="text-muted">{{ $description }}</small></p>
            </div>
            @if(isset($icon))
                <i class="bi {{ $icon }} h1 text-muted"></i>
            @endif
        </div>
         <a href="#" class="stretched-link">See all</a>
    </div>
</div>>