<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DeveloperDashboardController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\LeccionController;
use App\Http\Controllers\DesafioController;
use App\Http\Controllers\LogroController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\LenguajeController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\Student\PracticaController;
use App\Http\Controllers\Developer\DesafioController as DeveloperDesafioController;
use App\Http\Controllers\Developer\LeccionController as DeveloperLeccionController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Developer\CursoController as DeveloperCursoController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// Ruta de dashboard personalizada que redirige según el rol
Route::get('/dashboard', function () {
    if (Auth::check()) {
        $rol = Auth::user()->rol;
        
        switch ($rol) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'desarrollador':
                return redirect()->route('developer.dashboard');
            case 'estudiante':
                return redirect()->route('student.dashboard');
            default:
                return redirect()->route('login');
        }
    }
    
    return redirect()->route('login');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas del Administrador
Route::middleware(['auth', 'role:admin', 'nocache'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // CRUD de Usuarios (solo admin)
    Route::resource('usuarios', UsuarioController::class);
    
    // CRUD de Categorías (solo admin)
    Route::resource('categorias', CategoriaController::class);
    
    // CRUD de Lenguajes (solo admin)
    Route::resource('lenguajes', LenguajeController::class);
    
    // CRUD de Cursos (admin puede gestionar todos)
    Route::resource('cursos', CursoController::class);
    
    // CRUD de Lecciones (admin puede gestionar todas)  
    Route::resource('lecciones', LeccionController::class);
    
    // CRUD de Desafíos (admin puede gestionar todos)
    Route::resource('desafios', DesafioController::class);
    
    // Reportes PDF
    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/usuarios', [ReporteController::class, 'usuarios'])->name('usuarios');
        Route::get('/cursos', [ReporteController::class, 'cursos'])->name('cursos');
        Route::get('/progreso', [ReporteController::class, 'progreso'])->name('progreso');
        Route::get('/logros', [ReporteController::class, 'logros'])->name('logros');
    });
});

// Rutas del Desarrollador
Route::middleware(['auth', 'role:desarrollador', 'nocache'])->prefix('developer')->name('developer.')->group(function () {
    Route::get('/dashboard', [DeveloperDashboardController::class, 'index'])->name('dashboard');
    
    // Desarrolladores pueden crear/editar sus propios cursos, lecciones y desafíos
    Route::resource('cursos', CursoController::class)->except(['index', 'show']);
    Route::resource('lecciones', LeccionController::class)->except(['index', 'show']);
    Route::resource('desafios', DesafioController::class)->except(['index', 'show']);
});

// Rutas para desarrolladores (crear desafíos y lecciones)
Route::middleware(['web','auth','role:desarrollador|developer'])->prefix('developer')->name('developer.')->group(function () {
	// Desafíos
	Route::get('desafios/create', [DeveloperDesafioController::class, 'create'])->name('desafios.create');
	Route::post('desafios', [DeveloperDesafioController::class, 'store'])->name('desafios.store');

	// Lecciones
	Route::get('lecciones/create', [DeveloperLeccionController::class, 'create'])->name('lecciones.create');
	Route::post('lecciones', [DeveloperLeccionController::class, 'store'])->name('lecciones.store');
});

// Rutas para desarrolladores: crear y almacenar cursos (propuestas)
Route::middleware(['web','auth','role:desarrollador|developer'])->prefix('developer')->name('developer.')->group(function () {
	// Crear curso (form)
	Route::get('cursos/create', [DeveloperCursoController::class, 'create'])->name('cursos.create');
	// Guardar curso propuesto
	Route::post('cursos', [DeveloperCursoController::class, 'store'])->name('cursos.store');
});

// Rutas del Estudiante
Route::middleware(['auth', 'role:estudiante', 'nocache'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    
    // Rutas de práctica y desafíos
    Route::get('/practica', [PracticaController::class, 'index'])->name('practica.index');
    Route::get('/practica/{desafio}', [PracticaController::class, 'show'])->name('practica.show');
    Route::post('/practica/{desafio}/ejecutar', [PracticaController::class, 'ejecutar'])->name('practica.ejecutar');
    Route::post('/practica/{desafio}/guardar', [PracticaController::class, 'guardar'])->name('practica.guardar');
});

// Rutas para estudiante - resolver práctica (POST)
Route::middleware(['web', 'auth', 'role:estudiante', 'nocache'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        // ...otras rutas student...
        Route::post('practica/{desafio}/resolver', [PracticaController::class, 'resolver'])
            ->name('practica.resolver');
    });

// Rutas de perfil (requieren auth)
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas compartidas (según permisos)
Route::middleware(['auth', 'nocache'])->group(function () {
    // Vistas de solo lectura para todos los usuarios autenticados
    Route::get('/cursos', [CursoController::class, 'index'])->name('cursos.index');
    Route::get('/cursos/{curso}', [CursoController::class, 'show'])->name('cursos.show');
    Route::get('/lecciones', [LeccionController::class, 'index'])->name('lecciones.index');
    Route::get('/lecciones/{leccione}', [LeccionController::class, 'show'])->name('lecciones.show');
    Route::get('/desafios', [DesafioController::class, 'index'])->name('desafios.index');
    Route::get('/desafios/{desafio}', [DesafioController::class, 'show'])->name('desafios.show');
    
    // CRUD de Logros (solo admin puede crear/editar/eliminar)
    Route::middleware('role:admin')->group(function () {
        Route::resource('logros', LogroController::class)->except(['index', 'show']);
    });
    Route::get('/logros', [LogroController::class, 'index'])->name('logros.index');
    Route::get('/logros/{logro}', [LogroController::class, 'show'])->name('logros.show');
    
    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas para administrador: revisar y aprobar contenidos propuestos
Route::middleware(['web','auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {
	Route::get('revisiones', [AdminReviewController::class, 'index'])->name('reviews.index');
	Route::post('revisiones/desafio/{id}/aprobar', [AdminReviewController::class, 'approveDesafio'])->name('reviews.desafio.approve');
	Route::post('revisiones/leccion/{id}/aprobar', [AdminReviewController::class, 'approveLeccion'])->name('reviews.leccion.approve');
});

require __DIR__.'/auth.php';
