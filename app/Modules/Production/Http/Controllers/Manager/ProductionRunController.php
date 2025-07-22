<?php

namespace App\Modules\Production\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\ProductionRun;
use Illuminate\Http\Request;
use Illuminate\Support\Collection; // Import the Collection class

class ProductionRunController extends Controller
{
    /**
     * Display a list of all production runs, grouped by factory.
     */
    public function index()
    {
        // 1. Fetch all production runs, still eager loading the relationships.
        $allProductionRuns = ProductionRun::with(['user', 'factory', 'product'])
            ->latest('completed_at')
            ->get(); // Use get() to fetch all records for grouping

        // 2. Group the entire collection by the 'factory_id'.
        // This creates a collection where each key is a factory ID, and the value is a
        // collection of all runs for that factory.
        $runsByFactory = $allProductionRuns->groupBy('factory_id');

        // 3. (NEW) Calculate the total cost for each factory group.
        // We will create a new array to store these totals.
        $factoryTotals = $runsByFactory->map(function (Collection $runsInGroup) {
            return $runsInGroup->sum('cost_of_materials');
        });
        
        // 4. Return the view, passing the grouped data and the totals.
        return view('manager.production.runs', [
            'runsByFactory' => $runsByFactory,
            'factoryTotals' => $factoryTotals,
        ]);
    }
}