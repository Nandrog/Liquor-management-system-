<?php

namespace App\Modules\Product\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('manager.products.index', compact('products'));
    }

    public function create()
    {
        return view('manager.products.create');
    }

    public function store(StoreProductRequest $request)
    {
        Product::create($request->validated());
        return redirect()->route('manager.products.index')->with('success', 'Product created successfully.');
    }
}
