<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

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
        //fetch actual sales data from orders table
        $monthlyTotals = DB::table('orders')
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        //12month array, default 0 for missing months
        $salesData = [];
        for ($m = 1; $m <= 12; $m++) {
            $actualSales[] = round($monthlyTotals[$m] ?? 0, 2);
        }

        //Call Flask ML API for forecasting
        $response = Http::get('http://127.0.0.1:5000/api/forecast');
        if (! $response->successful()) {
            abort(500, 'Forecast API unavailable');
        }

        $forecastData = $response->json();

        $data = [
            'predicted_sales' => $forecastData['predicted_sales'],
            'efficiency' => $forecastData['efficiency'],
            'fulfillment_days' => $forecastData['fulfillment_days'],
            'actual_sales' => $actualSales,
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
