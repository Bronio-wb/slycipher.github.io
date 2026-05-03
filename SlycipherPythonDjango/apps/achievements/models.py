from django.db import models
from django.conf import settings


TIPO_LOGRO_CHOICES = [
    ('lecciones', 'Lecciones'),
    ('desafios', 'Desafíos'),
    ('racha', 'Racha'),
    ('especial', 'Especial'),
]


class Logro(models.Model):
    achievement_id = models.BigAutoField(primary_key=True)
    nombre = models.CharField(max_length=100)
    descripcion = models.TextField(blank=True, null=True)
    icono = models.CharField(max_length=50, blank=True, null=True)
    puntos_requeridos = models.IntegerField(null=True, blank=True)
    tipo = models.CharField(max_length=15, choices=TIPO_LOGRO_CHOICES, null=True, blank=True)
    activo = models.BooleanField(default=True)
    fecha_creacion = models.DateTimeField(auto_now_add=True, null=True)

    def __str__(self):
        return self.nombre

    class Meta:
        db_table = 'logros'
        managed = False
        verbose_name = 'Logro'
        verbose_name_plural = 'Logros'


class LogroUsuario(models.Model):
    user_achievement_id = models.BigAutoField(primary_key=True)
    user = models.ForeignKey(
        settings.AUTH_USER_MODEL, on_delete=models.CASCADE,
        db_column='user_id', to_field='user_id', related_name='logros_usuario',
    )
    achievement = models.ForeignKey(
        Logro, on_delete=models.CASCADE,
        db_column='achievement_id', related_name='usuarios',
    )
    desbloqueado_en = models.DateTimeField(null=True, blank=True)

    class Meta:
        db_table = 'logros_usuarios'
        managed = False
        verbose_name = 'Logro de Usuario'
        verbose_name_plural = 'Logros de Usuarios'
