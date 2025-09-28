<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin', 'nocache']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = User::paginate(15);
        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.usuarios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'rol' => 'required|in:admin,estudiante,desarrollador',
        ]);

        User::create([
            'username' => $request->username,
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'name' => $request->nombre . ' ' . $request->apellido,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
            'estado' => 'activo',
            'activo' => true,
            'racha' => 0,
        ]);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $usuario)
    {
        $usuario->load(['cursosCreados', 'progresos.leccion.curso', 'desafios.desafio', 'logros.logro']);
        return view('admin.usuarios.show', compact('usuario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $usuario)
    {
        return view('admin.usuarios.edit', compact('usuario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $usuario)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . $usuario->id,
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users,email,' . $usuario->id,
            'rol' => 'required|in:admin,estudiante,desarrollador',
            'estado' => 'required|in:activo,inactivo',
            'activo' => 'boolean',
        ]);

        $updateData = [
            'username' => $request->username,
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'name' => $request->nombre . ' ' . $request->apellido,
            'email' => $request->email,
            'rol' => $request->rol,
            'estado' => $request->estado,
            'activo' => $request->has('activo'),
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:6|confirmed']);
            $updateData['password'] = Hash::make($request->password);
        }

        $usuario->update($updateData);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $usuario)
    {
        if ($usuario->id === Auth::id()) {
            return redirect()->route('admin.usuarios.index')->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $usuario->delete();
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}
