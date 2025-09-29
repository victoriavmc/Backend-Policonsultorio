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
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id('idsolicitudes');
            $table->string('descripcion', 255)->nullable();
            $table->date('fecha');
            $table->unsignedBigInteger('tiposSolicitudes_idtiposSolicitudes');

            $table->foreign('tiposSolicitudes_idtiposSolicitudes', 'fk_solicitudes_tiposSolicitudes1')
                  ->references('idtiposSolicitudes')->on('tiposSolicitudes')
                  ->onDelete('no action')
                  ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};