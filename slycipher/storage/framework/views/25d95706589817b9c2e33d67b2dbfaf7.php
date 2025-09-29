<?php if (isset($component)) { $__componentOriginal69dc84650370d1d4dc1b42d016d7226b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b = $attributes; } ?>
<?php $component = App\View\Components\GuestLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\GuestLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <!-- Tabs -->
    <div class="tab-buttons">
        <a href="<?php echo e(route('login')); ?>" class="active">Iniciar sesión</a>
        <a href="<?php echo e(route('register')); ?>">Registrarse</a>
    </div>

    <!-- Login Social -->
    <div class="social-login">
        <a href="#" class="btn-social github">
            <img src="<?php echo e(asset('img/github_536452.png')); ?>" alt="GitHub"> Iniciar con GitHub
        </a>
        <a href="#" class="btn-social google">
            <img src="<?php echo e(asset('img/logo_google.png')); ?>" alt="Google"> Iniciar con Google
        </a>
    </div>

    <div class="divider">
        <span>o continúa con tu email</span>
    </div>

    <!-- Errores -->
    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Formulario Login -->
    <form method="POST" action="<?php echo e(route('login')); ?>" class="auth-form">
        <?php echo csrf_field(); ?>

        <div class="input-group">
            <label for="email">Correo electrónico</label>
            <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus autocomplete="username" placeholder="ejemplo@correo.com">
        </div>

        <div class="input-group">
            <label for="password">Contraseña</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Tu contraseña">
            <button type="button" class="toggle-btn" onclick="togglePassword('password')">
                <i class="fa-solid fa-eye"></i>
            </button>
        </div>

        <div class="checkbox-group">
            <input type="checkbox" id="remember" name="remember">
            <label for="remember">Recuérdame</label>
        </div>

        <button type="submit" class="btn btn-success">
            <i class="fas fa-sign-in-alt me-2"></i>Ingresar
        </button>
    </form>

    <!-- Enlaces -->
    <div class="auth-links">
        <?php if(Route::has('password.request')): ?>
            <a href="<?php echo e(route('password.request')); ?>">¿Olvidaste tu contraseña?</a>
        <?php endif; ?>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            input.type = input.type === "password" ? "text" : "password";
        }
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $attributes = $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $component = $__componentOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php /**PATH C:\Users\broni\OneDrive\Documentos\Laravel\slycipher\resources\views/auth/login.blade.php ENDPATH**/ ?>