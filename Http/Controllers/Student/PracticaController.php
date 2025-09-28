<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Desafio;
use App\Models\Progreso;
use Illuminate\Support\Facades\Auth;
use App\Models\Curso;
use Illuminate\Support\Facades\Storage;

class PracticaController extends Controller
{
    public function __construct()
    {
        // El middleware ya está aplicado en las rutas
    }

    /**
     * Mostrar la página principal de práctica
     */
    public function index()
    {
        $user = Auth::user();
        
        // Obtener desafíos disponibles (ajustado a estructura real)
        $desafios = Desafio::with(['curso'])
            ->orderBy('dificultad', 'asc')
            ->orderBy('titulo', 'asc')
            ->get();

        // Obtener progreso del usuario
        $progresosCompletados = Progreso::where('usuario_id', $user->id)
            ->where('completado', true)
            ->pluck('desafio_id')
            ->toArray();

        // Estadísticas de práctica
        $stats = [
            'total_desafios' => $desafios->count(),
            'completados' => count($progresosCompletados),
            'puntos_ganados' => Progreso::where('usuario_id', $user->id)
                ->where('completado', true)
                ->sum('puntos_ganados'),
            'racha_actual' => $this->calcularRachaActual($user->id)
        ];

        return view('student.practica.index', compact('desafios', 'progresosCompletados', 'stats'));
    }

    /**
     * Mostrar un desafío específico con el editor
     */
    public function show($desafioId)
    {
        // Obtener el desafío (usando la columna PK que tengas: challenge_id según el stacktrace)
        $desafio = Desafio::where('challenge_id', $desafioId)->firstOrFail();

        $curso = null;
        $lecciones = collect();

        // Intentar cargar el curso relacionado de forma segura si existe la relación
        try {
            if (method_exists($desafio, 'curso')) {
                // si Desafio tiene relación curso(), la usamos y cargamos sus lecciones
                $curso = $desafio->curso()->with('lecciones')->first();
            } else {
                // fallback: buscar por campo course_id si existe
                $courseId = $desafio->course_id ?? null;
                if ($courseId) {
                    $curso = Curso::where('course_id', $courseId)->with('lecciones')->first();
                }
            }
        } catch (\Throwable $e) {
            // prevenir error en caso de relaciones inexistentes; dejamos $curso = null
            $curso = null;
        }

        // Si conseguimos curso, tomar sus lecciones (si existe la relación)
        if ($curso && method_exists($curso, 'lecciones')) {
            try {
                $lecciones = $curso->lecciones;
            } catch (\Throwable $e) {
                $lecciones = collect();
            }
        }

        // Pasar variables a la vista (usar nombres claros en la vista)
        return view('student.practica.show', [
            'desafio' => $desafio,
            'curso' => $curso,
            'lecciones' => $lecciones,
        ]);
    }

    /**
     * Ejecutar código enviado por el estudiante
     */
    public function ejecutar(Request $request, $id)
    {
        $request->validate([
            'codigo' => 'required|string',
            'lenguaje' => 'required|string|in:python,javascript,java,php'
        ]);

        $desafio = Desafio::findOrFail($id);
        $user = Auth::user();
        $codigo = $request->input('codigo');
        $lenguaje = $request->input('lenguaje');

        // Actualizar progreso
        $progreso = Progreso::where('usuario_id', $user->id)
            ->where('desafio_id', $desafio->challenge_id)
            ->first();

        if ($progreso) {
            $progreso->codigo_actual = $codigo;
            $progreso->intentos += 1;
            $progreso->save();
        }

        // Simular ejecución de código (aquí integrarías con un motor de ejecución real)
        $resultado = $this->simularEjecucion($codigo, $lenguaje, $desafio);

        return response()->json([
            'success' => true,
            'output' => $resultado['output'],
            'error' => $resultado['error'] ?? null,
            'tests_passed' => $resultado['tests_passed'] ?? 0,
            'total_tests' => $resultado['total_tests'] ?? 0,
            'completed' => $resultado['completed'] ?? false
        ]);
    }

    /**
     * Guardar progreso sin ejecutar
     */
    public function guardar(Request $request, $id)
    {
        $request->validate([
            'codigo' => 'required|string'
        ]);

        $user = Auth::user();
        $codigo = $request->input('codigo');

        $progreso = Progreso::where('usuario_id', $user->id)
            ->where('desafio_id', $id)
            ->first();

        if ($progreso) {
            $progreso->codigo_actual = $codigo;
            $progreso->save();
        }

        return response()->json(['success' => true, 'message' => 'Código guardado exitosamente']);
    }

    /**
     * Simular ejecución de código (reemplazar con motor real)
     */
    private function simularEjecucion($codigo, $lenguaje, $desafio)
    {
        // Simulación básica - en producción usar un sandboxed executor
        $output = '';
        $error = null;
        $testsPassedCount = 0;
        $totalTests = 3; // Ejemplo

        try {
            switch ($lenguaje) {
                case 'python':
                    if (strpos($codigo, 'print(') !== false) {
                        $output = "Hola Mundo\n";
                        $testsPassedCount = 3;
                    } else {
                        $output = "Sin salida";
                        $testsPassedCount = 0;
                    }
                    break;
                
                case 'javascript':
                    if (strpos($codigo, 'console.log') !== false) {
                        $output = "Hola Mundo\n";
                        $testsPassedCount = 3;
                    } else {
                        $output = "Sin salida";
                        $testsPassedCount = 0;
                    }
                    break;
                
                default:
                    $output = "Lenguaje no soportado aún";
                    $testsPassedCount = 0;
            }
        } catch (\Exception $e) {
            $error = "Error de ejecución: " . $e->getMessage();
            $testsPassedCount = 0;
        }

        $completed = $testsPassedCount === $totalTests;

        // Si se completó, actualizar el progreso
        if ($completed) {
            $progreso = Progreso::where('usuario_id', Auth::id())
                ->where('desafio_id', $desafio->challenge_id)
                ->first();
            
            if ($progreso && !$progreso->completado) {
                $progreso->completado = true;
                $progreso->puntos_ganados = 20; // Puntos fijos por ahora
                $progreso->fecha_completado = now();
                $progreso->save();
            }
        }

        return [
            'output' => $output,
            'error' => $error,
            'tests_passed' => $testsPassedCount,
            'total_tests' => $totalTests,
            'completed' => $completed
        ];
    }

    /**
     * Calcular racha actual del usuario
     */
    private function calcularRachaActual($userId)
    {
        // Lógica para calcular días consecutivos de práctica
        $ultimosProgressos = Progreso::where('usuario_id', $userId)
            ->where('completado', true)
            ->orderBy('fecha_completado', 'desc')
            ->take(30)
            ->get();

        $racha = 0;
        $fechaAnterior = now()->startOfDay();

        foreach ($ultimosProgressos as $progreso) {
            $fechaProgreso = $progreso->fecha_completado->startOfDay();
            
            if ($fechaProgreso->equalTo($fechaAnterior) || $fechaProgreso->equalTo($fechaAnterior->subDay())) {
                $racha++;
                $fechaAnterior = $fechaProgreso;
            } else {
                break;
            }
        }

        return $racha;
    }

    /**
     * Recibe la solución enviada por el alumno y la guarda en storage.
     * No ejecuta código del alumno en el servidor; devuelve respuesta JSON.
     */
    public function resolver(Request $request, $desafio)
    {
        $request->validate([
            'code' => 'required|string',
            'lang' => 'nullable|string|max:32',
        ]);

        $user = Auth::user();

        // Obtener el desafío de forma segura
        $des = Desafio::where('challenge_id', $desafio)->first();
        if (!$des) {
            return response()->json(['message' => 'Desafío no encontrado.'], 404);
        }

        // Preparar payload a guardar
        $payload = [
            'user_id' => $user->id ?? null,
            'user_email' => $user->email ?? null,
            'challenge_id' => $desafio,
            'lang' => $request->input('lang'),
            'code' => $request->input('code'),
            'ip' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'created_at' => now()->toDateTimeString(),
        ];

        // Guardar en storage/app/submissions/ (crea la carpeta si hace falta)
        $filename = 'submissions/challenge_' . $desafio . '_user_' . ($user->id ?? 'guest') . '_' . time() . '.json';
        try {
            Storage::disk('local')->put($filename, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        } catch (\Throwable $e) {
            return response()->json(['message' => 'No se pudo guardar la solución en servidor.'], 500);
        }

        // Respuesta mínima: no hay ejecución por seguridad
        return response()->json([
            'message' => 'Solución recibida y guardada en servidor.',
            'output' => '',          // backend puede rellenar si ejecuta pruebas reales
            'results' => [],         // estructura para resultados de test (vacía por ahora)
            'saved_file' => $filename,
        ], 200);
    }
}