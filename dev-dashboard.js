// === Simulación de base de datos ===
let cursos = [
    {id: 1, titulo: "Python Básico"},
    {id: 2, titulo: "JavaScript Pro"},
];

let lecciones = [
    {id: 1, curso: 1, titulo: "Variables", contenido: "Contenido sobre variables", estado: "pendiente"},
    {id: 2, curso: 1, titulo: "Condicionales", contenido: "Contenido sobre condicionales", estado: "aprobada"},
    {id: 3, curso: 2, titulo: "Funciones", contenido: "Contenido sobre funciones", estado: "rechazada"},
];

// ========== UTILIDADES ==========
function showSection(section) {
    document.querySelectorAll('.section').forEach(sec => sec.classList.add('hidden'));
    document.getElementById('section-' + section).classList.remove('hidden');
}

// ========== RENDER DE LECCIONES ==========
function renderLecciones() {
    const ul = document.getElementById('leccionesList');
    ul.innerHTML = "";
    lecciones.forEach(lec => {
        const cursoNombre = cursos.find(c => c.id === lec.curso)?.titulo || "Curso desconocido";
        ul.innerHTML += `
            <li>
                <b>${cursoNombre}</b> - <span>${lec.titulo}</span> 
                <span class="${lec.estado === 'aprobada' ? 'valido' : lec.estado === 'rechazada' ? 'inactivo' : 'pendiente'}">[${lec.estado}]</span>
                <button class="btn btn-edit" onclick="editarLeccion(${lec.id})">Editar</button>
                <button class="btn btn-danger" onclick="eliminarLeccion(${lec.id})">Eliminar</button>
            </li>
        `;
    });
}

// ========== RENDER DE CURSOS Y PROPUESTAS ==========
function renderCursosPropuestos() {
    const div = document.getElementById('cursosPropuestos');
    let html = "<h3>Proponer contenido para curso</h3>";
    cursos.forEach(curso => {
        html += `<div style="margin-bottom: 12px;">
            <b>${curso.titulo}</b>
            <form class="propForm" data-curso="${curso.id}" style="display:inline;">
                <input type="text" name="titulo" placeholder="Título lección" required style="margin-left:8px;">
                <input type="text" name="contenido" placeholder="Breve contenido" required style="margin-left:8px;">
                <button type="submit" class="btn btn-success">Proponer</button>
            </form>
        </div>`;
    });
    div.innerHTML = html;

    // Añadir eventos
    document.querySelectorAll('.propForm').forEach(form => {
        form.addEventListener('submit', function(e){
            e.preventDefault();
            const cursoId = parseInt(this.getAttribute('data-curso'));
            const titulo = this.titulo.value.trim();
            const contenido = this.contenido.value.trim();
            if(titulo && contenido) {
                lecciones.push({
                    id: Date.now(),
                    curso: cursoId,
                    titulo,
                    contenido,
                    estado: "pendiente"
                });
                alert("Lección propuesta. Espera la validación del administrador.");
                renderLecciones();
            }
            this.reset();
        });
    });
}

// ========== CREAR, EDITAR, ELIMINAR LECCIONES ==========
function cargarCursosSelect() {
    const sel = document.querySelector('#createLessonForm select[name="curso"]');
    sel.innerHTML = "";
    cursos.forEach(c => {
        sel.innerHTML += `<option value="${c.id}">${c.titulo}</option>`;
    });
}
document.addEventListener('DOMContentLoaded', ()=>{
    showSection('lecciones');
    cargarCursosSelect();
    renderLecciones();
    renderCursosPropuestos();

    // Crear lección normal
    document.getElementById('createLessonForm').addEventListener('submit', function(e){
        e.preventDefault();
        const curso = parseInt(this.curso.value);
        const titulo = this.titulo.value.trim();
        const contenido = this.contenido.value.trim();
        if(titulo && curso && contenido) {
            lecciones.push({
                id: Date.now(),
                curso,
                titulo,
                contenido,
                estado: "pendiente"
            });
            renderLecciones();
            this.reset();
        }
    });
});

function editarLeccion(id) {
    const lec = lecciones.find(l => l.id === id);
    if (!lec) return;
    const nuevoTitulo = prompt("Nuevo título para la lección:", lec.titulo);
    const nuevoContenido = prompt("Nuevo contenido para la lección:", lec.contenido);
    if(nuevoTitulo !== null && nuevoContenido !== null) {
        lec.titulo = nuevoTitulo;
        lec.contenido = nuevoContenido;
        renderLecciones();
    }
}
function eliminarLeccion(id) {
    const idx = lecciones.findIndex(l => l.id === id);
    if(idx !== -1) {
        if(confirm("¿Seguro que deseas eliminar esta lección?")) {
            lecciones.splice(idx, 1);
            renderLecciones();
        }
    }
}

// Para navegación desde sidebar
window.showSection = showSection;
window.editarLeccion = editarLeccion;
window.eliminarLeccion = eliminarLeccion;