

<?php $__env->startSection('title', 'Dashboard Desarrollador'); ?>
<?php $__env->startSection('page-title', 'Dashboard Desarrollador'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!-- Estadísticas del desarrollador -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Mis Cursos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($totalMisCursos); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Lecciones</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($totalLecciones); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Desafíos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($totalDesafios); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-puzzle-piece fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Mis Cursos -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Mis Cursos</h6>
                    <a href="<?php echo e(route('developer.cursos.create')); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Nuevo Curso
                    </a>
                </div>
                <div class="card-body">
                    <?php if($misCursos->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Lenguaje</th>
                                        <th>Categoría</th>
                                        <th>Nivel</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $misCursos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $curso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($curso->titulo); ?></td>
                                        <td><?php echo e($curso->lenguaje->nombre); ?></td>
                                        <td><?php echo e($curso->categoria->nombre); ?></td>
                                        <td>
                                            <span class="badge 
                                                <?php if($curso->nivel === 'principiante'): ?> bg-success 
                                                <?php elseif($curso->nivel === 'intermedio'): ?> bg-warning 
                                                <?php else: ?> bg-danger <?php endif; ?>">
                                                <?php echo e(ucfirst($curso->nivel)); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('cursos.show', $curso->course_id)); ?>" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('cursos.edit', $curso->course_id)); ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-book fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">No has creado ningún curso aún.</p>
                            <a href="<?php echo e(route('developer.cursos.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Crear mi primer curso
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Herramientas de Desarrollo -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Herramientas de Desarrollo</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="<?php echo e(route('developer.cursos.create')); ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-plus text-primary me-2"></i>
                            Crear Nuevo Curso
                        </a>
                        <a href="<?php echo e(route('developer.lecciones.create')); ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-list text-success me-2"></i>
                            Crear Nueva Lección
                        </a>
                        <a href="<?php echo e(route('developer.desafios.create')); ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-puzzle-piece text-info me-2"></i>
                            Crear Nuevo Desafío
                        </a>
                        <a href="<?php echo e(route('logros.create')); ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-trophy text-warning me-2"></i>
                            Crear Nuevo Logro
                        </a>
                    </div>
                </div>
            </div>

            <!-- Lenguajes y Categorías Disponibles -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recursos Disponibles</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-sm font-weight-bold">Lenguajes de Programación:</h6>
                        <div class="row">
                            <?php $__currentLoopData = $lenguajes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lenguaje): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-6 mb-1">
                                    <span class="badge bg-primary"><?php echo e($lenguaje->nombre); ?></span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-sm font-weight-bold">Categorías:</h6>
                        <div class="row">
                            <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-12 mb-1">
                                    <span class="badge bg-secondary"><?php echo e($categoria->nombre); ?></span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.text-gray-800 {
    color: #5a5c69 !important;
}
.text-gray-300 {
    color: #dddfeb !important;
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\broni\OneDrive\Documentos\Laravel\slycipher\resources\views/developer/dashboard.blade.php ENDPATH**/ ?>