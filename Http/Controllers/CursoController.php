<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\Lenguaje;
use App\Models\Categoria;

class CursoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'nocache']);
        $this->middleware('role:admin,desarrollador')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cursos = Curso::with(['lenguaje', 'categoria', 'creador'])->paginate(10);
        return view('cursos.index', compact('cursos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Cargar lenguajes y categorias si las clases existen
        $lenguajes = class_exists(Lenguaje::class) ? Lenguaje::all() : collect();
        $categorias = class_exists(Categoria::class) ? Categoria::all() : collect();

        // Detectar la ruta de guardado disponible (admin o developer)
        $storeRoute = null;
        if (Route::has('admin.cursos.store')) {
            $storeRoute = route('admin.cursos.store');
        } elseif (Route::has('developer.cursos.store')) {
            $storeRoute = route('developer.cursos.store');
        } elseif (Route::has('cursos.store')) {
            $storeRoute = route('cursos.store');
        }

        return view('cursos.create', compact('lenguajes', 'categorias', 'storeRoute'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:200',
            'descripcion' => 'nullable|string',
            'nivel' => 'required|in:principiante,intermedio,avanzado',
            'language_id' => 'required|exists:lenguajes,language_id',
            'category_id' => 'required|exists:categorias,category_id',
        ]);

        $curso = Curso::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'nivel' => $request->nivel,
            'language_id' => $request->language_id,
            'category_id' => $request->category_id,
            'creado_por' => Auth::id(),
        ]);

        return redirect()->route('cursos.index')->with('success', 'Curso creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Curso $curso)
    {
        $curso->load(['lenguaje', 'categoria', 'creador', 'lecciones', 'desafios']);
        return view('cursos.show', compact('curso'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Curso $curso)
    {
        $lenguajes = Lenguaje::all();
        $categorias = Categoria::all();
        return view('cursos.edit', compact('curso', 'lenguajes', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Curso $curso)
    {
        $request->validate([
            'titulo' => 'required|string|max:200',
            'descripcion' => 'nullable|string',
            'nivel' => 'required|in:principiante,intermedio,avanzado',
            'language_id' => 'required|exists:lenguajes,language_id',
            'category_id' => 'required|exists:categorias,category_id',
        ]);

        $curso->update([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'nivel' => $request->nivel,
            'language_id' => $request->language_id,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('cursos.index')->with('success', 'Curso actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Curso $curso)
    {
        $curso->delete();
        return redirect()->route('cursos.index')->with('success', 'Curso eliminado exitosamente.');
    }
}
