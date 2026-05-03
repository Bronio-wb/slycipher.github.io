from django.contrib.auth.models import AbstractBaseUser, BaseUserManager
from django.db import models

ROL_CHOICES = [
    ('admin', 'Admin'),
    ('estudiante', 'Estudiante'),
    ('desarrollador', 'Desarrollador'),
]


class UsuarioManager(BaseUserManager):
    def create_user(self, email, nombre='', apellido='', rol='estudiante', password=None, **extra_fields):
        if not email:
            raise ValueError('El email es obligatorio.')
        email = self.normalize_email(email)
        extra_fields.setdefault('username', email)
        user = self.model(email=email, nombre=nombre, apellido=apellido, rol=rol, **extra_fields)
        user.set_password(password)
        user.save(using=self._db)
        return user

    def create_superuser(self, email, password=None, **extra_fields):
        extra_fields.setdefault('nombre', 'Admin')
        extra_fields.setdefault('apellido', 'Admin')
        extra_fields.setdefault('rol', 'admin')
        extra_fields.setdefault('activo', True)
        extra_fields.setdefault('estado', True)
        return self.create_user(email, password=password, **extra_fields)


class Usuario(AbstractBaseUser):
    """Modelo de usuario personalizado que mapea a la tabla `usuarios` existente."""

    user_id = models.AutoField(primary_key=True)
    username = models.CharField(max_length=100)
    nombre = models.CharField(max_length=100)
    apellido = models.CharField(max_length=100)
    email = models.EmailField(max_length=150, unique=True)
    fecha_nacimiento = models.DateField(null=True, blank=True)

    # Mapea el campo password al nombre de columna `password_hash`
    password = models.CharField(max_length=255, db_column='password_hash')

    rol = models.CharField(max_length=15, choices=ROL_CHOICES, default='estudiante')
    creado_en = models.DateTimeField(auto_now_add=True)

    # Mapea last_login al nombre de columna `ultimo_login`
    last_login = models.DateTimeField(null=True, blank=True, db_column='ultimo_login')

    estado = models.IntegerField(default=1)
    activo = models.BooleanField(default=True)
    racha = models.IntegerField(default=0)

    USERNAME_FIELD = 'email'
    REQUIRED_FIELDS = ['username', 'nombre', 'apellido']

    objects = UsuarioManager()

    # ── Propiedades requeridas por Django auth ─────────────────────────────────
    @property
    def is_active(self):
        return bool(self.activo)

    @is_active.setter
    def is_active(self, value):
        self.activo = bool(value)

    @property
    def is_staff(self):
        return self.rol in ('admin', 'desarrollador')

    @property
    def is_superuser(self):
        return self.rol == 'admin'

    def has_perm(self, perm, obj=None):
        return self.rol == 'admin'

    def has_module_perms(self, app_label):
        return self.rol == 'admin'

    def get_full_name(self):
        return f"{self.nombre} {self.apellido}".strip()

    def get_short_name(self):
        return self.nombre

    def __str__(self):
        return self.email

    class Meta:
        db_table = 'usuarios'
        managed = False
        verbose_name = 'Usuario'
        verbose_name_plural = 'Usuarios'


class Profile(models.Model):
    """Tabla extra gestionada por Django para campos de perfil adicionales."""

    user = models.OneToOneField(
        Usuario,
        on_delete=models.CASCADE,
        related_name='profile',
        db_column='user_id',
        to_field='user_id',
    )
    tipo_documento = models.CharField(max_length=10, blank=True, default='')
    numero_documento = models.CharField(max_length=50, blank=True, default='')

    class Meta:
        db_table = 'profiles'
        managed = True

    def __str__(self):
        return f"Perfil de {self.user.get_full_name()}"


class CertificadoEmitido(models.Model):
    TIPO_CHOICES = [
        ('general', 'General'),
        ('curso', 'Curso'),
    ]

    certificate_id = models.BigAutoField(primary_key=True)
    student = models.ForeignKey(
        Usuario,
        on_delete=models.CASCADE,
        related_name='certificados_recibidos',
        db_column='student_id',
        to_field='user_id',
    )
    issued_by = models.ForeignKey(
        Usuario,
        on_delete=models.SET_NULL,
        related_name='certificados_emitidos',
        db_column='issued_by_id',
        to_field='user_id',
        null=True,
        blank=True,
    )
    course = models.ForeignKey(
        'courses.Curso',
        on_delete=models.SET_NULL,
        related_name='certificados_generados',
        db_column='course_id',
        null=True,
        blank=True,
    )
    certificate_type = models.CharField(max_length=20, choices=TIPO_CHOICES, default='general')
    code = models.CharField(max_length=40, unique=True)
    total_courses = models.IntegerField(default=0)
    notes = models.TextField(blank=True, default='')
    issued_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        db_table = 'certificados_emitidos'
        managed = True
        ordering = ['-issued_at']
        verbose_name = 'Certificado emitido'
        verbose_name_plural = 'Certificados emitidos'

    def __str__(self):
        return f'{self.student} - {self.certificate_type} - {self.code}'
