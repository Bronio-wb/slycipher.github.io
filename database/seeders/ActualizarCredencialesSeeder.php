<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ActualizarCredencialesSeeder extends Seeder
{
    public function run()
    {
        // Actualizar contraseña del administrador
        DB::table('users')
            ->where('email', 'admin@slycipher.com')
            ->update([
                'password' => Hash::make('Adminsly123!'),
                'updated_at' => now()
            ]);

        // Actualizar contraseña del estudiante
        DB::table('users')
            ->where('email', 'estudiante@slycipher.com')
            ->update([
                'password' => Hash::make('Estsly123!'),
                'updated_at' => now()
            ]);

        // Crear usuario desarrollador de contenido
        $desarrolladorId = DB::table('users')->where('email', 'dev@slycipher.com')->value('id');
        
        if (!$desarrolladorId) {
            $desarrolladorId = DB::table('users')->insertGetId([
                'username' => 'dev_content',
                'nombre' => 'Desarrollador',
                'apellido' => 'Contenido',
                'name' => 'Desarrollador de Contenido',
                'email' => 'dev@slycipher.com',
                'password' => Hash::make('Devsly123!'),
                'rol' => 'desarrollador',
                'estado' => 'activo',
                'activo' => 1,
                'racha' => 0,
                'puntos_totales' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            // Si ya existe, solo actualizar la contraseña
            DB::table('users')
                ->where('email', 'dev@slycipher.com')
                ->update([
                    'password' => Hash::make('Devsly123!'),
                    'updated_at' => now()
                ]);
        }

        $this->command->info('=== CREDENCIALES ACTUALIZADAS ===');
        $this->command->info('👨‍💼 Admin: admin@slycipher.com / Adminsly123!');
        $this->command->info('🎓 Estudiante: estudiante@slycipher.com / Estsly123!');
        $this->command->info('👨‍💻 Desarrollador: dev@slycipher.com / Devsly123!');
        $this->command->info('================================');
    }
}