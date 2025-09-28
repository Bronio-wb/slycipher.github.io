<x-guest-layout>
    <!-- Título personalizado -->
    <div style="text-align: center; margin-bottom: 24px;">
        <h2 style="color: #ff8c00; font-size: 24px; font-weight: 700; margin-bottom: 12px;">
            <i class="fas fa-key me-2"></i>Recuperar Contraseña
        </h2>
        <p style="color: #666; font-size: 14px; line-height: 1.5;">
            ¿Olvidaste tu contraseña? No hay problema. Ingresa tu correo electrónico y te enviaremos un enlace para crear una nueva contraseña.
        </p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
        </div>
    @endif

    <!-- Errores de validación -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="auth-form">
        @csrf

        <!-- Email Address -->
        <div class="input-group">
            <label for="email">Correo electrónico</label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   placeholder="Ingresa tu correo electrónico"
                   class="@error('email') is-invalid @enderror"
                   required 
                   autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane me-2"></i>Enviar enlace de recuperación
        </button>
    </form>

    <!-- Enlaces adicionales -->
    <div class="auth-links">
        <a href="{{ route('login') }}">
            <i class="fas fa-arrow-left me-2"></i>Volver al inicio de sesión
        </a>
    </div>
</x-guest-layout>
