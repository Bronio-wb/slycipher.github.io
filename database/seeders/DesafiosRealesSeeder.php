<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DesafiosRealesSeeder extends Seeder
{
    public function run()
    {
        // Primero crear un lenguaje Python si no existe
        $pythonId = DB::table('lenguajes')->where('nombre', 'Python')->value('language_id');
        
        if (!$pythonId) {
            $pythonId = DB::table('lenguajes')->insertGetId([
                'nombre' => 'Python',
                'descripcion' => 'Lenguaje de programación Python',
                'icono' => 'fab fa-python',
                'color' => '#3776ab',
                'version' => '3.9',
                'estado' => 'activo',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Crear una categoría si no existe
        $categoriaId = DB::table('categorias')->where('nombre', 'Programación Básica')->value('category_id');
        
        if (!$categoriaId) {
            $categoriaId = DB::table('categorias')->insertGetId([
                'nombre' => 'Programación Básica',
                'descripcion' => 'Conceptos fundamentales de programación',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Crear un usuario administrador temporal si no existe
        $adminId = DB::table('users')->where('email', 'admin@slycipher.com')->value('id');
        
        if (!$adminId) {
            $adminId = DB::table('users')->insertGetId([
                'username' => 'admin_slycipher',
                'nombre' => 'Administrador',
                'apellido' => 'Sistema',
                'name' => 'Administrador Sistema',
                'email' => 'admin@slycipher.com',
                'password' => bcrypt('password'),
                'rol' => 'admin',
                'estado' => 'activo',
                'activo' => 1,
                'racha' => 0,
                'puntos_totales' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Crear un curso básico
        $cursoId = DB::table('cursos')->where('titulo', 'Desafíos de Programación')->value('course_id');
        
        if (!$cursoId) {
            $cursoId = DB::table('cursos')->insertGetId([
                'titulo' => 'Desafíos de Programación',
                'descripcion' => 'Colección de desafíos para practicar programación',
                'nivel' => 'principiante',
                'language_id' => $pythonId,
                'category_id' => $categoriaId,
                'creado_por' => $adminId,
                'estado' => 'activo',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Crear desafíos con la estructura correcta
        $desafios = [
            [
                'course_id' => $cursoId,
                'language_id' => $pythonId,
                'titulo' => 'Hola Mundo',
                'descripcion' => 'Escribe un programa que imprima "Hola Mundo" en la consola. Este es tu primer desafío de programación.',
                'dificultad' => 'facil',
                'solucion' => "print('Hola Mundo')",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'course_id' => $cursoId,
                'language_id' => $pythonId,
                'titulo' => 'Suma de Dos Números',
                'descripcion' => 'Crea un programa que sume los números 5 y 3, y muestre el resultado.',
                'dificultad' => 'facil',
                'solucion' => "numero1 = 5\nnumero2 = 3\nresultado = numero1 + numero2\nprint(resultado)",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'course_id' => $cursoId,
                'language_id' => $pythonId,
                'titulo' => 'Números Pares',
                'descripcion' => 'Escribe un programa que imprima todos los números pares del 1 al 10.',
                'dificultad' => 'medio',
                'solucion' => "for i in range(1, 11):\n    if i % 2 == 0:\n        print(i)",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'course_id' => $cursoId,
                'language_id' => $pythonId,
                'titulo' => 'Calculadora Simple',
                'descripcion' => 'Crea una función que sume dos números y devuelva el resultado.',
                'dificultad' => 'medio',
                'solucion' => "def sumar(a, b):\n    return a + b\n\nresultado = sumar(10, 5)\nprint(resultado)",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'course_id' => $cursoId,
                'language_id' => $pythonId,
                'titulo' => 'Factorial',
                'descripcion' => 'Implementa una función que calcule el factorial de un número.',
                'dificultad' => 'dificil',
                'solucion' => "def factorial(n):\n    if n == 0 or n == 1:\n        return 1\n    else:\n        return n * factorial(n - 1)\n\nprint(factorial(5))",
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($desafios as $desafio) {
            // Solo insertar si no existe ya
            $existe = DB::table('desafios')->where('titulo', $desafio['titulo'])->exists();
            if (!$existe) {
                DB::table('desafios')->insert($desafio);
            }
        }

        $this->command->info('Desafíos de práctica creados exitosamente!');
        $this->command->info('Usuario administrador: admin@slycipher.com / password');
    }
}