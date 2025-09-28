@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Editar Curso</h1>
        <a href="{{ route('cursos.show', $curso) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Cancelar
        </a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <form method="POST" action="{{ route('cursos.update', $curso) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="mb-4">
                            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre del Curso *</label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $curso->nombre) }}" 
                                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" 
                                   required>
                            @error('nombre')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción *</label>
                            <textarea name="descripcion" id="descripcion" rows="4" 
                                      class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" 
                                      required>{{ old('descripcion', $curso->descripcion) }}</textarea>
                            @error('descripcion')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="language_id" class="block text-sm font-medium text-gray-700">Lenguaje *</label>
                            <select name="language_id" id="language_id" 
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                                    required>
                                <option value="">Seleccionar lenguaje</option>
                                @foreach($lenguajes as $lenguaje)
                                    <option value="{{ $lenguaje->language_id }}" 
                                            {{ old('language_id', $curso->language_id) == $lenguaje->language_id ? 'selected' : '' }}>
                                        {{ $lenguaje->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('language_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Categoría *</label>
                            <select name="category_id" id="category_id" 
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                                    required>
                                <option value="">Seleccionar categoría</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->category_id }}" 
                                            {{ old('category_id', $curso->category_id) == $categoria->category_id ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <div class="mb-4">
                            <label for="dificultad" class="block text-sm font-medium text-gray-700">Dificultad *</label>
                            <select name="dificultad" id="dificultad" 
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                                    required>
                                <option value="">Seleccionar dificultad</option>
                                <option value="principiante" {{ old('dificultad', $curso->dificultad) == 'principiante' ? 'selected' : '' }}>Principiante</option>
                                <option value="intermedio" {{ old('dificultad', $curso->dificultad) == 'intermedio' ? 'selected' : '' }}>Intermedio</option>
                                <option value="avanzado" {{ old('dificultad', $curso->dificultad) == 'avanzado' ? 'selected' : '' }}>Avanzado</option>
                            </select>
                            @error('dificultad')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="duracion_horas" class="block text-sm font-medium text-gray-700">Duración (horas)</label>
                            <input type="number" name="duracion_horas" id="duracion_horas" value="{{ old('duracion_horas', $curso->duracion_horas) }}" 
                                   min="1" max="1000" step="0.5"
                                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('duracion_horas')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        @if(isset($curso->estado))
                        <div class="mb-4">
                            <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
                            <select name="estado" id="estado" 
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="activo" {{ old('estado', $curso->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ old('estado', $curso->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                <option value="borrador" {{ old('estado', $curso->estado) == 'borrador' ? 'selected' : '' }}>Borrador</option>
                            </select>
                            @error('estado')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        @endif

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vista previa</label>
                            <div class="p-4 bg-gray-50 rounded-md">
                                <h3 id="preview-nombre" class="font-medium text-gray-900">{{ $curso->nombre }}</h3>
                                <p id="preview-descripcion" class="text-sm text-gray-600 mt-1">{{ Str::limit($curso->descripcion, 100) }}</p>
                                <div class="mt-2 flex items-center space-x-2 text-xs text-gray-500">
                                    <span id="preview-lenguaje">{{ $curso->lenguaje->nombre ?? 'Lenguaje' }}</span>
                                    <span>•</span>
                                    <span id="preview-categoria">{{ $curso->categoria->nombre ?? 'Categoría' }}</span>
                                    <span>•</span>
                                    <span id="preview-dificultad">{{ ucfirst($curso->dificultad) ?? 'Dificultad' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estadísticas Actuales</label>
                            <div class="p-4 bg-blue-50 rounded-md">
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-blue-900">Lecciones:</span>
                                        <span class="text-blue-700">{{ $curso->lecciones()->count() }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-blue-900">Estudiantes:</span>
                                        <span class="text-blue-700">{{ $curso->inscripciones()->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-6 space-x-3">
                    <a href="{{ route('cursos.show', $curso) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Cancelar
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Actualizar Curso
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($curso->lecciones()->count() > 0)
    <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Lecciones del Curso ({{ $curso->lecciones()->count() }})</h3>
            <div class="space-y-2">
                @foreach($curso->lecciones->sortBy('orden') as $leccion)
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-md">
                    <div>
                        <span class="font-medium text-gray-900">{{ $leccion->orden }}. {{ $leccion->titulo }}</span>
                        @if($leccion->duracion_minutos)
                            <span class="text-sm text-gray-500 ml-2">({{ $leccion->duracion_minutos }} min)</span>
                        @endif
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('lecciones.show', $leccion) }}" class="text-blue-600 hover:text-blue-800 text-sm">Ver</a>
                        <a href="{{ route('lecciones.edit', $leccion) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">Editar</a>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4">
                <a href="{{ route('lecciones.create', ['curso_id' => $curso->course_id]) }}" 
                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                    Agregar Nueva Lección
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nombreInput = document.getElementById('nombre');
    const descripcionInput = document.getElementById('descripcion');
    const lenguajeSelect = document.getElementById('language_id');
    const categoriaSelect = document.getElementById('category_id');
    const dificultadSelect = document.getElementById('dificultad');
    
    const previewNombre = document.getElementById('preview-nombre');
    const previewDescripcion = document.getElementById('preview-descripcion');
    const previewLenguaje = document.getElementById('preview-lenguaje');
    const previewCategoria = document.getElementById('preview-categoria');
    const previewDificultad = document.getElementById('preview-dificultad');

    function updatePreview() {
        previewNombre.textContent = nombreInput.value || 'Nombre del curso';
        previewDescripcion.textContent = descripcionInput.value.substring(0, 100) + (descripcionInput.value.length > 100 ? '...' : '') || 'Descripción del curso';
        previewLenguaje.textContent = lenguajeSelect.options[lenguajeSelect.selectedIndex]?.text || 'Lenguaje';
        previewCategoria.textContent = categoriaSelect.options[categoriaSelect.selectedIndex]?.text || 'Categoría';
        previewDificultad.textContent = dificultadSelect.value ? dificultadSelect.value.charAt(0).toUpperCase() + dificultadSelect.value.slice(1) : 'Dificultad';
    }

    nombreInput.addEventListener('input', updatePreview);
    descripcionInput.addEventListener('input', updatePreview);
    lenguajeSelect.addEventListener('change', updatePreview);
    categoriaSelect.addEventListener('change', updatePreview);
    dificultadSelect.addEventListener('change', updatePreview);
});
</script>
@endsection