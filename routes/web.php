<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::middleware(['auth'])->post('/order', [OrderController::class, 'placeOrder']);


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return "This would be the login page.";
})->name('login');
