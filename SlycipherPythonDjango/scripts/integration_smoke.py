import os
import sys
import unittest
import uuid

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
if BASE_DIR not in sys.path:
    sys.path.insert(0, BASE_DIR)

os.environ.setdefault('DJANGO_SETTINGS_MODULE', 'slycipher.settings')

import django  # noqa: E402

django.setup()

from django.test import Client  # noqa: E402

from apps.challenges.models import Desafio  # noqa: E402
from apps.courses.models import Curso, Leccion, Lenguaje, ProgresoUsuario  # noqa: E402
from apps.users.models import Usuario  # noqa: E402


class IntegrationSmokeTests(unittest.TestCase):
    @classmethod
    def setUpClass(cls):
        super().setUpClass()
        cls.admin_user = Usuario.objects.filter(rol__icontains='admin').order_by('user_id').first()
        cls.student_user = Usuario.objects.filter(rol__icontains='est').order_by('user_id').first()
        cls.dev_user = (
            Usuario.objects.filter(email='dev1@example.com').first()
            or Usuario.objects.filter(rol__icontains='des').order_by('user_id').first()
        )

        if not cls.admin_user:
            raise RuntimeError('No se encontro usuario admin para smoke tests.')
        if not cls.student_user:
            raise RuntimeError('No se encontro usuario estudiante para smoke tests.')
        if not cls.dev_user:
            raise RuntimeError('No se encontro usuario desarrollador para smoke tests.')

        cls.dev_course = Curso.objects.filter(creado_por=cls.dev_user).order_by('course_id').first()
        if not cls.dev_course:
            raise RuntimeError('No se encontro curso del desarrollador para smoke tests.')

        cls.dev_language = Lenguaje.objects.order_by('language_id').first()
        if not cls.dev_language:
            raise RuntimeError('No se encontro lenguaje para smoke tests.')

    def setUp(self):
        self.admin_client = Client()
        self.admin_client.force_login(self.admin_user)

        self.student_client = Client()
        self.student_client.force_login(self.student_user)

        self.dev_client = Client()
        self.dev_client.force_login(self.dev_user)

    def test_admin_core_routes(self):
        for path in ['/dashboard/', '/users/', '/panel/courses/', '/statistics/', '/reports/']:
            with self.subTest(path=path):
                response = self.admin_client.get(path)
                self.assertEqual(response.status_code, 200)

    def test_student_core_routes(self):
        for path in ['/student/dashboard/', '/student/courses/', '/student/progreso/', '/student/logros/', '/student/desafios/']:
            with self.subTest(path=path):
                response = self.student_client.get(path)
                self.assertEqual(response.status_code, 200)

    def test_developer_core_routes(self):
        routes = [
            '/developer/dashboard/',
            '/developer/courses/',
            '/developer/challenges/',
            '/developer/solutions/pending/',
            '/developer/statistics/',
            f'/developer/courses/{self.dev_course.course_id}/view/',
            f'/developer/courses/{self.dev_course.course_id}/lessons/',
            f'/developer/courses/{self.dev_course.course_id}/lessons/create/',
            f'/developer/courses/{self.dev_course.course_id}/delete/',
        ]
        for path in routes:
            with self.subTest(path=path):
                response = self.dev_client.get(path)
                self.assertEqual(response.status_code, 200)

    def test_code_example_roundtrip(self):
        unique = uuid.uuid4().hex[:8]
        title = f'Temp Smoke Lesson {unique}'

        create_response = self.dev_client.post(
            f'/developer/courses/{self.dev_course.course_id}/lessons/create/',
            {
                'titulo': title,
                'orden': '995',
                'contenido': 'Contenido de prueba de smoke',
                'codigo_ejemplo': 'print(456)',
                'visible': 'true',
            },
            follow=True,
        )
        self.assertEqual(create_response.status_code, 200)

        lesson = Leccion.objects.filter(course=self.dev_course, titulo=title).order_by('-lesson_id').first()
        self.assertIsNotNone(lesson)
        self.assertEqual((lesson.codigo_ejemplo or '').strip(), 'print(456)')

        try:
            lesson_page = self.student_client.get(f'/student/lessons/{lesson.lesson_id}/')
            self.assertEqual(lesson_page.status_code, 200)
            self.assertIn(b'print(456)', lesson_page.content)
            self.assertIn('Mostrar ejemplo'.encode('utf-8'), lesson_page.content)
        finally:
            lesson.delete()


    def test_challenge_roundtrip(self):
        """Crear → Ver → Editar → Eliminar desafío como desarrollador."""
        unique = uuid.uuid4().hex[:8]
        title = f'Temp Smoke Desafio {unique}'

        create_resp = self.dev_client.post(
            '/developer/challenges/create/',
            {
                'course_id': self.dev_course.course_id,
                'titulo': title,
                'descripcion': 'Descripción smoke test',
                'dificultad': 'facil',
                'solucion': 'print("smoke")',
                'language_id': self.dev_language.pk,
            },
            follow=True,
        )
        self.assertEqual(create_resp.status_code, 200)

        challenge = Desafio.objects.filter(course=self.dev_course, titulo=title).order_by('-challenge_id').first()
        self.assertIsNotNone(challenge, 'Desafío no fue creado en la BD')

        try:
            # Ver
            view_resp = self.dev_client.get(f'/developer/challenges/{challenge.challenge_id}/view/')
            self.assertEqual(view_resp.status_code, 200)

            # Editar
            edited_title = f'{title} EDIT'
            edit_resp = self.dev_client.post(
                f'/developer/challenges/{challenge.challenge_id}/edit/',
                {
                    'course_id': self.dev_course.course_id,
                    'titulo': edited_title,
                    'descripcion': 'Descripción editada',
                    'dificultad': 'medio',
                    'solucion': 'print("edited")',
                    'language_id': self.dev_language.pk,
                },
                follow=True,
            )
            self.assertEqual(edit_resp.status_code, 200)
            challenge.refresh_from_db()
            self.assertEqual(challenge.titulo, edited_title)

            # Eliminar
            del_resp = self.dev_client.post(
                f'/developer/challenges/{challenge.challenge_id}/delete/',
                follow=True,
            )
            self.assertEqual(del_resp.status_code, 200)
            self.assertFalse(Desafio.objects.filter(challenge_id=challenge.challenge_id).exists())
            challenge = None
        finally:
            if challenge is not None and Desafio.objects.filter(challenge_id=challenge.challenge_id).exists():
                challenge.delete()

    def test_student_lesson_complete(self):
        """Marcar una lección como completada como estudiante."""
        lesson = Leccion.objects.filter(course=self.dev_course).order_by('lesson_id').first()
        if lesson is None:
            self.skipTest('No hay lecciones en el curso del desarrollador.')

        ProgresoUsuario.objects.filter(user=self.student_user, lesson=lesson).delete()

        try:
            resp = self.student_client.post(
                f'/student/lessons/{lesson.lesson_id}/complete/',
                follow=True,
            )
            self.assertEqual(resp.status_code, 200)

            progress = ProgresoUsuario.objects.filter(user=self.student_user, lesson=lesson).first()
            self.assertIsNotNone(progress, 'No se creó registro de progreso')
        finally:
            ProgresoUsuario.objects.filter(user=self.student_user, lesson=lesson).delete()

    def test_admin_user_crud(self):
        """Crear → Editar → Eliminar usuario desde el panel de administración."""
        unique = uuid.uuid4().hex[:8]
        email = f'smoke_{unique}@test.com'

        create_resp = self.admin_client.post(
            '/users/new/',
            {
                'email': email,
                'first_name': 'Smoke',
                'last_name': 'Test',
                'role': 'estudiante',
                'password': 'Smoke1234@',
            },
            follow=True,
        )
        self.assertEqual(create_resp.status_code, 200)

        test_user = Usuario.objects.filter(email=email).first()
        self.assertIsNotNone(test_user, 'Usuario no fue creado en la BD')

        try:
            edit_resp = self.admin_client.post(
                f'/users/{test_user.pk}/edit/',
                {
                    'email': email,
                    'first_name': 'SmokeEdited',
                    'last_name': 'TestEdited',
                    'role': 'estudiante',
                },
                follow=True,
            )
            self.assertEqual(edit_resp.status_code, 200)
            test_user.refresh_from_db()
            self.assertEqual(test_user.nombre, 'SmokeEdited')

            del_resp = self.admin_client.post(
                f'/users/{test_user.pk}/delete/',
                follow=True,
            )
            self.assertEqual(del_resp.status_code, 200)
            self.assertFalse(Usuario.objects.filter(pk=test_user.pk).exists())
            test_user = None
        finally:
            if test_user is not None and Usuario.objects.filter(pk=test_user.pk).exists():
                test_user.delete()


if __name__ == '__main__':
    unittest.main(verbosity=2)
