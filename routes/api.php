<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndicacionesController;
use App\Http\Controllers\RecetasController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    // Ruta para solicitar pin de restablecimiento
    Route::post('/forgot', [AuthController::class, 'sendPin']);

    // Ruta para verificar pin (sin consumir)
    Route::post('/verify-pin', [AuthController::class, 'verifyPin']);

    // Ruta para restablecer contraseña con el pin
    Route::post('/reset', [AuthController::class, 'resetPassword']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('indicaciones', IndicacionesController::class);

    Route::resource('recetas', RecetasController::class);
});
