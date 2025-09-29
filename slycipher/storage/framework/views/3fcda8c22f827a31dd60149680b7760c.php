<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Logros</title>
    <link rel="stylesheet" href="<?php echo e(public_path('css/reportes.css')); ?>">
</head>
<body>
    <header>
        <img src="<?php echo e(public_path('img/Logo.jpeg')); ?>" alt="Logo" class="logo">
        <h1>Reporte de Logros</h1>
    </header>

    <main>
        <h2>Estadísticas Generales</h2>
        <table>
            <tr><th>Total Logros</th><td><?php echo e($estadisticas['total_logros']); ?></td></tr>
            <tr><th>Logros Desbloqueados</th><td><?php echo e($estadisticas['logros_desbloqueados']); ?></td></tr>
            <tr><th>Usuarios con Logros</th><td><?php echo e($estadisticas['usuarios_con_logros']); ?></td></tr>
            <tr><th>Logro más Popular</th><td><?php echo e($estadisticas['logro_mas_popular']); ?></td></tr>
        </table>

        <h2>Listado de Logros Desbloqueados</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Usuario</th>
                    <th>Logro</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $logrosUsuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $logroUsuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($i+1); ?></td>
                    <td><?php echo e($logroUsuario->usuario->nombre ?? 'N/A'); ?></td>
                    <td><?php echo e($logroUsuario->logro->nombre ?? 'N/A'); ?></td>
                    <td><?php echo e($logroUsuario->desbloqueado_en ? $logroUsuario->desbloqueado_en->format('d/m/Y H:i') : 'N/A'); ?></td>
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





<?php /**PATH C:\Users\broni\OneDrive\Documentos\Laravel\slycipher\resources\views/admin/reports/logros.blade.php ENDPATH**/ ?>