"""
Servicio de ejecución de código para desafíos.
Soporta Python y Java con límite de tiempo y tamaño de salida.
"""
import subprocess
import tempfile
import os
import sys
import shutil

TIMEOUT_SECONDS = 10
MAX_OUTPUT_BYTES = 4096
ALLOWED_LANGUAGES = {'python', 'java'}


def _truncate(output: str, max_bytes: int = MAX_OUTPUT_BYTES) -> str:
    if len(output) > max_bytes:
        return output[:max_bytes] + '\n... (salida truncada)'
    return output


def execute_code(language: str, code: str) -> dict:
    """
    Ejecuta código en un proceso aislado.
    Retorna {'success': bool, 'output': str, 'error': str}.
    """
    lang = language.lower()
    if lang not in ALLOWED_LANGUAGES:
        return {'success': False, 'output': '', 'error': f'Lenguaje no soportado: {language}'}

    tmpdir = tempfile.mkdtemp()
    try:
        if lang == 'python':
            return _run_python(code, tmpdir)
        elif lang == 'java':
            return _run_java(code, tmpdir)
    finally:
        shutil.rmtree(tmpdir, ignore_errors=True)


def _run_python(code: str, tmpdir: str) -> dict:
    src = os.path.join(tmpdir, 'solution.py')
    with open(src, 'w', encoding='utf-8') as f:
        f.write(code)

    try:
        proc = subprocess.run(
            [sys.executable, src],
            capture_output=True,
            text=True,
            timeout=TIMEOUT_SECONDS,
            cwd=tmpdir,
        )
        stdout = _truncate(proc.stdout or '')
        stderr = _truncate(proc.stderr or '')
        if proc.returncode == 0:
            return {'success': True, 'output': stdout, 'error': stderr}
        return {'success': False, 'output': stdout, 'error': stderr}
    except subprocess.TimeoutExpired:
        return {'success': False, 'output': '', 'error': f'Tiempo límite excedido ({TIMEOUT_SECONDS}s)'}
    except Exception as exc:
        return {'success': False, 'output': '', 'error': str(exc)}


def _run_java(code: str, tmpdir: str) -> dict:
    # Soporta snippets simples: si no hay clase declarada, envuelve el código en main.
    import re
    raw_code = (code or '').strip()
    has_class = bool(re.search(r'\b(class|interface|enum|record)\b', raw_code))

    if has_class:
        match = re.search(r'public\s+class\s+(\w+)', raw_code)
        class_name = match.group(1) if match else 'Solution'
        source_code = raw_code
    else:
        imports = []
        body_lines = []
        for line in raw_code.splitlines():
            stripped = line.strip()
            if stripped.startswith('import '):
                imports.append(stripped if stripped.endswith(';') else f'{stripped};')
            elif stripped:
                body_lines.append(line)

        body = '\n'.join(body_lines).strip() or 'System.out.println("");'
        indented_body = '\n'.join(f'        {line}' if line.strip() else '' for line in body.splitlines())
        class_name = 'Solution'
        source_code = (
            ('\n'.join(imports) + '\n\n') if imports else ''
        ) + (
            'public class Solution {\n'
            '    public static void main(String[] args) {\n'
            f'{indented_body}\n'
            '    }\n'
            '}\n'
        )

    src = os.path.join(tmpdir, f'{class_name}.java')
    with open(src, 'w', encoding='utf-8') as f:
        f.write(source_code)

    javac = shutil.which('javac')
    java = shutil.which('java')
    if not javac or not java:
        return {'success': False, 'output': '', 'error': 'Java no encontrado en el sistema'}

    # Compilar
    try:
        compile_proc = subprocess.run(
            [javac, src],
            capture_output=True,
            text=True,
            timeout=TIMEOUT_SECONDS,
            cwd=tmpdir,
        )
        if compile_proc.returncode != 0:
            return {'success': False, 'output': '', 'error': _truncate(compile_proc.stderr or '')}
    except subprocess.TimeoutExpired:
        return {'success': False, 'output': '', 'error': 'Tiempo de compilación excedido'}
    except Exception as exc:
        return {'success': False, 'output': '', 'error': str(exc)}

    # Ejecutar
    try:
        run_proc = subprocess.run(
            [java, '-cp', tmpdir, class_name],
            capture_output=True,
            text=True,
            timeout=TIMEOUT_SECONDS,
            cwd=tmpdir,
        )
        stdout = _truncate(run_proc.stdout or '')
        stderr = _truncate(run_proc.stderr or '')
        if run_proc.returncode == 0:
            return {'success': True, 'output': stdout, 'error': stderr}
        return {'success': False, 'output': stdout, 'error': stderr}
    except subprocess.TimeoutExpired:
        return {'success': False, 'output': '', 'error': f'Tiempo límite excedido ({TIMEOUT_SECONDS}s)'}
    except Exception as exc:
        return {'success': False, 'output': '', 'error': str(exc)}
