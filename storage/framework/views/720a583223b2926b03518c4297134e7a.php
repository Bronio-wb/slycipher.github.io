

<?php $__env->startSection('title','Crear Desafío'); ?>
<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <h3>Proponer nuevo Desafío</h3>

    <?php if(session('status')): ?> <div class="alert alert-success"><?php echo e(session('status')); ?></div> <?php endif; ?>

    <form action="<?php echo e(route('developer.desafios.store')); ?>" method="POST" class="mt-3">
        <?php echo csrf_field(); ?>

        <div class="mb-3">
            <label class="form-label">Título</label>
            <input name="titulo" class="form-control" required value="<?php echo e(old('titulo')); ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="4"><?php echo e(old('descripcion')); ?></textarea>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Lenguaje</label>
                <select name="language_id" class="form-select">
                    <option value="">-- seleccionar --</option>
                    <?php $__currentLoopData = $lenguajes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($l->language_id ?? $l->id); ?>"><?php echo e($l->nombre ?? $l->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Dificultad</label>
                <select name="dificultad" class="form-select">
                    <option value="principiante">Principiante</option>
                    <option value="intermedio">Intermedio</option>
                    <option value="avanzado">Avanzado</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Puntos</label>
                <input name="puntos" type="number" class="form-control" value="<?php echo e(old('puntos',10)); ?>">
            </div>
        </div>

        <button class="btn btn-primary rounded-action-btn">Proponer Desafío</button>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\broni\OneDrive\Documentos\Laravel\slycipher\resources\views/desafios/create.blade.php ENDPATH**/ ?>