<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse; 
use App\Models\StockLevel;  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductLogController extends Controller
{
    public function index()
    {
        if (!Auth::user()->hasAnyRole(['Admin', 'Procurement Officer', 'Liquor Manager'])) {
            abort(403, 'Unauthorized Access');
        }

        // We need to pass the list of warehouses to the view for the dropdown.
        $warehouses = Warehouse::all();
        $products = Product::with('stockLevels')->orderBy('name')->get();

        // Calculate total stock for display purposes
        foreach ($products as $product) {
            $product->total_stock = $product->stockLevels->sum('quantity');
        }

        return view('products.index', compact('products', 'warehouses'));
    }

    public function addStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'warehouse_id' => 'required|exists:warehouses,warehouse_id', // Validate the selected warehouse
        ]);

        // This is the new, correct logic.
        // It finds the stock level for the given product AND warehouse,
        // or creates a new record if one doesn't exist.
        $stockLevel = StockLevel::firstOrNew([
            'product_id' => $product->id,
            'warehouse_id' => $validated['warehouse_id'],
        ]);

        // Increment the quantity and save.
        $stockLevel->quantity += $validated['quantity'];
        $stockLevel->save();

        return redirect()->back()->with('success', $validated['quantity'] . ' units of ' . $product->name . ' added to stock.');
    }
}
