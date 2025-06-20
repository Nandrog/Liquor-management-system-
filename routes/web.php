<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\VendorApplicationController;

Route::middleware(['auth'])->post('/order', [OrderController::class, 'placeOrder']);


Route::get('/', function () {
    return view('welcome');
});

Route::get('vendor-application', [VendorApplicationController::class, 'create'])->name('vendor.application.create');
Route::post('vendor-application', [VendorApplicationController::class, 'store'])->name('vendor.application.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
