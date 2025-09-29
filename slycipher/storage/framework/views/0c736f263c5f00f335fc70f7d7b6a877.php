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
        <a href="<?php echo e(route('login')); ?>">Iniciar sesión</a>
        <a href="<?php echo e(route('register')); ?>" class="active">Registrarse</a>
    </div>

    <!-- Login Social -->
    <div class="social-login">
        <a href="#" class="btn-social github">
            <img src="<?php echo e(asset('img/github_536452.png')); ?>" alt="GitHub"> Registrarse con GitHub
        </a>
        <a href="#" class="btn-social google">
            <img src="<?php echo e(asset('img/logo_google.png')); ?>" alt="Google"> Registrarse con Google
        </a>
    </div>

    <div class="divider">
        <span>o crea tu cuenta con email</span>
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

    <!-- Formulario Registro -->
    <form method="POST" action="<?php echo e(route('register')); ?>" class="auth-form">
        <?php echo csrf_field(); ?>

        <div class="input-group">
            <label for="name">Nombre completo</label>
            <input id="name" type="text" name="name" value="<?php echo e(old('name')); ?>" required autocomplete="name" placeholder="Tu nombre completo">
        </div>

        <div class="input-group">
            <label for="email">Correo electrónico</label>
            <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="username" placeholder="ejemplo@correo.com">
        </div>

        <div class="input-group">
            <label for="password">Contraseña</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Crea una contraseña">
            <button type="button" class="toggle-btn" onclick="togglePassword('password')">
                <i class="fa-solid fa-eye"></i>
            </button>
            <div id="passwordRequirements" class="password-requirements">
                <small id="lengthReq">Entre 8 y 20 caracteres</small>
                <small id="upperReq">Al menos una mayúscula</small>
                <small id="lowerReq">Al menos una minúscula</small>
                <small id="numberReq">Al menos un número</small>
                <small id="specialReq">Al menos un carácter especial</small>
            </div>
        </div>

        <div class="input-group">
            <label for="password_confirmation">Confirmar contraseña</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repite tu contraseña">
            <button type="button" class="toggle-btn" onclick="togglePassword('password_confirmation')">
                <i class="fa-solid fa-eye"></i>
            </button>
        </div>

        <button type="submit" class="btn btn-success">
            <i class="fas fa-user-plus me-2"></i>Crear cuenta
        </button>
    </form>

    <!-- Enlaces -->
    <div class="auth-links">
        <a href="<?php echo e(route('login')); ?>">¿Ya tienes cuenta? Inicia sesión</a>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            input.type = input.type === "password" ? "text" : "password";
        }

        // Validación en vivo de la contraseña
        const passwordInput = document.getElementById("password");
        const lengthReq = document.getElementById("lengthReq");
        const upperReq = document.getElementById("upperReq");
        const lowerReq = document.getElementById("lowerReq");
        const numberReq = document.getElementById("numberReq");
        const specialReq = document.getElementById("specialReq");

        passwordInput.addEventListener("input", () => {
            const value = passwordInput.value;
            lengthReq.classList.toggle("valid", value.length >= 8 && value.length <= 20);
            upperReq.classList.toggle("valid", /[A-Z]/.test(value));
            lowerReq.classList.toggle("valid", /[a-z]/.test(value));
            numberReq.classList.toggle("valid", /[0-9]/.test(value));
            specialReq.classList.toggle("valid", /[^A-Za-z0-9]/.test(value));
        });
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
<?php /**PATH C:\Users\broni\OneDrive\Documentos\Laravel\slycipher\resources\views/auth/register.blade.php ENDPATH**/ ?>