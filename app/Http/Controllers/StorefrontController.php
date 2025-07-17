<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;


class StorefrontController extends Controller
{
     public function index(): View
    {
        // We only want to show products that are ready for sale to customers.
       $products = Product::where('type', 'finished_good')
                        ->with('media') // <-- EAGER LOAD THE IMAGES
                        ->get();


        // Return the main storefront view and pass the collection of products to it.
        return view('storefront.index', [
            'products' => $products,
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
