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
        Schema::create('desafios', function (Blueprint $table) {
            $table->id('challenge_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('language_id');
            $table->string('titulo', 200);
            $table->text('descripcion');
            $table->enum('dificultad', ['facil', 'medio', 'dificil']);
            $table->longText('solucion');
            $table->timestamps();

            $table->foreign('course_id')->references('course_id')->on('cursos');
            $table->foreign('language_id')->references('language_id')->on('lenguajes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desafios');
    }
};
