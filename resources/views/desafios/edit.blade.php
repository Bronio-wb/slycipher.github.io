@extends('layouts.app')

@section('title', 'Editar Desafío')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Editar Desafío
                    </h4>
                    <a href="{{ route('desafios.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('desafios.update', $desafio->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="titulo" class="form-label fw-bold">
                                        <i class="fas fa-heading me-1"></i>Título del Desafío *
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('titulo') is-invalid @enderror" 
                                           id="titulo" 
                                           name="titulo" 
                                           value="{{ old('titulo', $desafio->titulo) }}" 
                                           maxlength="200" 
                                           required>
                                    @error('titulo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="lenguaje_id" class="form-label fw-bold">
                                        <i class="fas fa-code me-1"></i>Lenguaje *
                                    </label>
                                    <select class="form-select @error('lenguaje_id') is-invalid @enderror" 
                                            id="lenguaje_id" 
                                            name="lenguaje_id" 
                                            required>
                                        <option value="">Seleccionar lenguaje</option>
                                        @foreach($lenguajes as $lenguaje)
                                            <option value="{{ $lenguaje->id }}" 
                                                    {{ old('lenguaje_id', $desafio->lenguaje_id) == $lenguaje->id ? 'selected' : '' }}>
                                                {{ $lenguaje->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('lenguaje_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label fw-bold">
                                <i class="fas fa-file-text me-1"></i>Descripción del Desafío *
                            </label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      id="descripcion" 
                                      name="descripcion" 
                                      rows="5" 
                                      maxlength="1000" 
                                      required>{{ old('descripcion', $desafio->descripcion) }}</textarea>
                            <div class="form-text">Describe claramente qué debe resolver el usuario.</div>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="codigo_inicial" class="form-label fw-bold">
                                <i class="fas fa-code me-1"></i>Código Inicial
                            </label>
                            <textarea class="form-control @error('codigo_inicial') is-invalid @enderror" 
                                      id="codigo_inicial" 
                                      name="codigo_inicial" 
                                      rows="6" 
                                      style="font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;">{{ old('codigo_inicial', $desafio->codigo_inicial) }}</textarea>
                            <div class="form-text">Código base o plantilla para iniciar el desafío.</div>
                            @error('codigo_inicial')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="solucion" class="form-label fw-bold">
                                <i class="fas fa-check-circle me-1"></i>Solución *
                            </label>
                            <textarea class="form-control @error('solucion') is-invalid @enderror" 
                                      id="solucion" 
                                      name="solucion" 
                                      rows="8" 
                                      style="font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;" 
                                      required>{{ old('solucion', $desafio->solucion) }}</textarea>
                            <div class="form-text">Código de la solución completa y funcional.</div>
                            @error('solucion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="dificultad" class="form-label fw-bold">
                                        <i class="fas fa-signal me-1"></i>Dificultad *
                                    </label>
                                    <select class="form-select @error('dificultad') is-invalid @enderror" 
                                            id="dificultad" 
                                            name="dificultad" 
                                            required>
                                        <option value="">Seleccionar dificultad</option>
                                        <option value="principiante" {{ old('dificultad', $desafio->dificultad) == 'principiante' ? 'selected' : '' }}>
                                            🟢 Principiante
                                        </option>
                                        <option value="intermedio" {{ old('dificultad', $desafio->dificultad) == 'intermedio' ? 'selected' : '' }}>
                                            🟡 Intermedio
                                        </option>
                                        <option value="avanzado" {{ old('dificultad', $desafio->dificultad) == 'avanzado' ? 'selected' : '' }}>
                                            🔴 Avanzado
                                        </option>
                                        <option value="experto" {{ old('dificultad', $desafio->dificultad) == 'experto' ? 'selected' : '' }}>
                                            ⚫ Experto
                                        </option>
                                    </select>
                                    @error('dificultad')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="puntos" class="form-label fw-bold">
                                        <i class="fas fa-trophy me-1"></i>Puntos a Otorgar *
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('puntos') is-invalid @enderror" 
                                           id="puntos" 
                                           name="puntos" 
                                           value="{{ old('puntos', $desafio->puntos) }}" 
                                           min="10" 
                                           max="500" 
                                           required>
                                    <div class="form-text">Entre 10 y 500 puntos según dificultad.</div>
                                    @error('puntos')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="estado" class="form-label fw-bold">
                                        <i class="fas fa-toggle-on me-1"></i>Estado *
                                    </label>
                                    <select class="form-select @error('estado') is-invalid @enderror" 
                                            id="estado" 
                                            name="estado" 
                                            required>
                                        <option value="activo" {{ old('estado', $desafio->estado) == 'activo' ? 'selected' : '' }}>
                                            ✅ Activo
                                        </option>
                                        <option value="inactivo" {{ old('estado', $desafio->estado) == 'inactivo' ? 'selected' : '' }}>
                                            ❌ Inactivo
                                        </option>
                                        <option value="borrador" {{ old('estado', $desafio->estado) == 'borrador' ? 'selected' : '' }}>
                                            📝 Borrador
                                        </option>
                                    </select>
                                    @error('estado')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="border-top pt-3 mt-4">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('desafios.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i>Actualizar Desafío
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
    // Actualizar puntos sugeridos según dificultad
    const dificultadSelect = document.getElementById('dificultad');
    const puntosInput = document.getElementById('puntos');
    
    const puntosPorDificultad = {
        'principiante': 50,
        'intermedio': 100,
        'avanzado': 200,
        'experto': 350
    };
    
    dificultadSelect.addEventListener('change', function() {
        if (this.value && puntosPorDificultad[this.value]) {
            puntosInput.value = puntosPorDificultad[this.value];
        }
    });
    
    // Validación de caracteres restantes
    const descripcionTextarea = document.getElementById('descripcion');
    const maxLength = 1000;
    
    function updateCharCount() {
        const remaining = maxLength - descripcionTextarea.value.length;
        const helpText = descripcionTextarea.parentNode.querySelector('.form-text');
        if (helpText) {
            helpText.textContent = `Describe claramente qué debe resolver el usuario. (${remaining} caracteres restantes)`;
            if (remaining < 100) {
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