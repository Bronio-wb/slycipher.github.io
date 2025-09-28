@extends('layouts.app')

@section('title', 'Editar Logro')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Editar Logro
                    </h4>
                    <a href="{{ route('logros.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('logros.update', $logro->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label fw-bold">
                                        <i class="fas fa-award me-1"></i>Nombre del Logro *
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('nombre') is-invalid @enderror" 
                                           id="nombre" 
                                           name="nombre" 
                                           value="{{ old('nombre', $logro->nombre) }}" 
                                           maxlength="100" 
                                           required>
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="tipo" class="form-label fw-bold">
                                        <i class="fas fa-tags me-1"></i>Tipo de Logro *
                                    </label>
                                    <select class="form-select @error('tipo') is-invalid @enderror" 
                                            id="tipo" 
                                            name="tipo" 
                                            required>
                                        <option value="">Seleccionar tipo</option>
                                        <option value="progreso" {{ old('tipo', $logro->tipo) == 'progreso' ? 'selected' : '' }}>
                                            📈 Progreso
                                        </option>
                                        <option value="desafio" {{ old('tipo', $logro->tipo) == 'desafio' ? 'selected' : '' }}>
                                            🎯 Desafío
                                        </option>
                                        <option value="tiempo" {{ old('tipo', $logro->tipo) == 'tiempo' ? 'selected' : '' }}>
                                            ⏱️ Tiempo
                                        </option>
                                        <option value="especial" {{ old('tipo', $logro->tipo) == 'especial' ? 'selected' : '' }}>
                                            ⭐ Especial
                                        </option>
                                    </select>
                                    @error('tipo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label fw-bold">
                                <i class="fas fa-file-text me-1"></i>Descripción del Logro *
                            </label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      id="descripcion" 
                                      name="descripcion" 
                                      rows="4" 
                                      maxlength="500" 
                                      required>{{ old('descripcion', $logro->descripcion) }}</textarea>
                            <div class="form-text">Explica claramente qué debe hacer el usuario para obtener este logro.</div>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="puntos_requeridos" class="form-label fw-bold">
                                        <i class="fas fa-star me-1"></i>Puntos Requeridos *
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('puntos_requeridos') is-invalid @enderror" 
                                           id="puntos_requeridos" 
                                           name="puntos_requeridos" 
                                           value="{{ old('puntos_requeridos', $logro->puntos_requeridos) }}" 
                                           min="0" 
                                           max="10000" 
                                           required>
                                    <div class="form-text">Puntos necesarios para desbloquear este logro.</div>
                                    @error('puntos_requeridos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="estado" class="form-label fw-bold">
                                        <i class="fas fa-toggle-on me-1"></i>Estado *
                                    </label>
                                    <select class="form-select @error('estado') is-invalid @enderror" 
                                            id="estado" 
                                            name="estado" 
                                            required>
                                        <option value="activo" {{ old('estado', $logro->estado) == 'activo' ? 'selected' : '' }}>
                                            ✅ Activo
                                        </option>
                                        <option value="inactivo" {{ old('estado', $logro->estado) == 'inactivo' ? 'selected' : '' }}>
                                            ❌ Inactivo
                                        </option>
                                        <option value="borrador" {{ old('estado', $logro->estado) == 'borrador' ? 'selected' : '' }}>
                                            📝 Borrador
                                        </option>
                                    </select>
                                    @error('estado')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Información de usuarios que han obtenido este logro -->
                        @if($logro->usuariosLogros->count() > 0)
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="fas fa-users me-2"></i>Usuarios con este logro
                            </h6>
                            <p class="mb-2">
                                <strong>{{ $logro->usuariosLogros->count() }}</strong> usuarios han obtenido este logro.
                            </p>
                            <div class="row">
                                @foreach($logro->usuariosLogros->take(6) as $usuarioLogro)
                                <div class="col-md-4 col-sm-6 mb-1">
                                    <small>
                                        <i class="fas fa-user me-1"></i>
                                        {{ $usuarioLogro->usuario->nombre ?? 'N/A' }} {{ $usuarioLogro->usuario->apellido ?? '' }}
                                        <span class="text-muted">
                                            ({{ $usuarioLogro->desbloqueado_en ? $usuarioLogro->desbloqueado_en->format('d/m/Y') : 'N/A' }})
                                        </span>
                                    </small>
                                </div>
                                @endforeach
                                @if($logro->usuariosLogros->count() > 6)
                                <div class="col-12">
                                    <small class="text-muted">
                                        ... y {{ $logro->usuariosLogros->count() - 6 }} usuarios más.
                                    </small>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="border-top pt-3 mt-4">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('logros.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i>Actualizar Logro
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validación de caracteres restantes
    const descripcionTextarea = document.getElementById('descripcion');
    const maxLength = 500;
    
    function updateCharCount() {
        const remaining = maxLength - descripcionTextarea.value.length;
        const helpText = descripcionTextarea.parentNode.querySelector('.form-text');
        if (helpText) {
            helpText.textContent = `Explica claramente qué debe hacer el usuario para obtener este logro. (${remaining} caracteres restantes)`;
            if (remaining < 50) {
                helpText.style.color = '#dc3545';
            } else {
                helpText.style.color = '';
            }
        }
    }
    
    descripcionTextarea.addEventListener('input', updateCharCount);
    updateCharCount();
});
</script>
@endpush