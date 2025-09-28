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
        Schema::create('imagenesIA', function (Blueprint $table) {
            $table->id('idImagenesIA');
            $table->string('descripcionIA', 255)->nullable();
            $table->string('observacionMedica', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imagenesIA');
    }
};