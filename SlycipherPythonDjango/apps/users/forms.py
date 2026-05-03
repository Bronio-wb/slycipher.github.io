from django import forms
from django.conf import settings
import re


class AdminUserForm(forms.Form):
    """Formulario para crear/editar usuarios desde el panel de administración."""

    email = forms.EmailField(
        label='Correo electrónico',
        widget=forms.EmailInput(attrs={'autocomplete': 'off'}),
    )
    first_name = forms.CharField(
        max_length=100,
        label='Nombre',
        widget=forms.TextInput(attrs={'autocomplete': 'off'}),
    )
    last_name = forms.CharField(
        max_length=100,
        label='Apellido',
        widget=forms.TextInput(attrs={'autocomplete': 'off'}),
    )
    role = forms.ChoiceField(
        choices=[('estudiante', 'Estudiante'), ('desarrollador', 'Desarrollador'), ('admin', 'Admin')],
        label='Rol',
    )
    password = forms.CharField(
        widget=forms.PasswordInput(attrs={'autocomplete': 'new-password'}),
        required=False,
        label='Nueva Contraseña',
    )
    tipo_documento = forms.ChoiceField(
        choices=[
            ('', 'Seleccionar'),
            ('CC', 'Cédula de Ciudadanía'),
            ('TI', 'Tarjeta de Identidad'),
            ('CE', 'Cédula de Extranjería'),
            ('PP', 'Pasaporte'),
            ('NIT', 'NIT'),
        ],
        required=False,
        label='Tipo de Documento',
        widget=forms.Select(attrs={'autocomplete': 'off'}),
    )
    numero_documento = forms.CharField(
        required=False,
        label='Número de Documento',
        widget=forms.TextInput(attrs={'autocomplete': 'off'}),
    )
    fecha_nacimiento = forms.DateField(
        required=False,
        widget=forms.DateInput(
            format='%Y-%m-%d',
            attrs={'type': 'date', 'min': '1920-01-01', 'max': '2015-12-31', 'autocomplete': 'off'},
        ),
        input_formats=['%Y-%m-%d'],
        label='Fecha de Nacimiento',
    )
    racha = forms.IntegerField(
        required=False,
        label='Racha (días)',
        widget=forms.NumberInput(attrs={'autocomplete': 'off'}),
    )

    def __init__(self, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self.fields['password'].initial = ''

    def clean_password(self):
        pwd = self.cleaned_data.get('password')
        if not pwd:
            return pwd
        if len(pwd) < 8 or len(pwd) > 20:
            raise forms.ValidationError('La contraseña debe tener entre 8 y 20 caracteres')
        if not re.search(r'[A-Z]', pwd):
            raise forms.ValidationError('Debe contener al menos una letra mayúscula')
        if not re.search(r'[a-z]', pwd):
            raise forms.ValidationError('Debe contener al menos una letra minúscula')
        if not re.search(r'\d', pwd):
            raise forms.ValidationError('Debe contener al menos un número')
        if not re.search(r'[@$!%*?&]', pwd):
            raise forms.ValidationError('Debe contener al menos un símbolo (@$!%*?&)')
        return pwd


class RegistrationForm(forms.Form):
    """Formulario de registro de nuevos usuarios."""

    first_name = forms.CharField(max_length=100, label='Nombre')
    last_name = forms.CharField(max_length=100, label='Apellido')
    email = forms.EmailField(label='Correo electrónico')
    password = forms.CharField(
        widget=forms.PasswordInput,
        min_length=8,
        label='Contraseña',
    )
    password2 = forms.CharField(
        widget=forms.PasswordInput,
        label='Confirmar contraseña',
    )
    tipo_documento = forms.ChoiceField(
        choices=[
            ('', 'Seleccionar'),
            ('CC', 'Cédula de Ciudadanía'),
            ('TI', 'Tarjeta de Identidad'),
            ('CE', 'Cédula de Extranjería'),
            ('PP', 'Pasaporte'),
            ('NIT', 'NIT'),
        ],
        required=False,
        label='Tipo de Documento',
    )
    numero_documento = forms.CharField(required=False, label='Número de Documento')
    fecha_nacimiento = forms.DateField(
        required=False,
        widget=forms.DateInput(attrs={'type': 'date'}),
        label='Fecha de Nacimiento',
    )

    def clean_password2(self):
        p1 = self.cleaned_data.get('password')
        p2 = self.cleaned_data.get('password2')
        if p1 and p2 and p1 != p2:
            raise forms.ValidationError('Las contraseñas no coinciden')
        return p2

    def clean_email(self):
        email = self.cleaned_data.get('email', '')
        if not email or '@' not in email:
            raise forms.ValidationError('Ingrese un correo válido que contenga @')
        from apps.users.models import Usuario
        if Usuario.objects.filter(email__iexact=email).exists():
            raise forms.ValidationError('Este correo ya está registrado')
        return email.lower()

    def clean_fecha_nacimiento(self):
        fecha = self.cleaned_data.get('fecha_nacimiento')
        if fecha:
            if fecha.year < 1920 or fecha.year > 2015:
                raise forms.ValidationError('La fecha debe estar entre 1920 y 2015')
        return fecha
