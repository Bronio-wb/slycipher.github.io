<?php

namespace App\Http\Controllers;

use App\Models\Leccion;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeccionController extends Controller
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
        $query = Leccion::with(['curso.lenguaje', 'curso.categoria']);

        if (Auth::user()->rol === 'desarrollador') {
            $query->whereHas('curso', function($q) {
                $q->where('creado_por', Auth::id());
            });
        }

        $lecciones = $query->paginate(15);
        return view('lecciones.index', compact('lecciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cursos = Auth::user()->rol === 'admin' 
            ? Curso::with(['lenguaje', 'categoria'])->get()
            : Curso::where('creado_por', Auth::id())->with(['lenguaje', 'categoria'])->get();
            
        return view('lecciones.create', compact('cursos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:cursos,course_id',
            'titulo' => 'required|string|max:200',
            'contenido' => 'required|string',
            'orden' => 'required|integer|min:1',
            'estado' => 'required|in:activo,inactivo',
        ]);

        // Verificar que el usuario puede crear lecciones para este curso
        $curso = Curso::findOrFail($request->course_id);
        if (Auth::user()->rol === 'desarrollador' && $curso->creado_por !== Auth::id()) {
            return redirect()->back()->with('error', 'No tienes permisos para crear lecciones en este curso.');
        }

        Leccion::create($request->all());

        return redirect()->route('lecciones.index')->with('success', 'Lección creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Leccion $leccione)
    {
        $leccione->load(['curso.lenguaje', 'curso.categoria', 'progresos.usuario']);
        return view('lecciones.show', compact('leccione'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Leccion $leccione)
    {
        $cursos = Auth::user()->rol === 'admin' 
            ? Curso::with(['lenguaje', 'categoria'])->get()
            : Curso::where('creado_por', Auth::id())->with(['lenguaje', 'categoria'])->get();
            
        return view('lecciones.edit', compact('leccione', 'cursos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Leccion $leccione)
    {
        $request->validate([
            'course_id' => 'required|exists:cursos,course_id',
            'titulo' => 'required|string|max:200',
            'contenido' => 'required|string',
            'orden' => 'required|integer|min:1',
            'estado' => 'required|in:activo,inactivo',
        ]);

        // Verificar que el usuario puede editar lecciones de este curso
        $curso = Curso::findOrFail($request->course_id);
        if (Auth::user()->rol === 'desarrollador' && $curso->creado_por !== Auth::id()) {
            return redirect()->back()->with('error', 'No tienes permisos para editar lecciones de este curso.');
        }

        $leccione->update($request->all());

        return redirect()->route('lecciones.index')->with('success', 'Lección actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leccion $leccione)
    {
        // Verificar que el usuario puede eliminar lecciones de este curso
        if (Auth::user()->rol === 'desarrollador' && $leccione->curso->creado_por !== Auth::id()) {
            return redirect()->back()->with('error', 'No tienes permisos para eliminar esta lección.');
        }

        $leccione->delete();

        return redirect()->route('lecciones.index')->with('success', 'Lección eliminada exitosamente.');
    }
}
