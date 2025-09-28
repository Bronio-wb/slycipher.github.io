<x-guest-layout>
    <!-- Pestañas de navegación -->
    <div class="tab-buttons">
        <a href="{{ route('login') }}">Iniciar sesión</a>
        <a href="{{ route('register') }}" class="active">Registrarse</a>
    </div>

    <!-- Login Social -->
    <div class="social-login">
        <a href="#" class="btn-social github">
            <img src="{{ asset('img/github_536452.png') }}" alt="GitHub"> Registrarse con GitHub
        </a>
        <a href="#" class="btn-social google">
            <img src="{{ asset('img/logo_google.png') }}" alt="Google"> Registrarse con Google
        </a>
    </div>

    <div class="divider">
        <span>o crea tu cuenta con email</span>
    </div>

    <!-- Errores de validación -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf

        <!-- Name -->
        <div class="input-group">
            <label for="name">Nombre completo</label>
            <input id="name" 
                   type="text" 
                   name="name" 
                   value="{{ old('name') }}" 
                   placeholder="Tu nombre completo"
                   class="@error('name') is-invalid @enderror"
                   required 
                   autofocus 
                   autocomplete="name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="input-group">
            <label for="email">Correo electrónico</label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   placeholder="ejemplo@midominio.com"
                   class="@error('email') is-invalid @enderror"
                   required 
                   autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-2"></i>{{ __('Contraseña') }}
                            </label>
                            <input id="password" type="password" name="password" required 
                                   class="form-control" placeholder="Ingresa tu contraseña">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            
                            <!-- Requisitos de contraseña -->
                            <div id="passwordRequirements" class="password-requirements mt-2">
                                <small>Entre 8 y 20 caracteres</small>
                                <small>Al menos una mayúscula</small>
                                <small>Al menos una minúscula</small>
                                <small>Al menos un número</small>
                                <small>Al menos un carácter especial</small>
                            </div>
                        </div>        <!-- Confirm Password -->
        <div class="input-group">
            <label for="password_confirmation">Confirmar contraseña</label>
            <input id="password_confirmation" 
                   type="password" 
                   name="password_confirmation" 
                   placeholder="Confirma tu contraseña"
                   class="@error('password_confirmation') is-invalid @enderror"
                   required 
                   autocomplete="new-password">
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">
            <i class="fas fa-user-plus me-2"></i>Crear cuenta
        </button>
    </form>

    <!-- Enlaces adicionales -->
    <div class="auth-links">
        <a href="{{ route('login') }}">
            ¿Ya tienes cuenta? Inicia sesión
        </a>
    </div>
</x-guest-layout>
