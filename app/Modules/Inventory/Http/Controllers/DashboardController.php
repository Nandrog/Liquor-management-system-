<?php

namespace App\Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     * Builds and displays the inventory dashboard based on the user's role.
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        // This is our "template" list. Note that the routes are just placeholders.
        // This list only defines the "what" (title, icon), not the "where" (route).
        $masterActionList = [
            'items' => [
                'title' => 'Items',
                'description' => 'Manage your finished goods and raw materials.',
                'icon' => 'bi-box-seam',
                'route' => '#',
            ],
            'stock_levels' => [
                'title' => 'Stock Levels',
                'description' => 'View current stock across all warehouses.',
                'icon' => 'bi-clipboard-data',
                'route' => '#',
            ],
            'supply_management'=>[
                'title'=>'Supplier Management',
                'description' => 'View supplier purchase history and performance.',
                'icon' => 'bi-person-lines-fill',
                'route' => '#',

            ],
            'stock_movements' => [
                'title' => 'Stock Movements',
                'description' => 'Track internal transfers between warehouses.',
                'icon' => 'bi-truck',
                'route' => '#',
            ],
            'warehouses' => [
                'title' => 'Warehouses',
                'description' => 'Manage your storage locations.',
                'icon' => 'bi-building',
                'route' => '#',
            ],
            'purchases' => [
                'title' => 'Purchase Orders',
                'description' => 'Create and manage orders from suppliers.',
                'icon' => 'bi-cart-plus',
                'route' => '#',
            ],
            'sales' => [
                'title' => 'Sales Orders',
                'description' => 'Create and manage orders for customers.',
                'icon' => 'bi-cart-check',
                'route' => '#',
            ],
        ];

        $cardsForUser = [];

        // Now, we build the specific list of cards for the logged-in user.
        if ($user->hasRole('Supplier')) {
            // --- Supplier Cards ---
            
            // Items Card
            $itemCard = $masterActionList['items'];
            //$itemCard['route'] = route('supplier.items.index'); // Points to the SUPPLIER's item route
            $itemCard['description'] = 'Manage the products you supply to us.'; // Custom description
            $cardsForUser[] = $itemCard;
            
            // Stock Levels Card
            $stockCard = $masterActionList['stock_levels'];
           // $stockCard['route'] = route('supplier.stock_levels.index'); // Points to the SUPPLIER's stock level route
            $stockCard['description'] = 'View stock levels of your products at our warehouses.'; // Custom description
            $cardsForUser[] = $stockCard;

        } elseif ($user->hasRole('Liquor Manager')) {
            // --- Liquor Manager Cards ---
            // The manager gets access to everything, so we can use a loop for cleaner code.
            
            $managerActions = ['items', 'stock_levels'/*, 'stock_movements', 'warehouses', 'purchases', 'sales'*/];

            foreach ($managerActions as $action) {
                $card = $masterActionList[$action];
                // Dynamically create the route name, e.g., 'manager.items.index', 'manager.stock_levels.index'
                $card['route'] = route("manager.{$action}.index"); 
                $cardsForUser[] = $card;
            }
        }  elseif ($user->hasRole('Procurement Officer')) {

            
            $procurementActions = [ 'stock_movements',/* 'warehouses','stock_levels', 'purchases', 'sales'*/];

            foreach ($procurementActions as $action) {
                $card = $masterActionList[$action];
                // Dynamically create the route name, e.g., 'manager.items.index', 'manager.stock_levels.index'
                $card['route'] = route("officer.{$action}.index"); 
                $cardsForUser[] = $card;
                
            }

            $supplierCard = [ // Creating a new card on the fly
           'title' => 'Supplier Management',
           'description' => 'View supplier purchase history and performance.',
           'icon' => 'bi-person-lines-fill',
           'route' => route('officer.supplier.overview'),
          ];
           $cardsForUser[] = $supplierCard;
        }

        elseif ($user->hasRole('Manufacturer')) {
            // --- Manufacturer Cards ---
            // The manager gets access to everything, so we can use a loop for cleaner code.
            
            $managerActions = [ /*'stock_levels', 'stock_movements', 'warehouses', 'purchases', 'sales'*/];

            foreach ($managerActions as $action) {
                $card = $masterActionList[$action];
                // Dynamically create the route name, e.g., 'manager.items.index', 'manager.stock_levels.index'
                $card['route'] = route("manufacturer.{$action}.index"); 
                $cardsForUser[] = $card;
            }

                $stockCard = $masterActionList['stock_levels'];
         $stockCard['route'] = route('manufacturer.stock_levels.index');
         $stockCard['description'] = 'View stock levels at your assigned factory warehouse.';
         $cardsForUser[] = $stockCard;
               $productionCard = [
        'title' => 'Product Manufacture Allocation',
        'description' => 'Convert raw materials into finished goods.',
        'icon' => 'bi-gear-wide-connected',
        'route' => route('manufacturer.production.index'),
    ];
    $cardsForUser[] = $productionCard;
        }  elseif ($user->hasRole('Finance')) {

            
    $itemCard = $masterActionList['items'];
    $itemCard['route'] = route('finance.items.index');
    $itemCard['description'] = 'View and manage item pricing and valuation.';
    $cardsForUser[] = $itemCard;
}
        // Add more `elseif ($user->hasRole('...'))` blocks for other roles

        return view('inventory.dashboard', [
            'cards' => $cardsForUser,
        ]);
    }
}