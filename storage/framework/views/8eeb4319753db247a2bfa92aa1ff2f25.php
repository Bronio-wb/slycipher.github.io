

<?php $__env->startSection('title','Crear Lección'); ?>
<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <h3>Proponer nueva Lección</h3>

    <?php if(session('status')): ?> <div class="alert alert-success"><?php echo e(session('status')); ?></div> <?php endif; ?>

    <form action="<?php echo e(route('developer.lecciones.store')); ?>" method="POST" class="mt-3">
        <?php echo csrf_field(); ?>

        <div class="mb-3">
            <label class="form-label">Título</label>
            <input name="titulo" class="form-control" required value="<?php echo e(old('titulo')); ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="4"><?php echo e(old('descripcion')); ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Curso</label>
            <select name="course_id" class="form-select" required>
                <option value="">-- seleccionar curso --</option>
                <?php $__currentLoopData = $cursos ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($c->course_id ?? $c->id); ?>"><?php echo e($c->titulo ?? $c->title); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Duración (ej. 10m)</label>
            <input name="duracion" class="form-control" value="<?php echo e(old('duracion')); ?>">
        </div>

        <button class="btn btn-primary rounded-action-btn">Proponer Lección</button>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\broni\OneDrive\Documentos\Laravel\slycipher\resources\views/lecciones/create.blade.php ENDPATH**/ ?>