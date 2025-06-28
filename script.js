// ==============================
// LÓGICA PRINCIPAL DE AUTENTICACIÓN
// ==============================

function showTab(tab) {
    document.getElementById('loginForm').classList.toggle('hidden', tab !== 'login');
    document.getElementById('registerForm').classList.toggle('hidden', tab !== 'register');
    document.getElementById('loginTabBtn').classList.toggle('active', tab === 'login');
    document.getElementById('registerTabBtn').classList.toggle('active', tab === 'register');
}

// ==============================
// VALIDACIONES DE FORMULARIOS
// ==============================

const regexValidations = {
    nombre: /^[a-zA-ZáéíóúüñÁÉÍÓÚÜÑ ]{2,30}$/,
    apellido: /^[a-zA-ZáéíóúüñÁÉÍÓÚÜÑ ]{2,30}$/,
    email: /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/,
    password: /^.{8,20}$/,
    fecha: /^\d{4}-\d{2}-\d{2}$/,
};

const passwordRequirements = {
    length: val => val.length >= 8 && val.length <= 20,
    uppercase: val => /[A-Z]/.test(val),
    lowercase: val => /[a-z]/.test(val),
    number: val => /\d/.test(val),
    special: val => /[!@#$%^&*]/.test(val)
};

const errorMessages = {
    nombre: "Nombre (solo letras y espacios, mínimo 2)",
    apellido: "Apellido (solo letras y espacios, mínimo 2)",
    email: "Correo electrónico inválido",
    password: "Contraseña debe tener 8-20 caracteres, mayúscula, minúscula, número y símbolo",
    fecha: "Fecha válida obligatoria"
};

function validateField(input) {
    const type = input.getAttribute('data-validation');
    const value = input.value.trim();

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

function updateFieldStatus(input, isValid, message) {
    input.classList.toggle('error', !isValid);
    input.classList.toggle('success', isValid);
    const errorEl = input.parentElement.querySelector('.error-message');
    if (errorEl) {
        errorEl.textContent = isValid ? "" : message;
        errorEl.style.display = isValid ? "none" : "block";
    }
}

function sanitizeInput(str) {
    return str.replace(/[<>'"]/g, "");
}

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
                // Solo login
                if (form.id === "loginForm") {
                    const email = form.querySelector('input[name="email"]').value.trim();
                    const password = form.querySelector('input[name="password"]').value;
                    // Admin
                    if (email === "admin@slycipher.com" && password === "Admin123!") {
                        window.location.href = "admin-dashboard.html";
                        return;
                    }
                    // Desarrollador de contenido
                    if (email === "dev@slycipher.com" && password === "Devsly123!") {
                        window.location.href = "dev-dashboard.html";
                        return;
                    }
                    // Usuario normal
                    window.location.href = "dashboard.html";
                    return;
                } // Solo registro
                alert('¡Formulario válido! (Simulación)');
                form.reset();
                form.querySelectorAll('input').forEach(i => {
                    i.classList.remove('success', 'error');
                });
            } else {
                form.querySelectorAll('input[data-validation]').forEach(input => {
                    validateField(input);
                });
            }
        });
    });
});