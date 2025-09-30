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
        Schema::create('auditorias', function (Blueprint $table) {
            $table->id('idAuditorias');
            $table->string('modulo', 45);
            $table->string('accion');
            $table->string('valorAnterior', 255)->nullable();
            $table->string('valorNuevo', 255)->nullable();
            $table->date('fecha');
            $table->integer('idReferente')->nullable();
            $table->foreignId('usuarios_idUsuarios')->constrained('usuarios','idUsuarios');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auditorias');
    }
};