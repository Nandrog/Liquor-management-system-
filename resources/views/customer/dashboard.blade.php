<x-app-layout>
    @php
        // Use the asset() helper to generate a foolproof, absolute URL to your image.
        $bgImageUrl = asset('images/backgrounds/customer-hero.jpg');
    @endphp
    <div class="container-fluid">
        {{-- 1. Hero Section with Background Image and Welcome Message --}}
        <div class="hero-section" style="background-image: url('{{ $bgImageUrl }}')">
            <div class="hero-overlay"></div>
            <div class="hero-content text-center">
                <h1 class="display-4 fw-bold">Welcome, {{ $user->firstname }}!</h1>
                <p class="lead">Discover our exclusive collection of premium spirits, beers, and wines.</p>
                <a href="{{ route('storefront.index') }}"  class="colour" class="btn btn-primary btn-lg mt-3">
                    <i class="bi bi-shop me-2"></i>Start Browsing
                </a>
            </div>
        </div>

        {{-- 2. Featured Products Section (you can add this later) --}}
        <div class="container py-5">
            <h2 class="text-center mb-4">Featured Products</h2>
            <div class="row">
    @forelse ($featuredProducts as $product)
        <div class="col-12 col-md-6 col-lg-3 mb-4">
            {{-- Use the new component, passing the product data to it --}}
            <x-product-card :product="$product" />
        </div>
    @empty
        <div class="col-12">
            <p class="text-center text-muted">No featured products available at this time.</p>
        </div>
    @endforelse
        </div>
        </div>
    </div>

    {{-- 3. The Classic Footer --}}
    <footer class="app-footer">
    <div class="container footer-container">
        {{-- The row now only contains two main columns --}}
        <div class="row">
            {{-- Column 1: Slogan --}}
            <div class="col-lg-6 text-center text-lg-start mb-4 mb-lg-0">
                <p class="footer-slogan">Your Partner in Premium Beverages since 1946.</p>
            </div>

            {{-- Column 2: Contact Info --}}
            <div class="col-lg-6 text-center text-lg-end">
                <address class="footer-contact">
                    123 Industrial Area Rd<br>
                    Kampala, Uganda<br>
                    <a href="mailto:contact@ugliquor.com">contact@ugliquor.com</a>
                </address>
            </div>
        </div>

        {{-- Copyright notice remains at the bottom --}}
        <div class="text-center footer-copyright">
            © {{ date('Y') }} Uganda Liquor Breweries. All Rights Reserved.
        </div>
    </div>
</footer>
</x-app-layout>