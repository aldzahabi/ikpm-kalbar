<?php

use App\Http\Controllers\Api\PembayaranController;
use App\Http\Controllers\Api\RombonganController;
use App\Http\Controllers\Api\SantriController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Mobile (Laravel Sanctum)
|--------------------------------------------------------------------------
| Buat token: $user->createToken('mobile')->plainTextToken
*/

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('/santris', [SantriController::class, 'index']);
    Route::get('/rombongans', [RombonganController::class, 'index']);
    Route::post('/pembayaran', [PembayaranController::class, 'store']);
});
