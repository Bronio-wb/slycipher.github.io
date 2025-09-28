@extends('layouts.app')

@section('title', 'Crear Lenguaje')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-plus me-2"></i>Crear Nuevo Lenguaje
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.lenguajes.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <!-- Información básica -->
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">
                                        <i class="fas fa-code me-1"></i>Nombre <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('nombre') is-invalid @enderror" 
                                           id="nombre" 
                                           name="nombre" 
                                           value="{{ old('nombre') }}" 
                                           required
                                           placeholder="Ej: JavaScript, Python, Java">
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="version" class="form-label">
                                        <i class="fas fa-tag me-1"></i>Versión
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('version') is-invalid @enderror" 
                                           id="version" 
                                           name="version" 
                                           value="{{ old('version') }}"
                                           placeholder="Ej: ES6, 3.9, 17">
                                    @error('version')
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
                                              placeholder="Descripción del lenguaje de programación">{{ old('descripcion') }}</textarea>
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
                                                   value="{{ old('icono') }}"
                                                   placeholder="fab fa-js-square">
                                            @error('icono')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Usa clases de FontAwesome (ej: fab fa-js-square)
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
                                                   value="{{ old('color', '#007bff') }}">
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
                                                <div class="display-4">💻</div>
                                            </div>
                                            <div id="preview-name" class="h5 text-primary">Nombre del lenguaje</div>
                                            <div id="preview-version" class="small text-muted"></div>
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
                                                <option value="activo" {{ old('estado') === 'activo' ? 'selected' : '' }}>
                                                    ✅ Activo
                                                </option>
                                                <option value="inactivo" {{ old('estado') === 'inactivo' ? 'selected' : '' }}>
                                                    ❌ Inactivo
                                                </option>
                                            </select>
                                            @error('estado')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="border-top pt-4 mt-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="{{ route('admin.lenguajes.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>Cancelar
                                    </a>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-1"></i>Crear Lenguaje
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
    const versionInput = document.getElementById('version');
    const iconoInput = document.getElementById('icono');
    const colorInput = document.getElementById('color');
    const previewName = document.getElementById('preview-name');
    const previewVersion = document.getElementById('preview-version');
    const previewIcon = document.getElementById('preview-icon');

    // Actualizar vista previa del nombre
    nombreInput.addEventListener('input', function() {
        previewName.textContent = this.value || 'Nombre del lenguaje';
    });

    // Actualizar vista previa de la versión
    versionInput.addEventListener('input', function() {
        previewVersion.textContent = this.value ? `v${this.value}` : '';
    });

    // Actualizar vista previa del icono
    iconoInput.addEventListener('input', function() {
        const iconClass = this.value;
        if (iconClass) {
            previewIcon.innerHTML = `<i class="${iconClass} fa-3x" style="color: ${colorInput.value};"></i>`;
        } else {
            previewIcon.innerHTML = '<div class="display-4">💻</div>';
        }
    });

    // Actualizar vista previa del color
    colorInput.addEventListener('input', function() {
        const iconClass = iconoInput.value;
        if (iconClass) {
            previewIcon.innerHTML = `<i class="${iconClass} fa-3x" style="color: ${this.value};"></i>`;
        }
        previewName.style.color = this.value;
    });
});

// Iconos comunes para lenguajes de programación
const iconosLenguajes = [
    'fab fa-js-square',
    'fab fa-python',
    'fab fa-java',
    'fab fa-php',
    'fab fa-html5',
    'fab fa-css3-alt',
    'fab fa-react',
    'fab fa-vue',
    'fab fa-angular',
    'fab fa-node-js',
    'fas fa-code',
    'fas fa-file-code'
];

// Añadir botones de iconos comunes
document.addEventListener('DOMContentLoaded', function() {
    const iconoInput = document.getElementById('icono');
    const iconosContainer = document.createElement('div');
    iconosContainer.className = 'mt-2';
    iconosContainer.innerHTML = '<small class="text-muted">Iconos comunes:</small><br>';
    
    iconosLenguajes.forEach(icono => {
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