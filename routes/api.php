<?php

use App\Http\Controllers\AuditoriasController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DatoPersonalController;
use App\Http\Controllers\DiagnosticosController;
use App\Http\Controllers\ImagenController;
use App\Http\Controllers\IndicacionController;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\FormularioPDFController;
use App\Http\Controllers\HistorialClinicoController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\HorariosMedicoController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\ObraSocialController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SeguimientosPagoController;
use App\Http\Controllers\TratamientoController;
use App\Http\Controllers\TurnoController;
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

Route::apiResource('datos-personales', DatoPersonalController::class)
    ->parameters(['datos-personales' => 'id']);
Route::apiResource('usuarios', UserController::class)->except(['store', 'destroy', 'update']);
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('auditorias', AuditoriasController::class);
    Route::apiResource('indicaciones', IndicacionController::class);
    Route::apiResource('recetas', RecetaController::class);
    Route::apiResource('imagenes', ImagenController::class);
    
});
// Obras Sociales
Route::apiResource('obras-sociales', ObraSocialController::class);
// Pacientes
Route::apiResource('pacientes', PacienteController::class);
// Formularios PDFs
Route::apiResource('formularios-pdfs', FormularioPDFController::class);
// Horarios
Route::apiResource('horarios', HorarioController::class);

// Medicos
Route::apiResource('medicos', MedicoController::class);

// HorariosMedicos
Route::apiResource('horariosMedicos', HorariosMedicoController::class);

// Seguimiento Pago
Route::apiResource('seguimientosPagos', SeguimientosPagoController::class);

// Tratamientos
Route::apiResource('tratamientos', TratamientoController::class);

// Turnos 
Route::apiResource('turnos', TurnoController::class);

// Diagnosticos
Route::apiResource('diagnosticos', DiagnosticosController::class);

// HistorialClinico
Route::apiResource('historialClinico', HistorialClinicoController::class);

// Noticias
Route::apiResource('noticia', NoticiaController::class);
