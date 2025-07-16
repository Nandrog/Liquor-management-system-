<?php

use App\Modules\Inventory\Http\Controllers\DashboardController as InventoryDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Modules\Orders\Http\Controllers\OrderController;
use App\Http\Controllers\VendorApplicationController;
use Illuminate\Support\Facades\Auth;
// KEEP ONLY THE NEW, MODULAR CONTROLLER IMPORT. Delete any other DashboardController import.
use App\Modules\Dashboard\Http\Controllers\DashboardController;
use App\Modules\Payments\Http\Controllers\PaymentController;
use App\Http\Controllers\WorkDistribution\TaskController;
use App\Http\Controllers\WorkDistribution\ShiftController;
use App\Modules\Communications\Http\Controllers\MessageController;
use App\Http\Controllers\ChatController;

use App\Modules\Inventory\Http\Controllers\LmStockLevelController;
use App\Modules\Inventory\Http\Controllers\LmItemController;
use App\Modules\Inventory\Http\Controllers\FiItemController;
use App\Modules\Inventory\Http\Controllers\MaStockLevelController;
use App\Modules\Inventory\Http\Controllers\PoStockMovementController;
use App\Modules\Inventory\Http\Controllers\PoSupplierMgtController;
use App\Modules\Production\Http\Controllers\Manufacturer\ProductionController;
use App\Modules\Product\Http\Controllers\ProductController;
use App\Modules\Product\Http\Controllers\VendorProductController;
use App\Modules\Orders\Http\Controllers\SupplierOrderController;
use App\Modules\Orders\Http\Controllers\VendorOrderController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\ManufacturerController;
use App\Modules\Orders\Http\Controllers\CustomerOrderController;
use App\Modules\Inventory\Http\Controllers\MaPurchaseOrderController;
use App\Http\Controllers\SetPasswordController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalesReportController;


Route::prefix('work-distribution')->group(function () {
    // (Tasks above)
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

    // Shifts
    Route::get('/shifts', [ShiftController::class, 'index'])->name('shifts.index');
    Route::get('/shifts/create', [ShiftController::class, 'create'])->name('shifts.create');
    Route::post('/shifts', [ShiftController::class, 'store'])->name('shifts.store');
});
Route::prefix('work-distribution')->group(function () {

    // Show the Task form
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');

    // Save the Task (handle the form POST)
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

});
/*
|--------------------------------------------------------------------------
| Public Routes (No login required)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Vendor Application routes are public because users are not logged in yet.
Route::get('vendor-application', [VendorApplicationController::class, 'create'])->name('vendor.application.create');
Route::post('vendor-application', [VendorApplicationController::class, 'store'])->name('vendor.application.store');

// All authentication routes (login, register, password reset, etc.) from Breeze.
require __DIR__.'/auth.php';


/*
|--------------------------------------------------------------------------
| Authenticated Routes (Login and email verification required)
|--------------------------------------------------------------------------
*/


// A single group for ALL routes that require a user to be logged in.
Route::middleware(['auth', 'verified'])->group(function () {

    // Main dashboard entry point, correctly pointing to the 'index' method.
   
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/inventory', InventoryDashboardController::class)->name('inventory.dashboard');

    // Profile routes from Breeze, now inside the main auth group.
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


     // --- COMMUNICATION ROUTES (Available to all logged-in users) ---
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/messages/{id}/read', [MessageController::class, 'markAsRead'])->name('messages.markAsRead');

    // --- REPORTS ROUTES (Available to all logged-in users) ---
  
    

 


    // --- DASHBOARD ROUTES (Available to all logged-in users) ---
   // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // --- INVENTORY ROUTES (Available to all logged-in users) ---
    //Route::get('/inventory', [InventoryDashboardController::class, 'index'])->name('inventory.index');

    // --- PAYMENT ROUTES (Available to all logged-in users) ---
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments/charge', [PaymentController::class, 'charge'])->name('payments.charge');

   




   // --- CHAT ROUTES (Available to all logged-in users) ---
    Route::get('/chat/{user}', [ChatController::class, 'chat'])->name('chat.with');
    Route::post('/chat/send/{user}', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chats', [ChatController::class, 'users'])->name('chat.page');
   

});

    // --- ROLE-SPECIFIC FUNCTIONALITY ROUTES ---

    // All routes for a Supplier's specific actions (e.g., managing their inventory).
    Route::middleware(['role:Supplier'])->prefix('supplier')->name('supplier.')->group(function () {
        // Example: Route::get('/inventory', [InventoryStockController::class, 'index'])->name('inventory.index');
    });

    // All routes for a Liquor Manager's specific actions.


        // All routes for a Liquor Manager's specific actions.
    Route::middleware(['role:Liquor Manager'])->prefix('manager')->name('manager.')->group(function () {


        Route::get('/stock-levels', [LmStockLevelController::class, 'index'])->name('stock_levels.index');
        Route::resource('items', LmItemController::class);
        Route::get('/purchase-orders', [MaPurchaseOrderController::class, 'index'])->name('purchase_orders.index');
    });

        // All routes for a Finance's specific actions.
    Route::middleware(['role:Finance'])->prefix('finance')->name('finance.')->group(function () {
        Route::get('/items', [FiItemController::class, 'index'])->name('items.index');
        Route::patch('/items/{product}', [FiItemController::class, 'updatePrice'])->name('items.update_price');
    });

        // All routes for a Customer's specific actions.
    Route::middleware(['role:Customer'])->prefix('customer')->name('customer.')->group(function () {
        // ...
    });

        // All routes for a manufacturer's specific actions.
    Route::middleware(['role:Manufacturer'])->prefix('manufacturer')->name('manufacturer.')->group(function () {
        Route::get('/stock-levels', [MaStockLevelController::class, 'index'])->name('stock_levels.index');
        Route::get('/production', [ProductionController::class, 'index'])->name('production.index');
        Route::post('/production', [ProductionController::class, 'store'])->name('production.store');
    });


/*----------------------------------------------------
Communication routes
------------------------------------------------------*/
Route::middleware(['auth'])->group(function () {
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index'); // showing view and messages
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show'); // single user messages
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store'); // send message
    Route::post('/messages/{id}/read', [MessageController::class, 'markAsRead'])->name('messages.markAsRead'); // mark as read
    Route::delete('/messages/{id}', [MessageController::class, 'destroy'])->middleware('auth'); // delete message
});

// All routes for a Procurement officer's specific actions.


// --- REPORTS ROUTES (Available to all logged-in users) ---
Route::middleware(['auth'])->group(function () {
    
    // Reports index
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Individual PDF report routes
    Route::get('/reports/inventory', [ReportController::class, 'inventoryPdf'])->name('reports.inventory');
    Route::get('/reports/sales', [ReportController::class, 'salesPdf'])->name('reports.sales');
    Route::get('/reports/vendor', [ReportController::class, 'vendorPdf'])->name('reports.vendor');

    // Charts or visual report data (optional)
    Route::get('/reports/sales/chart', [ReportController::class, 'salesChart'])->name('reports.sales.chart');
});




//Route:: get('/dashboard/payments/success', [PaymentController::class, 'success'])->name('payments');
//Route:: get('/dashboard/payments/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
Route::middleware('auth')->group(function () {
    // Route to show the payment form for a specific order.
    // Example URL: /order/123/pay
    Route::get('/order/{order}/pay', [PaymentController::class, 'showPaymentForm'])->name('payment.form');

    // Route that the form submits to (processes the payment)
    Route::post('/stripe/charge', [PaymentController::class, 'processPayment'])->name('stripe.charge');

    // A simple "Thank You" page to redirect to after payment
    Route::get('/payment/thank-you', [PaymentController::class, 'thankYou'])->name('payment.thankyou');
});

// The webhook route must be exempt from CSRF protection
Route::post('/stripe/webhook', [PaymentController::class, 'handleWebhook'])->name('stripe.webhook');


        // All routes for a Procurement officer's specific actions.

    Route::middleware(['role:Procurement Officer'])->prefix('officer')->name('officer.')->group(function () {
    Route::get('/stock-movements', [PoStockMovementController::class, 'index'])->name('stock_movements.index');
        Route::post('/stock-movements', [PoStockMovementController::class, 'store'])->name('stock_movements.store');
        Route::get('/supplier-overview', [PoSupplierMgtController::class, 'index'])->name('supplier.overview');
    });

        // All routes for a Vendor's specific actions.
    Route::middleware(['role:Vendor'])->prefix('vendor')->name('vendor.')->group(function () {
        Route::get('/dashboard', function (){
            $stats =[
                'newChats' => 0,
                'outOfStock' => 0,
                'unfulfilledOrders' => 0,
                'salesTotal' => 0,
            ];
            return view('vendor.dashboard', compact('stats'));
        })->name('dashboard');
    });

    Route::middleware(['can:view stock levels'])->group(function () {


    // Now, any user whose role has the 'view stock levels' permission can access this.
    Route::get('/stock-levels', [LmStockLevelController::class, 'index'])->name('manager.stock_levels.index');
    Route::get('/stock-levels', [LmStockLevelController::class, 'index'])->name('officer.stock_levels.index');

    });




// Route to set initial password via signed link
Route::get('/set-password/{user}', [SetPasswordController::class, 'show'])
    ->middleware(['signed']) // ensures link validity
    ->name('password.set');

Route::post('/set-password/{user}', [SetPasswordController::class, 'update'])
    ->name('password.set.update');

// Route for dashboard, redirects user based on their role
Route::middleware(['auth'])->group(function () {
    // 1. Liquor Manager Routes
    Route::middleware('role:Liquor Manager')->prefix('liquor-manager')->name('liquor-manager.')->group(function () {
        Route::resource('products', ProductController::class);
        Route::get('products', [ProductController::class, 'index'])->name('products.index');
    });

    // 2. Supplier Routes
    Route::middleware('role:Supplier')->prefix('supplier')->name('supplier.')->group(function () {
        Route::resource('orders', SupplierOrderController::class)->only(['index', 'show', 'create', 'store']);
        // NEW ROUTE for the supplier to mark an order as delivering
    Route::patch('/orders/{order}/deliver', [SupplierOrderController::class, 'markAsDelivering'])
        ->name('orders.markAsDelivering');
        //Route::get('orders', [SupplierOrderController::class, 'index'])->name('orders.index');
        //Route::resource('supplier/orders', SupplierOrderController::class)->names('supplier.orders');
        // Route to show the form for editing an existing order
        Route::get('/supplier/orders/{order}/edit', [SupplierOrderController::class, 'edit'])->name('orders.edit');

        // Route to handle the submission of the edit form
        Route::put('/supplier/orders/{order}', [SupplierOrderController::class, 'update'])->name('orders.update');
        Route::delete('/supplier/orders/{order}', [SupplierOrderController::class, 'destroy'])->name('orders.destroy');
        Route::get('orders/{order}', [SupplierOrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}', [SupplierOrderController::class, 'update'])->name('orders.update');
    });

    // 3. Manufacturer Routes
    Route::middleware('role:Manufacturer')->prefix('manufacturer')->name('manufacturer.')->group(function () {
        Route::get('orders', [ManufacturerController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [ManufacturerController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}', [ManufacturerController::class, 'update'])->name('orders.update');
        // ... your other routes ...
    Route::get('/orders/paid', [ManufacturerController::class, 'paidOrders'])->name('orders.paid');

    // NEW ROUTE to view all delivering orders
    Route::get('/orders/delivering', [ManufacturerController::class, 'deliveringOrders'])
        ->name('orders.delivering');

    // NEW ROUTE to confirm the delivery
    Route::patch('/orders/{order}/receive', [ManufacturerController::class, 'confirmDelivery'])
        ->name('orders.confirmDelivery');
    });

    // 4. Vendor Routes (Placing Orders)
    Route::middleware('role:Vendor')->prefix('vendor')->name('vendor.')->group(function () {
        Route::resource('orders', VendorOrderController::class)->only(['index', 'show', 'create', 'store']);
        Route::resource('products', VendorProductController::class)->only(['index', 'edit', 'update']);
    });

    // 5. Procurement Officer Routes
    Route::middleware('role:Procurement Officer')->prefix('procurement')->name('procurement.')->group(function () {
        Route::get('orders', [ProcurementController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [ProcurementController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}', [ProcurementController::class, 'update'])->name('orders.update');
    });

    // 7. Customer Routes
    Route::middleware('role:Customer')->prefix('customer')->name('customer.')->group(function () {
        Route::get('browse/{vendor}', [CustomerOrderController::class, 'browse'])->name('browse');
        Route::get('orders', [CustomerOrderController::class, 'index'])->name('orders.index');
        Route::resource('orders', CustomerOrderController::class)->only(['index', 'show', 'create', 'store']);
    });

    Route::middleware(['auth', 'role:Liquor Manager|Procurement Officer|Finance'])->group(function () {
        Route::get('/analytics/dashboard', [AnalyticsController::class, 'dashboard'])->name('analytics.dashboard');
    });

    Route::middleware(['auth', 'role:Finance|Liquor Manager'])->prefix('analytics')->group(function () {
        Route::get('/dashboard', [AnalyticsController::class, 'analyticsMenu'])->name('analytics.menu');
        Route::get('/forecast', [AnalyticsController::class, 'forecast'])->name('analytics.forecast');
        Route::get('/segmentation', [AnalyticsController::class, 'segmentation'])->name('analytics.segmentation');
});
  Route::middleware(['auth'])->group(function () {
    Route::get('/reports/sales/weekly', [SalesReportController::class, 'weeklyReport'])->name('reports.sales.weekly');
    Route::get('/reports/sales/weekly/pdf', [SalesReportController::class, 'downloadWeeklyPdf'])->name('reports.sales.weekly.pdf');
});

});

Route::get('/set-password/{user}', [SetPasswordController::class, 'show'])
    ->middleware(['signed'])//ensures link validity
    ->name('password.set');

Route::post('/set-password/{user}', [SetPasswordController::class, 'update'])
    ->name('password.set.update');




