<?php

namespace App\Http\Controllers;

use App\Models\Desafio;
use App\Models\Lenguaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class DesafioController extends Controller
{
    public function __construct()
    {
        // Los middlewares se aplican en el archivo de rutas
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Detectar columna de "creador" en la tabla 'desafios'
        $possibleCreatorCols = ['creado_por', 'creador_id', 'user_id', 'created_by', 'autor_id'];
        $creatorColumn = null;
        foreach ($possibleCreatorCols as $col) {
            if (Schema::hasColumn('desafios', $col)) {
                $creatorColumn = $col;
                break;
            }
        }

        // Construir query base
        $desafiosQuery = Desafio::query();

        // Si hay columna de creador, filtramos por el usuario actual (si procede)
        if ($creatorColumn && $user) {
            $desafiosQuery->where($creatorColumn, $user->id);
        } else {
            // Si no hay columna de creador, no forzamos el where; opcionalmente podrías loggear esto.
            // \Log::warning('DesafioController@index: no se detectó columna de creador en table desafios.');
        }

        // Aplicar paginación/orden u otros filtros existentes
        $desafios = $desafiosQuery->orderBy('created_at', 'desc')->paginate(10);

        return view('desafios.index', compact('desafios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $lenguajes = Lenguaje::where('estado', 'activo')->get();
        return view('desafios.create', compact('lenguajes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:200',
            'descripcion' => 'required|string',
            'codigo_inicial' => 'nullable|string',
            'solucion' => 'required|string',
            'puntos' => 'required|integer|min:0|max:1000',
            'dificultad' => 'required|in:fácil,medio,difícil',
            'language_id' => 'required|exists:lenguajes,language_id',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $data = $request->all();
        $data['creado_por'] = Auth::id();

        Desafio::create($data);

        return redirect()->route('desafios.index')->with('success', 'Desafío creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Desafio $desafio)
    {
        $desafio->load(['lenguaje', 'creador', 'usuariosDesafios.usuario']);
        return view('desafios.show', compact('desafio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Desafio $desafio)
    {
        // Verificar permisos
        if (Auth::user()->rol === 'desarrollador' && $desafio->creado_por !== Auth::id()) {
            return redirect()->route('desafios.index')->with('error', 'No tienes permisos para editar este desafío.');
        }

        $lenguajes = Lenguaje::where('estado', 'activo')->get();
        return view('desafios.edit', compact('desafio', 'lenguajes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Desafio $desafio)
    {
        // Verificar permisos
        if (Auth::user()->rol === 'desarrollador' && $desafio->creado_por !== Auth::id()) {
            return redirect()->route('desafios.index')->with('error', 'No tienes permisos para editar este desafío.');
        }

        $request->validate([
            'titulo' => 'required|string|max:200',
            'descripcion' => 'required|string',
            'codigo_inicial' => 'nullable|string',
            'solucion' => 'required|string',
            'puntos' => 'required|integer|min:0|max:1000',
            'dificultad' => 'required|in:fácil,medio,difícil',
            'language_id' => 'required|exists:lenguajes,language_id',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $desafio->update($request->all());

        return redirect()->route('desafios.index')->with('success', 'Desafío actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Desafio $desafio)
    {
        // Verificar permisos
        if (Auth::user()->rol === 'desarrollador' && $desafio->creado_por !== Auth::id()) {
            return redirect()->route('desafios.index')->with('error', 'No tienes permisos para eliminar este desafío.');
        }

        $desafio->delete();

        return redirect()->route('desafios.index')->with('success', 'Desafío eliminado exitosamente.');
    }
}
