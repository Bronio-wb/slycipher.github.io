# SLYCIPHER - Plataforma de Aprendizaje de Programación

## 🚀 Descripción del Proyecto

**SLYCIPHER** es una plataforma educativa interactiva desarrollada en Laravel 10 para el aprendizaje de lenguajes de programación. La plataforma ofrece un sistema completo de gestión de cursos, lecciones, desafíos de código y un sistema de logros para motivar a los estudiantes.

### ✨ Características Principales

- **Sistema de Roles**: Administradores, Desarrolladores y Estudiantes con permisos específicos
- **Gestión de Cursos**: Creación y administración de cursos por categorías y lenguajes
- **Lecciones Interactivas**: Contenido estructurado con seguimiento de progreso
- **Desafíos de Código**: Ejercicios prácticos con soluciones y sistema de puntuación
- **Sistema de Logros**: Reconocimientos por progreso, desafíos completados y tiempo de estudio
- **Reportes PDF**: Generación automática de reportes administrativos
- **Interfaz Responsiva**: Diseño moderno con Bootstrap 5 y FontAwesome

---

## 🛠️ Tecnologías Utilizadas

### Backend
- **Laravel 10.x** - Framework PHP principal
- **MySQL** - Base de datos relacional
- **Eloquent ORM** - Mapeo objeto-relacional
- **Laravel Sanctum** - Autenticación API
- **DomPDF** - Generación de reportes PDF

### Frontend
- **Blade Templates** - Motor de plantillas de Laravel
- **Bootstrap 5** - Framework CSS responsivo
- **FontAwesome 6** - Iconografía
- **JavaScript/jQuery** - Interactividad del cliente

### Herramientas de Desarrollo
- **Composer** - Gestor de dependencias PHP
- **NPM** - Gestor de paquetes Node.js
- **Vite** - Bundler de assets frontend
- **PHPUnit** - Testing framework

---

## 📋 Requisitos del Sistema

### Requisitos Mínimos
- **PHP**: 8.1 o superior
- **Composer**: 2.0 o superior
- **Node.js**: 16.0 o superior
- **MySQL**: 8.0 o superior
- **Extensiones PHP**: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath

### Recomendados
- **Memoria RAM**: 2GB mínimo
- **Espacio en Disco**: 1GB libre
- **Servidor Web**: Apache 2.4+ o Nginx 1.18+

---

## 🚀 Instalación

### 1. Clonar el Repositorio
```bash
git clone https://github.com/tu-usuario/slycipher.git
cd slycipher
```

### 2. Instalar Dependencias PHP
```bash
composer install
```

### 3. Instalar Dependencias Node.js
```bash
npm install
```

### 4. Configurar Variables de Entorno
```bash
# Copiar archivo de configuración
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

### 5. Configurar Base de Datos
Editar el archivo `.env` con los datos de tu base de datos:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=plataforma_aprendizaje3
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 6. Ejecutar Migraciones y Seeders
```bash
# Crear tablas
php artisan migrate

# Cargar datos de prueba (opcional)
php artisan db:seed
```

### 7. Compilar Assets Frontend
```bash
# Desarrollo
npm run dev

# Producción
npm run build
```

### 8. Iniciar el Servidor
```bash
php artisan serve
```

La aplicación estará disponible en: `http://localhost:8000`

---

## 👥 Usuarios de Prueba

### Administrador
- **Email**: `admin@slycipher.com`
- **Contraseña**: `admin123`
- **Permisos**: Acceso completo al sistema

### Desarrollador
- **Email**: `developer@slycipher.com`
- **Contraseña**: `dev123`
- **Permisos**: Gestión de contenido y reportes

### Estudiante
- **Email**: `estudiante@slycipher.com`
- **Contraseña**: `est123`
- **Permisos**: Acceso a cursos y lecciones

---

## 📁 Estructura del Proyecto

```
slycipher/
├── app/
│   ├── Http/Controllers/       # Controladores principales
│   ├── Models/                # Modelos Eloquent
│   ├── Providers/             # Proveedores de servicios
│   └── ...
├── config/                    # Archivos de configuración
├── database/
│   ├── migrations/            # Migraciones de BD
│   ├── seeders/              # Datos de prueba
│   └── factories/            # Factories para testing
├── public/                   # Assets públicos
├── resources/
│   ├── views/                # Plantillas Blade
│   ├── css/                  # Estilos CSS
│   └── js/                   # JavaScript
├── routes/                   # Definición de rutas
├── storage/                  # Archivos de almacenamiento
├── tests/                    # Tests automatizados
└── ...
```

---

## 🗃️ Base de Datos

### Tablas Principales

1. **users** - Usuarios del sistema
2. **categorias** - Categorías de cursos
3. **lenguajes** - Lenguajes de programación
4. **cursos** - Cursos disponibles
5. **lecciones** - Lecciones de cada curso
6. **desafios** - Desafíos de programación
7. **logros** - Sistema de reconocimientos
8. **progreso_usuarios** - Seguimiento de avance
9. **usuario_logros** - Logros obtenidos por usuarios

### Relaciones Principales
- Usuario → Progreso (1:N)
- Curso → Lecciones (1:N)
- Lenguaje → Desafíos (1:N)
- Usuario → Logros (N:M)

---

## 🔧 Funcionalidades

### Sistema de Autenticación
- Registro de usuarios
- Login/Logout
- Recuperación de contraseña
- Middleware de roles

### Gestión de Contenido
- **Categorías**: Organización temática de cursos
- **Lenguajes**: JavaScript, Python, Java, C++, etc.
- **Cursos**: Contenido estructurado por niveles
- **Lecciones**: Módulos de aprendizaje con seguimiento
- **Desafíos**: Ejercicios prácticos con validación

### Sistema de Progreso
- Seguimiento de lecciones completadas
- Puntuación por actividades realizadas
- Estadísticas de avance personal
- Historial de actividades

### Sistema de Logros
- **Logros de Progreso**: Por completar lecciones/cursos
- **Logros de Desafío**: Por resolver ejercicios
- **Logros de Tiempo**: Por racha de estudio
- **Logros Especiales**: Reconocimientos únicos

### Reportes Administrativos
- **Reporte de Usuarios**: Estadísticas y listados
- **Reporte de Cursos**: Análisis de contenido
- **Reporte de Progreso**: Seguimiento de estudiantes
- **Reporte de Logros**: Sistema de reconocimientos

---

## 🧪 Testing

### Ejecutar Tests
```bash
# Todos los tests
php artisan test

# Tests específicos
php artisan test --filter=AuthTest
php artisan test --filter=AccessControlTest
```

### Tests Implementados
- **AuthTest**: Pruebas de autenticación
- **AccessControlTest**: Verificación de permisos
- **Feature Tests**: Funcionalidades principales
- **Unit Tests**: Componentes individuales

---

## 📊 Comandos Artisan Útiles

```bash
# Limpiar caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Ver rutas
php artisan route:list

# Generar documentación de API
php artisan l5-swagger:generate

# Optimización para producción
php artisan optimize
```

---

## 🎨 Personalización

### Modificar Estilos
Los estilos están en `resources/css/app.css` y se compilan con Vite.

### Agregar Nuevos Lenguajes
1. Insertar en tabla `lenguajes`
2. Agregar iconos en `public/assets/images/lenguajes/`
3. Actualizar seeders si es necesario

### Crear Nuevos Tipos de Logros
1. Modificar enum en migración `logros`
2. Actualizar formularios en vistas
3. Implementar lógica de otorgamiento

---

## 🚨 Solución de Problemas

### Error: "Class not found"
```bash
composer dump-autoload
```

### Error: "Permission denied"
```bash
chmod -R 775 storage bootstrap/cache
```

### Error de Base de Datos
1. Verificar credenciales en `.env`
2. Comprobar que la BD exista
3. Ejecutar `php artisan migrate:fresh --seed`

### Assets no cargan
```bash
npm run dev
# o para producción
npm run build
```

---

## 📈 Roadmap Futuro

### Versión 2.0 (Planeado)
- [ ] Sistema de mensajería entre usuarios
- [ ] Foros de discusión por curso
- [ ] Editor de código integrado
- [ ] Compilación y ejecución en línea
- [ ] Sistema de certificaciones
- [ ] Integración con GitHub
- [ ] Modo oscuro/claro
- [ ] API REST completa
- [ ] Aplicación móvil

### Mejoras Técnicas
- [ ] Implementar Redis para caché
- [ ] Queue jobs para tareas pesadas
- [ ] Implementar WebSockets
- [ ] Monitoreo con Telescope
- [ ] Optimización de consultas N+1
- [ ] Implementar Rate Limiting

---

## 🤝 Contribución

### Cómo Contribuir
1. Fork del repositorio
2. Crear rama feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit cambios (`git commit -am 'Agregar nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Crear Pull Request

### Estándares de Código
- Seguir PSR-12 para PHP
- Usar ESLint para JavaScript
- Comentarios en español
- Tests para nuevas funcionalidades

---

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver archivo `LICENSE` para más detalles.

---

## 📞 Soporte

### Contacto
- **Email**: soporte@slycipher.com
- **GitHub Issues**: [Reportar problema](https://github.com/tu-usuario/slycipher/issues)
- **Documentación**: [Wiki del proyecto](https://github.com/tu-usuario/slycipher/wiki)

### FAQ

**¿Puedo usar SLYCIPHER comercialmente?**
Sí, bajo los términos de la licencia MIT.

**¿Cómo agrego más lenguajes de programación?**
Consulta la sección de personalización en esta documentación.

**¿El sistema es multiidioma?**
Actualmente está en español, pero Laravel soporta localización.

---

## 🙏 Agradecimientos

- **Laravel Team** - Por el excelente framework
- **Bootstrap Team** - Por el framework CSS
- **FontAwesome** - Por los iconos
- **Comunidad PHP** - Por las librerías utilizadas

---

## 📋 Changelog

### v1.0.0 (2024-01-15)
- ✅ Sistema completo de autenticación y roles
- ✅ CRUD completo para todas las entidades
- ✅ Sistema de progreso y logros
- ✅ Generación de reportes PDF
- ✅ Interfaz responsiva completa
- ✅ Tests básicos implementados
- ✅ Documentación completa

---

**Desarrollado con ❤️ para la comunidad educativa**

*SLYCIPHER - Decodifica tu futuro en programación* 🚀

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
