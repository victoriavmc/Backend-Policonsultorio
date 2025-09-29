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
        Schema::create('obrasSociales', function (Blueprint $table) {
            $table->id('idObrasSociales');
            $table->string('nombre', 45);
            $table->enum('estado', ['Activo', 'Inactivo', 'Suspendido', 'ObraSocialInactiva']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obrasSociales');
    }
};