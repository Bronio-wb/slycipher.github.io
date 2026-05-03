import logging
from services.auth_service import verify_password, normalize_bcrypt_hash

logger = logging.getLogger(__name__)


class UsuarioBackend:
    """Backend de autenticación para el modelo Usuario con soporte bcrypt legacy."""

    def authenticate(self, request, username=None, email=None, password=None, **kwargs):
        identifier = (email or username or kwargs.get('username') or kwargs.get('email') or '').strip()
        if not identifier or not password:
            return None

        from apps.users.models import Usuario

        # Busca por email o username
        user = None
        try:
            if '@' in str(identifier):
                user = Usuario.objects.get(email__iexact=identifier)
            else:
                user = Usuario.objects.filter(username__iexact=identifier).first()
                if user is None:
                    user = Usuario.objects.filter(email__iexact=identifier).first()
        except Usuario.DoesNotExist:
            return None
        except Exception:
            logger.exception('Error buscando usuario en autenticación')
            return None

        if user is None or not user.activo:
            return None

        stored_hash = user.password
        if not stored_hash:
            return None

        if verify_password(password, stored_hash):
            # Si el valor legacy no está en formato Django, actualizarlo al formato nativo de Django.
            if not stored_hash.startswith('bcrypt$'):
                try:
                    user.set_password(password)
                    user.save(update_fields=['password'])
                except Exception:
                    pass
            return user

        return None

    def get_user(self, user_id):
        from apps.users.models import Usuario
        try:
            return Usuario.objects.get(pk=user_id)
        except Usuario.DoesNotExist:
            return None
