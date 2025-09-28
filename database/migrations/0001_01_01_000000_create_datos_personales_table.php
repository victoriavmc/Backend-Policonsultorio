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
        Schema::create('datosPersonales', function (Blueprint $table) {
            $table->id('idDatosPersonales');
            $table->string('nombre', 55);
            $table->string('apellido', 55);
            $table->string('documento', 55);
            $table->string('tipoDocumento', 55);
            $table->string('genero', 55);
            $table->date('fechaNacimiento');
            $table->string('celular', 55);
            $table->string('estado', 55);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datosPersonales');
    }
};
