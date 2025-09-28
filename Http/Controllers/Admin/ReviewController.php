<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\Desafio;
use App\Models\Leccion;

class ReviewController extends Controller
{
    public function index()
    {
        // Buscar desafíos/lecciones con estado pendiente (detección flexible)
        $desafios = Desafio::query();
        if (Schema::hasColumn((new Desafio())->getTable(), 'estado')) {
            $desafios = $desafios->where('estado','pendiente');
        } elseif (Schema::hasColumn((new Desafio())->getTable(), 'status')) {
            $desafios = $desafios->where('status','pending');
        } else {
            $desafios = $desafios->whereNull('id'); // none
        }
        $desafios = $desafios->get();

        $lecciones = Leccion::query();
        if (Schema::hasColumn((new Leccion())->getTable(), 'estado')) {
            $lecciones = $lecciones->where('estado','pendiente');
        } elseif (Schema::hasColumn((new Leccion())->getTable(), 'status')) {
            $lecciones = $lecciones->where('status','pending');
        } else {
            $lecciones = $lecciones->whereNull('id');
        }
        $lecciones = $lecciones->get();

        return view('admin.reviews.index', compact('desafios','lecciones'));
    }

    public function approveDesafio($id)
    {
        $d = Desafio::findOrFail($id);
        if (Schema::hasColumn($d->getTable(), 'estado')) $d->estado = 'activo';
        if (Schema::hasColumn($d->getTable(), 'status')) $d->status = 'approved';
        $d->save();
        return back()->with('status','Desafío aprobado.');
    }

    public function approveLeccion($id)
    {
        $l = Leccion::findOrFail($id);
        if (Schema::hasColumn($l->getTable(), 'estado')) $l->estado = 'activo';
        if (Schema::hasColumn($l->getTable(), 'status')) $l->status = 'approved';
        $l->save();
        return back()->with('status','Lección aprobada.');
    }
}
