<?php

namespace App\Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FiItemController extends Controller
{
     public function index()
    {
        $products = Product::with('stockLevels')->get();
        $groupedProducts = $products->groupBy('type');
        $finishedGoods = $groupedProducts->get('finished_good', collect());
        $rawMaterials = $groupedProducts->get('raw_material', collect());

        $finishedGoodsValue = $finishedGoods->reduce(function ($carry, $product) {
        $totalQuantity = $product->stockLevels->sum('quantity');
        return $carry + ($totalQuantity * $product->unit_price);
    }, 0);

    // Calculate total inventory value for Raw Materials.
    $rawMaterialsValue = $rawMaterials->reduce(function ($carry, $product) {
        $totalQuantity = $product->stockLevels->sum('quantity');
        return $carry + ($totalQuantity * $product->unit_price);
    }, 0);

        return view('finance.items.index', [
            'finishedGoods' => $finishedGoods,
            'rawMaterials' => $rawMaterials,
            'finishedGoodsValue' => $finishedGoodsValue, // <-- Now this variable exists
            'rawMaterialsValue' => $rawMaterialsValue,
        ]);
    }

    public function updatePrice(Request $request, Product $product)
    {
      // 1. Get the authenticated user.
        $user = Auth::user();

        // 2. Perform the authorization check directly.
        // If the user does not have the 'Finance' role, abort the request
        // and show a 403 Forbidden error page.
        if (!$user || !$user->hasRole('Finance')) {
            abort(403, 'Unauthorized action.');
        }

        // 3. If the check passes, proceed with validation and update.
        $validated = $request->validate([
            'unit_price' => 'required|numeric|min:0',
        ]);

        $product->update([
            'unit_price' => $validated['unit_price'],
        ]);

        return back()->with('success', "Price for '{$product->name}' updated successfully.");
    }
}
