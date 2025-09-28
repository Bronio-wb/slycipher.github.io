@extends('layouts.app')

@section('title', 'Crear Logro')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-trophy me-2"></i>Crear Nuevo Logro
                    </h4>
                    <a href="{{ route('logros.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('logros.store') }}" method="POST">
                        @csrf
                        
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
                                           value="{{ old('nombre') }}" 
                                           maxlength="100" 
                                           placeholder="Ej: Primer Paso, Maestro del Código..."
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
                                        <option value="progreso" {{ old('tipo') == 'progreso' ? 'selected' : '' }}>
                                            📈 Progreso
                                        </option>
                                        <option value="desafio" {{ old('tipo') == 'desafio' ? 'selected' : '' }}>
                                            🎯 Desafío
                                        </option>
                                        <option value="tiempo" {{ old('tipo') == 'tiempo' ? 'selected' : '' }}>
                                            ⏱️ Tiempo
                                        </option>
                                        <option value="especial" {{ old('tipo') == 'especial' ? 'selected' : '' }}>
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
                                      placeholder="Describe cómo se obtiene este logro y qué representa..."
                                      required>{{ old('descripcion') }}</textarea>
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
                                           value="{{ old('puntos_requeridos') }}" 
                                           min="0" 
                                           max="10000" 
                                           required>
                                    <div class="form-text">Puntos necesarios para desbloquear este logro (0 para logros especiales).</div>
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
                                        <option value="activo" {{ old('estado') == 'activo' ? 'selected' : '' }}>
                                            ✅ Activo
                                        </option>
                                        <option value="inactivo" {{ old('estado') == 'inactivo' ? 'selected' : '' }}>
                                            ❌ Inactivo
                                        </option>
                                        <option value="borrador" {{ old('estado') == 'borrador' ? 'selected' : '' }}>
                                            📝 Borrador
                                        </option>
                                    </select>
                                    @error('estado')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Criterios adicionales según el tipo -->
                        <div id="criterios-adicionales" class="mb-3" style="display: none;">
                            <label class="form-label fw-bold">
                                <i class="fas fa-cogs me-1"></i>Criterios Adicionales
                            </label>
                            <div class="bg-light p-3 rounded">
                                <div id="criterio-progreso" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="lecciones_completadas" class="form-label">Lecciones Completadas</label>
                                            <input type="number" class="form-control" id="lecciones_completadas" name="lecciones_completadas" min="1" max="100">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="cursos_completados" class="form-label">Cursos Completados</label>
                                            <input type="number" class="form-control" id="cursos_completados" name="cursos_completados" min="1" max="20">
                                        </div>
                                    </div>
                                </div>
                                <div id="criterio-desafio" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="desafios_completados" class="form-label">Desafíos Completados</label>
                                            <input type="number" class="form-control" id="desafios_completados" name="desafios_completados" min="1" max="50">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="racha_desafios" class="form-label">Racha de Desafíos</label>
                                            <input type="number" class="form-control" id="racha_desafios" name="racha_desafios" min="1" max="30">
                                        </div>
                                    </div>
                                </div>
                                <div id="criterio-tiempo" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="dias_consecutivos" class="form-label">Días Consecutivos</label>
                                            <input type="number" class="form-control" id="dias_consecutivos" name="dias_consecutivos" min="1" max="365">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="horas_estudio" class="form-label">Horas de Estudio</label>
                                            <input type="number" class="form-control" id="horas_estudio" name="horas_estudio" min="1" max="1000">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-top pt-3 mt-4">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('logros.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i>Crear Logro
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
    const tipoSelect = document.getElementById('tipo');
    const criteriosDiv = document.getElementById('criterios-adicionales');
    const criterioProgreso = document.getElementById('criterio-progreso');
    const criterioDesafio = document.getElementById('criterio-desafio');
    const criterioTiempo = document.getElementById('criterio-tiempo');
    const puntosInput = document.getElementById('puntos_requeridos');
    
    // Puntos sugeridos por tipo
    const puntosPorTipo = {
        'progreso': 100,
        'desafio': 150,
        'tiempo': 200,
        'especial': 500
    };
    
    tipoSelect.addEventListener('change', function() {
        // Ocultar todos los criterios
        criteriosDiv.style.display = 'none';
        criterioProgreso.style.display = 'none';
        criterioDesafio.style.display = 'none';
        criterioTiempo.style.display = 'none';
        
        // Mostrar criterios específicos según el tipo
        if (this.value) {
            criteriosDiv.style.display = 'block';
            
            switch(this.value) {
                case 'progreso':
                    criterioProgreso.style.display = 'block';
                    break;
                case 'desafio':
                    criterioDesafio.style.display = 'block';
                    break;
                case 'tiempo':
                    criterioTiempo.style.display = 'block';
                    break;
            }
            
            // Sugerir puntos
            if (puntosPorTipo[this.value] && !puntosInput.value) {
                puntosInput.value = puntosPorTipo[this.value];
            }
        }
    });
    
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