<?php

use App\Modules\Inventory\Http\Controllers\DashboardController as InventoryDashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VendorApplicationController;
// KEEP ONLY THE NEW, MODULAR CONTROLLER IMPORT. Delete any other DashboardController import.
use App\Modules\Dashboard\Http\Controllers\DashboardController;
use App\Modules\Inventory\Http\Controllers\LmStockLevelController;
use App\Modules\Inventory\Http\Controllers\LmItemController;
use App\Modules\Inventory\Http\Controllers\FiItemController;
use App\Modules\Inventory\Http\Controllers\MaStockLevelController;
use App\Modules\Inventory\Http\Controllers\PoStockMovementController;
use App\Modules\Inventory\Http\Controllers\PoSupplierMgtController;
use App\Modules\Production\Http\Controllers\Manufacturer\ProductionController;

/*
|--------------------------------------------------------------------------
| Public Routes (No login required)
|--------------------------------------------------------------------------
*/

//Route::middleware(['auth'])->post('/order', [OrderController::class, 'index']);


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

    // --- ROLE-SPECIFIC FUNCTIONALITY ROUTES ---

    // All routes for a Supplier's specific actions (e.g., managing their inventory).
    Route::middleware(['role:Supplier'])->prefix('supplier')->name('supplier.')->group(function () {
        // Example: Route::get('/inventory', [InventoryStockController::class, 'index'])->name('inventory.index');
});

    // All routes for a Liquor Manager's specific actions.
    Route::middleware(['role:Liquor Manager'])->prefix('manager')->name('manager.')->group(function () {

        Route::get('/stock-levels', [LmStockLevelController::class, 'index'])->name('stock_levels.index');
        Route::resource('items', LmItemController::class);
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

        // All routes for a Procurement officer's specific actions.
    Route::middleware(['role:Procurement Officer'])->prefix('officer')->name('officer.')->group(function () {
       Route::get('/stock-movements', [PoStockMovementController::class, 'index'])->name('stock_movements.index');
        Route::post('/stock-movements', [PoStockMovementController::class, 'store'])->name('stock_movements.store');
        Route::get('/supplier-overview', [PoSupplierMgtController::class, 'index'])->name('supplier.overview');
    });

        // All routes for a Liquor Manager's specific actions.
    Route::middleware(['role:Vendor'])->prefix('vendor')->name('vendor.')->group(function () {
        // ...
    });

    Route::middleware(['can:view stock levels'])->group(function () {

    // Now, any user whose role has the 'view stock levels' permission can access this.
    Route::get('/stock-levels', [LmStockLevelController::class, 'index'])->name('manager.stock_levels.index');
    Route::get('/stock-levels', [LmStockLevelController::class, 'index'])->name('officer.stock_levels.index');

});

});
