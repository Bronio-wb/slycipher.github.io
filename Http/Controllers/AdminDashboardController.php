<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Curso;
use App\Models\Categoria;
use App\Models\Lenguaje;
use App\Models\Desafio;
use App\Models\Logro;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalUsuarios' => User::count(),
            'totalCursos' => Curso::count(),
            'totalCategorias' => Categoria::count(),
            'totalLenguajes' => Lenguaje::count(),
            'totalDesafios' => Desafio::count(),
            'totalLogros' => Logro::count(),
            'usuariosRecientes' => User::latest()->take(5)->get(),
            'cursosRecientes' => Curso::with(['lenguaje', 'categoria', 'creador'])->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', $data);
    }
}
