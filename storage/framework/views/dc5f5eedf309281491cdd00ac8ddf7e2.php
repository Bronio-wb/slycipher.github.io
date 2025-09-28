

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Editar Usuario</h1>
        <a href="<?php echo e(route('admin.usuarios.show', $usuario)); ?>" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Cancelar
        </a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <form method="POST" action="<?php echo e(route('admin.usuarios.update', $usuario)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nombre Completo *</label>
                            <input type="text" name="name" id="name" value="<?php echo e(old('name', $usuario->name)); ?>" 
                                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" 
                                   required>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico *</label>
                            <input type="email" name="email" id="email" value="<?php echo e(old('email', $usuario->email)); ?>" 
                                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" 
                                   required>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700">Nueva Contraseña</label>
                            <input type="password" name="password" id="password" 
                                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <p class="mt-1 text-xs text-gray-500">Dejar en blanco para mantener la contraseña actual</p>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Nueva Contraseña</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>

                    <div>
                        <div class="mb-4">
                            <label for="role" class="block text-sm font-medium text-gray-700">Rol *</label>
                            <select name="role" id="role" 
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                                    required>
                                <option value="student" <?php echo e(old('role', $usuario->role) == 'student' ? 'selected' : ''); ?>>Estudiante</option>
                                <option value="developer" <?php echo e(old('role', $usuario->role) == 'developer' ? 'selected' : ''); ?>>Desarrollador</option>
                                <option value="admin" <?php echo e(old('role', $usuario->role) == 'admin' ? 'selected' : ''); ?>>Administrador</option>
                            </select>
                            <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estado del Email</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="email_verified" value="1" 
                                           class="text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                           <?php echo e($usuario->email_verified_at ? 'checked' : ''); ?>>
                                    <span class="ml-2 text-sm text-gray-600">Email verificado</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="email_verified" value="0" 
                                           class="text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                           <?php echo e(!$usuario->email_verified_at ? 'checked' : ''); ?>>
                                    <span class="ml-2 text-sm text-gray-600">Email no verificado</span>
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Vista previa</label>
                            <div class="p-4 bg-blue-50 rounded-md">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                            <span id="preview-initials" class="text-white font-medium text-sm">
                                                <?php echo e(strtoupper(substr($usuario->name, 0, 1))); ?><?php echo e(strlen($usuario->name) > 1 ? strtoupper(substr(explode(' ', $usuario->name)[1] ?? $usuario->name, 0, 1)) : ''); ?>

                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <p id="preview-name" class="font-medium text-gray-900"><?php echo e($usuario->name); ?></p>
                                        <p id="preview-email" class="text-sm text-gray-600"><?php echo e($usuario->email); ?></p>
                                        <span id="preview-role" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                            <?php switch($usuario->role):
                                                case ('admin'): ?> bg-red-100 text-red-800 <?php break; ?>
                                                <?php case ('developer'): ?> bg-blue-100 text-blue-800 <?php break; ?>
                                                <?php case ('student'): ?> bg-green-100 text-green-800 <?php break; ?>
                                                <?php default: ?> bg-gray-100 text-gray-800
                                            <?php endswitch; ?>">
                                            <?php echo e(ucfirst($usuario->role)); ?>

                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Información Actual</label>
                            <div class="p-4 bg-gray-50 rounded-md">
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-gray-700">Registrado:</span>
                                        <span class="text-gray-600"><?php echo e($usuario->created_at->format('d/m/Y')); ?></span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Última actualización:</span>
                                        <span class="text-gray-600"><?php echo e($usuario->updated_at->format('d/m/Y')); ?></span>
                                    </div>
                                    <?php if($usuario->role === 'student'): ?>
                                    <div>
                                        <span class="font-medium text-gray-700">Cursos:</span>
                                        <span class="text-gray-600"><?php echo e($usuario->inscripciones()->count()); ?></span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Logros:</span>
                                        <span class="text-gray-600"><?php echo e($usuario->logros()->count()); ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Acciones Adicionales</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center">
                            <label class="flex items-center justify-center">
                                <input type="checkbox" name="send_notification" value="1" 
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600">Notificar cambios al usuario</span>
                            </label>
                        </div>
                        
                        <div class="text-center">
                            <label class="flex items-center justify-center">
                                <input type="checkbox" name="force_logout" value="1" 
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600">Forzar cierre de sesión</span>
                            </label>
                        </div>
                        
                        <?php if($usuario->role === 'student'): ?>
                        <div class="text-center">
                            <label class="flex items-center justify-center">
                                <input type="checkbox" name="reset_progress" value="1" 
                                       class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-red-600">Reiniciar progreso</span>
                            </label>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-6 space-x-3">
                    <a href="<?php echo e(route('admin.usuarios.show', $usuario)); ?>" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Cancelar
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Actualizar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php if($usuario->role === 'student' && $usuario->inscripciones()->count() > 0): ?>
    <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Gestión de Inscripciones</h3>
            <div class="space-y-2">
                <?php $__currentLoopData = $usuario->inscripciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inscripcion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-md">
                    <div>
                        <span class="font-medium text-gray-900"><?php echo e($inscripcion->curso->nombre); ?></span>
                        <span class="text-sm text-gray-500 ml-2">
                            (<?php echo e($inscripcion->created_at->format('d/m/Y')); ?>)
                        </span>
                    </div>
                    <div class="flex space-x-2">
                        <a href="<?php echo e(route('cursos.show', $inscripcion->curso)); ?>" class="text-blue-600 hover:text-blue-800 text-sm">Ver</a>
                        <form method="POST" action="<?php echo e(route('admin.usuarios.remove-inscription', [$usuario, $inscripcion])); ?>" class="inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm"
                                    onclick="return confirm('¿Eliminar inscripción del curso?')">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
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
        const name = nameInput.value || '<?php echo e($usuario->name); ?>';
        const email = emailInput.value || '<?php echo e($usuario->email); ?>';
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\broni\OneDrive\Documentos\Laravel\slycipher\resources\views/admin/usuarios/edit.blade.php ENDPATH**/ ?>