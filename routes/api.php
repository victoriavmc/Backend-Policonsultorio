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

    // Auditorias (Medico solamente)
    Route::controller(AuditoriasController::class)
        ->middleware('role:medico')
        ->group(function () {
            Route::get('/auditorias', 'index');
            Route::post('/auditorias', 'store');
            Route::get('/auditorias/{id}', 'show');
            Route::put('/auditorias/{id}', 'update');
            Route::delete('/auditorias/{id}', 'destroy');
        });

    // Indicaciones (El medico puede crear, ver, actualizar y eliminar y el secretario solamente ver)
    Route::controller(IndicacionController::class)->group(function () {
        Route::get('/indicaciones', 'index')->middleware('role:medico,secretario');
        Route::post('/indicaciones', 'store')->middleware('role:medico');
        Route::get('/indicaciones/{id}', 'show')->middleware('role:medico,secretario');
        Route::put('/indicaciones/{id}', 'update')->middleware('role:medico');
        Route::delete('/indicaciones/{id}', 'destroy')->middleware('role:medico');
    });

    // Recetas (Medico puede crear, ver, actualizar y eliminar, el secretario no va a poder eliminar)
    Route::controller(RecetaController::class)->group(function () {
        Route::get('/recetas', 'index')->middleware('role:medico,secretario');
        Route::post('/recetas', 'store')->middleware('role:medico,secretario');
        Route::get('/recetas/{id}', 'show')->middleware('role:medico,secretario');
        Route::put('/recetas/{id}', 'update')->middleware('role:medico,secretario');
        Route::delete('/recetas/{id}', 'destroy')->middleware('role:medico');
    });

    // Imagenes (Medico, secretario y paciente pueden ver, crear, actualizar y eliminar)
    Route::controller(ImagenController::class)
        ->middleware('role:medico,secretario,paciente')
        ->group(function () {
            Route::get('/imagenes', 'index');
            Route::post('/imagenes', 'store');
            Route::get('/imagenes/{id}', 'show');
            Route::put('/imagenes/{id}', 'update');
            Route::delete('/imagenes/{id}', 'destroy');
        });

    // Solicitudes (Medico puede crear, ver, actualizar y eliminar, el secretario solamente puede ver)
    Route::controller(SolicitudesController::class)->group(function () {
        Route::get('/solicitudes', 'index')->middleware('role:medico,secretario');
        Route::post('/solicitudes', 'store')->middleware('role:medico');
        Route::get('/solicitudes/{id}', 'show')->middleware('role:medico,secretario');
        Route::put('/solicitudes/{id}', 'update')->middleware('role:medico');
        Route::delete('/solicitudes/{id}', 'destroy')->middleware('role:medico');
    });

    // Tipos Solicitudes (Medico puede crear, ver, actualizar y eliminar, el secretario no puede eliminar)
    Route::controller(TiposSolicitudesController::class)->group(function () {
        Route::get('/tipos-solicitudes', 'index')->middleware('role:medico,secretario');
        Route::post('/tipos-solicitudes', 'store')->middleware('role:medico,secretario');
        Route::get('/tipos-solicitudes/{id}', 'show')->middleware('role:medico,secretario');
        Route::put('/tipos-solicitudes/{id}', 'update')->middleware('role:medico,secretario');
        Route::delete('/tipos-solicitudes/{id}', 'destroy')->middleware('role:medico');
    });

    // Seguimiento Tratamiento (Medico puede crear, ver, actualizar y eliminar, el secretario solamente puede ver)
    Route::controller(SeguimientoTratamientoController::class)->group(function () {
        Route::get('/seguimientos-tratamientos', 'index')->middleware('role:medico,secretario');
        Route::post('/seguimientos-tratamientos', 'store')->middleware('role:medico');
        Route::get('/seguimientos-tratamientos/{id}', 'show')->middleware('role:medico,secretario');
        Route::put('/seguimientos-tratamientos/{id}', 'update')->middleware('role:medico');
        Route::delete('/seguimientos-tratamientos/{id}', 'destroy')->middleware('role:medico');
    });

    // Obras Sociales (Medico y secretario pueden ver, crear, actualizar, eliminar)
    Route::controller(ObraSocialController::class)
        ->middleware('role:medico,secretario')
        ->group(function () {
            Route::get('/obras-sociales/all', 'getAll');
            Route::get('/obras-sociales', 'index');
            Route::post('/obras-sociales', 'store');
            Route::get('/obras-sociales/{id}', 'show');
            Route::put('/obras-sociales/{id}', 'update');
            Route::delete('/obras-sociales/{id}', 'destroy');
        });

    // Pacientes (Medico y secretario pueden ver, crear, actualizar, eliminar, paciente solo puede ver EL SUYO)
    Route::controller(PacienteController::class)->group(function () {
        Route::get('/pacientes', 'index')->middleware('role:medico,secretario');
        Route::post('/pacientes', 'store')->middleware('role:medico,secretario');
        Route::get('/pacientes/{id}', 'show')->middleware('role:medico,secretario,paciente');
        Route::put('/pacientes/{id}', 'update')->middleware('role:medico,secretario');
        Route::delete('/pacientes/{id}', 'destroy')->middleware('role:medico,secretario');
    });

    // Formularios PDFs (Medico y secretario pueden ver, crear, actualizar, eliminar)
    Route::controller(FormularioPDFController::class)
        ->middleware('role:medico,secretario')
        ->group(function () {
            Route::get('/formularios-pdfs', 'index');
            Route::post('/formularios-pdfs', 'store');
            Route::get('/formularios-pdfs/{id}', 'show');
            Route::put('/formularios-pdfs/{id}', 'update');
            Route::delete('/formularios-pdfs/{id}', 'destroy');
        });

    // Horarios (Medico y secretario pueden ver, crear, actualizar, eliminar)
    Route::controller(HorarioController::class)
        ->middleware('role:medico,secretario')
        ->group(function () {
            Route::get('/horarios', 'index');
            Route::post('/horarios', 'store');
            Route::get('/horarios/{id}', 'show');
            Route::put('/horarios/{id}', 'update');
            Route::delete('/horarios/{id}', 'destroy');
        });

    // Medicos (Medico puede ver, crear, actualizar, eliminar, secretario y paciente solo pueden ver)
    Route::controller(MedicoController::class)->group(function () {
        Route::get('/medicos', 'index')->middleware('role:medico,secretario,paciente');
        Route::post('/medicos', 'store')->middleware('role:medico');
        Route::get('/medicos/{id}', 'show')->middleware('role:medico,secretario,paciente');
        Route::put('/medicos/{id}', 'update')->middleware('role:medico');
        Route::delete('/medicos/{id}', 'destroy')->middleware('role:medico');
    });

    // HorariosMedicos (Medico y secretario pueden ver, crear, actualizar, eliminar)
    Route::controller(HorariosMedicoController::class)
        ->middleware('role:medico,secretario')
        ->group(function () {
            Route::get('/horariosMedicos', 'index');
            Route::post('/horariosMedicos', 'store');
            Route::get('/horariosMedicos/{id}', 'show');
            Route::put('/horariosMedicos/{id}', 'update');
            Route::delete('/horariosMedicos/{id}', 'destroy');
        });

    // Seguimiento Pago (Secretario: puede crear, actualizar, modificar y eliminar. Medico: ver y eliminar)
    Route::controller(SeguimientosPagoController::class)->group(function () {
        Route::get('/seguimientosPagos', 'index')->middleware('role:secretario,medico');
        Route::post('/seguimientosPagos', 'store')->middleware('role:secretario');
        Route::get('/seguimientosPagos/{id}', 'show')->middleware('role:secretario,medico');
        Route::put('/seguimientosPagos/{id}', 'update')->middleware('role:secretario');
        Route::delete('/seguimientosPagos/{id}', 'destroy')->middleware('role:secretario,medico');
    });

    // Tratamientos (Medico y secretario pueden crear, actualizar, modificar y eliminar, paciente solamente puede ver EL SUYO)
    Route::controller(TratamientoController::class)->group(function () {
        Route::get('/tratamientos', 'index')->middleware('role:medico,secretario');
        Route::post('/tratamientos', 'store')->middleware('role:medico,secretario');
        Route::get('/tratamientos/{id}', 'show')->middleware('role:medico,secretario,paciente');
        Route::put('/tratamientos/{id}', 'update')->middleware('role:medico,secretario');
        Route::delete('/tratamientos/{id}', 'destroy')->middleware('role:medico,secretario');
    });

    // Turnos (Medico y secretario pueden ver, crear, actualizar, eliminar)
    Route::controller(TurnoController::class)
        ->middleware('role:medico,secretario')
        ->group(function () {
            Route::get('/turnos', 'index');
            Route::post('/turnos', 'store');
            Route::get('/turnos/{id}', 'show');
            Route::put('/turnos/{id}', 'update');
            Route::delete('/turnos/{id}', 'destroy');
        });

    // Diagnosticos (Medico pueden crear, ver, actualizar y eliminar, el secretario solamente puede ver)
    Route::controller(DiagnosticosController::class)->group(function () {
        Route::get('/diagnosticos', 'index')->middleware('role:medico,secretario');
        Route::post('/diagnosticos', 'store')->middleware('role:medico');
        Route::get('/diagnosticos/{id}', 'show')->middleware('role:medico,secretario');
        Route::put('/diagnosticos/{id}', 'update')->middleware('role:medico');
        Route::delete('/diagnosticos/{id}', 'destroy')->middleware('role:medico');
    });

    // HistorialClinico (Medico y secretario pueden ver, crear, actualizar, eliminar)
    Route::controller(HistorialClinicoController::class)
        ->middleware('role:medico,secretario')
        ->group(function () {
            Route::get('/historialClinicos', 'index');
            Route::post('/historialClinicos', 'store');
            Route::get('/historialClinicos/{id}', 'show');
            Route::put('/historialClinicos/{id}', 'update');
            Route::delete('/historialClinicos/{id}', 'destroy');
        });

    // Noticias (Medico y secretario pueden crear, actualizar, modificar y eliminar, paciente solo puede ver)
    Route::controller(NoticiaController::class)->group(function () {
        Route::get('/noticias', 'index')->middleware('role:medico,secretario,pacientes');
        Route::post('/noticias', 'store')->middleware('role:medico,secretario');
        Route::get('/noticias/{id}', 'show')->middleware('role:medico,secretario,pacientes');
        Route::put('/noticias/{id}', 'update')->middleware('role:medico,secretario');
        Route::delete('/noticias/{id}', 'destroy')->middleware('role:medico,secretario');
    });

    // Consultas (Medico y secretario pueden ver, crear, actualizar, eliminar)
    Route::controller(ConsultaController::class)
        ->middleware('role:medico,secretario')
        ->group(function () {
            Route::get('/consultas', 'index');
            Route::post('/consultas', 'store');
            Route::get('/consultas/{id}', 'show');
            Route::put('/consultas/{id}', 'update');
            Route::delete('/consultas/{id}', 'destroy');
        });
});
