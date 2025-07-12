<?php

namespace App\Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
         

        // Use a switch statement for cleaner code when you have many roles
        switch ($user->getRoleNames()->first()) {
            case 'Supplier':
                return $this->supplierDashboard();
            case 'Liquor Manager':
                return $this->managerDashboard();
            case 'Procurement Officer':
                return $this->officerDashboard();
            case 'Vendor':
                return $this->vendorDashboard();
            case 'Finance':
                return $this->financeDashboard();
            case 'Manufacturer':
                return $this->manufacturerDashboard();
            case 'Customer':
                return $this->customerDashboard();
            default:
                // This is the fallback for any other role (e.g., Admin)
                return view('dashboard', ['cards' => []]);
        }
    }

    private function supplierDashboard()
    {
        $cards = [
            ['title' => 'Purchase Orders', 'description' => 'View incoming orders', 'icon' => 'bi-clipboard-check', 'route' => '#', 'count' => 5, 'count_label' => 'Pending'],
            ['title' => 'My Products', 'description' => 'Manage your product listings', 'icon' => 'bi-box-seam', 'route' => '#', 'count' => 25, 'count_label' => 'Total'],
        ];
        return view('supplier.dashboard', ['cards' => $cards]);
    }

    private function managerDashboard()
    {
        // Your existing logic to get counts...
        $itemsAvailable = Product::whereHas('stockLevels', fn($q) => $q->where('quantity', '>', 0))->count();
        $itemsOutOfStock = Product::whereDoesntHave('stockLevels', fn($q) => $q->where('quantity', '>', 0))->count();

        $cards = [
            [
                'title' => 'Stock Levels',
                'description' => 'View detailed stock levels.',
                'icon' => 'bi-clipboard-data',
                'route' => route('manager.stock_levels.index'), // Assuming a shared stock levels route
                'count' => $itemsAvailable,
                'count_label' => 'Items Available',
                'secondary_count' => $itemsOutOfStock,
                'secondary_count_label' => 'Out of Stock'
            ],
            // ... other cards for the manager
        ];
        
        // This should return the manager-specific view
        return view('manager.dashboard', ['cards' => $cards]);
    }

    // You can build out these methods with their own logic and views
    private function officerDashboard() { return view('procurement.dashboard'); }
    private function vendorDashboard() { return view('vendor.dashboard'); }
    private function customerDashboard() { return view('customer.dashboard'); }
    private function manufacturerDashboard() { return view('manufacturer.dashboard'); }
    private function financeDashboard() { 
         
        $itemsAvailable = Product::whereHas('stockLevels', fn($q) => $q->where('quantity', '>', 0))->count();

        $cards = [
            [
              'title' => 'Stock Levels',
                'description' => 'View detailed stock levels.',
                'icon' => 'bi-clipboard-data',
                'route' => route('finance.items.index'), // Assuming a shared stock levels route
                'count' => $itemsAvailable,
                'count_label' => 'Items Available',  
            ]
        ];
        return view('finance.dashboard', ['cards'=>$cards]); 
    }

    /**
     * The default dashboard for any user whose role doesn't have a custom view.
     */
    private function defaultDashboard()
    {
        // This method now returns the generic 'dashboard' view
        // and provides it with an empty array for the $cards variable.
        return view('dashboard', ['cards' => []]);
    }
}