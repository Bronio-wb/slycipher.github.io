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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 50)->unique()->after('id');
            $table->string('nombre', 100)->after('username');
            $table->string('apellido', 100)->after('nombre');
            $table->enum('rol', ['admin', 'estudiante', 'desarrollador'])->default('estudiante')->after('password');
            $table->timestamp('ultimo_login')->nullable()->after('email_verified_at');
            $table->enum('estado', ['activo', 'inactivo'])->default('activo')->after('ultimo_login');
            $table->boolean('activo')->default(true)->after('estado');
            $table->integer('racha')->default(0)->after('activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'nombre', 'apellido', 'rol', 'ultimo_login', 'estado', 'activo', 'racha']);
        });
    }
};
