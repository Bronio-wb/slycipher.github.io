@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Gestión de Categorías</h1>
                <a href="{{ route('admin.categorias.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nueva Categoría
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
                                    <th>Descripción</th>
                                    <th>Icono</th>
                                    <th>Color</th>
                                    <th>Cursos</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categorias as $categoria)
                                <tr>
                                    <td>{{ $categoria->category_id }}</td>
                                    <td>{{ $categoria->nombre }}</td>
                                    <td>{{ Str::limit($categoria->descripcion ?? 'Sin descripción', 50) }}</td>
                                    <td>
                                        @if($categoria->icono)
                                            <i class="{{ $categoria->icono }}"></i>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if($categoria->color)
                                            <span class="badge categoria-color-display" 
                                                  data-bg-color="{{ $categoria->color }}">{{ $categoria->color }}</span>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $categoria->cursos->count() }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $categoria->estado === 'activo' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($categoria->estado) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.categorias.show', $categoria) }}" class="btn btn-info btn-sm" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.categorias.edit', $categoria) }}" class="btn btn-warning btn-sm" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.categorias.destroy', $categoria) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar esta categoría?')">
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
                        {{ $categorias->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Aplicar colores dinámicos a los badges
    document.querySelectorAll('.categoria-color-display').forEach(function(badge) {
        if (badge.dataset.bgColor) {
            badge.style.backgroundColor = badge.dataset.bgColor;
            badge.style.color = 'white';
        }
    });
});
</script>
@endpush