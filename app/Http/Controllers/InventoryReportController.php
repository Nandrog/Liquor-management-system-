<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\StockLevel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Make sure Carbon is imported

class InventoryReportController extends Controller
{
    public function showFinanceReport()
{
    if (!Auth::user()->hasAnyRole(['Finance', 'Admin', 'Liquor Manager'])) {
        abort(403, 'Unauthorized Access');
    }

    // --- RE-INTRODUCE THE DATE VARIABLES ---
    $startOfWeek = now()->startOfWeek();
    $weekStartDate = $startOfWeek->format('M d, Y');
    $weekEndDate = now()->endOfWeek()->format('M d, Y');

    // 1. Get the total stock for every product from the stock_levels table.
    $totalStock = StockLevel::query()
        ->select('product_id', DB::raw('SUM(quantity) as current_stock'))
        ->groupBy('product_id')
        ->pluck('current_stock', 'product_id');

    // 2. Get the products and attach their calculated stock.
    $products = Product::where('type', 'finished_good')
        ->orderBy('name')
        ->get();

    // To keep the Blade file consistent, let's call this 'ending_stock'
    foreach ($products as $product) {
        $product->ending_stock = $totalStock->get($product->id, 0);
    }

    // --- THIS IS THE FIX: PASS THE CORRECT DATE VARIABLES ---
    return view('reports.inventory_finance', [
        'products'      => $products,
        'weekStartDate' => $weekStartDate, // Pass the variable the view expects
        'weekEndDate'   => $weekEndDate,   // Pass the variable the view expects
    ]);
}

    public function showProcurementReport()
    {
        if (!Auth::user()->hasAnyRole(['Procurement Officer', 'Admin', 'Liquor Manager'])) {
            abort(403, 'Unauthorized Access');
        }
        
        $totalStock = StockLevel::query()
            ->select('product_id', DB::raw('SUM(quantity) as current_stock'))
            ->groupBy('product_id')
            ->pluck('current_stock', 'product_id');

        $products = Product::where('type', 'finished_good')
            ->with('vendor', 'category') // Eager load relationships
            ->orderBy('name')
            ->get();

        foreach ($products as $product) {
            $product->current_stock = $totalStock->get($product->id, 0);

            if ($product->current_stock <= 0) {
                $product->status = 'Out of Stock';
            } elseif ($product->current_stock <= $product->reorder_level) {
                $product->status = 'Reorder Now';
            } else {
                $product->status = 'In Stock';
            }
        }
        
        $sortedProducts = $products->sortBy(fn($p) => match($p->status) {
            'Reorder Now' => 1, 'Out of Stock' => 2, 'In Stock' => 3, default => 4
        });

        return view('reports.inventory_procurement', [
            'products' => $sortedProducts,
            'reportDate' => now()->format('M d, Y'),
        ]);
    }
    
    public function showRawMaterialsReport()
    {
        // This report is for internal users who manage production and purchasing.
        if (!Auth::user()->hasAnyRole(['Admin', 'Manufacturer', 'Procurement Officer', 'Liquor Manager'])) {
            abort(403, 'Unauthorized Access');
        }

        // --- DEFINE THE TIME PERIODS ---
         // 1. Get the current stock for every product from the 'stock_levels' table.
    $totalStock = StockLevel::query()
        ->select('product_id', DB::raw('SUM(quantity) as current_stock'))
        ->groupBy('product_id')
        ->pluck('current_stock', 'product_id');

    // 2. Get the list of raw material products and eager load their relationships.
    $products = Product::where('type', 'raw_material')
        ->with('supplier', 'category')
        ->orderBy('name')
        ->get();

    // 3. Attach the true stock level to each product and determine its status.
    foreach ($products as $product) {
        // Look up the stock level from our query; default to 0 if not found.
        $product->current_stock = $totalStock->get($product->id, 0);

        // Determine the stock status based on reorder level
        if ($product->current_stock <= 0) {
            $product->status = 'Out of Stock';
        } elseif ($product->current_stock <= $product->reorder_level) {
            $product->status = 'Reorder Now';
        } else {
            $product->status = 'In Stock';
        }
    }

    // 4. Sort the products to show the most urgent items first.
    $sortedProducts = $products->sortBy(function ($product) {
        return match ($product->status) {
            'Reorder Now' => 1,
            'Out of Stock' => 2,
            'In Stock' => 3,
            default => 4,
        };
    });

    return view('reports.inventory_raw_materials', [
        'products' => $sortedProducts,
        'reportDate' => now()->format('M d, Y'),
    ]);
    }
    public function showManufacturerReport()
    {
        
        // This report is for users involved in the production process.
        if (!Auth::user()->hasAnyRole(['Manufacturer', 'Admin', 'Liquor Manager'])) {
            abort(403, 'Unauthorized Access');
        }

        // 1. Get current stock for all products from the 'stock_levels' table.
        $totalStock = StockLevel::query()
            ->select('product_id', DB::raw('SUM(quantity) as current_stock'))
            ->groupBy('product_id')
            ->pluck('current_stock', 'product_id');

        // 2. Get the list of all raw material products.
        $products = Product::where('type', 'raw_material')
            ->with('supplier', 'category')
            ->orderBy('name')
            ->get();

        // 3. Attach the true stock level and determine the status for each material.
        foreach ($products as $product) {
            $product->current_stock = $totalStock->get($product->id, 0);

            if ($product->current_stock <= 0) {
                $product->status = 'Out of Stock';
            } elseif ($product->current_stock <= $product->reorder_level) {
                $product->status = 'Action Required'; // Wording is more relevant for a manufacturer
            } else {
                $product->status = 'Sufficient Stock';
            }
        }

        // 4. Sort to show the most urgent items first.
        $sortedProducts = $products->sortBy(function ($product) {
            return match ($product->status) {
                'Action Required' => 1,
                'Out of Stock' => 2,
                'Sufficient Stock' => 3,
                default => 4,
            };
        });

        return view('reports.inventory_manufacturer', [
            'products' => $sortedProducts,
            'reportDate' => now()->format('M d, Y'),
        ]);
    }
}
