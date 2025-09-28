@extends('layouts.app')

@section('title', 'Dashboard Desarrollador')
@section('page-title', 'Dashboard Desarrollador')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Estadísticas del desarrollador -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Mis Cursos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalMisCursos }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Lecciones</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalLecciones }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Desafíos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalDesafios }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-puzzle-piece fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Mis Cursos -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Mis Cursos</h6>
                    <a href="{{ route('developer.cursos.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Nuevo Curso
                    </a>
                </div>
                <div class="card-body">
                    @if($misCursos->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Lenguaje</th>
                                        <th>Categoría</th>
                                        <th>Nivel</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($misCursos as $curso)
                                    <tr>
                                        <td>{{ $curso->titulo }}</td>
                                        <td>{{ $curso->lenguaje->nombre }}</td>
                                        <td>{{ $curso->categoria->nombre }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($curso->nivel === 'principiante') bg-success 
                                                @elseif($curso->nivel === 'intermedio') bg-warning 
                                                @else bg-danger @endif">
                                                {{ ucfirst($curso->nivel) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('cursos.show', $curso->course_id) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('cursos.edit', $curso->course_id) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-book fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">No has creado ningún curso aún.</p>
                            <a href="{{ route('developer.cursos.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Crear mi primer curso
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Herramientas de Desarrollo -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Herramientas de Desarrollo</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('developer.cursos.create') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-plus text-primary me-2"></i>
                            Crear Nuevo Curso
                        </a>
                        <a href="{{ route('developer.lecciones.create') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-list text-success me-2"></i>
                            Crear Nueva Lección
                        </a>
                        <a href="{{ route('developer.desafios.create') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-puzzle-piece text-info me-2"></i>
                            Crear Nuevo Desafío
                        </a>
                        <a href="{{ route('logros.create') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-trophy text-warning me-2"></i>
                            Crear Nuevo Logro
                        </a>
                    </div>
                </div>
            </div>

            <!-- Lenguajes y Categorías Disponibles -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recursos Disponibles</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-sm font-weight-bold">Lenguajes de Programación:</h6>
                        <div class="row">
                            @foreach($lenguajes as $lenguaje)
                                <div class="col-6 mb-1">
                                    <span class="badge bg-primary">{{ $lenguaje->nombre }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-sm font-weight-bold">Categorías:</h6>
                        <div class="row">
                            @foreach($categorias as $categoria)
                                <div class="col-12 mb-1">
                                    <span class="badge bg-secondary">{{ $categoria->nombre }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.text-gray-800 {
    color: #5a5c69 !important;
}
.text-gray-300 {
    color: #dddfeb !important;
}
</style>
@endsection