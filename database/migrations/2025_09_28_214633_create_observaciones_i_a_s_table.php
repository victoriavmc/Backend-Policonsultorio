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
        Schema::create('observacionesIA', function (Blueprint $table) {
            $table->id('idObservacionesIA');
            $table->foreignId('imagenesIA_idImagenesIA')->constrained('imagenesIA','idImagenesIA');
            $table->foreignId('imagenes_idImagenes')->constrained('imagenes','idImagenes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('observacionesIA');
    }
};