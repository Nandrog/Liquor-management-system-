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
        $response = Http::get('http://127.0.0.1:5000/api/forecast');
        if ($response->successful()) {
            return view('analytics.forecast', ['data' => $response->json()]);
        }

        abort(500, 'Forecast API unavailable');
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
