<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndicacionesController;
use App\Http\Controllers\RecetasController;
use App\Http\Controllers\FormularioPDFController;
use App\Http\Controllers\ObraSocialController;
use App\Http\Controllers\PacienteController;
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
    Route::resource('obras-sociales', ObraSocialController::class);
    Route::resource('pacientes', PacienteController::class);
    Route::resource('formularios-pdfs', FormularioPDFController::class);
});
