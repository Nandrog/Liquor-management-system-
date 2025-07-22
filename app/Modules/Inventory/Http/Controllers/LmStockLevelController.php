<?php

namespace App\Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\StockLevel;
use Illuminate\Http\Request;

class LmStockLevelController extends Controller
{
        public function index()
    {
        // 1. Fetch ALL stock levels, eager loading the product and warehouse relationships.
        // It's crucial to load the product's 'type' for our primary grouping.
        $allStockLevels = StockLevel::with(['product', 'warehouse'])
            ->get(); // Use get() to fetch the full collection for grouping

        // 2. Perform the Primary Grouping: Separate by Product Type.
        // We group by the 'type' attribute on the related Product model.
        $groupedByType = $allStockLevels->groupBy('product.type');

        // 3. Extract the two main collections.
        $finishedGoodsStock = $groupedByType->get('finished_good', collect());
        $rawMaterialsStock = $groupedByType->get('raw_material', collect());

        // 4. Perform the Secondary Grouping on each collection: Group by Warehouse.
        // The key of the resulting collection will be the warehouse NAME.
        $finishedGoodsByWarehouse = $finishedGoodsStock->groupBy('warehouse.name');
        $rawMaterialsByWarehouse = $rawMaterialsStock->groupBy('warehouse.name');

        // 5. Pass the two structured, multi-level collections to the view.
        return view('manager.stock_levels.index', [
            'finishedGoodsByWarehouse' => $finishedGoodsByWarehouse,
            'rawMaterialsByWarehouse' => $rawMaterialsByWarehouse,
        ]);
    }
}  