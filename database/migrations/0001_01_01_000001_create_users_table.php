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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('idUsuarios');
            $table->string('email', 45)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255);

            $table->string('pinOlvido', 255)->nullable();
            $table->timestamp('pinOlvido_expires_at')->nullable();
            $table->timestamp('pinOlvido_created_at')->nullable();
            $table->integer('pin_attempts')->default(0);
            $table->timestamp('pin_blocked_until')->nullable();

            $table->string('rol', 45);
            $table->string('estado', 55);
            $table->unsignedBigInteger('datosPersonales_idDatosPersonales')->nullable();
            $table->rememberToken();
            
            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('sessions');
    }
};
