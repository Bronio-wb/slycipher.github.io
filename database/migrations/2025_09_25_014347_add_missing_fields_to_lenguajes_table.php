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
        Schema::table('lenguajes', function (Blueprint $table) {
            $table->string('icono', 100)->nullable()->after('descripcion');
            $table->string('color', 7)->nullable()->after('icono');
            $table->string('version', 20)->nullable()->after('color');
            $table->enum('estado', ['activo', 'inactivo'])->default('activo')->after('version');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lenguajes', function (Blueprint $table) {
            $table->dropColumn(['icono', 'color', 'version', 'estado']);
        });
    }
};
