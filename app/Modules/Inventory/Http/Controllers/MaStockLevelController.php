<?php

namespace App\Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\StockLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaStockLevelController extends Controller
{
    /**
     * Display a paginated list of stock levels for the manufacturer's
     * assigned factory warehouse.
     */
   public function index()
    {
        $user = Auth::user();
        $user->load('productionPlant.warehouse');
        
        if (!$user->productionPlant || !$user->productionPlant->warehouse) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not assigned to a factory with a warehouse.');
        }

        $warehouse = $user->productionPlant->warehouse;

        // --- THIS IS THE MODIFIED LOGIC ---

        // 1. Fetch ALL stock levels for the specific warehouse.
        // Eager load the product relationship so we can access its 'type'.
        $allStockForWarehouse = StockLevel::with('product')
            ->where('warehouse_id', $warehouse->warehouse_id)
            ->get(); // Use get() to fetch the full collection for grouping

        // 2. Group the results by the product's type.
        $groupedStock = $allStockForWarehouse->groupBy('product.type');

        // 3. Extract the two collections.
        $finishedGoods = $groupedStock->get('finished_good', collect());
        $rawMaterials = $groupedStock->get('raw_material', collect());
        
        // --- END OF MODIFIED LOGIC ---

        // 4. Return the view, passing the warehouse and the two new collections.
        return view('manufacturer.stock_levels.index', [
            'warehouse' => $warehouse,
            'finishedGoods' => $finishedGoods,
            'rawMaterials' => $rawMaterials,
        ]);
    }
}
