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
        Schema::create('agrupacionNoticiasImagenes', function (Blueprint $table) {
            $table->id('idagrupacionNoticiasImagenes');
            $table->unsignedBigInteger('noticias_idnoticias');
            $table->unsignedBigInteger('imagenes_idimagenes');

            $table->foreign('noticias_idnoticias', 'fk_agrupacionNoticiasImagenes_noticias1')
                  ->references('idnoticias')->on('noticias')
                  ->onDelete('no action')
                  ->onUpdate('no action');

            $table->foreign('imagenes_idimagenes', 'fk_agrupacionNoticiasImagenes_imagenes1')
                  ->references('idimagenes')->on('imagenes')
                  ->onDelete('no action')
                  ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agrupacionNoticiasImagenes');
    }
};