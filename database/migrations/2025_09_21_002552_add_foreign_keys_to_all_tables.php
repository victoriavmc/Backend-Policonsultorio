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
        // FK: usuarios -> datos_personales
        Schema::table('usuarios', function (Blueprint $table) {
            $table->foreign('datosPersonales_idDatosPersonales', 'fk_usuarios_datosPersonales')
                ->references('idDatosPersonales')
                ->on('datosPersonales')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        // FK: auditorias -> usuarios  
        Schema::table('auditorias', function (Blueprint $table) {
            $table->foreign('usuarios_idUsuarios', 'fk_auditorias_usuarios')
                ->references('idUsuarios')
                ->on('usuarios')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('all_tables', function (Blueprint $table) {
            //
        });
    }
};
