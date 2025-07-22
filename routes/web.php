<?php
use App\Http\Controllers\CartController;
use App\Http\Controllers\StorefrontController;
// KEEP ONLY THE NEW, MODULAR CONTROLLER IMPORT. Delete any other DashboardController import.

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VendorApplicationController;
use App\Modules\Dashboard\Http\Controllers\DashboardController;
use App\Modules\Inventory\Http\Controllers\DashboardController as InventoryDashboardController;
use App\Modules\Payments\Http\Controllers\PaymentController;
use App\Modules\Payments\Http\Controllers\PaymentsController;
use App\Http\Controllers\WorkDistribution\TaskController;
use App\Http\Controllers\WorkDistribution\ShiftController;
use App\Modules\Communications\Http\Controllers\MessageController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\InventoryReportController;
use App\Http\Controllers\Auth\VendorRegistrationController;
use App\Http\Controllers\ReportDashboardController;
use App\Modules\Inventory\Http\Controllers\LmStockLevelController;
use App\Modules\Inventory\Http\Controllers\MaPurchaseOrderController;
use App\Modules\Inventory\Http\Controllers\LmItemController;
use App\Modules\Inventory\Http\Controllers\FiItemController;
use App\Modules\Inventory\Http\Controllers\MaStockLevelController;
use App\Modules\Inventory\Http\Controllers\PoStockMovementController;
use App\Modules\Inventory\Http\Controllers\PoSupplierMgtController;
use App\Modules\Production\Http\Controllers\Manufacturer\ProductionController;
use App\Modules\Product\Http\Controllers\ProductController;
use App\Modules\Product\Http\Controllers\VendorProductController;
use App\Modules\Orders\Http\Controllers\OrderController;
use App\Modules\Orders\Http\Controllers\SupplierOrderController;
use App\Modules\Orders\Http\Controllers\VendorOrderController;
use App\Modules\Orders\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\SetPasswordController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalesReportController;
use App\Modules\Inventory\Http\Controllers\Finance\OrderReportController;
use App\Modules\Inventory\Http\Controllers\FiOrderReportController;
use App\Http\Controllers\ProductLogController;
//use App\Modules\Inventory\Http\Controllers\MaPurchaseOrderController;



Route::prefix ('work-distribution')->group(function () {
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
    Route::get('/officer/work-distribution/task-list', [TaskController::class, 'taskList'])
     ->name('officer.work-distribution.task-list');

});
/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('vendor-application', [VendorApplicationController::class, 'create'])->name('vendor.application.create');
Route::post('vendor-application', [VendorApplicationController::class, 'store'])->name('vendor.application.store');

Route::get('/register/vendor/{application}', [VendorRegistrationController::class, 'create'])->name('vendor.registration.create');
Route::post('/register/vendor', [VendorRegistrationController::class, 'store'])->name('vendor.registration.store');

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/



Route::middleware(['auth', 'verified'])->group(function () {
    // Main Dashboard

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/inventory', InventoryDashboardController::class)->name('inventory.dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
    /*
    |--------------------------------------------------------------------------
    | Work Distribution
    |--------------------------------------------------------------------------
    */
    Route::prefix('work-distribution')->group(function () {
        // Tasks
        Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
        Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
        Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

        // Shifts
        Route::get('/shifts', [ShiftController::class, 'index'])->name('shifts.index');
        Route::get('/shifts/create', [ShiftController::class, 'create'])->name('shifts.create');
        Route::post('/shifts', [ShiftController::class, 'store'])->name('shifts.store');
        Route::delete('/shifts/{shift}', [ShiftController::class, 'destroy'])->name('shifts.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Communication
    |--------------------------------------------------------------------------
    */
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/messages/{id}/read', [MessageController::class, 'markAsRead'])->name('messages.markAsRead');
    Route::delete('/messages/{id}', [MessageController::class, 'destroy'])->name('messages.destroy');

    /*
    |--------------------------------------------------------------------------
    | Supplier Routes
    |--------------------------------------------------------------------------
    */

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


Route::middleware(['auth', 'role:Liquor Manager|Finance|Procurement Officer|Manufacturer'])
    ->prefix('reports')
    ->name('reports.')
    ->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/stock-movements', [ReportController::class, 'stockMovements'])->name('stock_movements');
        Route::get('/shift-schedules', [ReportController::class, 'shiftSchedules'])->name('shift_schedules');
        Route::get('/task-performance', [ReportController::class, 'taskPerformance'])->name('task_performance');
        Route::get('/reports/shift-schedules', [ReportController::class, 'shiftSchedules'])->name('reports.shift_schedules');
    });


    // --- ROLE-SPECIFIC FUNCTIONALITY ROUTES ---

    // All routes for a Supplier's specific actions (e.g., managing their inventory).
    Route::middleware(['role:Supplier'])->prefix('supplier')->name('supplier.')->group(function () {
        Route::get('/orders/paid', [SupplierOrderController::class, 'paidOrders'])->name('orders.paid');
        Route::get('/orders/delivery', [SupplierOrderController::class, 'readyForDelivery'])->name('orders.delivery');
        Route::resource('orders', SupplierOrderController::class)->only(['index', 'show', 'create', 'store']);
        Route::patch('/orders/{order}/deliver', [SupplierOrderController::class, 'markAsDelivering'])->name('orders.markAsDelivering');
        Route::patch('/orders/{order}/mark-as-delivered', [SupplierOrderController::class, 'markAsDelivered'])->name('orders.markAsDelivered');
        Route::get('/orders/{order}/edit', [SupplierOrderController::class, 'edit'])->name('orders.edit');
        Route::put('/orders/{order}', [SupplierOrderController::class, 'update'])->name('orders.update');
        Route::delete('/orders/{order}', [SupplierOrderController::class, 'destroy'])->name('orders.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Manufacturer Routes
    |--------------------------------------------------------------------------
    */
        // Example: Route::get('/inventory', [InventoryStockController::class, 'index'])->name('inventory.index');



    // All routes for a Liquor Manager's specific actions.


        // All routes for a Liquor Manager's specific actions.
    Route::middleware(['role:Liquor Manager'])->prefix('manager')->name('manager.')->group(function () {


        Route::get('/stock-levels', [LmStockLevelController::class, 'index'])->name('stock_levels.index');
        Route::resource('items', LmItemController::class);
        Route::get('/purchase-orders', [MaPurchaseOrderController::class, 'index'])->name('purchase_orders.index');

        Route::get('/tasks', [TaskController::class, 'index'])->name('work-distribution.task-list');
        Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
        Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');


    });

        // All routes for a Finance's specific actions.
    Route::middleware(['role:Finance'])->prefix('finance')->name('finance.')->group(function () {
        Route::get('/items', [FiItemController::class, 'index'])->name('items.index');
        Route::patch('/items/{product}', [FiItemController::class, 'updatePrice'])->name('items.update_price');
        Route::get('/supplier-orders', [OrderReportController::class, 'supplierOrders'])->name('orders.supplier_report');
        Route::get('/sales-orders-report', [FiOrderReportController::class, 'salesOrders'])->name('orders.sales_report');
        Route::get('/tasks', [TaskController::class, 'index'])->name('work-distribution.task-list');
        Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
        Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

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

        Route::get('/tasks', [ShiftController::class, 'index'])->name('work-distribution.shift-list');
        Route::get('/tasks/create', [ShiftController::class, 'create'])->name('shift.create');
        Route::post('/tasks', [ShiftController::class, 'store'])->name('shift.store');
        Route::get('/reports/sales/weekly', [ReportController::class, 'weeklySales'])->name('reports.sales.weekly');

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

    Route::get('/reports/sales', [ReportController::class, 'salesPdf'])->name('reports.sales');
    Route::get('/reports/vendor', [ReportController::class, 'vendorPdf'])->name('reports.vendor');
Route::get('/reports/inventory', [ReportController::class, 'inventoryView'])->name('reports.inventory');
Route::get('/reports/inventory/pdf', [ReportController::class, 'inventoryPdf'])->name('reports.inventory.pdf');
Route::get('/reports/inventory/chart', [ReportController::class, 'inventoryView'])->name('reports.inventory_chart');
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

        Route::get('/tasks', [TaskController::class, 'index'])->name('work-distribution.task-list');
        Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
        Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

        Route::get('/orders', [ManufacturerController::class, 'index'])->name('orders.index');
        Route::get('/orders/paid', [ManufacturerController::class, 'paidOrders'])->name('orders.paid');
        Route::get('/orders/delivery', [ManufacturerController::class, 'deliveringOrders'])->name('orders.delivery');
        Route::get('/orders/{order}', [ManufacturerController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}', [ManufacturerController::class, 'update'])->name('orders.update');
        Route::patch('/orders/{order}/mark-as-delivered', [ManufacturerController::class, 'markAsDelivered'])->name('orders.markAsDelivered');
        Route::patch('/orders/{order}/receive', [ManufacturerController::class, 'confirmDelivery'])->name('orders.confirmDelivery');
    });

    /*
    |--------------------------------------------------------------------------
    | Vendor Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:Vendor'])->prefix('vendor')->name('vendor.')->group(function () {
        Route::get('/dashboard', function () {
            $stats = [
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
        Route::get('/orders/paid', [SupplierOrderController::class, 'paidOrders'])->name('orders.paid');
        Route::get('/orders/delivery', [SupplierOrderController::class, 'readyForDelivery'])->name('orders.delivery');
        Route::resource('orders', SupplierOrderController::class)->only(['index', 'show', 'create', 'store']);
        // NEW ROUTE for the supplier to mark an order as delivering
    Route::patch('/orders/{order}/deliver', [SupplierOrderController::class, 'markAsDelivering'])
        ->name('orders.markAsDelivering');
        Route::patch('/orders/{order}/mark-as-delivered', [SupplierOrderController::class, 'markAsDelivered'])
        ->name('orders.markAsDelivered');
        //Route::get('orders', [SupplierOrderController::class, 'index'])->name('orders.index');
        //Route::resource('supplier/orders', SupplierOrderController::class)->names('supplier.orders');
        // Route to show the form for editing an existing order
        Route::get('/supplier/orders/{order}/edit', [SupplierOrderController::class, 'edit'])->name('orders.edit');
        Route::get('/suppliers/payments', [PaymentController::class, 'index'])
        ->name('payments.index');
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        // Route to handle the submission of the edit form
        Route::put('/supplier/orders/{order}', [SupplierOrderController::class, 'update'])->name('orders.update');
        Route::delete('/supplier/orders/{order}', [SupplierOrderController::class, 'destroy'])->name('orders.destroy');
        Route::put('/supplier-orders/{order}', [SupplierOrderController::class, 'update'])->name('orders.update');
        Route::get('orders/{order}', [SupplierOrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}', [SupplierOrderController::class, 'update'])->name('orders.update');
    });

    // 3. Manufacturer Routes
    Route::middleware('role:Manufacturer')->prefix('manufacturer')->name('manufacturer.')->group(function () {
        Route::get('orders', [ManufacturerController::class, 'index'])->name('orders.index');

        Route::get('/orders/delivery', [ManufacturerController::class, 'deliveringOrders'])->name('orders.delivery');

        Route::get('/orders/paid', [ManufacturerController::class, 'paidOrders'])->name('orders.paid');


        Route::get('orders/{order}', [ManufacturerController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}', [ManufacturerController::class, 'update'])->name('orders.update');
        // ... your other routes ...

    // NEW ROUTE to view all delivering orders

    // Route for the action of marking an order as delivered
    Route::patch('/orders/{order}/mark-as-delivered', [ManufacturerController::class, 'markAsDelivered'])->name('orders.markAsDelivered');

    Route::patch('/orders/{order}/receive', [ManufacturerController::class, 'confirmDelivery'])
        ->name('orders.confirmDelivery');
    });

    // 4. Vendor Routes (Placing Orders)
    Route::middleware('role:Vendor')->prefix('vendor')->name('vendor.')->group(function () {
        Route::resource('orders', VendorOrderController::class)->only(['index', 'show', 'create', 'store']);
        Route::get('orders', [VendorOrderController::class, 'index'])->name('orders.index');
        Route::get('Customer-orders', [CartController::class, 'indexs'])->name('orders.indexs');
        Route::resource('products', VendorProductController::class)->only(['index', 'edit', 'update']);
        Route::get('/products', [VendorProductController::class, 'index'])->name('products.index');
        Route::get('/customer-carts', [VendorOrderController::class, 'showCustomerCartLookup'])->name('carts.lookup');
        Route::patch('/products/{product}', [VendorProductController::class, 'update'])->name('products.update');
        Route::get('/orders/{order}', [VendorOrderController::class, 'show'])->name('orders.show');
        Route::patch('/products/bulk-update', [VendorProductController::class, 'bulkUpdate'])->name('products.bulk-update');
        // Route to show the payment page for a specific order
        Route::get('/orders/{order}/payment', [VendorOrderController::class, 'showPaymentPage'])->name('orders.payment.create');

        // Route to process the payment (this would interact with a payment gateway)
        Route::post('/orders/{order}/payment', [VendorOrderController::class, 'processPayment'])->name('orders.payment.store');
    });

    /*
    |--------------------------------------------------------------------------
    | Customer Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:Customer'])->prefix('customer')->name('customer.')->group(function () {
        Route::get('browse/{vendor}', [CustomerOrderController::class, 'browse'])->name('browse');
        Route::get('orders', [CustomerOrderController::class, 'index'])->name('orders.index');
        Route::resource('orders', CustomerOrderController::class)->only(['index', 'show', 'create', 'store']);
         // Route for showing the checkout page with the shipping form
        Route::get('/checkout', [CustomerOrderController::class, 'create'])->name('checkout.create');

    // Route for processing the checkout form submission
        Route::post('/checkout', [CustomerOrderController::class, 'store'])->name('checkout.store');

    });

    /*
    |--------------------------------------------------------------------------
    | Procurement Officer Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:Procurement Officer'])->prefix('procurement')->name('procurement.')->group(function () {
        Route::get('orders', [ProcurementController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [ProcurementController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}', [ProcurementController::class, 'update'])->name('orders.update');
        Route::get('/stock-movements', [PoStockMovementController::class, 'index'])->name('stock_movements.index');
        Route::post('/stock-movements', [PoStockMovementController::class, 'store'])->name('stock_movements.store');
        Route::get('/supplier-overview', [PoSupplierMgtController::class, 'index'])->name('supplier.overview');
    Route::get('/tasks', [TaskController::class, 'index'])->name('work-distribution.task-list');
        Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
        Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
        Route::middleware(['role:Procurement Officer'])->prefix('officer')->name('officer.')->group(function () {
    Route::get('/work-distribution/tasks', [TaskController::class, 'index'])->name('work-distribution.task-list');
});

});
    /*
    |--------------------------------------------------------------------------
    | Finance Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:Finance'])->prefix('finance')->name('finance.')->group(function () {
        Route::get('/items', [FiItemController::class, 'index'])->name('items.index');
        Route::patch('/items/{product}', [FiItemController::class, 'updatePrice'])->name('items.update_price');
    });Route::get('/tasks', [TaskController::class, 'index'])->name('work-distribution.task-list');
        Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
        Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

    /*
    |--------------------------------------------------------------------------
    | Liquor Manager Routes
    |--------------------------------------------------------------------------
    */
   Route::middleware(['role:Liquor Manager'])
    ->prefix('manager')
    ->name('manager.') // ✅ match prefix!
    ->group(function () {
        Route::get('/stock-levels', [LmStockLevelController::class, 'index'])->name('stock_levels.index');
        Route::resource('items', LmItemController::class);
        Route::get('/purchase-orders', [MaPurchaseOrderController::class, 'index'])->name('purchase_orders.index');
        Route::resource('products', ProductController::class)->only(['index']);
        Route::get('/tasks', [TaskController::class, 'index'])->name('work-distribution.task-list');
        Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
        Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

    });

    /*
    |--------------------------------------------------------------------------
    | Analytics
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'role:Finance|Liquor Manager|Procurement Officer|Supplier'])->group(function () {
        Route::get('/analytics/dashboard', [AnalyticsController::class, 'dashboard'])->name('analytics.dashboard');
        Route::get('/analytics/menu', [AnalyticsController::class, 'analyticsMenu'])->name('analytics.menu');
        Route::get('/analytics/forecast', [AnalyticsController::class, 'forecast'])->name('analytics.forecast');
        Route::get('/analytics/segmentation', [AnalyticsController::class, 'segmentation'])->name('analytics.segmentation');
    });

    /*
    |--------------------------------------------------------------------------
    | Payments
    |--------------------------------------------------------------------------
    */
    Route::get('/order/{order}/pay', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
    Route::post('/stripe/charge', [PaymentController::class, 'processPayment'])->name('stripe.charge');
    Route::get('/payment/thank-you', [PaymentController::class, 'thankYou'])->name('payment.thankyou');
    Route::post('/stripe/webhook', [PaymentController::class, 'handleWebhook'])->name('stripe.webhook');

    /*
    |--------------------------------------------------------------------------
    | Notifications: Mark all read
    |--------------------------------------------------------------------------
    */
    Route::get('/notifications/mark-all-read', function () {
        \Illuminate\Support\Facades\Auth::user()->unreadNotifications->markAsRead();
        if (Auth::check()) {
            Auth::user()->unreadNotifications->markAsRead();
        }
        return back();
    })->name('notifications.markAllRead');

    /*
    |--------------------------------------------------------------------------
    | Set Password Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/set-password/{user}', [SetPasswordController::class, 'show'])
        ->middleware(['signed'])
        ->name('password.set');
    });

    Route::middleware(['auth', 'role:Finance|Liquor Manager|Supplier'])->prefix('analytics')->group(function () {
        Route::get('/dashboard', [AnalyticsController::class, 'analyticsMenu'])->name('analytics.menu');
        Route::get('/forecast', [AnalyticsController::class, 'forecast'])->name('analytics.forecast');
        Route::get('/segmentation', [AnalyticsController::class, 'segmentation'])->name('analytics.segmentation');
});
   // Route::middleware(['auth'])->group(function () {
   // Route::get('/reports/sales/weekly', [SalesReportController::class, 'weeklyReport'])->name('reports.sales.weekly');
   // Route::get('/reports/sales/weekly/pdf', [SalesReportController::class, 'downloadWeeklyPdf'])->name('reports.sales.weekly.pdf');
//});

// in routes/web.php



// ... other routes

Route::middleware(['auth'])->group(function () {
    // This route will show the report in the browser
    Route::get('/reports/weekly-summary', [SalesReportController::class, 'showWeeklySummaryReport'])
         ->name('reports.weekly_summary.show');

    // This route will download the report as a PDF
    Route::get('/reports/weekly-summary/download', [SalesReportController::class, 'downloadWeeklySummaryReport'])
         ->name('reports.weekly_summary.download');

Route::get('/reports/sales/weekly', [SalesReportController::class, 'showWeeklySummaryReport'])->name('reports.sales.weekly');
Route::get('/reports/sales/weekly/download', [SalesReportController::class, 'downloadWeeklySummaryReport'])->name('reports.sales.weekly.download');

});



Route::get('/set-password/{user}', [SetPasswordController::class, 'show'])
    ->middleware(['signed'])//ensures link validity
    ->name('password.set');

Route::post('/set-password/{user}', [SetPasswordController::class, 'update'])
    ->name('password.set.update');


Route::middleware(['auth'])->group(function () {

    Route::get('/storefront', [StorefrontController::class, 'index'])->name('storefront.index');


    Route::get('/storefront/product/{product}', [StorefrontController::class, 'show'])->name('storefront.show');
    Route::get('/reports/inventory-finance', [InventoryReportController::class, 'showFinanceReport'])
     ->name('reports.inventory.finance');
     // ... inside your auth middleware group ...
Route::get('/reports/inventory-procurement', [InventoryReportController::class, 'showProcurementReport'])
     ->name('reports.inventory.procurement');
     // We'll create a simple controller for this page


// ... inside your auth middleware group ...
Route::get('/reports', [ReportDashboardController::class, 'index'])
     ->name('reports.index');
     // ... inside your auth middleware group ...
Route::get('/reports/inventory-raw-materials', [InventoryReportController::class, 'showRawMaterialsReport'])
     ->name('reports.inventory.raw_materials');
    




Route::get('/products', [ProductLogController::class, 'index'])->name('products.index');


Route::post('/products/{product}/add-stock', [ProductLogController::class, 'addStock'])->name('products.add-stock');

Route::get('/reports/inventory-manufacturer', [InventoryReportController::class, 'showManufacturerReport'])
     ->name('reports.inventory.manufacturer');

});

Route::middleware(['auth'])->prefix('cart')->name('cart.')->group(function () {

    // Route to display the cart page
    Route::get('/', [CartController::class, 'index'])->name('index');

    // Route to add an item to the cart (this fixes your error)
    Route::post('/add', [CartController::class, 'add'])->name('add');

    // Route to update item quantities in the cart
    Route::patch('/cart/update', [CartController::class, 'update'])->name('update');
    // Route to remove an item from the cart
    Route::delete('/cart/remove', [CartController::class, 'remove'])->name('remove');
    Route::post('/remove', [CartController::class, 'remove'])->name('remove');

});
    Route::post('/set-password/{user}', [SetPasswordController::class, 'update'])->name('password.set.update');

    Route::prefix('officer')->name('officer.work-distribution.')->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])->name('task-list');
});


// ... inside your auth middleware group ...
