from django.db import models
from django.conf import settings


DIFICULTAD_CHOICES = [
    ('facil', 'Fácil'),
    ('medio', 'Medio'),
    ('dificil', 'Difícil'),
]

ESTADO_SUBMISSION_CHOICES = [
    ('pendiente', 'Pendiente'),
    ('correcto', 'Correcto'),
    ('incorrecto', 'Incorrecto'),
]


class Desafio(models.Model):
    challenge_id = models.AutoField(primary_key=True)
    course = models.ForeignKey(
        'courses.Curso', on_delete=models.CASCADE,
        db_column='course_id', related_name='desafios',
    )
    titulo = models.CharField(max_length=100)
    descripcion = models.TextField(blank=True, null=True)
    dificultad = models.CharField(
        max_length=10, choices=DIFICULTAD_CHOICES, default='facil',
    )
    solucion = models.TextField(blank=True, null=True)
    language = models.ForeignKey(
        'courses.Lenguaje', on_delete=models.CASCADE,
        db_column='language_id', related_name='desafios',
    )

    def __str__(self):
        return self.titulo

    class Meta:
        db_table = 'desafios'
        managed = False
        verbose_name = 'Desafío'
        verbose_name_plural = 'Desafíos'


class DesafioUsuario(models.Model):
    submission_id = models.BigAutoField(primary_key=True)
    user = models.ForeignKey(
        settings.AUTH_USER_MODEL, on_delete=models.SET_NULL,
        null=True, db_column='user_id', to_field='user_id',
        related_name='desafio_submissions',
    )
    challenge = models.ForeignKey(
        Desafio, on_delete=models.SET_NULL,
        null=True, db_column='challenge_id', related_name='submissions',
    )
    solucion_enviada = models.TextField(blank=True, null=True)
    estado = models.CharField(
        max_length=15, choices=ESTADO_SUBMISSION_CHOICES, null=True, blank=True,
    )
    puntaje = models.IntegerField(null=True, blank=True)
    enviado_en = models.DateTimeField(auto_now_add=True, null=True)
    evaluado_en = models.DateTimeField(null=True, blank=True)

    class Meta:
        db_table = 'desafio_usuarios'
        managed = False
        verbose_name = 'Envío de Desafío'
        verbose_name_plural = 'Envíos de Desafíos'
