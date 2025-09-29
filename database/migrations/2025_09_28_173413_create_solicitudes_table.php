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
            $table->id('idSolicitudes');
            $table->string('descripcion', 255)->nullable();
            $table->date('fecha');
            $table->unsignedBigInteger('tiposSolicitudes_idTiposSolicitudes');

            $table->foreign('tiposSolicitudes_idTiposSolicitudes', 'fk_solicitudes_tiposSolicitudes1')
                  ->references('idTiposSolicitudes')->on('tiposSolicitudes')
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