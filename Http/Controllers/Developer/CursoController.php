<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\Curso;
use App\Models\Lenguaje;
use App\Models\Categoria;

class CursoController extends Controller
{
    public function create()
    {
        // Cargar datos auxiliares si existen
        $lenguajes = class_exists(Lenguaje::class) ? Lenguaje::all() : collect();
        $categorias = class_exists(Categoria::class) ? Categoria::all() : collect();

        // Pasamos la ruta de store para que la vista la use
        $storeRoute = route('developer.cursos.store');

        return view('cursos.create', compact('lenguajes','categorias','storeRoute'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'language_id' => 'nullable|integer',
            'category_id' => 'nullable|integer',
            'nivel' => 'nullable|string',
            'estado' => 'nullable|string',
        ]);

        $curso = new Curso();

        // Asignar sólo columnas existentes
        $possible = [
            'titulo'=>'titulo',
            'descripcion'=>'descripcion',
            'nivel'=>'nivel',
            'language_id'=>'language_id',
            'category_id'=>'category_id',
            'estado'=>'estado',
        ];

        foreach ($possible as $input => $col) {
            if ($request->has($input) && Schema::hasColumn($curso->getTable(), $col)) {
                $curso->{$col} = $request->input($input);
            }
        }

        // detectar columna de creador y asignar
        $possibleCreatorCols = ['creado_por','creador_id','user_id','created_by','autor_id'];
        foreach ($possibleCreatorCols as $col) {
            if (Schema::hasColumn($curso->getTable(), $col)) {
                $curso->{$col} = Auth::id();
                break;
            }
        }

        // si existe columna estado y no fue enviada, marcar como 'pendiente'
        if (Schema::hasColumn($curso->getTable(), 'estado') && empty($curso->estado)) {
            $curso->estado = 'pendiente';
        } elseif (Schema::hasColumn($curso->getTable(), 'status') && empty($curso->status)) {
            $curso->status = 'pending';
        }

        $curso->save();

        return redirect()->route('developer.cursos.create')->with('status','Curso propuesto correctamente. Espera revisión del administrador.');
    }
}
