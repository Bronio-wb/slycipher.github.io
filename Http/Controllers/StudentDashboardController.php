<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Progreso;
use App\Models\Desafio;
use App\Models\Logro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        $data = [
            'cursosDisponibles' => Curso::all(),
            'miProgreso' => Progreso::where('usuario_id', $userId)
                ->with(['desafio'])
                ->orderBy('updated_at', 'desc')
                ->take(10)
                ->get(),
            'desafiosDisponibles' => Desafio::all(),
            'logrosDisponibles' => Logro::all(),
            'totalCompletados' => Progreso::where('usuario_id', $userId)
                ->where('completado', true)
                ->count(),
            'totalDesafios' => Desafio::count(),
            'usuario' => Auth::user(),
        ];

        return view('student.dashboard', $data);
    }
}
