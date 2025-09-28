

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Gestión de Logros</h1>
                <?php if(auth()->user()->rol === 'admin'): ?>
                <a href="<?php echo e(route('logros.create')); ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nuevo Logro
                </a>
                <?php endif; ?>
            </div>

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Icono</th>
                                    <th>Tipo</th>
                                    <th>Puntos Requeridos</th>
                                    <th>Usuarios</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $logros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $logro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($logro->achievement_id); ?></td>
                                    <td><?php echo e($logro->nombre); ?></td>
                                    <td><?php echo e(Str::limit($logro->descripcion, 50)); ?></td>
                                    <td>
                                        <?php if($logro->icono): ?>
                                            <i class="<?php echo e($logro->icono); ?>"></i>
                                        <?php else: ?>
                                            <i class="fas fa-trophy"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                            $colorTipo = match($logro->tipo) {
                                                'progreso' => 'primary',
                                                'desafio' => 'warning',
                                                'tiempo' => 'info',
                                                'especial' => 'success',
                                                default => 'secondary'
                                            };
                                        ?>
                                        <span class="badge badge-<?php echo e($colorTipo); ?>"><?php echo e(ucfirst($logro->tipo)); ?></span>
                                    </td>
                                    <td><?php echo e($logro->puntos_requeridos); ?></td>
                                    <td>
                                        <span class="badge badge-info"><?php echo e($logro->usuariosLogros->count()); ?></span>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo e($logro->estado === 'activo' ? 'success' : 'secondary'); ?>">
                                            <?php echo e(ucfirst($logro->estado)); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(route('logros.show', $logro)); ?>" class="btn btn-info btn-sm" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if(auth()->user()->rol === 'admin'): ?>
                                            <a href="<?php echo e(route('logros.edit', $logro)); ?>" class="btn btn-warning btn-sm" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?php echo e(route('logros.destroy', $logro)); ?>" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este logro?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Paginación -->
                    <div class="d-flex justify-content-center">
                        <?php echo e($logros->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\broni\OneDrive\Documentos\Laravel\slycipher\resources\views/logros/index.blade.php ENDPATH**/ ?>