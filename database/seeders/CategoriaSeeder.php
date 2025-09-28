<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Programación Web',
                'descripcion' => 'Cursos sobre desarrollo web frontend y backend'
            ],
            [
                'nombre' => 'Ciencias de Datos',
                'descripcion' => 'Cursos sobre análisis de datos, machine learning y estadística'
            ],
            [
                'nombre' => 'Desarrollo Móvil',
                'descripcion' => 'Cursos sobre desarrollo de aplicaciones móviles'
            ],
            [
                'nombre' => 'Ciberseguridad',
                'descripcion' => 'Cursos sobre seguridad informática y protección de datos'
            ],
            [
                'nombre' => 'Inteligencia Artificial',
                'descripcion' => 'Cursos sobre IA, machine learning y deep learning'
            ]
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
