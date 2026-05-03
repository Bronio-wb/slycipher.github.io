import json
from typing import Any

from django.db import OperationalError, ProgrammingError

from apps.achievements.models import Logro
from apps.courses.models import Categoria, Lenguaje


DEFAULT_INITIAL_DATA: dict[str, list[dict[str, Any]]] = {
    'categorias': [
        {
            'nombre': 'Fundamentos',
            'descripcion': 'Bases de programacion, logica y estructuras esenciales.',
        },
        {
            'nombre': 'Backend',
            'descripcion': 'Desarrollo de APIs, bases de datos y servicios.',
        },
        {
            'nombre': 'Frontend',
            'descripcion': 'Interfaces web, estilos y experiencia de usuario.',
        },
        {
            'nombre': 'Seguridad',
            'descripcion': 'Buenas practicas, ciberseguridad y proteccion de datos.',
        },
    ],
    'lenguajes': [
        {
            'nombre': 'Python',
            'descripcion': 'Lenguaje versatil para backend, data y automatizacion.',
        },
        {
            'nombre': 'Java',
            'descripcion': 'Lenguaje orientado a objetos para aplicaciones empresariales.',
        },
        {
            'nombre': 'JavaScript',
            'descripcion': 'Lenguaje principal para web interactiva y aplicaciones modernas.',
        },
        {
            'nombre': 'SQL',
            'descripcion': 'Lenguaje estandar para consultas y administracion de datos.',
        },
    ],
    'logros': [
        {
            'nombre': 'Primeros pasos',
            'descripcion': 'Completa tu primera leccion.',
            'icono': 'fa-seedling',
            'puntos_requeridos': 1,
            'tipo': 'lecciones',
            'activo': True,
        },
        {
            'nombre': 'Constancia inicial',
            'descripcion': 'Mantiene una racha de 3 dias.',
            'icono': 'fa-fire',
            'puntos_requeridos': 3,
            'tipo': 'racha',
            'activo': True,
        },
        {
            'nombre': 'Cazador de desafios',
            'descripcion': 'Resuelve 5 desafios correctamente.',
            'icono': 'fa-bullseye',
            'puntos_requeridos': 5,
            'tipo': 'desafios',
            'activo': True,
        },
        {
            'nombre': 'Explorador Slycipher',
            'descripcion': 'Completa hitos clave del onboarding.',
            'icono': 'fa-compass',
            'puntos_requeridos': 10,
            'tipo': 'especial',
            'activo': True,
        },
    ],
}


def _init_stats() -> dict[str, int]:
    return {'creados': 0, 'actualizados': 0, 'omitidos': 0, 'errores': 0}


def _normalize_text(value: Any) -> str:
    return str(value or '').strip()


def _parse_bool(value: Any) -> bool:
    if isinstance(value, bool):
        return value
    return str(value or '').strip().lower() in ('1', 'true', 'si', 'sí', 'yes', 'activo')


def _as_int(value: Any, default: int = 0) -> int:
    try:
        return int(value)
    except (TypeError, ValueError):
        return default


def _upsert_categories(items: list[dict[str, Any]], update_existing: bool, result: dict[str, Any]) -> None:
    stats = _init_stats()
    for item in items:
        nombre = _normalize_text(item.get('nombre'))
        if not nombre:
            stats['errores'] += 1
            result['detalles'].append('Categoria omitida: falta el campo nombre.')
            continue

        descripcion = _normalize_text(item.get('descripcion'))
        obj = Categoria.objects.filter(nombre__iexact=nombre).first()
        if obj is None:
            Categoria.objects.create(nombre=nombre, descripcion=descripcion)
            stats['creados'] += 1
            continue

        if update_existing and descripcion and (obj.descripcion or '') != descripcion:
            obj.descripcion = descripcion
            obj.save(update_fields=['descripcion'])
            stats['actualizados'] += 1
        else:
            stats['omitidos'] += 1

    result['categorias'] = stats


def _upsert_languages(items: list[dict[str, Any]], update_existing: bool, result: dict[str, Any]) -> None:
    stats = _init_stats()
    for item in items:
        nombre = _normalize_text(item.get('nombre'))
        if not nombre:
            stats['errores'] += 1
            result['detalles'].append('Lenguaje omitido: falta el campo nombre.')
            continue

        descripcion = _normalize_text(item.get('descripcion'))
        obj = Lenguaje.objects.filter(nombre__iexact=nombre).first()
        if obj is None:
            Lenguaje.objects.create(nombre=nombre, descripcion=descripcion)
            stats['creados'] += 1
            continue

        if update_existing and descripcion and (obj.descripcion or '') != descripcion:
            obj.descripcion = descripcion
            obj.save(update_fields=['descripcion'])
            stats['actualizados'] += 1
        else:
            stats['omitidos'] += 1

    result['lenguajes'] = stats


def _upsert_achievements(items: list[dict[str, Any]], update_existing: bool, result: dict[str, Any]) -> None:
    stats = _init_stats()
    for item in items:
        nombre = _normalize_text(item.get('nombre'))
        tipo = _normalize_text(item.get('tipo'))
        if not nombre:
            stats['errores'] += 1
            result['detalles'].append('Logro omitido: falta el campo nombre.')
            continue

        description = _normalize_text(item.get('descripcion'))
        icono = _normalize_text(item.get('icono'))
        puntos_requeridos = _as_int(item.get('puntos_requeridos'), 0)
        activo = _parse_bool(item.get('activo'))

        lookup = {'nombre__iexact': nombre}
        if tipo:
            lookup['tipo'] = tipo

        obj = Logro.objects.filter(**lookup).first()
        if obj is None:
            Logro.objects.create(
                nombre=nombre,
                descripcion=description,
                icono=icono,
                puntos_requeridos=puntos_requeridos,
                tipo=tipo or None,
                activo=activo,
            )
            stats['creados'] += 1
            continue

        if update_existing:
            changed_fields = []
            if description and (obj.descripcion or '') != description:
                obj.descripcion = description
                changed_fields.append('descripcion')
            if icono and (obj.icono or '') != icono:
                obj.icono = icono
                changed_fields.append('icono')
            if (obj.puntos_requeridos or 0) != puntos_requeridos:
                obj.puntos_requeridos = puntos_requeridos
                changed_fields.append('puntos_requeridos')
            if bool(obj.activo) != activo:
                obj.activo = activo
                changed_fields.append('activo')
            if tipo and (obj.tipo or '') != tipo:
                obj.tipo = tipo
                changed_fields.append('tipo')

            if changed_fields:
                obj.save(update_fields=changed_fields)
                stats['actualizados'] += 1
            else:
                stats['omitidos'] += 1
        else:
            stats['omitidos'] += 1

    result['logros'] = stats


def execute_data_load(payload: dict[str, Any], *, update_existing: bool = False) -> dict[str, Any]:
    result = {
        'categorias': _init_stats(),
        'lenguajes': _init_stats(),
        'logros': _init_stats(),
        'detalles': [],
    }

    try:
        categories = payload.get('categorias') or []
        languages = payload.get('lenguajes') or []
        achievements = payload.get('logros') or []

        if not isinstance(categories, list) or not isinstance(languages, list) or not isinstance(achievements, list):
            raise ValueError('El JSON debe tener listas en las claves categorias, lenguajes y logros.')

        _upsert_categories(categories, update_existing, result)
        _upsert_languages(languages, update_existing, result)
        _upsert_achievements(achievements, update_existing, result)

    except (ProgrammingError, OperationalError) as exc:
        result['detalles'].append(f'Error de base de datos durante la carga: {exc}')
        for key in ('categorias', 'lenguajes', 'logros'):
            result[key]['errores'] += 1
    except ValueError as exc:
        result['detalles'].append(str(exc))
        for key in ('categorias', 'lenguajes', 'logros'):
            result[key]['errores'] += 1

    return result


def execute_initial_load(*, update_existing: bool = False) -> dict[str, Any]:
    return execute_data_load(DEFAULT_INITIAL_DATA, update_existing=update_existing)


def parse_json_payload(raw_text: str) -> dict[str, Any]:
    data = json.loads(raw_text)
    if not isinstance(data, dict):
        raise ValueError('El archivo JSON debe contener un objeto principal.')
    return data
