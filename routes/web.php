<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root ke dashboard atau login
Route::get('/', function () {
    return auth()->check() ? redirect()->route('admin.dashboard') : redirect()->route('login');
});

// Auth routes (login/logout bawaan Laravel)
Auth::routes(['register' => false, 'reset' => false]);

// Route grup Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Inventaris
    Route::resource('inventories', InventoryController::class);

    // CRUD Jadwal Peminjaman
    Route::resource('schedules', ScheduleController::class);
});
