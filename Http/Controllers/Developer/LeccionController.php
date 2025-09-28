<?php
namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Models\Leccion;
use App\Models\Curso;

class LeccionController extends Controller
{
    public function create()
    {
        $cursos = class_exists(Curso::class) ? Curso::all() : collect();
        return view('lecciones.create', compact('cursos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'course_id' => 'required|integer',
            'duracion' => 'nullable|string',
        ]);

        $data = $request->only(['titulo','descripcion','course_id','duracion']);

        $leccion = new Leccion();

        foreach ($data as $k => $v) {
            if (Schema::hasColumn($leccion->getTable(), $k)) {
                $leccion->{$k} = $v;
            }
        }

        // creador columna
        $possibleCreatorCols = ['creado_por','creador_id','user_id','created_by','autor_id'];
        foreach ($possibleCreatorCols as $col) {
            if (Schema::hasColumn($leccion->getTable(), $col)) {
                $leccion->{$col} = Auth::id();
                break;
            }
        }

        if (Schema::hasColumn($leccion->getTable(), 'estado')) {
            $leccion->estado = 'pendiente';
        } elseif (Schema::hasColumn($leccion->getTable(), 'status')) {
            $leccion->status = 'pending';
        }

        $leccion->save();

        return redirect()->route('developer.lecciones.create')->with('status','Lección propuesta correctamente. Será revisada por un administrador.');
    }
}
