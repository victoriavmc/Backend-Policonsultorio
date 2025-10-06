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
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id('idPacientes');
            $table->boolean('particular');
            $table->string('numAfiliado', 45)->nullable();
            $table->foreignId('datosPersonales_idDatosPersonales')->constrained('datosPersonales','idDatosPersonales');

            
            $table->foreignId('obrasSociales_idObrasSociales')
            ->nullable()
            ->constrained('obrasSociales', 'idObrasSociales');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};