

<?php $__env->startSection('title', 'Error del servidor'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center align-items-center" style="min-height: 60vh;">
        <div class="col-lg-6 text-center">
            <div class="card shadow">
                <div class="card-body py-5">
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle fa-5x text-warning mb-4"></i>
                        <h1 class="h1 text-gray-900 mb-4">500 - Error del servidor</h1>
                        <p class="text-gray-500 mb-4">
                            Oops! Algo salió mal en nuestros servidores. Por favor, inténtalo de nuevo más tarde.
                        </p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="<?php echo e(url()->previous()); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Volver
                            </a>
                            <?php if(auth()->guard()->check()): ?>
                                <?php if(Auth::user()->rol === 'admin'): ?>
                                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-primary">
                                        <i class="fas fa-home me-2"></i>Ir al Dashboard
                                    </a>
                                <?php elseif(Auth::user()->rol === 'desarrollador'): ?>
                                    <a href="<?php echo e(route('developer.dashboard')); ?>" class="btn btn-primary">
                                        <i class="fas fa-home me-2"></i>Ir al Dashboard
                                    </a>
                                <?php elseif(Auth::user()->rol === 'estudiante'): ?>
                                    <a href="<?php echo e(route('student.dashboard')); ?>" class="btn btn-primary">
                                        <i class="fas fa-home me-2"></i>Ir al Dashboard
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <a href="<?php echo e(route('login')); ?>" class="btn btn-primary">
                                    <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.text-gray-900 {
    color: #3a3b45 !important;
}
.text-gray-500 {
    color: #858796 !important;
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\broni\OneDrive\Documentos\Laravel\slycipher\resources\views/errors/500.blade.php ENDPATH**/ ?>