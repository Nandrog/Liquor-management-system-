@props(['product'])


{{-- The main card element --}}
<div class="card h-100 product-card border-0 shadow-sm">
    
    {{-- 1. Image Wrapper: This is new. We'll apply the clip-path to this div. --}}
    <div class="product-card-image-wrapper">
        <img src="{{ asset('images/' . ($product->image_filename ?? 'default.jpg')) }}" 
             class="product-card-img" 
             alt="{{ $product->name }}">
    </div>

    {{-- 2. Card Body: Now uses flexbox to control layout --}}
    <div class="card-body d-flex flex-column">
        <h5 class="card-title">{{ $product->name }}</h5>
        <p class="card-text text-muted">{{ $product->description }}</p>
        
        {{-- 3. Price: The 'mt-auto' class pushes this to the bottom of the card --}}
        <p class="card-price mt-auto">Sh. {{ number_format($product->unit_price, 0) }}</p>
    </div>
    
    {{-- The card-footer with the "Add to Cart" button has been removed to match your design --}}
</div>