import json
import re

from django.contrib import messages
from django.contrib.auth.decorators import login_required
from django.http import JsonResponse
from django.shortcuts import get_object_or_404, redirect, render
from django.utils import timezone
from django.views.decorators.http import require_POST

from apps.challenges.models import Desafio, DesafioUsuario
from apps.courses.models import Curso, Lenguaje
from services.code_executor import execute_code


def _difficulty_label(value: str) -> str:
    difficulty = (value or '').strip().lower()
    if difficulty == 'facil':
        return 'Fácil'
    if difficulty == 'dificil':
        return 'Difícil'
    if difficulty == 'medio':
        return 'Medio'
    return value or 'Medio'


def _parse_request_payload(request):
    if request.content_type and 'application/json' in request.content_type:
        try:
            return json.loads(request.body.decode('utf-8') or '{}')
        except (json.JSONDecodeError, UnicodeDecodeError):
            return {}
    return request.POST


def _sanitize_student_code(code: str) -> str:
    """Elimina texto guía accidental pegado en el editor del estudiante."""
    if not code:
        return ''

    placeholder_patterns = [
        r'^\s*#\s*Escribe tu soluci[oó]n aqu[ií]\s*$',
        r'^\s*//\s*Escribe tu soluci[oó]n aqu[ií]\s*$',
        r'^\s*Escribe tu soluci[oó]n aqu[ií]\s*$',
        r'^\s*be tu soluci[oó]n aqu[ií]\s*$',
    ]

    cleaned_lines = []
    for raw_line in code.splitlines():
        line = raw_line.replace('\u200b', '')
        normalized = line.strip()
        if any(re.match(pattern, normalized, flags=re.IGNORECASE) for pattern in placeholder_patterns):
            continue
        cleaned_lines.append(line)

    return '\n'.join(cleaned_lines).strip()


def _challenge_payload(challenge: Desafio) -> dict:
    return {
        'id': challenge.pk,
        'challenge_id': challenge.pk,
        'legacy_id': challenge.pk,
        'course_id': challenge.course_id,
        'titulo': challenge.titulo,
        'descripcion': challenge.descripcion or '',
        'dificultad': _difficulty_label(challenge.dificultad or 'medio'),
        'dificultad_raw': challenge.dificultad or 'medio',
        'language': challenge.language.nombre if challenge.language_id else '',
        'lenguaje': challenge.language.nombre if challenge.language_id else '',
        'curso': challenge.course.titulo if challenge.course_id else '',
    }


def _developer_challenge_form_context(user, challenge=None, posted=None, error=''):
    posted = posted or {}
    courses = [
        {
            'id': course.pk,
            'titulo': course.titulo or 'Curso sin título',
        }
        for course in Curso.objects.filter(creado_por=user).order_by('-course_id')
    ]
    languages = [
        {
            'id': language.pk,
            'nombre': language.nombre,
        }
        for language in Lenguaje.objects.order_by('nombre')
    ]
    form_data = {
        'course_id': str(posted.get('course_id', getattr(challenge, 'course_id', '')) or ''),
        'language_id': str(posted.get('language_id', getattr(challenge, 'language_id', '')) or ''),
        'titulo': posted.get('titulo', getattr(challenge, 'titulo', '')),
        'descripcion': posted.get('descripcion', getattr(challenge, 'descripcion', '')),
        'dificultad': (posted.get('dificultad', getattr(challenge, 'dificultad', 'medio')) or 'medio').strip().lower(),
        'solucion': posted.get('solucion', getattr(challenge, 'solucion', '')),
    }
    return {
        'cursos': courses,
        'lenguajes': languages,
        'form_data': form_data,
        'is_edit': challenge is not None,
        'desafio': challenge,
        'error': error,
        'pendientes': DesafioUsuario.objects.filter(challenge__course__creado_por=user, estado='pendiente').count(),
        'racha': user.racha or 0,
    }


@login_required
def student_challenges(request):
    uid = request.user.user_id
    challenges = [_challenge_payload(c) for c in Desafio.objects.select_related('course', 'language').all().order_by('-challenge_id')]
    solved = DesafioUsuario.objects.filter(user_id=uid, estado='correcto').count()
    submissions = (
        DesafioUsuario.objects.filter(user_id=uid)
        .select_related('challenge')
        .order_by('-enviado_en')
    )
    completados_map = {}
    for submission in submissions:
        if not submission.challenge_id:
            continue
        key = str(submission.challenge_id)
        if key in completados_map:
            continue
        status_map = {
            'correcto': 'completado',
            'incorrecto': 'rechazado',
            'pendiente': 'pendiente',
        }
        completados_map[key] = {
            'estado': status_map.get(submission.estado or '', submission.estado or 'pendiente'),
            'puntaje': submission.puntaje,
            'enviado_en': submission.enviado_en.strftime('%d/%m/%Y %H:%M') if submission.enviado_en else '',
            'evaluado_en': submission.evaluado_en.strftime('%d/%m/%Y %H:%M') if submission.evaluado_en else '',
        }

    return render(
        request,
        'student/challenges.html',
        {
            'desafios': challenges,
            'resueltos': solved,
            'racha': request.user.racha or 0,
            'username': request.user.username,
            'desafios_disponibles': len(challenges),
            'desafios_completados': solved,
            'completados_map': completados_map,
        },
    )


@login_required
def student_challenge_detail(request, pk):
    challenge = get_object_or_404(Desafio.objects.select_related('course', 'language'), challenge_id=pk)
    return render(
        request,
        'student/ver_desafio.html',
        {
            'desafio': challenge,
            'racha': request.user.racha or 0,
            'username': request.user.username,
        },
    )


@login_required
def student_challenge_attempt(request, pk):
    challenge = get_object_or_404(Desafio.objects.select_related('course', 'language'), challenge_id=pk)
    return render(
        request,
        'student/intentar_desafio.html',
        {
            'desafio': challenge,
            'racha': request.user.racha or 0,
            'username': request.user.username,
            'lenguaje': (challenge.language.nombre if challenge.language_id else 'python').lower(),
        },
    )


@login_required
@require_POST
def student_code_execute(request):
    payload = _parse_request_payload(request)
    language = payload.get('language') or payload.get('lenguaje') or 'python'
    code = payload.get('code') or payload.get('codigo') or ''
    code = _sanitize_student_code(code)
    if not code:
        return JsonResponse({'error': 'Debes escribir una solución antes de ejecutar.'}, status=400)
    result = execute_code(language, code)
    if result.get('success'):
        return JsonResponse({'salida': result.get('output', '')})
    error_text = result.get('error') or result.get('output') or 'No se pudo ejecutar el código.'
    return JsonResponse({'error': error_text}, status=400)


@login_required
@require_POST
def student_challenge_submit(request, pk):
    challenge = get_object_or_404(Desafio, challenge_id=pk)
    payload = _parse_request_payload(request)
    code = payload.get('solution') or payload.get('code') or payload.get('codigo') or ''
    code = _sanitize_student_code(code)
    if not code.strip():
        return JsonResponse({'error': 'Debes escribir una solución antes de enviarla.'}, status=400)

    DesafioUsuario.objects.create(
        user=request.user,
        challenge=challenge,
        solucion_enviada=code,
        estado='pendiente',
        puntaje=None,
        evaluado_en=None,
    )
    return JsonResponse({'pendiente': True, 'mensaje': 'Solución enviada correctamente para revisión.'})


@login_required
def developer_challenges(request):
    challenges = [_challenge_payload(c) for c in Desafio.objects.filter(course__creado_por=request.user).select_related('course', 'language').order_by('-challenge_id')]
    courses = []
    course_challenges = {}
    for challenge in challenges:
        course_challenges.setdefault(challenge['course_id'], []).append(challenge)

    for course in Curso.objects.filter(creado_por=request.user).select_related('category', 'language').order_by('-course_id'):
        courses.append(
            {
                'id': course.pk,
                'titulo': course.titulo,
                'categoria': course.category.nombre if course.category_id else '',
                'descripcion': course.descripcion or '',
                'lenguaje': course.language.nombre if course.language_id else '',
                'nivel': course.nivel or '',
                'desafios': course_challenges.get(course.pk, []),
            }
        )

    pending_count = DesafioUsuario.objects.filter(challenge__course__creado_por=request.user, estado='pendiente').count()
    return render(
        request,
        'developer/challenges.html',
        {
            'desafios': challenges,
            'cursos': courses,
            'pendientes': pending_count,
            'racha': request.user.racha or 0,
            'total_desafios': len(challenges),
            'total_facil': sum(1 for d in challenges if (d['dificultad_raw'] or '').lower() == 'facil'),
            'total_medio': sum(1 for d in challenges if (d['dificultad_raw'] or '').lower() == 'medio'),
            'total_dificil': sum(1 for d in challenges if (d['dificultad_raw'] or '').lower() == 'dificil'),
        },
    )


@login_required
def developer_challenge_create(request):
    if request.method == 'POST':
        if not request.POST.get('course_id') or not request.POST.get('titulo', '').strip():
            context = _developer_challenge_form_context(request.user, posted=request.POST, error='Debes completar los campos obligatorios.')
            return render(request, 'developer/challenge_form.html', context)

        course_id = int(request.POST.get('course_id'))
        course = get_object_or_404(Curso, course_id=course_id, creado_por=request.user)
        language_id = int(request.POST.get('language_id')) if request.POST.get('language_id') else course.language_id
        Desafio.objects.create(
            course=course,
            titulo=request.POST.get('titulo', '').strip(),
            descripcion=request.POST.get('descripcion', '').strip(),
            dificultad=(request.POST.get('dificultad') or 'facil').strip().lower(),
            solucion=request.POST.get('solucion', ''),
            language_id=language_id,
        )
        messages.success(request, 'Desafío creado.')
        return redirect('core:developer_challenges')

    return render(request, 'developer/challenge_form.html', _developer_challenge_form_context(request.user))


@login_required
def developer_challenge_view(request, challenge_id):
    challenge = get_object_or_404(Desafio.objects.select_related('course', 'language'), challenge_id=challenge_id, course__creado_por=request.user)
    challenge_data = _challenge_payload(challenge)
    challenge_data['curso_titulo'] = challenge.course.titulo if challenge.course_id else ''
    challenge_data['solucion'] = challenge.solucion or ''
    return render(
        request,
        'developer/challenge_detail.html',
        {
            'desafio': challenge_data,
            'pendientes': DesafioUsuario.objects.filter(challenge__course__creado_por=request.user, estado='pendiente').count(),
            'racha': request.user.racha or 0,
        },
    )


@login_required
def developer_challenge_edit(request, challenge_id):
    challenge = get_object_or_404(Desafio, challenge_id=challenge_id, course__creado_por=request.user)
    if request.method == 'POST':
        if not request.POST.get('course_id') or not request.POST.get('titulo', '').strip():
            context = _developer_challenge_form_context(request.user, challenge=challenge, posted=request.POST, error='Debes completar los campos obligatorios.')
            return render(request, 'developer/challenge_form.html', context)

        challenge.course_id = int(request.POST.get('course_id'))
        challenge.titulo = request.POST.get('titulo', '').strip()
        challenge.descripcion = request.POST.get('descripcion', '').strip()
        challenge.dificultad = (request.POST.get('dificultad') or challenge.dificultad or 'medio').strip().lower()
        challenge.solucion = request.POST.get('solucion', '')
        challenge.language_id = int(request.POST.get('language_id')) if request.POST.get('language_id') else challenge.language_id
        challenge.save()
        messages.success(request, 'Desafío actualizado.')
        return redirect('core:developer_challenges')

    return render(request, 'developer/challenge_form.html', _developer_challenge_form_context(request.user, challenge=challenge))


@login_required
@require_POST
def developer_challenge_delete(request, challenge_id):
    challenge = get_object_or_404(Desafio, challenge_id=challenge_id, course__creado_por=request.user)
    challenge.delete()
    messages.success(request, 'Desafío eliminado.')
    return redirect('core:developer_challenges')


@login_required
def developer_pending_solutions(request):
    pending = DesafioUsuario.objects.filter(challenge__course__creado_por=request.user, estado='pendiente').select_related('challenge', 'user').order_by('-enviado_en')
    solutions = [
        {
            'submissionId': submission.submission_id,
            'desafioTitulo': submission.challenge.titulo if submission.challenge_id else 'Desafío',
            'cursoTitulo': submission.challenge.course.titulo if submission.challenge_id and submission.challenge.course_id else 'N/A',
            'userId': submission.user_id,
            'enviadoEn': submission.enviado_en,
            'estado': submission.estado,
        }
        for submission in pending
    ]
    return render(
        request,
        'developer/pending_solutions.html',
        {
            'soluciones': solutions,
            'pendientes': len(solutions),
            'total_pendientes': len(solutions),
            'racha': request.user.racha or 0,
        },
    )


@login_required
def developer_review_solution(request, submission_id):
    submission = get_object_or_404(DesafioUsuario.objects.select_related('challenge', 'user'), submission_id=submission_id, challenge__course__creado_por=request.user)
    return render(
        request,
        'developer/review_solution.html',
        {
            'submission': submission,
            'estudiante': submission.user,
            'desafio': submission.challenge,
            'lenguaje': submission.challenge.language.nombre if submission.challenge_id and submission.challenge.language_id else 'python',
            'solucion': {
                'submissionId': submission.submission_id,
                'enviadoEn': submission.enviado_en,
                'estado': submission.estado,
                'solucionEnviada': submission.solucion_enviada,
                'desafioTitulo': submission.challenge.titulo if submission.challenge_id else 'Desafío',
            },
        },
    )


@login_required
@require_POST
def developer_evaluate_solution(request, submission_id):
    submission = get_object_or_404(DesafioUsuario, submission_id=submission_id, challenge__course__creado_por=request.user)
    action = (request.POST.get('action') or request.POST.get('accion') or '').lower()
    score = int(request.POST.get('puntaje') or 0)
    submission.estado = 'correcto' if action in ('approve', 'aprobar', 'correcto', 'correct') else 'incorrecto'
    submission.puntaje = score if submission.estado == 'correcto' else 0
    submission.evaluado_en = timezone.now()
    submission.save(update_fields=['estado', 'puntaje', 'evaluado_en'])
    messages.success(request, 'Evaluación guardada.')
    return redirect('core:developer_pending_solutions')
