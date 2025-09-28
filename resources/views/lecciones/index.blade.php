@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Gestión de Lecciones</h1>
                @can('create', App\Models\Leccion::class)
                <a href="{{ route('lecciones.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nueva Lección
                </a>
                @endcan
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
                                    <th>Título</th>
                                    <th>Curso</th>
                                    <th>Lenguaje</th>
                                    <th>Orden</th>
                                    <th>Estado</th>
                                    <th>Fecha Creación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lecciones as $leccion)
                                <tr>
                                    <td>{{ $leccion->lesson_id }}</td>
                                    <td>{{ $leccion->titulo }}</td>
                                    <td>{{ $leccion->curso->titulo ?? 'N/A' }}</td>
                                    <td>
                                        @if($leccion->curso && $leccion->curso->lenguaje)
                                            <span class="badge badge-info">{{ $leccion->curso->lenguaje->nombre }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $leccion->orden }}</td>
                                    <td>
                                        <span class="badge badge-{{ $leccion->estado === 'activo' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($leccion->estado) }}
                                        </span>
                                    </td>
                                    <td>{{ $leccion->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('lecciones.show', $leccion) }}" class="btn btn-info btn-sm" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(auth()->user()->rol === 'admin' || (auth()->user()->rol === 'desarrollador' && $leccion->curso && $leccion->curso->creado_por === auth()->id()))
                                            <a href="{{ route('lecciones.edit', $leccion) }}" class="btn btn-warning btn-sm" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('lecciones.destroy', $leccion) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar esta lección?')">
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
                        {{ $lecciones->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection