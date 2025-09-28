<?php

namespace Database\Seeders;

use App\Models\Lenguaje;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LenguajeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lenguajes = [
            [
                'nombre' => 'JavaScript',
                'descripcion' => 'Lenguaje de programación para desarrollo web'
            ],
            [
                'nombre' => 'Python',
                'descripcion' => 'Lenguaje de programación versátil para múltiples aplicaciones'
            ],
            [
                'nombre' => 'Java',
                'descripcion' => 'Lenguaje de programación orientado a objetos'
            ],
            [
                'nombre' => 'PHP',
                'descripcion' => 'Lenguaje de programación para desarrollo web del lado servidor'
            ],
            [
                'nombre' => 'C++',
                'descripcion' => 'Lenguaje de programación de bajo nivel y alto rendimiento'
            ],
            [
                'nombre' => 'React',
                'descripcion' => 'Biblioteca de JavaScript para interfaces de usuario'
            ],
            [
                'nombre' => 'Laravel',
                'descripcion' => 'Framework de PHP para desarrollo web'
            ]
        ];

        foreach ($lenguajes as $lenguaje) {
            Lenguaje::create($lenguaje);
        }
    }
}
