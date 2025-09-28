@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Gestión de Lenguajes</h1>
                <a href="{{ route('admin.lenguajes.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nuevo Lenguaje
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
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
                                    <th>Versión</th>
                                    <th>Descripción</th>
                                    <th>Icono</th>
                                    <th>Cursos</th>
                                    <th>Desafíos</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lenguajes as $lenguaje)
                                <tr>
                                    <td>{{ $lenguaje->language_id }}</td>
                                    <td>{{ $lenguaje->nombre }}</td>
                                    <td>{{ $lenguaje->version ?? 'N/A' }}</td>
                                    <td>{{ Str::limit($lenguaje->descripcion ?? 'Sin descripción', 30) }}</td>
                                    <td>
                                        @if($lenguaje->icono)
                                            <i class="{{ $lenguaje->icono }}"></i>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $lenguaje->cursos->count() }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-warning">{{ $lenguaje->desafios->count() }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $lenguaje->estado === 'activo' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($lenguaje->estado) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.lenguajes.show', $lenguaje) }}" class="btn btn-info btn-sm" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.lenguajes.edit', $lenguaje) }}" class="btn btn-warning btn-sm" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.lenguajes.destroy', $lenguaje) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este lenguaje?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginación -->
                    <div class="d-flex justify-content-center">
                        {{ $lenguajes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection