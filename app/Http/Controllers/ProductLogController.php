<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductLogController extends Controller
{
    /**
     * Display a list of all products to manage stock.
     */
    public function index()
    {
        //dd('SUCCESS: You are running the NEW ProductController index method!');
        // Only roles that manage inventory should see this page
        if (!Auth::user()->hasAnyRole(['Admin', 'Procurement Officer', 'Liquor Manager'])) {
            abort(403, 'Unauthorized Access');
        }

        // We will list both finished goods and raw materials for stock management
        $products = Product::orderBy('type')->orderBy('name')->get();

        return view('products.index', compact('products'));
    }

    /**
     * Add a specified quantity to a product's stock.
     */
    public function addStock(Request $request, Product $product)
    {
        // 1. Validation: Ensure the input is a valid, positive number.
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // 2. The Core Logic: Increment the stock column.
        // This is safer than doing `stock = stock + ...` as it's an atomic operation.
        $product->increment('stock', $validated['quantity']);

        // 3. Redirect back with a success message for the user.
        return redirect()->back()->with('success', $validated['quantity'] . ' units of ' . $product->name . ' have been added to stock.');
    }
}
