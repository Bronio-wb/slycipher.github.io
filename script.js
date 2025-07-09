// ==============================
// FUNCIÓN: Cambia entre pestañas de Login y Registro
// ==============================

/**
 * Muestra el formulario correspondiente (login o registro) y resalta el botón activo.
 * Se eligió toggle('hidden') para ocultar/mostrar sin recargar la página.
 */
function showTab(tab) {
    document.getElementById('loginForm').classList.toggle('hidden', tab !== 'login');
    document.getElementById('registerForm').classList.toggle('hidden', tab !== 'register');
    document.getElementById('loginTabBtn').classList.toggle('active', tab === 'login');
    document.getElementById('registerTabBtn').classList.toggle('active', tab === 'register');
}

// ==============================
// SOCIAL LOGIN (Simulado para demo)
// ==============================

/**
 * Simula el flujo de autenticación social.
 * Se usa alert para indicar que aquí iría la integración real.
 */
function loginWithGithub() {
    alert("Demo: Aquí iría el flujo de autenticación con GitHub.");
}
function loginWithGoogle() {
    alert("Demo: Aquí iría el flujo de autenticación con Google.");
}

// ==============================
// VALIDACIONES DE FORMULARIOS
// ==============================

/**
 * Expresiones regulares para validar cada campo.
 * Se eligió regex para validaciones rápidas y reutilizables.
 */
const regexValidations = {
    nombre: /^[a-zA-ZáéíóúüñÁÉÍÓÚÜÑ ]{2,30}$/, // Solo letras y espacios, 2-30 caracteres
    apellido: /^[a-zA-ZáéíóúüñÁÉÍÓÚÜÑ ]{2,30}$/,
    email: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/, // Email básico
    password: /^.{8,20}$/, // Longitud, otros requisitos se validan aparte
    fecha: /^\d{4}-\d{2}-\d{2}$/ // Formato YYYY-MM-DD
};

/**
 * Requisitos de contraseña como funciones.
 * Permite mostrar visualmente qué requisitos se cumplen.
 */
const passwordRequirements = {
    length: val => val.length >= 8 && val.length <= 20,
    uppercase: val => /[A-Z]/.test(val),
    lowercase: val => /[a-z]/.test(val),
    number: val => /\d/.test(val),
    special: val => /[!@#$%^&*]/.test(val)
};

/**
 * Mensajes de error personalizados para cada campo.
 * Facilita la traducción y el mantenimiento.
 */
const errorMessages = {
    nombre: "Nombre (solo letras y espacios, mínimo 2)",
    apellido: "Apellido (solo letras y espacios, mínimo 2)",
    email: "Correo electrónico inválido",
    password: "Contraseña debe tener 8-20 caracteres, mayúscula, minúscula, número y símbolo",
    fecha: "Fecha válida obligatoria"
};

/**
 * Valida la fecha de nacimiento:
 * - Formato correcto
 * - No puede ser hoy/futuro
 * - Debe tener al menos 13 años
 * Se eligió Date para comparar fechas de forma robusta.
 */
function validateFechaNacimiento(input) {
    const value = input.value.trim();
    if (!/^\d{4}-\d{2}-\d{2}$/.test(value)) {
        updateFieldStatus(input, false, "Fecha válida obligatoria");
        return false;
    }
    const fecha = new Date(value + "T00:00:00");
    const hoy = new Date();
    hoy.setHours(0,0,0,0);

    const minEdad = 13;
    const fechaMin = new Date(hoy);
    fechaMin.setFullYear(hoy.getFullYear() - minEdad);

    if (fecha >= hoy) {
        updateFieldStatus(input, false, "Escoja una fecha válida");
        return false;
    }
    if (fecha > fechaMin) {
        updateFieldStatus(input, false, `Debes tener al menos ${minEdad} años`);
        return false;
    }
    updateFieldStatus(input, true, "");
    return true;
}

/**
 * Valida un campo según su tipo.
 * - Usa regex para la mayoría
 * - Password y fecha tienen validaciones especiales
 */
function validateField(input) {
    const type = input.getAttribute('data-validation');
    const value = input.value.trim();

    if (type === 'fecha') {
        return validateFechaNacimiento(input);
    }
    // Validación especial para password en registro
    if (type === 'password' && input.form.id === "registerForm") {
        return validatePassword(input);
    }

    let valid = true;
    if (regexValidations[type]) {
        valid = regexValidations[type].test(value);
    } else if (input.required) {
        valid = !!value;
    }
    updateFieldStatus(input, valid, errorMessages[type]);
    return valid;
}

/**
 * Valida la contraseña en registro:
 * - Marca visualmente cada requisito cumplido
 * - Devuelve true solo si todos se cumplen
 */
function validatePassword(input) {
    const value = input.value;
    let valid = true;
    Object.entries(passwordRequirements).forEach(([key, fn]) => {
        const passed = fn(value);
        if (input.form.id === "registerForm") {
            const reqEl = document.querySelector(`#passwordRequirements [data-req="${key}"]`);
            if (reqEl) reqEl.classList.toggle('valid', passed);
        }
        if (!passed) valid = false;
    });
    let msg = valid ? "" : "La contraseña no cumple todos los requisitos";
    updateFieldStatus(input, valid, msg);
    return valid;
}

/**
 * Actualiza el estado visual del campo:
 * - Añade clase 'error' o 'success'
 * - Muestra/oculta mensaje de error
 * Se eligió manipulación de clases para feedback inmediato y accesible.
 */
function updateFieldStatus(input, isValid, message) {
    input.classList.toggle('error', !isValid);
    input.classList.toggle('success', isValid);
    const errorEl = input.parentElement.querySelector('.error-message');
    if (errorEl) {
        errorEl.textContent = isValid ? "" : message;
        errorEl.style.display = isValid ? "none" : "block";
    }
}

/**
 * Sanea la entrada para evitar inyección de HTML/JS.
 * Se eligió reemplazo simple para evitar XSS en campos de texto.
 */
function sanitizeInput(str) {
    return str.replace(/[<>'"]/g, "");
}

// ==============================
// INICIALIZACIÓN Y EVENTOS
// ==============================

/**
 * Al cargar el DOM:
 * - Muestra la pestaña de login por defecto
 * - Añade listeners a inputs para validar en tiempo real y al salir del campo
 * - Al enviar el formulario, valida todos los campos y simula el login/registro
 * 
 * Se eligió validación en tiempo real para mejor UX y menos errores al enviar.
 */
document.addEventListener('DOMContentLoaded', () => {
    showTab('login');
    document.querySelectorAll('.auth-form').forEach(form => {
        form.querySelectorAll('input[data-validation]').forEach(input => {
            input.addEventListener('input', (e) => {
                e.target.value = sanitizeInput(e.target.value);
                validateField(e.target);
            });
            input.addEventListener('blur', (e) => {
                validateField(e.target);
            });
        });
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            let validForm = true;
            form.querySelectorAll('input[data-validation]').forEach(input => {
                if (!validateField(input)) validForm = false;
            });
            if (validForm) {
                // Lógica de login con acceso a dashboards según usuario
                if(form.id === "loginForm") {
                    const email = form.querySelector('input[name="email"]').value.trim();
                    const password = form.querySelector('input[name="password"]').value;

                    // Admin
                    if (email === "admin@slycipher.com" && password === "Admin123!") {
                        window.location.href = "admin-dashboard.html";
                        return;
                    }
                    // Desarrollador de contenido (aprobado por admin)
                    if (email === "dev@slycipher.com" && password === "Devsly123!") {
                        window.location.href = "dev-dashboard.html";
                        return;
                    }
                    // Usuario normal
                    window.location.href = "dashboard.html";
                    return;
                }
                // Registro (simulado)
                alert('¡Formulario válido! (Simulación)');
                form.reset();
                form.querySelectorAll('input').forEach(i => {
                    i.classList.remove('success', 'error');
                });
            } else {
                // Si hay errores, los muestra en todos los campos
                form.querySelectorAll('input[data-validation]').forEach(input => {
                    validateField(input);
                });
            }
        });
    });
});
