<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Desafio;
use App\Models\Leccion;
use App\Models\Lenguaje;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeveloperDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        $data = [
            'misCursos' => Curso::where('creado_por', $userId)->with(['lenguaje', 'categoria'])->get(),
            'totalMisCursos' => Curso::where('creado_por', $userId)->count(),
            'totalDesafios' => Desafio::whereHas('curso', function($query) use ($userId) {
                $query->where('creado_por', $userId);
            })->count(),
            'totalLecciones' => Leccion::whereHas('curso', function($query) use ($userId) {
                $query->where('creado_por', $userId);
            })->count(),
            'lenguajes' => Lenguaje::all(),
            'categorias' => Categoria::all(),
        ];

        return view('developer.dashboard', $data);
    }
}
