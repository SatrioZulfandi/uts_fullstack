<?php

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

    // Logout: menghapus token yang sedang aktif
    Route::post('/logout', [AuthController::class, 'logout']);

    // Mengambil daftar inventaris yang berstatus 'available'
    Route::get('/inventories', [MemberController::class, 'availableInventories']);

    // Check-in peralatan secara real-time
    Route::post('/check-in', [MemberController::class, 'checkIn']);
});
