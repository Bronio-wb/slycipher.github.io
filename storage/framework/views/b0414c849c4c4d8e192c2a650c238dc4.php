

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Gestión de Desafíos</h1>
                
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Desafio::class)): ?>
                    <?php if(Route::has('desafios.create')): ?>
                        <a href="<?php echo e(route('desafios.create')); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nuevo Desafío
                        </a>
                    <?php elseif(Route::has('admin.desafios.create') && auth()->user()->rol === 'admin'): ?>
                        <a href="<?php echo e(route('admin.desafios.create')); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nuevo Desafío
                        </a>
                    <?php elseif(Route::has('developer.desafios.create') && auth()->user()->rol === 'desarrollador'): ?>
                        <a href="<?php echo e(route('developer.desafios.create')); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nuevo Desafío
                        </a>
                    <?php else: ?>
                        
                    <?php endif; ?>
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
                                    <th>Lenguaje</th>
                                    <th>Dificultad</th>
                                    <th>Puntos</th>
                                    <th>Creador</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $desafios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $desafio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($desafio->challenge_id); ?></td>
                                    <td><?php echo e($desafio->titulo); ?></td>
                                    <td>
                                        <?php if($desafio->lenguaje): ?>
                                            <span class="badge badge-info"><?php echo e($desafio->lenguaje->nombre); ?></span>
                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                            $colorDificultad = match($desafio->dificultad) {
                                                'fácil' => 'success',
                                                'medio' => 'warning',
                                                'difícil' => 'danger',
                                                default => 'secondary'
                                            };
                                        ?>
                                        <span class="badge badge-<?php echo e($colorDificultad); ?>"><?php echo e(ucfirst($desafio->dificultad)); ?></span>
                                    </td>
                                    <td><?php echo e($desafio->puntos); ?></td>
                                    <td><?php echo e($desafio->creador->nombre ?? 'N/A'); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo e($desafio->estado === 'activo' ? 'success' : 'secondary'); ?>">
                                            <?php echo e(ucfirst($desafio->estado)); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo e(route('desafios.show', $desafio)); ?>" class="btn btn-info btn-sm" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if(auth()->user()->rol === 'admin' || (auth()->user()->rol === 'desarrollador' && $desafio->creado_por === auth()->id())): ?>
                                            <a href="<?php echo e(route('desafios.edit', $desafio)); ?>" class="btn btn-warning btn-sm" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?php echo e(route('desafios.destroy', $desafio)); ?>" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este desafío?')">
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
                        <?php echo e($desafios->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\broni\OneDrive\Documentos\Laravel\slycipher\resources\views/desafios/index.blade.php ENDPATH**/ ?>