<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriaController extends Controller
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
        $categorias = Categoria::with(['cursos'])->paginate(15);
        return view('categorias.index', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias,nombre',
            'descripcion' => 'nullable|string',
            'icono' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:7',
            'estado' => 'required|in:activo,inactivo',
        ]);

        Categoria::create($request->all());

        return redirect()->route('categorias.index')->with('success', 'Categoría creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        $categoria->load(['cursos.lenguaje', 'cursos.creador']);
        return view('categorias.show', compact('categoria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias,nombre,' . $categoria->category_id . ',category_id',
            'descripcion' => 'nullable|string',
            'icono' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:7',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $categoria->update($request->all());

        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        // Verificar si tiene cursos asociados
        if ($categoria->cursos()->count() > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar la categoría porque tiene cursos asociados.');
        }

        $categoria->delete();

        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada exitosamente.');
    }
}
