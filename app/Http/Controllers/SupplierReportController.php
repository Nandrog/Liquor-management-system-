<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupplierReportController extends Controller
{
    /**
     * Display a dedicated inventory and forecast report for a logged-in supplier.
     */
    public function showDashboard()
{
    $user = Auth::user();

    if (!$user->hasRole('Supplier')) {
        abort(403, 'Unauthorized Access');
    }

    $supplierName = $user->name;
    $reportDate = now()->format('M d, Y');

    // 1. Get the list of raw material products for this supplier.
    $products = Product::where('type', 'raw_material')
        ->where('user_id', $user->id)
        ->orderBy('name')
        ->get();

    if ($products->isEmpty()) {
        return view('reports.supplier_dashboard', [
            'products' => collect(),
            'supplierName' => $supplierName,
            'reportDate' => $reportDate,
        ]);
    }

    $productIds = $products->pluck('id');

    // 2. Get current stock levels (this logic doesn't change).
    $currentStock = StockLevel::query()
        ->whereIn('product_id', $productIds)
        ->select('product_id', DB::raw('SUM(quantity) as current_stock'))
        ->groupBy('product_id')
        ->pluck('current_stock', 'product_id');

    // =========================================================
    //         THIS IS THE SECTION WE ARE CHANGING
    // =========================================================
    
    // 3. Calculate consumption over the LAST 7 DAYS.
    $endDate = now();
    $startDate = now()->subDays(7); // <-- CHANGED FROM 30 to 7

    // IMPORTANT: As before, this is a placeholder. You would need to query
    // your 'production_logs' table to get the real consumption data.
    // For now, it will return an empty collection.
    $consumptionLast7Days = collect(); // Placeholder for `production_logs` query

    // 4. Attach the calculated data to each product.
    foreach ($products as $product) {
        $product->current_stock = $currentStock->get($product->id, 0);
        $consumption = $consumptionLast7Days->get($product->id, 0);
        
        // Calculate average daily usage based on a 7-day week.
        $product->avg_daily_usage = round($consumption / 7, 2); // <-- CHANGED FROM 30 to 7

        if ($product->avg_daily_usage > 0) {
            $product->days_remaining = floor($product->current_stock / $product->avg_daily_usage);
        } else {
            $product->days_remaining = 'N/A';
        }

        if ($product->current_stock <= $product->reorder_level) {
            $product->status = 'Reorder Anticipated';
        } else {
            $product->status = 'Healthy Stock';
        }
    }
    // =========================================================
    //                  END OF CHANGES
    // =========================================================

    return view('reports.supplier_dashboard', [
        'products' => $products,
        'reportDate' => $reportDate,
        'supplierName' => $supplierName,
    ]);
}
}
