<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Progreso</title>
    <link rel="stylesheet" href="<?php echo e(public_path('css/reportes.css')); ?>">
</head>
<body>
    <header>
        <img src="<?php echo e(public_path('img/Logo.jpeg')); ?>" alt="Logo" class="logo">
        <h1>Reporte de Progreso</h1>
    </header>

    <main>
        <h2>Estadísticas Generales</h2>
        <table>
            <tr><th>Total Progresos</th><td><?php echo e($estadisticas['total_progresos']); ?></td></tr>
            <tr><th>Completados</th><td><?php echo e($estadisticas['completados']); ?></td></tr>
            <tr><th>En Progreso</th><td><?php echo e($estadisticas['en_progreso']); ?></td></tr>
            <tr><th>Usuarios Activos</th><td><?php echo e($estadisticas['usuarios_activos']); ?></td></tr>
        </table>

        <h2>Detalle de Progresos</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Usuario</th>
                    <th>Curso</th>
                    <th>Lección</th>
                    <th>Estado</th>
                    <th>Completado en</th>
                    <th>Puntaje</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $progresos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $progreso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($i+1); ?></td>
                    <td><?php echo e($progreso->usuario->nombre ?? 'N/A'); ?></td>
                    <td><?php echo e($progreso->leccion->curso->titulo ?? 'N/A'); ?></td>
                    <td><?php echo e($progreso->leccion->titulo ?? 'N/A'); ?></td>
                    <td><?php echo e(ucfirst($progreso->estado)); ?></td>
                    <td><?php echo e($progreso->completado_en ? $progreso->completado_en->format('d/m/Y H:i') : 'Pendiente'); ?></td>
                    <td><?php echo e($progreso->puntaje ?? 0); ?></td>
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






<?php /**PATH C:\Users\broni\OneDrive\Documentos\Laravel\slycipher\resources\views/admin/reports/progreso.blade.php ENDPATH**/ ?>