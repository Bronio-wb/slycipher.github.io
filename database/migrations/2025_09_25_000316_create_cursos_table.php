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
        Schema::create('cursos', function (Blueprint $table) {
            $table->id('course_id');
            $table->string('titulo', 200);
            $table->text('descripcion')->nullable();
            $table->enum('nivel', ['principiante', 'intermedio', 'avanzado']);
            $table->unsignedBigInteger('language_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('creado_por');
            $table->timestamps();

            $table->foreign('language_id')->references('language_id')->on('lenguajes');
            $table->foreign('category_id')->references('category_id')->on('categorias');
            $table->foreign('creado_por')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
