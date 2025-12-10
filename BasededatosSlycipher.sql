CREATE DATABASE IF NOT EXISTS msqlslycipherr;
USE msqlslycipherr;



-- TABLA USUARIOS

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
ALTER TABLE usuarios
ADD COLUMN tipo_documento ENUM('CC', 'TI', 'CE', 'PP', 'NIT') NOT NULL AFTER apellido,
ADD COLUMN numero_documento VARCHAR(30) NOT NULL UNIQUE AFTER tipo_documento;

INSERT INTO usuarios (user_id, username, nombre, apellido, email, fecha_nacimiento, password_hash, rol, creado_en, ultimo_login, estado, activo, racha) VALUES
(1, 'Juan Lopez', 'Juan', 'Lopez', 'admin1@example.com', NULL, '$2y$12$9LZ8jWn1ZXTOdgLYJIAyfOx0gQpnIG4suVBXDSfXP0sEH1W7/C0LW', 'admin', '2025-09-24 21:56:17', '2025-10-31 04:28:20', 1, 1, 3),
(2, 'Camilo Gutierrez', 'Camilo', 'Gutierrez', 'est1@example.com', NULL, '$2y$12$6buhGIXDfY4M47E1fxmKFe8nMMNqQj3I6SSXqYZsEdKrLAKnEVnp6', 'estudiante', '2025-09-24 21:56:17', '2025-10-31 01:53:51', 1, 1, 7),
(3, 'Sandra Gomez', 'Sandra', 'Gomez', 'est2@example.com', NULL, 'Est2sly123*', 'estudiante', '2025-09-24 21:56:17', NULL, 1, 1, 5),
(4, 'Alejandro Torres', 'Alejandro', 'Torres', 'dev1@example.com', NULL, '$2y$12$mKbkaRMTjO84VAipZ15bc.AzkmZ8khJiw7vq4Cb8E9vqymlDa1cgu', 'desarrollador', '2025-09-24 21:56:17', '2025-10-02 16:55:23', 1, 0, 2),
(8, 'norma@gmail.com', 'Norma', 'Solano', 'norma@gmail.com', NULL, '$2y$12$jrhepgkh6pjqmyq8k2P2se2MbLLM4BNicx.rMbzotAiJ1wFyp7vQ6', 'estudiante', '2025-10-02 13:29:48', NULL, 1, 1, 0),
(10, 'dianadelgado', 'Lizeth', 'Delgado', 'dianadelgado@gmail.com', '1990-12-29', '$2y$12$lOVGC3.c9YlEtcQbyIcQ/uM1sTgtoGStyJky678V28ieqFUvaxHLG', 'estudiante', '2025-10-02 19:54:35', NULL, 1, 1, 0);

ALTER TABLE usuarios
ADD COLUMN tipo_documento ENUM('CC', 'TI', 'CE', 'PP', 'NIT') NULL AFTER apellido,
ADD COLUMN numero_documento VARCHAR(30) NULL AFTER tipo_documento;

UPDATE usuarios SET tipo_documento = 'CC', numero_documento = '123456789' WHERE user_id = 1;
UPDATE usuarios SET tipo_documento = 'TI', numero_documento = '6846546565' WHERE user_id = 2;
UPDATE usuarios SET tipo_documento = 'CC', numero_documento = '616546565' WHERE user_id = 4;
UPDATE usuarios SET tipo_documento = 'CC', numero_documento = '854651351' WHERE user_id = 10;
UPDATE usuarios SET tipo_documento = 'CC', numero_documento = '7521589' WHERE user_id = 13;
UPDATE usuarios SET tipo_documento = 'CC', numero_documento = '57451244' WHERE user_id = 14;
UPDATE usuarios SET tipo_documento = 'CC', numero_documento = '87521555' WHERE user_id = 15;
UPDATE usuarios SET tipo_documento = 'PP', numero_documento = '124684669' WHERE user_id = 16;
UPDATE usuarios SET tipo_documento = 'CC', numero_documento = '1034277330' WHERE user_id = 19;
UPDATE usuarios SET tipo_documento = 'CC', numero_documento = '1034277329' WHERE user_id = 20;
ALTER TABLE usuarios
ADD UNIQUE(numero_documento);




-- TABLA CATEGORIAS

CREATE TABLE categorias (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    descripcion TEXT
);

INSERT INTO categorias (category_id, nombre, descripcion) VALUES
(1, 'Desarrollo Web', 'Cursos relacionados con tecnologías web'),
(2, 'Ciencia de Datos', 'Cursos sobre análisis y procesamiento de datos'),
(3, 'Programación General', 'Cursos introductorios a la programación');


-- TABLA LENGUAJES

CREATE TABLE lenguajes (
    language_id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT
);

INSERT INTO lenguajes (language_id, nombre, descripcion) VALUES
(1, 'Python', 'Lenguaje versátil para múltiples aplicaciones'),
(2, 'JavaScript', 'Lenguaje para desarrollo web interactivo'),
(3, 'Java', 'Lenguaje orientado a objetos para aplicaciones empresariales');


-- TABLA CURSOS

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
ALTER TABLE cursos MODIFY COLUMN estado ENUM('pendiente','aprobada','rechazada') DEFAULT 'pendiente';
ALTER TABLE cursos ADD COLUMN visible BOOLEAN DEFAULT FALSE AFTER estado;
-- Actualizar cursos que tienen NULL o valores no válidos
UPDATE cursos 
SET estado = 'pendiente' 
WHERE estado IS NULL OR estado NOT IN ('pendiente', 'aprobada', 'rechazada');

-- Actualizar visible NULL a FALSE por defecto
UPDATE cursos 
SET visible = FALSE 
WHERE visible IS NULL;

-- Verificar los cambios
SELECT course_id, titulo, estado, visible FROM cursos;

INSERT INTO cursos (course_id, titulo, descripcion, nivel, language_id, category_id, creado_por, estado, duracion_estimada, precio, requisitos, fecha_creacion) VALUES
(1, 'Python para Principiantes', 'Introducción a Python', 'principiante', 1, 3, 1, 1, NULL, 0.00, NULL, NULL),
(2, 'JavaScript Avanzado', 'Técnicas avanzadas de JS', 'avanzado', 2, 1, 2, 1, NULL, 0.00, NULL, NULL),
(3, 'Java Intermedio', 'Programación en Java', 'intermedio', 3, 3, 2, 1, NULL, 0.00, NULL, NULL),
(4, 'Curso de Python Básico', 'Curso introductorio de Python', 'principiante', 1, 3, 1, 1, NULL, 0.00, NULL, NULL),
(5, 'Suma de Números', 'Suma de números en Python', 'principiante', 1, 3, 4, 1, NULL, 0.00, NULL, NULL),
(6, 'Introducción a Python', 'Aqui empezaremos a entender sobre Python', 'principiante', 1, 3, 4, 1, NULL, 0.00, NULL, NULL),
(8, 'Java principiantes', 'principiantes curso', 'principiante', 2, 3, 4, 1, NULL, 0.00, NULL, '2025-10-02 14:00:16');


-- TABLA LECCIONES

CREATE TABLE lecciones (
    lesson_id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    contenido TEXT,
    orden INT,
    estado ENUM('pendiente','aprobada','rechazada') DEFAULT 'pendiente',
    CONSTRAINT fk_lecciones_cursos FOREIGN KEY (course_id) REFERENCES cursos(course_id)
);
ALTER TABLE lecciones ADD COLUMN visible BOOLEAN DEFAULT TRUE AFTER estado;
ALTER TABLE lecciones 
ADD COLUMN codigo_ejemplo TEXT NULL AFTER contenido;
use msqlslycipherr;

INSERT INTO lecciones (lesson_id, course_id, titulo, contenido, orden, estado) VALUES
(1,1,'Introducción a Python','Conceptos básicos de Python',1,'aprobada'),
(2,1,'Estructuras de Control','Bucles y condicionales',2,'aprobada'),
(3,2,'Promesas en JS','Manejo de asincronía',1,'aprobada'),
(4,5,'Suma de Números','a = 5\nb = 7\nresultado = a + b\nprint("La suma es:", resultado)',1,'aprobada'),
(5,5,'resta','a = 10\nb = 5\nprint("")',2,'pendiente');



-- TABLA PROGRESO_USUARIOS

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


-- TABLA LOGROS

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


-- TABLA LOGROS_USUARIOS


CREATE TABLE logros_usuarios (
    user_achievement_id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    achievement_id BIGINT NOT NULL,
    desbloqueado_en DATETIME DEFAULT NULL,
    CONSTRAINT fk_logros_usuarios_user FOREIGN KEY(user_id) REFERENCES usuarios(user_id) ON DELETE CASCADE,
    CONSTRAINT fk_logros_usuarios_achievement FOREIGN KEY(achievement_id) REFERENCES logros(achievement_id) ON DELETE CASCADE
);



-- TABLA DESAFIOS

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


-- TABLA DESAFIOS_USUARIOS

CREATE TABLE desafios_usuarios (progreso_usuariosdesafio_usuarios
    user_challenge_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    challenge_id INT NOT NULL,
    estado ENUM('intentado','completado') DEFAULT 'intentado',
    envio TEXT,
    completado_en DATETIME DEFAULT NULL,
    puntaje FLOAT DEFAULT NULL,
    CONSTRAINT fk_desafios_usuarios_user FOREIGN KEY(user_id) REFERENCES usuarios(user_id) ON DELETE CASCADE,
    CONSTRAINT fk_desafios_usuarios_challenge FOREIGN KEY(challenge_id) REFERENCES desafios(challenge_id) ON DELETE CASCADE
);


-- TABLA DESAFIO_USUARIOS (otra variante)

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


-- TABLAS DE SISTEMA

CREATE TABLE users (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    CONSTRAINT fk_sessions_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE jobs (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload LONGTEXT NOT NULL,
    attempts TINYINT UNSIGNED NOT NULL,
    reserved_at INT DEFAULT NULL,
    available_at INT NOT NULL,
    created_at INT NOT NULL
);

CREATE TABLE job_batches (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) DEFAULT NULL,
    total_jobs INT NOT NULL,
    pending_jobs INT NOT NULL,
    failed_jobs INT NOT NULL,
    failed_job_ids LONGTEXT DEFAULT NULL,
    options MEDIUMTEXT DEFAULT NULL,
    cancelled_at INT DEFAULT NULL,
    created_at INT DEFAULT NULL,
    finished_at INT DEFAULT NULL
);

CREATE TABLE failed_jobs (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload LONGTEXT NOT NULL,
    exception LONGTEXT NOT NULL,
    failed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE migrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INT NOT NULL
);
INSERT INTO migrations (id, migration, batch) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(7, '2025_09_25_142512_add_columns_to_cursos_table', 2),
(8, '2025_10_02_005823_create_desafio_usuarios_table', 2),
(9, '2025_10_02_005901_create_logros_table', 2),
(10, '2025_10_02_005956_create_logros_usuarios_table', 2),
(11, '2025_10_02_055259_add_fecha_nacimiento_to_usuarios_table', 3);


CREATE TABLE cache_locks (
    `key` VARCHAR(255) PRIMARY KEY,
    owner VARCHAR(255),
    expiration INT
);

CREATE TABLE cache (
    `key` VARCHAR(255) PRIMARY KEY,
    value MEDIUMTEXT,
    expiration INT
);

CREATE TABLE password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



SELECT * FROM usuarios LIMIT 10;
SELECT * FROM cursos LIMIT 10;


SELECT course_id, titulo, estado, visible FROM cursos;


drop table password_reset_tokens, cache, cache_locks, migrations, failed_jobs,job_batches, jobs, sessions, users;



CREATE TABLE IF NOT EXISTS inscripciones_cursos (
    inscripcion_id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    curso_id INT NOT NULL,
    fecha_inscripcion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    estado VARCHAR(20) DEFAULT 'activo',
    progreso INT DEFAULT 0,
    ultima_actividad DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(user_id) ON DELETE CASCADE,
    FOREIGN KEY (curso_id) REFERENCES cursos(course_id) ON DELETE CASCADE,
    UNIQUE KEY unique_inscripcion (usuario_id, curso_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_usuario_inscripciones ON inscripciones_cursos(usuario_id);
CREATE INDEX idx_curso_inscripciones ON inscripciones_cursos(curso_id);
CREATE INDEX idx_estado_inscripciones ON inscripciones_cursos(estado);