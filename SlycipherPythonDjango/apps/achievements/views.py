from django.contrib.auth.decorators import login_required
from django.shortcuts import render

from apps.achievements.models import Logro, LogroUsuario
from apps.challenges.models import DesafioUsuario
from apps.courses.models import ProgresoUsuario


def _achievement_icon(icon_name: str, achievement_type: str) -> str:
    if icon_name:
        return icon_name

    type_key = (achievement_type or '').lower()
    return {
        'lecciones': 'bi-journal-check',
        'desafios': 'bi-trophy-fill',
        'racha': 'bi-fire',
        'especial': 'bi-stars',
    }.get(type_key, 'bi-award-fill')


def _achievement_accent(achievement_type: str) -> str:
    type_key = (achievement_type or '').lower()
    return {
        'lecciones': 'blue',
        'desafios': 'gold',
        'racha': 'fire',
        'especial': 'purple',
    }.get(type_key, 'silver')


@login_required
def student_achievements(request):
    uid = request.user.user_id
    racha = request.user.racha or 0

    lecciones_completadas = ProgresoUsuario.objects.filter(user_id=uid, estado='completado').count()
    desafios_resueltos = DesafioUsuario.objects.filter(user_id=uid, estado='correcto').count()

    unlocked_ids = set(
        LogroUsuario.objects.filter(user_id=uid).values_list('achievement_id', flat=True)
    )

    all_achievements = Logro.objects.filter(activo=True).order_by('achievement_id')

    achievements_payload = []
    for achievement in all_achievements:
        target = achievement.puntos_requeridos or 1
        achievement_type = (achievement.tipo or '').lower()

        if achievement_type == 'lecciones':
            current = lecciones_completadas
        elif achievement_type == 'desafios':
            current = desafios_resueltos
        elif achievement_type == 'racha':
            current = racha
        else:
            current = 1 if achievement.achievement_id in unlocked_ids else 0

        progress = 100 if target <= 0 else min(100, int((current / target) * 100))
        unlocked = achievement.achievement_id in unlocked_ids or (achievement_type in {'lecciones', 'desafios', 'racha'} and current >= target)

        achievements_payload.append(
            {
                'title': achievement.nombre or 'Logro',
                'description': achievement.descripcion or 'Sigue avanzando para desbloquear este logro.',
                'icon': _achievement_icon(achievement.icono, achievement_type),
                'accent': _achievement_accent(achievement_type),
                'target': target,
                'current': min(current, target) if unlocked else current,
                'progress': progress,
                'unlocked': unlocked,
            }
        )

    context = {
        'achievements': achievements_payload,
        'racha': racha,
        'lecciones_completadas': lecciones_completadas,
        'desbloqueados': sum(1 for item in achievements_payload if item['unlocked']),
        'total_logros': len(achievements_payload),
    }
    return render(request, 'student/achievements.html', context)
