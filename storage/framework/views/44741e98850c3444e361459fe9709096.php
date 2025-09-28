

<?php $__env->startSection('title', 'Dashboard Estudiante'); ?>
<?php $__env->startSection('page-title', 'Dashboard Estudiante'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Bienvenida y estadísticas -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <h4>¡Bienvenido, <?php echo e(Auth::user()->nombre); ?>!</h4>
                            <p class="mb-0">Continúa tu aventura de aprendizaje en SLYCIPHER</p>
                        </div>
                        <div class="col-4 text-end">
                            <i class="fas fa-fire fa-3x text-warning"></i>
                            <h5 class="mt-2">¡Sigue adelante!</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Estadísticas del estudiante -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Desafíos Completados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($totalCompletados); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
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

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Logros Disponibles</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($logrosDisponibles->count()); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-trophy fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Cursos Disponibles</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($cursosDisponibles->count()); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Mi Progreso Reciente -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Mi Progreso Reciente</h6>
                </div>
                <div class="card-body">
                    <?php if($miProgreso->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Desafío</th>
                                        <th>Categoría</th>
                                        <th>Estado</th>
                                        <th>Puntaje</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $miProgreso; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $progreso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($progreso->desafio->titulo ?? 'Desafío'); ?></td>
                                        <td>Práctica de Programación</td>
                                        <td>
                                            <span class="badge 
                                                <?php if($progreso->completado): ?> bg-success 
                                                <?php else: ?> bg-warning <?php endif; ?>">
                                                <?php echo e($progreso->completado ? 'Completado' : 'En Progreso'); ?>

                                            </span>
                                        </td>
                                        <td><?php echo e($progreso->puntaje); ?>/100</td>
                                        <td><?php echo e($progreso->updated_at->format('d/m/Y')); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-chart-line fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">Aún no has comenzado ningún desafío.</p>
                            <a href="<?php echo e(route('student.practica.index')); ?>" class="btn btn-primary">
                                <i class="fas fa-code me-1"></i>Comenzar a Practicar
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Mis Logros -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Mis Logros Recientes</h6>
                </div>
                <div class="card-body">
                    <?php
                        // Asegurar que la variable existe para evitar el error Undefined variable
                        $misLogros = $misLogros ?? [];
                    ?>

                    <?php if(count($misLogros) > 0): ?>
                        <?php $__currentLoopData = $misLogros->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $logro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex align-items-center mb-3 p-2 bg-light rounded">
                            <div class="me-3">
                                <i class="fas fa-trophy text-warning fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="mb-1"><?php echo e($logro->logro->titulo); ?></h6>
                                <small class="text-muted"><?php echo e($logro->desbloqueado_en->format('d/m/Y')); ?></small>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                        <?php if(count($misLogros) > 5): ?>
                        <div class="text-center">
                            <a href="<?php echo e(route('logros.index')); ?>" class="btn btn-outline-primary btn-sm">
                                Ver todos mis logros
                            </a>
                        </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-trophy fa-3x text-gray-300 mb-3"></i>
                            <p class="text-muted">Aún no has obtenido logros.</p>
                            <p class="small text-muted">¡Completa lecciones y desafíos para obtener logros!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Cursos Recomendados -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Cursos Disponibles</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php $__currentLoopData = $cursosDisponibles->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $curso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-title"><?php echo e($curso->titulo); ?></h6>
                                    <p class="card-text small text-muted"><?php echo e(Str::limit($curso->descripcion, 80)); ?></p>
                                    <div class="mb-2">
                                        <span class="badge bg-info"><?php echo e($curso->lenguaje->nombre); ?></span>
                                        <span class="badge bg-secondary"><?php echo e($curso->categoria->nombre); ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge 
                                            <?php if($curso->nivel === 'principiante'): ?> bg-success 
                                            <?php elseif($curso->nivel === 'intermedio'): ?> bg-warning 
                                            <?php else: ?> bg-danger <?php endif; ?>">
                                            <?php echo e(ucfirst($curso->nivel)); ?>

                                        </span>
                                        <a href="<?php echo e(route('cursos.show', $curso->course_id)); ?>" class="btn btn-primary btn-sm">
                                            Ver Curso
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <?php if($cursosDisponibles->count() > 6): ?>
                    <div class="text-center mt-3">
                        <a href="<?php echo e(route('cursos.index')); ?>" class="btn btn-outline-primary">
                            Ver todos los cursos
                        </a>
                    </div>
                    <?php endif; ?>
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
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.text-gray-800 {
    color: #5a5c69 !important;
}
.text-gray-300 {
    color: #dddfeb !important;
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\broni\OneDrive\Documentos\Laravel\slycipher\resources\views/student/dashboard.blade.php ENDPATH**/ ?>