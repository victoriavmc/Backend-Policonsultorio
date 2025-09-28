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
        Schema::create('historialClinicos', function (Blueprint $table) {
            $table->id('idHistorialClinico');
            $table->string('descripcion',255)->nullable();
            $table->string('causaConsulta',255)->nullable();
            $table->date('fecha')->nullable();
            $table->string('contenidoFormulario',255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historialClinicos');
    }
};