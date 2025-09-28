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
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id('idpacientes');
            $table->tinyInteger('particular');
            $table->string('numAfiliado', 45)->nullable();
            $table->unsignedBigInteger('datosPersonales_iddatosPersonales');
            $table->unsignedBigInteger('obrasSociales_idobrasSociales');

            $table->foreign('datosPersonales_iddatosPersonales', 'fk_pacientes_datosPersonales1')
                  ->references('idDatosPersonales')->on('datos_personales')
                  ->onDelete('no action')
                  ->onUpdate('no action');

            $table->foreign('obrasSociales_idobrasSociales', 'fk_pacientes_obrasSociales1')
                  ->references('idobrasSociales')->on('obrasSociales')
                  ->onDelete('no action')
                  ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};