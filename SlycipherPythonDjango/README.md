# SlycipherPythonDjango

Plataforma educativa web para formacion en programacion con tres perfiles de uso, motor de cursos y desafios, seguimiento de progreso, logros, reportes administrativos, estadisticas avanzadas y emision de certificados en PDF con validacion publica.

---

## Tabla de contenido

1. Vision general
2. Stack tecnologico
3. Estructura de carpetas
4. Roles y flujo funcional
5. Componentes del proyecto y flujos
6. Base de datos y modelos
7. Estadisticas avanzadas (admin)
8. Certificados (admin + validacion publica)
9. Carga inicial y carga masiva de datos
10. OAuth e integraciones externas
11. Endpoints por rol
12. APIs internas
13. APIs externas
14. Configuracion y arranque local
15. Pruebas y utilidades
16. Notas tecnicas
17. Convencion CSS (evitar duplicados)

---

## 1. Vision general

SlycipherPythonDjango permite:

- Administrar usuarios con roles admin, desarrollador y estudiante.
- Crear cursos, lecciones y desafios de programacion.
- Ejecutar y evaluar codigo de desafios.
- Llevar progreso academico por estudiante.
- Otorgar logros en base al avance.
- Analizar datos de uso con panel estadistico.
- Emitir certificados PDF con codigo unico verificable.
- Exportar reportes administrativos en PDF y Excel.

---

## 2. Stack tecnologico

| Categoria | Tecnologia | Uso |
|---|---|---|
| Backend | Django 6.0.3 | MVC, ORM, rutas, templates |
| Lenguaje | Python 3.13 | Logica de negocio |
| Base de datos | MySQL | Persistencia |
| Conectores | mysql-connector-python / PyMySQL | Conexion BD |
| Auth | bcrypt | Verificacion de contraseñas (incluye hashes legacy) |
| PDF | reportlab | Reportes y certificados |
| Excel | openpyxl | Exportacion XLSX |
| Frontend | HTML + CSS + JS | Interfaz por panel |
| OAuth | Integracion HTTP manual | Google, GitHub, Microsoft |

---

## 3. Estructura de carpetas

```text
SlycipherPythonDjango/
|-- manage.py
|-- requirements.txt
|-- slycipher/                    # Configuracion Django (settings, urls, asgi, wsgi)
|-- apps/
|   |-- users/                    # Auth, perfiles, admin, certificaciones, estadisticas
|   |-- courses/                  # Cursos, lecciones, progreso, inscripciones
|   |-- challenges/               # Desafios, intentos, evaluacion
|   `-- achievements/             # Logros e insignias
|-- services/
|   |-- auth_service.py           # Verificacion password/hash legacy
|   |-- bootstrap_data_service.py # Carga inicial y masiva
|   |-- code_executor.py          # Ejecucion de codigo Python/Java
|   |-- report_service.py         # Exportes PDF/XLSX + certificados
|   `-- statistics_service.py     # Consultas analiticas del dashboard
|-- templates/                    # Vistas por rol y modulo
|-- static/                       # CSS, JS, imagenes
`-- scripts/                      # Utilidades operativas y pruebas
```

---

## 4. Roles y flujo funcional

### 4.1 Roles

- admin: gestiona usuarios, cursos, reportes, estadisticas y certificados.
- desarrollador: crea y mantiene cursos, lecciones y desafios; revisa soluciones pendientes.
- estudiante: se inscribe en cursos, completa lecciones, intenta desafios y desbloquea logros.

### 4.2 Flujo academico resumido

1. Desarrollador crea curso y contenido.
2. Admin aprueba o rechaza el curso.
3. Estudiante se inscribe y avanza por lecciones/desafios.
4. Se registra progreso y se actualizan metricas.
5. Admin consulta estadisticas y emite certificados si aplica.

### 4.3 Flujo de un curso (detalle)

1. El desarrollador crea el curso con nivel, lenguaje y categoria.
2. El curso queda pendiente de revision.
3. El admin aprueba o rechaza el curso.
4. El estudiante visualiza el curso aprobado, se inscribe y empieza lecciones.
5. El progreso se guarda por leccion/desafio y luego impacta estadisticas y certificaciones.

### 4.4 Flujo de un desafio (detalle)

1. El desarrollador crea el desafio y define dificultad (facil/medio/dificil).
2. El estudiante abre el editor y ejecuta pruebas sin enviar.
3. El sistema compara salida esperada vs salida del estudiante.
4. El envio queda correcto, incorrecto o pendiente de revision.
5. Si aplica, el desarrollador revisa manualmente pendientes.

---

## 5. Componentes del proyecto y flujos

Nota: esta seccion describe componentes reales del repositorio (apps, services, templates y static). No implica que el proyecto use arquitectura por modulos independientes o microservicios.

### 5.1 Usuarios y autenticacion

- Login, registro, logout y perfil.
- OAuth social con Google, GitHub y Microsoft.
- Backend de autenticacion compatible con hashes bcrypt legacy.

Flujo basico de autenticacion:

1. Usuario accede a /login/ o /register/.
2. Se valida credencial local o respuesta OAuth.
3. Si credenciales validas, se inicia sesion y se redirige segun rol.

### 5.2 Cursos y lecciones

- Catalogo de cursos por categoria, nivel y lenguaje.
- Lecciones con avance individual.
- Inscripciones por estudiante.

### 5.3 Desafios de programacion

- Creacion y mantenimiento de desafios por desarrolladores.
- Intento de solucion por estudiante.
- Comparacion de resultados y estado correcto/incorrecto.

Ejemplo de flujo de ejecucion:

- POST /student/desafios/ejecutar/ para probar codigo sin envio final.
- POST /student/desafios/<pk>/enviar/ para registrar intento oficial.

### 5.4 Logros

- Definicion de logros (tipo, puntos, icono, estado).
- Relacion logro-estudiante segun cumplimiento.

### 5.5 Reportes

- Exportacion PDF/XLSX para administracion.
- Soporte de filtros por rango y contexto funcional.

Ejemplo de salida:

- Reporte PDF con metricas filtradas por fechas.
- Reporte Excel con tablas de usuarios/cursos/desafios.

---

## 6. Base de datos y modelos

El proyecto trabaja con MySQL. Parte del esquema viene de tablas legacy, por lo que algunos modelos son no gestionados por migraciones de Django.

Tablas principales:

- usuarios
- profiles
- categorias
- lenguajes
- cursos
- lecciones
- progreso_usuarios
- inscripciones
- desafios
- desafio_usuarios
- logros
- logros_usuarios
- certificados_emitidos

Relaciones funcionales relevantes:

- Usuario tiene progreso, envios de desafios, logros y certificados.
- Curso pertenece a categoria y lenguaje, y contiene lecciones/desafios.
- DesafioUsuario conecta estudiante con desafio e intento.
- CertificadoEmitido conecta estudiante, emisor y opcionalmente curso.

---

## 7. Estadisticas avanzadas (admin)

El modulo de estadisticas se fortalecio para soporte de analisis academico y toma de decisiones.

### 7.1 Ruta y archivos clave

- Vista: /statistics/
- Exporte PDF: /statistics/pdf/
- Servicio: services/statistics_service.py
- Vista Django: apps/users/views.py (admin_statistics)
- Template: templates/statistics.html
- JS de graficos: static/js/admin_statistics.js
- Estilos: static/css/admin_statistics.css

### 7.2 Que analiza

- KPIs principales: usuarios, activacion, cursos, desafios, tasa general de exito.
- Exito por dificultad: facil, medio, dificil.
- Top usuarios por actividad.
- Top cursos por movimiento.
- Distribucion por nivel de curso.
- Logros mas desbloqueados.
- Tendencia historica y tendencia de ultimos 30 dias.
- Segmentacion de usuarios en 4 grupos de actividad.
- Analisis tabular por categoria y por lenguaje.

Secciones visibles en dashboard (resumen):

1. KPIs principales.
2. Cobertura y rendimiento.
3. Top usuarios y top cursos.
4. Logros y segmentacion.
5. Tablas analiticas por categoria/lenguaje.
6. Tendencias historicas y de ultimos 30 dias.

### 7.3 Consultas y rendimiento

El servicio usa consultas ORM agregadas (annotate + Count + filtros) para evitar el patron N+1.

Buenas practicas aplicadas:

- Agregacion en base de datos en vez de loops Python por registro.
- Contexto unificado para una sola renderizacion de dashboard.
- Manejo de escenarios sin datos para no romper graficos.

Ejemplos de consultas ORM usadas en estadisticas:

```python
DesafioUsuario.objects.values('challenge__dificultad').annotate(
	total=Count('submission_id'),
	exitosos=Count('submission_id', filter=Q(estado='correcto')),
)
```

```python
Categoria.objects.annotate(
	total_cursos=Count('cursos'),
	total_desafios=Count('cursos__desafios'),
	total_intentos=Count('cursos__desafios__desafiousuario'),
)
```

```python
Usuario.objects.annotate(
	desafios_completados=Count('desafio_submissions', filter=Q(desafio_submissions__estado='correcto')),
	logros_desbloqueados=Count('logros_usuario'),
).order_by('-desafios_completados', '-logros_desbloqueados')[:10]
```

### 7.4 Guia de lectura rapida del dashboard

- Tasa de exito menor a 50%: revisar dificultad o claridad del contenido.
- Tasa 60-80%: equilibrio saludable en la mayoria de contextos.
- Segmento inactivo alto: reforzar onboarding, seguimiento y gamificacion.
- Categoria/lenguaje con baja actividad: evaluar contenido, visibilidad y oferta.

Casos practicos:

1. Si hay pocos usuarios activos: revisar segmentacion + tendencia 30 dias + tasa de exito general.
2. Si un desafio no progresa: revisar tasa del desafio y ajustar enunciado/hints/dificultad.
3. Si una categoria cae: comparar contra otras categorias y reforzar contenido inicial.

---

## 8. Certificados (admin + validacion publica)

El proyecto incluye emision de certificados PDF con registro historico y validacion por codigo unico.

### 8.1 Modelo

- Modelo: CertificadoEmitido
- Tabla: certificados_emitidos
- Campos clave: student, issued_by, course, certificate_type, code, total_courses, notes, issued_at

Tipos de certificado:

- general: reconocimiento global.
- curso: reconocimiento por curso individual.

### 8.2 Reglas de emision

- Certificado general: estudiante con minimo 2 cursos completados.
- Certificado por curso: estudiante debe completar el curso objetivo.

### 8.3 Rutas

- Panel de certificaciones: /certifications/
- PDF general: /dashboard/certificates/<user_id>/pdf/
- PDF por curso: /dashboard/certificates/<user_id>/courses/<course_id>/pdf/
- Validacion publica: /certificates/verify/<code>/

Flujo de certificacion general:

1. Admin abre /certifications/.
2. El sistema identifica estudiantes elegibles.
3. Admin genera PDF general.
4. Se registra CertificadoEmitido con codigo unico.
5. Cualquier persona valida el codigo en /certificates/verify/<code>/.

Flujo de certificacion por curso:

1. Admin selecciona estudiante y curso.
2. Se verifica completitud real del curso.
3. Si cumple, se emite PDF y se guarda historico.
4. El certificado queda validable por URL publica.

### 8.4 Contenido del PDF

- Nombre del estudiante.
- Tipo de certificado.
- Curso(s) acreditado(s).
- Totales de lecciones y desafios completados.
- Fecha y usuario emisor.
- Codigo unico y URL de validacion.

Motor de generacion:

- services/report_service.py -> export_student_certificate_pdf(...)

Pantalla de validacion:

- templates/core/certificate_verify.html

---

## 9. Carga inicial y carga masiva de datos

La administracion incluye herramientas para preparar datos sin depender de scripts manuales cada vez.

### 9.1 Carga inicial

- Inserta catalogos base (categorias, lenguajes, logros).
- Enfoque idempotente para no duplicar registros.
- Opcion de actualizar registros existentes.

### 9.2 Carga masiva JSON

- Subida de archivo JSON desde dashboard.
- Validacion en frontend antes de enviar.
- Proceso backend con resumen de crear/actualizar/omitir/error.

Flujo de carga masiva:

1. Admin descarga plantilla JSON desde dashboard.
2. Completa categorias/lenguajes/logros.
3. Sube archivo por formulario multipart.
4. Backend parsea y aplica upsert por entidad.
5. Dashboard muestra resumen final por tipo de operacion.

Estructura esperada:

```json
{
	"categorias": [
		{"nombre": "Backend", "descripcion": "Servicios y APIs"}
	],
	"lenguajes": [
		{"nombre": "Python", "descripcion": "Programacion general"}
	],
	"logros": [
		{
			"nombre": "Primer logro",
			"descripcion": "Completa tu primera leccion",
			"icono": "fa-seedling",
			"puntos_requeridos": 1,
			"tipo": "lecciones",
			"activo": true
		}
	]
}
```

Archivos relacionados:

- services/bootstrap_data_service.py
- apps/users/views.py
- templates/admin/dashboard.html
- apps/users/urls.py (descarga de plantilla)

---

## 10. OAuth e integraciones externas

Integraciones disponibles:

- Google OAuth 2.0
- GitHub OAuth 2.0
- Microsoft OAuth 2.0

Caracteristicas:

- Flujo manual por llamadas HTTP.
- Control de estado para seguridad en callback.
- Uso de variables .env para credenciales y redirect URIs.

Flujo OAuth resumido:

1. Usuario inicia login social.
2. Se redirige al proveedor (Google/GitHub/Microsoft).
3. Proveedor retorna codigo a callback.
4. El sistema intercambia codigo por token y consulta perfil.
5. Se inicia sesion o se redirige segun reglas de login/registro.

---

## 11. Endpoints por rol

Esta seccion resume los endpoints funcionales mas importantes de la plataforma.

### 11.1 Autenticacion y cuenta

| Metodo | URL | Descripcion |
|---|---|---|
| GET / POST | /login/ | Iniciar sesion con correo y contraseña |
| GET / POST | /register/ | Registrar cuenta nueva |
| GET | /logout/ | Cerrar sesion |
| GET / POST | /profile/ | Ver/editar perfil propio |
| GET | /login/google/ | Inicio OAuth Google |
| GET | /login/google/callback/ | Callback OAuth Google |
| GET | /accounts/google/login/ | Alias inicio OAuth Google |
| GET | /accounts/google/login/callback/ | Alias callback OAuth Google |
| GET | /login/github/ | Inicio OAuth GitHub |
| GET | /login/github/callback/ | Callback OAuth GitHub |
| GET | /accounts/github/login/ | Alias inicio OAuth GitHub |
| GET | /accounts/github/login/callback/ | Alias callback OAuth GitHub |
| GET | /login/microsoft/ | Inicio OAuth Microsoft |
| GET | /login/microsoft/callback/ | Callback OAuth Microsoft |
| GET | /accounts/microsoft/login/ | Alias inicio OAuth Microsoft |
| GET | /accounts/microsoft/login/callback/ | Alias callback OAuth Microsoft |

### 11.2 Endpoints de administrador

| Metodo | URL | Descripcion |
|---|---|---|
| GET | /dashboard/ | Dashboard principal admin |
| POST | /dashboard/ | Ejecuta acciones de carga (inicial o masiva) |
| GET | /dashboard/seed-template/ | Descarga plantilla JSON de carga |
| GET | /statistics/ | Panel de estadisticas avanzadas |
| GET | /statistics/pdf/ | Exporta estadisticas a PDF |
| GET | /reports/ | Pantalla de reportes |
| GET / POST | /reports/result/ | Resultado del reporte y descarga |
| GET | /users/ | Listado de usuarios |
| GET / POST | /users/new/ | Crear usuario |
| GET / POST | /users/<pk>/edit/ | Editar usuario |
| GET / POST | /users/<pk>/delete/ | Eliminar usuario |
| POST | /users/<pk>/toggle_active/ | Activar/desactivar usuario |
| GET | /certifications/ | Panel de certificaciones |
| GET | /dashboard/certificates/<user_id>/pdf/ | Emitir certificado general PDF |
| GET | /dashboard/certificates/<user_id>/courses/<course_id>/pdf/ | Emitir certificado por curso PDF |
| GET | /certificates/verify/<code>/ | Validar certificado por codigo |
| GET | /panel/courses/ | Ver cursos para gestion admin |
| GET | /panel/courses/<id>/view/ | Ver detalle del curso |
| GET / POST | /panel/courses/<id>/edit/ | Editar curso |
| POST | /panel/courses/<id>/approve/ | Aprobar curso |
| POST | /panel/courses/<id>/reject/ | Rechazar curso |
| POST | /panel/courses/<id>/delete/ | Eliminar curso |
| POST | /panel/courses/<pk>/toggle_visibility/ | Activar/desactivar visibilidad |

### 11.3 Endpoints de desarrollador

| Metodo | URL | Descripcion |
|---|---|---|
| GET | /developer/dashboard/ | Dashboard del desarrollador |
| GET | /developer/courses/ | Listado de cursos propios |
| GET / POST | /developer/courses/create/ | Crear curso |
| GET | /developer/courses/<id>/view/ | Ver curso |
| GET / POST | /developer/courses/<id>/edit/ | Editar curso |
| POST | /developer/courses/<id>/delete/ | Eliminar curso |
| GET | /developer/courses/<id>/lessons/ | Listar lecciones del curso |
| GET / POST | /developer/courses/<id>/lessons/create/ | Crear leccion |
| GET / POST | /developer/lessons/<id>/edit/ | Editar leccion |
| POST | /developer/lessons/<id>/delete/ | Eliminar leccion |
| POST | /developer/courses/<id>/lessons/execute/ | Ejecutar codigo de ejemplo |
| GET | /developer/challenges/ | Listado de desafios propios |
| GET / POST | /developer/challenges/create/ | Crear desafio |
| GET | /developer/challenges/<id>/view/ | Ver desafio |
| GET / POST | /developer/challenges/<id>/edit/ | Editar desafio |
| POST | /developer/challenges/<id>/delete/ | Eliminar desafio |
| GET | /developer/solutions/pending/ | Ver soluciones pendientes |
| GET | /developer/solutions/<id>/review/ | Revisar envio |
| POST | /developer/solutions/<id>/evaluate/ | Calificar envio |
| GET | /developer/statistics/ | Estadisticas del desarrollador |

### 11.4 Endpoints de estudiante

| Metodo | URL | Descripcion |
|---|---|---|
| GET | /student/dashboard/ | Dashboard del estudiante |
| GET | /student/courses/ | Catalogo de cursos |
| GET | /student/courses/<id>/ | Detalle del curso |
| POST | /student/courses/<id>/enroll/ | Inscribirse en curso |
| GET | /student/lessons/<id>/ | Ver leccion |
| POST | /student/lessons/<id>/complete/ | Marcar leccion completada |
| GET | /student/progreso/ | Ver progreso general |
| GET | /student/desafios/ | Listado de desafios |
| GET | /student/desafios/<pk>/ | Ver detalle de desafio |
| GET | /student/desafios/<pk>/intentar/ | Abrir editor de intento |
| POST | /student/desafios/ejecutar/ | Ejecutar codigo sin envio final |
| POST | /student/desafios/<pk>/enviar/ | Enviar solucion oficial |
| GET | /student/logros/ | Ver logros del estudiante |

### 11.5 Referencia rapida (estadisticas y certificados)

| Area | Metodo | Endpoint | Uso rapido |
|---|---|---|---|
| Estadisticas | GET | /statistics/ | Ver panel analitico admin |
| Estadisticas | GET | /statistics/pdf/ | Exportar estadisticas a PDF |
| Certificados | GET | /certifications/ | Ver elegibles e historico |
| Certificados | GET | /dashboard/certificates/<user_id>/pdf/ | Emitir certificado general |
| Certificados | GET | /dashboard/certificates/<user_id>/courses/<course_id>/pdf/ | Emitir certificado por curso |
| Certificados | GET | /certificates/verify/<code>/ | Validar autenticidad publica |

Flujo rapido para demo:

1. Entrar como admin y abrir /statistics/.
2. Mostrar KPIs y exportar PDF en /statistics/pdf/.
3. Ir a /certifications/ y emitir certificado general o por curso.
4. Abrir /certificates/verify/<code>/ para validar en publico.

---

## 12. APIs internas

APIs internas son los endpoints que usa la plataforma para sus flujos web y AJAX.

### 12.1 Autenticacion y cuenta

| Metodo | URL | Descripcion |
|---|---|---|
| GET / POST | /login/ | Inicio de sesion local |
| GET / POST | /register/ | Registro de usuario |
| GET | /logout/ | Cierre de sesion |
| GET / POST | /profile/ | Gestion de perfil |

### 12.2 Administracion

| Metodo | URL | Descripcion |
|---|---|---|
| GET | /dashboard/ | Panel principal admin |
| GET | /statistics/ | Estadisticas avanzadas |
| GET | /statistics/pdf/ | Exporte PDF de estadisticas |
| GET | /reports/ | Pantalla de reportes |
| GET / POST | /reports/result/ | Resultado y exportes de reportes |
| GET | /users/ | Gestion de usuarios |
| GET | /certifications/ | Gestion de certificaciones |

### 12.3 Desarrollador

| Metodo | URL | Descripcion |
|---|---|---|
| GET | /developer/dashboard/ | Panel del desarrollador |
| GET | /developer/courses/ | Cursos propios |
| GET | /developer/challenges/ | Desafios propios |
| GET | /developer/solutions/pending/ | Soluciones pendientes |
| POST | /developer/courses/<id>/lessons/execute/ | Ejecucion de codigo en lecciones |

### 12.4 Estudiante

| Metodo | URL | Descripcion |
|---|---|---|
| GET | /student/dashboard/ | Panel del estudiante |
| GET | /student/courses/ | Catalogo de cursos |
| POST | /student/courses/<id>/enroll/ | Inscripcion a curso |
| POST | /student/desafios/ejecutar/ | Ejecucion previa de codigo |
| POST | /student/desafios/<pk>/enviar/ | Envio oficial de desafio |
| GET | /student/logros/ | Visualizacion de logros |

---

## 13. APIs externas

Estas son las APIs de terceros que consume la plataforma para autenticacion social.

### 13.1 Google OAuth 2.0

| Paso | Tipo | URL | Descripcion |
|---|---|---|---|
| Autorizacion | Redirect | https://accounts.google.com/o/oauth2/v2/auth | Consentimiento del usuario |
| Token | POST | https://oauth2.googleapis.com/token | Intercambio codigo por access token |
| Perfil | GET | https://openidconnect.googleapis.com/v1/userinfo | Datos del usuario autenticado |

Scopes: openid email profile

### 13.2 GitHub OAuth 2.0

| Paso | Tipo | URL | Descripcion |
|---|---|---|---|
| Autorizacion | Redirect | https://github.com/login/oauth/authorize | Permisos de acceso |
| Token | POST | https://github.com/login/oauth/access_token | Intercambio codigo por access token |
| Perfil | GET | https://api.github.com/user | Datos basicos del usuario |
| Emails | GET | https://api.github.com/user/emails | Correo primario verificado |

Scopes: read:user user:email

### 13.3 Microsoft OAuth 2.0

| Paso | Tipo | Endpoint | Descripcion |
|---|---|---|---|
| Autorizacion | Redirect | Microsoft Identity Platform (authorize) | Inicio del consentimiento |
| Token | POST | Microsoft Identity Platform (token) | Intercambio codigo por token |
| Perfil | GET | Microsoft Graph (/me) | Datos del usuario autenticado |

Nota: los IDs, secretos y URIs de callback se configuran por variables de entorno en el archivo .env.

---

## 14. Configuracion y arranque local

### 14.1 Instalar dependencias

```bash
pip install -r requirements.txt
```

### 14.2 Definir .env

```env
MYSQL_HOST=localhost
MYSQL_PORT=3307
MYSQL_DATABASE=SlycipherBDPython
MYSQL_USER=root
MYSQL_PASSWORD=tu_contrasena

GOOGLE_CLIENT_ID=...
GOOGLE_CLIENT_SECRET=...
GITHUB_CLIENT_ID=...
GITHUB_CLIENT_SECRET=...
MICROSOFT_CLIENT_ID=...
MICROSOFT_CLIENT_SECRET=...
GOOGLE_REDIRECT_URI=http://localhost:8000/login/google/callback/
GITHUB_REDIRECT_URI=http://localhost:8000/login/github/callback/
MICROSOFT_REDIRECT_URI=http://localhost:8000/login/microsoft/callback/
OAUTH_BASE_URL=http://localhost:8000
```

### 14.3 Migrar

```bash
py manage.py migrate
```

### 14.4 Ejecutar

```bash
py manage.py runserver
```

Aplicacion: http://localhost:8000

---

## 15. Pruebas y utilidades

### 15.1 Prueba de humo de integracion

```bash
python scripts/integration_smoke.py
```

### 15.2 Scripts administrativos utiles

- scripts/create_admin.py
- scripts/migrate_usuarios.py
- scripts/list_users.py
- scripts/inspect_legacy_users.py
- scripts/show_legacy_users.py
- scripts/sync_user_names.py
- scripts/hash_test.py

---

## 16. Notas tecnicas

- La plataforma mezcla componentes nuevos con esquema legacy de BD.
- La autenticacion contempla compatibilidad con hashes bcrypt heredados.
- Los reportes se generan en PDF y Excel.
- El modulo de estadisticas prioriza agregacion en BD para eficiencia.
- El modulo de certificados usa codigo unico para trazabilidad y validacion publica.

---

## 17. Convencion CSS (evitar duplicados)

Para evitar inconsistencias visuales, el proyecto define una unica fuente oficial para estilos de autenticacion:

- Fuente canónica de login: static/css/login.css
- Fuente canónica de registro: static/css/register.css

Compatibilidad mantenida:

- static/login.css importa static/css/login.css
- static/register.css importa static/css/register.css

Regla de mantenimiento:

- Cualquier cambio de color, tipografia o layout de login/registro se hace solo en static/css/login.css y static/css/register.css.
- No se deben volver a duplicar bloques completos de CSS en static/login.css o static/register.css.

Beneficio:

- Se conserva el estilo actual y se evita que dos versiones del mismo CSS se desincronicen.

---

Este README consolida y amplia la documentacion funcional y tecnica del proyecto, incluyendo estadisticas avanzadas y certificados.
