@extends('layouts.app')

@section('title', 'Dashboard Administrador')
@section('page-title', 'Dashboard Administrador')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Estadísticas generales -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Usuarios</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsuarios }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Cursos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCursos }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
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

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Logros</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalLogros }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-trophy fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Usuarios Recientes -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Usuarios Recientes</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuariosRecientes as $usuario)
                                <tr>
                                    <td>{{ $usuario->nombre }} {{ $usuario->apellido }}</td>
                                    <td>{{ $usuario->email }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($usuario->rol === 'admin') bg-danger 
                                            @elseif($usuario->rol === 'desarrollador') bg-warning 
                                            @else bg-primary @endif">
                                            {{ ucfirst($usuario->rol) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $usuario->activo ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cursos Recientes -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Cursos Recientes</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Lenguaje</th>
                                    <th>Nivel</th>
                                    <th>Creador</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cursosRecientes as $curso)
                                <tr>
                                    <td>{{ $curso->titulo }}</td>
                                    <td>{{ $curso->lenguaje->nombre }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($curso->nivel === 'principiante') bg-success 
                                            @elseif($curso->nivel === 'intermedio') bg-warning 
                                            @else bg-danger @endif">
                                            {{ ucfirst($curso->nivel) }}
                                        </span>
                                    </td>
                                    <td>{{ $curso->creador->nombre }} {{ $curso->creador->apellido }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Acciones Rápidas</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-user-plus me-2"></i>Crear Usuario
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.cursos.create') }}" class="btn btn-success btn-block">
                                <i class="fas fa-plus me-2"></i>Crear Curso
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.reportes.usuarios') }}" class="btn btn-info btn-block">
                                <i class="fas fa-file-pdf me-2"></i>Reporte Usuarios
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.reportes.cursos') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-file-pdf me-2"></i>Reporte Cursos
                            </a>
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
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.text-gray-800 {
    color: #5a5c69 !important;
}
.text-gray-300 {
    color: #dddfeb !important;
}
</style>
@endsection