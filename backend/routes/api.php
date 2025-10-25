<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TravelRequestController;
use Illuminate\Http\Request;


// Rotas públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rotas protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Deslogado com sucesso.']);
    });

    Route::prefix('travel-requests')->group(function () {
        Route::get('/', [TravelRequestController::class, 'index'])->middleware('role:user,admin');
        Route::post('/', [TravelRequestController::class, 'store'])->middleware('role:user,admin');
        Route::patch('/{id}/status', [TravelRequestController::class, 'updateStatus'])->middleware('role:admin');
    });
});