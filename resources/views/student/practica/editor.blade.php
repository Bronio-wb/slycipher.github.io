@extends('layouts.app')

@section('title', 'Editor de Práctica - ' . $desafio->titulo)
@section('page-title', 'Editor de Práctica')

@push('styles')
<!-- Monaco Editor -->
<link rel="stylesheet" data-name="vs/editor/editor.main" href="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.34.1/min/vs/editor/editor.main.min.css">

<style>
/* Estilo del editor inspirado en Codedx */
.editor-container {
    background: #1a1a2e;
    min-height: 100vh;
    color: white;
}

.editor-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-template-rows: auto 1fr auto;
    height: 100vh;
    gap: 1px;
    background: #1a1a2e;
}

.editor-header {
    grid-column: 1 / -1;
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(10px);
    padding: 1rem 2rem;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.challenge-info {
    flex: 1;
}

.challenge-title {
    font-size: 1.3rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
    color: #fff;
}

.challenge-breadcrumb {
    color: rgba(255,255,255,0.7);
    font-size: 0.9rem;
}

.editor-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.language-selector {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    outline: none;
}

.language-selector:focus {
    border-color: #4ecdc4;
    box-shadow: 0 0 0 2px rgba(78, 205, 196, 0.2);
}

.btn-editor {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-editor:hover {
    background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
    transform: translateY(-1px);
}

.btn-editor:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.btn-run {
    background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
}

.btn-run:hover {
    background: linear-gradient(135deg, #45b7b8 0%, #3d8e7a 100%);
}

.btn-save {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.btn-save:hover {
    background: linear-gradient(135deg, #ed7fb5 0%, #e84a5f 100%);
}

.problem-panel {
    background: rgba(255,255,255,0.02);
    border-right: 1px solid rgba(255,255,255,0.1);
    overflow-y: auto;
    padding: 2rem;
}

.editor-panel {
    background: #1e1e1e;
    position: relative;
}

.output-panel {
    grid-column: 1 / -1;
    background: rgba(255,255,255,0.03);
    border-top: 1px solid rgba(255,255,255,0.1);
    max-height: 300px;
    overflow-y: auto;
}

.output-tabs {
    display: flex;
    background: rgba(255,255,255,0.05);
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.output-tab {
    padding: 0.75rem 1.5rem;
    background: transparent;
    border: none;
    color: rgba(255,255,255,0.7);
    cursor: pointer;
    transition: all 0.3s ease;
    border-bottom: 2px solid transparent;
}

.output-tab.active {
    color: #4ecdc4;
    border-bottom-color: #4ecdc4;
    background: rgba(78, 205, 196, 0.1);
}

.output-content {
    padding: 1.5rem;
    font-family: 'Fira Code', 'Consolas', monospace;
    font-size: 0.9rem;
    line-height: 1.5;
    white-space: pre-wrap;
}

.output-success {
    color: #4caf50;
}

.output-error {
    color: #f44336;
}

.output-info {
    color: #2196f3;
}

.problem-title {
    font-size: 1.4rem;
    font-weight: bold;
    margin-bottom: 1rem;
    color: #fff;
}

.problem-description {
    color: rgba(255,255,255,0.8);
    line-height: 1.6;
    margin-bottom: 2rem;
}

.problem-meta {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.meta-item {
    background: rgba(255,255,255,0.05);
    padding: 1rem;
    border-radius: 8px;
    text-align: center;
}

.meta-label {
    color: rgba(255,255,255,0.6);
    font-size: 0.8rem;
    margin-bottom: 0.5rem;
}

.meta-value {
    color: #4ecdc4;
    font-weight: bold;
    font-size: 1.1rem;
}

.examples-section {
    margin-top: 2rem;
}

.example {
    background: rgba(255,255,255,0.05);
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
    border-left: 4px solid #4ecdc4;
}

.example-title {
    font-weight: bold;
    color: #4ecdc4;
    margin-bottom: 0.5rem;
}

.example-content {
    font-family: 'Fira Code', 'Consolas', monospace;
    font-size: 0.85rem;
    color: rgba(255,255,255,0.9);
}

.test-results {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
}

.test-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: bold;
}

.test-passed {
    background: rgba(76, 175, 80, 0.2);
    color: #4caf50;
    border: 1px solid rgba(76, 175, 80, 0.3);
}

.test-failed {
    background: rgba(244, 67, 54, 0.2);
    color: #f44336;
    border: 1px solid rgba(244, 67, 54, 0.3);
}

.loading-indicator {
    display: none;
    text-align: center;
    padding: 2rem;
    color: rgba(255,255,255,0.7);
}

.loading-indicator.show {
    display: block;
}

.completion-celebration {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
    padding: 2rem;
    border-radius: 15px;
    text-align: center;
    z-index: 1000;
    display: none;
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
}

.completion-celebration.show {
    display: block;
    animation: celebrationPop 0.5s ease;
}

@keyframes celebrationPop {
    0% {
        transform: translate(-50%, -50%) scale(0.8);
        opacity: 0;
    }
    100% {
        transform: translate(-50%, -50%) scale(1);
        opacity: 1;
    }
}

@media (max-width: 768px) {
    .editor-layout {
        grid-template-columns: 1fr;
        grid-template-rows: auto auto 1fr auto;
    }
    
    .editor-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .editor-actions {
        justify-content: center;
        flex-wrap: wrap;
    }
}
</style>
@endpush

@section('content')
<div class="editor-container">
    <div class="editor-layout">
        <!-- Header -->
        <div class="editor-header">
            <div class="challenge-info">
                <div class="challenge-title">{{ $desafio->titulo }}</div>
                <div class="challenge-breadcrumb">
                    <a href="{{ route('student.practica.index') }}" class="text-decoration-none text-light opacity-75">
                        <i class="fas fa-arrow-left me-1"></i>
                        Volver a Práctica
                    </a>
                    <span class="mx-2">•</span>
                    {{ $desafio->curso->titulo }}
                </div>
            </div>
            
            <div class="editor-actions">
                <select class="language-selector" id="languageSelector">
                    <option value="python">Python</option>
                    <option value="javascript">JavaScript</option>
                    <option value="java">Java</option>
                    <option value="php">PHP</option>
                </select>
                
                <button class="btn-editor btn-save" id="saveBtn">
                    <i class="fas fa-save me-1"></i>
                    Guardar
                </button>
                
                <button class="btn-editor btn-run" id="runBtn">
                    <i class="fas fa-play me-1"></i>
                    Ejecutar
                </button>
            </div>
        </div>
        
        <!-- Panel del problema -->
        <div class="problem-panel">
            <div class="problem-title">{{ $desafio->titulo }}</div>
            
            <div class="problem-meta">
                <div class="meta-item">
                    <div class="meta-label">Dificultad</div>
                    <div class="meta-value">{{ ucfirst($desafio->dificultad) }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Tipo</div>
                    <div class="meta-value">{{ ucfirst($desafio->dificultad) }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">Intentos</div>
                    <div class="meta-value" id="attemptsCount">{{ $progreso->intentos }}</div>
                </div>
            </div>
            
            <div class="problem-description">
                {!! nl2br(e($desafio->descripcion)) !!}
            </div>
            
            <div class="examples-section">
                <h5 class="text-light mb-3">
                    <i class="fas fa-lightbulb me-2"></i>
                    Solución de Referencia
                </h5>
                
                <div class="example">
                    <div class="example-title">Código esperado:</div>
                    <div class="example-content">{{ $desafio->solucion }}</div>
                </div>
            </div>
        </div>
        
        <!-- Panel del editor -->
        <div class="editor-panel">
            <div id="codeEditor" style="height: 100%; width: 100%;"></div>
        </div>
        
        <!-- Panel de salida -->
        <div class="output-panel">
            <div class="output-tabs">
                <button class="output-tab active" data-tab="output">
                    <i class="fas fa-terminal me-1"></i>
                    Salida
                </button>
                <button class="output-tab" data-tab="tests">
                    <i class="fas fa-check-circle me-1"></i>
                    Tests
                </button>
                <button class="output-tab" data-tab="errors">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Errores
                </button>
            </div>
            
            <div class="output-content">
                <div id="outputContent" class="tab-content active">
                    <div class="loading-indicator" id="loadingIndicator">
                        <i class="fas fa-spinner fa-spin me-2"></i>
                        Ejecutando código...
                    </div>
                    <div id="outputText">Haz clic en "Ejecutar" para ver la salida de tu código.</div>
                </div>
                
                <div id="testsContent" class="tab-content">
                    <div id="testResults">No hay resultados de tests aún.</div>
                </div>
                
                <div id="errorsContent" class="tab-content">
                    <div id="errorText">No hay errores.</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de celebración -->
    <div class="completion-celebration" id="completionModal">
        <div class="text-center">
            <i class="fas fa-trophy fa-4x text-warning mb-3"></i>
            <h3>¡Desafío Completado!</h3>
            <p>Has ganado <strong>20</strong> puntos</p>
            <button class="btn-editor" onclick="hideCompletionModal()">Continuar</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Monaco Editor -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.34.1/min/vs/loader.min.js"></script>

<script>
let editor;
let currentLanguage = 'python';

// Código inicial por lenguaje
const initialCode = {
    python: @json($codigoInicial ?: "# Escribe tu código aquí\nprint('¡Hola Mundo!')"),
    javascript: "// Escribe tu código aquí\nconsole.log('¡Hola Mundo!');",
    java: "public class Main {\n    public static void main(String[] args) {\n        System.out.println(\"¡Hola Mundo!\");\n    }\n}",
    php: "<?php\n// Escribe tu código aquí\necho '¡Hola Mundo!';\n?>"
};

// Inicializar Monaco Editor
require.config({ paths: { vs: 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.34.1/min/vs' } });

require(['vs/editor/editor.main'], function () {
    editor = monaco.editor.create(document.getElementById('codeEditor'), {
        value: initialCode[currentLanguage],
        language: currentLanguage,
        theme: 'vs-dark',
        automaticLayout: true,
        fontSize: 14,
        minimap: { enabled: false },
        scrollBeyondLastLine: false,
        wordWrap: 'on',
        lineNumbers: 'on',
        folding: true,
        cursorBlinking: 'blink',
        renderWhitespace: 'selection'
    });
    
    // Auto-guardar cada 30 segundos
    setInterval(autoSave, 30000);
});

// Cambio de lenguaje
document.getElementById('languageSelector').addEventListener('change', function() {
    currentLanguage = this.value;
    
    if (editor) {
        const currentCode = editor.getValue();
        
        // Solo cambiar si el código es el inicial
        if (currentCode === initialCode[currentLanguage] || currentCode.trim() === '') {
            editor.setValue(initialCode[currentLanguage]);
        }
        
        monaco.editor.setModelLanguage(editor.getModel(), currentLanguage);
    }
});

// Botón ejecutar
document.getElementById('runBtn').addEventListener('click', function() {
    executeCode();
});

// Botón guardar
document.getElementById('saveBtn').addEventListener('click', function() {
    saveCode();
});

// Tabs de salida
document.querySelectorAll('.output-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        const targetTab = this.dataset.tab;
        
        // Actualizar tabs activos
        document.querySelectorAll('.output-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        
        this.classList.add('active');
        document.getElementById(targetTab + 'Content').classList.add('active');
    });
});

// Función para ejecutar código
async function executeCode() {
    const code = editor.getValue();
    const runBtn = document.getElementById('runBtn');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const outputText = document.getElementById('outputText');
    
    if (!code.trim()) {
        alert('Por favor escribe algo de código antes de ejecutar.');
        return;
    }
    
    // Mostrar loading
    runBtn.disabled = true;
    runBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Ejecutando...';
    loadingIndicator.classList.add('show');
    outputText.textContent = '';
    
    try {
        const response = await fetch(`{{ route('student.practica.ejecutar', $desafio->id) }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                codigo: code,
                lenguaje: currentLanguage
            })
        });
        
        const result = await response.json();
        
        // Actualizar contador de intentos
        document.getElementById('attemptsCount').textContent = parseInt(document.getElementById('attemptsCount').textContent) + 1;
        
        // Mostrar resultados
        displayResults(result);
        
        // Si se completó el desafío
        if (result.completed) {
            showCompletionModal();
        }
        
    } catch (error) {
        console.error('Error:', error);
        outputText.textContent = 'Error de conexión. Por favor intenta nuevamente.';
        outputText.className = 'output-error';
    } finally {
        // Ocultar loading
        runBtn.disabled = false;
        runBtn.innerHTML = '<i class="fas fa-play me-1"></i>Ejecutar';
        loadingIndicator.classList.remove('show');
    }
}

// Función para mostrar resultados
function displayResults(result) {
    const outputText = document.getElementById('outputText');
    const testResults = document.getElementById('testResults');
    const errorText = document.getElementById('errorsContent');
    
    // Salida
    outputText.textContent = result.output || 'Sin salida';
    outputText.className = result.error ? 'output-error' : 'output-success';
    
    // Tests
    if (result.total_tests > 0) {
        testResults.innerHTML = `
            <div class="test-results">
                <span class="test-badge test-passed">${result.tests_passed} Pasaron</span>
                <span class="test-badge test-failed">${result.total_tests - result.tests_passed} Fallaron</span>
            </div>
            <div>Progreso: ${result.tests_passed}/${result.total_tests} tests completados</div>
        `;
    }
    
    // Errores
    if (result.error) {
        errorText.innerHTML = `<div class="output-error">${result.error}</div>`;
        
        // Cambiar a tab de errores automáticamente
        document.querySelector('[data-tab="errors"]').click();
    } else {
        errorText.innerHTML = '<div class="output-success">No hay errores.</div>';
    }
}

// Función para guardar código
async function saveCode() {
    const code = editor.getValue();
    const saveBtn = document.getElementById('saveBtn');
    
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Guardando...';
    
    try {
        const response = await fetch(`{{ route('student.practica.guardar', $desafio->id) }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                codigo: code
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            saveBtn.innerHTML = '<i class="fas fa-check me-1"></i>Guardado';
            setTimeout(() => {
                saveBtn.innerHTML = '<i class="fas fa-save me-1"></i>Guardar';
            }, 2000);
        }
        
    } catch (error) {
        console.error('Error:', error);
        alert('Error al guardar el código');
    } finally {
        saveBtn.disabled = false;
    }
}

// Auto-guardar
function autoSave() {
    if (editor) {
        saveCode();
    }
}

// Modal de celebración
function showCompletionModal() {
    document.getElementById('completionModal').classList.add('show');
}

function hideCompletionModal() {
    document.getElementById('completionModal').classList.remove('show');
}

// Atajos de teclado
document.addEventListener('keydown', function(e) {
    // Ctrl+Enter para ejecutar
    if (e.ctrlKey && e.key === 'Enter') {
        e.preventDefault();
        executeCode();
    }
    
    // Ctrl+S para guardar
    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        saveCode();
    }
});
</script>

<style>
.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}
</style>
@endpush