/**
 * SLYCIPHER - JavaScript Principal
 * Funcionalidades generales de la plataforma de aprendizaje
 */

// Configuración global
const SLYCIPHER = {
    version: '1.0.0',
    apiUrl: '/api',
    timeout: 5000,
    debug: true
};

// Utility Functions
const Utils = {
    // Mostrar notificaciones toast
    showToast: function(message, type = 'info', duration = 3000) {
        // Crear elemento toast si no existe
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }
        
        const toastId = 'toast-' + Date.now();
        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-white bg-${type} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        
        toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, { delay: duration });
        toast.show();
        
        // Limpiar después de que se oculte
        toastElement.addEventListener('hidden.bs.toast', function() {
            this.remove();
        });
    },
    
    // Confirmar acción con modal
    confirmAction: function(message, callback) {
        if (confirm(message)) {
            callback();
        }
    },
    
    // Formatear números
    formatNumber: function(num) {
        return new Intl.NumberFormat('es-ES').format(num);
    },
    
    // Formatear fechas
    formatDate: function(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('es-ES', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    },
    
    // Validar email
    isValidEmail: function(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    },
    
    // Copiar al portapapeles
    copyToClipboard: function(text, successMessage = 'Copiado al portapapeles') {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(function() {
                Utils.showToast(successMessage, 'success');
            }).catch(function(err) {
                console.error('Error al copiar: ', err);
                Utils.showToast('Error al copiar', 'danger');
            });
        } else {
            // Fallback para navegadores antiguos
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            Utils.showToast(successMessage, 'success');
        }
    }
};

// Gestión de Formularios
const FormManager = {
    // Validación en tiempo real
    setupRealTimeValidation: function() {
        const forms = document.querySelectorAll('.needs-validation');
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    this.classList.remove('is-invalid');
                    if (this.checkValidity()) {
                        this.classList.add('is-valid');
                    }
                });
            });
        });
    },
    
    // Envío de formularios con AJAX
    submitForm: function(formId, callback) {
        const form = document.getElementById(formId);
        if (!form) return;
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Mostrar loading
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Enviando...';
            submitBtn.disabled = true;
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Utils.showToast(data.message || 'Operación exitosa', 'success');
                    if (callback) callback(data);
                } else {
                    Utils.showToast(data.message || 'Error en la operación', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Utils.showToast('Error de conexión', 'danger');
            })
            .finally(() => {
                // Restaurar botón
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    },
    
    // Contador de caracteres
    setupCharacterCounters: function() {
        const textareas = document.querySelectorAll('textarea[maxlength]');
        textareas.forEach(textarea => {
            const maxLength = parseInt(textarea.getAttribute('maxlength'));
            const counterId = textarea.id + '-counter';
            
            // Crear contador si no existe
            let counter = document.getElementById(counterId);
            if (!counter) {
                counter = document.createElement('small');
                counter.id = counterId;
                counter.className = 'form-text text-muted';
                textarea.parentNode.appendChild(counter);
            }
            
            const updateCounter = () => {
                const remaining = maxLength - textarea.value.length;
                counter.textContent = `${remaining} caracteres restantes`;
                counter.className = remaining < 50 ? 'form-text text-danger' : 'form-text text-muted';
            };
            
            textarea.addEventListener('input', updateCounter);
            updateCounter(); // Actualizar al cargar
        });
    }
};

// Gestión de Tablas
const TableManager = {
    // Búsqueda en tablas
    setupTableSearch: function(tableId, searchInputId) {
        const table = document.getElementById(tableId);
        const searchInput = document.getElementById(searchInputId);
        
        if (!table || !searchInput) return;
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    },
    
    // Ordenamiento de columnas
    setupColumnSorting: function(tableId) {
        const table = document.getElementById(tableId);
        if (!table) return;
        
        const headers = table.querySelectorAll('th[data-sort]');
        headers.forEach(header => {
            header.style.cursor = 'pointer';
            header.innerHTML += ' <i class="fas fa-sort text-muted"></i>';
            
            header.addEventListener('click', function() {
                const column = this.getAttribute('data-sort');
                const isAscending = this.classList.contains('sort-asc');
                
                // Remover clases de ordenamiento de otros headers
                headers.forEach(h => h.classList.remove('sort-asc', 'sort-desc'));
                
                // Aplicar nueva clase
                this.classList.add(isAscending ? 'sort-desc' : 'sort-asc');
                
                // Ordenar filas
                TableManager.sortTableByColumn(table, column, !isAscending);
            });
        });
    },
    
    sortTableByColumn: function(table, column, ascending = true) {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        rows.sort((a, b) => {
            const aValue = a.querySelector(`[data-value="${column}"]`)?.textContent || '';
            const bValue = b.querySelector(`[data-value="${column}"]`)?.textContent || '';
            
            if (ascending) {
                return aValue.localeCompare(bValue, 'es', { numeric: true });
            } else {
                return bValue.localeCompare(aValue, 'es', { numeric: true });
            }
        });
        
        rows.forEach(row => tbody.appendChild(row));
    }
};

// Gestión de Progreso
const ProgressManager = {
    // Actualizar barra de progreso
    updateProgress: function(elementId, percentage) {
        const progressBar = document.querySelector(`#${elementId} .progress-bar`);
        if (progressBar) {
            progressBar.style.width = percentage + '%';
            progressBar.setAttribute('aria-valuenow', percentage);
            progressBar.textContent = Math.round(percentage) + '%';
        }
    },
    
    // Animar progreso
    animateProgress: function(elementId, targetPercentage, duration = 1000) {
        const progressBar = document.querySelector(`#${elementId} .progress-bar`);
        if (!progressBar) return;
        
        const startPercentage = 0;
        const increment = targetPercentage / (duration / 16); // 60fps
        let currentPercentage = startPercentage;
        
        const timer = setInterval(() => {
            currentPercentage += increment;
            if (currentPercentage >= targetPercentage) {
                currentPercentage = targetPercentage;
                clearInterval(timer);
            }
            
            ProgressManager.updateProgress(elementId.replace('#', ''), currentPercentage);
        }, 16);
    }
};

// Gestión de Código
const CodeManager = {
    // Resaltar sintaxis (básico)
    highlightCode: function(codeElement) {
        if (!codeElement) return;
        
        let code = codeElement.textContent;
        
        // Palabras clave básicas
        const keywords = ['function', 'var', 'let', 'const', 'if', 'else', 'for', 'while', 'return', 'class', 'import', 'export'];
        keywords.forEach(keyword => {
            const regex = new RegExp(`\\b${keyword}\\b`, 'g');
            code = code.replace(regex, `<span class="text-primary fw-bold">${keyword}</span>`);
        });
        
        // Strings
        code = code.replace(/"([^"]*)"/g, '<span class="text-success">"$1"</span>');
        code = code.replace(/'([^']*)'/g, '<span class="text-success">\'$1\'</span>');
        
        // Comentarios
        code = code.replace(/\/\/(.*)/g, '<span class="text-muted">\/\/$1</span>');
        
        codeElement.innerHTML = code;
    },
    
    // Copiar código
    setupCodeCopyButtons: function() {
        const codeBlocks = document.querySelectorAll('.code-block');
        codeBlocks.forEach(block => {
            const copyBtn = document.createElement('button');
            copyBtn.className = 'btn btn-sm btn-outline-light position-absolute top-0 end-0 m-2';
            copyBtn.innerHTML = '<i class="fas fa-copy"></i>';
            copyBtn.onclick = () => Utils.copyToClipboard(block.textContent, 'Código copiado');
            
            block.style.position = 'relative';
            block.appendChild(copyBtn);
        });
    }
};

// Inicialización cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar componentes
    FormManager.setupRealTimeValidation();
    FormManager.setupCharacterCounters();
    CodeManager.setupCodeCopyButtons();
    
    // Resaltar código
    document.querySelectorAll('code').forEach(CodeManager.highlightCode);
    
    // Tooltips de Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function(tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Popovers de Bootstrap
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.forEach(function(popoverTriggerEl) {
        new bootstrap.Popover(popoverTriggerEl);
    });
    
    // Confirmación de eliminación
    document.querySelectorAll('[data-confirm]').forEach(function(element) {
        element.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm');
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
    
    // Auto-hide alerts
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
    
    console.log('SLYCIPHER v' + SLYCIPHER.version + ' iniciado correctamente');
});

// Funciones globales para usar en las vistas
window.SLYCIPHER = SLYCIPHER;
window.Utils = Utils;
window.FormManager = FormManager;
window.TableManager = TableManager;
window.ProgressManager = ProgressManager;
window.CodeManager = CodeManager;