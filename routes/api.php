<?php

use App\Http\Controllers\AuditoriasController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImagenController;
use App\Http\Controllers\IndicacionController;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\FormularioPDFController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\ObraSocialController;
use App\Http\Controllers\PacienteController;
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
Route::apiResource('imagenes', ImagenController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('auditorias', AuditoriasController::class);
    Route::apiResource('indicaciones', IndicacionController::class);
    Route::apiResource('recetas', RecetaController::class);
    Route::resource('obras-sociales', ObraSocialController::class);
    Route::resource('pacientes', PacienteController::class);
    Route::resource('formularios-pdfs', FormularioPDFController::class);
});

//Horarios
Route::apiResource('horarios', HorarioController::class);