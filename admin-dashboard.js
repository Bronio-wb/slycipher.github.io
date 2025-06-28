/* 
    ADMIN DASHBOARD JS - SlyCipher
    --------------------------------
    Funcionalidades para el admin:
    - Listar, asignar roles y eliminar usuarios
    - Restablecer contraseñas
    - Ver si los usuarios están activos/inactivos y sus rachas activas
    - Ver estadísticas generales y de elecciones
    - Activar/desactivar funcionalidades del sistema
    - Validar y controlar el contenido: cursos y lecciones (crear, editar, eliminar)
    - Todo con estructura y comentarios claros para el equipo
*/

// ================ DATOS SIMULADOS ================
let users = [
    {
        id: 1,
        nombre: "Juan Pérez",
        email: "juan@correo.com",
        rol: "Usuario",
        activo: true,
        racha: 5,
        elecciones: {python: true, js: false},
    },
    {
        id: 2,
        nombre: "Ana Gómez",
        email: "ana@correo.com",
        rol: "Usuario",
        activo: false,
        racha: 0,
        elecciones: {python: true, js: true},
    },
    {
        id: 3,
        nombre: "Carlos Ruiz",
        email: "carlos@correo.com",
        rol: "Desarrollador",
        activo: true,
        racha: 12,
        elecciones: {python: false, js: true},
    },
    {
        id: 4,
        nombre: "Admin",
        email: "admin@slycipher.com",
        rol: "Administrador",
        activo: true,
        racha: 99,
        elecciones: {python: true, js: true},
    }
];

let courses = [
    { id: 1, titulo: "Python Básico", descripcion: "Aprende Python desde cero.", validado: true, lecciones: 12 },
    { id: 2, titulo: "JavaScript Pro", descripcion: "Domina JS moderno.", validado: false, lecciones: 8 }
];

let lecciones = [
    { id: 1, curso: "Python Básico", titulo: "Variables", estado: "pendiente" },
    { id: 2, curso: "Python Básico", titulo: "Condicionales", estado: "aprobada" },
    { id: 3, curso: "JavaScript Pro", titulo: "Funciones", estado: "pendiente" }
];

let systemFeatures = [
    { name: "Chat de soporte", active: true },
    { name: "Ranking global", active: true },
    { name: "Modo oscuro", active: false },
    { name: "Foro de preguntas", active: true }
];

// =============== UTILIDADES ===============
function showSection(section) {
    document.querySelectorAll('.section').forEach(sec => sec.classList.add('hidden'));
    document.getElementById(`section-${section}`).classList.remove('hidden');
}
function showContentSubsection(tipo) {
    document.querySelectorAll('.content-subsection').forEach(sub => sub.classList.add('hidden'));
    document.getElementById('content-' + tipo).classList.remove('hidden');
}

// =============== GESTIÓN DE USUARIOS ===============
function renderUsers() {
    const tbody = document.getElementById('usersBody');
    tbody.innerHTML = "";
    users.forEach((user, idx) => {
        tbody.innerHTML += `
            <tr>
                <td>${user.nombre}</td>
                <td>${user.email}</td>
                <td>
                    <select class="role-select" data-idx="${idx}" ${user.rol === "Administrador" ? "disabled" : ""}>
                        <option value="Usuario" ${user.rol === "Usuario" ? "selected" : ""}>Usuario</option>
                        <option value="Desarrollador" ${user.rol === "Desarrollador" ? "selected" : ""}>Desarrollador</option>
                        <option value="Administrador" ${user.rol === "Administrador" ? "selected" : ""}>Administrador</option>
                    </select>
                </td>
                <td>
                    <span class="${user.activo ? 'activo' : 'inactivo'}">${user.activo ? 'Activo' : 'Inactivo'}</span>
                </td>
                <td>
                    ${user.racha} ${user.racha === 1 ? 'día' : 'días'}
                </td>
                <td>
                    <button class="btn btn-warning" onclick="resetPassword(${user.id})" ${user.rol === "Administrador" ? "disabled" : ""}>Restablecer contraseña</button>
                    <button class="btn btn-danger" onclick="deleteUser(${user.id})" ${user.rol === "Administrador" ? "disabled" : ""}>Eliminar</button>
                </td>
            </tr>
        `;
    });
    // Cambiar roles
    document.querySelectorAll('.role-select').forEach(sel => {
        sel.addEventListener('change', function() {
            const idx = this.getAttribute('data-idx');
            users[idx].rol = this.value;
            renderUsers();
            renderStats();
        });
    });
}
function resetPassword(userId) {
    const user = users.find(u => u.id === userId);
    if (user) {
        alert(`Se ha enviado un correo de restablecimiento a ${user.email} (simulado)`);
    }
}
function deleteUser(userId) {
    const idx = users.findIndex(u => u.id === userId);
    if (idx !== -1 && users[idx].rol !== "Administrador") {
        if (confirm(`¿Seguro que deseas eliminar a ${users[idx].nombre}?`)) {
            users.splice(idx, 1);
            renderUsers();
            renderStats();
        }
    }
}

// =============== GESTIÓN DE FUNCIONES DEL SISTEMA ===============
function renderFeatures() {
    const container = document.getElementById('featureList');
    container.innerHTML = "";
    systemFeatures.forEach((feature, idx) => {
        container.innerHTML += `
            <div>
                <label>
                    <input type="checkbox" ${feature.active ? "checked" : ""} onchange="toggleFeature(${idx})">
                    ${feature.name}
                </label>
            </div>
        `;
    });
}
function toggleFeature(idx) {
    systemFeatures[idx].active = !systemFeatures[idx].active;
    renderFeatures();
}

// =============== GESTIÓN DE CURSOS ===============
function renderCourses() {
    const ul = document.getElementById('coursesList');
    if (!ul) return;
    ul.innerHTML = "";
    courses.forEach(course => {
        ul.innerHTML += `
            <li>
                <b>${course.titulo}</b> - ${course.descripcion}
                <span class="${course.validado ? 'valido' : 'pendiente'}">[${course.validado ? 'Validado' : 'Pendiente'}]</span>
                <button class="btn btn-edit" onclick="editCourse(${course.id})">Editar</button>
                <button class="btn ${course.validado ? 'btn-secondary btn-invalidar' : 'btn-success'}" onclick="validateCourse(${course.id})">${course.validado ? 'Invalidar' : 'Validar'}</button>
                <button class="btn btn-danger" onclick="deleteCourse(${course.id})">Eliminar</button>
            </li>`;
    });
}
function editCourse(courseId) {
    const course = courses.find(c => c.id === courseId);
    if (course) {
        const nuevoTitulo = prompt("Nuevo título para el curso:", course.titulo);
        const nuevaDesc = prompt("Nueva descripción:", course.descripcion);
        if (nuevoTitulo !== null && nuevaDesc !== null) {
            course.titulo = nuevoTitulo;
            course.descripcion = nuevaDesc;
            renderCourses();
            renderStats();
            renderChart();
        }
    }
}
function validateCourse(courseId) {
    const course = courses.find(c => c.id === courseId);
    if (course) {
        course.validado = !course.validado;
        renderCourses();
    }
}
function deleteCourse(courseId) {
    const idx = courses.findIndex(c => c.id === courseId);
    if (idx !== -1) {
        if (confirm("¿Seguro que deseas eliminar este curso?")) {
            courses.splice(idx, 1);
            renderCourses();
            renderStats();
            renderChart();
        }
    }
}
document.addEventListener('DOMContentLoaded', () => {
    // Para sección cursos
    if (document.getElementById('createCourseForm')) {
        document.getElementById('createCourseForm').addEventListener('submit', function(e){
            e.preventDefault();
            const titulo = this.titulo.value.trim();
            const descripcion = this.descripcion.value.trim();
            if(titulo && descripcion){
                courses.push({ id: Date.now(), titulo, descripcion, validado: false, lecciones: 0 });
                renderCourses();
                renderStats();
                renderChart();
                this.reset();
            }
        });
    }
});

// =============== GESTIÓN DE LECCIONES ===============
function renderLecciones() {
    const cont = document.getElementById('content-lecciones');
    if (!cont) return;
    let html = "<ul>";
    lecciones.forEach(lec => {
        html += `<li>${lec.curso} - <b>${lec.titulo}</b> 
            <span class="${lec.estado === 'aprobada' ? 'valido' : 'pendiente'}">[${lec.estado}]</span>
            <button class="btn ${lec.estado === 'aprobada' ? 'btn-secondary btn-invalidar' : 'btn-success'}" onclick="validateLeccion(${lec.id})">${lec.estado === 'aprobada' ? 'Invalidar' : 'Aprobar'}</button>
            <button class="btn btn-edit" onclick="editLeccion(${lec.id})">Editar</button>
            <button class="btn btn-danger" onclick="deleteLeccion(${lec.id})">Eliminar</button>
        </li>`;
    });
    html += "</ul>";
    cont.innerHTML = html;
}
function validateLeccion(lecId) {
    const lec = lecciones.find(l => l.id === lecId);
    if (lec) {
        lec.estado = lec.estado === "aprobada" ? "pendiente" : "aprobada";
        renderLecciones();
    }
}
function editLeccion(lecId) {
    const lec = lecciones.find(l => l.id === lecId);
    if (lec) {
        const nuevoTitulo = prompt("Nuevo título para la lección:", lec.titulo);
        if (nuevoTitulo !== null) {
            lec.titulo = nuevoTitulo;
            renderLecciones();
        }
    }
}
function deleteLeccion(lecId) {
    const idx = lecciones.findIndex(l => l.id === lecId);
    if (idx !== -1) {
        if (confirm("¿Seguro que deseas eliminar esta lección?")) {
            lecciones.splice(idx, 1);
            renderLecciones();
        }
    }
}

// =============== ESTADÍSTICAS ===============
function renderStats() {
    // Usuarios
    const total = users.length;
    const activos = users.filter(u => u.activo).length;
    const desarrolladores = users.filter(u => u.rol === "Desarrollador").length;
    const admins = users.filter(u => u.rol === "Administrador").length;
    const statsDiv = document.getElementById('stats');
    if (statsDiv) {
        statsDiv.innerHTML = `
            <div>Usuarios totales: <b>${total}</b></div>
            <div>Usuarios activos: <b>${activos}</b></div>
            <div>Administradores: <b>${admins}</b></div>
            <div>Desarrolladores: <b>${desarrolladores}</b></div>
            <div>Promedio de racha activa: <b>${averageRacha()} días</b></div>
        `;
    }
    // Tarjetas simples si existen
    if(document.getElementById('statUsers')) document.getElementById('statUsers').textContent = total;
    if(document.getElementById('statCourses')) document.getElementById('statCourses').textContent = courses.length;
}
function averageRacha() {
    if (users.length === 0) return 0;
    const sum = users.reduce((acc, curr) => acc + curr.racha, 0);
    return (sum / users.length).toFixed(1);
}
function renderChart() {
    if (!document.getElementById('statsChart')) return;
    if (window.statsChartInstance) window.statsChartInstance.destroy();
    // Estadísticas de elecciones (por ejemplo, preferencia de lenguaje)
    const python = users.filter(u => u.elecciones.python).length;
    const js = users.filter(u => u.elecciones.js).length;
    const ctx = document.getElementById('statsChart').getContext('2d');
    window.statsChartInstance = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Python', 'JavaScript'],
            datasets: [{
                data: [python, js],
                backgroundColor: ['#ffcc00', '#ff4d4d']
            }]
        },
        options: {
            plugins: {legend: {display: true, position: "right"}},
        }
    });
}

// =============== INICIALIZACIÓN ===============
document.addEventListener('DOMContentLoaded', () => {
    // Sección por defecto
    showSection('usuarios');
    showContentSubsection('lecciones');
    // Renderizar todo
    renderUsers();
    renderFeatures();
    renderCourses();
    renderLecciones();
    renderStats();
    renderChart();
    // Botones para subsecciones de contenido
    window.showSection = showSection;
    window.showContentSubsection = showContentSubsection;
    window.resetPassword = resetPassword;
    window.deleteUser = deleteUser;
    window.toggleFeature = toggleFeature;
    window.editCourse = editCourse;
    window.validateCourse = validateCourse;
    window.deleteCourse = deleteCourse;
    window.editLeccion = editLeccion;
    window.validateLeccion = validateLeccion;
    window.deleteLeccion = deleteLeccion;
});