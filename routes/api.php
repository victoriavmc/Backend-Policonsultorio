<?php

use App\Http\Controllers\AuditoriasController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImagenController;
use App\Http\Controllers\IndicacionController;
use App\Http\Controllers\RecetaController;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Ruta para solicitar pin de restablecimiento
Route::post('/forgot-password', [AuthController::class, 'sendPin']);

// Ruta para verificar pin (sin consumir)
Route::post('/verify-pin', [AuthController::class, 'verifyPin']);

// Ruta para restablecer contraseña con el pin
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('auditorias', AuditoriasController::class);
    Route::apiResource('indicaciones', IndicacionController::class);
    Route::apiResource('recetas', RecetaController::class);
    Route::apiResource('imagenes', ImagenController::class);
});
