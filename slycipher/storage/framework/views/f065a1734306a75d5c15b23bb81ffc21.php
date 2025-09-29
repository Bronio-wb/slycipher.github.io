<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Cursos</title>
    <link rel="stylesheet" href="<?php echo e(public_path('css/reportes.css')); ?>">
</head>
<body>
    <header>
        <img src="<?php echo e(public_path('img/Logo.jpeg')); ?>" alt="Logo" class="logo">
        <h1>Reporte de Cursos</h1>
    </header>

    <main>
        <h2>Estadísticas Generales</h2>
        <table>
            <tr><th>Total Cursos</th><td><?php echo e($estadisticas['total_cursos']); ?></td></tr>
            <tr><th>Cursos Activos</th><td><?php echo e($estadisticas['activos']); ?></td></tr>
            <tr><th>Cursos Inactivos</th><td><?php echo e($estadisticas['inactivos']); ?></td></tr>
            <tr><th>Total Lecciones</th><td><?php echo e($estadisticas['total_lecciones'] ?? 0); ?></td></tr>
            <tr><th>Cursos con Progreso</th><td><?php echo e($estadisticas['cursos_con_progreso'] ?? 0); ?></td></tr>
        </table>

        <h2>Listado de Cursos</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Nivel</th>
                    <th>Estado</th>
                    <th>Creador</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $cursos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $curso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($i+1); ?></td>
                    <td><?php echo e($curso->titulo); ?></td>
                    <td><?php echo e(Str::limit($curso->descripcion, 50)); ?></td>
                    <td><?php echo e(ucfirst($curso->nivel)); ?></td>
                    <td><?php echo e(ucfirst($curso->estado)); ?></td>
                    <td><?php echo e($curso->creador->nombre ?? 'N/A'); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </main>

    <footer>
        Reporte generado por <strong>SlyCipher</strong> – <?php echo e(\Carbon\Carbon::now()->format('d/m/Y H:i')); ?>

    </footer>
</body>
</html>






<?php /**PATH C:\Users\broni\OneDrive\Documentos\Laravel\slycipher\resources\views/admin/reports/cursos.blade.php ENDPATH**/ ?>