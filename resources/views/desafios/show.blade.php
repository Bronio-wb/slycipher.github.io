@extends('layouts.app')

@section('title', 'Detalles del Desafío')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-eye me-2"></i>Detalles del Desafío
                    </h4>
                    <div class="btn-group">
                        <a href="{{ route('desafios.edit', $desafio->id) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-edit me-1"></i>Editar
                        </a>
                        <a href="{{ route('desafios.index') }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Volver
                        </a>
                    </div>
                </div>
                <div class="card-body" 
                     data-codigo-inicial="{{ base64_encode($desafio->codigo_inicial ?? '') }}" 
                     data-solucion="{{ base64_encode($desafio->solucion ?? '') }}">
                    <!-- Información básica del desafío -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <h3 class="text-primary mb-3">{{ $desafio->titulo }}</h3>
                            <div class="mb-3">
                                <strong><i class="fas fa-file-text me-2"></i>Descripción:</strong>
                                <div class="bg-light p-3 rounded mt-2">
                                    {{ $desafio->descripcion }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title text-muted">INFORMACIÓN DEL DESAFÍO</h6>
                                    
                                    <div class="mb-2">
                                        <strong><i class="fas fa-code me-1"></i>Lenguaje:</strong>
                                        <span class="badge bg-primary ms-1">{{ $desafio->lenguaje->nombre ?? 'N/A' }}</span>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <strong><i class="fas fa-signal me-1"></i>Dificultad:</strong>
                                        @php
                                            $dificultadColors = [
                                                'principiante' => ['bg-success', '🟢'],
                                                'intermedio' => ['bg-warning', '🟡'],
                                                'avanzado' => ['bg-danger', '🔴'],
                                                'experto' => ['bg-dark', '⚫']
                                            ];
                                            $colorClass = $dificultadColors[$desafio->dificultad][0] ?? 'bg-secondary';
                                            $emoji = $dificultadColors[$desafio->dificultad][1] ?? '❓';
                                        @endphp
                                        <span class="badge {{ $colorClass }} ms-1">
                                            {{ $emoji }} {{ ucfirst($desafio->dificultad) }}
                                        </span>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <strong><i class="fas fa-trophy me-1"></i>Puntos:</strong>
                                        <span class="text-success fw-bold">{{ $desafio->puntos }}</span>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <strong><i class="fas fa-toggle-on me-1"></i>Estado:</strong>
                                        @php
                                            $estadoColors = [
                                                'activo' => ['text-success', '✅'],
                                                'inactivo' => ['text-danger', '❌'],
                                                'borrador' => ['text-warning', '📝']
                                            ];
                                            $colorClass = $estadoColors[$desafio->estado][0] ?? 'text-secondary';
                                            $emoji = $estadoColors[$desafio->estado][1] ?? '❓';
                                        @endphp
                                        <span class="{{ $colorClass }} fw-bold">
                                            {{ $emoji }} {{ ucfirst($desafio->estado) }}
                                        </span>
                                    </div>
                                    
                                    <hr>
                                    <div class="small text-muted">
                                        <div><strong>Creado:</strong> {{ $desafio->created_at->format('d/m/Y H:i') }}</div>
                                        <div><strong>Actualizado:</strong> {{ $desafio->updated_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Código inicial (si existe) -->
                    @if($desafio->codigo_inicial)
                    <div class="mb-4">
                        <h5 class="text-secondary mb-3">
                            <i class="fas fa-code me-2"></i>Código Inicial
                        </h5>
                        <div class="bg-dark text-light p-3 rounded" style="font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;">
                            <pre class="mb-0 text-light"><code>{{ $desafio->codigo_inicial }}</code></pre>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary mt-2" onclick="copyToClipboard('codigo_inicial')">
                            <i class="fas fa-copy me-1"></i>Copiar Código
                        </button>
                    </div>
                    @endif

                    <!-- Solución -->
                    <div class="mb-4">
                        <h5 class="text-success mb-3">
                            <i class="fas fa-check-circle me-2"></i>Solución
                        </h5>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Nota:</strong> Esta sección contiene la solución completa del desafío. 
                            Solo visible para administradores y desarrolladores.
                        </div>
                        <div class="bg-dark text-light p-3 rounded" style="font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;">
                            <pre class="mb-0 text-light"><code>{{ $desafio->solucion }}</code></pre>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary mt-2" onclick="copyToClipboard('solucion')">
                            <i class="fas fa-copy me-1"></i>Copiar Solución
                        </button>
                    </div>

                    <!-- Estadísticas del desafío -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h5 class="text-info mb-3">
                                <i class="fas fa-chart-bar me-2"></i>Estadísticas
                            </h5>
                            <div class="row">
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card text-center bg-primary text-white">
                                        <div class="card-body">
                                            <div class="display-6">{{ $desafio->intentos->count() ?? 0 }}</div>
                                            <small>Intentos Totales</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card text-center bg-success text-white">
                                        <div class="card-body">
                                            <div class="display-6">{{ $desafio->intentos->where('exitoso', 1)->count() ?? 0 }}</div>
                                            <small>Completados</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card text-center bg-info text-white">
                                        <div class="card-body">
                                            <div class="display-6">{{ $desafio->intentos->unique('usuario_id')->count() ?? 0 }}</div>
                                            <small>Usuarios Únicos</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="card text-center bg-warning text-white">
                                        <div class="card-body">
                                            @php
                                                $totalIntentos = $desafio->intentos->count();
                                                $exitosos = $desafio->intentos->where('exitoso', 1)->count();
                                                $tasa = $totalIntentos > 0 ? round(($exitosos / $totalIntentos) * 100, 1) : 0;
                                            @endphp
                                            <div class="display-6">{{ $tasa }}%</div>
                                            <small>Tasa de Éxito</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('desafios.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-list me-1"></i>Volver a Lista
                                </a>
                            </div>
                            <div class="btn-group">
                                <a href="{{ route('desafios.edit', $desafio->id) }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-1"></i>Editar
                                </a>
                                <button type="button" class="btn btn-danger" onclick="confirmarEliminacion('{{ $desafio->id }}')">
                                    <i class="fas fa-trash me-1"></i>Eliminar
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario oculto para eliminación -->
                    <form id="delete-form-{{ $desafio->id }}" action="{{ route('desafios.destroy', $desafio->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Variables desde PHP usando atributos data
const cardBody = document.querySelector('.card-body');
const codigoInicial = atob(cardBody.dataset.codigoInicial || '');
const solucion = atob(cardBody.dataset.solucion || '');

function copyToClipboard(type) {
    let text = '';
    if (type === 'codigo_inicial') {
        text = codigoInicial;
    } else if (type === 'solucion') {
        text = solucion;
    }
    
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(function() {
            // Mostrar mensaje de éxito
            const btn = event.target.closest('button');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check me-1"></i>¡Copiado!';
            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('btn-success');
            
            setTimeout(function() {
                btn.innerHTML = originalText;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-secondary');
            }, 2000);
        });
    } else {
        // Fallback para navegadores sin soporte
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        
        alert('Código copiado al portapapeles');
    }
}

function confirmarEliminacion(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este desafío? Esta acción no se puede deshacer.')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush