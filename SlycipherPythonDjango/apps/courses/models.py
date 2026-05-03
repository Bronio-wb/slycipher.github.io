from django.db import models
from django.conf import settings


NIVEL_CHOICES = [
    ('principiante', 'Principiante'),
    ('intermedio', 'Intermedio'),
    ('avanzado', 'Avanzado'),
]

ESTADO_LECCION_CHOICES = [
    ('pendiente', 'Pendiente'),
    ('aprobada', 'Aprobada'),
    ('rechazada', 'Rechazada'),
]

ESTADO_PROGRESO_CHOICES = [
    ('en_progreso', 'En progreso'),
    ('completado', 'Completado'),
]


class Categoria(models.Model):
    category_id = models.AutoField(primary_key=True)
    nombre = models.CharField(max_length=50, blank=True, null=True)
    descripcion = models.TextField(blank=True, null=True)

    def __str__(self):
        return self.nombre or f'Categoría {self.category_id}'

    class Meta:
        db_table = 'categorias'
        managed = False
        verbose_name = 'Categoría'
        verbose_name_plural = 'Categorías'


class Lenguaje(models.Model):
    language_id = models.AutoField(primary_key=True)
    nombre = models.CharField(max_length=50)
    descripcion = models.TextField(blank=True, null=True)

    def __str__(self):
        return self.nombre

    class Meta:
        db_table = 'lenguajes'
        managed = False
        verbose_name = 'Lenguaje'
        verbose_name_plural = 'Lenguajes'


class Curso(models.Model):
    """
    Mapa ORM de la tabla `cursos`.
    estado: TINYINT(1) — 1 = activo/aprobado, 0 = pendiente/inactivo
    """
    course_id = models.AutoField(primary_key=True)
    titulo = models.CharField(max_length=100, blank=True, null=True)
    descripcion = models.TextField(blank=True, null=True)
    nivel = models.CharField(max_length=15, choices=NIVEL_CHOICES, blank=True, null=True)
    language = models.ForeignKey(
        Lenguaje, on_delete=models.RESTRICT,
        db_column='language_id', related_name='cursos',
    )
    category = models.ForeignKey(
        Categoria, on_delete=models.RESTRICT,
        db_column='category_id', related_name='cursos',
    )
    creado_por = models.ForeignKey(
        settings.AUTH_USER_MODEL, on_delete=models.RESTRICT,
        db_column='creado_por', related_name='cursos_creados',
        to_field='user_id',
    )
    estado = models.IntegerField(default=0, null=True, blank=True)
    duracion_estimada = models.IntegerField(null=True, blank=True)
    precio = models.DecimalField(max_digits=8, decimal_places=2, default=0, null=True, blank=True)
    requisitos = models.TextField(blank=True, null=True)
    fecha_creacion = models.DateTimeField(auto_now_add=True, null=True, blank=True)

    def __str__(self):
        return self.titulo or f'Curso {self.course_id}'

    @property
    def estado_texto(self):
        return 'aprobada' if self.estado else 'pendiente'

    @property
    def esta_activo(self):
        return bool(self.estado)

    class Meta:
        db_table = 'cursos'
        managed = False
        verbose_name = 'Curso'
        verbose_name_plural = 'Cursos'


class Leccion(models.Model):
    lesson_id = models.AutoField(primary_key=True)
    course = models.ForeignKey(
        Curso, on_delete=models.CASCADE,
        db_column='course_id', related_name='lecciones',
    )
    titulo = models.CharField(max_length=100)
    contenido = models.TextField(blank=True, null=True)
    codigo_ejemplo = models.TextField(blank=True, null=True)
    orden = models.IntegerField(null=True, blank=True)
    estado = models.CharField(
        max_length=10, choices=ESTADO_LECCION_CHOICES, default='pendiente',
    )

    def __str__(self):
        return self.titulo

    @property
    def visible(self):
        return self.estado == 'aprobada'

    class Meta:
        db_table = 'lecciones'
        managed = False
        ordering = ['orden']
        verbose_name = 'Lección'
        verbose_name_plural = 'Lecciones'


class ProgresoUsuario(models.Model):
    progress_id = models.AutoField(primary_key=True)
    user = models.ForeignKey(
        settings.AUTH_USER_MODEL, on_delete=models.CASCADE,
        db_column='user_id', to_field='user_id', related_name='progresos',
    )
    lesson = models.ForeignKey(
        Leccion, on_delete=models.CASCADE,
        db_column='lesson_id', related_name='progresos',
    )
    estado = models.CharField(
        max_length=15, choices=ESTADO_PROGRESO_CHOICES, default='en_progreso',
    )
    completado_en = models.DateTimeField(null=True, blank=True)
    puntaje = models.FloatField(null=True, blank=True, default=0)

    class Meta:
        db_table = 'progreso_usuarios'
        managed = False
        verbose_name = 'Progreso de Usuario'
        verbose_name_plural = 'Progresos de Usuarios'


class Inscripcion(models.Model):
    """
    Tabla de inscripciones gestionada por Django (nueva tabla).
    Registra qué estudiantes están inscritos en qué cursos.
    """
    user = models.ForeignKey(
        settings.AUTH_USER_MODEL, on_delete=models.CASCADE,
        db_column='user_id', to_field='user_id', related_name='inscripciones',
    )
    course = models.ForeignKey(
        Curso, on_delete=models.CASCADE,
        db_column='course_id', related_name='inscripciones',
    )
    enrolled_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        db_table = 'inscripciones'
        managed = True
        unique_together = [('user', 'course')]
        verbose_name = 'Inscripción'
        verbose_name_plural = 'Inscripciones'

    def __str__(self):
        return f'{self.user} → {self.course}'
