

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Gestión de Lecciones</h1>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Leccion::class)): ?>
                <a href="<?php echo e(route('lecciones.create')); ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nueva Lección
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
                                    <th>Título</th>
                                    <th>Curso</th>
                                    <th>Lenguaje</th>
                                    <th>Orden</th>
                                    <th>Estado</th>
                                    <th>Fecha Creación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $lecciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leccion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($leccion->lesson_id); ?></td>
                                    <td><?php echo e($leccion->titulo); ?></td>
                                    <td><?php echo e($leccion->curso->titulo ?? 'N/A'); ?></td>
                                    <td>
                                        <?php if($leccion->curso && $leccion->curso->lenguaje): ?>
                                            <span class="badge badge-info"><?php echo e($leccion->curso->lenguaje->nombre); ?></span>
                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($leccion->orden); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo e($leccion->estado === 'activo' ? 'success' : 'secondary'); ?>">
                                            <?php echo e(ucfirst($leccion->estado)); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($leccion->created_at->format('d/m/Y')); ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(route('lecciones.show', $leccion)); ?>" class="btn btn-info btn-sm" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if(auth()->user()->rol === 'admin' || (auth()->user()->rol === 'desarrollador' && $leccion->curso && $leccion->curso->creado_por === auth()->id())): ?>
                                            <a href="<?php echo e(route('lecciones.edit', $leccion)); ?>" class="btn btn-warning btn-sm" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?php echo e(route('lecciones.destroy', $leccion)); ?>" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar esta lección?')">
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
                        <?php echo e($lecciones->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\broni\OneDrive\Documentos\Laravel\slycipher\resources\views/lecciones/index.blade.php ENDPATH**/ ?>