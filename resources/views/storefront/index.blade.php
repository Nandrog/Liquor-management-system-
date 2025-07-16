<x-app-layout>
    {{-- We don't need a traditional header for this full-page experience --}}

    @push('styles')
    <style>
        /* --- Main Page Styling --- */
        .liquor-shelf-page {
            background-color: #1a1a1d; /* Dark charcoal background */
            background-image: linear-gradient(rgba(247, 203, 7, 0.95), rgba(26, 26, 29, 0.95)), url('https://www.transparenttextures.com/patterns/dark-matter.png'); /* Subtle texture */
            color: #f8f9fa;
            min-height: 100vh;
        }

        /* --- Shelf Styling --- */
        .shelf {
            background: #4a2c2a; /* Dark wood color */
            border-radius: 5px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.5),
                        inset 0 -2px 5px rgba(0,0,0,0.3);
            padding: 0 20px 20px 20px;
            margin-top: 4rem;
            position: relative;
        }

        .shelf-title {
            color: #c9a25e; /* Gold color for titles */
            font-family: 'Garamond', serif;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: bold;
            padding: 10px 20px;
            background-color: rgba(0,0,0,0.3);
            border-bottom: 1px solid #c9a25e;
            display: inline-block;
            margin-bottom: 2rem;
            position: relative;
            z-index: 10;
        }

        /* --- Blade/PHP Product Card Styling --- */
        .product-bottle {
            transition: transform 0.3s ease, filter 0.3s ease;
            text-align: center;
            position: relative;
            padding: 15px;
        }
        .product-bottle:hover {
            transform: scale(1.05);
        }
        .product-bottle img {
            max-height: 280px;
            object-fit: contain;
            filter: drop-shadow(5px 5px 10px rgba(0, 0, 0, 0.6));
            transition: filter 0.3s ease;
        }
        .product-bottle:hover img {
            filter: drop-shadow(0 0 15px #c9a25e); /* Glow effect */
        }
        .product-info {
            position: absolute;
            bottom: -50px;
            left: 0;
            right: 0;
            opacity: 0;
            transition: opacity 0.3s ease, bottom 0.3s ease;
            text-align: center;
        }
        .product-bottle:hover .product-info {
            opacity: 1;
            bottom: 5px;
        }
        .product-name {
            font-weight: 600;
            font-size: 1rem;
            margin-top: 10px;
            color: #fff;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.8);
        }
        .product-price {
            font-weight: bold;
            font-size: 1.2rem;
            color: #c9a25e;
        }
        .add-to-cart-btn {
            background-color: #c9a25e;
            color: #1a1a1d;
            border: none;
            font-weight: bold;
        }
        .add-to-cart-btn:hover {
            background-color: #dfb879;
            color: #000;
        }

        /* --- JavaScript Product Card Styling --- */
        .js-product-card {
            background-color: #2d2d31;
            border-radius: 8px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            height: 100%;
            box-shadow: 0 4px 8px rgba(0,0,0,0.4);
            border: 1px solid #333;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .js-product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(201, 162, 94, 0.15);
        }
        .js-product-card .product-image {
            width: 100%;
            height: 150px;
            object-fit: contain;
            margin-bottom: 15px;
        }
        .js-product-card .product-name {
            font-size: 0.9rem;
            color: #f8f9fa;
            line-height: 1.3;
            flex-grow: 1;
        }
        .js-product-card .product-price {
            font-size: 1.1rem;
        }
        .js-product-card .add-to-cart-button {
            width: 100%;
            position: relative;
        }
        .js-product-card .added-to-cart {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #dfb879;
            color: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.25rem;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }
        .js-product-card .added-to-cart.visible {
            opacity: 1;
        }
        .js-product-card .added-to-cart img {
            height: 16px;
            margin-right: 8px;
        }
    </style>
    @endpush

    <div class="liquor-shelf-page">
        <div class="container py-5">
            <div class="text-center mb-4">
                <h1 class="display-4" style="font-family: 'Garamond', serif; color: #c9a25e;">THE CELLAR</h1>
                <p class="lead" style="color: #ccc;">Select your spirits of choice. Cart: <span class="js-cart-quantity">0</span></p>
            </div>

            {{-- SHELF 1: WHISKEYS & BOURBONS --}}
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <div class="shelf">
                    <h2 class="shelf-title">All Whiskeys & Bourbons</h2>
                    <div class="row gx-0 gy-5">
                        @foreach ($products->where('category', 'Whiskey') as $product)
                            <div class="col-lg-2 col-md-3 col-6">
                                <div class="product-bottle">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                    <div class="product-info">
                                        <div class="product-name">{{ $product->name }}</div>
                                        <div class="product-price">UGX {{ number_format($product->unit_price, 0) }}</div>
                                        <button type="submit" name="product_id" value="{{ $product->id }}" class="btn btn-sm add-to-cart-btn mt-2">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </form>
            <div class="shelf">
                <h2 class="shelf-title">Featured Whiskeys & Bourbons</h2>
                <div class="row gx-3 gy-4 js-products-grid-whiskey">
                    {{-- JS will inject featured whiskey products here --}}
                </div>
            </div>


            {{-- SHELF 2: VODKAS & GINS --}}
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <div class="shelf">
                    <h2 class="shelf-title">All Vodkas & Gins</h2>
                    <div class="row gx-0 gy-5">
                        @foreach ($products->whereIn('category', ['Vodka', 'Gin']) as $product)
                            <div class="col-lg-2 col-md-3 col-6">
                                <div class="product-bottle">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                    <div class="product-info">
                                        <div class="product-name">{{ $product->name }}</div>
                                        <div class="product-price">UGX {{ number_format($product->unit_price, 0) }}</div>
                                        <button type="submit" name="product_id" value="{{ $product->id }}" class="btn btn-sm add-to-cart-btn mt-2">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </form>
            <div class="shelf">
                <h2 class="shelf-title">Featured Vodkas & Gins</h2>
                <div class="row gx-3 gy-4 js-products-grid-vodka-gin">
                    {{-- JS will inject featured vodka/gin products here --}}
                </div>
            </div>


            {{-- SHELF 3: BEERS & CIDERS --}}
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <div class="shelf">
                    <h2 class="shelf-title">All Beers & Ciders</h2>
                    <div class="row gx-0 gy-5">
                        @foreach ($products->whereIn('category', ['Beer', 'Cider']) as $product)
                            <div class="col-lg-2 col-md-3 col-6">
                                <div class="product-bottle">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                    <div class="product-info">
                                        <div class="product-name">{{ $product->name }}</div>
                                        <div class="product-price">UGX {{ number_format($product->unit_price, 0) }}</div>
                                        <button type="submit" name="product_id" value="{{ $product->id }}" class="btn btn-sm add-to-cart-btn mt-2">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </form>
            <div class="shelf">
                <h2 class="shelf-title">Featured Beers & Ciders</h2>
                <div class="row gx-3 gy-4 js-products-grid-beer-cider">
                    {{-- JS will inject featured beer/cider products here --}}
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
// Use Blade to define a base path to your public/images directory.
const imageBasePath = "{{ asset('images/') }}";
// It's also good practice to do this for icons.
const iconBasePath = "{{ asset('icons/') }}";

// Data now uses only the filename.
// IMPORTANT: Make sure you have files with these exact names in your `public/images/` folder.
const featuredProducts = [
  {
    id: "fw01",
    image: "whiskey-bourbon.jpg",
    name: "Oak Barrel Reserve Bourbon",
    price: 95500,
    category: "Whiskey"
  },
  {
    id: "fw02",
    image: "whiskey-single-malt.jpg",
    name: "OBAN Single Malt Scotch Whisky",
    price: 362000,
    category: "Whiskey"
  },
  {
    id: "fw03",
    image: "Hinch-Peated-Single-Malt.jpg",
    name: "Hinch's Peated Single Malt",
    price: 762000,
    category: "Whiskey"
  },
  {
    id: "fw04",
    image: "whiskey-single-malt.jpg",
    name: "Smoky Peat Single Malt",
    price: 162000,
    category: "Whiskey"
  },
  {
    id: "fw05",
    image: "Hennessy.jpg",
    name: "Hennessy VS Cognac",
    price: 592000,
    category: "Whiskey"
  },
  {
    id: "fw06",
    image: "uganda-waragi.jpg",
    name: "Uganda Waragi premium",
    price: 72000,
    category: "Whiskey"
  },
  {
    id: "fw07",
    image: "Captain-morgan.jpg",
    name: "Captain morgan premium",
    price: 70000,
    category: "Whiskey"
  },
  {
    id: "fw08",
    image: "uganda-waragi.jpg",
    name: "Uganda Waragi lemon and ginger",
    price: 82000,
    category: "Whiskey"
  },
  {
    id: "fw09",
    image: "Jameson.jpg",
    name: "Jameson Irish Whiskey",
    price: 82000,
    category: "Whiskey"
  },
  {
    id: "fw010",
    image: "black-label.jpg",
    name: "Black Label Scotch Whisky",
    price: 582000,
    category: "Whiskey"
  },
  {
    id: "fw011",
    image: "red-label.jpg",
    name: "Red Label Scotch Whisky",
    price: 482000,
    category: "Whiskey"
  },
  {
    id: "fw012",
    image: "grants.jpg",
    name: "Grant's Family Reserve",
    price: 92000,
    category: "Whiskey"
  },
  {
    id: "fvg01",
    image: "vodka-crystal.jpg",
    name: "Arctic Crystal Vodka",
    price: 78900,
    category: "Vodka"
  },
  {
    id: "fvg02",
    image: "gin-botanical.jpg",
    name: "Botanical Garden Gin",
    price: 84500,
    category: "Gin"
  },
    {
        id: "fvg03",
        image: "blue-label.jpg",
        name: "blue label",
        price: 78900,
        category: "Vodka"
    },
    {
        id: "fvg04",
        image: "bailey.jpg",
        name: "Bailey",
        price: 94500,
        category: "Gin"
    },
    {
        id: "fvg05",
        image: "Malibu.jpg",
        name: "Malibu",
        price: 79900,
        category: "Vodka"
    },
    {
        id: "fvg06",
        image: "bombay-sapphire.jpg",
        name: "Bombay Sapphire",
        price: 84700,
        category: "Gin"
    },
    {
    id: "fbc01",
    image: "bell-beer.jpg",
    name: "Bell IPA 6-Pack",
    price: 38900,
    category: "Beer"
  },
  {
    id: "fbc02",
    image: "tusker.jpg",
    name: "Tusker 4-Pack",
    price: 35500,
    category: "Cider"
  },
    {
        id: "fbc03",
        image: "nile.jpg",
        name: "Nile special IPA 6-Pack",
        price: 38900,
        category: "Beer"
    },
    {
        id: "fbc04",
        image: "smirnoff-4.jpg",
        name: "Smirnoff 4-Pack",
        price: 35500,
        category: "Cider"
    },
    {
    id: "fbc05",
    image: "smirnoff.jpg",
    name: "Smirnoff 6-Pack",
    price: 35500,
    category: "Cider"
  },
    {
        id: "fbc06",
        image: "club.jpg",
        name: "Club Beer IPA 6-Pack",
        price: 38900,
        category: "Beer"
    },
];

// --- Client-Side Cart Logic (unchanged) ---
let cart = [];
let addedMessageTimeouts = {};

function addToCart(productId) {
    let matchingItem;
    cart.forEach((cartItem) => {
        if (productId === cartItem.productId) {
            matchingItem = cartItem;
        }
    });

    if (matchingItem) {
        matchingItem.quantity += 1;
    } else {
        cart.push({ productId: productId, quantity: 1 });
    }
    updateCartQuantity();
    showAddedMessage(productId);
}

function updateCartQuantity() {
    let cartQuantity = 0;
    cart.forEach((cartItem) => {
        cartQuantity += cartItem.quantity;
    });
    document.querySelector('.js-cart-quantity').innerHTML = cartQuantity;
}

function showAddedMessage(productId) {
    const addedMessage = document.querySelector(`.js-added-to-cart-${product.id}`);
    if (!addedMessage) return;
    addedMessage.classList.add('visible');
    if (addedMessageTimeouts[productId]) {
        clearTimeout(addedMessageTimeouts[productId]);
    }
    const timeoutId = setTimeout(() => {
        addedMessage.classList.remove('visible');
    }, 2000);
    addedMessageTimeouts[productId] = timeoutId;
}

// --- DOM Rendering ---
document.addEventListener('DOMContentLoaded', () => {

    function renderProducts(targetSelector, categories) {
        const container = document.querySelector(targetSelector);
        if (!container) return;

        const productsToRender = featuredProducts.filter(product => categories.includes(product.category));

        let productsHTML = '';
        productsToRender.forEach((product) => {
            // Construct the full, correct URL for the product image and checkmark icon
            const imageUrl = `${imageBasePath}/${product.image}`;
            const checkmarkUrl = `${iconBasePath}/checkmark.png`; // Assumes checkmark.png is in public/icons/

            const formattedPrice = new Intl.NumberFormat().format(product.price);

            productsHTML += `
                <div class="col-xl-2 col-lg-3 col-md-4 col-6 d-flex align-items-stretch">
                    <div class="js-product-card">
                        <img class="product-image" src="${imageUrl}" alt="${product.name}">

                        <div class="product-name">
                            ${product.name}
                        </div>

                        <div class="product-price mt-2">
                            UGX ${formattedPrice}
                        </div>

                        <div class="mt-auto pt-3">
                            <button class="btn add-to-cart-btn add-to-cart-button js-add-to-cart-button" data-product-id="${product.id}">
                                Add to Cart
                                <div class="added-to-cart js-added-to-cart-${product.id}">
                                    <img src="${checkmarkUrl}" alt="">
                                    Added
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        container.innerHTML = productsHTML;
    }

    // --- Render all the featured product sections ---
    renderProducts('.js-products-grid-whiskey', ['Whiskey', 'Bourbon']);
    renderProducts('.js-products-grid-vodka-gin', ['Vodka', 'Gin']);
    renderProducts('.js-products-grid-beer-cider', ['Beer', 'Cider']);

    // --- Attach Event Listeners to all newly created buttons ---
    document.querySelectorAll('.js-add-to-cart-button').forEach((button) => {
        button.addEventListener('click', () => {
            const { productId } = button.dataset;
            addToCart(productId);
        });
    });
});
</script>
@endpush

</x-app-layout>