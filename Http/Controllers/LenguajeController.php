<?php

namespace App\Http\Controllers;

use App\Models\Lenguaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LenguajeController extends Controller
{
    public function __construct()
    {
        // Los middlewares se aplican en el archivo de rutas
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lenguajes = Lenguaje::with(['cursos', 'desafios'])->paginate(15);
        return view('lenguajes.index', compact('lenguajes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lenguajes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50|unique:lenguajes,nombre',
            'icono' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:7',
            'version' => 'nullable|string|max:20',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:activo,inactivo',
        ]);

        Lenguaje::create($request->all());

        return redirect()->route('lenguajes.index')->with('success', 'Lenguaje creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lenguaje $lenguaje)
    {
        $lenguaje->load(['cursos.categoria', 'cursos.creador', 'desafios.creador']);
        return view('lenguajes.show', compact('lenguaje'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lenguaje $lenguaje)
    {
        return view('lenguajes.edit', compact('lenguaje'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lenguaje $lenguaje)
    {
        $request->validate([
            'nombre' => 'required|string|max:50|unique:lenguajes,nombre,' . $lenguaje->language_id . ',language_id',
            'icono' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:7',
            'version' => 'nullable|string|max:20',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $lenguaje->update($request->all());

        return redirect()->route('lenguajes.index')->with('success', 'Lenguaje actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lenguaje $lenguaje)
    {
        // Verificar si tiene cursos o desafíos asociados
        if ($lenguaje->cursos()->count() > 0 || $lenguaje->desafios()->count() > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar el lenguaje porque tiene cursos o desafíos asociados.');
        }

        $lenguaje->delete();

        return redirect()->route('lenguajes.index')->with('success', 'Lenguaje eliminado exitosamente.');
    }
}