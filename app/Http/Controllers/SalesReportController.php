<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SalesReportController extends Controller
{
    public function weeklyReport()
    {
        $role = Auth::user()->getRoleNames()->first();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $query = OrderItem::with(['product', 'sale'])
            ->whereHas('sale', function ($q) use ($startOfWeek, $endOfWeek) {
                $q->whereBetween('sale_date', [$startOfWeek, $endOfWeek]);
            });

        if ($role === 'Manufacturer') {
            $query->whereHas('product', fn($q) => $q->where('type', 'finished_good'));
        } elseif ($role === 'Supplier') {
            $query->whereHas('product', fn($q) => $q->where('user_id', Auth::id()));
        }

        $items = $query->get();

        $grouped = $items->groupBy('product.name')->map(function ($group) {
            return [
                'quantity' => $group->sum('quantity'),
                'revenue' => $group->sum(fn($item) => $item->quantity * $item->price)
            ];
        });

        return view('reports.weekly_sales', compact('items', 'grouped', 'startOfWeek', 'endOfWeek', 'role'));
    }

    public function downloadWeeklyPdf()
    {
        $role = Auth::user()->getRoleNames()->first();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $query = OrderItem::with(['product', 'sale'])
            ->whereHas('sale', function ($q) use ($startOfWeek, $endOfWeek) {
                $q->whereBetween('sale_date', [$startOfWeek, $endOfWeek]);
            });

        if ($role === 'Manufacturer') {
            $query->whereHas('product', fn($q) => $q->where('type', 'finished_good'));
        } elseif ($role === 'Supplier') {
            $query->whereHas('product', fn($q) => $q->where('user_id', Auth::id()));
        }

        $items = $query->get();

        $grouped = $items->groupBy('product.name')->map(function ($group) {
            return [
                'quantity' => $group->sum('quantity'),
                'revenue' => $group->sum(fn($item) => $item->quantity * $item->price)
            ];
        });

        $pdf = Pdf::loadView('reports.weekly_sales_pdf', compact('grouped', 'role', 'startOfWeek', 'endOfWeek'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("weekly_sales_report_{$role}.pdf");
    }
}
