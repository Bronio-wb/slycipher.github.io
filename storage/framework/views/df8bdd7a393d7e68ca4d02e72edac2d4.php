

<?php $__env->startSection('title', 'Gestión de Usuarios'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-users me-2"></i>Gestión de Usuarios
                    </h4>
                    <a href="<?php echo e(route('admin.usuarios.create')); ?>" class="btn btn-light btn-sm">
                        <i class="fas fa-plus me-1"></i>Nuevo Usuario
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-select" id="filterRol" onchange="filtrarTabla()">
                                <option value="">Todos los roles</option>
                                <option value="admin">Administrador</option>
                                <option value="desarrollador">Desarrollador</option>
                                <option value="estudiante">Estudiante</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="filterEstado" onchange="filtrarTabla()">
                                <option value="">Todos los estados</option>
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchInput" placeholder="Buscar por nombre, email o username..." onkeyup="filtrarTabla()">
                                <button class="btn btn-outline-secondary" type="button" onclick="limpiarFiltros()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de usuarios -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="usuarioTable">
                            <thead class="table-dark">
                                <tr>
                                    <th><i class="fas fa-user me-1"></i>Usuario</th>
                                    <th><i class="fas fa-envelope me-1"></i>Email</th>
                                    <th><i class="fas fa-user-tag me-1"></i>Rol</th>
                                    <th><i class="fas fa-toggle-on me-1"></i>Estado</th>
                                    <th><i class="fas fa-star me-1"></i>Puntos</th>
                                    <th><i class="fas fa-calendar me-1"></i>Último Login</th>
                                    <th><i class="fas fa-cogs me-1"></i>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                <span class="badge bg-secondary rounded-circle" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                                    <?php echo e(strtoupper(substr($usuario->nombre, 0, 1))); ?><?php echo e(strtoupper(substr($usuario->apellido ?? '', 0, 1))); ?>

                                                </span>
                                            </div>
                                            <div>
                                                <strong><?php echo e($usuario->nombre); ?> <?php echo e($usuario->apellido ?? ''); ?></strong>
                                                <br>
                                                <small class="text-muted">{{ $usuario->username }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo e($usuario->email); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($usuario->rol == 'admin' ? 'danger' : ($usuario->rol == 'desarrollador' ? 'primary' : 'success')); ?>">
                                            <?php if($usuario->rol == 'admin'): ?>
                                                <i class="fas fa-crown me-1"></i>Administrador
                                            <?php elseif($usuario->rol == 'desarrollador'): ?>
                                                <i class="fas fa-code me-1"></i>Desarrollador
                                            <?php else: ?>
                                                <i class="fas fa-graduation-cap me-1"></i>Estudiante
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo e($usuario->estado == 'activo' ? 'success' : 'danger'); ?>">
                                            <?php echo e($usuario->estado == 'activo' ? '✅ Activo' : '❌ Inactivo'); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-warning"><?php echo e($usuario->puntos_totales ?? 0); ?></span>
                                    </td>
                                    <td>
                                        <?php if($usuario->ultimo_login): ?>
                                            <span class="text-muted"><?php echo e($usuario->ultimo_login->format('d/m/Y H:i')); ?></span>
                                            <br>
                                            <small class="text-info"><?php echo e($usuario->ultimo_login->diffForHumans()); ?></small>
                                        <?php else: ?>
                                            <span class="text-muted">Nunca</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(route('admin.usuarios.show', $usuario->id)); ?>" class="btn btn-info btn-sm" title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('admin.usuarios.edit', $usuario->id)); ?>" class="btn btn-warning btn-sm" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if($usuario->id !== Auth::id()): ?>
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmarEliminacion('<?php echo e($usuario->id); ?>')" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No hay usuarios registrados.</p>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <?php if($usuarios->hasPages()): ?>
                    <div class="d-flex justify-content-center mt-4">
                        <?php echo e($usuarios->links()); ?>

                    </div>
                    <?php endif; ?>

                    <!-- Formulario oculto para eliminación -->
                    <form id="delete-form" action="" method="POST" style="display: none;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function filtrarTabla() {
    const rolFilter = document.getElementById('filterRol').value.toLowerCase();
    const estadoFilter = document.getElementById('filterEstado').value.toLowerCase();
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const table = document.getElementById('usuarioTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        
        if (cells.length === 0) continue; // Skip empty rows
        
        const nombre = cells[0].textContent.toLowerCase();
        const email = cells[1].textContent.toLowerCase();
        const rol = cells[2].textContent.toLowerCase();
        const estado = cells[3].textContent.toLowerCase();

        const matchesRol = !rolFilter || rol.includes(rolFilter);
        const matchesEstado = !estadoFilter || estado.includes(estadoFilter);
        const matchesSearch = !searchTerm || 
            nombre.includes(searchTerm) || 
            email.includes(searchTerm);

        if (matchesRol && matchesEstado && matchesSearch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
}

function limpiarFiltros() {
    document.getElementById('filterRol').value = '';
    document.getElementById('filterEstado').value = '';
    document.getElementById('searchInput').value = '';
    filtrarTabla();
}

function confirmarEliminacion(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este usuario?\n\nEsta acción no se puede deshacer y se eliminarán todos los datos asociados al usuario.')) {
        const form = document.getElementById('delete-form');
        form.action = `<?php echo e(route('admin.usuarios.index')); ?>/${id}`;
        form.submit();
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\broni\OneDrive\Documentos\Laravel\slycipher\resources\views/admin/usuarios/index.blade.php ENDPATH**/ ?>