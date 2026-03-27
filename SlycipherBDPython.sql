CREATE DATABASE IF NOT EXISTS SlycipherBDPython;
USE SlycipherBDPython;



-- ========================================
-- TABLA USUARIOS
-- ========================================
CREATE TABLE usuarios (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    fecha_nacimiento DATE NULL,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'estudiante', 'desarrollador') NOT NULL,
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,
    ultimo_login DATETIME NULL,
    estado TINYINT(1) DEFAULT 1,
    activo TINYINT(1) DEFAULT 1,
    racha INT DEFAULT 0
);

INSERT INTO usuarios (user_id, username, nombre, apellido, email, fecha_nacimiento, password_hash, rol, creado_en, ultimo_login, estado, activo, racha) VALUES
(1, 'Juan Lopez', 'Juan', 'Lopez', 'admin1@example.com', NULL, '$2y$12$9LZ8jWn1ZXTOdgLYJIAyfOx0gQpnIG4suVBXDSfXP0sEH1W7/C0LW', 'admin', '2025-09-24 21:56:17', '2025-10-31 04:28:20', 1, 1, 3),
(2, 'Camilo Gutierrez', 'Camilo', 'Gutierrez', 'est1@example.com', NULL, '$2y$12$6buhGIXDfY4M47E1fxmKFe8nMMNqQj3I6SSXqYZsEdKrLAKnEVnp6', 'estudiante', '2025-09-24 21:56:17', '2025-10-31 01:53:51', 1, 1, 7),
(3, 'Sandra Gomez', 'Sandra', 'Gomez', 'est2@example.com', NULL, 'Est2sly123*', 'estudiante', '2025-09-24 21:56:17', NULL, 1, 1, 5),
(4, 'Alejandro Torres', 'Alejandro', 'Torres', 'dev1@example.com', NULL, '$2y$12$mKbkaRMTjO84VAipZ15bc.AzkmZ8khJiw7vq4Cb8E9vqymlDa1cgu', 'desarrollador', '2025-09-24 21:56:17', '2025-10-02 16:55:23', 1, 0, 2),
(8, 'norma@gmail.com', 'Norma', 'Solano', 'norma@gmail.com', NULL, '$2y$12$jrhepgkh6pjqmyq8k2P2se2MbLLM4BNicx.rMbzotAiJ1wFyp7vQ6', 'estudiante', '2025-10-02 13:29:48', NULL, 1, 1, 0),
(10, 'dianadelgado', 'Lizeth', 'Delgado', 'dianadelgado@gmail.com', '1990-12-29', '$2y$12$lOVGC3.c9YlEtcQbyIcQ/uM1sTgtoGStyJky678V28ieqFUvaxHLG', 'estudiante', '2025-10-02 19:54:35', NULL, 1, 1, 0);

-- ========================================
-- TABLA CATEGORIAS
-- ========================================
CREATE TABLE categorias (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    descripcion TEXT
);

INSERT INTO categorias (category_id, nombre, descripcion) VALUES
(1, 'Desarrollo Web', 'Cursos relacionados con tecnologías web'),
(2, 'Ciencia de Datos', 'Cursos sobre análisis y procesamiento de datos'),
(3, 'Programación General', 'Cursos introductorios a la programación');

-- ========================================
-- TABLA LENGUAJES
-- ========================================
CREATE TABLE lenguajes (
    language_id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT
);

INSERT INTO lenguajes (language_id, nombre, descripcion) VALUES
(1, 'Python', 'Lenguaje versátil para múltiples aplicaciones'),
(2, 'JavaScript', 'Lenguaje para desarrollo web interactivo'),
(3, 'Java', 'Lenguaje orientado a objetos para aplicaciones empresariales');

-- ========================================
-- TABLA CURSOS
-- ========================================
CREATE TABLE cursos (
    course_id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100),
    descripcion TEXT,
    nivel ENUM('principiante','intermedio','avanzado'),
    language_id INT NOT NULL,
    category_id INT NOT NULL,
    creado_por INT NOT NULL,
    estado TINYINT(1),
    duracion_estimada INT,
    precio DECIMAL(8,2),
    requisitos TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_cursos_lenguajes FOREIGN KEY (language_id) REFERENCES lenguajes(language_id),
    CONSTRAINT fk_cursos_categorias FOREIGN KEY (category_id) REFERENCES categorias(category_id),
    CONSTRAINT fk_cursos_usuarios FOREIGN KEY (creado_por) REFERENCES usuarios(user_id)
);

INSERT INTO cursos (course_id, titulo, descripcion, nivel, language_id, category_id, creado_por, estado, duracion_estimada, precio, requisitos, fecha_creacion) VALUES
(1, 'Python para Principiantes', 'Introducción a Python', 'principiante', 1, 3, 1, 1, NULL, 0.00, NULL, NULL),
(2, 'JavaScript Avanzado', 'Técnicas avanzadas de JS', 'avanzado', 2, 1, 2, 1, NULL, 0.00, NULL, NULL),
(3, 'Java Intermedio', 'Programación en Java', 'intermedio', 3, 3, 2, 1, NULL, 0.00, NULL, NULL),
(4, 'Curso de Python Básico', 'Curso introductorio de Python', 'principiante', 1, 3, 1, 1, NULL, 0.00, NULL, NULL),
(5, 'Suma de Números', 'Suma de números en Python', 'principiante', 1, 3, 4, 1, NULL, 0.00, NULL, NULL),
(6, 'Introducción a Python', 'Aqui empezaremos a entender sobre Python', 'principiante', 1, 3, 4, 1, NULL, 0.00, NULL, NULL),
(8, 'Java principiantes', 'principiantes curso', 'principiante', 2, 3, 4, 1, NULL, 0.00, NULL, '2025-10-02 14:00:16');

-- ========================================
-- TABLA LECCIONES
-- ========================================
CREATE TABLE lecciones (
    lesson_id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    contenido TEXT,
    orden INT,
    estado ENUM('pendiente','aprobada','rechazada') DEFAULT 'pendiente',
    CONSTRAINT fk_lecciones_cursos FOREIGN KEY (course_id) REFERENCES cursos(course_id)
);

INSERT INTO lecciones (lesson_id, course_id, titulo, contenido, orden, estado) VALUES
(1,1,'Introducción a Python','Conceptos básicos de Python',1,'aprobada'),
(2,1,'Estructuras de Control','Bucles y condicionales',2,'aprobada'),
(3,2,'Promesas en JS','Manejo de asincronía',1,'aprobada'),
(4,5,'Suma de Números','a = 5\nb = 7\nresultado = a + b\nprint("La suma es:", resultado)',1,'aprobada'),
(5,5,'resta','a = 10\nb = 5\nprint("")',2,'pendiente');

-- ========================================
-- TABLA PROGRESO_USUARIOS
-- ========================================
CREATE TABLE progreso_usuarios (
    progress_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    lesson_id INT NOT NULL,
    estado ENUM('en_progreso','completado') DEFAULT 'en_progreso',
    completado_en DATETIME DEFAULT NULL,
    puntaje FLOAT DEFAULT 0,
    INDEX idx_user_id(user_id),
    INDEX idx_lesson_id(lesson_id),
    CONSTRAINT fk_progreso_usuario FOREIGN KEY(user_id) REFERENCES usuarios(user_id) ON DELETE CASCADE,
    CONSTRAINT fk_progreso_leccion FOREIGN KEY(lesson_id) REFERENCES lecciones(lesson_id) ON DELETE CASCADE
);

INSERT INTO progreso_usuarios (progress_id, user_id, lesson_id, estado, completado_en, puntaje) VALUES
(1,3,1,'completado','2025-06-01 10:00:00',85.5),
(2,3,2,'en_progreso',NULL,NULL),
(3,2,1,'completado','2025-10-02 01:34:08',100),
(4,2,2,'completado','2025-10-02 01:34:26',100),
(6,10,1,'en_progreso',NULL,0);

-- ========================================
-- TABLA LOGROS
-- ========================================
CREATE TABLE logros (
    achievement_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    icono VARCHAR(50),
    puntos_requeridos INT,
    tipo ENUM('lecciones','desafios','racha','especial'),
    activo TINYINT(1) DEFAULT 1,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO logros (achievement_id,nombre,descripcion,icono,puntos_requeridos,tipo,activo,fecha_creacion) VALUES
(1,'Primera Lección','Completa tu primera lección','🎯',1,'lecciones',1,'2025-10-02 02:14:21'),
(2,'Estudiante Dedicado','Completa 5 lecciones','📚',5,'lecciones',1,'2025-10-02 02:14:21'),
(3,'Experto en Programación','Completa 20 lecciones','💻',20,'lecciones',1,'2025-10-02 02:14:21'),
(4,'Primer Desafío','Completa tu primer desafío de código','⚡',1,'desafios',1,'2025-10-02 02:14:21'),
(5,'Solucionador de Problemas','Completa 10 desafíos','🧩',10,'desafios',1,'2025-10-02 02:14:21'),
(6,'Racha de 3 Días','Mantén una racha de aprendizaje de 3 días consecutivos','🔥',3,'racha',1,'2025-10-02 02:14:21'),
(7,'Racha de 7 Días','Mantén una racha de aprendizaje de 7 días consecutivos','🏆',7,'racha',1,'2025-10-02 02:14:21'),
(8,'Bienvenido a SlyCipher','Completa tu perfil y comienza tu aventura de aprendizaje','🎉',0,'especial',1,'2025-10-02 02:14:21');

-- ========================================
-- TABLA LOGROS_USUARIOS
-- ========================================

CREATE TABLE logros_usuarios (
    user_achievement_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    achievement_id BIGINT NOT NULL,
    desbloqueado_en DATETIME DEFAULT NULL,
    CONSTRAINT fk_logros_usuarios_user FOREIGN KEY(user_id) REFERENCES usuarios(user_id) ON DELETE CASCADE,
    CONSTRAINT fk_logros_usuarios_achievement FOREIGN KEY(achievement_id) REFERENCES logros(achievement_id) ON DELETE CASCADE
);



-- ========================================
-- TABLA DESAFIOS
-- ========================================
CREATE TABLE desafios (
    challenge_id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    dificultad ENUM('facil','medio','dificil') DEFAULT 'facil',
    solucion TEXT,
    language_id INT NOT NULL,
    CONSTRAINT fk_desafios_curso FOREIGN KEY(course_id) REFERENCES cursos(course_id) ON DELETE CASCADE,
    CONSTRAINT fk_desafios_lenguaje FOREIGN KEY(language_id) REFERENCES lenguajes(language_id) ON DELETE CASCADE
);

INSERT INTO desafios (challenge_id, course_id, titulo, descripcion, dificultad, solucion, language_id) VALUES
(1,1,'Suma de Números','Escribe una función que sume dos números','facil','def suma(a,b): return a+b',1),
(2,2,'Fetch API','Realiza una llamada a una API','medio','fetch(url).then(res=>res.json())',2);


-- ========================================
-- TABLA DESAFIO_USUARIOS
-- ========================================
CREATE TABLE desafio_usuarios (
    submission_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT,
    challenge_id BIGINT,
    solucion_enviada TEXT,
    estado ENUM('pendiente','correcto','incorrecto'),
    puntaje INT,
    enviado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    evaluado_en TIMESTAMP NULL DEFAULT NULL
);

SELECT VERSION();
