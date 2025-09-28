@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ $lenguaje->nombre }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('lenguajes.edit', $lenguaje) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Editar
            </a>
            <a href="{{ route('lenguajes.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Volver
            </a>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Lenguaje</h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $lenguaje->nombre }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Descripción</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $lenguaje->descripcion ?? 'Sin descripción' }}</p>
                    </div>

                    @if(isset($lenguaje->icono))
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Icono</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $lenguaje->icono }}</p>
                    </div>
                    @endif

                    @if(isset($lenguaje->color))
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Color</label>
                        <div class="mt-1 flex items-center">
                            <div class="w-6 h-6 rounded-full mr-2 lenguaje-color-circle" 
                                 data-bg-color="{{ $lenguaje->color }}"></div>
                            <span class="text-sm text-gray-900">{{ $lenguaje->color }}</span>
                        </div>
                    </div>
                    @endif

                    @if(isset($lenguaje->version))
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Versión</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $lenguaje->version }}</p>
                    </div>
                    @endif
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Estadísticas</h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Cursos Asociados</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $lenguaje->cursos()->count() }} cursos</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Desafíos Asociados</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $lenguaje->desafios()->count() }} desafíos</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Fecha de Creación</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $lenguaje->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Última Actualización</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $lenguaje->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($lenguaje->cursos()->count() > 0)
    <div class="mt-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Cursos de {{ $lenguaje->nombre }}</h3>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($lenguaje->cursos as $curso)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $curso->nombre }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $curso->categoria->nombre ?? 'Sin categoría' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if(isset($curso->estado))
                                    @if($curso->estado === 'activo')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Activo
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Inactivo
                                        </span>
                                    @endif
                                @else
                                    <span class="text-gray-500">Sin estado</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('cursos.show', $curso) }}" class="text-indigo-600 hover:text-indigo-900">Ver</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Aplicar colores dinámicos
    document.querySelectorAll('.lenguaje-color-circle').forEach(function(circle) {
        if (circle.dataset.bgColor) {
            circle.style.backgroundColor = circle.dataset.bgColor;
        }
    });
});
</script>
@endpush