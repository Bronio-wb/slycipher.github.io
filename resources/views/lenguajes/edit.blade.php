@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Editar Lenguaje</h1>
        <a href="{{ route('lenguajes.show', $lenguaje) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Cancelar
        </a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <form method="POST" action="{{ route('lenguajes.update', $lenguaje) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="mb-4">
                            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre *</label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $lenguaje->nombre) }}" 
                                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm rounded-md @error('nombre') border-red-500 @else border-gray-300 @enderror" 
                                   required>
                            @error('nombre')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea name="descripcion" id="descripcion" rows="3" 
                                      class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm rounded-md @error('descripcion') border-red-500 @else border-gray-300 @enderror">{{ old('descripcion', $lenguaje->descripcion) }}</textarea>
                            @error('descripcion')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="icono" class="block text-sm font-medium text-gray-700">Icono</label>
                            <input type="text" name="icono" id="icono" value="{{ old('icono', $lenguaje->icono ?? '') }}" 
                                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm rounded-md @error('icono') border-red-500 @else border-gray-300 @enderror"
                                   placeholder="Ej: fab fa-php">
                            @error('icono')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Clase CSS del icono (Font Awesome, etc.)</p>
                        </div>
                    </div>

                    <div>
                        <div class="mb-4">
                            <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
                            <div class="mt-1 flex items-center space-x-2">
                                <input type="color" name="color" id="color" value="{{ old('color', $lenguaje->color ?? '#000000') }}" 
                                       class="h-10 w-16 border rounded-md @error('color') border-red-500 @else border-gray-300 @enderror">
                                <input type="text" name="color_text" id="color_text" value="{{ old('color', $lenguaje->color ?? '#000000') }}" 
                                       class="flex-1 focus:ring-indigo-500 focus:border-indigo-500 block shadow-sm sm:text-sm rounded-md @error('color') border-red-500 @else border-gray-300 @enderror"
                                       placeholder="#000000">
                            </div>
                            @error('color')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="version" class="block text-sm font-medium text-gray-700">Versión</label>
                            <input type="text" name="version" id="version" value="{{ old('version', $lenguaje->version ?? '') }}" 
                                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm rounded-md @error('version') border-red-500 @else border-gray-300 @enderror"
                                   placeholder="Ej: 8.1, 11, latest">
                            @error('version')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vista previa</label>
                            <div class="p-4 bg-gray-50 rounded-md">
                                <div class="flex items-center space-x-2">
                                    <i id="icon-preview" class="{{ $lenguaje->icono ?? 'fas fa-code' }}" style="color: {{ $lenguaje->color ?? '#000000' }}"></i>
                                    <span id="name-preview">{{ $lenguaje->nombre }}</span>
                                    <span id="version-preview" class="text-sm text-gray-500">{{ $lenguaje->version ?? '' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-6 space-x-3">
                    <a href="{{ route('lenguajes.show', $lenguaje) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Cancelar
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Actualizar Lenguaje
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const colorInput = document.getElementById('color');
    const colorTextInput = document.getElementById('color_text');
    const iconInput = document.getElementById('icono');
    const nameInput = document.getElementById('nombre');
    const versionInput = document.getElementById('version');
    
    const iconPreview = document.getElementById('icon-preview');
    const namePreview = document.getElementById('name-preview');
    const versionPreview = document.getElementById('version-preview');

    function updatePreview() {
        const color = colorInput.value;
        const icon = iconInput.value || 'fas fa-code';
        const name = nameInput.value || 'Lenguaje';
        const version = versionInput.value;

        iconPreview.className = icon;
        iconPreview.style.color = color;
        namePreview.textContent = name;
        versionPreview.textContent = version;
        colorTextInput.value = color;
    }

    colorInput.addEventListener('input', updatePreview);
    colorTextInput.addEventListener('input', function() {
        colorInput.value = this.value;
        updatePreview();
    });
    iconInput.addEventListener('input', updatePreview);
    nameInput.addEventListener('input', updatePreview);
    versionInput.addEventListener('input', updatePreview);
});
</script>
@endsection