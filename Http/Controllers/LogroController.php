<?php

namespace App\Http\Controllers;

use App\Models\Logro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogroController extends Controller
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
        $logros = Logro::paginate(15);
        return view('logros.index', compact('logros'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('logros.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string',
            'icono' => 'nullable|string|max:100',
            'puntos_requeridos' => 'required|integer|min:0',
            'tipo' => 'required|in:progreso,desafio,tiempo,especial',
            'estado' => 'required|in:activo,inactivo',
        ]);

        Logro::create($request->all());

        return redirect()->route('logros.index')->with('success', 'Logro creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Logro $logro)
    {
        $logro->load(['usuariosLogros.usuario']);
        return view('logros.show', compact('logro'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Logro $logro)
    {
        return view('logros.edit', compact('logro'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Logro $logro)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string',
            'icono' => 'nullable|string|max:100',
            'puntos_requeridos' => 'required|integer|min:0',
            'tipo' => 'required|in:progreso,desafio,tiempo,especial',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $logro->update($request->all());

        return redirect()->route('logros.index')->with('success', 'Logro actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Logro $logro)
    {
        $logro->delete();

        return redirect()->route('logros.index')->with('success', 'Logro eliminado exitosamente.');
    }
}
