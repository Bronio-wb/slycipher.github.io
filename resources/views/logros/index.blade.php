@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Gestión de Logros</h1>
                @if(auth()->user()->rol === 'admin')
                <a href="{{ route('logros.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nuevo Logro
                </a>
                @endif
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Icono</th>
                                    <th>Tipo</th>
                                    <th>Puntos Requeridos</th>
                                    <th>Usuarios</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logros as $logro)
                                <tr>
                                    <td>{{ $logro->achievement_id }}</td>
                                    <td>{{ $logro->nombre }}</td>
                                    <td>{{ Str::limit($logro->descripcion, 50) }}</td>
                                    <td>
                                        @if($logro->icono)
                                            <i class="{{ $logro->icono }}"></i>
                                        @else
                                            <i class="fas fa-trophy"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $colorTipo = match($logro->tipo) {
                                                'progreso' => 'primary',
                                                'desafio' => 'warning',
                                                'tiempo' => 'info',
                                                'especial' => 'success',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge badge-{{ $colorTipo }}">{{ ucfirst($logro->tipo) }}</span>
                                    </td>
                                    <td>{{ $logro->puntos_requeridos }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $logro->usuariosLogros->count() }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $logro->estado === 'activo' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($logro->estado) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('logros.show', $logro) }}" class="btn btn-info btn-sm" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(auth()->user()->rol === 'admin')
                                            <a href="{{ route('logros.edit', $logro) }}" class="btn btn-warning btn-sm" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('logros.destroy', $logro) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este logro?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginación -->
                    <div class="d-flex justify-content-center">
                        {{ $logros->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection