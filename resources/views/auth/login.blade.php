<x-guest-layout>
    <!-- Pestañas de navegación -->
    <div class="tab-buttons">
        <a href="{{ route('login') }}" class="active">Iniciar sesión</a>
        <a href="{{ route('register') }}">Registrarse</a>
    </div>

    <!-- Login Social -->
    <div class="social-login">
        <a href="#" class="btn-social github">
            <img src="{{ asset('img/github_536452.png') }}" alt="GitHub"> Iniciar con GitHub
        </a>
        <a href="#" class="btn-social google">
            <img src="{{ asset('img/logo_google.png') }}" alt="Google"> Iniciar con Google
        </a>
    </div>

    <div class="divider">
        <span>o continúa con tu email</span>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

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

    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf

        <!-- Email Address -->
        <div class="input-group">
            <label for="email">Correo electrónico</label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   placeholder="ejemplo@tudominio.com"
                   class="@error('email') is-invalid @enderror"
                   required 
                   autofocus 
                   autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="input-group">
            <label for="password">Contraseña</label>
            <input id="password" 
                   type="password" 
                   name="password" 
                   placeholder="Tu contraseña"
                   class="@error('password') is-invalid @enderror"
                   required 
                   autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="checkbox-group">
            <input id="remember_me" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
            <label for="remember_me">Recordarme</label>
        </div>

        <button type="submit" class="btn btn-success">
            <i class="fas fa-sign-in-alt me-2"></i>Ingresar
        </button>
    </form>

    <!-- Enlaces adicionales -->
    <div class="auth-links">
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">
                ¿Olvidaste tu contraseña?
            </a>
        @endif
    </div>
</x-guest-layout>
