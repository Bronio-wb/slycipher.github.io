<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Desafio;
use App\Models\Curso;

class DesafiosPracticaSeeder extends Seeder
{
    public function run()
    {
        // Crear lenguaje si no existe
        $lenguaje = DB::table('lenguajes')->insertGetId([
            'nombre' => 'Python',
            'descripcion' => 'Lenguaje de programación Python',
            'icono' => 'fab fa-python',
            'color' => '#3776ab',
            'version' => '3.9',
            'estado' => 'activo',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Crear categoría si no existe
        $categoria = DB::table('categorias')->insertGetId([
            'nombre' => 'Programación Básica',
            'descripcion' => 'Conceptos fundamentales de programación',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Buscar o crear un curso básico usando los IDs correctos
        $curso = Curso::firstOrCreate([
            'titulo' => 'Programación Básica'
        ], [
            'descripcion' => 'Curso introductorio de programación',
            'nivel' => 'principiante',
            'language_id' => $lenguaje,
            'category_id' => $categoria,
            'creado_por' => 1, // Asume que existe un usuario admin con ID 1
            'estado' => 'activo'
        ]);

        // Crear desafíos de práctica
        $desafios = [
            [
                'titulo' => 'Hola Mundo',
                'descripcion' => 'Escribe un programa que imprima "Hola Mundo" en la consola. Este es tu primer desafío de programación.',
                'dificultad' => 'facil',
                'puntos' => 10,
                'orden' => 1,
                'codigo_inicial' => "# Escribe tu código aquí\nprint('Hola Mundo')",
                'ejemplo_entrada' => '',
                'ejemplo_salida' => 'Hola Mundo',
                'pistas' => 'Usa la función print() para mostrar texto en pantalla.',
                'activo' => true
            ],
            [
                'titulo' => 'Suma de Dos Números',
                'descripcion' => 'Crea un programa que sume dos números y muestre el resultado. Los números son 5 y 3.',
                'dificultad' => 'facil',
                'puntos' => 15,
                'orden' => 2,
                'codigo_inicial' => "# Suma dos números\nnumero1 = 5\nnumero2 = 3\n# Tu código aquí",
                'ejemplo_entrada' => '5, 3',
                'ejemplo_salida' => '8',
                'pistas' => 'Usa el operador + para sumar los números y print() para mostrar el resultado.',
                'activo' => true
            ],
            [
                'titulo' => 'Calculadora Básica',
                'descripcion' => 'Crea una calculadora que pueda sumar, restar, multiplicar y dividir dos números.',
                'dificultad' => 'medio',
                'puntos' => 25,
                'orden' => 3,
                'codigo_inicial' => "# Calculadora básica\ndef calculadora(a, b, operacion):\n    # Tu código aquí\n    pass\n\n# Prueba tu función\nprint(calculadora(10, 5, '+'))",
                'ejemplo_entrada' => '10, 5, "+"',
                'ejemplo_salida' => '15',
                'pistas' => 'Usa condicionales (if, elif, else) para determinar qué operación realizar.',
                'activo' => true
            ],
            [
                'titulo' => 'Números Pares',
                'descripcion' => 'Escribe un programa que imprima todos los números pares del 1 al 20.',
                'dificultad' => 'medio',
                'puntos' => 20,
                'orden' => 4,
                'codigo_inicial' => "# Números pares del 1 al 20\n# Tu código aquí",
                'ejemplo_entrada' => '',
                'ejemplo_salida' => "2\n4\n6\n8\n10\n12\n14\n16\n18\n20",
                'pistas' => 'Usa un bucle for y el operador módulo (%) para verificar si un número es par.',
                'activo' => true
            ],
            [
                'titulo' => 'Factorial',
                'descripcion' => 'Implementa una función que calcule el factorial de un número. El factorial de n es n! = n × (n-1) × (n-2) × ... × 1',
                'dificultad' => 'dificil',
                'puntos' => 35,
                'orden' => 5,
                'codigo_inicial' => "# Función factorial\ndef factorial(n):\n    # Tu código aquí\n    pass\n\n# Prueba tu función\nprint(factorial(5))",
                'ejemplo_entrada' => '5',
                'ejemplo_salida' => '120',
                'pistas' => 'Puedes usar recursión o un bucle. Recuerda que 0! = 1.',
                'activo' => true
            ],
            [
                'titulo' => 'Palíndromo',
                'descripcion' => 'Crea una función que determine si una palabra es un palíndromo (se lee igual al derecho y al revés).',
                'dificultad' => 'dificil',
                'puntos' => 30,
                'orden' => 6,
                'codigo_inicial' => "# Verificar palíndromo\ndef es_palindromo(palabra):\n    # Tu código aquí\n    pass\n\n# Prueba tu función\nprint(es_palindromo('radar'))",
                'ejemplo_entrada' => 'radar',
                'ejemplo_salida' => 'True',
                'pistas' => 'Compara la palabra con su versión invertida. Puedes usar word[::-1] para invertir.',
                'activo' => true
            ]
        ];

        foreach ($desafios as $desafioData) {
            $desafioData['curso_id'] = $curso->course_id; // Usar el ID correcto
            Desafio::firstOrCreate([
                'titulo' => $desafioData['titulo']
            ], $desafioData);
        }

        $this->command->info('Desafíos de práctica creados exitosamente!');
    }
}