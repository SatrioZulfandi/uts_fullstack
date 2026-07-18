<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\InventoryController as MemberInventoryController;
use App\Http\Controllers\Member\ScheduleController as MemberScheduleController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root ke dashboard berdasarkan role, atau ke login
Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('member.dashboard');
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
    Route::post('schedules/{schedule}/approve', [ScheduleController::class, 'approve'])->name('schedules.approve');
    Route::post('schedules/{schedule}/reject', [ScheduleController::class, 'reject'])->name('schedules.reject');
    Route::resource('schedules', ScheduleController::class);
});

// Route grup Member
Route::middleware(['auth', 'member'])->prefix('member')->name('member.')->group(function () {

    // Dashboard Member
    Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');

    // Inventaris Tersedia (read-only)
    Route::get('/inventories', [MemberInventoryController::class, 'index'])->name('inventories.index');

    // Jadwal Peminjaman Member
    Route::get('/schedules', [MemberScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/schedules/create', [MemberScheduleController::class, 'create'])->name('schedules.create');
    Route::post('/schedules', [MemberScheduleController::class, 'store'])->name('schedules.store');
    Route::get('/schedules/{schedule}', [MemberScheduleController::class, 'show'])->name('schedules.show');
    Route::post('/schedules/{schedule}/check-in', [MemberScheduleController::class, 'checkIn'])->name('schedules.check-in');
    Route::post('/schedules/{schedule}/check-out', [MemberScheduleController::class, 'checkOut'])->name('schedules.check-out');
});
