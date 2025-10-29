<?php

use App\Http\Controllers\AuditoriasController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsultaController;
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
use App\Http\Controllers\SolicitudesController;
use App\Http\Controllers\SeguimientoTratamientoController;
use App\Http\Controllers\TiposSolicitudesController;

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

    // Auditorias
    Route::controller(AuditoriasController::class)->group(function () {
        Route::get('/auditorias', 'index');
        Route::post('/auditorias', 'store');
        Route::get('/auditorias/{id}', 'show');
        Route::put('/auditorias/{id}', 'update');
        Route::delete('/auditorias/{id}', 'destroy');
    });

    // Indicaciones
    Route::controller(IndicacionController::class)->group(function () {
        Route::get('/indicaciones', 'index');
        Route::post('/indicaciones', 'store');
        Route::get('/indicaciones/{id}', 'show');
        Route::put('/indicaciones/{id}', 'update');
        Route::delete('/indicaciones/{id}', 'destroy');
    });

    // Recetas
    Route::controller(RecetaController::class)->group(function () {
        Route::get('/recetas', 'index');
        Route::post('/recetas', 'store');
        Route::get('/recetas/{id}', 'show');
        Route::put('/recetas/{id}', 'update');
        Route::delete('/recetas/{id}', 'destroy');
    });

    // Imagenes
    Route::controller(ImagenController::class)->group(function () {
        Route::get('/imagenes', 'index');
        Route::post('/imagenes', 'store');
        Route::get('/imagenes/{id}', 'show');
        Route::put('/imagenes/{id}', 'update');
        Route::delete('/imagenes/{id}', 'destroy');
    });

    // Solicitudes
    Route::controller(SolicitudesController::class)->group(function () {
        Route::get('/solicitudes', 'index');
        Route::post('/solicitudes', 'store');
        Route::get('/solicitudes/{id}', 'show');
        Route::put('/solicitudes/{id}', 'update');
        Route::delete('/solicitudes/{id}', 'destroy');
    });

    // Tipos Solicitudes
    Route::controller(TiposSolicitudesController::class)->group(function () {
        Route::get('/tipos-solicitudes', 'index');
        Route::post('/tipos-solicitudes', 'store');
        Route::get('/tipos-solicitudes/{id}', 'show');
        Route::put('/tipos-solicitudes/{id}', 'update');
        Route::delete('/tipos-solicitudes/{id}', 'destroy');
    });

    // Seguimiento Tratamiento
    Route::controller(SeguimientoTratamientoController::class)->group(function () {
        Route::get('/seguimientos-tratamientos', 'index');
        Route::post('/seguimientos-tratamientos', 'store');
        Route::get('/seguimientos-tratamientos/{id}', 'show');
        Route::put('/seguimientos-tratamientos/{id}', 'update');
        Route::delete('/seguimientos-tratamientos/{id}', 'destroy');
    });

    // Obras Sociales
    Route::controller(ObraSocialController::class)->group(function () {
        Route::get('/obras-sociales/all', 'getAll');
        Route::get('/obras-sociales', 'index');
        Route::post('/obras-sociales', 'store');
        Route::get('/obras-sociales/{id}', 'show');
        Route::put('/obras-sociales/{id}', 'update');
        Route::delete('/obras-sociales/{id}', 'destroy');
    });

    // Pacientes
    Route::controller(PacienteController::class)->group(function () {
        Route::get('/pacientes', 'index');
        Route::post('/pacientes', 'store');
        Route::get('/pacientes/{id}', 'show');
        Route::put('/pacientes/{id}', 'update');
        Route::delete('/pacientes/{id}', 'destroy');
    });

    // Formularios PDFs
    Route::controller(FormularioPDFController::class)->group(function () {
        Route::get('/formularios-pdfs', 'index');
        Route::post('/formularios-pdfs', 'store');
        Route::get('/formularios-pdfs/{id}', 'show');
        Route::put('/formularios-pdfs/{id}', 'update');
        Route::delete('/formularios-pdfs/{id}', 'destroy');
    });

    // Horarios
    Route::controller(HorarioController::class)->group(function () {
        Route::get('/horarios', 'index');
        Route::post('/horarios', 'store');
        Route::get('/horarios/{id}', 'show');
        Route::put('/horarios/{id}', 'update');
        Route::delete('/horarios/{id}', 'destroy');
    });

    // Medicos
    Route::controller(MedicoController::class)->group(function () {
        Route::get('/medicos', 'index');
        Route::post('/medicos', 'store');
        Route::get('/medicos/{id}', 'show');
        Route::put('/medicos/{id}', 'update');
        Route::delete('/medicos/{id}', 'destroy');
    });

    // HorariosMedicos
    Route::controller(HorariosMedicoController::class)->group(function () {
        Route::get('/horariosMedicos', 'index');
        Route::post('/horariosMedicos', 'store');
        Route::get('/horariosMedicos/{id}', 'show');
        Route::put('/horariosMedicos/{id}', 'update');
        Route::delete('/horariosMedicos/{id}', 'destroy');
    });

    // Seguimiento Pago
    Route::controller(SeguimientosPagoController::class)->group(function () {
        Route::get('/seguimientosPagos', 'index');
        Route::post('/seguimientosPagos', 'store');
        Route::get('/seguimientosPagos/{id}', 'show');
        Route::put('/seguimientosPagos/{id}', 'update');
        Route::delete('/seguimientosPagos/{id}', 'destroy');
    });

    // Tratamientos
    Route::controller(TratamientoController::class)->group(function () {
        Route::get('/tratamientos', 'index');
        Route::post('/tratamientos', 'store');
        Route::get('/tratamientos/{id}', 'show');
        Route::put('/tratamientos/{id}', 'update');
        Route::delete('/tratamientos/{id}', 'destroy');
    });

    // Turnos 
    Route::controller(TurnoController::class)->group(function () {
        Route::get('/turnos', 'index');
        Route::post('/turnos', 'store');
        Route::get('/turnos/{id}', 'show');
        Route::put('/turnos/{id}', 'update');
        Route::delete('/turnos/{id}', 'destroy');
    });

    // Diagnosticos
    Route::controller(DiagnosticosController::class)->group(function () {
        Route::get('/diagnosticos', 'index');
        Route::post('/diagnosticos', 'store');
        Route::get('/diagnosticos/{id}', 'show');
        Route::put('/diagnosticos/{id}', 'update');
        Route::delete('/diagnosticos/{id}', 'destroy');
    });

    // HistorialClinico
    Route::controller(HistorialClinicoController::class)->group(function () {
        Route::get('/historialClinicos', 'index');
        Route::post('/historialClinicos', 'store');
        Route::get('/historialClinicos/{id}', 'show');
        Route::put('/historialClinicos/{id}', 'update');
        Route::delete('/historialClinicos/{id}', 'destroy');
    });

    // Noticias
    Route::controller(NoticiaController::class)->group(function () {
        Route::get('/noticias', 'index');
        Route::post('/noticias', 'store');
        Route::get('/noticias/{id}', 'show');
        Route::put('/noticias/{id}', 'update');
        Route::delete('/noticias/{id}', 'destroy');
    });

    // Consultas
    Route::controller(ConsultaController::class)->group(function () {
        Route::get('/consultas', 'index');
        Route::post('/consultas', 'store');
        Route::get('/consultas/{id}', 'show');
        Route::put('/consultas/{id}', 'update');
        Route::delete('/consultas/{id}', 'destroy');
    });
});
