/**
 * SlyCipher - JavaScript para Autenticación
 * Funcionalidades interactivas para login y registro
 */

document.addEventListener('DOMContentLoaded', function() {
    // Validación de contraseña en tiempo real
    const passwordInput = document.getElementById('password');
    const passwordRequirements = document.getElementById('passwordRequirements');
    
    if (passwordInput && passwordRequirements) {
        passwordInput.addEventListener('input', function() {
            validatePasswordRequirements(this.value);
        });
    }

    // Validación de confirmación de contraseña
    const passwordConfirmation = document.getElementById('password_confirmation');
    if (passwordConfirmation && passwordInput) {
        passwordConfirmation.addEventListener('input', function() {
            validatePasswordMatch(passwordInput.value, this.value);
        });
    }

    // Efectos visuales en botones
    addButtonEffects();
    
    // Auto-hide alerts después de 5 segundos
    autoHideAlerts();
});

/**
 * Valida los requisitos de contraseña en tiempo real
 */
function validatePasswordRequirements(password) {
    const requirements = document.getElementById('passwordRequirements');
    if (!requirements) return;

    const checks = {
        length: password.length >= 8 && password.length <= 20,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /\d/.test(password),
        special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
    };

    // Actualizar estilos de cada requisito
    Object.keys(checks).forEach(requirement => {
        const element = requirements.querySelector(`small:nth-child(${getRequirementIndex(requirement)})`);
        if (element) {
            if (checks[requirement]) {
                element.style.color = '#1bc831';
                element.style.fontWeight = 'bold';
                element.innerHTML = '✓ ' + element.textContent.replace('✓ ', '');
            } else {
                element.style.color = '#888';
                element.style.fontWeight = 'normal';
                element.innerHTML = element.textContent.replace('✓ ', '');
            }
        }
    });
}

/**
 * Obtiene el índice del requisito para la validación
 */
function getRequirementIndex(requirement) {
    const mapping = {
        length: 1,
        uppercase: 2,
        lowercase: 3,
        number: 4,
        special: 5
    };
    return mapping[requirement] || 1;
}

/**
 * Valida que las contraseñas coincidan
 */
function validatePasswordMatch(password, confirmation) {
    const confirmationInput = document.getElementById('password_confirmation');
    if (!confirmationInput) return;

    if (confirmation && password !== confirmation) {
        confirmationInput.style.borderColor = '#ff4d4d';
        showFieldError(confirmationInput, 'Las contraseñas no coinciden');
    } else if (confirmation) {
        confirmationInput.style.borderColor = '#1bc831';
        hideFieldError(confirmationInput);
    }
}

/**
 * Muestra error en un campo específico
 */
function showFieldError(field, message) {
    hideFieldError(field); // Limpiar error anterior
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.textContent = message;
    errorDiv.style.display = 'block';
    
    field.parentNode.appendChild(errorDiv);
    field.classList.add('is-invalid');
}

/**
 * Oculta error de un campo específico
 */
function hideFieldError(field) {
    const existingError = field.parentNode.querySelector('.invalid-feedback');
    if (existingError && !existingError.textContent.includes('validation')) {
        existingError.remove();
    }
    field.classList.remove('is-invalid');
}

/**
 * Agrega efectos visuales a los botones
 */
function addButtonEffects() {
    const buttons = document.querySelectorAll('.btn, .btn-social');
    
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-1px)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
        
        button.addEventListener('mousedown', function() {
            this.style.transform = 'translateY(1px) scale(0.98)';
        });
        
        button.addEventListener('mouseup', function() {
            this.style.transform = 'translateY(-1px)';
        });
    });
}

/**
 * Auto-oculta las alertas después de 5 segundos
 */
function autoHideAlerts() {
    const alerts = document.querySelectorAll('.alert-success');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease-out';
            alert.style.opacity = '0';
            
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });
}

/**
 * Simulación de login social (para futuras implementaciones)
 */
function loginWithGithub() {
    console.log('Login con GitHub - Funcionalidad pendiente de implementación');
    // Aquí se implementaría la integración con GitHub OAuth
}

function loginWithGoogle() {
    console.log('Login con Google - Funcionalidad pendiente de implementación');
    // Aquí se implementaría la integración con Google OAuth
}

/**
 * Validación de email en tiempo real
 */
document.addEventListener('DOMContentLoaded', function() {
    const emailInputs = document.querySelectorAll('input[type="email"]');
    
    emailInputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateEmail(this);
        });
    });
});

/**
 * Valida formato de email
 */
function validateEmail(input) {
    const email = input.value;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (email && !emailRegex.test(email)) {
        input.style.borderColor = '#ff4d4d';
        showFieldError(input, 'Por favor ingresa un email válido');
    } else if (email) {
        input.style.borderColor = '#1bc831';
        hideFieldError(input);
    }
}

/**
 * Animación de carga en formularios
 */
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('.auth-form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitButton = this.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.classList.add('loading');
                submitButton.innerHTML = submitButton.innerHTML + ' <i class="fas fa-spinner fa-spin"></i>';
            }
        });
    });
});