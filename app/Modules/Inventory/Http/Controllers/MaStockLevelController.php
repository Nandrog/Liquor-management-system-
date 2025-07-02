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
        // 1. Get the authenticated user (the manufacturer).
        $user = Auth::user();

        // 2. Find the user's factory and its associated warehouse.
        // Eager load the 'factory.warehouse' relationship to do this efficiently.
        $user->load('productionPlant.warehouse');
        
        // 3. Handle the case where the manufacturer is not assigned to a factory/warehouse.
        if (!$user->productionPlant || !$user->productionPlant->warehouse) {
            // You can either show an error, or an empty page.
            // Let's redirect back with an error message for clarity.
            return redirect()->route('dashboard')
                ->with('error', 'You are not assigned to a factory with a warehouse.');
        }

        $warehouse = $user->productionPlant->warehouse;

        // 4. Fetch only the stock levels for that specific warehouse.
        // We still eager load the 'product' relationship for efficiency.
        $stockLevels = StockLevel::with('product')
            ->where('warehouse_id', $warehouse->id)
            ->paginate(15); // Paginate the results

        // 5. Return the view, passing the warehouse name and its stock levels.
        return view('manufacturer.stock_levels.index', [
            'warehouse' => $warehouse,
            'stockLevels' => $stockLevels,
        ]);
    }
}
