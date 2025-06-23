<?php

namespace App\Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * The main entry point for the /dashboard route.
     * Dispatches to the correct role-specific dashboard method.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Supplier')) {
            return $this->supplierDashboard();
        }

        if ($user->hasRole('Liquor Manager')) {
            return $this->managerDashboard();
        }

        // ... other role checks here ...

        // Fallback to a generic view if no specific dashboard is defined
        return view('dashboard');
    }

    /**
     * Gathers data for and displays the Supplier dashboard.
     */
    private function supplierDashboard()
    {
        // In the future, you will get data from your *real* modules.
        // For example:
        // $chatCount = app(ChatService::class)->getUnreadCountFor(Auth::id());
        // $outOfStock = app(InventoryService::class)->getOutOfStockCountFor(Auth::id());

        // For now, we use static data.
        $stats = [
            'newChats' => 8,
            'outOfStock' => 12,
            'unfulfilledOrders' => 10,
            'salesTotal' => 'Sh.40000000',
        ];

        // This controller from the 'Dashboard' module renders the view
        // from the 'supplier' role-based view folder.
        return view('supplier.dashboard', ['stats' => $stats]);
    }

    /**
     * Gathers data for and displays the Liquor Manager dashboard.
     */
    private function managerDashboard()
    {
        // Fetch data specific to the Liquor Manager...
        $stats = [ /* ... manager stats ... */ ];

        return view('manager.dashboard', ['stats' => $stats]);
    }

    // ... add other private methods for other roles (financeDashboard, etc.)
}
