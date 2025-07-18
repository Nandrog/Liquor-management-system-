<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;


class StorefrontController extends Controller
{
    public function index(): View
    {
      // Fetch ALL products for the main shelves
        $products = Product::with('category','vendorProducts')
                            ->where('type', 'finished_good')
                            ->get();
                             $initialCartTotal = 0;
    if (Auth::check()) {
        // If user is logged in, calculate their total cart quantity from the database
        $initialCartTotal = Cart::where('user_id', Auth::id())->sum('quantity');
    }

    // Fetch ONLY the featured products for the featured shelves
    $featuredProducts = Product::where('is_featured', true)->get();

    // Pass BOTH collections to the view
    return view('storefront.index', [
        'products' => $products,
        'featuredProducts' => $featuredProducts,
        'initialCartTotal' => $initialCartTotal
    ]);
    }

    public function show(Product $product): View
    {
        // Security Check: Ensure a customer cannot view a raw material or other non-public product type
        // by manually changing the URL. If they try, show a "Not Found" error.
        if ($product->type !== 'finished_good') {
            abort(404);
        }

        // Return the single product detail view.
        return view('storefront.show', [
            'product' => $product,
        ]);
    }
}
