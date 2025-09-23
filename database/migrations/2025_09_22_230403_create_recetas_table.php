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
        Schema::create('recetas', function (Blueprint $table) {
            $table->id('idRecetas');
            $table->string('nombreMedicamento', 45);
            $table->string('presentacion', 45);
            $table->string('concentracion', 45);
            $table->string('cantidad', 45);
            $table->date('fecha');
            $table->date('vigencia');
            $table->text('codigoRecetaElectronica');
            $table->string('observacion', 255);
            $table->unsignedBigInteger('indicaciones_idIndicaciones');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recetas');
    }
};
