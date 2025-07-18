<x-app-layout>
    {{-- We don't need a traditional header for this full-page experience --}}

    @push('styles')
    <style>
        /* --- Main Page & Shelf Styling --- */
        .liquor-shelf-page { background-color: #1a1a1d; background-image: linear-gradient(rgba(247, 203, 7, 0.95), rgba(26, 26, 29, 0.95)), url('https://www.transparenttextures.com/patterns/dark-matter.png'); color: #f8f9fa; min-height: 100vh; }
        .shelf { background: #4a2c2a; border-radius: 5px; box-shadow: 0 5px 15px rgba(0,0,0,0.5), inset 0 -2px 5px rgba(0,0,0,0.3); padding: 20px; margin-top: 4rem; position: relative; }
        .shelf-title { color: #c9a25e; font-family: 'Garamond', serif; text-transform: uppercase; letter-spacing: 2px; font-weight: bold; padding: 10px 20px; background-color: rgba(0,0,0,0.3); border-bottom: 1px solid #c9a25e; display: inline-block; margin-bottom: 2rem; position: relative; z-index: 10; }

        /* --- Unified Product Card Styling --- */
        .product-card {
            background-color: #2d2d31;
            border-radius: 8px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            height: 100%;
            box-shadow: 0 4px 8px rgba(0,0,0,0.4);
            border: 1px solid #333;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative; /* For the badge */
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(201, 162, 94, 0.15);
        }
        .product-card .product-image {
            width: 100%;
            height: 150px;
            object-fit: contain;
            margin-bottom: 15px;
        }
        .product-card .product-name {
            font-size: 0.9rem;
            color: #f8f9fa;
            line-height: 1.3;
            flex-grow: 1; /* Ensures cards align nicely */
        }
        .product-card .product-price {
            font-size: 1.1rem;
            font-weight: bold;
            color: #c9a25e;
        }
        /* NEW: Style for unavailable price */
        .product-card .price-unavailable {
            font-size: 0.9rem;
            font-style: italic;
            color: #888;
        }
        .add-to-cart-btn {
            background-color: #c9a25e;
            color: #1a1a1d;
            border: none;
            font-weight: bold;
            width: 100%;
        }
        .add-to-cart-btn:hover {
            background-color: #dfb879;
            color: #000;
        }
        /* NEW: Style for disabled button */
        .add-to-cart-btn:disabled {
            background-color: #555;
            color: #888;
            cursor: not-allowed;
        }

        /* --- Featured Badge Styling --- */
        .featured-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #c9a25e;
            color: #1a1a1d;
            padding: 2px 8px;
            font-size: 0.7rem;
            font-weight: bold;
            border-radius: 3px;
            text-transform: uppercase;
            z-index: 10;
        }

        /* --- Cart Icon Styling --- */
        .cart-link { color: #ccc; text-decoration: none; transition: color 0.2s ease; }
        .cart-link:hover { color: #c9a25e; }
        .cart-icon { width: 24px; height: 24px; fill: currentColor; vertical-align: middle; margin-left: 8px; }
    </style>
    @endpush

    <div class="liquor-shelf-page">
        <div class="container py-5">
            <div class="text-center mb-4">
                <h1 class="display-4" style="font-family: 'Garamond', serif; color: #c9a25e;">THE CELLAR</h1>
                <p class="lead" style="color: #097BECFF;">
                    Select your spirits of choice.
                    <a href="{{ route('cart.index') }}" class="cart-link">
                        Cart: <span class="js-cart-quantity">{{ $initialCartTotal ?? 0 }}</span>
                        <svg class="cart-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49A1.003 1.003 0 0 0 20 4H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
                    </a>
                </p>
            </div>

            @php
                $categoryGroups = [
                    'Whiskeys & Bourbons' => ['Whiskey'],
                    'Vodkas & Gins' => ['Vodka', 'Gin'],
                    'Beers & Ciders' => ['Beer', 'Cider'],
                ];
            @endphp

            {{-- A single, smart loop to generate all shelves --}}
            @foreach ($categoryGroups as $title => $categories)
                <div class="shelf">
                    <h2 class="shelf-title">{{ $title }}</h2>
                    <div class="row gx-3 gy-4">
                        @foreach ($products->whereIn('category.name', $categories) as $product)
                            {{-- MODIFICATION START --}}
                            @php
                                // Get the vendor's specific product data. We assume one vendor per product on storefront.
                                $vendorProduct = $product->vendorProducts->first();
                                // Check if a retail price has been set and is greater than zero
                                $priceIsSet = $vendorProduct && isset($vendorProduct->retail_price) && $vendorProduct->retail_price > 0;
                            @endphp
                            {{-- MODIFICATION END --}}

                            <div class="col-xl-2 col-lg-3 col-md-4 col-6 d-flex align-items-stretch">
                                <div class="product-card">
                                    @if($product->is_featured)
                                        <span class="featured-badge">Featured</span>
                                    @endif

                                    <img class="product-image" src="{{ asset('images/' . $product->image_filename) }}" alt="{{ $product->name }}">
                                    <div class="product-name">{{ $product->name }}</div>

                                    {{-- MODIFICATION START: Conditional Price and Button Display --}}
                                    <div class="mt-2">
                                        @if($priceIsSet)
                                            <div class="product-price">UGX {{ number_format($vendorProduct->retail_price, 0) }}</div>
                                        @else
                                            <div class="price-unavailable">Wait for Vendor Price</div>
                                        @endif
                                    </div>
                                    <div class="mt-auto pt-3">
                                        <button class="btn add-to-cart-btn js-add-to-cart-button"
                                                data-product-id="{{ $product->id }}"
                                                {{-- Disable the button if the price is not set --}}
                                                @if(!$priceIsSet) disabled @endif>
                                            Add to Cart
                                        </button>
                                    </div>
                                    {{-- MODIFICATION END --}}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

        </div>
    </div>

@push('scripts')
{{-- The JavaScript does not need to be changed. It already works correctly. --}}
<script>
    console.log('Checkpoint 0: Script rendering.');

    async function addToCart(productId) {
        console.log(`Checkpoint 3: addToCart function called with ID: ${productId}`);
        try {
            const response = await fetch("{{ route('cart.add') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ product_id: productId })
            });

            if (!response.ok) {
                const errorData = await response.json();
                console.error('Network response was NOT ok. Status:', response.status, 'Error:', errorData.message || 'Unknown error');
                if (response.status === 401) {
                    alert('Please log in to add items to your cart.');
                    window.location.href = "{{ route('login') }}";
                }
                return;
            }

            const data = await response.json();
            if (data.success) {
                console.log('Server responded with success:', data);
                updateCartQuantity(data.cart_total);
            } else {
                console.error('The server indicated an error:', data.message);
            }
        } catch (error) {
            console.error('Error adding item to cart:', error);
            alert('A problem occurred while adding the item to your cart.');
        }
    }

    function updateCartQuantity(total) {
        const cartElement = document.querySelector('.js-cart-quantity');
        if (cartElement) {
            cartElement.textContent = total;
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        console.log('Checkpoint 1: DOMContentLoaded event fired.');
        const initialCartTotal = {{ $initialCartTotal ?? 0 }};
        updateCartQuantity(initialCartTotal);

        document.body.addEventListener('click', function(event) {
            const button = event.target.closest('.js-add-to-cart-button');
            if (button && !button.disabled) { // Only proceed if the button is not disabled
                console.log('Checkpoint 2: Button click detected.');
                addToCart(button.dataset.productId);
            }
        });
    });
</script>
@endpush

</x-app-layout>
