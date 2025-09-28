-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS plataforma_aprendizaje3;
USE plataforma_aprendizaje3;

-- Tabla: Categorías
CREATE TABLE categorias (
    category_id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT
);

-- Tabla: Lenguajes
CREATE TABLE lenguajes (
    language_id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    nombre VARCHAR(50) UNIQUE NOT NULL,
    descripcion TEXT
);

-- Tabla: Usuarios
CREATE TABLE usuarios (
    user_id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    username VARCHAR(50) NOT NULL,
    nombre VARCHAR(50),
    apellido VARCHAR(50),
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'estudiante', 'desarrollador') NOT NULL DEFAULT 'estudiante',
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,
    ultimo_login DATETIME,
    estado BOOLEAN DEFAULT TRUE
);

-- Tabla: Cursos
CREATE TABLE cursos (
    course_id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    nivel ENUM('principiante', 'intermedio', 'avanzado') NOT NULL,
    language_id INT NOT NULL,
    category_id INT NOT NULL,
    creado_por INT NOT NULL,
    estado BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (language_id) REFERENCES lenguajes(language_id),
    FOREIGN KEY (category_id) REFERENCES categorias(category_id),
    FOREIGN KEY (creado_por) REFERENCES usuarios(user_id)
);

-- Tabla: Lecciones
CREATE TABLE lecciones (
    lesson_id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    course_id INT NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    contenido TEXT,
    orden INT NOT NULL CHECK (orden > 0),
    estado ENUM('pendiente', 'aprobada', 'rechazada') DEFAULT 'pendiente',
    FOREIGN KEY (course_id) REFERENCES cursos(course_id)
);

-- Tabla: Progreso de Usuarios
CREATE TABLE progreso_usuarios (
    progress_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    lesson_id INT NOT NULL,
    estado ENUM('en_progreso', 'completado') NOT NULL,
    completado_en DATETIME,
    puntaje FLOAT CHECK (puntaje >= 0 AND puntaje <= 100),
    FOREIGN KEY (user_id) REFERENCES usuarios(user_id),
    FOREIGN KEY (lesson_id) REFERENCES lecciones(lesson_id),
    INDEX idx_user_lesson (user_id, lesson_id)
);

-- Tabla: Desafíos
CREATE TABLE desafios (
    challenge_id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    dificultad ENUM('facil', 'medio', 'dificil') NOT NULL,
    solucion TEXT,
    language_id INT,
    FOREIGN KEY (course_id) REFERENCES cursos(course_id),
    FOREIGN KEY (language_id) REFERENCES lenguajes(language_id)
);

-- Tabla: Desafíos de Usuarios
CREATE TABLE desafios_usuarios (
    user_challenge_id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    user_id INT NOT NULL,
    challenge_id INT NOT NULL,
    estado ENUM('intentado', 'completado') NOT NULL,
    envio TEXT,
    completado_en DATETIME,
    puntaje FLOAT CHECK (puntaje >= 0 AND puntaje <= 100),
    FOREIGN KEY (user_id) REFERENCES usuarios(user_id),
    FOREIGN KEY (challenge_id) REFERENCES desafios(challenge_id)
);

-- Tabla: Logros
CREATE TABLE logros (
    achievement_id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    criterios TEXT NOT NULL,
    language_id INT NOT NULL,
    FOREIGN KEY (language_id) REFERENCES lenguajes(language_id)
);

-- Tabla: Logros de Usuarios
CREATE TABLE logros_usuarios (
    user_achievement_id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    user_id INT NOT NULL,
    achievement_id INT NOT NULL,
    desbloqueado_en DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES usuarios(user_id),
    FOREIGN KEY (achievement_id) REFERENCES logros(achievement_id)
);

-- Insertar datos en categorias
INSERT INTO categorias (nombre, descripcion) VALUES
('Desarrollo Web', 'Cursos relacionados con tecnologías web'),
('Ciencia de Datos', 'Cursos sobre análisis y procesamiento de datos'),
('Programación General', 'Cursos introductorios a la programación');

-- Insertar datos en lenguajes
INSERT INTO lenguajes (nombre, descripcion) VALUES
('Python', 'Lenguaje versátil para múltiples aplicaciones'),
('JavaScript', 'Lenguaje para desarrollo web interactivo'),
('Java', 'Lenguaje orientado a objetos para aplicaciones empresariales');

-- Insertar datos en usuarios (con contraseñas hasheadas)
INSERT INTO usuarios (username, email, password_hash, rol) VALUES
('Juan Lopez', 'admin1@example.com', 'Adminsly123!', 'admin'),
('Camilo Gutierrez', 'est1@example.com', 'Estsly123*', 'estudiante'),
('Sandra Gomez', 'est2@example.com', 'Est2sly123*', 'estudiante'),
('Alejandro Torres', 'dev1@example.com', 'Devsly123!', 'desarrollador');

-- Insertar datos en cursos
INSERT INTO cursos (titulo, descripcion, nivel, language_id, category_id, creado_por) VALUES
('Python para Principiantes', 'Introducción a Python', 'principiante', 1, 3, 1),
('JavaScript Avanzado', 'Técnicas avanzadas de JS', 'avanzado', 2, 1, 2),
('Java Intermedio', 'Programación en Java', 'intermedio', 3, 3, 2),
('Curso de Python Básico', 'Curso introductorio de Python', 'principiante', 1, 3, 1);

-- Insertar datos en lecciones
INSERT INTO lecciones (course_id, titulo, contenido, orden, estado) VALUES
(1, 'Introducción a Python', 'Conceptos básicos de Python', 1, 'aprobada'),
(1, 'Estructuras de Control', 'Bucles y condicionales', 2, 'aprobada'),
(2, 'Promesas en JS', 'Manejo de asincronía', 1, 'aprobada');

-- Insertar datos en progreso_usuarios
INSERT INTO progreso_usuarios (user_id, lesson_id, estado, completado_en, puntaje) VALUES
(3, 1, 'completado', '2025-06-01 10:00:00', 85.5),
(3, 2, 'en_progreso', NULL, NULL);

-- Insertar datos en desafios
INSERT INTO desafios (course_id, language_id, titulo, descripcion, dificultad, solucion) VALUES
(1, 1, 'Suma de Números', 'Escribe una función que sume dos números', 'facil', 'def suma(a, b): return a + b'),
(2, 2, 'Fetch API', 'Realiza una llamada a una API', 'medio', 'fetch(url).then(res => res.json())');

-- Insertar datos en logros
INSERT INTO logros (titulo, descripcion, criterios, language_id) VALUES
('Maestro Python', 'Completar 5 lecciones de Python', 'completar_5_lecciones', 1),
('JS Ninja', 'Resolver 3 desafíos de JS', 'resolver_3_desafios', 2);

ALTER TABLE usuarios ADD COLUMN activo TINYINT(1) DEFAULT 1;

UPDATE usuarios SET password_hash = '$2y$10$tyjvc4VyNzEEcYirnb1xbu0R/wBFhK9FFW40fBpAUuMJpGWl9HPpu' WHERE email = 'admin1@example.com';
UPDATE usuarios SET password_hash = '$2y$10$kisMuBihOtay59Lka1GX2.0RFLn4TkaQQg9WtEELbnbSCwn4VX08W' WHERE email = 'dev1@example.com';
UPDATE usuarios SET password_hash = '$2y$10$10jxuihXJeDDXpeSgXIZgujJPtLnzRaw2oJStdf.Uv3/.LvWagzZC' WHERE email = 'est1@example.com';
UPDATE usuarios SET password_hash = '$2y$10$1ccKl4hlb.tTc40vn2fYXOTDpl/t5JlAUGxYh.qNuVU1uy1yUnTbO' WHERE email = 'est2@example.com';

ALTER TABLE usuarios ADD COLUMN racha INT DEFAULT 0;

UPDATE usuarios SET racha = 3 WHERE email = 'admin1@example.com';
UPDATE usuarios SET racha = 7 WHERE email = 'est1@example.com';
UPDATE usuarios SET racha = 5 WHERE email = 'est2@example.com';
UPDATE usuarios SET racha = 2 WHERE email = 'dev1@example.com';
