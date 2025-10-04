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
        Schema::create('diagnosticos', function (Blueprint $table) {
            $table->id('idDiagnosticos');
            $table->date('fecha');
            $table->string('tipo',255)->nullable();
            $table->string('descripcion',255)->nullable();
            $table->string('observacion',255)->nullable();
            $table->string('clasificacion',255);
            $table->string('contenidoFormulario',255)->nullable();
            $table->foreignId('observacionesIA_idObservacionesIA')->constrained('observacionesIA','idObservacionesIA')->nullable();
            $table->foreignId('recetas_idRecetas')->constrained('recetas','idRecetas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnosticos');
    }
};