<?php

namespace App\Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Message;
use App\Models\StockMovement;
use App\Models\WorkDistribution\Task;
use App\Models\Order;
use Carbon\Carbon;
use App\Models\Recipe;
use App\Models\WorkDistribution\ShiftSchedule;
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

        $messages = Message::where('receiver_id', Auth::id())->where('is_read',false)->count();
        $itemsAvailable = Product::Where('type','raw_material')->count();
        
        // Get the supplier record for the current user
        $supplier = \App\Models\Supplier::where('user_id', Auth::id())->first();

        $orderspaid = 0;
        if ($supplier) {
            $orderspaid = Order::where('type', 'supplier_order')
                ->where('status', 'paid')
                ->where('supplier_id', $supplier->id)
                ->count();
        }

        $cards = [
            [
                'title' => 'Make Invoice', 
            'description' => 'Create invoices to supply products',
            'icon' => 'bi-box-seam',
            'route' =>route( 'supplier.orders.create'),
            'count' => $itemsAvailable, 
            'count_label' => 'Options to supply'
        ],
            [
             'title' => 'Orders History',
             'description' => 'View list of all orders made',
             'icon' => 'bi-clipboard-check',
             'route' =>route('supplier.orders.paid') ,
             'count' => $orderspaid, 
             'count_label' => 'Orders Paid'
            ],

            [
                'title' => 'Chats',
                'description' => 'Communicate with other users',
                'icon' => 'bi-chat-left-text',
                'route' => route('chat.page'),
                'count' => $messages,
                'count_label' => 'Unread Messages'

            ],
        ];
        return view('supplier.dashboard', ['cards' => $cards]);
    }

    private function managerDashboard()
    {
        // Your existing logic to get counts...
        $itemsAvailable = Product::whereHas('stockLevels', fn($q) => $q->where('quantity', '>', 0))->count();
        $itemsOutOfStock = Product::whereDoesntHave('stockLevels', fn($q) => $q->where('quantity', '>', 0))->count();
        $tasks=Task::where('status','pending')->count();
        $messages = Message::where('receiver_id', Auth::id())->where('is_read',false)->count();


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
            [
                'title'=>'Task Master',
                'description'=>'Monitor tasks and assign tasks',
                'icon'=>'bi-clipboard-check',
                'route'=>route('manager.work-distribution.task-list'),
                'count' =>$tasks,
                'count_label' =>'Pending Tasks', 
                'secondaryCount' => null,     // Default to null
                'secondaryCountLabel' => null
            ],
            [
                'title' => 'Forecast Analysis',
                'description' => 'Analysis of stock trends and forecasts',
                'icon' => 'bi-graph-up',
                'route'=> route('analytics.forecast'),
                'count' =>null,
                'count_label' => null,         // Default to null
                'secondaryCount' => null,     // Default to null
                'secondaryCountLabel' => null
            ],
            [
                'title' => 'Chats',
                'description' => 'Communicate with other users',
                'icon' => 'bi-chat-left-text',
                'route' => route('chat.page'),
                'count' => $messages,
                'count_label' => 'Unread Messages'

            ]
            // ... other cards for the manager
        ];
        
        // This should return the manager-specific view
        return view('manager.dashboard', ['cards' => $cards]);
    }

    // You can build out these methods with their own logic and views
    private function officerDashboard() {
        $itemsAvailable = Product::whereHas('stockLevels', fn($q) => $q->where('quantity', '>', 0))->count();
        $itemsOutOfStock = Product::whereDoesntHave('stockLevels', fn($q) => $q->where('quantity', '>', 0))->count();
        $tasks=Task::where('status','pending')->count();
        $messages = Message::where('receiver_id', Auth::id())->where('is_read',false)->count();
        $stalkMovements = StockMovement::count();


        $cards = [
            [
                'title' => 'Stock Movements',
                'description' => 'Transfer stock and track stock movements and adjustments',
                'icon' => 'bi-truck',
                'route' => route('officer.stock_movements.index'), // Assuming a shared stock levels route
                'count' => $stalkMovements ,
                'count_label' => 'Moved Stock',
                'secondary_count' => $itemsOutOfStock,
                'secondary_count_label' => 'Out of Stock'
            ],
            [
                'title'=>'Task Master',
                'description'=>'Monitor tasks and assign tasks',
                'icon'=>'bi-clipboard-check',
                'route'=>route('officer.work-distribution.task-list'),
                'count' =>$tasks,
                'count_label' =>'Pending Tasks', 
                'secondaryCount' => null,     // Default to null
                'secondaryCountLabel' => null
            ],
            [
                'title' => 'Chats',
                'description' => 'Communicate with other users',
                'icon' => 'bi-chat-left-text',
                'route' => route('chat.page'),
                'count' => $messages,
                'count_label' => 'Unread Messages'
            ],
           [
                'title' => 'Order Management',
                'description' => 'Manage purchase orders and vendor interactions',
                'icon' => 'bi-file-earmark-text',
                'route' =>route('procurement.orders.index'),
                'count' =>Order::count(),
                'count_label' => 'Orders Made'
            ]
        ];
        
        return view('officer.dashboard',['cards' => $cards]);
     }


    private function vendorDashboard() { 
        $messages = Message::where('receiver_id', Auth::id())->where('is_read',false)->count();
        $cards = [
            [
                'title' => 'Place Orders',
                'description' => 'Make orders for products you supply',
                'icon' => 'bi-box-seam',
                'route' => route('vendor.orders.create'),
                'count' => Product::where('type', 'finished_good')->count(),
                'count_label' => 'Products Available'
            ],
            [
                'title' => 'Orders History',
                'description' => 'View list of all orders made',
                'icon' => 'bi-clipboard-check',
                'route' => route('vendor.orders.index'),
                'count' => Order::where('type', 'vendor_order')->count(),
                'count_label' => 'Orders Made'
            ],
            [
                'title' => 'Chats',
                'description' => 'Communicate with other users',
                'icon' => 'bi-chat-left-text',
                'route' => route('chat.page'),
                'count' => $messages,
                'count_label' => 'Unread Messages'
            ]
        ];
        
        
        
        return view('vendor.dashboard',['cards'=>$cards]); }
    private function customerDashboard() { 
        
        // Fetch the currently authenticated user
        $user = auth()->user();
        
         $featuredProducts = Product::where('type', 'finished_good')
            ->whereHas('stockLevels', function ($query) {
                $query->where('quantity', '>', 0); // Only show products that are in stock
            })
            ->inRandomOrder() // Show a random selection each time
            ->take(4)         // Limit to a maximum of 4 products
            ->get();

        
        
        
        
        return view('customer.dashboard',['user' => $user,'featuredProducts'=>$featuredProducts]); }
    private function manufacturerDashboard() { 

         $messages = Message::where('receiver_id', Auth::id())->where('is_read',false)->count();
         $shifts = ShiftSchedule::where('end_time', '<', Carbon::now())->get()->count();
         $recipes = Recipe::count();
         $sales = Order::where('type','!=','supplier_order')->where('status','paid')->count();

        $cards =[
            [
                'title' => 'Sales Report',
                'description' => 'View sales reports and analytics',
                'icon' => 'bi-graph-up',
                'route' => route('reports.sales.weekly'),
                'count' => $sales, // Placeholder, can be replaced with actual count if needed
                'count_label' => 'Sales made', // Placeholder, can be replaced with actual label if needed
            
            ],
            [
                'title' => 'Chats',
                'description' => 'Communicate with other users',
                'icon' => 'bi-chat-left-text',
                'route' => route('chat.page'),
                'count' => $messages,
                'count_label' => 'Unread Messages'
            ],
            [
                'title'=>'Worker Shift Management',
                'description'=>'Manage worker shifts and schedules',
                'icon'=>'bi-clipboard-check',
                'route'=>route('manufacturer.work-distribution.shift-list'),
                'count' =>$shifts,
                'count_label' =>'Pending Tasks', 
                'secondaryCount' => null,     // Default to null
                'secondaryCountLabel' => null
            ],
            [
                 'title' => 'Brew Liqour',
                'description' => 'Manage product manufacturing processes',
                'icon' => 'bi-gear',
                'route' => route('manufacturer.production.index'), 
                'count' => $recipes,
                'count_label' => 'Products to make',
            ]
            
        ];
        
        
        return view('manufacturer.dashboard',['cards' => $cards]); }
    private function financeDashboard() { 
         
        $itemsAvailable = Product::whereHas('stockLevels', fn($q) => $q->where('quantity', '>', 0))->count();
        $tasks=Task::where('status','pending')->count();
        $messages = Message::where('receiver_id', Auth::id())->where('is_read',false)->count();
        $sales=Order::whereIn('type', ['vendor_order', 'customer_order'])->count();
        
        $cards = [
             [
        'title' => 'Sales Orders Tracking',
        'description' => 'Track revenue from customers and vendors.',
        'icon' => 'bi-graph-up-arrow',
        'route' => route('finance.orders.sales_report'),
        'count' => $sales,
        'count_label' => 'Total Sales Orders',
    ],
            [
            'title' => 'Supplier Orders Tracking',
            'description' => 'Track financial status of all purchase orders.',
            'icon' => 'bi-receipt',
            'route' => route('finance.orders.supplier_report'),
            'count' => \App\Models\Order::where('type', 'supplier_order')->count(),
            'count_label' => 'Total Orders',
            ],
            [
                'title'=>'Task Master',
                'description'=>'Monitor tasks and assign tasks',
                'icon'=>'bi-clipboard-check',
                'route'=>route('officer.work-distribution.task-list'),
                'count' =>$tasks,
                'count_label' =>'Pending Tasks', 
                'secondaryCount' => null,     // Default to null
                'secondaryCountLabel' => null
            ],
            [
                'title' => 'Chats',
                'description' => 'Communicate with other users',
                'icon' => 'bi-chat-left-text',
                'route' => route('chat.page'),
                'count' => $messages,
                'count_label' => 'Unread Messages'

            ],
            [
                'title' => 'Financial Reports',
                'description' => 'View financial reports and analytics',
                'icon' => 'bi-graph-up',
                'route' => route('reports.index'), // Assuming a route for financial reports
                'count' => null, // Placeholder, can be replaced with actual count if needed
                'count_label' => null, // Placeholder, can be replaced with actual label if needed
                'secondaryCount' => null,     // Default to null
                'secondaryCountLabel' => null
            ],
            [
                'title' => 'Forecast Analysis',
                'description' => 'Analysis of stock trends and forecasts',
                'icon' => 'bi-graph-up',
                'route'=> route('analytics.forecast'),
                'count' =>null,
                'count_label' => null,         // Default to null
                'secondaryCount' => null,     // Default to null
                'secondaryCountLabel' => null
            ],
        
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