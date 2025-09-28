<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Curso;
use App\Models\Logro;
use App\Models\ProgresoUsuario;
use App\Models\LogroUsuario;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use Carbon\Carbon;

class ReporteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin', 'nocache']);
    }

    /**
     * Generate usuarios report in PDF format
     */
    public function usuarios()
    {
        try {
            // relaciones que el reporte de usuarios pudiera necesitar
            // (por ejemplo user->progresos u otras)
            $relations = [
                'progresos' => ['progresos', 'progreso_leccions', 'progreso_lecciones', 'progreso_leccion'],
                'cursos' => ['cursos'],
                // añade más relaciones si tu reporte las usa
            ];

            $with = $this->safeRelationsFor(User::class, $relations);

            $query = User::query();
            if (!empty($with)) $query->with($with);

            $users = $query->orderBy('created_at', 'desc')->get();
            
            $estadisticas = [
                'total_usuarios' => $users->count(),
                'administradores' => $users->where('rol', 'admin')->count(),
                'desarrolladores' => $users->where('rol', 'desarrollador')->count(), 
                'estudiantes' => $users->where('rol', 'estudiante')->count(),
                'activos' => $users->where('estado', 'activo')->count(),
                'inactivos' => $users->where('estado', 'inactivo')->count(),
            ];
            
            // Generar PDF
            $pdf = Pdf::loadView('admin.reports.usuarios', compact('users', 'estadisticas'));
            
            return $pdf->download('reporte_usuarios_' . date('Y-m-d') . '.pdf');
        } catch (QueryException $e) {
            \Log::warning('Reporte usuarios: QueryException al generar reporte: '.$e->getMessage());
            $users = User::orderBy('created_at', 'desc')->get();
            return view('admin.reportes.usuarios', compact('users'));
        } catch (\Throwable $e) {
            \Log::error('Reporte usuarios error: '.$e->getMessage());
            abort(500, 'Error interno al generar reporte de usuarios.');
        }
    }

    /**
     * Helper: devuelve array de relaciones que podemos eager-load de forma segura
     * $modelClass: nombre de clase (e.g. Curso::class)
     * $relationsToTables: array asociativo relation => [possible_table_names...]
     */
    protected function safeRelationsFor(string $modelClass, array $relationsToTables): array
    {
        $with = [];
        foreach ($relationsToTables as $rel => $tables) {
            // debe existir el método de relación en el modelo
            if (!method_exists($modelClass, $rel)) {
                continue;
            }
            // al menos una de las tablas relacionadas debe existir en la DB
            $exists = false;
            foreach ((array)$tables as $t) {
                if (Schema::hasTable($t)) { $exists = true; break; }
            }
            if ($exists) $with[] = $rel;
        }
        return $with;
    }

    public function cursos()
    {
        try {
            // define relaciones candidatas y tablas asociadas
            $relations = [
                'lecciones' => ['lecciones', 'lessons'],
                'categorias' => ['categorias', 'categories'],
                'lenguajes' => ['lenguajes', 'languages'],
                'progresos' => ['progresos', 'progreso_leccions', 'progreso_lecciones', 'progreso_leccion'],
            ];

            $with = $this->safeRelationsFor(Curso::class, $relations);

            $query = Curso::query();
            if (!empty($with)) $query->with($with);

            $cursos = $query->orderBy('created_at', 'desc')->get();
            
            $estadisticas = [
                'total_cursos' => $cursos->count(),
                'activos' => $cursos->where('estado', 'activo')->count(),
                'inactivos' => $cursos->where('estado', 'inactivo')->count(),
                'total_lecciones' => $cursos->sum(fn($curso) => $curso->lecciones->count()),
                'cursos_con_progreso' => $cursos->filter(fn($curso) => $curso->progresos->count() > 0)->count(),
            ];
            
            // Generar PDF
            $pdf = Pdf::loadView('admin.reports.cursos', compact('cursos', 'estadisticas'));
            
            return $pdf->download('reporte_cursos_' . date('Y-m-d') . '.pdf');
        } catch (QueryException $e) {
            // Fallback seguro: volver a intentar sin relaciones si hay error en tablas
            \Log::warning('Reporte cursos: QueryException al generar reporte: '.$e->getMessage());
            $cursos = Curso::orderBy('created_at', 'desc')->get();
            return view('admin.reportes.cursos', compact('cursos'));
        } catch (\Throwable $e) {
            \Log::error('Reporte cursos error: '.$e->getMessage());
            abort(500, 'Error interno al generar reporte de cursos.');
        }
    }

    public function progreso()
    {
        // Obtener datos para el reporte
        $progresos = ProgresoUsuario::with(['usuario', 'leccion.curso.lenguaje'])
            ->orderBy('completado_en', 'desc')
            ->get();
        
        $estadisticas = [
            'total_progresos' => $progresos->count(),
            'completados' => $progresos->where('completado', true)->count(),
            'en_progreso' => $progresos->where('completado', false)->count(),
            'usuarios_activos' => $progresos->groupBy('user_id')->count(),
        ];
        
        // Generar PDF
        $pdf = Pdf::loadView('admin.reports.progreso', compact('progresos', 'estadisticas'));
        
        return $pdf->download('reporte_progreso_' . date('Y-m-d') . '.pdf');
    }

    public function logros()
    {
        // Obtener datos para el reporte
        $logrosUsuarios = LogroUsuario::with(['usuario', 'logro'])
            ->orderBy('desbloqueado_en', 'desc')
            ->get();
        
        $logros = Logro::with(['usuariosLogros.usuario'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $estadisticas = [
            'total_logros' => $logros->count(),
            'logros_desbloqueados' => $logrosUsuarios->count(),
            'usuarios_con_logros' => $logrosUsuarios->groupBy('user_id')->count(),
            'logro_mas_popular' => $logros->sortByDesc(fn($logro) => $logro->usuariosLogros->count())->first()?->nombre ?? 'N/A',
        ];
        
        // Generar PDF
        $pdf = Pdf::loadView('admin.reports.logros', compact('logrosUsuarios', 'logros', 'estadisticas'));
        
        return $pdf->download('reporte_logros_' . date('Y-m-d') . '.pdf');
    }
}
