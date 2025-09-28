@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Crear Nuevo Usuario</h1>
        <a href="{{ route('admin.usuarios.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Cancelar
        </a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <form method="POST" action="{{ route('admin.usuarios.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nombre Completo *</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" 
                                   required>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico *</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" 
                                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" 
                                   required>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700">Contraseña *</label>
                            <input type="password" name="password" id="password" 
                                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" 
                                   required>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Mínimo 8 caracteres</p>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña *</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" 
                                   required>
                        </div>
                    </div>

                    <div>
                        <div class="mb-4">
                            <label for="role" class="block text-sm font-medium text-gray-700">Rol *</label>
                            <select name="role" id="role" 
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                                    required>
                                <option value="">Seleccionar rol</option>
                                <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Estudiante</option>
                                <option value="developer" {{ old('role') == 'developer' ? 'selected' : '' }}>Desarrollador</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                            </select>
                            @error('role')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Descripción de Roles</label>
                            <div class="p-4 bg-gray-50 rounded-md text-sm text-gray-600">
                                <div id="role-description">
                                    <p class="mb-2"><strong>Estudiante:</strong> Puede acceder a cursos, realizar lecciones y desafíos.</p>
                                    <p class="mb-2"><strong>Desarrollador:</strong> Puede crear y gestionar contenido educativo.</p>
                                    <p><strong>Administrador:</strong> Acceso completo al sistema, gestión de usuarios y configuraciones.</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="email_verified" value="1" 
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                       {{ old('email_verified') ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">Marcar email como verificado</span>
                            </label>
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="send_welcome_email" value="1" 
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                       {{ old('send_welcome_email', true) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">Enviar email de bienvenida</span>
                            </label>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vista previa</label>
                            <div class="p-4 bg-blue-50 rounded-md">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                            <span id="preview-initials" class="text-white font-medium text-sm">U</span>
                                        </div>
                                    </div>
                                    <div>
                                        <p id="preview-name" class="font-medium text-gray-900">Nombre del usuario</p>
                                        <p id="preview-email" class="text-sm text-gray-600">email@ejemplo.com</p>
                                        <span id="preview-role" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Rol
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-6 space-x-3">
                    <a href="{{ route('admin.usuarios.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Cancelar
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Crear Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const roleSelect = document.getElementById('role');
    
    const previewName = document.getElementById('preview-name');
    const previewEmail = document.getElementById('preview-email');
    const previewRole = document.getElementById('preview-role');
    const previewInitials = document.getElementById('preview-initials');

    function getInitials(name) {
        return name.split(' ').map(word => word.charAt(0)).join('').toUpperCase().substring(0, 2);
    }

    function getRoleColor(role) {
        switch(role) {
            case 'admin':
                return 'bg-red-100 text-red-800';
            case 'developer':
                return 'bg-blue-100 text-blue-800';
            case 'student':
                return 'bg-green-100 text-green-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    function getRoleText(role) {
        switch(role) {
            case 'admin':
                return 'Administrador';
            case 'developer':
                return 'Desarrollador';
            case 'student':
                return 'Estudiante';
            default:
                return 'Rol';
        }
    }

    function updatePreview() {
        const name = nameInput.value || 'Nombre del usuario';
        const email = emailInput.value || 'email@ejemplo.com';
        const role = roleSelect.value;

        previewName.textContent = name;
        previewEmail.textContent = email;
        previewInitials.textContent = getInitials(name);
        
        previewRole.textContent = getRoleText(role);
        previewRole.className = `inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${getRoleColor(role)}`;
    }

    nameInput.addEventListener('input', updatePreview);
    emailInput.addEventListener('input', updatePreview);
    roleSelect.addEventListener('change', updatePreview);
});
</script>
@endsection