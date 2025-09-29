<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Usuarios</title>
    <link rel="stylesheet" href="<?php echo e(public_path('css/reportes.css')); ?>">
</head>
<body>
    <header>
        <img src="<?php echo e(public_path('img/Logo.jpeg')); ?>" alt="Logo" class="logo">
        <h1>Reporte de Usuarios</h1>
    </header>

    <main>
        <h2>Estadísticas Generales</h2>
        <table>
            <tr><th>Total Usuarios</th><td><?php echo e($estadisticas['total_usuarios']); ?></td></tr>
            <tr><th>Administradores</th><td><?php echo e($estadisticas['administradores']); ?></td></tr>
            <tr><th>Desarrolladores</th><td><?php echo e($estadisticas['desarrolladores']); ?></td></tr>
            <tr><th>Estudiantes</th><td><?php echo e($estadisticas['estudiantes']); ?></td></tr>
            <tr><th>Activos</th><td><?php echo e($estadisticas['activos']); ?></td></tr>
            <tr><th>Inactivos</th><td><?php echo e($estadisticas['inactivos']); ?></td></tr>
        </table>

        <h2>Listado de Usuarios</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Último Login</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($i+1); ?></td>
                    <td><?php echo e($user->nombre); ?> <?php echo e($user->apellido); ?></td>
                    <td><?php echo e($user->email); ?></td>
                    <td><?php echo e(ucfirst($user->rol)); ?></td>
                    <td><?php echo e(ucfirst($user->estado)); ?></td>
                    <td><?php echo e($user->ultimo_login ? $user->ultimo_login->format('d/m/Y H:i') : 'N/A'); ?></td>
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
<?php /**PATH C:\Users\broni\OneDrive\Documentos\Laravel\slycipher\resources\views/admin/reports/usuarios.blade.php ENDPATH**/ ?>