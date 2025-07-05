<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Communications\Http\Controllers\MessageController;

// All routes in this file are automatically loaded by the service provider.
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/messages/{id}/read', [MessageController::class, 'markAsRead'])->name('messages.markAsRead');
});