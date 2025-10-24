<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TravelRequestController;


// Rotas públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rotas protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('travel-requests')->group(function () {
        Route::get('/', [TravelRequestController::class, 'index'])->middleware('role:user,admin');
        Route::post('/', [TravelRequestController::class, 'store'])->middleware('role:user,admin');
        Route::patch('/{id}/status', [TravelRequestController::class, 'updateStatus'])->middleware('role:admin');
    });
});