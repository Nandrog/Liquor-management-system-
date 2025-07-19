<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        if ($user->hasRole('Procurement Officer')) {
            return view('analytics.procurement',[
                'data' => $this->getProcurementData()
            ]);
        }

        if ($user->hasRole('Supplier')) {
            return view('analytics.supplier',[
                'data' => $this->getProcurementData()
            ]);
        }

        if ($user->hasRole('Finance')) {
            return view('analytics.finance',[
                'data' => $this->getFinanceData()
            ]);
        }

        if ($user->hasRole('Liquor Manager')) {
            return view('analytics.liquor-manager',[
                'data' => $this->getLiquorData()
            ]);
        }
        abort(403, 'Unauthorised');

        // Role-based data filtering example
        $salesQuery = DB::table('orders');
        if ($user->role === 'supplier') {
            $salesQuery->where('supplier_id', $user->id);
        } elseif ($user->role === 'vendor') {
            $salesQuery->where('vendor_id', $user->id);
        }

        $sales = DB::table('orders')
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->groupBy('month')
            ->get();

        //Call Flask ML API for forecasting
        $forecast = Http::get('http://127.0.0.1:5000/api/forecast')->json();

        return view('analytics.dashboard', compact('sales', 'forecast'));
    }

    public function segmentation()
    {
        /*Call Flask ML API for segmentation
        $segments = Http::get('http://127.0.0.1:5000/api/segments')->json();
        return view('analytics.segmentation', compact('segments'));*/
        $response = Http::get('http://127.0.0.1:5000/api/segments');
        if ($response->successful()) {
            return view('analytics.customer-segmentation', ['segments' => $response->json()]);
        }

        abort(500, 'Segementation API unavailable');
    }

    public function forecast()
{
    // Get all products (liquors)
    $products = DB::table('products')->pluck('name', 'id')->toArray();

    // Start of the year for filtering
    $startOfYear = Carbon::now()->startOfYear();

    // Query: sum of quantity * price grouped by product and week (yearweek)
    $salesData = DB::table('order_items')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->selectRaw("
            order_items.product_id,
            YEARWEEK(orders.created_at, 1) AS year_week,
            SUM(order_items.quantity * order_items.price) AS total_sales
        ")
        ->where('orders.created_at', '>=', $startOfYear)
        ->groupBy('order_items.product_id', 'year_week')
        ->orderBy('year_week')
        ->get();

    // Prepare week labels for all weeks so far this year
    $currentWeek = Carbon::now()->weekOfYear;
    $year = Carbon::now()->year;

    $weeks = [];
    for ($w = 1; $w <= $currentWeek; $w++) {
        $weeks[] = sprintf("%d-W%02d", $year, $w);
    }

    // Initialize data array: product_id => [week_label => 0]
    $data = [];
    foreach ($products as $id => $name) {
        $data[$id] = array_fill_keys($weeks, 0);
    }

    // Fill in sales data into $data array
    foreach ($salesData as $row) {
        $yearPart = substr($row->year_week, 0, 4);
        $weekPart = substr($row->year_week, 4, 2);
        $weekLabel = sprintf("%d-W%s", $yearPart, $weekPart);
        if (isset($data[$row->product_id][$weekLabel])) {
            $data[$row->product_id][$weekLabel] = round($row->total_sales, 2);
        }
    }

    // Prepare datasets for Chart.js
    $colors = ['#1f77b4','#ff7f0e','#2ca02c','#d62728','#9467bd','#8c564b','#e377c2','#7f7f7f'];
    $datasets = [];
    $colorIndex = 0;

    foreach ($products as $id => $name) {
        $datasets[] = [
            'label' => $name,
            'data' => array_values($data[$id]),
            'backgroundColor' => $colors[$colorIndex % count($colors)],
            'borderColor' => $colors[$colorIndex % count($colors)],
            'fill' => false,
            'tension' => 0.1,
        ];
        $colorIndex++;
    }

    // Optional: Call your existing Flask API for forecast summary data
    $response = Http::get('http://127.0.0.1:5000/api/forecast');
    $forecastData = $response->successful() ? $response->json() : null;

    return view('analytics.forecast', [
        'weeks' => $weeks,
        'datasets' => $datasets,
        'efficiency' => $forecastData['efficiency'] ?? 96.5,
        'fulfillment_days' => $forecastData['fulfillment_days'] ?? 4.3,
    ]);

    // Fetch weekly sales sums for current year
    $weeklyTotals = DB::table('orders')
        ->selectRaw("YEARWEEK(created_at, 1) as year_week, SUM(total_amount) as total") // mode 1 = Monday start
        ->whereYear('created_at', now()->year)
        ->groupBy('year_week')
        ->orderBy('year_week')
        ->pluck('total', 'year_week')
        ->toArray();

    // Convert YEARWEEK keys to labels like '2025-W27'
    $labels = [];
    $values = [];
    foreach ($weeklyTotals as $yearWeek => $total) {
        $year = substr($yearWeek, 0, 4);
        $week = substr($yearWeek, 4, 2);
        $labels[] = $year . '-W' . $week;
        $values[] = round($total, 2);
    }

    // Call Flask API
    $response = Http::get('http://127.0.0.1:5000/api/forecast');
    if (! $response->successful()) {
        abort(500, 'Forecast API unavailable');
    }
    $forecastData = $response->json();

    $data = [
        'weeks' => $labels,
        'actual_sales' => $values,
        'weekly_sales_forecast' => $forecastData['weekly_sales'] ?? [],
        'efficiency' => $forecastData['efficiency'],
        'fulfillment_days' => $forecastData['fulfillment_days'],
    ];

    return view('analytics.forecast', compact('data'));
}

    private function getProcurementData()
    {
        return [
            'labels' => ['Jan', 'Feb', 'Mar'],
            'values' => [100, 150, 200],
        ];
    }

    private function getFinanceData()
    {
        return [
            'labels' => ['Jan', 'Feb', 'Mar'],
            'values' => [12000, 14500, 13000],
        ];
    }

    private function getLiquorData()
    {
        return [
            'labels' => ['Whiskey', 'Beer', 'Gin', 'Wine'],
            'values' => [40, 30, 20, 10],
        ];
    }

    public function analyticsMenu()
    {
        return view('analytics.menu');
    }

}
