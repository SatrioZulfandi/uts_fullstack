<?php

use App\Http\Controllers\Api\Admin\InventoryController;
use App\Http\Controllers\Api\Admin\ScheduleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MemberController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Endpoint REST API untuk aplikasi tablet (Member Area).
| Menggunakan Laravel Sanctum untuk autentikasi token.
|--------------------------------------------------------------------------
*/

// Endpoint publik: Login untuk mendapatkan Bearer Token
Route::post('/login', [AuthController::class, 'login']);

// Endpoint yang memerlukan autentikasi token Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // Member Authentication
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Member API
    Route::get('/inventories', [MemberController::class, 'availableInventories']);
    Route::get('/my-schedules', [MemberController::class, 'schedules']);
    Route::get('/my-schedules/{schedule}', [MemberController::class, 'showSchedule']);
    Route::post('/check-in', [MemberController::class, 'checkIn']);

    // Admin API
    Route::prefix('admin')->middleware('admin')->group(function () {
        // Inventories
        Route::get('/inventories', [InventoryController::class, 'index']);
        Route::post('/inventories', [InventoryController::class, 'store']);
        Route::delete('/inventories/{inventory}', [InventoryController::class, 'destroy']);

        // Members
        Route::get('/members', [App\Http\Controllers\Api\Admin\MemberController::class, 'index']);

        // Schedules
        Route::get('/schedules', [ScheduleController::class, 'index']);
        Route::post('/schedules', [ScheduleController::class, 'store']);
        Route::get('/schedules/{schedule}', [ScheduleController::class, 'show']);
        Route::patch('/schedules/{schedule}', [ScheduleController::class, 'update']);
    });
});
