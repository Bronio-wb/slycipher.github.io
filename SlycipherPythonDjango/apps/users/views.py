import json
import secrets
import datetime as _dt
from urllib.error import HTTPError, URLError
from urllib.parse import urlencode
from urllib.request import Request, urlopen

from django.conf import settings
from django.contrib import messages
from django.contrib.auth import authenticate, login, logout
from django.contrib.auth.decorators import login_required
from django.core.paginator import Paginator
from django.db import OperationalError, ProgrammingError
from django.db.models import Count, Q, Avg
from django.http import JsonResponse, HttpResponse
from django.shortcuts import get_object_or_404, redirect, render
from django.urls import reverse
from django.utils import timezone
from django.views.decorators.http import require_POST
from types import SimpleNamespace

from apps.challenges.models import Desafio, DesafioUsuario
from apps.courses.models import Categoria, Curso, Inscripcion, Leccion, Lenguaje, ProgresoUsuario
from apps.users.forms import AdminUserForm, RegistrationForm
from apps.users.models import CertificadoEmitido, Profile, Usuario
from services.bootstrap_data_service import (
    execute_initial_load,
    execute_data_load,
    parse_json_payload,
)
from services.report_service import export_excel, export_pdf, export_student_certificate_pdf


def _normalize_role(role: str) -> str:
    role = (role or '').strip().lower()
    if role in ('developer', 'desarrollador'):
        return 'desarrollador'
    if role in ('student', 'estudiante'):
        return 'estudiante'
    if role in ('admin',):
        return 'admin'
    return role


def _to_template_role(role: str) -> str:
    role = _normalize_role(role)
    if role == 'desarrollador':
        return 'developer'
    if role == 'estudiante':
        return 'student'
    return 'admin' if role == 'admin' else role


def _role_redirect(role: str):
    role = _normalize_role(role)
    if role == 'admin':
        return 'core:admin_dashboard'
    if role == 'desarrollador':
        return 'core:developer_dashboard'
    return 'core:student_dashboard'


def _profile_context(user: Usuario, profile) -> dict:
    role = _normalize_role(user.rol)
    completion_fields = [
        bool(user.nombre),
        bool(user.apellido),
        bool(user.email),
        bool(getattr(user, 'fecha_nacimiento', None)),
        bool(getattr(profile, 'tipo_documento', '')),
        bool(getattr(profile, 'numero_documento', '')),
    ]
    profile_completion = int((sum(completion_fields) / len(completion_fields)) * 100)

    role_key_map = {
        'admin': 'ADMIN',
        'desarrollador': 'DEVELOPER',
        'estudiante': 'STUDENT',
    }
    role_label_map = {
        'admin': 'Administrador',
        'desarrollador': 'Desarrollador',
        'estudiante': 'Estudiante',
    }
    panel_label_map = {
        'admin': 'Panel Admin',
        'desarrollador': 'Panel Dev',
        'estudiante': 'Panel Estudiante',
    }

    return {
        'profile': profile,
        'display_name': user.get_full_name() or user.username,
        'role_key': role_key_map.get(role, 'STUDENT'),
        'role_label': role_label_map.get(role, 'Estudiante'),
        'panel_label': panel_label_map.get(role, 'Panel Estudiante'),
        'dashboard_route': _role_redirect(user.rol),
        'profile_completion': profile_completion,
        'member_since': user.creado_en,
        'birth_date': user.fecha_nacimiento,
        'streak_days': user.racha or 0,
    }


def _build_role_segments(role_map: dict) -> list[dict]:
    segments_config = [
        ('Admin', role_map.get('admin', 0), '#1f2937', 'swatch-admin'),
        ('Desarrollador', role_map.get('desarrollador', 0), '#f97316', 'swatch-dev'),
        ('Estudiante', role_map.get('estudiante', 0), '#2563eb', 'swatch-student'),
    ]
    total = sum(value for _, value, _, _ in segments_config)
    circumference = 100
    offset = 25
    segments = []

    for label, value, color, swatch_class in segments_config:
        percentage = (value / total * circumference) if total else 0
        dasharray = f'{percentage:.2f} {circumference - percentage:.2f}' if percentage else '0 100'
        segments.append({
            'label': label,
            'value': value,
            'color': color,
            'swatch_class': swatch_class,
            'dasharray': dasharray,
            'dashoffset': f'{offset:.2f}',
        })
        offset -= percentage

    return segments


def _course_completion_snapshot(user_id: int, course_id: int) -> dict:
    # El estudiante solo puede completar lecciones aprobadas, así que el cálculo
    # de completado debe usar el mismo universo de lecciones visibles.
    total_lessons = Leccion.objects.filter(course_id=course_id, estado='aprobada').count()
    completed_lessons = ProgresoUsuario.objects.filter(
        user_id=user_id,
        lesson__course_id=course_id,
        lesson__estado='aprobada',
        estado='completado',
    ).values('lesson_id').distinct().count()

    total_challenges = Desafio.objects.filter(course_id=course_id).count()
    completed_challenges = DesafioUsuario.objects.filter(
        user_id=user_id,
        challenge__course_id=course_id,
        estado='correcto',
    ).values('challenge_id').distinct().count()

    lessons_ok = total_lessons == 0 or completed_lessons >= total_lessons
    challenges_ok = total_challenges == 0 or completed_challenges >= total_challenges
    # Regla funcional:
    # - Si el curso tiene lecciones, el completado para certificación se define por lecciones.
    # - Si no tiene lecciones pero sí desafíos, usar desafíos.
    # - Si no tiene contenido, no se considera completado.
    if total_lessons > 0:
        completed = lessons_ok
    elif total_challenges > 0:
        completed = challenges_ok
    else:
        completed = False

    return {
        'completed': completed,
        'total_lessons': total_lessons,
        'completed_lessons': completed_lessons,
        'total_challenges': total_challenges,
        'completed_challenges': completed_challenges,
    }


def _build_admin_course_enrollment_rows(limit: int = 8) -> list[dict]:
    cursos = (
        Curso.objects.select_related('creado_por', 'category', 'language')
        .annotate(
            total_inscritos=Count('inscripciones', distinct=True),
            total_lecciones=Count('lecciones', distinct=True),
            total_desafios=Count('desafios', distinct=True),
        )
        .order_by('-total_inscritos', '-course_id')[:limit]
    )

    return [
        {
            'course_id': curso.course_id,
            'titulo': curso.titulo or f'Curso {curso.course_id}',
            'creador': curso.creado_por.get_full_name() or curso.creado_por.username if curso.creado_por_id else 'Sin creador',
            'nivel': curso.nivel or 'N/A',
            'inscritos': curso.total_inscritos,
            'lecciones': curso.total_lecciones,
            'desafios': curso.total_desafios,
        }
        for curso in cursos
    ]


def _build_student_certificate_snapshot(student_id: int) -> dict:
    course_ids = list(Inscripcion.objects.filter(user_id=student_id).values_list('course_id', flat=True))
    completed_courses = []
    total_lessons_completed = 0
    total_challenges_completed = 0

    for curso in Curso.objects.filter(course_id__in=course_ids).order_by('titulo'):
        snapshot = _course_completion_snapshot(student_id, curso.course_id)
        total_lessons_completed += snapshot['completed_lessons']
        total_challenges_completed += snapshot['completed_challenges']
        if snapshot['completed']:
            completed_courses.append({
                'course_id': curso.course_id,
                'titulo': curso.titulo or f'Curso {curso.course_id}',
                'completed_lessons': snapshot['completed_lessons'],
                'total_lessons': snapshot['total_lessons'],
                'completed_challenges': snapshot['completed_challenges'],
                'total_challenges': snapshot['total_challenges'],
            })

    return {
        'completed_courses': completed_courses,
        'total_lessons_completed': total_lessons_completed,
        'total_challenges_completed': total_challenges_completed,
    }


def _build_certificate_candidates(limit: int = 8) -> list[dict]:
    enrolled_user_ids = list(Inscripcion.objects.values_list('user_id', flat=True).distinct())
    estudiantes = Usuario.objects.filter(user_id__in=enrolled_user_ids, rol='estudiante', activo=True).order_by('nombre', 'apellido', 'username')

    candidatos = []
    for estudiante in estudiantes:
        snapshot = _build_student_certificate_snapshot(estudiante.user_id)
        completed_courses = snapshot['completed_courses']
        total_lessons_completed = snapshot['total_lessons_completed']
        total_challenges_completed = snapshot['total_challenges_completed']

        if len(completed_courses) < 1:
            continue

        candidatos.append({
            'user_id': estudiante.user_id,
            'nombre': estudiante.get_full_name() or estudiante.username,
            'email': estudiante.email,
            'completed_courses_count': len(completed_courses),
            'completed_courses': completed_courses,
            'total_lessons_completed': total_lessons_completed,
            'total_challenges_completed': total_challenges_completed,
            'eligible_general': len(completed_courses) >= 2,
        })

    candidatos.sort(key=lambda item: (-item['completed_courses_count'], -item['total_lessons_completed'], item['nombre']))
    return candidatos[:limit]


def _build_certificate_history(limit: int = 10) -> list[dict]:
    certificados = CertificadoEmitido.objects.select_related('student', 'issued_by', 'course').order_by('-issued_at')[:limit]
    return [
        {
            'student_name': cert.student.get_full_name() or cert.student.username,
            'certificate_type': 'General' if cert.certificate_type == 'general' else 'Por curso',
            'course_title': cert.course.titulo if cert.course_id else 'Certificado general',
            'issued_by': cert.issued_by.get_full_name() or cert.issued_by.username if cert.issued_by_id else 'Sistema',
            'issued_at': timezone.localtime(cert.issued_at).strftime('%d/%m/%Y %H:%M'),
            'code': cert.code,
        }
        for cert in certificados
    ]


def _create_certificate_history_entry(*, student: Usuario, issued_by: Usuario, certificate_type: str, total_courses: int, course: Curso | None = None, notes: str = ''):
    return CertificadoEmitido.objects.create(
        student=student,
        issued_by=issued_by,
        course=course,
        certificate_type=certificate_type,
        total_courses=total_courses,
        notes=notes,
        code=f"SLY-{certificate_type[:3].upper()}-{secrets.token_hex(4).upper()}",
    )


def _format_ceremonial_datetime(dt) -> str:
    if dt is None:
        dt = timezone.localtime()
    else:
        dt = timezone.localtime(dt)

    months = {
        1: 'enero',
        2: 'febrero',
        3: 'marzo',
        4: 'abril',
        5: 'mayo',
        6: 'junio',
        7: 'julio',
        8: 'agosto',
        9: 'septiembre',
        10: 'octubre',
        11: 'noviembre',
        12: 'diciembre',
    }
    return f"{dt.day} de {months.get(dt.month, '')} de {dt.year}"


def _build_compact_page_range(current_page: int, total_pages: int) -> list:
    if total_pages <= 7:
        return list(range(1, total_pages + 1))

    pages = {1, total_pages}
    pages.update({current_page - 1, current_page, current_page + 1})

    if current_page <= 3:
        pages.update({2, 3, 4})
    if current_page >= total_pages - 2:
        pages.update({total_pages - 1, total_pages - 2, total_pages - 3})

    ordered = sorted(p for p in pages if 1 <= p <= total_pages)
    compact = []
    previous = None
    for page in ordered:
        if previous is not None and (page - previous) > 1:
            compact.append('...')
        compact.append(page)
        previous = page
    return compact


def _request_param(request, *names: str, default: str = '') -> str:
    for name in names:
        value = request.GET.get(name)
        if value is not None:
            return value
    return default


def _normalize_report_role(role: str) -> str:
    role = (role or '').strip().lower()
    if role in ('developer', 'desarrollador', 'dev'):
        return 'desarrollador'
    if role in ('student', 'estudiante'):
        return 'estudiante'
    if role in ('admin', 'administrator'):
        return 'admin'
    return role


def _parse_active_filter(value: str):
    text = (value or '').strip().lower()
    if text in ('1', 'true', 'activo', 'active', 'yes', 'si', 'sí'):
        return True
    if text in ('0', 'false', 'inactivo', 'inactive', 'no'):
        return False
    return None


def _apply_date_filters(qs, field_name: str, fecha_desde: str, fecha_hasta: str):
    if fecha_desde:
        try:
            d = _dt.date.fromisoformat(fecha_desde.strip())
            dt_desde = timezone.make_aware(_dt.datetime.combine(d, _dt.time.min))
            qs = qs.filter(**{f'{field_name}__gte': dt_desde})
        except (ValueError, AttributeError, OverflowError):
            pass
    if fecha_hasta:
        try:
            d = _dt.date.fromisoformat(fecha_hasta.strip())
            dt_hasta = timezone.make_aware(_dt.datetime.combine(d, _dt.time.max))
            qs = qs.filter(**{f'{field_name}__lte': dt_hasta})
        except (ValueError, AttributeError, OverflowError):
            pass
    return qs


def _empty_profile():
    return SimpleNamespace(tipo_documento='', numero_documento='')


def _safe_profile_for_user(user: Usuario):
    try:
        return getattr(user, 'profile', None)
    except (ProgrammingError, OperationalError):
        return None


def _safe_get_or_create_profile(user: Usuario):
    try:
        return Profile.objects.get_or_create(user=user)
    except (ProgrammingError, OperationalError):
        return _empty_profile(), False


def _safe_update_profile(user: Usuario, *, tipo_documento: str = '', numero_documento: str = '') -> bool:
    try:
        Profile.objects.update_or_create(
            user=user,
            defaults={
                'tipo_documento': tipo_documento or '',
                'numero_documento': numero_documento or '',
            },
        )
        return True
    except (ProgrammingError, OperationalError):
        return False


def _http_post_form(url: str, data: dict, headers: dict | None = None) -> dict:
    encoded = urlencode(data).encode('utf-8')
    req_headers = {'Content-Type': 'application/x-www-form-urlencoded'}
    if headers:
        req_headers.update(headers)

    request = Request(url, data=encoded, headers=req_headers, method='POST')
    with urlopen(request, timeout=15) as response:
        body = response.read().decode('utf-8')
        return json.loads(body or '{}')


def _http_get_json(url: str, headers: dict | None = None) -> dict:
    request = Request(url, headers=headers or {}, method='GET')
    with urlopen(request, timeout=15) as response:
        body = response.read().decode('utf-8')
        return json.loads(body or '{}')


def _split_full_name(full_name: str, fallback: str = '') -> tuple[str, str]:
    text = (full_name or '').strip()
    if not text:
        return (fallback or 'Usuario', '')

    parts = text.split()
    if len(parts) == 1:
        return parts[0], ''
    return parts[0], ' '.join(parts[1:])


def _build_load_summary(result: dict) -> str:
    blocks = []
    for key, label in (
        ('categorias', 'Categorias'),
        ('lenguajes', 'Lenguajes'),
        ('logros', 'Logros'),
    ):
        stats = result.get(key, {})
        blocks.append(
            f"{label}: +{stats.get('creados', 0)} creados, "
            f"~{stats.get('actualizados', 0)} actualizados, "
            f"={stats.get('omitidos', 0)} omitidos, "
            f"!{stats.get('errores', 0)} errores"
        )
    return ' | '.join(blocks)


def _has_any_errors(result: dict) -> bool:
    for key in ('categorias', 'lenguajes', 'logros'):
        if result.get(key, {}).get('errores', 0) > 0:
            return True
    return False


@login_required
def admin_data_load(request):
    if _normalize_role(request.user.rol) != 'admin':
        return redirect(_role_redirect(request.user.rol))

    if request.method == 'POST':
        action = (request.POST.get('seed_action') or '').strip().lower()
        update_existing = (request.POST.get('update_existing') or '').strip().lower() in ('1', 'true', 'on', 'si', 'sí')

        if action == 'initial':
            result = execute_initial_load(update_existing=update_existing)
            messages.success(request, f'Carga inicial completada. {_build_load_summary(result)}')
            if result.get('detalles'):
                messages.info(request, ' | '.join(result['detalles']))
            return redirect('core:admin_data_load')

        if action == 'bulk_json':
            uploaded_file = request.FILES.get('seed_file')
            if uploaded_file is None:
                messages.error(request, 'Debes seleccionar un archivo JSON para la carga masiva.')
                return redirect('core:admin_data_load')

            if uploaded_file.size > (2 * 1024 * 1024):
                messages.error(request, 'El archivo supera el limite de 2 MB.')
                return redirect('core:admin_data_load')

            try:
                payload = parse_json_payload(uploaded_file.read().decode('utf-8'))
            except UnicodeDecodeError:
                messages.error(request, 'No se pudo leer el archivo. Verifica codificacion UTF-8.')
                return redirect('core:admin_data_load')
            except (ValueError, json.JSONDecodeError) as exc:
                messages.error(request, f'JSON invalido: {exc}')
                return redirect('core:admin_data_load')

            result = execute_data_load(payload, update_existing=update_existing)
            if _has_any_errors(result):
                messages.warning(request, f'Carga masiva finalizada con incidencias. {_build_load_summary(result)}')
            else:
                messages.success(request, f'Carga masiva completada. {_build_load_summary(result)}')

            if result.get('detalles'):
                messages.info(request, ' | '.join(result['detalles']))
            return redirect('core:admin_data_load')

        messages.error(request, 'Accion de carga no reconocida.')
        return redirect('core:admin_data_load')

    return render(request, 'admin/data_load.html')


def _oauth_intent(value: str) -> str:
    return 'register' if (value or '').strip().lower() == 'register' else 'login'


def _social_login_or_register(request, *, email: str, first_name: str, last_name: str, intent: str = 'login', provider_label: str = 'social'):
    intent = _oauth_intent(intent)
    provider_label = (provider_label or 'social').strip()
    email = (email or '').strip().lower()
    if not email:
        messages.error(request, 'No se pudo obtener el correo de tu cuenta social.')
        return redirect('core:register' if intent == 'register' else 'core:login')

    user = Usuario.objects.filter(email=email).first()
    if user:
        if not bool(user.activo):
            messages.error(request, 'Tu cuenta está inactiva. Contacta al administrador.')
            return redirect('core:login')

        # Completa nombre/apellido si venían vacíos en el usuario existente.
        updated_fields = []
        if not (user.nombre or '').strip() and first_name:
            user.nombre = first_name[:100]
            updated_fields.append('nombre')
        if not (user.apellido or '').strip() and last_name:
            user.apellido = last_name[:100]
            updated_fields.append('apellido')
        if updated_fields:
            user.save(update_fields=updated_fields)

        if intent == 'register':
            messages.info(request, f'Ya existe una cuenta con este correo en {provider_label}. Inicia sesión para continuar.')
            return redirect('core:login')
    else:
        if intent != 'register':
            messages.error(
                request,
                f'No existe una cuenta registrada con {provider_label}. Regístrate primero para poder iniciar sesión.',
            )
            return redirect('core:register')

        user = Usuario.objects.create_user(
            email=email,
            nombre=(first_name or 'Usuario')[:100],
            apellido=(last_name or '')[:100],
            rol='estudiante',
            password=secrets.token_urlsafe(24),
            username=email,
            activo=True,
            estado=1,
        )
        _safe_update_profile(user, tipo_documento='', numero_documento='')

        messages.success(request, f'Registro exitoso con {provider_label}. Ahora inicia sesión.')
        return redirect(f"{reverse('core:login')}?registered=1")

    login(request, user)
    return redirect(_role_redirect(user.rol))


def _oauth_redirect_uri(request, *, provider: str, route_name: str) -> str:
    provider_key = (provider or '').strip().lower()
    if provider_key == 'google' and (settings.GOOGLE_REDIRECT_URI or '').strip():
        return settings.GOOGLE_REDIRECT_URI.strip()
    if provider_key == 'github' and (settings.GITHUB_REDIRECT_URI or '').strip():
        return settings.GITHUB_REDIRECT_URI.strip()
    if provider_key == 'microsoft' and (settings.MICROSOFT_REDIRECT_URI or '').strip():
        return settings.MICROSOFT_REDIRECT_URI.strip()

    base = (settings.OAUTH_BASE_URL or '').strip().rstrip('/')
    if base:
        return f"{base}{reverse(route_name)}"

    # Azure y Microsoft no aceptan http://127.0.0.1; reemplazar por localhost.
    uri = request.build_absolute_uri(reverse(route_name))
    if provider_key == 'microsoft':
        uri = uri.replace('http://127.0.0.1:', 'http://localhost:')
    return uri


def oauth_google_start(request):
    intent = _oauth_intent(request.GET.get('intent', 'login'))
    client_id = (settings.GOOGLE_CLIENT_ID or '').strip()
    client_secret = (settings.GOOGLE_CLIENT_SECRET or '').strip()
    if not client_id or not client_secret:
        messages.error(request, 'Google OAuth no está configurado en el servidor.')
        return redirect('core:login')

    state = secrets.token_urlsafe(24)
    request.session['oauth_google_state'] = state
    request.session['oauth_google_intent'] = intent
    redirect_uri = _oauth_redirect_uri(request, provider='google', route_name='core:oauth_google_callback')

    params = {
        'client_id': client_id,
        'redirect_uri': redirect_uri,
        'response_type': 'code',
        'scope': 'openid email profile',
        'state': state,
        'prompt': 'select_account',
    }
    return redirect(f"https://accounts.google.com/o/oauth2/v2/auth?{urlencode(params)}")


def oauth_google_callback(request):
    if request.GET.get('error'):
        messages.error(request, 'No se pudo completar el login con Google.')
        return redirect('core:login')

    expected_state = request.session.pop('oauth_google_state', '')
    intent = _oauth_intent(request.session.pop('oauth_google_intent', 'login'))
    state = request.GET.get('state', '')
    if not expected_state or state != expected_state:
        messages.error(request, 'Estado OAuth inválido. Intenta nuevamente.')
        return redirect('core:login')

    code = request.GET.get('code', '')
    if not code:
        messages.error(request, 'Google no devolvió el código de autorización.')
        return redirect('core:login')

    client_id = (settings.GOOGLE_CLIENT_ID or '').strip()
    client_secret = (settings.GOOGLE_CLIENT_SECRET or '').strip()
    redirect_uri = _oauth_redirect_uri(request, provider='google', route_name='core:oauth_google_callback')

    try:
        token_data = _http_post_form(
            'https://oauth2.googleapis.com/token',
            {
                'code': code,
                'client_id': client_id,
                'client_secret': client_secret,
                'redirect_uri': redirect_uri,
                'grant_type': 'authorization_code',
            },
        )
        access_token = token_data.get('access_token', '')
        if not access_token:
            raise ValueError('Token de acceso no recibido.')

        profile = _http_get_json(
            'https://openidconnect.googleapis.com/v1/userinfo',
            headers={'Authorization': f'Bearer {access_token}'},
        )
    except (HTTPError, URLError, TimeoutError, ValueError, json.JSONDecodeError):
        messages.error(request, 'Error al autenticar con Google. Intenta de nuevo.')
        return redirect('core:login')

    first_name = (profile.get('given_name') or '').strip()
    last_name = (profile.get('family_name') or '').strip()
    if not first_name and not last_name:
        first_name, last_name = _split_full_name(profile.get('name', ''), fallback='Usuario')

    return _social_login_or_register(
        request,
        email=profile.get('email', ''),
        first_name=first_name,
        last_name=last_name,
        intent=intent,
        provider_label='Google',
    )


def oauth_github_start(request):
    intent = _oauth_intent(request.GET.get('intent', 'login'))
    client_id = (settings.GITHUB_CLIENT_ID or '').strip()
    client_secret = (settings.GITHUB_CLIENT_SECRET or '').strip()
    if not client_id or not client_secret:
        messages.error(request, 'GitHub OAuth no está configurado en el servidor.')
        return redirect('core:login')

    state = secrets.token_urlsafe(24)
    request.session['oauth_github_state'] = state
    request.session['oauth_github_intent'] = intent
    redirect_uri = _oauth_redirect_uri(request, provider='github', route_name='core:oauth_github_callback')

    params = {
        'client_id': client_id,
        'redirect_uri': redirect_uri,
        'scope': 'read:user user:email',
        'state': state,
    }
    return redirect(f"https://github.com/login/oauth/authorize?{urlencode(params)}")


def oauth_github_callback(request):
    if request.GET.get('error'):
        messages.error(request, 'No se pudo completar el login con GitHub.')
        return redirect('core:login')

    expected_state = request.session.pop('oauth_github_state', '')
    intent = _oauth_intent(request.session.pop('oauth_github_intent', 'login'))
    state = request.GET.get('state', '')
    if not expected_state or state != expected_state:
        messages.error(request, 'Estado OAuth inválido. Intenta nuevamente.')
        return redirect('core:login')

    code = request.GET.get('code', '')
    if not code:
        messages.error(request, 'GitHub no devolvió el código de autorización.')
        return redirect('core:login')

    client_id = (settings.GITHUB_CLIENT_ID or '').strip()
    client_secret = (settings.GITHUB_CLIENT_SECRET or '').strip()
    redirect_uri = _oauth_redirect_uri(request, provider='github', route_name='core:oauth_github_callback')

    try:
        token_data = _http_post_form(
            'https://github.com/login/oauth/access_token',
            {
                'code': code,
                'client_id': client_id,
                'client_secret': client_secret,
                'redirect_uri': redirect_uri,
            },
            headers={'Accept': 'application/json'},
        )

        access_token = token_data.get('access_token', '')
        if not access_token:
            raise ValueError('Token de acceso no recibido.')

        profile = _http_get_json(
            'https://api.github.com/user',
            headers={
                'Authorization': f'Bearer {access_token}',
                'Accept': 'application/vnd.github+json',
                'User-Agent': 'SlycipherPythonDjango',
            },
        )
        emails = _http_get_json(
            'https://api.github.com/user/emails',
            headers={
                'Authorization': f'Bearer {access_token}',
                'Accept': 'application/vnd.github+json',
                'User-Agent': 'SlycipherPythonDjango',
            },
        )
    except (HTTPError, URLError, TimeoutError, ValueError, json.JSONDecodeError):
        messages.error(request, 'Error al autenticar con GitHub. Intenta de nuevo.')
        return redirect('core:login')

    email = (profile.get('email') or '').strip().lower()
    if not email and isinstance(emails, list):
        primary_verified = next((e for e in emails if e.get('primary') and e.get('verified') and e.get('email')), None)
        fallback_email = next((e for e in emails if e.get('email')), None)
        email = (primary_verified or fallback_email or {}).get('email', '').strip().lower()

    first_name, last_name = _split_full_name(profile.get('name', ''), fallback=(profile.get('login') or 'Usuario'))
    return _social_login_or_register(
        request,
        email=email,
        first_name=first_name,
        last_name=last_name,
        intent=intent,
        provider_label='GitHub',
    )


def oauth_microsoft_start(request):
    intent = _oauth_intent(request.GET.get('intent', 'login'))
    client_id = (settings.MICROSOFT_CLIENT_ID or '').strip()
    client_secret = (settings.MICROSOFT_CLIENT_SECRET or '').strip()
    if not client_id or not client_secret:
        messages.error(request, 'Microsoft OAuth (Hotmail/Outlook) no está configurado en el servidor.')
        return redirect('core:login')

    state = secrets.token_urlsafe(24)
    request.session['oauth_microsoft_state'] = state
    request.session['oauth_microsoft_intent'] = intent
    redirect_uri = _oauth_redirect_uri(request, provider='microsoft', route_name='core:oauth_microsoft_callback')

    params = {
        'client_id': client_id,
        'redirect_uri': redirect_uri,
        'response_type': 'code',
        'response_mode': 'query',
        'scope': 'openid profile email User.Read',
        'state': state,
        'prompt': 'select_account',
    }
    return redirect(f"https://login.microsoftonline.com/common/oauth2/v2.0/authorize?{urlencode(params)}")


def oauth_microsoft_callback(request):
    if request.GET.get('error'):
        messages.error(request, 'No se pudo completar el login con Microsoft/Hotmail.')
        return redirect('core:login')

    expected_state = request.session.pop('oauth_microsoft_state', '')
    intent = _oauth_intent(request.session.pop('oauth_microsoft_intent', 'login'))
    state = request.GET.get('state', '')
    if not expected_state or state != expected_state:
        messages.error(request, 'Estado OAuth inválido. Intenta nuevamente.')
        return redirect('core:login')

    code = request.GET.get('code', '')
    if not code:
        messages.error(request, 'Microsoft no devolvió el código de autorización.')
        return redirect('core:login')

    client_id = (settings.MICROSOFT_CLIENT_ID or '').strip()
    client_secret = (settings.MICROSOFT_CLIENT_SECRET or '').strip()
    redirect_uri = _oauth_redirect_uri(request, provider='microsoft', route_name='core:oauth_microsoft_callback')

    try:
        token_data = _http_post_form(
            'https://login.microsoftonline.com/common/oauth2/v2.0/token',
            {
                'code': code,
                'client_id': client_id,
                'client_secret': client_secret,
                'redirect_uri': redirect_uri,
                'grant_type': 'authorization_code',
                'scope': 'openid profile email User.Read',
            },
        )
        access_token = token_data.get('access_token', '')
        if not access_token:
            raise ValueError('Token de acceso no recibido.')

        profile = _http_get_json(
            'https://graph.microsoft.com/v1.0/me?$select=displayName,givenName,surname,mail,userPrincipalName',
            headers={'Authorization': f'Bearer {access_token}'},
        )
    except (HTTPError, URLError, TimeoutError, ValueError, json.JSONDecodeError):
        messages.error(request, 'Error al autenticar con Microsoft/Hotmail. Intenta de nuevo.')
        return redirect('core:login')

    email = (profile.get('mail') or profile.get('userPrincipalName') or '').strip().lower()
    first_name = (profile.get('givenName') or '').strip()
    last_name = (profile.get('surname') or '').strip()
    if not first_name and not last_name:
        first_name, last_name = _split_full_name(profile.get('displayName', ''), fallback='Usuario')

    return _social_login_or_register(
        request,
        email=email,
        first_name=first_name,
        last_name=last_name,
        intent=intent,
        provider_label='Microsoft/Hotmail',
    )


def _user_payload(user: Usuario) -> dict:
    profile = _safe_profile_for_user(user)
    full_name = f"{user.nombre} {user.apellido}".strip()
    return {
        'id': user.user_id,
        'legacy_id': user.user_id,
        'username': user.username,
        'full_name': full_name,
        'email': user.email,
        'role': _to_template_role(user.rol),
        'rol': user.rol,
        'is_active': bool(user.activo),
        'date_joined': user.creado_en,
        'creado_en': user.creado_en.strftime('%d/%m/%Y') if user.creado_en else 'N/A',
        'tipo_documento': getattr(profile, 'tipo_documento', ''),
        'numero_documento': getattr(profile, 'numero_documento', ''),
        'racha': user.racha or 0,
    }


def home_view(request):
    if request.user.is_authenticated:
        return redirect(_role_redirect(getattr(request.user, 'rol', 'estudiante')))
    context = {
        'total_usuarios': Usuario.objects.filter(activo=True).count(),
        'total_cursos': Curso.objects.count(),
        'total_desafios': Desafio.objects.count(),
    }
    return render(request, 'core/home.html', context)


def login_view(request):
    if request.user.is_authenticated:
        return redirect(_role_redirect(getattr(request.user, 'rol', 'estudiante')))

    if request.method == 'POST':
        email = (request.POST.get('email') or request.POST.get('username') or '').strip().lower()
        password = request.POST.get('password') or ''
        user = authenticate(request, email=email, password=password)
        if user is not None and user.activo:
            login(request, user)
            return redirect(_role_redirect(user.rol))
        messages.error(request, 'Credenciales inválidas o cuenta inactiva.')

    return render(request, 'core/login.html')


@login_required
def logout_view(request):
    logout(request)
    return redirect('core:login')


def register_view(request):
    form = RegistrationForm(request.POST or None)
    if request.method == 'POST' and form.is_valid():
        data = form.cleaned_data
        user = Usuario.objects.create_user(
            email=data['email'],
            nombre=data['first_name'],
            apellido=data['last_name'],
            rol='estudiante',
            password=data['password'],
            username=data['email'],
            fecha_nacimiento=data.get('fecha_nacimiento'),
            activo=True,
            estado=1,
        )
        _safe_update_profile(
            user,
            tipo_documento=data.get('tipo_documento') or '',
            numero_documento=data.get('numero_documento') or '',
        )
        messages.success(request, 'Registro exitoso. Ahora puedes iniciar sesión.')
        return redirect(f"{reverse('core:login')}?registered=1")

    return render(request, 'core/register.html', {'form': form})


@login_required
def profile_view(request):
    user = request.user
    profile, _ = _safe_get_or_create_profile(user)

    if request.method == 'POST':
        user.nombre = request.POST.get('nombre', user.nombre)
        user.apellido = request.POST.get('apellido', user.apellido)
        user.email = request.POST.get('email', user.email).strip().lower()
        fecha = request.POST.get('fecha_nacimiento')
        user.fecha_nacimiento = fecha or user.fecha_nacimiento

        new_password = request.POST.get('password') or ''
        if new_password:
            user.set_password(new_password)

        profile.tipo_documento = request.POST.get('tipo_documento', profile.tipo_documento)
        profile.numero_documento = request.POST.get('numero_documento', profile.numero_documento)

        user.save()
        if hasattr(profile, 'save'):
            try:
                profile.save()
            except (ProgrammingError, OperationalError):
                pass

        if new_password:
            login(request, user)

        messages.success(request, 'Perfil actualizado correctamente.')
        return redirect('core:profile')

    return render(request, 'core/profile.html', _profile_context(user, profile))


@login_required
def admin_dashboard(request):
    if _normalize_role(request.user.rol) != 'admin':
        return redirect(_role_redirect(request.user.rol))

    total_users = Usuario.objects.count()
    total_courses = Curso.objects.count()
    active_users = Usuario.objects.filter(activo=True).count()
    active_courses = Curso.objects.filter(estado=1).count()
    recent_users = Usuario.objects.order_by('-creado_en')[:5]

    role_counts = Usuario.objects.values('rol').annotate(total=Count('user_id'))
    role_map = {'admin': 0, 'desarrollador': 0, 'estudiante': 0}
    for item in role_counts:
        role_map[_normalize_role(item['rol'])] = item['total']
    role_segments = _build_role_segments(role_map)
    top_courses_with_enrollments = _build_admin_course_enrollment_rows()
    certificate_candidates = _build_certificate_candidates()
    certificate_history = _build_certificate_history()

    context = {
        'total_users': total_users,
        'total_courses': total_courses,
        'active_users': active_users,
        'active_courses': active_courses,
        'recent_users': [_user_payload(user) for user in recent_users],
        'role_segments': role_segments,
        'role_values': [role_map['admin'], role_map['desarrollador'], role_map['estudiante']],
        'top_courses_with_enrollments': top_courses_with_enrollments,
        'certificate_candidates': certificate_candidates,
        'certificate_history': certificate_history,
    }
    return render(request, 'admin/dashboard.html', context)


@login_required
def admin_certifications(request):
    if _normalize_role(request.user.rol) != 'admin':
        return redirect(_role_redirect(request.user.rol))

    student_filter = (request.GET.get('student') or '').strip()
    course_filter = (request.GET.get('course') or '').strip()
    certificate_search = (request.GET.get('certificate') or '').strip()

    student_filter_l = student_filter.lower()
    course_filter_l = course_filter.lower()

    top_courses_with_enrollments = _build_admin_course_enrollment_rows(limit=12)
    certificate_candidates = _build_certificate_candidates(limit=12)

    if student_filter_l:
        certificate_candidates = [
            item for item in certificate_candidates
            if student_filter_l in (item.get('nombre', '').lower()) or student_filter_l in (item.get('email', '').lower())
        ]

    if course_filter_l:
        top_courses_with_enrollments = [
            item for item in top_courses_with_enrollments
            if course_filter_l in (item.get('titulo', '').lower()) or course_filter_l in (item.get('creador', '').lower())
        ]
        certificate_candidates = [
            item for item in certificate_candidates
            if any(course_filter_l in (course.get('titulo', '').lower()) for course in item.get('completed_courses', []))
        ]

    history_qs = CertificadoEmitido.objects.select_related('student', 'issued_by', 'course').order_by('-issued_at')
    if student_filter:
        history_qs = history_qs.filter(
            Q(student__nombre__icontains=student_filter)
            | Q(student__apellido__icontains=student_filter)
            | Q(student__username__icontains=student_filter)
            | Q(student__email__icontains=student_filter)
        )
    if course_filter:
        history_qs = history_qs.filter(Q(course__titulo__icontains=course_filter) | Q(notes__icontains=course_filter))
    if certificate_search:
        history_qs = history_qs.filter(
            Q(code__icontains=certificate_search)
            | Q(student__nombre__icontains=certificate_search)
            | Q(student__apellido__icontains=certificate_search)
            | Q(student__username__icontains=certificate_search)
            | Q(student__email__icontains=certificate_search)
            | Q(course__titulo__icontains=certificate_search)
            | Q(notes__icontains=certificate_search)
        )

    page_number = request.GET.get('page') or '1'
    paginator = Paginator(history_qs, 10)
    history_page = paginator.get_page(page_number)
    history_compact_range = _build_compact_page_range(history_page.number, paginator.num_pages)

    certificate_history = [
        {
            'student_name': cert.student.get_full_name() or cert.student.username,
            'certificate_type': 'General' if cert.certificate_type == 'general' else 'Por curso',
            'course_title': cert.course.titulo if cert.course_id else 'Certificado general',
            'issued_by': cert.issued_by.get_full_name() or cert.issued_by.username if cert.issued_by_id else 'Sistema',
            'issued_at': timezone.localtime(cert.issued_at).strftime('%d/%m/%Y %H:%M'),
            'code': cert.code,
        }
        for cert in history_page.object_list
    ]

    context = {
        'eligible_students_count': len(certificate_candidates),
        'certificates_total': CertificadoEmitido.objects.count(),
        'general_certificates_total': CertificadoEmitido.objects.filter(certificate_type='general').count(),
        'course_certificates_total': CertificadoEmitido.objects.filter(certificate_type='curso').count(),
        'courses_with_enrollments_count': len([item for item in top_courses_with_enrollments if item.get('inscritos', 0) > 0]),
        'top_courses_with_enrollments': top_courses_with_enrollments,
        'certificate_candidates': certificate_candidates,
        'certificate_history': certificate_history,
        'history_page': history_page,
        'history_page_range': history_page.paginator.page_range,
        'history_compact_range': history_compact_range,
        'student_filter': student_filter,
        'course_filter': course_filter,
        'certificate_search': certificate_search,
    }
    return render(request, 'admin/certifications.html', context)


@login_required
def admin_student_certificate_pdf(request, user_id):
    if _normalize_role(request.user.rol) != 'admin':
        return redirect(_role_redirect(request.user.rol))

    estudiante = get_object_or_404(Usuario, user_id=user_id, rol='estudiante')
    snapshot = _build_student_certificate_snapshot(estudiante.user_id)
    completed_courses = snapshot['completed_courses']
    total_lessons_completed = snapshot['total_lessons_completed']
    total_challenges_completed = snapshot['total_challenges_completed']

    if len(completed_courses) < 2:
        messages.error(request, 'El estudiante aun no cumple el criterio minimo para certificado (2 cursos completados).')
        return redirect('core:admin_certifications')

    emitted_at = timezone.localtime()
    certificate_entry = _create_certificate_history_entry(
        student=estudiante,
        issued_by=request.user,
        certificate_type='general',
        total_courses=len(completed_courses),
        notes=' | '.join(course['titulo'] for course in completed_courses[:6]),
    )

    content = export_student_certificate_pdf(
        student_name=estudiante.get_full_name() or estudiante.username,
        completed_courses=[course['titulo'] for course in completed_courses],
        total_lessons_completed=total_lessons_completed,
        total_challenges_completed=total_challenges_completed,
        generated_by=request.user.username,
        generated_at=emitted_at.strftime('%d/%m/%Y %H:%M:%S'),
        ceremonial_date=_format_ceremonial_datetime(emitted_at),
        certificate_code=certificate_entry.code,
        validation_url=request.build_absolute_uri(reverse('core:certificate_verify', args=[certificate_entry.code])),
    )
    response = HttpResponse(content, content_type='application/pdf')
    response['Content-Disposition'] = f'attachment; filename="certificado_{estudiante.username}.pdf"'
    return response


@login_required
def admin_student_course_certificate_pdf(request, user_id, course_id):
    if _normalize_role(request.user.rol) != 'admin':
        return redirect(_role_redirect(request.user.rol))

    estudiante = get_object_or_404(Usuario, user_id=user_id, rol='estudiante')
    curso = get_object_or_404(Curso, course_id=course_id)
    snapshot = _course_completion_snapshot(estudiante.user_id, curso.course_id)

    if not snapshot['completed']:
        messages.error(request, 'El estudiante aun no ha completado este curso para emitir el certificado individual.')
        return redirect('core:admin_certifications')

    emitted_at = timezone.localtime()
    certificate_entry = _create_certificate_history_entry(
        student=estudiante,
        issued_by=request.user,
        course=curso,
        certificate_type='curso',
        total_courses=1,
        notes=curso.titulo or f'Curso {curso.course_id}',
    )

    content = export_student_certificate_pdf(
        student_name=estudiante.get_full_name() or estudiante.username,
        completed_courses=[curso.titulo or f'Curso {curso.course_id}'],
        total_lessons_completed=snapshot['completed_lessons'],
        total_challenges_completed=snapshot['completed_challenges'],
        generated_by=request.user.username,
        generated_at=emitted_at.strftime('%d/%m/%Y %H:%M:%S'),
        certificate_kind='course',
        course_name=curso.titulo or f'Curso {curso.course_id}',
        ceremonial_date=_format_ceremonial_datetime(emitted_at),
        certificate_code=certificate_entry.code,
        validation_url=request.build_absolute_uri(reverse('core:certificate_verify', args=[certificate_entry.code])),
    )
    response = HttpResponse(content, content_type='application/pdf')
    response['Content-Disposition'] = f'attachment; filename="certificado_{estudiante.username}_curso_{curso.course_id}.pdf"'
    return response


def certificate_verify(request, code):
    certificate = get_object_or_404(CertificadoEmitido.objects.select_related('student', 'issued_by', 'course'), code=code)
    context = {
        'certificate': certificate,
        'student_name': certificate.student.get_full_name() or certificate.student.username,
        'issued_by_name': certificate.issued_by.get_full_name() or certificate.issued_by.username if certificate.issued_by_id else 'Sistema',
        'course_title': certificate.course.titulo if certificate.course_id else 'Certificado general de logro',
        'ceremonial_date': _format_ceremonial_datetime(certificate.issued_at),
        'certificate_type_label': 'General' if certificate.certificate_type == 'general' else 'Por curso',
    }
    return render(request, 'core/certificate_verify.html', context)


@login_required
def admin_statistics(request):
    if _normalize_role(request.user.rol) != 'admin':
        return redirect(_role_redirect(request.user.rol))

    # Importar el servicio de estadísticas
    from services.statistics_service import EstadisticasService

    # Obtener datos originales para compatibilidad
    total_categories = Categoria.objects.count()
    total_languages = Lenguaje.objects.count()
    total_courses = Curso.objects.count()
    registros_del_mes = Usuario.objects.filter(creado_en__month=timezone.now().month, creado_en__year=timezone.now().year).count()

    by_category = Curso.objects.values('category__nombre').annotate(total=Count('course_id')).order_by('-total')
    by_language = Curso.objects.values('language__nombre').annotate(total=Count('course_id')).order_by('-total')
    by_month = (
        Usuario.objects.extra(select={'mes': "DATE_FORMAT(creado_en, '%%Y-%%m')"})
        .values('mes')
        .annotate(total=Count('user_id'))
        .order_by('mes')[:12]
    )

    courses_by_category = [(x['category__nombre'] or 'Sin categoría', x['total']) for x in by_category]
    courses_by_language = [(x['language__nombre'] or 'Sin lenguaje', x['total']) for x in by_language]
    users_by_month = [(x['mes'], x['total']) for x in by_month]

    # Obtener datos avanzados del servicio de estadísticas
    kpis = EstadisticasService.obtener_kpis_principales()
    desempeno_desafios = EstadisticasService.obtener_desempeno_desafios()
    usuarios_activos_top = EstadisticasService.obtener_usuarios_mas_activos(8)
    cursos_populares = EstadisticasService.obtener_cursos_mas_populares(8)
    distribucion_por_nivel = EstadisticasService.obtener_distribucion_por_nivel()
    logros_mas_desbloqueados = EstadisticasService.obtener_logros_mas_desbloqueados(6)
    tendencia_usuarios = EstadisticasService.obtener_tendencia_usuarios_30_dias()
    analisis_categorias = EstadisticasService.obtener_analisis_por_categoria()
    analisis_lenguajes = EstadisticasService.obtener_analisis_por_lenguaje()
    segmentacion_usuarios = EstadisticasService.obtener_segmentacion_usuarios()

    context = {
        # Datos originales (para compatibilidad con template actual)
        'total_categories': total_categories,
        'total_languages': total_languages,
        'registros_del_mes': registros_del_mes,
        'total_courses': total_courses,
        'courses_by_category': courses_by_category,
        'courses_by_category_labels': [x['category__nombre'] or 'Sin categoría' for x in by_category],
        'courses_by_category_values': [x['total'] for x in by_category],
        'courses_by_language': courses_by_language,
        'courses_by_language_labels': [x['language__nombre'] or 'Sin lenguaje' for x in by_language],
        'courses_by_language_values': [x['total'] for x in by_language],
        'users_by_month': users_by_month,
        'users_by_month_labels': [x['mes'] for x in by_month],
        'users_by_month_values': [x['total'] for x in by_month],
        
        # Datos avanzados (nuevos)
        'kpis': kpis,
        'desempeno_desafios': desempeno_desafios,
        'usuarios_activos_top': usuarios_activos_top,
        'cursos_populares': cursos_populares,
        'distribucion_por_nivel': distribucion_por_nivel,
        'logros_mas_desbloqueados': logros_mas_desbloqueados,
        'tendencia_usuarios': tendencia_usuarios,
        'analisis_categorias': analisis_categorias,
        'analisis_lenguajes': analisis_lenguajes,
        'segmentacion_usuarios': segmentacion_usuarios,
    }
    return render(request, 'admin/statistics.html', context)


@login_required
def admin_statistics_pdf(request):
    if _normalize_role(request.user.rol) != 'admin':
        return redirect(_role_redirect(request.user.rol))

    from services.statistics_service import EstadisticasService

    tipo = (request.GET.get('tipo') or 'general').strip().lower()
    generated_at = timezone.localtime().strftime('%d/%m/%Y %H:%M:%S')

    if tipo == 'categorias':
        analisis_categorias = EstadisticasService.obtener_analisis_por_categoria()
        headers = ['Categoría', 'Cursos', 'Desafíos', 'Intentos', 'Exitosos', 'Tasa de éxito']
        rows = [
            [
                cat.get('nombre', 'N/A'),
                cat.get('cursos', 0),
                cat.get('desafios', 0),
                cat.get('intentos', 0),
                cat.get('exitosos', 0),
                f"{cat.get('tasa_exito', 0)}%",
            ]
            for cat in analisis_categorias
        ]
        content = export_pdf(
            title='Estadísticas por Categoría',
            headers=headers,
            rows=rows,
            filtros=['Módulo: Estadísticas', 'Tipo: Categorías'],
            generated_by=request.user.username,
            generated_at=generated_at,
            report_type='Estadísticas - Categorías',
        )
        resp = HttpResponse(content, content_type='application/pdf')
        resp['Content-Disposition'] = 'attachment; filename="estadisticas_categorias.pdf"'
        return resp

    if tipo == 'lenguajes':
        analisis_lenguajes = EstadisticasService.obtener_analisis_por_lenguaje()
        headers = ['Lenguaje', 'Cursos', 'Desafíos', 'Intentos', 'Exitosos', 'Tasa de éxito']
        rows = [
            [
                lng.get('nombre', 'N/A'),
                lng.get('cursos', 0),
                lng.get('desafios', 0),
                lng.get('intentos', 0),
                lng.get('exitosos', 0),
                f"{lng.get('tasa_exito', 0)}%",
            ]
            for lng in analisis_lenguajes
        ]
        content = export_pdf(
            title='Estadísticas por Lenguaje',
            headers=headers,
            rows=rows,
            filtros=['Módulo: Estadísticas', 'Tipo: Lenguajes'],
            generated_by=request.user.username,
            generated_at=generated_at,
            report_type='Estadísticas - Lenguajes',
        )
        resp = HttpResponse(content, content_type='application/pdf')
        resp['Content-Disposition'] = 'attachment; filename="estadisticas_lenguajes.pdf"'
        return resp

    if tipo == 'desafios':
        desempeno_desafios = EstadisticasService.obtener_desempeno_desafios()
        headers = ['Desafío', 'Dificultad', 'Completados', 'Intentos', 'Tasa de éxito']
        rows = [
            [
                des.get('titulo', 'N/A'),
                des.get('dificultad', 'N/A'),
                des.get('completados', 0),
                des.get('intentos', 0),
                f"{des.get('tasa_exito', 0)}%",
            ]
            for des in desempeno_desafios.get('desafios_mas_completados', [])
        ]
        content = export_pdf(
            title='Estadísticas de Desafíos',
            headers=headers,
            rows=rows,
            filtros=['Módulo: Estadísticas', 'Tipo: Desafíos'],
            generated_by=request.user.username,
            generated_at=generated_at,
            report_type='Estadísticas - Desafíos',
            summary_rows=[
                ['Tasa de éxito general', f"{desempeno_desafios.get('tasa_exito_general', 0)}%"],
            ],
        )
        resp = HttpResponse(content, content_type='application/pdf')
        resp['Content-Disposition'] = 'attachment; filename="estadisticas_desafios.pdf"'
        return resp

    if tipo == 'usuarios':
        usuarios_activos_top = EstadisticasService.obtener_usuarios_mas_activos(20)
        segmentacion_usuarios = EstadisticasService.obtener_segmentacion_usuarios()
        headers = ['Usuario', 'Email', 'Desafíos completados', 'Logros', 'Racha']
        rows = [
            [
                u.get('nombre', 'N/A'),
                u.get('email', 'N/A'),
                u.get('desafios_completados', 0),
                u.get('logros', 0),
                u.get('racha', 0),
            ]
            for u in usuarios_activos_top
        ]
        total_seg = segmentacion_usuarios.get('total', 0) or 0
        content = export_pdf(
            title='Estadísticas de Usuarios',
            headers=headers,
            rows=rows,
            filtros=['Módulo: Estadísticas', 'Tipo: Usuarios'],
            generated_by=request.user.username,
            generated_at=generated_at,
            report_type='Estadísticas - Usuarios',
            distribution_title='Segmentación de Usuarios',
            distribution_headers=['Segmento', 'Cantidad', 'Porcentaje'],
            distribution_rows=[
                ['Muy activos', segmentacion_usuarios.get('muy_activos', 0), f"{((segmentacion_usuarios.get('muy_activos', 0) / total_seg) * 100):.1f}%" if total_seg else '0%'],
                ['Activos recientes', segmentacion_usuarios.get('activos_recientes', 0), f"{((segmentacion_usuarios.get('activos_recientes', 0) / total_seg) * 100):.1f}%" if total_seg else '0%'],
                ['Con participación', segmentacion_usuarios.get('con_participacion', 0), f"{((segmentacion_usuarios.get('con_participacion', 0) / total_seg) * 100):.1f}%" if total_seg else '0%'],
                ['Inactivos', segmentacion_usuarios.get('inactivos', 0), f"{((segmentacion_usuarios.get('inactivos', 0) / total_seg) * 100):.1f}%" if total_seg else '0%'],
            ],
        )
        resp = HttpResponse(content, content_type='application/pdf')
        resp['Content-Disposition'] = 'attachment; filename="estadisticas_usuarios.pdf"'
        return resp

    if tipo == 'cursos':
        cursos_populares = EstadisticasService.obtener_cursos_mas_populares(20)
        distribucion_por_nivel = EstadisticasService.obtener_distribucion_por_nivel()
        total_cursos = sum(item.get('cantidad', 0) for item in distribucion_por_nivel)
        headers = ['Curso', 'Nivel', 'Desafíos', 'Participaciones']
        rows = [
            [
                c.get('titulo', 'N/A'),
                c.get('nivel', 'N/A'),
                c.get('desafios', 0),
                c.get('participaciones', 0),
            ]
            for c in cursos_populares
        ]
        content = export_pdf(
            title='Estadísticas de Cursos',
            headers=headers,
            rows=rows,
            filtros=['Módulo: Estadísticas', 'Tipo: Cursos'],
            generated_by=request.user.username,
            generated_at=generated_at,
            report_type='Estadísticas - Cursos',
            distribution_title='Distribución por Nivel',
            distribution_headers=['Nivel', 'Cantidad', 'Porcentaje'],
            distribution_rows=[
                [
                    item.get('nivel', 'N/A'),
                    item.get('cantidad', 0),
                    f"{((item.get('cantidad', 0) / total_cursos) * 100):.1f}%" if total_cursos else '0%'
                ]
                for item in distribucion_por_nivel
            ],
        )
        resp = HttpResponse(content, content_type='application/pdf')
        resp['Content-Disposition'] = 'attachment; filename="estadisticas_cursos.pdf"'
        return resp

    kpis = EstadisticasService.obtener_kpis_principales()
    desempeno_desafios = EstadisticasService.obtener_desempeno_desafios()
    segmentacion_usuarios = EstadisticasService.obtener_segmentacion_usuarios()
    total_seg = segmentacion_usuarios.get('total', 0) or 0

    summary_rows = [
        ['Total usuarios', kpis.get('total_usuarios', 0)],
        ['Usuarios activos', kpis.get('usuarios_activos', 0)],
        ['Tasa de activación', f"{kpis.get('tasa_activacion', 0)}%"],
        ['Total cursos', kpis.get('total_cursos', 0)],
        ['Total desafíos', kpis.get('total_desafios', 0)],
        ['Total intentos', kpis.get('total_submissions', 0)],
        ['Respuestas correctas', kpis.get('respuestas_correctas', 0)],
        ['Tasa de éxito general', f"{desempeno_desafios.get('tasa_exito_general', 0)}%"],
    ]

    headers = ['Indicador', 'Valor']
    rows = summary_rows

    content = export_pdf(
        title='Reporte General de Estadísticas',
        headers=headers,
        rows=rows,
        filtros=['Módulo: Estadísticas', 'Tipo: General'],
        generated_by=request.user.username,
        generated_at=generated_at,
        report_type='Estadísticas - General',
        summary_rows=summary_rows,
        distribution_title='Segmentación de Usuarios',
        distribution_headers=['Segmento', 'Cantidad', 'Porcentaje'],
        distribution_rows=[
            ['Muy activos', segmentacion_usuarios.get('muy_activos', 0), f"{((segmentacion_usuarios.get('muy_activos', 0) / total_seg) * 100):.1f}%" if total_seg else '0%'],
            ['Activos recientes', segmentacion_usuarios.get('activos_recientes', 0), f"{((segmentacion_usuarios.get('activos_recientes', 0) / total_seg) * 100):.1f}%" if total_seg else '0%'],
            ['Con participación', segmentacion_usuarios.get('con_participacion', 0), f"{((segmentacion_usuarios.get('con_participacion', 0) / total_seg) * 100):.1f}%" if total_seg else '0%'],
            ['Inactivos', segmentacion_usuarios.get('inactivos', 0), f"{((segmentacion_usuarios.get('inactivos', 0) / total_seg) * 100):.1f}%" if total_seg else '0%'],
        ],
    )
    resp = HttpResponse(content, content_type='application/pdf')
    resp['Content-Disposition'] = 'attachment; filename="estadisticas_generales.pdf"'
    return resp


@login_required
def admin_reports(request):
    if _normalize_role(request.user.rol) not in ('admin', 'desarrollador'):
        return redirect(_role_redirect(request.user.rol))
    return render(request, 'admin/reports.html')


@login_required
def admin_reports_result(request):
    if _normalize_role(request.user.rol) not in ('admin', 'desarrollador'):
        return redirect(_role_redirect(request.user.rol))

    report_type = (_request_param(request, 'report_type', 'tipo', 'tipoReporte', default='users') or 'users').lower()
    export_format = (_request_param(request, 'export', 'format', 'formato', default='') or '').lower()
    fecha_desde = (_request_param(request, 'fecha_desde', 'fechaDesde', default='') or '').strip()
    fecha_hasta = (_request_param(request, 'fecha_hasta', 'fechaHasta', default='') or '').strip()

    filtros = []
    if fecha_desde:
        filtros.append(f'Desde: {fecha_desde}')
    if fecha_hasta:
        filtros.append(f'Hasta: {fecha_hasta}')

    if report_type in ('usuarios', 'users'):
        qs = Usuario.objects.all().order_by('-creado_en')
        role = _normalize_report_role(_request_param(request, 'role', 'rol', default=''))
        estado = _request_param(request, 'estado', 'activo', default='') or ''
        if role:
            qs = qs.filter(rol=role)
            filtros.append(f'Rol: {role}')
        active_flag = _parse_active_filter(estado)
        if active_flag is not None:
            flag = bool(active_flag)
            qs = qs.filter(activo=flag)
            filtros.append(f'Estado: {"Activo" if flag else "Inactivo"}')
        qs = _apply_date_filters(qs, 'creado_en', fecha_desde, fecha_hasta)

        usuarios = list(qs[:500])
        rows = [[u.username, u.email, u.rol, 'Activo' if u.activo else 'Inactivo', u.creado_en.strftime('%d/%m/%Y') if u.creado_en else 'N/A'] for u in usuarios]
        headers = ['Usuario', 'Email', 'Rol', 'Estado', 'Fecha']

        if export_format == 'pdf':
            content = export_pdf(
                title='Reporte de Usuarios',
                headers=headers,
                rows=rows,
                filtros=filtros,
                generated_by=request.user.username,
                generated_at=timezone.localtime().strftime('%d/%m/%Y %H:%M:%S'),
                report_type='Usuarios',
            )
            resp = HttpResponse(content, content_type='application/pdf')
            resp['Content-Disposition'] = 'attachment; filename="reporte_usuarios.pdf"'
            return resp
        if export_format == 'excel':
            content = export_excel('Usuarios', headers, rows, filtros=filtros)
            resp = HttpResponse(content, content_type='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            resp['Content-Disposition'] = 'attachment; filename="reporte_usuarios.xlsx"'
            return resp

        context = {
            'tipo_reporte': 'usuarios',
            'total_registros': len(usuarios),
            'filtros': filtros,
            'filtros_aplicados': filtros,
            'usuarios': usuarios,
            'usuarios_activos': sum(1 for u in usuarios if u.activo),
            'usuarios_inactivos': sum(1 for u in usuarios if not u.activo),
            'total_usuarios': Usuario.objects.count(),
            'total_cursos': Curso.objects.count(),
            'cursos_activos': Curso.objects.filter(estado=1).count(),
            'racha_promedio': int((Usuario.objects.aggregate(v=Avg('racha'))['v'] or 0)),
            'top_usuarios': [
                {
                    'posicion': idx + 1,
                    'usuario': u.username,
                    'email': u.email,
                    'racha': u.racha or 0,
                }
                for idx, u in enumerate(Usuario.objects.order_by('-racha')[:10])
            ],
        }
        return render(request, 'admin/report_result.html', context)

    if report_type in ('progreso', 'progress'):
        top_usuarios = [
            {
                'posicion': idx + 1,
                'usuario': u.username,
                'email': u.email,
                'racha': u.racha or 0,
            }
            for idx, u in enumerate(Usuario.objects.order_by('-racha')[:15])
        ]

        if export_format == 'pdf':
            content = export_pdf(
                title='Reporte de Progreso',
                headers=['Posición', 'Usuario', 'Email', 'Racha'],
                rows=[[x['posicion'], x['usuario'], x['email'], x['racha']] for x in top_usuarios],
                generated_by=request.user.username,
                generated_at=timezone.localtime().strftime('%d/%m/%Y %H:%M:%S'),
                report_type='Progreso',
                progress_kpis=[
                    {'label': 'Total Usuarios', 'value': Usuario.objects.count()},
                    {'label': 'Total Cursos', 'value': Curso.objects.count()},
                    {'label': 'Usuarios Activos', 'value': Usuario.objects.filter(activo=True).count()},
                    {'label': 'Racha Promedio', 'value': int((Usuario.objects.aggregate(v=Avg('racha'))['v'] or 0))},
                ],
                progress_top_rows=[[x['posicion'], x['usuario'], x['email'], x['racha']] for x in top_usuarios],
            )
            resp = HttpResponse(content, content_type='application/pdf')
            resp['Content-Disposition'] = 'attachment; filename="reporte_progreso.pdf"'
            return resp

        context = {
            'tipo_reporte': 'progreso',
            'total_registros': len(top_usuarios),
            'filtros': filtros,
            'filtros_aplicados': filtros,
            'top_usuarios': top_usuarios,
            'top_usuarios_racha': top_usuarios,
            'total_usuarios': Usuario.objects.count(),
            'total_cursos': Curso.objects.count(),
            'usuarios_activos': Usuario.objects.filter(activo=True).count(),
            'racha_promedio': int((Usuario.objects.aggregate(v=Avg('racha'))['v'] or 0)),
            'cursos_activos': Curso.objects.filter(estado=1).count(),
        }
        return render(request, 'admin/report_result.html', context)

    if report_type in ('general',):
        total_usuarios = Usuario.objects.count()
        usuarios_activos = Usuario.objects.filter(activo=True).count()
        total_cursos = Curso.objects.count()
        cursos_activos = Curso.objects.filter(estado=1).count()
        racha_promedio = int((Usuario.objects.aggregate(v=Avg('racha'))['v'] or 0))
        role_counts = Usuario.objects.values('rol').annotate(total=Count('user_id'))
        role_map = {'admin': 0, 'desarrollador': 0, 'estudiante': 0}
        for item in role_counts:
            role_map[_normalize_role(item['rol'])] = item['total']

        rows = [
            ['Total Usuarios', total_usuarios],
            ['Usuarios Activos', usuarios_activos],
            ['Total Cursos', total_cursos],
            ['Cursos Activos', cursos_activos],
            ['Racha Promedio', racha_promedio],
        ]
        headers = ['Indicador', 'Valor']

        if export_format == 'pdf':
            content = export_pdf(
                title='Reporte General',
                headers=headers,
                rows=rows,
                filtros=filtros,
                generated_by=request.user.username,
                generated_at=timezone.localtime().strftime('%d/%m/%Y %H:%M:%S'),
                report_type='General',
                summary_rows=rows,
                distribution_title='Distribución de Usuarios por Rol',
                distribution_headers=['Rol', 'Cantidad', 'Porcentaje'],
                distribution_rows=[
                    ['Admin', role_map['admin'], f"{(role_map['admin'] / total_usuarios * 100):.1f}%" if total_usuarios else '0%'],
                    ['Desarrollador', role_map['desarrollador'], f"{(role_map['desarrollador'] / total_usuarios * 100):.1f}%" if total_usuarios else '0%'],
                    ['Estudiante', role_map['estudiante'], f"{(role_map['estudiante'] / total_usuarios * 100):.1f}%" if total_usuarios else '0%'],
                ],
            )
            resp = HttpResponse(content, content_type='application/pdf')
            resp['Content-Disposition'] = 'attachment; filename="reporte_general.pdf"'
            return resp

        if export_format == 'excel':
            content = export_excel('General', headers, rows, filtros=filtros)
            resp = HttpResponse(content, content_type='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            resp['Content-Disposition'] = 'attachment; filename="reporte_general.xlsx"'
            return resp

        context = {
            'tipo_reporte': 'general',
            'total_registros': len(rows),
            'filtros': filtros,
            'filtros_aplicados': filtros,
            'total_usuarios': total_usuarios,
            'usuarios_activos': usuarios_activos,
            'total_cursos': total_cursos,
            'cursos_activos': cursos_activos,
            'racha_promedio': racha_promedio,
            'role_segments': _build_role_segments(role_map),
        }
        return render(request, 'admin/report_result.html', context)

    qs = Curso.objects.select_related('language', 'category').order_by('-fecha_creacion')
    nivel = _request_param(request, 'nivel', default='') or ''
    lenguaje_id = _request_param(request, 'lenguajeId', 'language_id', default='') or ''
    if nivel:
        qs = qs.filter(nivel__iexact=nivel)
        filtros.append(f'Nivel: {nivel}')
    if lenguaje_id:
        qs = qs.filter(language_id=lenguaje_id)
        filtros.append(f'Lenguaje ID: {lenguaje_id}')
    qs = _apply_date_filters(qs, 'fecha_creacion', fecha_desde, fecha_hasta)

    cursos = list(qs[:500])
    rows = [[c.titulo, (c.descripcion or '')[:80], c.nivel or 'N/A', c.language.nombre if c.language_id else 'N/A', 'Aprobado' if c.estado else 'Pendiente', c.fecha_creacion.strftime('%d/%m/%Y') if c.fecha_creacion else 'N/A'] for c in cursos]
    headers = ['Título', 'Descripción', 'Nivel', 'Lenguaje', 'Estado', 'Fecha']

    if export_format == 'pdf':
        content = export_pdf(
            title='Reporte de Cursos',
            headers=headers,
            rows=rows,
            filtros=filtros,
            generated_by=request.user.username,
            generated_at=timezone.localtime().strftime('%d/%m/%Y %H:%M:%S'),
            report_type='Cursos',
        )
        resp = HttpResponse(content, content_type='application/pdf')
        resp['Content-Disposition'] = 'attachment; filename="reporte_cursos.pdf"'
        return resp

    if export_format == 'excel':
        content = export_excel('Cursos', headers, rows, filtros=filtros)
        resp = HttpResponse(content, content_type='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
        resp['Content-Disposition'] = 'attachment; filename="reporte_cursos.xlsx"'
        return resp

    context = {
        'tipo_reporte': 'cursos',
        'total_registros': len(cursos),
        'filtros': filtros,
        'filtros_aplicados': filtros,
        'cursos': [
            {
                'titulo': c.titulo,
                'descripcion': c.descripcion,
                'nivel': c.nivel,
                'lenguaje': c.language.nombre if c.language_id else 'N/A',
                'estado': 'Aprobado' if c.estado else 'Pendiente',
                'fecha': c.fecha_creacion.strftime('%d/%m/%Y') if c.fecha_creacion else 'N/A',
            }
            for c in cursos
        ],
        'total_usuarios': Usuario.objects.count(),
        'usuarios_activos': Usuario.objects.filter(activo=True).count(),
        'total_cursos': Curso.objects.count(),
        'cursos_activos': Curso.objects.filter(estado=1).count(),
    }
    return render(request, 'admin/report_result.html', context)


@login_required
def users_list(request):
    if _normalize_role(request.user.rol) != 'admin':
        return redirect(_role_redirect(request.user.rol))

    q = (request.GET.get('q') or '').strip()
    role_filter = _normalize_role(request.GET.get('role') or '')

    qs = Usuario.objects.all().order_by('-creado_en')
    if q:
        qs = qs.filter(
            Q(username__icontains=q)
            | Q(email__icontains=q)
            | Q(nombre__icontains=q)
            | Q(apellido__icontains=q)
        )
    if role_filter:
        qs = qs.filter(rol=role_filter)

    users = [_user_payload(u) for u in qs]
    context = {
        'users': users,
        'query': q,
        'selected_role': _to_template_role(role_filter) if role_filter else '',
        'admin_count': Usuario.objects.filter(rol='admin').count(),
        'dev_count': Usuario.objects.filter(rol='desarrollador').count(),
        'student_count': Usuario.objects.filter(rol='estudiante').count(),
    }
    return render(request, 'admin/users.html', context)


@login_required
def user_create(request):
    if _normalize_role(request.user.rol) != 'admin':
        return redirect(_role_redirect(request.user.rol))

    form = AdminUserForm(request.POST or None)
    if request.method == 'POST' and form.is_valid():
        data = form.cleaned_data
        role = _normalize_role(data['role'])
        user = Usuario.objects.create_user(
            email=data['email'].lower(),
            nombre=data['first_name'],
            apellido=data['last_name'],
            rol=role,
            password=data.get('password') or 'Temp1234@',
            username=data['email'].lower(),
            fecha_nacimiento=data.get('fecha_nacimiento'),
            racha=data.get('racha') or 0,
            activo=True,
            estado=1,
        )
        _safe_update_profile(
            user,
            tipo_documento=data.get('tipo_documento') or '',
            numero_documento=data.get('numero_documento') or '',
        )
        messages.success(request, 'Usuario creado correctamente.')
        return redirect('core:users')

    return render(request, 'admin/user_form.html', {'form': form, 'create': True})


@login_required
def user_edit(request, pk):
    if _normalize_role(request.user.rol) != 'admin':
        return redirect(_role_redirect(request.user.rol))

    user_obj = get_object_or_404(Usuario, user_id=pk)
    profile, _ = _safe_get_or_create_profile(user_obj)

    initial = {
        'email': user_obj.email,
        'first_name': user_obj.nombre,
        'last_name': user_obj.apellido,
        'role': _normalize_role(user_obj.rol),
        'tipo_documento': profile.tipo_documento,
        'numero_documento': profile.numero_documento,
        'fecha_nacimiento': user_obj.fecha_nacimiento,
        'racha': user_obj.racha,
    }
    form = AdminUserForm(request.POST or None, initial=initial)

    if request.method == 'POST' and form.is_valid():
        data = form.cleaned_data
        user_obj.email = data['email'].lower()
        user_obj.username = data['email'].lower()
        user_obj.nombre = data['first_name']
        user_obj.apellido = data['last_name']
        user_obj.rol = _normalize_role(data['role'])
        user_obj.fecha_nacimiento = data.get('fecha_nacimiento')
        user_obj.racha = data.get('racha') or 0
        if data.get('password'):
            user_obj.set_password(data['password'])
        user_obj.save()

        profile.tipo_documento = data.get('tipo_documento') or ''
        profile.numero_documento = data.get('numero_documento') or ''
        if hasattr(profile, 'save'):
            try:
                profile.save()
            except (ProgrammingError, OperationalError):
                pass

        messages.success(request, 'Usuario actualizado correctamente.')
        return redirect('core:users')

    return render(request, 'admin/user_form.html', {'form': form, 'create': False, 'target_user': user_obj, 'user_obj': user_obj})


@login_required
@require_POST
def user_delete(request, pk):
    if _normalize_role(request.user.rol) != 'admin':
        return redirect(_role_redirect(request.user.rol))

    user_obj = get_object_or_404(Usuario, user_id=pk)
    if user_obj.user_id == request.user.user_id:
        messages.error(request, 'No puedes eliminar tu propio usuario.')
        return redirect('core:users')

    user_obj.delete()
    messages.success(request, 'Usuario eliminado correctamente.')
    return redirect('core:users')


@login_required
@require_POST
def user_toggle_active(request, pk):
    if _normalize_role(request.user.rol) != 'admin':
        return JsonResponse({'ok': False, 'error': 'No autorizado'}, status=403)

    user_obj = get_object_or_404(Usuario, user_id=pk)
    if user_obj.user_id == request.user.user_id:
        return JsonResponse({'ok': False, 'error': 'No puedes cambiar tu propio estado'}, status=400)

    user_obj.activo = not bool(user_obj.activo)
    user_obj.save(update_fields=['activo'])
    return JsonResponse({'ok': True, 'is_active': bool(user_obj.activo)})
