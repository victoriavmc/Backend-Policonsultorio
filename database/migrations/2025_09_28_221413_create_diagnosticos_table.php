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
            $table->unsignedBigInteger('observacionesIA_idObservacionesIA')->nullable();
            $table->unsignedBigInteger('recetas_idRecetas')->nullable();

            // Luego las foreign keys aparte
            $table->foreign('observacionesIA_idObservacionesIA')
                ->references('idObservacionesIA')
                ->on('observacionesIA')
                ->onDelete('set null');

            $table->foreign('recetas_idRecetas')
                ->references('idRecetas')
                ->on('recetas')
                ->onDelete('set null');
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