import json

from django.contrib import messages
from django.contrib.auth.decorators import login_required
from django.db.models import Q
from django.http import JsonResponse
from django.shortcuts import get_object_or_404, redirect, render
from django.utils import timezone
from django.views.decorators.http import require_POST

from apps.achievements.models import LogroUsuario
from apps.challenges.models import Desafio, DesafioUsuario
from apps.courses.models import Categoria, Curso, Inscripcion, Leccion, Lenguaje, ProgresoUsuario
from services.code_executor import execute_code


def _normalize_role(role: str) -> str:
    role = (role or '').strip().lower()
    if role in ('developer', 'desarrollador'):
        return 'desarrollador'
    if role in ('student', 'estudiante'):
        return 'estudiante'
    if role in ('admin',):
        return 'admin'
    return role


def _role_redirect(role: str):
    role = _normalize_role(role)
    if role == 'admin':
        return 'core:admin_dashboard'
    if role == 'desarrollador':
        return 'core:developer_dashboard'
    return 'core:student_dashboard'


def _course_payload(course: Curso) -> dict:
    creator = course.creado_por
    creator_name = (f"{creator.nombre} {creator.apellido}").strip() if creator else ''
    return {
        'id': course.pk,
        'pk': course.pk,
        'legacy_id': course.pk,
        'titulo': course.titulo or '',
        'title': course.titulo or '',
        'descripcion': course.descripcion or '',
        'description': course.descripcion or '',
        'nivel': course.nivel or '',
        'level': course.nivel or '',
        'lenguaje': course.language.nombre if course.language_id else '',
        'language': course.language.nombre if course.language_id else '',
        'lenguaje_id': course.language_id,
        'language_id': course.language_id,
        'categoria': course.category.nombre if course.category_id else '',
        'category': course.category.nombre if course.category_id else '',
        'categoria_id': course.category_id,
        'category_id': course.category_id,
        'creator': creator_name or (creator.email if creator else ''),
        'instructor': creator_name or (creator.username if creator else ''),
        'estado': 'aprobada' if course.estado else 'pendiente',
        'status': 'Aprobada' if course.estado else 'Pendiente',
        'visibility': 1 if course.estado else 0,
        'visible': 1 if course.estado else 0,
        'requisitos': course.requisitos or '',
        'duracion': course.duracion_estimada or 0,
        'duracion_d': course.duracion_estimada or 0,
        'fecha': course.fecha_creacion.strftime('%d/%m/%Y') if course.fecha_creacion else 'N/A',
        'fecha_creacion': course.fecha_creacion,
    }


def _lesson_payload(lesson: Leccion) -> dict:
    contenido = lesson.contenido or ''
    return {
        'id': lesson.pk,
        'lesson_id': lesson.pk,
        'titulo': lesson.titulo,
        'contenido': contenido,
        'codigo_ejemplo': lesson.codigo_ejemplo or '',
        'contenido_resumen': (contenido[:120] + '...') if len(contenido) > 120 else contenido,
        'orden': lesson.orden or 0,
        'estado': lesson.estado,
        'visible': lesson.estado == 'aprobada',
    }


def _developer_lesson_form_context(user, course, lesson=None, posted=None, error=''):
    posted = posted or {}
    form_data = {
        'titulo': posted.get('titulo', getattr(lesson, 'titulo', '')),
        'orden': posted.get('orden', getattr(lesson, 'orden', 1) or 1),
        'contenido': posted.get('contenido', getattr(lesson, 'contenido', '')),
        'codigo_ejemplo': posted.get('codigo_ejemplo', getattr(lesson, 'codigo_ejemplo', '')),
        'visible': posted.get('visible') == 'true' if posted else getattr(lesson, 'estado', '') == 'aprobada',
    }
    return {
        'curso': _course_payload(course),
        'lesson': lesson,
        'is_edit': lesson is not None,
        'form_data': form_data,
        'error': error,
        'pendientes': DesafioUsuario.objects.filter(challenge__course__creado_por_id=user.user_id, estado='pendiente').count(),
        'racha': user.racha or 0,
    }


def _normalize_course_lesson_order(course_id: int) -> int:
    """Reasigna el orden secuencial (1..N) para evitar huecos/duplicados."""
    lessons = list(
        Leccion.objects.filter(course_id=course_id).order_by('orden', 'lesson_id')
    )
    updated = 0
    for index, lesson in enumerate(lessons, start=1):
        if lesson.orden != index:
            Leccion.objects.filter(lesson_id=lesson.lesson_id).update(orden=index)
            updated += 1
    return updated


@login_required
def admin_courses(request):
    if _normalize_role(request.user.rol) != 'admin':
        return redirect(_role_redirect(request.user.rol))

    base_qs = Curso.objects.select_related('category', 'language', 'creado_por').all()
    qs = base_qs.order_by('-course_id')

    selected_category = (request.GET.get('category') or '').strip()
    selected_level = (request.GET.get('level') or '').strip()
    selected_status = (request.GET.get('status') or '').strip()
    selected_visibility = (request.GET.get('visibility') or '').strip()

    query = (request.GET.get('q') or '').strip()
    if query:
        qs = qs.filter(
            Q(titulo__icontains=query)
            | Q(descripcion__icontains=query)
            | Q(creado_por__nombre__icontains=query)
            | Q(creado_por__apellido__icontains=query)
            | Q(creado_por__username__icontains=query)
            | Q(creado_por__email__icontains=query)
        )

    if selected_category:
        qs = qs.filter(category__nombre__iexact=selected_category)

    if selected_level:
        qs = qs.filter(nivel__iexact=selected_level)

    if selected_status:
        status_text = selected_status.strip().lower()
        if status_text in ('aprobada', 'aprobado', 'approved'):
            qs = qs.filter(estado=1)
        elif status_text in ('pendiente', 'pending'):
            qs = qs.filter(estado=0)

    if selected_visibility:
        visibility_text = selected_visibility.strip().lower()
        if visibility_text in ('visible', '1', 'true'):
            qs = qs.filter(estado=1)
        elif visibility_text in ('oculto', 'hidden', '0', 'false'):
            qs = qs.filter(estado=0)

    courses = [_course_payload(c) for c in qs]
    all_courses_for_filters = [_course_payload(c) for c in base_qs]
    categories = sorted({c['category'] for c in all_courses_for_filters if c['category']})
    levels = sorted({c['level'] for c in all_courses_for_filters if c['level']})
    statuses = sorted({c['status'] for c in all_courses_for_filters if c['status']})
    visibilities = ['Visible', 'Oculto']

    context = {
        'courses': courses,
        'legacy_courses': courses,
        'query': query,
        'categories': categories,
        'levels': levels,
        'statuses': statuses,
        'visibilities': visibilities,
        'selected_category': selected_category,
        'selected_level': selected_level,
        'selected_status': selected_status,
        'selected_visibility': selected_visibility,
        'total_courses': len(courses),
        'pending': sum(1 for c in courses if c['status'].lower() == 'pendiente'),
        'approved': sum(1 for c in courses if c['status'].lower() == 'aprobada'),
    }
    return render(request, 'admin/courses.html', context)


def _course_form_context(course=None, posted=None):
    posted = posted or {}
    form_data = {
        'titulo': posted.get('titulo', getattr(course, 'titulo', '')),
        'descripcion': posted.get('descripcion', getattr(course, 'descripcion', '')),
        'category_id': posted.get('category_id', getattr(course, 'category_id', '')),
        'language_id': posted.get('language_id', getattr(course, 'language_id', '')),
        'nivel': posted.get('nivel', getattr(course, 'nivel', '')),
        'duracion_d': posted.get('duracion_d', getattr(course, 'duracion_estimada', '')),
        'estado': posted.get('estado', 'aprobada' if getattr(course, 'estado', 0) else 'pendiente'),
        'visible': posted.get('visible', getattr(course, 'estado', 0)),
        'requisitos': posted.get('requisitos', getattr(course, 'requisitos', '')),
    }
    categories_list = [{'id': c.category_id, 'name': c.nombre} for c in Categoria.objects.all().order_by('nombre')]
    languages_list = [{'id': l.language_id, 'name': l.nombre} for l in Lenguaje.objects.all().order_by('nombre')]
    return {
        'form_data': form_data,
        'categories_list': categories_list,
        'languages_list': languages_list,
    }


@login_required
def admin_course_create(request):
    if _normalize_role(request.user.rol) != 'admin':
        return redirect(_role_redirect(request.user.rol))

    if request.method == 'POST':
        Curso.objects.create(
            titulo=request.POST.get('titulo', '').strip(),
            descripcion=request.POST.get('descripcion', '').strip(),
            category_id=int(request.POST.get('category_id')),
            language_id=int(request.POST.get('language_id')) if request.POST.get('language_id') else None,
            nivel=request.POST.get('nivel') or None,
            duracion_estimada=int(request.POST.get('duracion_d') or 0),
            estado=1 if (request.POST.get('visible') in ('1', 'true', 'True', 'visible')) else 0,
            requisitos=request.POST.get('requisitos', ''),
            creado_por=request.user,
        )
        messages.success(request, 'Curso creado correctamente.')
        return redirect('core:admin_courses')

    context = {'create': True}
    context.update(_course_form_context())
    return render(request, 'admin/course_form.html', context)


@login_required
def admin_course_edit(request, legacy_id):
    if _normalize_role(request.user.rol) != 'admin':
        return redirect(_role_redirect(request.user.rol))

    course = get_object_or_404(Curso, course_id=legacy_id)
    if request.method == 'POST':
        course.titulo = request.POST.get('titulo', '').strip()
        course.descripcion = request.POST.get('descripcion', '').strip()
        course.category_id = int(request.POST.get('category_id'))
        course.language_id = int(request.POST.get('language_id')) if request.POST.get('language_id') else None
        course.nivel = request.POST.get('nivel') or None
        course.duracion_estimada = int(request.POST.get('duracion_d') or 0)
        course.estado = 1 if (request.POST.get('visible') in ('1', 'true', 'True', 'visible')) else 0
        course.requisitos = request.POST.get('requisitos', '')
        course.save()
        messages.success(request, 'Curso actualizado correctamente.')
        return redirect('core:admin_courses')

    context = {'create': False, 'legacy_id': legacy_id}
    context.update(_course_form_context(course=course))
    return render(request, 'admin/course_form.html', context)


@login_required
def admin_course_detail_view(request, legacy_id):
    if _normalize_role(request.user.rol) != 'admin':
        return redirect(_role_redirect(request.user.rol))
    course = get_object_or_404(Curso, course_id=legacy_id)
    course_data = _course_payload(course)
    context = {
        'legacy_id': legacy_id,
        'course': course_data,
        'course_title': course_data['titulo'],
        'course_description': course_data['descripcion'],
        'course_level': course_data['nivel'] or 'No especificado',
        'course_status': course_data['status'],
        'is_visible': bool(course_data['visible']),
        'category_name': course_data['categoria'] or 'No especificada',
        'language_name': course_data['lenguaje'] or 'No especificado',
        'course_duration': course_data['duracion'] if course_data['duracion'] else '-',
        'creator_name': course_data['creator'] or 'No especificado',
        'course_requirements': course_data['requisitos'] or '-',
        'lessons': [_lesson_payload(l) for l in Leccion.objects.filter(course_id=legacy_id).order_by('orden')],
    }
    return render(request, 'admin/course_detail.html', context)


@login_required
def admin_course_delete(request, legacy_id):
    if _normalize_role(request.user.rol) != 'admin':
        return redirect(_role_redirect(request.user.rol))

    course = get_object_or_404(Curso, course_id=legacy_id)
    if request.method == 'POST':
        course.delete()
        messages.success(request, 'Curso eliminado correctamente.')
        return redirect('core:admin_courses')

    return render(
        request,
        'admin/course_confirm_delete.html',
        {
            'legacy_id': legacy_id,
            'course_title': course.titulo,
        },
    )


@login_required
@require_POST
def admin_course_approve(request, legacy_id):
    course = get_object_or_404(Curso, course_id=legacy_id)
    course.estado = 1
    course.save(update_fields=['estado'])
    messages.success(request, 'Curso aprobado.')
    return redirect('core:admin_courses')


@login_required
@require_POST
def admin_course_reject(request, legacy_id):
    course = get_object_or_404(Curso, course_id=legacy_id)
    course.estado = 0
    course.save(update_fields=['estado'])
    messages.success(request, 'Curso marcado como pendiente.')
    return redirect('core:admin_courses')


@login_required
@require_POST
def course_toggle_visibility(request, pk):
    course = get_object_or_404(Curso, course_id=pk)
    course.estado = 0 if course.estado else 1
    course.save(update_fields=['estado'])
    return JsonResponse({'ok': True, 'visibility': int(course.estado)})


@login_required
@require_POST
def legacy_course_toggle_visibility(request, legacy_id):
    return course_toggle_visibility(request, legacy_id)


@login_required
def course_list(request):
    courses = Curso.objects.all().order_by('-course_id')
    return render(request, 'core/course_list.html', {'courses': courses})


@login_required
def course_create(request):
    return admin_course_create(request)


@login_required
def course_detail(request, pk):
    return admin_course_detail_view(request, pk)


@login_required
def course_update(request, pk):
    return admin_course_edit(request, pk)


@login_required
def course_delete(request, pk):
    return admin_course_delete(request, pk)


@login_required
def student_dashboard(request):
    if _normalize_role(request.user.rol) != 'estudiante':
        return redirect(_role_redirect(request.user.rol))

    uid = request.user.user_id
    enrolled_ids = list(Inscripcion.objects.filter(user_id=uid).values_list('course_id', flat=True))
    recent_progress = (
        ProgresoUsuario.objects.filter(user_id=uid, estado='completado')
        .select_related('lesson', 'lesson__course')
        .order_by('-completado_en')[:5]
    )

    context = {
        'racha': request.user.racha or 0,
        'cursos_inscritos': len(enrolled_ids),
        'lecciones_completadas': ProgresoUsuario.objects.filter(user_id=uid, estado='completado').count(),
        'desafios_resueltos': DesafioUsuario.objects.filter(user_id=uid, estado='correcto').count(),
        'logros': LogroUsuario.objects.filter(user_id=uid).count(),
        'progreso_reciente': [
            {
                'titulo': p.lesson.titulo if p.lesson_id else 'Lección',
                'curso': p.lesson.course.titulo if p.lesson_id and p.lesson.course_id else 'Curso',
            }
            for p in recent_progress
        ],
        'cursos_disponibles': [_course_payload(c) for c in Curso.objects.filter(estado=1).order_by('-course_id')[:6]],
    }
    return render(request, 'student/dashboard.html', context)


@login_required
def student_courses(request):
    if _normalize_role(request.user.rol) != 'estudiante':
        return redirect(_role_redirect(request.user.rol))

    cursos = [_course_payload(c) for c in Curso.objects.filter(estado=1).select_related('language', 'category', 'creado_por').order_by('-course_id')]
    context = {
        'racha': request.user.racha or 0,
        'cursos': cursos,
        'cursos_total': len(cursos),
        'cursos_filtrados': len(cursos),
        'categorias': sorted({c['categoria'] for c in cursos if c['categoria']}),
        'niveles': sorted({c['nivel'] for c in cursos if c['nivel']}),
        'lenguajes': sorted({c['lenguaje'] for c in cursos if c['lenguaje']}),
        'estados': sorted({c['estado'] for c in cursos if c['estado']}),
    }
    return render(request, 'student/courses.html', context)


@login_required
def student_course_detail(request, course_id):
    if _normalize_role(request.user.rol) != 'estudiante':
        return redirect(_role_redirect(request.user.rol))

    course = get_object_or_404(Curso.objects.select_related('language', 'category', 'creado_por'), course_id=course_id)
    lessons = [
        _lesson_payload(l)
        for l in Leccion.objects.filter(course_id=course_id, estado='aprobada').order_by('orden', 'lesson_id')
    ]
    is_enrolled = Inscripcion.objects.filter(user_id=request.user.user_id, course_id=course_id).exists()
    context = {
        'curso': _course_payload(course),
        'lecciones': lessons,
        'total_lecciones': len(lessons),
        'racha': request.user.racha or 0,
        'esta_inscrito': is_enrolled,
        'inscrito': is_enrolled,
    }
    return render(request, 'student/course_detail.html', context)


@login_required
@require_POST
def student_course_enroll(request, course_id):
    if _normalize_role(request.user.rol) != 'estudiante':
        return redirect(_role_redirect(request.user.rol))

    course = get_object_or_404(Curso, course_id=course_id)
    Inscripcion.objects.get_or_create(user=request.user, course=course)
    messages.success(request, 'Te has inscrito al curso.')
    return redirect('core:student_course_detail', course_id=course_id)


@login_required
def student_lesson_detail(request, lesson_id):
    lesson = get_object_or_404(Leccion, lesson_id=lesson_id)
    course = lesson.course
    previous_lesson = (
        Leccion.objects.filter(course_id=lesson.course_id)
        .filter(Q(orden__lt=lesson.orden) | Q(orden=lesson.orden, lesson_id__lt=lesson.lesson_id))
        .order_by('-orden', '-lesson_id')
        .first()
    )
    next_lesson = (
        Leccion.objects.filter(course_id=lesson.course_id)
        .filter(Q(orden__gt=lesson.orden) | Q(orden=lesson.orden, lesson_id__gt=lesson.lesson_id))
        .order_by('orden', 'lesson_id')
        .first()
    )
    lesson_completed = ProgresoUsuario.objects.filter(
        user_id=request.user.user_id,
        lesson_id=lesson_id,
        estado='completado',
    ).exists()

    lesson.id = lesson.pk
    if previous_lesson:
        previous_lesson.id = previous_lesson.pk
    if next_lesson:
        next_lesson.id = next_lesson.pk

    language_name = (course.language.nombre if course.language_id else 'python').lower()
    code_mode = 'text/x-java' if language_name == 'java' else 'python'

    return render(
        request,
        'student/lesson_detail.html',
        {
            'lesson': lesson,
            'leccion': lesson,
            'curso': _course_payload(course),
            'racha': request.user.racha or 0,
            'leccion_completada': lesson_completed,
            'leccion_anterior': previous_lesson,
            'leccion_siguiente': next_lesson,
            'code_mode': code_mode,
        },
    )


@login_required
@require_POST
def student_lesson_complete(request, lesson_id):
    lesson = get_object_or_404(Leccion, lesson_id=lesson_id)
    ProgresoUsuario.objects.get_or_create(
        user=request.user,
        lesson=lesson,
        defaults={'estado': 'completado', 'completado_en': timezone.now(), 'puntaje': 100},
    )
    messages.success(request, 'Lección marcada como completada.')
    return redirect('core:student_lesson_detail', lesson_id=lesson_id)


@login_required
def student_progress(request):
    uid = request.user.user_id
    progress = ProgresoUsuario.objects.filter(user_id=uid).select_related('lesson', 'lesson__course').order_by('-completado_en')
    progress_list = list(progress)
    grouped_courses = {}
    for item in progress_list:
        if not item.lesson_id or not item.lesson.course_id:
            continue
        course = item.lesson.course
        entry = grouped_courses.setdefault(
            course.course_id,
            {
                'curso_id': course.course_id,
                'curso_titulo': course.titulo,
                'categoria': course.category.nombre if course.category_id else 'Sin categoría',
                'nivel': course.nivel or 'N/A',
                'total_lecciones': Leccion.objects.filter(course_id=course.course_id).count(),
                'lecciones_completadas': 0,
            },
        )
        if item.estado == 'completado':
            entry['lecciones_completadas'] += 1

    resumen_cursos = []
    for entry in grouped_courses.values():
        total_lessons = entry['total_lecciones'] or 0
        completed_lessons = entry['lecciones_completadas']
        percentage = int((completed_lessons / total_lessons) * 100) if total_lessons else 0
        entry['porcentaje'] = percentage
        resumen_cursos.append(entry)

    context = {
        'racha': request.user.racha or 0,
        'progress_items': progress,
        'completed_count': progress.filter(estado='completado').count(),
        'courses_count': Inscripcion.objects.filter(user_id=uid).count(),
        'lecciones_completadas': sum(1 for item in progress_list if item.estado == 'completado'),
        'puntos_totales': int(sum(item.puntaje or 0 for item in progress_list)),
        'resumen_cursos': resumen_cursos,
        'progresos': [
            {
                'lesson_title': item.lesson.titulo if item.lesson_id else 'Lección',
                'course_title': item.lesson.course.titulo if item.lesson_id and item.lesson.course_id else 'Curso',
                'completado_en': item.completado_en.strftime('%d/%m/%Y %H:%M') if item.completado_en else '',
                'estado': item.estado,
                'puntaje': item.puntaje or 0,
            }
            for item in progress_list
        ],
    }
    return render(request, 'student/progress.html', context)


@login_required
def developer_dashboard(request):
    if _normalize_role(request.user.rol) != 'desarrollador':
        return redirect(_role_redirect(request.user.rol))

    uid = request.user.user_id
    my_courses = Curso.objects.filter(creado_por_id=uid)
    context = {
        'racha': request.user.racha or 0,
        'mis_cursos': my_courses.count(),
        'total_lecciones': Leccion.objects.filter(course__creado_por_id=uid).count(),
        'total_desafios': Desafio.objects.filter(course__creado_por_id=uid).count(),
        'pendientes': DesafioUsuario.objects.filter(challenge__course__creado_por_id=uid, estado='pendiente').count(),
        'cursos_recientes': [_course_payload(c) for c in my_courses.order_by('-course_id')[:6]],
    }
    return render(request, 'developer/dashboard.html', context)


@login_required
def developer_courses(request):
    uid = request.user.user_id
    courses = [_course_payload(c) for c in Curso.objects.filter(creado_por_id=uid).select_related('language', 'category').order_by('-course_id')]
    context = {
        'racha': request.user.racha or 0,
        'pendientes': DesafioUsuario.objects.filter(challenge__course__creado_por_id=uid, estado='pendiente').count(),
        'cursos': courses,
    }
    return render(request, 'developer/courses.html', context)


@login_required
def developer_course_create(request):
    if request.method == 'POST':
        Curso.objects.create(
            titulo=request.POST.get('titulo', '').strip(),
            descripcion=request.POST.get('descripcion', '').strip(),
            category_id=int(request.POST.get('category_id')),
            language_id=int(request.POST.get('language_id')) if request.POST.get('language_id') else None,
            nivel=request.POST.get('nivel') or None,
            duracion_estimada=int(request.POST.get('duracion_d') or 0),
            estado=0,
            requisitos=request.POST.get('requisitos', ''),
            creado_por=request.user,
        )
        messages.success(request, 'Curso enviado para revisión.')
        return redirect('core:developer_courses')

    context = {'create': True}
    context.update(_course_form_context())
    return render(request, 'developer/course_form.html', context)


@login_required
def developer_course_view(request, course_id):
    course = get_object_or_404(Curso.objects.select_related('language', 'category', 'creado_por'), course_id=course_id, creado_por=request.user)
    lessons = [_lesson_payload(l) for l in Leccion.objects.filter(course_id=course_id).order_by('orden')]
    return render(
        request,
        'developer/course_detail.html',
        {
            'curso': _course_payload(course),
            'lecciones': lessons,
            'total_lecciones': len(lessons),
            'lecciones_activas': sum(1 for lesson in lessons if lesson['visible']),
            'pendientes': DesafioUsuario.objects.filter(
                challenge__course__creado_por_id=request.user.user_id,
                estado='pendiente',
            ).count(),
            'racha': request.user.racha or 0,
        },
    )


@login_required
def developer_course_lessons(request, course_id):
    course = get_object_or_404(Curso, course_id=course_id, creado_por=request.user)
    lessons = [_lesson_payload(l) for l in Leccion.objects.filter(course_id=course_id).order_by('orden')]
    return render(
        request,
        'developer/course_lessons.html',
        {
            'curso': _course_payload(course),
            'lecciones': lessons,
            'pendientes': DesafioUsuario.objects.filter(
                challenge__course__creado_por_id=request.user.user_id,
                estado='pendiente',
            ).count(),
            'racha': request.user.racha or 0,
        },
    )


@login_required
def developer_lesson_create(request, course_id):
    course = get_object_or_404(Curso, course_id=course_id, creado_por=request.user)
    if request.method == 'POST':
        if not request.POST.get('titulo', '').strip() or not request.POST.get('contenido', '').strip():
            context = _developer_lesson_form_context(request.user, course, posted=request.POST, error='Debes completar los campos obligatorios.')
            return render(request, 'developer/lesson_form.html', context)

        Leccion.objects.create(
            course=course,
            titulo=request.POST.get('titulo', '').strip(),
            contenido=request.POST.get('contenido', '').strip(),
            codigo_ejemplo=request.POST.get('codigo_ejemplo', '').strip() or None,
            orden=int(request.POST.get('orden') or 1),
            estado='aprobada' if request.POST.get('visible') == 'true' else 'pendiente',
        )
        _normalize_course_lesson_order(course_id)
        messages.success(request, 'Lección creada.')
        return redirect('core:developer_course_lessons', course_id=course_id)
    return render(request, 'developer/lesson_form.html', _developer_lesson_form_context(request.user, course))


@login_required
@require_POST
def developer_lesson_code_execute(request, course_id):
    if _normalize_role(request.user.rol) != 'desarrollador':
        return JsonResponse({'error': 'No autorizado.'}, status=403)

    course = get_object_or_404(Curso, course_id=course_id, creado_por=request.user)

    try:
        payload = json.loads((request.body or b'{}').decode('utf-8'))
    except Exception:
        payload = {}

    language = (payload.get('language') or payload.get('lenguaje') or (course.language.nombre if course.language_id else 'python')).strip().lower()
    code = payload.get('code') or payload.get('codigo') or ''

    if not code.strip():
        return JsonResponse({'error': 'Escribe código antes de ejecutar.'}, status=400)

    result = execute_code(language, code)
    if result.get('success'):
        return JsonResponse({'salida': result.get('output', ''), 'error': result.get('error', '')})

    error_text = result.get('error') or result.get('output') or 'No se pudo ejecutar el código.'
    return JsonResponse({'error': error_text}, status=400)


@login_required
def dev_course_edit(request, course_id):
    course = get_object_or_404(Curso, course_id=course_id, creado_por=request.user)
    if request.method == 'POST':
        course.titulo = request.POST.get('titulo', '').strip()
        course.descripcion = request.POST.get('descripcion', '').strip()
        course.category_id = int(request.POST.get('category_id'))
        course.language_id = int(request.POST.get('language_id')) if request.POST.get('language_id') else None
        course.nivel = request.POST.get('nivel') or None
        course.duracion_estimada = int(request.POST.get('duracion_d') or 0)
        course.requisitos = request.POST.get('requisitos', '')
        course.estado = 0
        course.save()
        messages.success(request, 'Curso actualizado y enviado a revisión.')
        return redirect('core:developer_courses')

    context = {'create': False, 'legacy_id': course.pk}
    context.update(_course_form_context(course=course))
    return render(request, 'developer/course_form.html', context)


@login_required
def dev_course_delete(request, course_id):
    course = get_object_or_404(Curso, course_id=course_id, creado_por=request.user)
    if request.method == 'POST':
        course.delete()
        messages.success(request, 'Curso eliminado.')
        return redirect('core:developer_courses')

    return render(
        request,
        'developer/course_confirm_delete.html',
        {
            'titulo': course.titulo or 'Curso sin título',
            'curso': _course_payload(course),
            'pendientes': DesafioUsuario.objects.filter(
                challenge__course__creado_por_id=request.user.user_id,
                estado='pendiente',
            ).count(),
            'racha': request.user.racha or 0,
        },
    )


@login_required
def developer_lesson_edit(request, lesson_id):
    lesson = get_object_or_404(Leccion, lesson_id=lesson_id, course__creado_por=request.user)
    if request.method == 'POST':
        if not request.POST.get('titulo', '').strip() or not request.POST.get('contenido', '').strip():
            context = _developer_lesson_form_context(request.user, lesson.course, lesson=lesson, posted=request.POST, error='Debes completar los campos obligatorios.')
            return render(request, 'developer/lesson_form.html', context)

        lesson.titulo = request.POST.get('titulo', '').strip()
        lesson.contenido = request.POST.get('contenido', '').strip()
        lesson.codigo_ejemplo = request.POST.get('codigo_ejemplo', '').strip() or None
        lesson.orden = int(request.POST.get('orden') or lesson.orden or 1)
        lesson.estado = 'aprobada' if request.POST.get('visible') == 'true' else 'pendiente'
        lesson.save()
        _normalize_course_lesson_order(lesson.course_id)
        messages.success(request, 'Lección actualizada.')
        return redirect('core:developer_course_lessons', course_id=lesson.course_id)
    return render(request, 'developer/lesson_form.html', _developer_lesson_form_context(request.user, lesson.course, lesson=lesson))


@login_required
@require_POST
def developer_lesson_delete(request, lesson_id):
    lesson = get_object_or_404(Leccion, lesson_id=lesson_id, course__creado_por=request.user)
    course_id = lesson.course_id
    lesson.delete()
    _normalize_course_lesson_order(course_id)
    messages.success(request, 'Lección eliminada.')
    return redirect('core:developer_course_lessons', course_id=course_id)


@login_required
def developer_statistics(request):
    uid = request.user.user_id
    context = {
        'total_cursos': Curso.objects.filter(creado_por_id=uid).count(),
        'total_lecciones': Leccion.objects.filter(course__creado_por_id=uid).count(),
        'total_desafios': Desafio.objects.filter(course__creado_por_id=uid).count(),
        'total_envios': DesafioUsuario.objects.filter(challenge__course__creado_por_id=uid).count(),
        'pendientes': DesafioUsuario.objects.filter(challenge__course__creado_por_id=uid, estado='pendiente').count(),
        'total_pendientes': DesafioUsuario.objects.filter(challenge__course__creado_por_id=uid, estado='pendiente').count(),
        'cursos_activos': Curso.objects.filter(creado_por_id=uid, estado=1).count(),
        'racha': request.user.racha or 0,
    }
    return render(request, 'developer/statistics.html', context)


@login_required
def developer_my_courses_students(request):
    """Vista que muestra los cursos del desarrollador con cantidad de estudiantes inscritos"""
    if _normalize_role(request.user.rol) != 'desarrollador':
        return redirect(_role_redirect(request.user.rol))

    uid = request.user.user_id
    cursos = Curso.objects.filter(creado_por_id=uid).select_related('category', 'language').order_by('-course_id')

    cursos_data = []
    for curso in cursos:
        total_inscritos = Inscripcion.objects.filter(course_id=curso.course_id).count()
        total_desafios = Desafio.objects.filter(course_id=curso.course_id).count()
        cuales_completaron = DesafioUsuario.objects.filter(
            challenge__course_id=curso.course_id,
            estado='correcto'
        ).values('user_id').distinct().count()

        cursos_data.append({
            'course_id': curso.course_id,
            'titulo': curso.titulo,
            'descripcion': curso.descripcion,
            'nivel': curso.nivel or 'N/A',
            'categoria': curso.category.nombre if curso.category_id else 'Sin categoría',
            'lenguaje': curso.language.nombre if curso.language_id else 'N/A',
            'estado': 'Aprobado' if curso.estado else 'Pendiente',
            'total_inscritos': total_inscritos,
            'total_desafios': total_desafios,
            'estudiantes_completacion': cuales_completaron,
        })

    context = {
        'racha': request.user.racha or 0,
        'cursos': cursos_data,
        'total_cursos': len(cursos_data),
        'total_inscritos': sum(c['total_inscritos'] for c in cursos_data),
    }
    return render(request, 'developer/my_courses_students.html', context)


@login_required
def developer_course_students_detail(request, course_id):
    """Vista que muestra los estudiantes inscritos en un curso específico y sus desafíos completados"""
    if _normalize_role(request.user.rol) != 'desarrollador':
        return redirect(_role_redirect(request.user.rol))

    uid = request.user.user_id
    curso = get_object_or_404(Curso, course_id=course_id, creado_por_id=uid)

    # Obtener estudiantes inscritos en el curso
    estudiantes = Inscripcion.objects.filter(course_id=course_id).select_related('user').order_by('-enrolled_at')

    total_desafios_curso = Desafio.objects.filter(course_id=course_id).count()
    total_lecciones_curso = Leccion.objects.filter(course_id=course_id, estado='aprobada').count()

    estudiantes_data = []
    for inscr in estudiantes:
        usuario = inscr.user
        desafios_completados = DesafioUsuario.objects.filter(
            user_id=usuario.user_id,
            challenge__course_id=course_id,
            estado='correcto'
        ).count()
        desafios_intentados = DesafioUsuario.objects.filter(
            user_id=usuario.user_id,
            challenge__course_id=course_id
        ).count()

        # Obtener detalle de desafíos
        desafios_detalle = []
        submissions = DesafioUsuario.objects.filter(
            user_id=usuario.user_id,
            challenge__course_id=course_id
        ).select_related('challenge').order_by('challenge__titulo')

        for sub in submissions:
            desafios_detalle.append({
                'titulo': sub.challenge.titulo,
                'dificultad': sub.challenge.dificultad,
                'estado': sub.estado,
                'intentos': DesafioUsuario.objects.filter(
                    user_id=usuario.user_id,
                    challenge_id=sub.challenge_id
                ).count(),
                'puntaje': sub.puntaje or 0,
                'enviado_en': sub.enviado_en.strftime('%d/%m/%Y %H:%M') if sub.enviado_en else '-',
            })

        lecciones_completadas = ProgresoUsuario.objects.filter(
            user_id=usuario.user_id,
            lesson__course_id=course_id,
            lesson__estado='aprobada',
            estado='completado',
        ).values('lesson_id').distinct().count()

        porcentaje_lecciones = int((lecciones_completadas / total_lecciones_curso) * 100) if total_lecciones_curso else 0
        porcentaje_desafios = int((desafios_completados / total_desafios_curso) * 100) if total_desafios_curso else 0

        if total_lecciones_curso:
            porcentaje_completacion = porcentaje_lecciones
        elif total_desafios_curso:
            porcentaje_completacion = porcentaje_desafios
        else:
            porcentaje_completacion = 0

        curso_completado = porcentaje_completacion >= 100

        estudiantes_data.append({
            'user_id': usuario.user_id,
            'nombre': usuario.nombre or usuario.username,
            'email': usuario.email,
            'racha': usuario.racha or 0,
            'total_lecciones_curso': total_lecciones_curso,
            'lecciones_completadas': lecciones_completadas,
            'porcentaje_lecciones': porcentaje_lecciones,
            'total_desafios_curso': total_desafios_curso,
            'desafios_completados': desafios_completados,
            'desafios_intentados': desafios_intentados,
            'porcentaje_desafios': porcentaje_desafios,
            'porcentaje_completacion': porcentaje_completacion,
            'curso_completado': curso_completado,
            'desafios_detalle': desafios_detalle,
            'inscrito_en': inscr.enrolled_at.strftime('%d/%m/%Y') if inscr.enrolled_at else '-',
        })

    total_inscritos = len(estudiantes_data)
    tasa_completacion_promedio = int(sum(e['porcentaje_completacion'] for e in estudiantes_data) / total_inscritos) if total_inscritos else 0
    tasa_lecciones_promedio = int(sum(e['porcentaje_lecciones'] for e in estudiantes_data) / total_inscritos) if total_inscritos else 0
    tasa_desafios_promedio = int(sum(e['porcentaje_desafios'] for e in estudiantes_data) / total_inscritos) if total_inscritos else 0

    context = {
        'racha': request.user.racha or 0,
        'course_id': course_id,
        'curso_titulo': curso.titulo,
        'curso_descripcion': curso.descripcion,
        'curso_nivel': curso.nivel or 'N/A',
        'curso_categoria': curso.category.nombre if curso.category_id else 'Sin categoría',
        'curso_lenguaje': curso.language.nombre if curso.language_id else 'N/A',
        'estudiantes': estudiantes_data,
        'total_inscritos': total_inscritos,
        'tasa_completacion_promedio': tasa_completacion_promedio,
        'tasa_lecciones_promedio': tasa_lecciones_promedio,
        'tasa_desafios_promedio': tasa_desafios_promedio,
        'total_desafios': Desafio.objects.filter(course_id=course_id).count(),
    }
    return render(request, 'developer/course_students_detail.html', context)
