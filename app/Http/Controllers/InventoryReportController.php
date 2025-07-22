<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Make sure Carbon is imported

class InventoryReportController extends Controller
{
    public function showFinanceReport()
    {
        //dd('I AM RUNNING THE CORRECT showFinanceReport METHOD');
        if (!Auth::user()->hasAnyRole(['Finance', 'Liquor Manager'])) {
            abort(403, 'Unauthorized Access');
        }

        // --- DEFINE THE TIME PERIODS ---
        $startOfWeek =  now()->startOfWeek();
        $endOfLastWeek =  now()->startOfWeek()->subSecond(); // e.g., Sunday 23:59:59

        // 1. Get the base list of products.
        $products = Product::where('type', 'finished_good')
            ->orderBy('name')
            ->get();
        $productIds = $products->pluck('id');

        // 2. Get the total quantity SOLD THIS WEEK.
        $salesThisWeek = OrderItem::query()
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity_sold'))
            ->whereIn('product_id', $productIds)
            ->where('created_at', '>=', $startOfWeek)
            ->groupBy('product_id')
            ->pluck('total_quantity_sold', 'product_id');

        // 3. Get the total quantity SOLD BEFORE THIS WEEK (all-time historical sales).
        $salesBeforeThisWeek = OrderItem::query()
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity_sold'))
            ->whereIn('product_id', $productIds)
            ->where('created_at', '<', $startOfWeek)
            ->groupBy('product_id')
            ->pluck('total_quantity_sold', 'product_id');

        // 4. Calculate the stock for each product.
        foreach ($products as $product) {
            $totalStockEver = $product->stock; // This is the cumulative stock received.
            
            $soldPreviously = $salesBeforeThisWeek->get($product->id, 0);
            $soldThisWeek = $salesThisWeek->get($product->id, 0);

            // The core weekly calculations
            $product->beginning_stock = $totalStockEver - $soldPreviously;
            $product->stock_in_this_week = 0; // We don't have a purchases table, so this is 0
            $product->stock_out_this_week = $soldThisWeek;
            $product->ending_stock = $product->beginning_stock - $product->stock_out_this_week;
        }

        return view('reports.inventory_finance', [
            'products' => $products,
            'weekStartDate' => $startOfWeek->format('M d, Y'),
            'weekEndDate' => now()->endOfWeek()->format('M d, Y'),
        ]);
    }
    
    public function showProcurementReport()
    {
        if (!Auth::user()->hasAnyRole(['Procurement Officer', 'Admin', 'Liquor Manager'])) {
            abort(403, 'Unauthorized Access');
        }

        // --- DEFINE THE TIME PERIODS ---
        $endDate = now();
        $startDate = now()->subDays(30); // We'll analyze the last 30 days of sales for velocity

        // 1. Get the base list of products.
        $products = Product::where('type', 'finished_good')
            ->orderBy('name')
            ->get();
        $productIds = $products->pluck('id');

        // 2. Get total quantity SOLD in the last 30 days.
        $salesLast30Days = OrderItem::query()
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity_sold'))
            ->whereIn('product_id', $productIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('product_id')
            ->pluck('total_quantity_sold', 'product_id');

        // 3. Get total quantity SOLD since the beginning of time.
        $salesAllTime = OrderItem::query()
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity_sold'))
            ->whereIn('product_id', $productIds)
            ->groupBy('product_id')
            ->pluck('total_quantity_sold', 'product_id');

        // 4. Calculate current stock and sales velocity for each product.
        foreach ($products as $product) {
            $totalStockEver = $product->stock;
            $soldAllTime = $salesAllTime->get($product->id, 0);
            $soldLast30 = $salesLast30Days->get($product->id, 0);

            $product->current_stock = $totalStockEver - $soldAllTime;
            $product->sales_velocity = round($soldLast30 / 30, 2); // Avg sales per day over last 30 days

            // Determine the stock status
            if ($product->current_stock <= 0) {
                $product->status = 'Out of Stock';
            } elseif ($product->current_stock <= $product->reorder_level) {
                $product->status = 'Reorder Now';
            } else {
                $product->status = 'In Stock';
            }
        }

        // Sort the products to show the most urgent items first
        $sortedProducts = $products->sortBy(function ($product) {
            // Custom sort order: Reorder Now > Out of Stock > In Stock
            return match ($product->status) {
                'Reorder Now' => 1,
                'Out of Stock' => 2,
                'In Stock' => 3,
                default => 4,
            };
        });

        return view('reports.inventory_procurement', [
            'products' => $sortedProducts,
            'reportDate' => $endDate->format('M d, Y'),
        ]);
    }
    public function showRawMaterialsReport()
    {
        // This report is for internal users who manage production and purchasing.
        if (!Auth::user()->hasAnyRole(['Admin', 'Manufacturer', 'Procurement Officer', 'Liquor Manager'])) {
            abort(403, 'Unauthorized Access');
        }

        // --- DEFINE THE TIME PERIODS ---
        $endDate = now();
        // We will analyze consumption over the last 30 days
        $startDate = now()->subDays(30); 

        // 1. Get the base list of raw material products.
        //    The key change is filtering for 'raw_material'.
        $products = Product::where('type', 'raw_material')
            ->orderBy('name')
            ->get();
        $productIds = $products->pluck('id');

        // 2. Determine raw material consumption.
        //    This is a bit tricky without a "production logs" table.
        //    A good ESTIMATE is to link raw material usage to finished goods produced (sold).
        //    For now, we'll assume a simplified model where consumption is tracked elsewhere or we use sales as a proxy.
        //    Let's stick to the simpler stock model for this example: Stock = Total Received - Total Used.
        //    Since we don't have a "Total Used" table, we will show current stock based on manual updates.
        //    This is a known limitation we can improve later.

        // 3. For a procurement report, the most important metric is the CURRENT stock level.
        foreach ($products as $product) {
            // In a simple model, `product->stock` would be manually updated after each production run.
            // Let's assume this is the current on-hand quantity.
            $product->current_stock = $product->stock;

            // Determine the stock status based on reorder level
            if ($product->current_stock <= 0) {
                $product->status = 'Out of Stock';
            } elseif ($product->current_stock <= $product->reorder_level) {
                $product->status = 'Reorder Now';
            } else {
                $product->status = 'In Stock';
            }
        }

        // Sort the products to show the most urgent items first
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
            'reportDate' => $endDate->format('M d, Y'),
        ]);
    }
}
