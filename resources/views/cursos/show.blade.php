@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ $curso->nombre }}</h1>
        <div class="flex space-x-3">
            @auth
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'developer')
                    <a href="{{ route('cursos.edit', $curso) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Editar
                    </a>
                @endif
            @endauth
            <a href="{{ route('cursos.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Volver
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información principal del curso -->
        <div class="lg:col-span-2">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-2">Descripción</h2>
                        <p class="text-gray-700 leading-relaxed">{{ $curso->descripcion }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Curso</h3>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Lenguaje:</span>
                                    <span class="text-sm text-gray-900">{{ $curso->lenguaje->nombre ?? 'Sin lenguaje' }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Categoría:</span>
                                    <span class="text-sm text-gray-900">{{ $curso->categoria->nombre ?? 'Sin categoría' }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Dificultad:</span>
                                    <span class="text-sm text-gray-900">
                                        @switch($curso->dificultad)
                                            @case('principiante')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    Principiante
                                                </span>
                                                @break
                                            @case('intermedio')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Intermedio
                                                </span>
                                                @break
                                            @case('avanzado')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                    Avanzado
                                                </span>
                                                @break
                                            @default
                                                <span class="text-gray-500">Sin definir</span>
                                        @endswitch
                                    </span>
                                </div>
                                
                                @if($curso->duracion_horas)
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Duración:</span>
                                    <span class="text-sm text-gray-900">{{ $curso->duracion_horas }} horas</span>
                                </div>
                                @endif

                                @if(isset($curso->estado))
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Estado:</span>
                                    <span class="text-sm text-gray-900">
                                        @if($curso->estado === 'activo')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                Activo
                                            </span>
                                        @elseif($curso->estado === 'inactivo')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                Inactivo
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ ucfirst($curso->estado) }}
                                            </span>
                                        @endif
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Estadísticas</h3>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Lecciones:</span>
                                    <span class="text-sm text-gray-900">{{ $curso->lecciones()->count() }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Estudiantes:</span>
                                    <span class="text-sm text-gray-900">{{ $curso->inscripciones()->count() }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Creado:</span>
                                    <span class="text-sm text-gray-900">{{ $curso->created_at->format('d/m/Y') }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Actualizado:</span>
                                    <span class="text-sm text-gray-900">{{ $curso->updated_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lecciones del curso -->
            @if($curso->lecciones()->count() > 0)
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Lecciones del Curso</h3>
                    <div class="space-y-3">
                        @foreach($curso->lecciones as $leccion)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">{{ $leccion->titulo }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($leccion->contenido, 150) }}</p>
                                    <div class="mt-2 flex items-center space-x-4 text-xs text-gray-500">
                                        <span>Orden: {{ $leccion->orden }}</span>
                                        @if($leccion->duracion_minutos)
                                            <span>{{ $leccion->duracion_minutos }} min</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <a href="{{ route('lecciones.show', $leccion) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                        Ver Lección
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Acciones Rápidas</h3>
                    
                    @auth
                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'developer')
                            <div class="space-y-2">
                                <a href="{{ route('lecciones.create', ['curso_id' => $curso->course_id]) }}" 
                                   class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-center block">
                                    Agregar Lección
                                </a>
                                
                                <a href="{{ route('cursos.edit', $curso) }}" 
                                   class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center block">
                                    Editar Curso
                                </a>
                            </div>
                        @endif
                        
                        @if(auth()->user()->role === 'student')
                            @php
                                $inscrito = $curso->inscripciones()->where('user_id', auth()->id())->exists();
                            @endphp
                            @if(!$inscrito)
                                <form method="POST" action="{{ route('cursos.inscribir', $curso) }}">
                                    @csrf
                                    <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        Inscribirse al Curso
                                    </button>
                                </form>
                            @else
                                <div class="text-center">
                                    <span class="px-3 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                        Ya estás inscrito
                                    </span>
                                </div>
                            @endif
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Información adicional -->
            @if($curso->lenguaje || $curso->categoria)
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Relacionado</h3>
                    
                    @if($curso->lenguaje)
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Lenguaje</h4>
                        <div class="flex items-center space-x-2">
                            @if(isset($curso->lenguaje->icono) && isset($curso->lenguaje->color))
                                <i class="{{ $curso->lenguaje->icono }}" style="color: {{ $curso->lenguaje->color }}"></i>
                            @endif
                            <span class="text-sm text-gray-900">{{ $curso->lenguaje->nombre }}</span>
                        </div>
                    </div>
                    @endif
                    
                    @if($curso->categoria)
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Categoría</h4>
                        <div class="flex items-center space-x-2">
                            @if(isset($curso->categoria->icono) && isset($curso->categoria->color))
                                <i class="{{ $curso->categoria->icono }}" style="color: {{ $curso->categoria->color }}"></i>
                            @endif
                            <span class="text-sm text-gray-900">{{ $curso->categoria->nombre }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection