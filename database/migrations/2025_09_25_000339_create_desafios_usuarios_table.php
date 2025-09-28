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
        Schema::create('desafios_usuarios', function (Blueprint $table) {
            $table->id('user_challenge_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('challenge_id');
            $table->enum('estado', ['pendiente', 'completado', 'fallido'])->default('pendiente');
            $table->text('envio')->nullable();
            $table->timestamp('completado_en')->nullable();
            $table->integer('puntaje')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('challenge_id')->references('challenge_id')->on('desafios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desafios_usuarios');
    }
};
