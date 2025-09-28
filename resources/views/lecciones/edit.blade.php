@extends('layouts.app')

@section('title', 'Editar Lección')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Editar Lección
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route(Auth::user()->rol === 'admin' ? 'admin.lecciones.update' : 'developer.lecciones.update', $leccione->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Información básica -->
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="titulo" class="form-label">
                                        <i class="fas fa-heading me-1"></i>Título <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('titulo') is-invalid @enderror" 
                                           id="titulo" 
                                           name="titulo" 
                                           value="{{ old('titulo', $leccione->titulo) }}" 
                                           required
                                           placeholder="Ingresa el título de la lección">
                                    @error('titulo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">
                                        <i class="fas fa-file-text me-1"></i>Descripción
                                    </label>
                                    <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                              id="descripcion" 
                                              name="descripcion" 
                                              rows="3"
                                              placeholder="Descripción breve de la lección">{{ old('descripcion', $leccione->descripcion) }}</textarea>
                                    @error('descripcion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="contenido" class="form-label">
                                        <i class="fas fa-file-alt me-1"></i>Contenido de la Lección
                                    </label>
                                    <textarea class="form-control @error('contenido') is-invalid @enderror" 
                                              id="contenido" 
                                              name="contenido" 
                                              rows="10"
                                              placeholder="Contenido completo de la lección...">{{ old('contenido', $leccione->contenido) }}</textarea>
                                    @error('contenido')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Puedes usar texto plano. Los saltos de línea se conservarán automáticamente.
                                    </div>
                                </div>
                            </div>

                            <!-- Configuración -->
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="mb-0">
                                            <i class="fas fa-cog me-1"></i>Configuración
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="curso_id" class="form-label">
                                                <i class="fas fa-graduation-cap me-1"></i>Curso <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('curso_id') is-invalid @enderror" 
                                                    id="curso_id" 
                                                    name="curso_id" 
                                                    required>
                                                <option value="">Selecciona un curso</option>
                                                @foreach($cursos as $curso)
                                                    <option value="{{ $curso->id }}" 
                                                            {{ old('curso_id', $leccione->curso_id) == $curso->id ? 'selected' : '' }}>
                                                        {{ $curso->titulo }}
                                                        @if($curso->lenguaje)
                                                            - {{ $curso->lenguaje->nombre }}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('curso_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="orden" class="form-label">
                                                <i class="fas fa-sort-numeric-up me-1"></i>Orden <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" 
                                                   class="form-control @error('orden') is-invalid @enderror" 
                                                   id="orden" 
                                                   name="orden" 
                                                   value="{{ old('orden', $leccione->orden) }}" 
                                                   min="1"
                                                   required
                                                   placeholder="1">
                                            @error('orden')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Orden de la lección dentro del curso
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="estado" class="form-label">
                                                <i class="fas fa-toggle-on me-1"></i>Estado <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('estado') is-invalid @enderror" 
                                                    id="estado" 
                                                    name="estado" 
                                                    required>
                                                <option value="borrador" {{ old('estado', $leccione->estado) === 'borrador' ? 'selected' : '' }}>
                                                    📝 Borrador
                                                </option>
                                                <option value="activo" {{ old('estado', $leccione->estado) === 'activo' ? 'selected' : '' }}>
                                                    ✅ Activo
                                                </option>
                                                <option value="inactivo" {{ old('estado', $leccione->estado) === 'inactivo' ? 'selected' : '' }}>
                                                    ❌ Inactivo
                                                </option>
                                            </select>
                                            @error('estado')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Información adicional -->
                                        <hr>
                                        <div class="small text-muted">
                                            <div><strong>Creado:</strong><br>{{ $leccione->created_at->format('d/m/Y H:i') }}</div>
                                            <div class="mt-2"><strong>Actualizado:</strong><br>{{ $leccione->updated_at->format('d/m/Y H:i') }}</div>
                                            @if($leccione->progresos->count() > 0)
                                                <div class="mt-2 text-info">
                                                    <strong>Estudiantes:</strong><br>{{ $leccione->progresos->count() }} inscritos
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="border-top pt-4 mt-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="{{ route(Auth::user()->rol === 'admin' ? 'admin.lecciones.show' : 'developer.lecciones.show', $leccione->id) }}" class="btn btn-secondary">
                                        <i class="fas fa-eye me-1"></i>Ver Detalles
                                    </a>
                                    <a href="{{ route(Auth::user()->rol === 'admin' ? 'admin.lecciones.index' : 'developer.lecciones.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>Cancelar
                                    </a>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-save me-1"></i>Actualizar Lección
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview del contenido -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">
                    <i class="fas fa-eye me-2"></i>Vista previa del contenido
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="preview-content" class="border rounded p-3 bg-light">
                    <!-- El contenido se cargará aquí -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-resize textarea
    const textarea = document.getElementById('contenido');
    if (textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    }

    // Preview functionality
    const previewBtn = document.createElement('button');
    previewBtn.type = 'button';
    previewBtn.className = 'btn btn-info btn-sm mt-2';
    previewBtn.innerHTML = '<i class="fas fa-eye me-1"></i>Vista Previa';
    previewBtn.onclick = function() {
        const content = document.getElementById('contenido').value;
        const previewContent = document.getElementById('preview-content');
        
        if (content.trim()) {
            previewContent.innerHTML = content.replace(/\n/g, '<br>');
        } else {
            previewContent.innerHTML = '<em class="text-muted">Sin contenido para mostrar</em>';
        }
        
        const modal = new bootstrap.Modal(document.getElementById('previewModal'));
        modal.show();
    };
    
    // Add preview button after the textarea
    if (textarea) {
        textarea.parentNode.appendChild(previewBtn);
    }
});
</script>
@endpush