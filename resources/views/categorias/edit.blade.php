@extends('layouts.app')

@section('title', 'Editar Categoría')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Editar Categoría
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.categorias.update', $categoria->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Información básica -->
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">
                                        <i class="fas fa-tag me-1"></i>Nombre <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('nombre') is-invalid @enderror" 
                                           id="nombre" 
                                           name="nombre" 
                                           value="{{ old('nombre', $categoria->nombre) }}" 
                                           required
                                           placeholder="Ingresa el nombre de la categoría">
                                    @error('nombre')
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
                                              rows="4"
                                              placeholder="Descripción de la categoría">{{ old('descripcion', $categoria->descripcion) }}</textarea>
                                    @error('descripcion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="icono" class="form-label">
                                                <i class="fas fa-icons me-1"></i>Icono
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('icono') is-invalid @enderror" 
                                                   id="icono" 
                                                   name="icono" 
                                                   value="{{ old('icono', $categoria->icono) }}"
                                                   placeholder="fas fa-folder">
                                            @error('icono')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Usa clases de FontAwesome (ej: fas fa-folder)
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="color" class="form-label">
                                                <i class="fas fa-palette me-1"></i>Color
                                            </label>
                                            <input type="color" 
                                                   class="form-control form-control-color @error('color') is-invalid @enderror" 
                                                   id="color" 
                                                   name="color" 
                                                   value="{{ old('color', $categoria->color ?? '#007bff') }}">
                                            @error('color')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Vista previa y configuración -->
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="mb-0">
                                            <i class="fas fa-eye me-1"></i>Vista Previa
                                        </h6>
                                    </div>
                                    <div class="card-body text-center">
                                        <div id="preview-container" class="mb-3">
                                            <div id="preview-icon" class="mb-2">
                                                @if($categoria->icono)
                                                    <i class="{{ $categoria->icono }} fa-3x text-primary"></i>
                                                @else
                                                    <div class="display-4">📁</div>
                                                @endif
                                            </div>
                                            <div id="preview-name" class="h5 text-primary">{{ $categoria->nombre }}</div>
                                        </div>
                                        
                                        <hr>
                                        
                                        <div class="mb-3">
                                            <label for="estado" class="form-label">
                                                <i class="fas fa-toggle-on me-1"></i>Estado <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('estado') is-invalid @enderror" 
                                                    id="estado" 
                                                    name="estado" 
                                                    required>
                                                <option value="activo" {{ old('estado', $categoria->estado) === 'activo' ? 'selected' : '' }}>
                                                    ✅ Activo
                                                </option>
                                                <option value="inactivo" {{ old('estado', $categoria->estado) === 'inactivo' ? 'selected' : '' }}>
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
                                            <div><strong>Creado:</strong><br>{{ $categoria->created_at->format('d/m/Y H:i') }}</div>
                                            <div class="mt-2"><strong>Actualizado:</strong><br>{{ $categoria->updated_at->format('d/m/Y H:i') }}</div>
                                            @if($categoria->cursos->count() > 0)
                                                <div class="mt-2 text-info">
                                                    <strong>Cursos:</strong><br>{{ $categoria->cursos->count() }} asignados
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
                                    <a href="{{ route('admin.categorias.show', $categoria->id) }}" class="btn btn-secondary">
                                        <i class="fas fa-eye me-1"></i>Ver Detalles
                                    </a>
                                    <a href="{{ route('admin.categorias.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>Cancelar
                                    </a>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-save me-1"></i>Actualizar Categoría
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nombreInput = document.getElementById('nombre');
    const iconoInput = document.getElementById('icono');
    const colorInput = document.getElementById('color');
    const previewName = document.getElementById('preview-name');
    const previewIcon = document.getElementById('preview-icon');

    // Actualizar vista previa del nombre
    nombreInput.addEventListener('input', function() {
        previewName.textContent = this.value || 'Nombre de la categoría';
    });

    // Actualizar vista previa del icono
    iconoInput.addEventListener('input', function() {
        const iconClass = this.value || 'fas fa-folder';
        if (this.value) {
            previewIcon.innerHTML = `<i class="${iconClass} fa-3x" style="color: ${colorInput.value};"></i>`;
        } else {
            previewIcon.innerHTML = '<div class="display-4">📁</div>';
        }
    });

    // Actualizar vista previa del color
    colorInput.addEventListener('input', function() {
        const iconClass = iconoInput.value || 'fas fa-folder';
        if (iconoInput.value) {
            previewIcon.innerHTML = `<i class="${iconClass} fa-3x" style="color: ${this.value};"></i>`;
        }
        previewName.style.color = this.value;
    });

    // Inicializar vista previa
    if (iconoInput.value) {
        previewIcon.innerHTML = `<i class="${iconoInput.value} fa-3x" style="color: ${colorInput.value};"></i>`;
    }
    previewName.style.color = colorInput.value;
});

// Iconos comunes para sugerir
const iconosSugeridos = [
    'fas fa-folder',
    'fas fa-code',
    'fas fa-laptop-code',
    'fas fa-database',
    'fas fa-server',
    'fas fa-mobile-alt',
    'fas fa-paint-brush',
    'fas fa-chart-bar',
    'fas fa-shield-alt',
    'fas fa-cogs',
    'fas fa-globe',
    'fas fa-gamepad'
];

// Añadir botones de iconos comunes
document.addEventListener('DOMContentLoaded', function() {
    const iconoInput = document.getElementById('icono');
    const iconosContainer = document.createElement('div');
    iconosContainer.className = 'mt-2';
    iconosContainer.innerHTML = '<small class="text-muted">Iconos comunes:</small><br>';
    
    iconosSugeridos.forEach(icono => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn btn-outline-secondary btn-sm me-1 mb-1';
        btn.innerHTML = `<i class="${icono}"></i>`;
        btn.title = icono;
        btn.onclick = function() {
            iconoInput.value = icono;
            iconoInput.dispatchEvent(new Event('input'));
        };
        iconosContainer.appendChild(btn);
    });
    
    iconoInput.parentNode.appendChild(iconosContainer);
});
</script>
@endpush