<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('agrupacionNoticiasImagenes', function (Blueprint $table) {
            $table->id('idAgrupacionNoticiasImagenes');
            $table->unsignedBigInteger('noticias_idNoticias');
            $table->unsignedBigInteger('imagenes_idImagenes');

            $table->foreign('noticias_idNoticias', 'fk_agrupacionNoticiasImagenes_noticias1')
                ->references('idNoticias')->on('noticias')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('imagenes_idImagenes', 'fk_agrupacionNoticiasImagenes_imagenes1')
                ->references('idImagenes')->on('imagenes')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('agrupacionNoticiasImagenes');
    }
};