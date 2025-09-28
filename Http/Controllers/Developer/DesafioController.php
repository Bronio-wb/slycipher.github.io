<?php
namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\Desafio;
use App\Models\Lenguaje;

class DesafioController extends Controller
{
    public function create()
    {
        $lenguajes = class_exists(Lenguaje::class) ? Lenguaje::all() : collect();
        return view('desafios.create', compact('lenguajes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'language_id' => 'nullable|integer',
            'dificultad' => 'nullable|string',
            'puntos' => 'nullable|integer',
            'tipo' => 'nullable|string',
        ]);

        $data = $request->only(['titulo','descripcion','language_id','dificultad','puntos','tipo']);

        $desafio = new Desafio();

        // Mapear campos si existen en la tabla
        foreach ($data as $k => $v) {
            if (Schema::hasColumn($desafio->getTable(), $k)) {
                $desafio->{$k} = $v;
            } else {
                // alias comunes
                if ($k === 'language_id' && Schema::hasColumn($desafio->getTable(), 'language_id')) {
                    $desafio->language_id = $v;
                }
            }
        }

        // detectar columna creador
        $possibleCreatorCols = ['creado_por','creador_id','user_id','created_by','autor_id'];
        foreach ($possibleCreatorCols as $col) {
            if (Schema::hasColumn($desafio->getTable(), $col)) {
                $desafio->{$col} = Auth::id();
                break;
            }
        }

        // estado pendiente/pendiente
        if (Schema::hasColumn($desafio->getTable(), 'estado')) {
            $desafio->estado = 'pendiente';
        } elseif (Schema::hasColumn($desafio->getTable(), 'status')) {
            $desafio->status = 'pending';
        }

        $desafio->save();

        return redirect()->route('developer.desafios.create')->with('status','Desafío propuesto correctamente. Será revisado por un administrador.');
    }
}
