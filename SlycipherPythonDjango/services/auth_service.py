import bcrypt
import logging

logger = logging.getLogger(__name__)


def normalize_bcrypt_hash(raw_hash: str) -> str:
    """Convierte hash PHP $2y$ a Python-compatible $2b$."""
    h = str(raw_hash or '').strip()
    if h.startswith('$2y$'):
        return '$2b$' + h[4:]
    return h


def verify_password(raw_password: str, stored_hash: str) -> bool:
    """
    Verifica una contraseña contra un hash almacenado.
    Soporta:
     - Hashes en formato Django (bcrypt$$2b$...)
     - Hashes raw bcrypt ($2b$... o $2y$...)
    """
    if not raw_password or not stored_hash:
        return False

    # Formato Django: 'bcrypt$$2b$12$...'
    if stored_hash.startswith('bcrypt$'):
        from django.contrib.auth.hashers import check_password as django_check
        try:
            return django_check(raw_password, stored_hash)
        except Exception:
            pass

    # Formato raw bcrypt (legacy PHP o Python directo)
    normalized = normalize_bcrypt_hash(stored_hash)
    if normalized.startswith('$2b$') or normalized.startswith('$2a$'):
        try:
            return bcrypt.checkpw(raw_password.encode('utf-8'), normalized.encode('utf-8'))
        except Exception:
            logger.exception('Error verificando bcrypt hash')

    # Compatibilidad temporal con registros legacy en texto plano.
    if raw_password == str(stored_hash or ''):
        return True

    return False
