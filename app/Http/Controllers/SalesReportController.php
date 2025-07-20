<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalesReportController extends Controller
{
    /**
     * Display the weekly sales report on a web page.
     */
    public function showWeeklySummaryReport()
    {
        $user = Auth::user();
        if (!$user->hasRole(['Liquor Manager', 'Finance', 'Supplier'])) {
            abort(403, 'Unauthorized Access');
        }

        $reportData = $this->_getWeeklyReportData();

        return view('reports.weekly_summary', $reportData);
    }

    /**
     * Generate and download the weekly sales report as a PDF.
     */
    public function downloadWeeklySummaryReport()
    {
        $user = Auth::user();
        if (!$user->hasRole(['Liquor Manager', 'Finance', 'Supplier'])) {
            abort(403, 'Unauthorized Access');
        }

        $reportData = $this->_getWeeklyReportData();
        $reportData['is_pdf'] = true; // Flag for the view to hide the download button

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('reports.weekly_summary', $reportData)->setPaper('a4', 'landscape');

        return $pdf->download('Weekly_Sales_Summary_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Private helper method to gather and process all sales data.
     */
    
   private function _getWeeklyReportData(): array
{
    $startOfWeek = now()->startOfWeek();
    $endOfWeek = now()->endOfWeek();

    $sales = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])
        ->with('orderItems.product.category')
        ->get();

    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    $categoryData = [];
    $totalSales = 0;
    $dailyTotals = array_fill_keys($days, 0);

    // 1. Process all sales data, preserving the daily breakdown
    foreach ($sales as $order) {
        $day = $order->created_at->format('l');
        foreach ($order->orderItems as $item) {
            $categoryName = $item->product->category ? trim($item->product->category->name) : 'Uncategorized';
            $productName = $item->product->name ?? 'Unknown Product';
            $revenue = $item->price * $item->quantity;

            // Initialize structures if they don't exist
            if (!isset($categoryData[$categoryName])) {
                $categoryData[$categoryName] = ['products' => [], 'category_total' => 0];
            }
            if (!isset($categoryData[$categoryName]['products'][$productName])) {
                $categoryData[$categoryName]['products'][$productName] = [
                    'name' => $productName,
                    'daily_sales' => array_fill_keys($days, 0), // <-- THIS IS THE KEY PART WE ARE RESTORING
                    'total' => 0
                ];
            }

            // Populate the daily sales data
            $categoryData[$categoryName]['products'][$productName]['daily_sales'][$day] += $revenue;
            $categoryData[$categoryName]['products'][$productName]['total'] += $revenue;
            $categoryData[$categoryName]['category_total'] += $revenue;
            $totalSales += $revenue;
            $dailyTotals[$day] += $revenue; // Track grand total for each day
        }
    }

    // 2. Convert to final report structure and calculate percentages
    $finalReport = [];
    foreach ($categoryData as $name => $data) {
        $data['category'] = $name;
        $data['products'] = array_values($data['products']);
        $data['percent'] = $totalSales > 0 ? ($data['category_total'] / $totalSales) * 100 : 0;
        $finalReport[] = $data;
    }
    usort($finalReport, fn($a, $b) => $a['category'] <=> $b['category']);

    // ===================================================================
    // 3. CALCULATE INSIGHTS (THIS LOGIC REMAINS THE SAME)
    // ===================================================================
    $topDay = 'N/A';
    if ($totalSales > 0) {
        arsort($dailyTotals); // Sort days by total sales
        $topDay = key($dailyTotals);
    }

    $allProducts = collect($finalReport)->flatMap(fn($c) => $c['products']);
    $topProduct = $allProducts->sortByDesc('total')->first() ?? ['name' => 'N/A'];
    $topCategory = collect($finalReport)->sortByDesc('category_total')->first() ?? ['category' => 'N/A'];
    $bottomProducts = $allProducts->where('total', '>', 0)->sortBy('total')->take(3)->values()->all();
    
    // 4. Prepare Chart Data (REMAINS THE SAME)
    $chartLabels = collect($finalReport)->pluck('category')->toArray();
    $chartPercentages = collect($finalReport)->pluck('percent')->map(fn($p) => round($p, 1))->toArray();

    // 5. Return ALL data to the view
    return [
        'report' => $finalReport, // <-- This now contains the full daily breakdown
        'days' => $days,
        'appName' => 'Liquor Supply Chain Stores',
        'weekStartDate' => $startOfWeek->format('j-M'),
        'weekEndDate' => $endOfWeek->format('j-M'),
        'totalSales' => $totalSales,
        'is_pdf' => false,
        'chartLabels' => $chartLabels,
        'chartData' => $chartPercentages,
        'topDay' => $topDay,
        'topProduct' => $topProduct,
        'topCategory' => $topCategory,
        'bottomProducts' => $bottomProducts,
    ];
}



    }


