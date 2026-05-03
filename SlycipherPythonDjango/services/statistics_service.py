"""
Servicio de análisis y estadísticas avanzadas para el panel de administración.
Proporciona consultas complejas que facilitan el análisis e interpretación de datos.
"""
from django.db.models import Count, Q, Avg, Case, When, IntegerField, Sum, F
from django.utils import timezone
from datetime import timedelta
from decimal import Decimal

from apps.users.models import Usuario
from apps.courses.models import Curso, Categoria, Lenguaje
from apps.challenges.models import Desafio, DesafioUsuario
from apps.achievements.models import Logro, LogroUsuario


class EstadisticasService:
    """Servicio centralizado para cálculos estadísticos del panel admin."""

    @staticmethod
    def obtener_kpis_principales():
        """
        Obtiene los KPIs principales del sistema.
        Retorna: dict con totales y tasas de engagement.
        """
        total_usuarios = Usuario.objects.count()
        usuarios_activos = Usuario.objects.filter(activo=True).count()
        tasa_activacion = (usuarios_activos / total_usuarios * 100) if total_usuarios > 0 else 0

        total_cursos = Curso.objects.count()
        cursos_activos = Curso.objects.filter(estado=1).count()
        tasa_cursos_activos = (cursos_activos / total_cursos * 100) if total_cursos > 0 else 0

        total_desafios = Desafio.objects.count()
        total_submissions = DesafioUsuario.objects.count()

        return {
            'total_usuarios': total_usuarios,
            'usuarios_activos': usuarios_activos,
            'tasa_activacion': round(tasa_activacion, 2),
            'total_cursos': total_cursos,
            'cursos_activos': cursos_activos,
            'tasa_cursos_activos': round(tasa_cursos_activos, 2),
            'total_desafios': total_desafios,
            'total_submissions': total_submissions,
        }

    @staticmethod
    def obtener_desempeno_desafios():
        """
        Analiza el desempeño en desafíos:
        - Tasa de éxito general
        - Éxito por dificultad
        - Desafíos más completados
        """
        total_submissions = DesafioUsuario.objects.count()
        if total_submissions == 0:
            return {
                'tasa_exito_general': 0,
                'exito_por_dificultad': [],
                'desafios_mas_completados': [],
            }

        # Tasa de éxito general
        submissiones_exitosas = DesafioUsuario.objects.filter(
            estado='correcto'
        ).count()
        tasa_exito = (submissiones_exitosas / total_submissions * 100)

        # Éxito por dificultad
        exito_por_dificultad = DesafioUsuario.objects.values(
            'challenge__dificultad'
        ).annotate(
            total=Count('submission_id'),
            exitosos=Count('submission_id', filter=Q(estado='correcto')),
        ).order_by('challenge__dificultad')

        exito_dificultad_formateado = []
        for item in exito_por_dificultad:
            tasa = (item['exitosos'] / item['total'] * 100) if item['total'] > 0 else 0
            exito_dificultad_formateado.append({
                'dificultad': item['challenge__dificultad'],
                'total': item['total'],
                'exitosos': item['exitosos'],
                'tasa': round(tasa, 2),
            })

        # Desafíos más completados exitosamente
        desafios_populares = Desafio.objects.annotate(
            total_completados=Count(
                'submissions',
                filter=Q(submissions__estado='correcto')
            ),
            intentos_totales=Count('submissions'),
        ).filter(total_completados__gt=0).order_by(
            '-total_completados'
        )[:10].values(
            'challenge_id', 'titulo', 'dificultad', 'total_completados', 'intentos_totales'
        )

        desafios_formateado = []
        for d in desafios_populares:
            tasa = (d['total_completados'] / d['intentos_totales'] * 100) if d['intentos_totales'] > 0 else 0
            desafios_formateado.append({
                'id': d['challenge_id'],
                'titulo': d['titulo'],
                'dificultad': d['dificultad'],
                'completados': d['total_completados'],
                'intentos': d['intentos_totales'],
                'tasa_exito': round(tasa, 2),
            })

        return {
            'tasa_exito_general': round(tasa_exito, 2),
            'exito_por_dificultad': exito_dificultad_formateado,
            'desafios_mas_completados': desafios_formateado,
        }

    @staticmethod
    def obtener_usuarios_mas_activos(limite=10):
        """
        Calcula los usuarios más activos basado en:
        - Desafíos completados
        - Logros desbloqueados
        - Racha actual
        """
        usuarios = Usuario.objects.annotate(
            desafios_completados=Count(
                'desafio_submissions',
                filter=Q(desafio_submissions__estado='correcto')
            ),
            logros_desbloqueados=Count('logros_usuario'),
        ).filter(
            Q(desafios_completados__gt=0) | Q(logros_desbloqueados__gt=0)
        ).order_by('-desafios_completados', '-logros_desbloqueados')[:limite]

        datos = []
        for u in usuarios:
            datos.append({
                'id': u.user_id,
                'nombre': f'{u.nombre} {u.apellido}',
                'email': u.email,
                'desafios_completados': u.desafios_completados,
                'logros': u.logros_desbloqueados,
                'racha': u.racha,
            })

        return datos

    @staticmethod
    def obtener_cursos_mas_populares(limite=10):
        """
        Identifica los cursos más populares basado en:
        - Cantidad de desafíos
        - Participación total (intentos en desafíos)
        """
        cursos = Curso.objects.annotate(
            total_desafios=Count('desafios'),
            total_participaciones=Count('desafios__submissions'),
        ).filter(estado=1).order_by(
            '-total_participaciones', '-total_desafios'
        )[:limite].values(
            'course_id', 'titulo', 'nivel', 'total_desafios', 'total_participaciones'
        )

        datos = []
        for c in cursos:
            datos.append({
                'id': c['course_id'],
                'titulo': c['titulo'],
                'nivel': c['nivel'],
                'desafios': c['total_desafios'],
                'participaciones': c['total_participaciones'],
            })

        return datos

    @staticmethod
    def obtener_distribucion_por_nivel():
        """Distribución de cursos por nivel de dificultad."""
        distribucion = Curso.objects.values('nivel').annotate(
            total=Count('course_id')
        ).order_by('-total')

        datos = []
        nivel_labels = {
            'principiante': 'Principiante',
            'intermedio': 'Intermedio',
            'avanzado': 'Avanzado',
        }

        for item in distribucion:
            datos.append({
                'nivel': nivel_labels.get(item['nivel'], item['nivel'] or 'Sin especificar'),
                'cantidad': item['total'],
            })

        return datos

    @staticmethod
    def obtener_logros_mas_desbloqueados(limite=10):
        """Logros más desbloqueados por los usuarios."""
        logros = Logro.objects.annotate(
            usuarios_desbloqueados=Count('usuarios')
        ).filter(usuarios_desbloqueados__gt=0).order_by(
            '-usuarios_desbloqueados'
        )[:limite].values(
            'achievement_id', 'nombre', 'tipo', 'usuarios_desbloqueados'
        )

        return list(logros)

    @staticmethod
    def obtener_tendencia_usuarios_30_dias():
        """
        Obtiene la tendencia de registro de usuarios en los últimos 30 días.
        Retorna datos diarios de registros.
        """
        hace_30_dias = timezone.now() - timedelta(days=30)

        registros_por_dia = Usuario.objects.filter(
            creado_en__gte=hace_30_dias
        ).extra(
            select={'fecha': "DATE(creado_en)"}
        ).values('fecha').annotate(
            total=Count('user_id')
        ).order_by('fecha')

        datos = []
        for item in registros_por_dia:
            datos.append({
                'fecha': str(item['fecha']),
                'registros': item['total'],
            })

        return datos

    @staticmethod
    def obtener_analisis_por_categoria():
        """
        Análisis profundo por categoría:
        - Cursos, desafíos, participación, tasa de éxito
        """
        categorias = Categoria.objects.annotate(
            total_cursos=Count('cursos'),
            total_desafios=Count('cursos__desafios'),
            total_intentos=Count('cursos__desafios__submissions'),
            intentos_exitosos=Count(
                'cursos__desafios__submissions',
                filter=Q(cursos__desafios__submissions__estado='correcto')
            ),
        ).order_by('-total_cursos')

        datos = []
        for c in categorias:
            tasa_exito = 0
            if c.total_intentos > 0:
                tasa_exito = (c.intentos_exitosos / c.total_intentos * 100)

            datos.append({
                'id': c.category_id,
                'nombre': c.nombre or 'Sin categoría',
                'cursos': c.total_cursos,
                'desafios': c.total_desafios,
                'intentos': c.total_intentos,
                'exitosos': c.intentos_exitosos,
                'tasa_exito': round(tasa_exito, 2),
            })

        return datos

    @staticmethod
    def obtener_analisis_por_lenguaje():
        """
        Análisis profundo por lenguaje de programación:
        - Cursos, desafíos, participación, tasa de éxito
        """
        lenguajes = Lenguaje.objects.annotate(
            total_cursos=Count('cursos'),
            total_desafios=Count('desafios'),
            total_intentos=Count('desafios__submissions'),
            intentos_exitosos=Count(
                'desafios__submissions',
                filter=Q(desafios__submissions__estado='correcto')
            ),
        ).order_by('-total_desafios')

        datos = []
        for l in lenguajes:
            tasa_exito = 0
            if l.total_intentos > 0:
                tasa_exito = (l.intentos_exitosos / l.total_intentos * 100)

            datos.append({
                'id': l.language_id,
                'nombre': l.nombre or 'Sin especificar',
                'cursos': l.total_cursos,
                'desafios': l.total_desafios,
                'intentos': l.total_intentos,
                'exitosos': l.intentos_exitosos,
                'tasa_exito': round(tasa_exito, 2),
            })

        return datos

    @staticmethod
    def obtener_segmentacion_usuarios():
        """
        Segmenta usuarios según su actividad:
        - Usuarios muy activos (racha > 7)
        - Activos (participación reciente)
        - Moderadamente activos (alguna participación)
        - Inactivos (sin participación)
        """
        hace_7_dias = timezone.now() - timedelta(days=7)

        muy_activos = Usuario.objects.filter(racha__gt=7).count()
        activos_recientes = Usuario.objects.filter(
            last_login__gte=hace_7_dias
        ).count()

        con_participacion = Usuario.objects.filter(
            desafio_submissions__isnull=False
        ).distinct().count()

        total_usuarios = Usuario.objects.count()
        inactivos = total_usuarios - min(muy_activos + activos_recientes, total_usuarios)

        return {
            'muy_activos': muy_activos,
            'activos_recientes': activos_recientes,
            'con_participacion': con_participacion,
            'inactivos': max(0, inactivos),
            'total': total_usuarios,
        }

    @staticmethod
    def obtener_resumen_ejecutivo():
        """
        Resumen ejecutivo con los datos más importantes para tomar decisiones.
        """
        kpis = EstadisticasService.obtener_kpis_principales()
        desafios = EstadisticasService.obtener_desempeno_desafios()
        usuarios_activos = EstadisticasService.obtener_usuarios_mas_activos(5)
        cursos_populares = EstadisticasService.obtener_cursos_mas_populares(5)
        segmentacion = EstadisticasService.obtener_segmentacion_usuarios()

        return {
            'kpis': kpis,
            'desempeno_desafios': desafios,
            'top_usuarios': usuarios_activos,
            'top_cursos': cursos_populares,
            'segmentacion_usuarios': segmentacion,
        }
