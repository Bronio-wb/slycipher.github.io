@extends('layouts.app')

@section('title', 'Detalles de la Categoría')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-folder me-2"></i>Detalles de la Categoría
                    </h4>
                    <div class="btn-group">
                        @if(Auth::user()->rol === 'admin')
                            <a href="{{ route('admin.categorias.edit', $categoria->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit me-1"></i>Editar
                            </a>
                        @endif
                        <a href="{{ route(Auth::user()->rol === 'admin' ? 'admin.categorias.index' : 'categorias.index') }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Información principal de la categoría -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3">
                                    @if($categoria->icono)
                                        <i class="{{ $categoria->icono }} fa-3x categoria-icon" 
                                           data-color="{{ $categoria->color ?? '#007bff' }}"></i>
                                    @else
                                        <div class="display-4">📁</div>
                                    @endif
                                </div>
                                <div>
                                    <h2 class="text-info mb-1">{{ $categoria->nombre }}</h2>
                                    <span class="badge bg-{{ $categoria->estado == 'activo' ? 'success' : 'danger' }} fs-6">
                                        {{ $categoria->estado == 'activo' ? '✅ Activo' : '❌ Inactivo' }}
                                    </span>
                                    @if($categoria->color)
                                        <span class="badge ms-2 categoria-color-badge" 
                                              data-bg-color="{{ $categoria->color }}">
                                            {{ $categoria->color }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <strong><i class="fas fa-file-text me-2"></i>Descripción:</strong>
                                <div class="bg-light p-3 rounded mt-2">
                                    {{ $categoria->descripcion ?: 'Sin descripción disponible.' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="card-title text-muted">INFORMACIÓN DE LA CATEGORÍA</h6>
                                    
                                    <div class="mb-3">
                                        <div class="display-6 text-primary">{{ $categoria->cursos->count() }}</div>
                                        <small class="text-muted">Cursos Totales</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="display-6 text-success">{{ $categoria->cursos->where('estado', 'activo')->count() }}</div>
                                        <small class="text-muted">Cursos Activos</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        @php
                                            $estadoColors = [
                                                'activo' => ['text-success', '✅'],
                                                'inactivo' => ['text-danger', '❌']
                                            ];
                                            $colorClass = $estadoColors[$categoria->estado][0] ?? 'text-secondary';
                                            $emoji = $estadoColors[$categoria->estado][1] ?? '❓';
                                        @endphp
                                        <div class="{{ $colorClass }} fw-bold">
                                            {{ $emoji }} {{ ucfirst($categoria->estado) }}
                                        </div>
                                        <small class="text-muted">Estado Actual</small>
                                    </div>
                                    
                                    <hr>
                                    <div class="small text-muted">
                                        <div><strong>Creado:</strong><br>{{ $categoria->created_at->format('d/m/Y H:i') }}</div>
                                        <div class="mt-2"><strong>Actualizado:</strong><br>{{ $categoria->updated_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de cursos de la categoría -->
                    @if($categoria->cursos->count() > 0)
                    <div class="mb-4">
                        <h5 class="text-success mb-3">
                            <i class="fas fa-graduation-cap me-2"></i>Cursos de esta Categoría
                        </h5>
                        <div class="row">
                            @foreach($categoria->cursos->sortBy('titulo') as $curso)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100 border-{{ $curso->estado == 'activo' ? 'success' : ($curso->estado == 'borrador' ? 'warning' : 'danger') }}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title text-primary mb-1">{{ $curso->titulo }}</h6>
                                            <span class="badge bg-{{ $curso->estado == 'activo' ? 'success' : ($curso->estado == 'borrador' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($curso->estado) }}
                                            </span>
                                        </div>
                                        
                                        <p class="card-text small text-muted">
                                            {{ Str::limit($curso->descripcion, 80) }}
                                        </p>
                                        
                                        <div class="d-flex flex-wrap gap-1 mb-2">
                                            @if($curso->lenguaje)
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-code me-1"></i>{{ $curso->lenguaje->nombre }}
                                                </span>
                                            @endif
                                            <span class="badge bg-info">
                                                <i class="fas fa-signal me-1"></i>{{ ucfirst($curso->nivel) }}
                                            </span>
                                        </div>
                                        
                                        <div class="small text-muted">
                                            <div><i class="fas fa-book me-1"></i>{{ $curso->lecciones->count() }} lecciones</div>
                                            <div><i class="fas fa-calendar me-1"></i>{{ $curso->created_at->format('d/m/Y') }}</div>
                                        </div>
                                        
                                        <div class="mt-2">
                                            @if(Auth::user()->rol === 'admin')
                                                <a href="{{ route('admin.cursos.show', $curso->id) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye me-1"></i>Ver
                                                </a>
                                            @elseif(Auth::user()->rol === 'desarrollador' && $curso->creado_por === Auth::id())
                                                <a href="{{ route('developer.cursos.show', $curso->id) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye me-1"></i>Ver
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Sin cursos</strong><br>
                        Aún no hay cursos asignados a esta categoría.
                    </div>
                    @endif

                    <!-- Estadísticas adicionales -->
                    @if($categoria->cursos->count() > 0)
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5 class="text-info mb-3">
                                <i class="fas fa-chart-bar me-2"></i>Estadísticas de la Categoría
                            </h5>
                            <div class="row">
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card text-center bg-primary text-white">
                                        <div class="card-body">
                                            <div class="display-6">{{ $categoria->cursos->count() }}</div>
                                            <small>Total de Cursos</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card text-center bg-success text-white">
                                        <div class="card-body">
                                            <div class="display-6">{{ $categoria->cursos->where('estado', 'activo')->count() }}</div>
                                            <small>Cursos Activos</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card text-center bg-warning text-white">
                                        <div class="card-body">
                                            <div class="display-6">{{ $categoria->cursos->where('estado', 'borrador')->count() }}</div>
                                            <small>En Borrador</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card text-center bg-info text-white">
                                        <div class="card-body">
                                            <div class="display-6">{{ $categoria->cursos->sum(function($curso) { return $curso->lecciones->count(); }) }}</div>
                                            <small>Total de Lecciones</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Acciones -->
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route(Auth::user()->rol === 'admin' ? 'admin.categorias.index' : 'categorias.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-list me-1"></i>Volver a Lista
                                </a>
                            </div>
                            @if(Auth::user()->rol === 'admin')
                            <div class="btn-group">
                                <a href="{{ route('admin.categorias.edit', $categoria->id) }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-1"></i>Editar
                                </a>
                                <button type="button" class="btn btn-danger" 
                                        data-categoria-id="{{ $categoria->id }}" 
                                        data-cursos-count="{{ $categoria->cursos->count() }}"
                                        onclick="confirmarEliminacion(this.dataset.categoriaId, this.dataset.cursosCount)">
                                    <i class="fas fa-trash me-1"></i>Eliminar
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Formulario oculto para eliminación -->
                    @if(Auth::user()->rol === 'admin')
                    <form id="delete-form-{{ $categoria->id }}" action="{{ route('admin.categorias.destroy', $categoria->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmarEliminacion(id, cursosAsociados) {
    let mensaje = '¿Estás seguro de que deseas eliminar esta categoría?';
    
    if (cursosAsociados > 0) {
        mensaje += '\n\nATENCIÓN: Esta categoría tiene ' + cursosAsociados + ' cursos asociados. Al eliminarla, estos cursos quedarán sin categoría.';
    }
    
    mensaje += '\n\nEsta acción no se puede deshacer.';
    
    if (confirm(mensaje)) {
        document.getElementById('delete-form-' + id).submit();
    }
}

// Aplicar colores dinámicos
document.addEventListener('DOMContentLoaded', function() {
    // Iconos de categoría
    document.querySelectorAll('.categoria-icon').forEach(function(icon) {
        if (icon.dataset.color) {
            icon.style.color = icon.dataset.color;
        }
    });
    
    // Badges de color
    document.querySelectorAll('.categoria-color-badge').forEach(function(badge) {
        if (badge.dataset.bgColor) {
            badge.style.backgroundColor = badge.dataset.bgColor;
            badge.style.color = 'white';
        }
    });
});
</script>
@endpush