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
        Schema::create('seguimientosPagos', function (Blueprint $table) {
            $table->id('idSeguimientosPagos');
            $table->enum('estado',['activo','finalizado']);
            $table->date('fecha');
            $table->string('modoPago',45);
            $table->string('obsevarcion',45)->nullable();
            $table->float('montoParcial')->nullable();
            $table->float('montoFinal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seguimientosPagos');
    }
};