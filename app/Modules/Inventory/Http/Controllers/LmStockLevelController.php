<?php

namespace App\Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\StockLevel;
use Illuminate\Http\Request;

class LmStockLevelController extends Controller
{
    /**
     * Display a paginated list of all stock levels across all warehouses.
     */
    public function index()
    {
        // 1. Fetch the data using the StockLevel model.
        // 2. Use `with()` to eager load the related Product and Warehouse models.
        // 3. Use `paginate()` to get a paginated result set.
        $stockLevels = StockLevel::with(['product', 'warehouse'])
            ->orderBy('warehouse_id')
            ->paginate(15); // Show 15 records per page

        // 4. Return the view, passing the data to it.
        
        return view('manager.stock_levels.index', [
            'stockLevels' => $stockLevels
        ]);
    }
}