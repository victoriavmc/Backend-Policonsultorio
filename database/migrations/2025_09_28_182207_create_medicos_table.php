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
        Schema::create('medicos', function (Blueprint $table) {
            $table->id('idMedicos');
            $table->timestamps();
            $table->string('matricula', 45);
            $table->string('especialidad', 255);
            $table->string('consultorio', 45)->nullable();
            $table->string('firmaVirtual', 255)->nullable();
            $table->foreignId('usuarios_idUsuarios')->constrained('usuarios','idUsuarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicos');
    }
};