@extends('layouts.app')

@section('title', 'Lista de Cursos')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-book me-2"></i>Lista de Cursos
                    </h4>
                    <div class="btn-group">
                        @php $role = strtolower(auth()->user()->rol ?? ''); @endphp

                        @if($role === 'admin')
                            @if(Route::has('admin.cursos.create'))
                                <a href="{{ route('admin.cursos.create') }}" class="btn btn-light btn-sm">
                                    <i class="fas fa-plus me-1"></i>Nuevo Curso
                                </a>
                            @else
                                <button class="btn btn-light btn-sm" disabled title="Ruta admin.cursos.create no disponible">
                                    <i class="fas fa-plus me-1"></i>Nuevo Curso
                                </button>
                            @endif
                        @elseif($role === 'desarrollador' || $role === 'developer')
                            @if(Route::has('developer.cursos.create'))
                                <a href="{{ route('developer.cursos.create') }}" class="btn btn-light btn-sm">
                                    <i class="fas fa-plus me-1"></i>Nuevo Curso
                                </a>
                            @else
                                <button class="btn btn-light btn-sm" disabled title="Ruta developer.cursos.create no disponible">
                                    <i class="fas fa-plus me-1"></i>Nuevo Curso
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <input type="text" id="searchInput" class="form-control" placeholder="Buscar cursos...">
                        </div>
                        <div class="col-md-3">
                            <select id="categoriaFilter" class="form-select">
                                <option value="">Todas las categorías</option>
                                @foreach(\App\Models\Categoria::all() as $categoria)
                                    <option value="{{ $categoria->nombre }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="lenguajeFilter" class="form-select">
                                <option value="">Todos los lenguajes</option>
                                @foreach(\App\Models\Lenguaje::all() as $lenguaje)
                                    <option value="{{ $lenguaje->nombre }}">{{ $lenguaje->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                                <i class="fas fa-eraser me-1"></i>Limpiar
                            </button>
                        </div>
                    </div>

                    <!-- Lista de cursos -->
                    @if($cursos->count() > 0)
                    <div class="row" id="cursosContainer">
                        @foreach($cursos as $curso)
                        <div class="col-md-6 col-lg-4 mb-4 curso-item" 
                             data-titulo="{{ strtolower($curso->titulo) }}"
                             data-categoria="{{ strtolower($curso->categoria->nombre ?? '') }}"
                             data-lenguaje="{{ strtolower($curso->lenguaje->nombre ?? '') }}">
                            <div class="card h-100 shadow-sm">
                                <div class="card-header bg-light">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h6 class="card-title mb-0">{{ $curso->titulo }}</h6>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('cursos.show', $curso->course_id) }}">
                                                        <i class="fas fa-eye me-1"></i>Ver detalles
                                                    </a>
                                                </li>
                                                @can('update', $curso)
                                                <li>
                                                    <a class="dropdown-item" href="{{ auth()->user()->rol === 'admin' ? route('admin.cursos.edit', $curso->course_id) : route('developer.cursos.edit', $curso->course_id) }}">
                                                        <i class="fas fa-edit me-1"></i>Editar
                                                    </a>
                                                </li>
                                                @endcan
                                                @can('delete', $curso)
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <button class="dropdown-item text-danger" onclick="eliminarCurso('{{ $curso->course_id }}')">
                                                        <i class="fas fa-trash me-1"></i>Eliminar
                                                    </button>
                                                </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="card-text small text-muted mb-2">{{ Str::limit($curso->descripcion, 100) }}</p>
                                    
                                    <div class="row text-center mb-3">
                                        <div class="col-4">
                                            <div class="text-primary">
                                                <i class="fas fa-code"></i>
                                                <div class="small">{{ $curso->lenguaje->nombre ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-success">
                                                <i class="fas fa-folder"></i>
                                                <div class="small">{{ $curso->categoria->nombre ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-info">
                                                <i class="fas fa-star"></i>
                                                <div class="small">{{ $curso->dificultad }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row text-center small text-muted">
                                        <div class="col-6">
                                            <i class="fas fa-list me-1"></i>{{ $curso->lecciones->count() }} lecciones
                                        </div>
                                        <div class="col-6">
                                            <i class="fas fa-user me-1"></i>{{ $curso->creador->nombre ?? 'Sin autor' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-{{ $curso->estado === 'activo' ? 'success' : ($curso->estado === 'borrador' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($curso->estado) }}
                                        </span>
                                        <small class="text-muted">{{ $curso->created_at->format('d/m/Y') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Paginación -->
                    <div class="d-flex justify-content-center">
                        {{ $cursos->links() }}
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-book fa-4x text-gray-300 mb-3"></i>
                        <h5 class="text-muted">No se encontraron cursos</h5>
                        <p class="text-muted">No hay cursos disponibles en este momento.</p>
                        @can('create', App\Models\Curso::class)
                        @if(auth()->user()->rol === 'admin')
                            <a href="{{ route('admin.cursos.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Crear primer curso
                            </a>
                        @elseif(auth()->user()->rol === 'desarrollador')
                            <a href="{{ route('developer.cursos.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Crear primer curso
                            </a>
                        @endif
                        @endcan
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Formularios ocultos para eliminación -->
@foreach($cursos as $curso)
<form id="delete-form-{{ $curso->course_id }}" action="{{ auth()->user()->rol === 'admin' ? route('admin.cursos.destroy', $curso->course_id) : route('developer.cursos.destroy', $curso->course_id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endforeach
@endsection

@push('scripts')
<script>
// Funcionalidad de búsqueda y filtrado
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const categoriaFilter = document.getElementById('categoriaFilter');
    const lenguajeFilter = document.getElementById('lenguajeFilter');
    const cursosContainer = document.getElementById('cursosContainer');
    const cursoItems = document.querySelectorAll('.curso-item');

    function filterCursos() {
        const searchTerm = searchInput.value.toLowerCase();
        const categoriaSelected = categoriaFilter.value.toLowerCase();
        const lenguajeSelected = lenguajeFilter.value.toLowerCase();

        cursoItems.forEach(item => {
            const titulo = item.dataset.titulo;
            const categoria = item.dataset.categoria;
            const lenguaje = item.dataset.lenguaje;

            const matchesSearch = titulo.includes(searchTerm);
            const matchesCategoria = !categoriaSelected || categoria.includes(categoriaSelected);
            const matchesLenguaje = !lenguajeSelected || lenguaje.includes(lenguajeSelected);

            if (matchesSearch && matchesCategoria && matchesLenguaje) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterCursos);
    categoriaFilter.addEventListener('change', filterCursos);
    lenguajeFilter.addEventListener('change', filterCursos);
});

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('categoriaFilter').value = '';
    document.getElementById('lenguajeFilter').value = '';
    
    // Mostrar todos los elementos
    document.querySelectorAll('.curso-item').forEach(item => {
        item.style.display = 'block';
    });
}

function eliminarCurso(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este curso?\n\nEsta acción eliminará también todas las lecciones asociadas y no se puede deshacer.')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush