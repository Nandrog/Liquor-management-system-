<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Modules\Orders\Http\Controllers\OrderController;
use App\Http\Controllers\VendorApplicationController;
// KEEP ONLY THE NEW, MODULAR CONTROLLER IMPORT. Delete any other DashboardController import.
use App\Modules\Dashboard\Http\Controllers\DashboardController;
use App\Modules\Payments\Http\Controllers\PaymentsController;
use App\Http\Controllers\WorkDistribution\TaskController;
use App\Http\Controllers\WorkDistribution\ShiftController;

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

//Route::middleware(['auth'])->post('/order', [OrderController::class, 'placeOrder']);

Route:: get('/dashboard/order', [OrderController::class, 'index'])->name('order');
Route:: get('/dashboard/payments', [PaymentsController::class, 'index'])->name('payments');
Route:: get('/dashboard/orders', [OrderController::class, 'orders'])->name('orders');

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
        // ...
    });

    // Add other role-specific route groups here...

});
