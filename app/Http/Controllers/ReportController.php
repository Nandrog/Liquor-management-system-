<?php
namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;


class ReportController extends Controller
{
    /**
     * Generate a PDF report of the inventory for the current week.
     * The report will include products updated this week, categorized by user role.
     */

    public function inventoryPdf()
    {
        $user = Auth::user();
        $role = $user->getRoleNames()->first();

        // Define current week's date range (Monday to Sunday)
        $startOfWeek = Carbon::now()->startOfWeek(); // Monday
        $endOfWeek = Carbon::now()->endOfWeek();     // Sunday

        // Get products updated this week
        $products = Product::with(['category', 'supplier', 'vendor'])
                    ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
                    ->get();

        // Load view depending on user role
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
    // Get all categorized data
    $manufacturerProducts = $products->where('type', 'finished_good');
    $supplierProducts = $products->where('user_id', $user->id); // For all suppliers
    $financeProducts = $products; // All products

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


}


