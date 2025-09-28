@extends('layouts.app')

@section('title', 'Detalles de la Lección')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-book-open me-2"></i>Detalles de la Lección
                    </h4>
                    <div class="btn-group">
                        @if(Auth::user()->rol === 'admin' || (Auth::user()->rol === 'desarrollador' && $leccione->curso->creado_por === Auth::id()))
                            <a href="{{ route(Auth::user()->rol === 'admin' ? 'admin.lecciones.edit' : 'developer.lecciones.edit', $leccione->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit me-1"></i>Editar
                            </a>
                        @endif
                        <a href="{{ route(Auth::user()->rol === 'admin' ? 'admin.lecciones.index' : 'developer.lecciones.index') }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Información principal de la lección -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3">
                                    <div class="display-4">📚</div>
                                </div>
                                <div>
                                    <h2 class="text-primary mb-1">{{ $leccione->titulo }}</h2>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-info me-2">Orden: {{ $leccione->orden }}</span>
                                        <span class="badge bg-{{ $leccione->estado == 'activo' ? 'success' : ($leccione->estado == 'borrador' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($leccione->estado) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <strong><i class="fas fa-file-text me-2"></i>Descripción:</strong>
                                <div class="bg-light p-3 rounded mt-2">
                                    {{ $leccione->descripcion ?: 'Sin descripción disponible.' }}
                                </div>
                            </div>

                            <!-- Información del curso -->
                            <div class="mb-3">
                                <strong><i class="fas fa-graduation-cap me-2"></i>Curso:</strong>
                                <div class="bg-light p-3 rounded mt-2">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h6 class="text-primary mb-2">{{ $leccione->curso->titulo }}</h6>
                                            <div class="d-flex flex-wrap gap-2">
                                                @if($leccione->curso->lenguaje)
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-code me-1"></i>{{ $leccione->curso->lenguaje->nombre }}
                                                    </span>
                                                @endif
                                                @if($leccione->curso->categoria)
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-folder me-1"></i>{{ $leccione->curso->categoria->nombre }}
                                                    </span>
                                                @endif
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-signal me-1"></i>{{ ucfirst($leccione->curso->nivel) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="card-title text-muted">INFORMACIÓN DE LA LECCIÓN</h6>
                                    
                                    <div class="mb-3">
                                        <div class="display-6 text-success">{{ $leccione->orden }}</div>
                                        <small class="text-muted">Orden en el Curso</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        @php
                                            $estadoColors = [
                                                'activo' => ['text-success', '✅'],
                                                'inactivo' => ['text-danger', '❌'],
                                                'borrador' => ['text-warning', '📝']
                                            ];
                                            $colorClass = $estadoColors[$leccione->estado][0] ?? 'text-secondary';
                                            $emoji = $estadoColors[$leccione->estado][1] ?? '❓';
                                        @endphp
                                        <div class="{{ $colorClass }} fw-bold">
                                            {{ $emoji }} {{ ucfirst($leccione->estado) }}
                                        </div>
                                        <small class="text-muted">Estado Actual</small>
                                    </div>
                                    
                                    <hr>
                                    <div class="small text-muted">
                                        <div><strong>Creado:</strong><br>{{ $leccione->created_at->format('d/m/Y H:i') }}</div>
                                        <div class="mt-2"><strong>Actualizado:</strong><br>{{ $leccione->updated_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contenido de la lección -->
                    @if($leccione->contenido)
                    <div class="mb-4">
                        <h5 class="text-success mb-3">
                            <i class="fas fa-file-alt me-2"></i>Contenido de la Lección
                        </h5>
                        <div class="border rounded p-4" style="max-height: 400px; overflow-y: auto;">
                            {!! nl2br(e($leccione->contenido)) !!}
                        </div>
                    </div>
                    @endif

                    <!-- Estadísticas de progreso -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5 class="text-info mb-3">
                                <i class="fas fa-chart-bar me-2"></i>Estadísticas de Progreso
                            </h5>
                            <div class="row">
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card text-center bg-primary text-white">
                                        <div class="card-body">
                                            <div class="display-6">{{ $leccione->progresos->count() }}</div>
                                            <small>Estudiantes Inscritos</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card text-center bg-success text-white">
                                        <div class="card-body">
                                            <div class="display-6">{{ $leccione->progresos->where('completado', true)->count() }}</div>
                                            <small>Completados</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card text-center bg-warning text-white">
                                        <div class="card-body">
                                            <div class="display-6">{{ $leccione->progresos->where('completado', false)->count() }}</div>
                                            <small>En Progreso</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card text-center bg-info text-white">
                                        <div class="card-body">
                                            @php
                                                $totalEstudiantes = $leccione->progresos->count();
                                                $completados = $leccione->progresos->where('completado', true)->count();
                                                $porcentaje = $totalEstudiantes > 0 ? round(($completados / $totalEstudiantes) * 100, 1) : 0;
                                            @endphp
                                            <div class="display-6">{{ $porcentaje }}%</div>
                                            <small>Tasa de Finalización</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de estudiantes -->
                    @if($leccione->progresos->count() > 0)
                    <div class="mb-4">
                        <h5 class="text-success mb-3">
                            <i class="fas fa-users me-2"></i>Progreso de Estudiantes
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th><i class="fas fa-user me-1"></i>Estudiante</th>
                                        <th><i class="fas fa-envelope me-1"></i>Email</th>
                                        <th><i class="fas fa-tasks me-1"></i>Estado</th>
                                        <th><i class="fas fa-calendar me-1"></i>Fecha Inicio</th>
                                        <th><i class="fas fa-check me-1"></i>Fecha Finalización</th>
                                        <th><i class="fas fa-percentage me-1"></i>Progreso</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leccione->progresos->sortByDesc('updated_at') as $progreso)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-2">
                                                    <span class="badge bg-secondary">{{ strtoupper(substr($progreso->usuario->nombre, 0, 1)) }}{{ strtoupper(substr($progreso->usuario->apellido ?? '', 0, 1)) }}</span>
                                                </div>
                                                <div>
                                                    <strong>{{ $progreso->usuario->nombre }} {{ $progreso->usuario->apellido ?? '' }}</strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $progreso->usuario->email }}</td>
                                        <td>
                                            @if($progreso->completado)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>Completado
                                                </span>
                                            @else
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-clock me-1"></i>En Progreso
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $progreso->created_at->format('d/m/Y H:i') }}</span>
                                            <br>
                                            <small class="text-info">{{ $progreso->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            @if($progreso->fecha_completado)
                                                <span class="text-muted">{{ $progreso->fecha_completado->format('d/m/Y H:i') }}</span>
                                                <br>
                                                <small class="text-success">{{ $progreso->fecha_completado->diffForHumans() }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-{{ $progreso->completado ? 'success' : 'warning' }} leccion-progress-bar" 
                                                     role="progressbar" 
                                                     data-width="{{ $progreso->progreso }}"
                                                     aria-valuenow="{{ $progreso->progreso }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    {{ $progreso->progreso }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Sin estudiantes</strong><br>
                        Aún ningún estudiante ha comenzado esta lección.
                    </div>
                    @endif

                    <!-- Acciones -->
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route(Auth::user()->rol === 'admin' ? 'admin.lecciones.index' : 'developer.lecciones.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-list me-1"></i>Volver a Lista
                                </a>
                            </div>
                            @if(Auth::user()->rol === 'admin' || (Auth::user()->rol === 'desarrollador' && $leccione->curso->creado_por === Auth::id()))
                            <div class="btn-group">
                                <a href="{{ route(Auth::user()->rol === 'admin' ? 'admin.lecciones.edit' : 'developer.lecciones.edit', $leccione->id) }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-1"></i>Editar
                                </a>
                                <button type="button" class="btn btn-danger" 
                                        data-leccion-id="{{ $leccione->id }}" 
                                        data-estudiantes-count="{{ $leccione->progresos->count() }}"
                                        onclick="confirmarEliminacion(this.dataset.leccionId, this.dataset.estudiantesCount)">
                                    <i class="fas fa-trash me-1"></i>Eliminar
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Formulario oculto para eliminación -->
                    @if(Auth::user()->rol === 'admin' || (Auth::user()->rol === 'desarrollador' && $leccione->curso->creado_por === Auth::id()))
                    <form id="delete-form-{{ $leccione->id }}" action="{{ route(Auth::user()->rol === 'admin' ? 'admin.lecciones.destroy' : 'developer.lecciones.destroy', $leccione->id) }}" method="POST" style="display: none;">
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
function confirmarEliminacion(id, estudiantesEnProgreso) {
    let mensaje = '¿Estás seguro de que deseas eliminar esta lección?';
    
    if (estudiantesEnProgreso > 0) {
        mensaje += '\n\nATENCIÓN: ' + estudiantesEnProgreso + ' estudiantes tienen progreso en esta lección. Al eliminarla, se perderán todos estos registros.';
    }
    
    mensaje += '\n\nEsta acción no se puede deshacer.';
    
    if (confirm(mensaje)) {
        document.getElementById('delete-form-' + id).submit();
    }
}

// Aplicar anchos dinámicos a las barras de progreso
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.leccion-progress-bar').forEach(function(bar) {
        if (bar.dataset.width) {
            bar.style.width = bar.dataset.width + '%';
        }
    });
});
</script>
@endpush