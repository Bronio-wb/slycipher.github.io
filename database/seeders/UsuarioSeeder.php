<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario Administrador
        User::create([
            'username' => 'admin',
            'nombre' => 'Administrador',
            'apellido' => 'Sistema',
            'name' => 'Admin Sistema',
            'email' => 'admin@slycipher.com',
            'password' => Hash::make('admin123'),
            'rol' => 'admin',
            'estado' => 'activo',
            'activo' => true,
            'racha' => 0,
        ]);

        // Usuario Desarrollador
        User::create([
            'username' => 'developer',
            'nombre' => 'Juan',
            'apellido' => 'Desarrollador',
            'name' => 'Juan Desarrollador',
            'email' => 'developer@slycipher.com',
            'password' => Hash::make('dev123'),
            'rol' => 'desarrollador',
            'estado' => 'activo',
            'activo' => true,
            'racha' => 5,
        ]);

        // Usuario Estudiante
        User::create([
            'username' => 'estudiante1',
            'nombre' => 'María',
            'apellido' => 'Estudiante',
            'name' => 'María Estudiante',
            'email' => 'estudiante@slycipher.com',
            'password' => Hash::make('est123'),
            'rol' => 'estudiante',
            'estado' => 'activo',
            'activo' => true,
            'racha' => 3,
        ]);

        // Más estudiantes
        User::create([
            'username' => 'estudiante2',
            'nombre' => 'Carlos',
            'apellido' => 'López',
            'name' => 'Carlos López',
            'email' => 'carlos@slycipher.com',
            'password' => Hash::make('carlos123'),
            'rol' => 'estudiante',
            'estado' => 'activo',
            'activo' => true,
            'racha' => 1,
        ]);
    }
}
