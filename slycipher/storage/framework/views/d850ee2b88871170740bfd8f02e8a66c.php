<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SlyCipher - Autenticación</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/auth.css')); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-box">
            <!-- Logo principal -->
            <img src="<?php echo e(asset('img/Logo.jpeg')); ?>" alt="Logo" class="logo">

            <!-- Título -->
            <h1 class="auth-title">SlyCipher</h1>

            <!-- Contenido dinámico -->
            <?php echo e($slot); ?>

        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\broni\OneDrive\Documentos\Laravel\slycipher\resources\views/layouts/guest.blade.php ENDPATH**/ ?>