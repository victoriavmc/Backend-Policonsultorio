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
        Schema::create('horariosMedicos', function (Blueprint $table) {
            $table->id('idHorariosMedicos');
            $table->timestamps();
            $table->enum('disponible',['Disponible','No Disponible']);
            $table->foreignId('horarios_idHorarios')->constrained('horarios','idHorarios');
            $table->foreignId('medicos_idMedicos')->constrained('medicos','idMedicos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horariosMedicos');
    }
};