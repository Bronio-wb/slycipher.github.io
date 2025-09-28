@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Gestión de Desafíos</h1>
                {{-- Mostrar botón "Crear" solo si existe la ruta correspondiente y el usuario puede crear --}}
                @can('create', App\Models\Desafio::class)
                    @if (Route::has('desafios.create'))
                        <a href="{{ route('desafios.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nuevo Desafío
                        </a>
                    @elseif (Route::has('admin.desafios.create') && auth()->user()->rol === 'admin')
                        <a href="{{ route('admin.desafios.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nuevo Desafío
                        </a>
                    @elseif (Route::has('developer.desafios.create') && auth()->user()->rol === 'desarrollador')
                        <a href="{{ route('developer.desafios.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nuevo Desafío
                        </a>
                    @else
                        {{-- Si no hay ruta pública disponible, no mostrar nada (evita error) --}}
                    @endif
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
                                    <th>Lenguaje</th>
                                    <th>Dificultad</th>
                                    <th>Puntos</th>
                                    <th>Creador</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($desafios as $desafio)
                                <tr>
                                    <td>{{ $desafio->challenge_id }}</td>
                                    <td>{{ $desafio->titulo }}</td>
                                    <td>
                                        @if($desafio->lenguaje)
                                            <span class="badge badge-info">{{ $desafio->lenguaje->nombre }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $colorDificultad = match($desafio->dificultad) {
                                                'fácil' => 'success',
                                                'medio' => 'warning',
                                                'difícil' => 'danger',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge badge-{{ $colorDificultad }}">{{ ucfirst($desafio->dificultad) }}</span>
                                    </td>
                                    <td>{{ $desafio->puntos }}</td>
                                    <td>{{ $desafio->creador->nombre ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $desafio->estado === 'activo' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($desafio->estado) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('desafios.show', $desafio) }}" class="btn btn-info btn-sm" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(auth()->user()->rol === 'admin' || (auth()->user()->rol === 'desarrollador' && $desafio->creado_por === auth()->id()))
                                            <a href="{{ route('desafios.edit', $desafio) }}" class="btn btn-warning btn-sm" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('desafios.destroy', $desafio) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este desafío?')">
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
                        {{ $desafios->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection