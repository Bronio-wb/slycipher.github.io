@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Perfil de Usuario</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Editar
            </a>
            <a href="{{ route('admin.usuarios.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Volver
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información principal del usuario -->
        <div class="lg:col-span-2">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="flex-shrink-0">
                            <div class="h-20 w-20 rounded-full bg-blue-500 flex items-center justify-center">
                                <span class="text-white font-bold text-2xl">
                                    {{ strtoupper(substr($usuario->name, 0, 1)) }}{{ strlen($usuario->name) > 1 ? strtoupper(substr(explode(' ', $usuario->name)[1] ?? $usuario->name, 0, 1)) : '' }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $usuario->name }}</h2>
                            <p class="text-gray-600">{{ $usuario->email }}</p>
                            <div class="mt-2">
                                @switch($usuario->role)
                                    @case('admin')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-crown mr-1"></i> Administrador
                                        </span>
                                        @break
                                    @case('developer')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-code mr-1"></i> Desarrollador
                                        </span>
                                        @break
                                    @case('student')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-graduation-cap mr-1"></i> Estudiante
                                        </span>
                                        @break
                                    @default
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                            {{ ucfirst($usuario->role) }}
                                        </span>
                                @endswitch
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Información Personal</h3>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Nombre:</span>
                                    <span class="text-sm text-gray-900">{{ $usuario->name }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Email:</span>
                                    <span class="text-sm text-gray-900">{{ $usuario->email }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Estado del Email:</span>
                                    <span class="text-sm">
                                        @if($usuario->email_verified_at)
                                            <span class="text-green-600 font-medium">
                                                <i class="fas fa-check-circle mr-1"></i>Verificado
                                            </span>
                                        @else
                                            <span class="text-red-600 font-medium">
                                                <i class="fas fa-times-circle mr-1"></i>No verificado
                                            </span>
                                        @endif
                                    </span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Rol:</span>
                                    <span class="text-sm text-gray-900">{{ ucfirst($usuario->role) }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Sistema</h3>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Registrado:</span>
                                    <span class="text-sm text-gray-900">{{ $usuario->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">Última actualización:</span>
                                    <span class="text-sm text-gray-900">{{ $usuario->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                                
                                <div class="flex justify-between">
                                    <span class="text-sm font-medium text-gray-500">ID de usuario:</span>
                                    <span class="text-sm text-gray-900 font-mono">#{{ $usuario->id }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas de actividad -->
            @if($usuario->role === 'student')
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Estadísticas de Aprendizaje</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ $usuario->inscripciones()->count() }}</div>
                            <div class="text-sm text-blue-800">Cursos Inscritos</div>
                        </div>
                        
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $usuario->progreso()->where('completado', true)->count() }}</div>
                            <div class="text-sm text-green-800">Lecciones Completadas</div>
                        </div>
                        
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">{{ $usuario->logros()->count() }}</div>
                            <div class="text-sm text-purple-800">Logros Obtenidos</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Cursos inscritos (para estudiantes) -->
            @if($usuario->role === 'student' && $usuario->inscripciones()->count() > 0)
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Cursos Inscritos</h3>
                    <div class="space-y-3">
                        @foreach($usuario->inscripciones as $inscripcion)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">{{ $inscripcion->curso->nombre }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($inscripcion->curso->descripcion, 100) }}</p>
                                    <div class="mt-2 flex items-center space-x-4 text-xs text-gray-500">
                                        <span>{{ $inscripcion->curso->dificultad }}</span>
                                        <span>{{ $inscripcion->created_at->format('d/m/Y') }}</span>
                                        @if($inscripcion->progreso)
                                            <span class="text-green-600">{{ $inscripcion->progreso }}% completado</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <a href="{{ route('cursos.show', $inscripcion->curso) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                        Ver Curso
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

        <!-- Sidebar con acciones -->
        <div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Acciones</h3>
                    
                    <div class="space-y-3">
                        <a href="{{ route('admin.usuarios.edit', $usuario) }}" 
                           class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center block">
                            Editar Usuario
                        </a>
                        
                        @if(!$usuario->email_verified_at)
                        <form method="POST" action="{{ route('admin.usuarios.verify-email', $usuario) }}">
                            @csrf
                            <button type="submit" class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Verificar Email
                            </button>
                        </form>
                        @endif
                        
                        <form method="POST" action="{{ route('admin.usuarios.reset-password', $usuario) }}">
                            @csrf
                            <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded"
                                    onclick="return confirm('¿Enviar email para restablecer contraseña?')">
                                Restablecer Contraseña
                            </button>
                        </form>
                        
                        @if($usuario->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.usuarios.destroy', $usuario) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                    onclick="return confirm('¿Estás seguro de que quieres eliminar este usuario? Esta acción no se puede deshacer.')">
                                Eliminar Usuario
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actividad reciente -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Información Adicional</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Tiempo en la plataforma:</span>
                            <span class="text-gray-900">{{ $usuario->created_at->diffForHumans() }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-500">Última actividad:</span>
                            <span class="text-gray-900">{{ $usuario->updated_at->diffForHumans() }}</span>
                        </div>
                        
                        @if($usuario->role === 'student' && $usuario->inscripciones()->count() > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-500">Primer curso:</span>
                            <span class="text-gray-900">{{ $usuario->inscripciones->first()->created_at->format('d/m/Y') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection