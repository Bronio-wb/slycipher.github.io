

<?php $__env->startSection('title', 'Práctica: ' . ($desafio->titulo ?? 'Desafío')); ?>
<?php $__env->startSection('content'); ?>
<!-- ...existing code (encabezado, breadcrumb, etc.) ... -->

<div class="container py-4">
	<a href="<?php echo e(route('student.practica.index')); ?>" class="btn btn-link mb-3">&larr; Volver a Prácticas</a>

	<div class="practice-list">
		<div class="practice-card">
			<div class="practice-header">
				<div class="practice-icon"><i class="fas fa-puzzle-piece"></i></div>
				<div>
					<h3 class="practice-title"><?php echo e($desafio->titulo ?? 'Desafío'); ?></h3>
					<div class="practice-meta">
						<?php if($curso): ?> Curso: <strong><?php echo e($curso->titulo); ?></strong> · <?php endif; ?>
						Nivel: <strong><?php echo e($desafio->nivel ?? ($curso->nivel ?? 'N/A')); ?></strong>
					</div>
				</div>
			</div>

			<p class="practice-desc mt-3"><?php echo e($desafio->descripcion ?? 'Descripción no disponible.'); ?></p>

			<div class="practice-footer">
				<div class="practice-badges">
					<span class="badge-small"><?php echo e($desafio->puntos ?? '0'); ?> puntos</span>
					<span class="badge-small"><?php echo e($desafio->tipo ?? 'Ejercicio'); ?></span>
					<?php if($curso): ?>
						<span class="badge-small"><?php echo e($curso->lenguaje->nombre ?? 'Lenguaje'); ?></span>
					<?php endif; ?>
				</div>

				<button type="button" class="solve-btn" data-bs-toggle="modal" data-bs-target="#solveModal" data-challenge-id="<?php echo e($desafio->challenge_id ?? $desafio->id); ?>">
					<i class="fas fa-play me-1"></i> Resolver
				</button>
			</div>
		</div>

		<!-- panel lecciones -->
		<div class="mt-4">
			<h5>Lecciones del curso</h5>
			<?php if($lecciones && $lecciones->count()): ?>
				<ul class="list-group">
					<?php $__currentLoopData = $lecciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leccion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<li class="list-group-item d-flex justify-content-between align-items-center">
							<div>
								<strong><?php echo e($leccion->titulo ?? 'Lección'); ?></strong>
								<div class="small text-muted"><?php echo e(Str::limit($leccion->descripcion ?? '', 120)); ?></div>
							</div>
							<span class="badge bg-secondary"><?php echo e($leccion->duracion ?? '—'); ?></span>
						</li>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</ul>
			<?php else: ?>
				<p class="text-muted">No se encontraron lecciones relacionadas con este desafío.</p>
			<?php endif; ?>
		</div>
	</div>
</div>

<!-- Modal: editor Ace + terminal + testcases -->
<div class="modal fade" id="solveModal" tabindex="-1" aria-labelledby="solveModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="solveModalLabel">Resolver: <?php echo e($desafio->titulo ?? 'Desafío'); ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <p class="text-muted">Escribe y prueba tu solución. "Run" ejecuta localmente (JavaScript). "Enviar solución" envía al servidor para evaluación.</p>

        <div id="solve-alert" class="alert d-none" role="alert"></div>

        <div class="editor-toolbar">
            <button id="saveDraftBtn" class="btn save-btn">Guardar borrador</button>
            <button id="runBtn" class="btn run-btn">Run (local)</button>
            <button id="submitSolutionBtn" class="btn submit-btn">Enviar solución</button>
            <select id="solutionLang" class="form-select w-auto ms-auto">
                <option value="javascript">JavaScript</option>
                <option value="python">Python</option>
                <option value="php">PHP</option>
            </select>
        </div>

        <div class="editor-split">
            <div>
                <!-- Ace Editor placeholder -->
                <div id="aceEditor" style="height:360px;width:100%;border-radius:8px;overflow:hidden;"></div>
            </div>

            <div>
                <div class="lesson-summary mb-3">
                    <div><strong>Información</strong></div>
                    <div class="meta">
                        <?php if($curso): ?> Curso: <?php echo e($curso->titulo); ?> · <?php endif; ?>
                        Desafío: <?php echo e($desafio->titulo ?? '—'); ?> · Puntos: <?php echo e($desafio->puntos ?? 0); ?>

                    </div>
                </div>

                <label class="form-label small">Terminal / Output</label>
                <div id="solutionTerminal" class="code-terminal"><pre id="terminalPre" style="margin:0;white-space:pre-wrap;"></pre></div>

                <div class="testcases-panel mt-3">
                    <strong>Testcases</strong>
                    <div id="testcasesList" class="mt-2">
                        <!-- testcases renderizados por JS -->
                    </div>
                </div>
            </div>
        </div>
      </div>

      <div class="modal-footer">
        <small class="text-muted me-auto">Nota: ejecución local sólo disponible para JavaScript.</small>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- ...existing code ... -->

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<!-- Ace Editor CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.15.2/ace.min.js" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const solveModal = document.getElementById('solveModal');
    const terminalPre = document.getElementById('terminalPre');
    const testcasesList = document.getElementById('testcasesList');
    const saveDraftBtn = document.getElementById('saveDraftBtn');
    const runBtn = document.getElementById('runBtn');
    const submitBtn = document.getElementById('submitSolutionBtn');
    const langSelect = document.getElementById('solutionLang');

    // Testcases provistos por backend si existen (array de {input, expected})
    const TESTCASES = <?php echo json_encode($desafio->testcases ?? [], 15, 512) ?>;

    // Inicializar Ace
    const editor = ace.edit("aceEditor", {
        mode: "ace/mode/javascript",
        theme: "ace/theme/one_dark",
        value: ""
    });
    editor.setOptions({ fontSize: "13pt", enableBasicAutocompletion: true, enableSnippets: true, enableLiveAutocompletion: true });

    // Guardado/recuperado por challenge id
    let currentChallengeId = null;
    function draftKey(id) { return 'slycipher_solution_' + id; }

    // Render testcases UI
    function renderTestcases() {
        testcasesList.innerHTML = '';
        if (!TESTCASES || !TESTCASES.length) {
            testcasesList.innerHTML = '<div class="small text-muted">No hay testcases públicos. Puedes probar manualmente en el terminal.</div>';
            return;
        }
        TESTCASES.forEach((t, idx) => {
            const div = document.createElement('div');
            div.className = 'testcase-item';
            div.innerHTML = `<div><strong>Case #${idx+1}</strong><div class="small text-muted">${t.input ?? ''}</div></div>
                             <div><span id="tc-status-${idx}" class="status status-pending">Pendiente</span></div>`;
            testcasesList.appendChild(div);
        });
    }

    // Mostrar texto en terminal con color
    function printToTerminal(text, type='info') {
        const line = document.createElement('div');
        line.className = 'output-line';
        if (type === 'error') line.classList.add('line-fail');
        if (type === 'success') line.classList.add('line-pass');
        line.textContent = text;
        terminalPre.appendChild(line);
        line.parentElement.scrollTop = line.parentElement.scrollHeight;
    }

    // Ejecutar JS local y comparar testcases
    async function runLocalJS(code) {
        terminalPre.textContent = '';
        // intercept console.log
        const logs = [];
        const originalConsole = {log:console.log, error:console.error};
        console.log = (...args) => { logs.push(args); printToTerminal(args.join(' ')); };
        console.error = (...args) => { printToTerminal('[Error] ' + args.join(' '), 'error'); };

        let result;
        try {
            // Ejecutar en función aislada
            const AsyncFunction = Object.getPrototypeOf(async function(){}).constructor;
            const fn = new AsyncFunction(code);
            result = await fn();
            if (result !== undefined) printToTerminal(String(result), 'success');
        } catch (err) {
            printToTerminal('[Exception] ' + (err && err.message ? err.message : String(err)), 'error');
        } finally {
            console.log = originalConsole.log;
            console.error = originalConsole.error;
        }

        // si hay testcases, intentamos ejecutar la función sobre cada input
        if (TESTCASES && TESTCASES.length) {
            // intentamos detectar una función exportada: buscar "function solve" o "const solve"
            let solveFn = null;
            try {
                // crear otra función que retorne la solve si existe
                const testWrap = new Function(code + '; return (typeof solve !== "undefined") ? solve : null;');
                solveFn = testWrap();
            } catch (e) {
                // ignore
            }
            TESTCASES.forEach((t, idx) => {
                const statusEl = document.getElementById('tc-status-'+idx);
                if (!solveFn) {
                    statusEl.className = 'status status-fail';
                    statusEl.textContent = 'No hay función "solve"';
                    return;
                }
                try {
                    // si input es array, spread; si string, pasar como string
                    let input = t.input;
                    let output = solveFn.apply(null, Array.isArray(input) ? input : [input]);
                    const pass = String(output) === String(t.expected);
                    statusEl.className = pass ? 'status status-pass' : 'status status-fail';
                    statusEl.textContent = pass ? 'OK' : 'FALLA';
                } catch (err) {
                    statusEl.className = 'status status-fail';
                    statusEl.textContent = 'Error';
                }
            });
        }
    }

    // Guardar borrador en localStorage
    saveDraftBtn.addEventListener('click', function () {
        if (!currentChallengeId) { alert('No se identificó el desafío'); return; }
        const code = editor.getValue();
        localStorage.setItem(draftKey(currentChallengeId), code);
        printToTerminal('Borrador guardado localmente.', 'info');
    });

    // Al abrir modal configurar editor y cargar borrador si existe
    solveModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        currentChallengeId = button?.getAttribute('data-challenge-id') || button?.dataset?.challengeId;
        renderTestcases();
        // cargar draft
        const draft = localStorage.getItem(draftKey(currentChallengeId));
        if (draft) editor.setValue(draft, 1);
        else if (!editor.getValue()) {
            editor.setValue('// Escribe tu solución aquí. Si defines una función `solve` la autoevaluaremos con testcases.\n', 1);
        }
        terminalPre.textContent = '';
        document.getElementById('solve-alert').classList.add('d-none');
    });

    // Run local button
    runBtn.addEventListener('click', async function () {
        const lang = langSelect.value;
        if (lang !== 'javascript') {
            printToTerminal('Ejecución local sólo disponible para JavaScript. Usa "Enviar solución" para evaluación en servidor.', 'info');
            return;
        }
        const code = editor.getValue();
        await runLocalJS(code);
    });

    // Submit (envío al servidor)
    submitBtn.addEventListener('click', async function () {
        if (!currentChallengeId) {
            alert('No se identificó el desafío.');
            return;
        }
        const code = editor.getValue();
        const lang = langSelect.value;
        if (!code.trim()) {
            alert('Ingresa tu solución antes de enviar.');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.textContent = 'Enviando...';

        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        const token = tokenMeta ? tokenMeta.getAttribute('content') : '';

        const endpoint = `/student/practica/${encodeURIComponent(currentChallengeId)}/resolver`;

        try {
            const res = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type':'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept':'application/json'
                },
                credentials: 'same-origin',
                body: JSON.stringify({ code, lang })
            });

            if (res.status === 404) {
                document.getElementById('solve-alert').className = 'alert alert-warning';
                document.getElementById('solve-alert').textContent = 'Evaluación en servidor no disponible (404).';
                document.getElementById('solve-alert').classList.remove('d-none');
            } else if (!res.ok) {
                let text = 'Error al enviar. Código: ' + res.status;
                try { const data = await res.json(); if (data?.message) text = data.message; } catch(e) {}
                document.getElementById('solve-alert').className = 'alert alert-danger';
                document.getElementById('solve-alert').textContent = text;
                document.getElementById('solve-alert').classList.remove('d-none');
            } else {
                const data = await res.json();
                if (data?.output) {
                    terminalPre.textContent = data.output;
                }
                document.getElementById('solve-alert').className = 'alert alert-success';
                document.getElementById('solve-alert').textContent = data?.message ?? 'Solución enviada correctamente.';
                document.getElementById('solve-alert').classList.remove('d-none');

                // si backend devuelve test results, actualizar UI de testcases
                if (data?.results && Array.isArray(data.results)) {
                    data.results.forEach((r, idx) => {
                        const statusEl = document.getElementById('tc-status-'+idx);
                        if (statusEl) {
                            statusEl.className = r.passed ? 'status status-pass' : 'status status-fail';
                            statusEl.textContent = r.passed ? 'OK' : 'FALLA';
                        }
                    });
                }
            }
        } catch (err) {
            document.getElementById('solve-alert').className = 'alert alert-danger';
            document.getElementById('solve-alert').textContent = 'Error de red al enviar la solución.';
            document.getElementById('solve-alert').classList.remove('d-none');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Enviar solución';
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\broni\OneDrive\Documentos\Laravel\slycipher\resources\views/student/practica/show.blade.php ENDPATH**/ ?>