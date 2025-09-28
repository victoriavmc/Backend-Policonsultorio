<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultas', function (Blueprint $table) {
            $table->id('idConsultas');
            $table->string('rellenoReceta', 255)->nullable();
            $table->string('rellenoDiagnostico', 255)->nullable();
            $table->string('rellenoOrden', 255)->nullable();
            $table->string('rellenoHistorial', 255)->nullable();

            // Foráneas
            $table->foreignId('turnos_idTurnos')->constrained('turnos', 'idTurnos');
            $table->foreignId('diagnosticos_idDiagnosticos')->constrained('diagnosticos', 'idDiagnosticos');
            $table->foreignId('solicitudes_idSolicitudes')->constrained('solicitudes', 'idSolicitudes');
            $table->foreignId('seguimientoTratamiento_idSeguimientoTratamiento')->constrained('seguimientoTratamientos', 'idSeguimientoTratamiento');
            $table->foreignId('historialClinicos_idHistorialClinico')->constrained('historialClinicos', 'idHistorialClinico');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultas');
    }
};