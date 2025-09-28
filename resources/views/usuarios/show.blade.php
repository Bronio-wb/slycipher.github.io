@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Detalle del Usuario</h1>
                <div>
                    <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Información Personal</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Nombre de Usuario:</strong>
                                    <p>{{ $usuario->username }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Email:</strong>
                                    <p>{{ $usuario->email }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Nombre Completo:</strong>
                                    <p>{{ $usuario->nombre }} {{ $usuario->apellido }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Rol:</strong>
                                    <span class="badge badge-{{ $usuario->rol === 'admin' ? 'danger' : ($usuario->rol === 'desarrollador' ? 'warning' : 'info') }} badge-pill">
                                        {{ ucfirst($usuario->rol) }}
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Estado:</strong>
                                    <span class="badge badge-{{ $usuario->estado === 'activo' ? 'success' : 'secondary' }} badge-pill">
                                        {{ ucfirst($usuario->estado) }}
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Fecha de Registro:</strong>
                                    <p>{{ $usuario->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Estadísticas</h6>
                        </div>
                        <div class="card-body">
                            @if($usuario->rol === 'desarrollador')
                                <div class="text-center">
                                    <div class="h5 font-weight-bold text-primary">{{ $usuario->cursosCreados->count() }}</div>
                                    <div class="small text-gray-500">Cursos Creados</div>
                                </div>
                                <hr>
                                <div class="text-center">
                                    <div class="h5 font-weight-bold text-success">{{ $usuario->desafios->count() }}</div>
                                    <div class="small text-gray-500">Desafíos Creados</div>
                                </div>
                            @elseif($usuario->rol === 'estudiante')
                                <div class="text-center">
                                    <div class="h5 font-weight-bold text-info">{{ $usuario->progresos->count() }}</div>
                                    <div class="small text-gray-500">Cursos en Progreso</div>
                                </div>
                                <hr>
                                <div class="text-center">
                                    <div class="h5 font-weight-bold text-warning">{{ $usuario->logros->count() }}</div>
                                    <div class="small text-gray-500">Logros Obtenidos</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($usuario->rol === 'desarrollador' && $usuario->cursosCreados->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Cursos Creados</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Categoría</th>
                                    <th>Lenguaje</th>
                                    <th>Estado</th>
                                    <th>Fecha Creación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuario->cursosCreados as $curso)
                                <tr>
                                    <td>{{ $curso->titulo }}</td>
                                    <td>{{ $curso->categoria->nombre ?? 'N/A' }}</td>
                                    <td>{{ $curso->lenguaje->nombre ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $curso->estado === 'activo' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($curso->estado) }}
                                        </span>
                                    </td>
                                    <td>{{ $curso->created_at->format('d/m/Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection