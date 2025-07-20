<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\StockMovement;
use App\Models\WorkDistribution\ShiftSchedule;
use App\Models\WorkDistribution\Task;

class ReportController extends Controller
{
    public function inventoryPdf()
    {
        $user = Auth::user();
        $role = $user->getRoleNames()->first();

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $products = Product::with(['category', 'supplier', 'vendor'])
                    ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
                    ->get();

        switch ($role) {
            case 'Manufacturer':
                $view = 'reports.inventory_manufacturer';
                break;
            case 'Supplier':
                $view = 'reports.inventory_supplier';
                break;
            case 'Finance':
                $view = 'reports.inventory_finance';
                break;
            case 'Liquor Manager':
                $manufacturerProducts = $products->where('type', 'finished_good');
                $supplierProducts = $products->where('user_id', $user->id);
                $financeProducts = $products;

                $view = 'reports.inventory_liquor_manager';

                $pdf = Pdf::loadView($view, [
                    'manufacturerProducts' => $manufacturerProducts,
                    'supplierProducts' => $supplierProducts,
                    'financeProducts' => $financeProducts,
                    'roleName' => $role,
                    'startOfWeek' => $startOfWeek->format('Y-m-d'),
                    'endOfWeek' => $endOfWeek->format('Y-m-d'),
                ]);

                return $pdf->download("weekly_inventory_report_{$role}.pdf");

            default:
                $view = 'reports.inventory_general';
                break;
        }

        $pdf = Pdf::loadView($view, [
            'products' => $products,
            'roleName' => $role,
            'startOfWeek' => $startOfWeek->format('Y-m-d'),
            'endOfWeek' => $endOfWeek->format('Y-m-d'),
        ]);

        return $pdf->download("weekly_inventory_report_{$role}.pdf");
    }

    public function index()
    {
        $user = Auth::user();
        $role = $user->getRoleNames()->first();

        return view('reports.index', compact('role'));
    }
    public function stockMovements()
{
    $movements = StockMovement::with(['product', 'fromWarehouse', 'toWarehouse', 'employee'])->latest()->get();

    // Group by warehouse and count movements per day
    $data = StockMovement::selectRaw('DATE(created_at) as date, from_warehouse_id, COUNT(*) as count')
                ->groupBy('date', 'from_warehouse_id')
                ->with('fromWarehouse')
                ->get()
                ->groupBy('from_warehouse_id');

    // Prepare chart data
    $chartData = [];
    foreach ($data as $warehouseId => $group) {
        $warehouseName = optional($group->first()->fromWarehouse)->name ?? 'Unknown';
        $chartData[$warehouseName] = $group->pluck('count', 'date')->toArray();
    }

    return view('reports.stock-movements', compact('movements', 'chartData'));
}
    public function shiftSchedules()
{
    $shifts = ShiftSchedule::with('employee')->latest()->get();

    // Group by employee name and count shifts
    $shiftCounts = ShiftSchedule::with('employee')
        ->get()
        ->groupBy(fn ($shift) => $shift->employee->name ?? 'N/A')
        ->map(fn ($group) => $group->count());

    return view('reports.shift-schedules', compact('shifts', 'shiftCounts'));
}
    public function taskPerformance()
    {
        $tasks = Task::with('employee')->latest()->get();

        $taskStatusCounts = Task::select('status', \DB::raw('count(*) as count'))
                                ->groupBy('status')
                                ->pluck('count', 'status');

        return view('reports.task-performance', compact('tasks', 'taskStatusCounts'));
    }
    public function inventoryView()
{
    $products = Product::with('category')->get();

    // Count products per category
    $productCounts = $products->groupBy(fn ($p) => $p->category->name ?? 'Uncategorized')
                              ->map(fn ($group) => $group->count());

    return view('reports.inventory-chart', [
        'products' => $products,
        'productCounts' => $productCounts
    ]);
}
}