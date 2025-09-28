<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstudiantePruebaSeeder extends Seeder
{
    public function run()
    {
        // Crear un estudiante de prueba
        $estudianteId = DB::table('users')->where('email', 'estudiante@slycipher.com')->value('id');
        
        if (!$estudianteId) {
            $estudianteId = DB::table('users')->insertGetId([
                'username' => 'estudiante_test',
                'nombre' => 'Estudiante',
                'apellido' => 'Prueba',
                'name' => 'Estudiante Prueba',
                'email' => 'estudiante@slycipher.com',
                'password' => bcrypt('password'),
                'rol' => 'estudiante',
                'estado' => 'activo',
                'activo' => 1,
                'racha' => 0,
                'puntos_totales' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $this->command->info('Usuario estudiante creado: estudiante@slycipher.com / password');
    }
}