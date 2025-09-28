<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('turnos', function (Blueprint $table) {
             $table->id('idTurnos');
            $table->date('fecha');
            $table->time('hora');
            $table->boolean('particular'); 
            $table->string('nombreObraSocial',45)->nullable();
            $table->enum('prioridad',['Con turno', 'Sin Turno']);
            $table->enum('estado',['Pendiente', 'Confirmado', 'En espera', 'En consulta', 'Finalizado', 'Cancelado', 'En espera sin Turno']);
            $table->time('duracion')->nullable();
            $table->time('horaLlegada')->nullable();
            $table->time('horaSalida')->nullable();
            $table->foreignId('pacientes_idPacientes')->constrained('pacientes','idPacientes');
            $table->foreignId('medicos_idMedicos')->constrained('medicos','idMedicos');
            $table->foreignId('tratamientos_idTratamientos')->constrained('tratamientos','idTratamientos');
            $table->foreignId('seguimientosPagos_idSeguimientosPagos')->constrained('seguimientosPagos','idSeguimientosPagos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turnos');
    }
};