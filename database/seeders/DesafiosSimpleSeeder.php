<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Desafio;

class DesafiosSimpleSeeder extends Seeder
{
    public function run()
    {
        // Crear desafíos directamente sin dependencias complejas
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
                'activo' => true,
                'curso_id' => null, // Sin curso por ahora
                'leccion_id' => null,
                'created_at' => now(),
                'updated_at' => now()
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
                'activo' => true,
                'curso_id' => null,
                'leccion_id' => null,
                'created_at' => now(),
                'updated_at' => now()
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
                'activo' => true,
                'curso_id' => null,
                'leccion_id' => null,
                'created_at' => now(),
                'updated_at' => now()
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
                'activo' => true,
                'curso_id' => null,
                'leccion_id' => null,
                'created_at' => now(),
                'updated_at' => now()
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
                'activo' => true,
                'curso_id' => null,
                'leccion_id' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($desafios as $desafioData) {
            DB::table('desafios')->insert($desafioData);
        }

        $this->command->info('Desafíos de práctica creados exitosamente!');
    }
}