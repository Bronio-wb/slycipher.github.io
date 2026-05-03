-- MySQL dump 10.13  Distrib 8.0.45, for Win64 (x86_64)
--
-- Host: localhost    Database: slycipherbdpython
-- ------------------------------------------------------
-- Server version	8.0.45

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `account_emailaddress`
--

DROP TABLE IF EXISTS `account_emailaddress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `account_emailaddress` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(75) NOT NULL,
  `verified` tinyint(1) NOT NULL,
  `primary` tinyint(1) NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_emailaddress`
--

LOCK TABLES `account_emailaddress` WRITE;
/*!40000 ALTER TABLE `account_emailaddress` DISABLE KEYS */;
/*!40000 ALTER TABLE `account_emailaddress` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `account_emailconfirmation`
--

DROP TABLE IF EXISTS `account_emailconfirmation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `account_emailconfirmation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `created` datetime(6) NOT NULL,
  `sent` datetime(6) DEFAULT NULL,
  `key` varchar(64) NOT NULL,
  `email_address_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_emailconfirmation`
--

LOCK TABLES `account_emailconfirmation` WRITE;
/*!40000 ALTER TABLE `account_emailconfirmation` DISABLE KEYS */;
/*!40000 ALTER TABLE `account_emailconfirmation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_group`
--

DROP TABLE IF EXISTS `auth_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_group` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_group`
--

LOCK TABLES `auth_group` WRITE;
/*!40000 ALTER TABLE `auth_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_group_permissions`
--

DROP TABLE IF EXISTS `auth_group_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_group_permissions` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `group_id` int NOT NULL,
  `permission_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `auth_group_permissions_group_id_permission_id_0cd325b0_uniq` (`group_id`,`permission_id`),
  KEY `auth_group_permissio_permission_id_84c5c92e_fk_auth_perm` (`permission_id`),
  CONSTRAINT `auth_group_permissio_permission_id_84c5c92e_fk_auth_perm` FOREIGN KEY (`permission_id`) REFERENCES `auth_permission` (`id`),
  CONSTRAINT `auth_group_permissions_group_id_b120cbf9_fk_auth_group_id` FOREIGN KEY (`group_id`) REFERENCES `auth_group` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_group_permissions`
--

LOCK TABLES `auth_group_permissions` WRITE;
/*!40000 ALTER TABLE `auth_group_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_group_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_permission`
--

DROP TABLE IF EXISTS `auth_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_permission` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `content_type_id` int NOT NULL,
  `codename` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `auth_permission_content_type_id_codename_01ab375a_uniq` (`content_type_id`,`codename`),
  CONSTRAINT `auth_permission_content_type_id_2f476e4b_fk_django_co` FOREIGN KEY (`content_type_id`) REFERENCES `django_content_type` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_permission`
--

LOCK TABLES `auth_permission` WRITE;
/*!40000 ALTER TABLE `auth_permission` DISABLE KEYS */;
INSERT INTO `auth_permission` VALUES (1,'Can add content type',1,'add_contenttype'),(2,'Can change content type',1,'change_contenttype'),(3,'Can delete content type',1,'delete_contenttype'),(4,'Can view content type',1,'view_contenttype'),(5,'Can add permission',3,'add_permission'),(6,'Can change permission',3,'change_permission'),(7,'Can delete permission',3,'delete_permission'),(8,'Can view permission',3,'view_permission'),(9,'Can add group',2,'add_group'),(10,'Can change group',2,'change_group'),(11,'Can delete group',2,'delete_group'),(12,'Can view group',2,'view_group'),(13,'Can add session',4,'add_session'),(14,'Can change session',4,'change_session'),(15,'Can delete session',4,'delete_session'),(16,'Can view session',4,'view_session'),(17,'Can add Usuario',6,'add_usuario'),(18,'Can change Usuario',6,'change_usuario'),(19,'Can delete Usuario',6,'delete_usuario'),(20,'Can view Usuario',6,'view_usuario'),(21,'Can add profile',5,'add_profile'),(22,'Can change profile',5,'change_profile'),(23,'Can delete profile',5,'delete_profile'),(24,'Can view profile',5,'view_profile'),(25,'Can add Categoría',7,'add_categoria'),(26,'Can change Categoría',7,'change_categoria'),(27,'Can delete Categoría',7,'delete_categoria'),(28,'Can view Categoría',7,'view_categoria'),(29,'Can add Lenguaje',11,'add_lenguaje'),(30,'Can change Lenguaje',11,'change_lenguaje'),(31,'Can delete Lenguaje',11,'delete_lenguaje'),(32,'Can view Lenguaje',11,'view_lenguaje'),(33,'Can add Curso',8,'add_curso'),(34,'Can change Curso',8,'change_curso'),(35,'Can delete Curso',8,'delete_curso'),(36,'Can view Curso',8,'view_curso'),(37,'Can add Lección',10,'add_leccion'),(38,'Can change Lección',10,'change_leccion'),(39,'Can delete Lección',10,'delete_leccion'),(40,'Can view Lección',10,'view_leccion'),(41,'Can add Progreso de Usuario',12,'add_progresousuario'),(42,'Can change Progreso de Usuario',12,'change_progresousuario'),(43,'Can delete Progreso de Usuario',12,'delete_progresousuario'),(44,'Can view Progreso de Usuario',12,'view_progresousuario'),(45,'Can add Inscripción',9,'add_inscripcion'),(46,'Can change Inscripción',9,'change_inscripcion'),(47,'Can delete Inscripción',9,'delete_inscripcion'),(48,'Can view Inscripción',9,'view_inscripcion'),(49,'Can add Desafío',13,'add_desafio'),(50,'Can change Desafío',13,'change_desafio'),(51,'Can delete Desafío',13,'delete_desafio'),(52,'Can view Desafío',13,'view_desafio'),(53,'Can add Envío de Desafío',14,'add_desafiousuario'),(54,'Can change Envío de Desafío',14,'change_desafiousuario'),(55,'Can delete Envío de Desafío',14,'delete_desafiousuario'),(56,'Can view Envío de Desafío',14,'view_desafiousuario'),(57,'Can add Logro',15,'add_logro'),(58,'Can change Logro',15,'change_logro'),(59,'Can delete Logro',15,'delete_logro'),(60,'Can view Logro',15,'view_logro'),(61,'Can add Logro de Usuario',16,'add_logrousuario'),(62,'Can change Logro de Usuario',16,'change_logrousuario'),(63,'Can delete Logro de Usuario',16,'delete_logrousuario'),(64,'Can view Logro de Usuario',16,'view_logrousuario'),(65,'Can add Certificado emitido',17,'add_certificadoemitido'),(66,'Can change Certificado emitido',17,'change_certificadoemitido'),(67,'Can delete Certificado emitido',17,'delete_certificadoemitido'),(68,'Can view Certificado emitido',17,'view_certificadoemitido');
/*!40000 ALTER TABLE `auth_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_user`
--

DROP TABLE IF EXISTS `auth_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `password` varchar(128) NOT NULL,
  `last_login` datetime(6) DEFAULT NULL,
  `is_superuser` tinyint(1) NOT NULL,
  `username` varchar(150) NOT NULL,
  `first_name` varchar(150) NOT NULL,
  `last_name` varchar(150) NOT NULL,
  `email` varchar(254) NOT NULL,
  `is_staff` tinyint(1) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `date_joined` datetime(6) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_user`
--

LOCK TABLES `auth_user` WRITE;
/*!40000 ALTER TABLE `auth_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_user_groups`
--

DROP TABLE IF EXISTS `auth_user_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_user_groups` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `group_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `auth_user_groups_user_id_group_id_94350c0c_uniq` (`user_id`,`group_id`),
  KEY `auth_user_groups_group_id_97559544_fk_auth_group_id` (`group_id`),
  CONSTRAINT `auth_user_groups_group_id_97559544_fk_auth_group_id` FOREIGN KEY (`group_id`) REFERENCES `auth_group` (`id`),
  CONSTRAINT `auth_user_groups_user_id_6a12ed8b_fk_auth_user_id` FOREIGN KEY (`user_id`) REFERENCES `auth_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_user_groups`
--

LOCK TABLES `auth_user_groups` WRITE;
/*!40000 ALTER TABLE `auth_user_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_user_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auth_user_user_permissions`
--

DROP TABLE IF EXISTS `auth_user_user_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auth_user_user_permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `permission_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `auth_user_user_permissions_user_id_permission_id_14a6b632_uniq` (`user_id`,`permission_id`),
  KEY `auth_user_user_permi_permission_id_1fbb5f2c_fk_auth_perm` (`permission_id`),
  CONSTRAINT `auth_user_user_permi_permission_id_1fbb5f2c_fk_auth_perm` FOREIGN KEY (`permission_id`) REFERENCES `auth_permission` (`id`),
  CONSTRAINT `auth_user_user_permissions_user_id_a95ead1b_fk_auth_user_id` FOREIGN KEY (`user_id`) REFERENCES `auth_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auth_user_user_permissions`
--

LOCK TABLES `auth_user_user_permissions` WRITE;
/*!40000 ALTER TABLE `auth_user_user_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_user_user_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `descripcion` text,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (1,'Desarrollo Web','Cursos relacionados con tecnologías web'),(2,'Ciencia de Datos','Cursos sobre análisis y procesamiento de datos'),(3,'Programación General','Cursos introductorios a la programación'),(4,'Fundamentos','Bases de programacion, logica y estructuras esenciales.'),(5,'Backend','Desarrollo de APIs, bases de datos y servicios.'),(6,'Frontend','Interfaces web, estilos y experiencia de usuario.'),(7,'Seguridad','Buenas practicas, ciberseguridad y proteccion de datos.');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `certificados_emitidos`
--

DROP TABLE IF EXISTS `certificados_emitidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `certificados_emitidos` (
  `certificate_id` bigint NOT NULL AUTO_INCREMENT,
  `certificate_type` varchar(20) NOT NULL,
  `code` varchar(40) NOT NULL,
  `total_courses` int NOT NULL,
  `notes` longtext NOT NULL,
  `issued_at` datetime(6) NOT NULL,
  `course_id` int DEFAULT NULL,
  `issued_by_id` int DEFAULT NULL,
  `student_id` int NOT NULL,
  PRIMARY KEY (`certificate_id`),
  UNIQUE KEY `code` (`code`),
  KEY `certificados_emitidos_course_id_5a6778ab_fk_cursos_course_id` (`course_id`),
  KEY `certificados_emitidos_issued_by_id_0c956119_fk_usuarios_user_id` (`issued_by_id`),
  KEY `certificados_emitidos_student_id_a7bdd384_fk_usuarios_user_id` (`student_id`),
  CONSTRAINT `certificados_emitidos_course_id_5a6778ab_fk_cursos_course_id` FOREIGN KEY (`course_id`) REFERENCES `cursos` (`course_id`),
  CONSTRAINT `certificados_emitidos_issued_by_id_0c956119_fk_usuarios_user_id` FOREIGN KEY (`issued_by_id`) REFERENCES `usuarios` (`user_id`),
  CONSTRAINT `certificados_emitidos_student_id_a7bdd384_fk_usuarios_user_id` FOREIGN KEY (`student_id`) REFERENCES `usuarios` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `certificados_emitidos`
--

LOCK TABLES `certificados_emitidos` WRITE;
/*!40000 ALTER TABLE `certificados_emitidos` DISABLE KEYS */;
INSERT INTO `certificados_emitidos` VALUES (1,'general','SLY-GEN-5DEF9B14',2,'Introducción a Python | Java principiantes','2026-04-14 00:14:42.095908',NULL,1,22),(2,'curso','SLY-CUR-5B81DB91',1,'Introducción a Python','2026-04-14 00:15:07.481737',6,1,22),(3,'curso','SLY-CUR-6F6D5EE3',1,'Java principiantes','2026-04-14 00:15:24.776580',8,1,22),(4,'general','SLY-GEN-C2D85438',2,'Introducción a Python | Java principiantes','2026-04-14 00:25:41.541127',NULL,1,22),(5,'general','SLY-GEN-FD29037B',2,'Introducción a Python | Java principiantes','2026-04-14 00:28:58.807647',NULL,1,22),(6,'curso','SLY-CUR-44AD1AA5',1,'Introducción a Python','2026-04-14 18:15:35.123138',6,1,22),(7,'general','SLY-GEN-2B7CF49B',2,'Introducción a Python | Java principiantes','2026-04-28 22:02:56.570611',NULL,1,22),(8,'general','SLY-GEN-D91BDBD3',2,'Introducción a Python | Java principiantes','2026-04-30 18:05:24.791182',NULL,1,22);
/*!40000 ALTER TABLE `certificados_emitidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cursos`
--

DROP TABLE IF EXISTS `cursos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cursos` (
  `course_id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) DEFAULT NULL,
  `descripcion` text,
  `nivel` enum('principiante','intermedio','avanzado') DEFAULT NULL,
  `language_id` int NOT NULL,
  `category_id` int NOT NULL,
  `creado_por` int NOT NULL,
  `estado` tinyint(1) DEFAULT NULL,
  `duracion_estimada` int DEFAULT NULL,
  `precio` decimal(8,2) DEFAULT NULL,
  `requisitos` text,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`course_id`),
  KEY `fk_cursos_lenguajes` (`language_id`),
  KEY `fk_cursos_categorias` (`category_id`),
  KEY `fk_cursos_usuarios` (`creado_por`),
  CONSTRAINT `fk_cursos_categorias` FOREIGN KEY (`category_id`) REFERENCES `categorias` (`category_id`),
  CONSTRAINT `fk_cursos_lenguajes` FOREIGN KEY (`language_id`) REFERENCES `lenguajes` (`language_id`),
  CONSTRAINT `fk_cursos_usuarios` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cursos`
--

LOCK TABLES `cursos` WRITE;
/*!40000 ALTER TABLE `cursos` DISABLE KEYS */;
INSERT INTO `cursos` VALUES (3,'Java Intermedio','Programación en Java','intermedio',3,3,2,1,NULL,0.00,NULL,NULL),(6,'Introducción a Python','En este curso aprenderás los conceptos básicos de Python desde cero. Conocerás cómo escribir tus primeros programas, trabajar con variables y tomar decisiones usando condiciones. Es ideal para quienes nunca han programado.','principiante',1,3,4,1,120,0.00,'No se requiere experiencia previa\r\nTener un computador con acceso a internet\r\nTener instalado Python o usar un entorno online (como Replit)\r\nGanas de aprender',NULL),(8,'Java principiantes','Este curso está diseñado para personas que desean iniciar en el mundo de la programación con Java desde cero. Aprenderás los conceptos básicos, la estructura del lenguaje y cómo crear tus primeros programas paso a paso, sin necesidad de experiencia previa.','principiante',3,3,4,1,0,0.00,'No se requiere experiencia en programación\r\nTener un computador con acceso a internet\r\nTener instalado un editor de código (como Visual Studio Code o IntelliJ IDEA)\r\nGanas de aprender','2025-10-02 19:00:16'),(14,'Fundamentos de Python','Curso introductorio diseñado para aprender los fundamentos de Python desde cero. Los estudiantes conocerán la sintaxis básica, variables, estructuras de control, funciones y manejo de datos, desarrollando pequeños ejercicios prácticos para fortalecer la lógica de programación.','principiante',1,3,4,0,60,0.00,'Aprende los conceptos básicos de Python, incluyendo sintaxis, variables, condicionales, ciclos y funciones mediante ejercicios prácticos.','2026-04-30 22:48:00');
/*!40000 ALTER TABLE `cursos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `desafio_usuarios`
--

DROP TABLE IF EXISTS `desafio_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `desafio_usuarios` (
  `submission_id` bigint NOT NULL AUTO_INCREMENT,
  `user_id` bigint DEFAULT NULL,
  `challenge_id` bigint DEFAULT NULL,
  `solucion_enviada` text,
  `estado` enum('pendiente','correcto','incorrecto') DEFAULT NULL,
  `puntaje` int DEFAULT NULL,
  `enviado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `evaluado_en` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`submission_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `desafio_usuarios`
--

LOCK TABLES `desafio_usuarios` WRITE;
/*!40000 ALTER TABLE `desafio_usuarios` DISABLE KEYS */;
INSERT INTO `desafio_usuarios` VALUES (1,3,NULL,'print(123)','pendiente',NULL,'2026-03-26 11:28:26',NULL),(2,12,11,'numero = 5\n\nif numero % 2 == 0:\n    print(\"Es par\")\nelse:\n    print(\"Es impar\")','correcto',100,'2026-03-27 05:15:30','2026-04-01 21:50:12'),(3,33,11,'numero = 7\nif numero % 2 == 0:\n    print(\"es par\")\nelse:\n    print(\"es impar\")','pendiente',NULL,'2026-04-29 02:01:33',NULL),(4,NULL,11,'numero = 7\nif numero % 2 == 0:\n    print(\"Es par\")\nelse: \n    print(\" Es Impar\")','correcto',100,'2026-04-29 02:59:42','2026-04-29 03:05:48'),(5,37,18,'resultado = 5 + 5\nprint(resultado)','pendiente',NULL,'2026-04-30 22:54:45',NULL);
/*!40000 ALTER TABLE `desafio_usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `desafios`
--

DROP TABLE IF EXISTS `desafios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `desafios` (
  `challenge_id` int NOT NULL AUTO_INCREMENT,
  `course_id` int NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text,
  `dificultad` enum('facil','medio','dificil') DEFAULT 'facil',
  `solucion` text,
  `language_id` int NOT NULL,
  PRIMARY KEY (`challenge_id`),
  KEY `fk_desafios_curso` (`course_id`),
  KEY `fk_desafios_lenguaje` (`language_id`),
  CONSTRAINT `fk_desafios_curso` FOREIGN KEY (`course_id`) REFERENCES `cursos` (`course_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_desafios_lenguaje` FOREIGN KEY (`language_id`) REFERENCES `lenguajes` (`language_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `desafios`
--

LOCK TABLES `desafios` WRITE;
/*!40000 ALTER TABLE `desafios` DISABLE KEYS */;
INSERT INTO `desafios` VALUES (9,8,'Mostrar información de un usuario','Crea un programa en Java que declare tres variables:\r\n\r\nNombre (String)\r\nEdad (int)\r\nCiudad (String)\r\n\r\nLuego, muestra en consola un mensaje con toda la información usando System.out.println()','facil','ejemplo de salida => Hola, mi nombre es Lesly, tengo 21 años y vivo en Bogotá.',3),(11,6,'Verificar si un número es par o impar','Crea un programa en Python que:\r\n\r\nDeclare una variable con un número\r\nVerifique si el número es par o impar\r\nMuestre un mensaje en consola con el resultado\r\n\r\nPista: usa el operador % (módulo)\r\n\r\nEjemplo:\r\n\r\nSi el número es 4 → \"Es par\"\r\nSi el número es 5 → \"Es impar\"','medio','ejemplo de salida  =>numero = 5',1),(18,14,'Crear una variable con una suma','En este desafío practicarás la creación de variables en Python y aprenderás a almacenar el resultado de una operación matemática básica. Debes crear una variable que guarde la suma de 5 + 5','facil','',1);
/*!40000 ALTER TABLE `desafios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `django_content_type`
--

DROP TABLE IF EXISTS `django_content_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `django_content_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `app_label` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `django_content_type_app_label_model_76bd3d3b_uniq` (`app_label`,`model`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `django_content_type`
--

LOCK TABLES `django_content_type` WRITE;
/*!40000 ALTER TABLE `django_content_type` DISABLE KEYS */;
INSERT INTO `django_content_type` VALUES (15,'achievements','logro'),(16,'achievements','logrousuario'),(2,'auth','group'),(3,'auth','permission'),(13,'challenges','desafio'),(14,'challenges','desafiousuario'),(1,'contenttypes','contenttype'),(7,'courses','categoria'),(8,'courses','curso'),(9,'courses','inscripcion'),(10,'courses','leccion'),(11,'courses','lenguaje'),(12,'courses','progresousuario'),(4,'sessions','session'),(17,'users','certificadoemitido'),(5,'users','profile'),(6,'users','usuario');
/*!40000 ALTER TABLE `django_content_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `django_migrations`
--

DROP TABLE IF EXISTS `django_migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `django_migrations` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `app` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `applied` datetime(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `django_migrations`
--

LOCK TABLES `django_migrations` WRITE;
/*!40000 ALTER TABLE `django_migrations` DISABLE KEYS */;
INSERT INTO `django_migrations` VALUES (1,'contenttypes','0001_initial','2026-03-26 03:47:22.610817'),(2,'contenttypes','0002_remove_content_type_name','2026-03-26 03:47:22.703444'),(3,'auth','0001_initial','2026-03-26 03:47:22.928716'),(4,'auth','0002_alter_permission_name_max_length','2026-03-26 03:47:22.981670'),(5,'auth','0003_alter_user_email_max_length','2026-03-26 03:47:22.986381'),(6,'auth','0004_alter_user_username_opts','2026-03-26 03:47:22.990478'),(7,'auth','0005_alter_user_last_login_null','2026-03-26 03:47:22.996384'),(8,'auth','0006_require_contenttypes_0002','2026-03-26 03:47:22.999544'),(9,'auth','0007_alter_validators_add_error_messages','2026-03-26 03:47:23.004405'),(10,'auth','0008_alter_user_username_max_length','2026-03-26 03:47:23.008657'),(11,'auth','0009_alter_user_last_name_max_length','2026-03-26 03:47:23.014216'),(12,'auth','0010_alter_group_name_max_length','2026-03-26 03:47:23.025265'),(13,'auth','0011_update_proxy_permissions','2026-03-26 03:47:23.034435'),(14,'auth','0012_alter_user_first_name_max_length','2026-03-26 03:47:23.038906'),(15,'sessions','0001_initial','2026-03-26 03:47:23.069246'),(16,'users','0001_initial','2026-03-26 06:06:21.616647'),(17,'courses','0001_initial','2026-03-26 06:20:26.197341'),(18,'users','0002_certificadoemitido','2026-04-13 23:36:08.914790');
/*!40000 ALTER TABLE `django_migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `django_session`
--

DROP TABLE IF EXISTS `django_session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `django_session` (
  `session_key` varchar(40) NOT NULL,
  `session_data` longtext NOT NULL,
  `expire_date` datetime(6) NOT NULL,
  PRIMARY KEY (`session_key`),
  KEY `django_session_expire_date_a5c62663` (`expire_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `django_session`
--

LOCK TABLES `django_session` WRITE;
/*!40000 ALTER TABLE `django_session` DISABLE KEYS */;
INSERT INTO `django_session` VALUES ('00bsrtk82kn412xjr7m9dg7jzckk54ao','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5vSI:8ANsgoc7BTF3G09QGJYOSe7WWUu-mkqyaS4u7Vqtw2M','2026-04-10 00:53:42.533904'),('04c3929wuvrdnc3eqstqqewl744c9dk2','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5tIZ:E0yk5pqYFcCF-fh_p17YIQptqTxz_2lFBS6AbCwzKtA','2026-04-09 22:35:31.287282'),('09cvqkrnbhbdj89pcap2nhppp3oy924s','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5sJp:LHih69rhnAUNuLvv-4qggPotQS4n6QbcMpbhrpgzSbQ','2026-04-09 21:32:45.882103'),('09pl7aw66givjf74knrkbh0ixkvxpw75','.eJxVyzEOwjAMheG7ZEYRdpzEYeQOzJHTpEqFBFXdToi7Q6UOsH7v_S-TZVt73rQtearmYsicfq3IcG-PfZB5Vrub2gPV3nSTZXpej9Nf2UX7N2scwHFxWCthauwjAHvvUnGJYwVGSIKF6AwVAgGCx5hIhiBuHBHM-wMJ-zO-:1w5eWK:2aFPvdQYnUeMyFoI-r5tQNt5uPIK8mEipJPxDrfoBUQ','2026-04-09 06:48:44.706605'),('0bhb8oz37kn7uhyie20en9mr2jvo56jb','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5tIZ:E0yk5pqYFcCF-fh_p17YIQptqTxz_2lFBS6AbCwzKtA','2026-04-09 22:35:31.444802'),('0eo3fe1vizldadvyt22o0txjqkg2reya','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5uia:-0n5vb54ufipmHjmfaiyNR962MPeKbLABr0MvnZOqzo','2026-04-10 00:06:28.606149'),('0h9lrfx5v75oo0258brq56xv3son0s6p','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5u69:8mrMpuaMyoKaBPUxhb5e6HvJD7AqA9OE0fh9oAvPy6Y','2026-04-09 23:26:45.908463'),('0m8lyjsbno8nadw00njc324jqserqz6r','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5w0w:QzLbEUFGgssmYjsqGCIGBD7FtO0fgcx8R-jG3D9xYPg','2026-04-10 01:29:30.795677'),('10zjnrxw10m27d4m0dsvo4kxfwqoptvz','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5u68:ITiM5FGI7Ljwo-a4bPSyWpxx_lGbBEWkfRaDyDB_2uM','2026-04-09 23:26:44.911062'),('14h9s266dqhzxng5st6mxd7neg9elr09','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5uqv:f7r6ZpmkkAaPHpwoYUG39QgkKKVbp7D49EtGGjw0PVg','2026-04-10 00:15:05.082164'),('16jt33wyqsxtu18qszjw9yrio86klus5','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5uib:Vl3nElbMyuN6X9sZOhXQoVSAkiMWklW_wToXQaMVLoI','2026-04-10 00:06:29.156406'),('1doillfspendu4n6mwwtlmwwkpgao50v','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5uia:6D23FwvDOu6w1jxCKfD5mU1TyE_vZSd_eOAYqOSNXcw','2026-04-10 00:06:28.593299'),('1e5mcwbgrr2l6nvz28432foy62chzhrl','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5vSH:yXRjvCA_W0KhkIVE1DvdRV3tMiAmGcHMngbusEwZdkw','2026-04-10 00:53:41.814330'),('1hp3dmtk8ec1jgewubz8jffh93si3619','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5uSl:PsmNtO6NaYwNDuYIPWcVcn7bAJRVr8wuWQa0_TZjKUc','2026-04-09 23:50:07.225423'),('1qo3csnhjtc6ymsv1zdprzmcb3vg81r1','.eJxVy0EOwiAQheG7sDaEDgwwLr2DazLQITQm2hRZGe-uTbrQ7ffe_1KJx7Ol0WVLy6zOalKnX8tcbnLfB17Xrnfr-sCur33wtjwux-mvbNzbN7PoI5pQiURsJrAlO8o1QvEzcTZewJAHCLWIw2oRAyFHcX6yBjyo9wc02zSW:1w5eU6:gMBp3t_D-v0qU6STItkCqonoQjIhI6TTeCZHsOp96R8','2026-04-09 06:46:26.424310'),('22pjthdy7gnivt06dn5rie29q9a2ebab','.eJxVyzEOwjAMheG7ZEYRdpzEYeQOzJHTpEqFBFXdToi7Q6UOsH7v_S-TZVt73rQtearmYsicfq3IcG-PfZB5Vrub2gPV3nSTZXpej9Nf2UX7N2scwHFxWCthauwjAHvvUnGJYwVGSIKF6AwVAgGCx5hIhiBuHBHM-wMJ-zO-:1w5eGy:Msb9AvThXPcbuw4OjueRKi4IOMw5mfpUb2YHpmOmPw0','2026-04-09 06:32:52.578270'),('24jwcomvvcjpt2jr3oclsqf2deb6xkxg','.eJxVyzEOwjAMheG7ZEYRdpzEYeQOzJHTpEqFBFXdToi7Q6UOsH7v_S-TZVt73rQtearmYsicfq3IcG-PfZB5Vrub2gPV3nSTZXpej9Nf2UX7N2scwHFxWCthauwjAHvvUnGJYwVGSIKF6AwVAgGCx5hIhiBuHBHM-wMJ-zO-:1w5eQy:p6dyvLU-hS_QzDaBog356vEt4YmKCfB0EfAiygGYebU','2026-04-09 06:43:12.821339'),('2a7ncdb92vzuhybn48vamd9hv3v7fh6t','.eJxVyzEOwjAMheG7ZEYRdpzEYeQOzJHTpEqFBFXdToi7Q6UOsH7v_S-TZVt73rQtearmYsicfq3IcG-PfZB5Vrub2gPV3nSTZXpej9Nf2UX7N2scwHFxWCthauwjAHvvUnGJYwVGSIKF6AwVAgGCx5hIhiBuHBHM-wMJ-zO-:1w5eCg:uCk3EjXFJNn4a9EXYpuAX679RqPaB-MGj6E21HW4rvg','2026-04-09 06:28:26.095802'),('2hqo2gosmn0a16nj829ldzui8lqcb1ws','.eJxVy0EOwiAQheG7sDaEDgwwLr2DazLQITQm2hRZGe-uTbrQ7ffe_1KJx7Ol0WVLy6zOalKnX8tcbnLfB17Xrnfr-sCur33wtjwux-mvbNzbN7PoI5pQiURsJrAlO8o1QvEzcTZewJAHCLWIw2oRAyFHcX6yBjyo9wc02zSW:1w5eT0:XbRyyybQl53ERj68dI_pYESWz2LYpcP5VbHnz4QTcsY','2026-04-09 06:45:18.763540'),('2oz1wbvg4fmr1cdduqqk43ffmnl3qyd8','.eJxVyzEOwjAMheG7ZEYRdpzEYeQOzJHTpEqFBFXdToi7Q6UOsH7v_S-TZVt73rQtearmYsicfq3IcG-PfZB5Vrub2gPV3nSTZXpej9Nf2UX7N2scwHFxWCthauwjAHvvUnGJYwVGSIKF6AwVAgGCx5hIhiBuHBHM-wMJ-zO-:1w5eEG:ojrM0x2bzLfcGL27rnaqF99f9BaKxumUB0JtlPAMaro','2026-04-09 06:30:04.393740'),('2paccmo8k4sltbjmkrhmaniyj6wptu9s','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5u69:ZxrzEH3U4QR1YFQRA8O33cLdDyObVClccWI1mWiG5Y0','2026-04-09 23:26:45.847025'),('2qyfgueigmasa7xh2pzrggfm9fdjrqdo','.eJxVy0EOwiAQheG7sDaEDgwwLr2DazLQITQm2hRZGe-uTbrQ7ffe_1KJx7Ol0WVLy6zOalKnX8tcbnLfB17Xrnfr-sCur33wtjwux-mvbNzbN7PoI5pQiURsJrAlO8o1QvEzcTZewJAHCLWIw2oRAyFHcX6yBjyo9wc02zSW:1w5eWK:RqPQjRg3GRk_fFEvqShOK1b8U2RtYZqgXc4hbwn6cfo','2026-04-09 06:48:44.386729'),('2tzuuuekoh62ybz3gw98hdacr3opa6xh','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5uwG:gc992u07mXs5HpYM2o3uqfgZZ_X9cYzlUHnJny4jwMA','2026-04-10 00:20:36.780555'),('2yi6b7tpbc773rasm010isfcfqmlen7g','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5uqv:f7r6ZpmkkAaPHpwoYUG39QgkKKVbp7D49EtGGjw0PVg','2026-04-10 00:15:05.013328'),('3c1c2ijk3udx2zf17pt769zjuj33j5lg','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5uqu:yrvqDqsPU8R623HbkbfRo8KJzDo0Nu_aMRtx4uoov28','2026-04-10 00:15:04.830623'),('3hleb9n8w042pxv6b7cgusdlzjro15eo','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5uib:Vl3nElbMyuN6X9sZOhXQoVSAkiMWklW_wToXQaMVLoI','2026-04-10 00:06:29.222202'),('3nz34u0ecynhnwr9mw0exnfj812s4yql','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5tIZ:xW3iqm8nohtl6Qs5FT0vKrWXULNhbUJe9uTWwulxHIo','2026-04-09 22:35:31.462348'),('3o788ma4e6loflu7tytpp9ea8x8bw7tf','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5uSk:L7c_i0DpQYnPj8NQSM-Muv2Gf87E0W4lQQ6Niu0hUbg','2026-04-09 23:50:06.413711'),('3rjvl5vzt80wlvy6r4if6lrqtcezwy30','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5sJp:LHih69rhnAUNuLvv-4qggPotQS4n6QbcMpbhrpgzSbQ','2026-04-09 21:32:45.642366'),('3sbppplyy4pt4qxbi4qcwn1lzp7ul0s8','.eJxVi70OwiAQgN-F2ZA7wQKOvoMzOQ4IjYk2PZmM796SdND1-_moSP3dYpeyxjmrqzLq9MsS8aM8h6BlET2Y6AOKvkundX7djujvbCRtbJVzcWgoWaoWIWTAGnzgGpAncMQWE-yNd2f2k6m-XBKBD4DsMoP6bmw4NiQ:1w5e3h:jIypRh1YbBw0dYjgKUGFqwxc9NW9QErhH-Cw0FJ5boY','2026-04-09 06:19:09.971388'),('3uz32a7lw71ee0l7l9txrnhzmj0rdoy1','.eJxVyzEOwjAMheG7ZEYRdpzEYeQOzJHTpEqFBFXdToi7Q6UOsH7v_S-TZVt73rQtearmYsicfq3IcG-PfZB5Vrub2gPV3nSTZXpej9Nf2UX7N2scwHFxWCthauwjAHvvUnGJYwVGSIKF6AwVAgGCx5hIhiBuHBHM-wMJ-zO-:1w5eNT:LslA-w1zx8k1bdrD6k7yU0iFDlEM832WuTdthTesmRE','2026-04-09 06:39:35.155539'),('3vb9ce0h1njiessp6y8hlmwxbwjaunpt','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5uia:bm3deuFxMqKwUaG7cGWwAgKz58sViqTDndGi_UZbHmA','2026-04-10 00:06:28.963941'),('42cx4yvmfij9ood3jlohw8oc05vinv0f','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5sJp:LHih69rhnAUNuLvv-4qggPotQS4n6QbcMpbhrpgzSbQ','2026-04-09 21:32:45.120579'),('44khrgs3iha1ma1mxi0wmvghefh4b43l','.eJxVy0EOwiAQheG7sDaEDgwwLr2DazLQITQm2hRZGe-uTbrQ7ffe_1KJx7Ol0WVLy6zOalKnX8tcbnLfB17Xrnfr-sCur33wtjwux-mvbNzbN7PoI5pQiURsJrAlO8o1QvEzcTZewJAHCLWIw2oRAyFHcX6yBjyo9wc02zSW:1w5sJq:lhiaA_S7g4cbNBF1sb_E4G-MJ7ducMKo8pUXGQhn9mA','2026-04-09 21:32:46.176512'),('47z8x5y1qzrznvruo7hott4az81xhfpy','.eJxVy0EOwiAQheG7sDaEDgwwLr2DazLQITQm2hRZGe-uTbrQ7ffe_1KJx7Ol0WVLy6zOalKnX8tcbnLfB17Xrnfr-sCur33wtjwux-mvbNzbN7PoI5pQiURsJrAlO8o1QvEzcTZewJAHCLWIw2oRAyFHcX6yBjyo9wc02zSW:1w5eWK:RqPQjRg3GRk_fFEvqShOK1b8U2RtYZqgXc4hbwn6cfo','2026-04-09 06:48:44.682383'),('489uw0bnf5p6b6uzb9nbfi0oq68kbrdg','.eJxVyzEOwjAMheG7ZEYRdpzEYeQOzJHTpEqFBFXdToi7Q6UOsH7v_S-TZVt73rQtearmYsicfq3IcG-PfZB5Vrub2gPV3nSTZXpej9Nf2UX7N2scwHFxWCthauwjAHvvUnGJYwVGSIKF6AwVAgGCx5hIhiBuHBHM-wMJ-zO-:1w5eHu:2__NkgxlzB81kRhl4KprELQ-U7DE40AkOBeXdoxeJ-s','2026-04-09 06:33:50.032403'),('4brdwnnsbe3iuylqo93v2dx0hpkek0g8','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5uSl:1wZPHjS1KHc4BT5IVIA2VWy-UIMyXFLSO7zqRrpDZpA','2026-04-09 23:50:07.119309'),('4dyli4q6yqcnpa1rby16eqabgdd988l8','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5tIY:WkgOjd2IOnQ2y7OG_nM1M3Emgb6AX9JKu8CzXbZKmkI','2026-04-09 22:35:30.605601'),('4hjhxn85ouwb2pzqrhdhefnoymok8yo2','.eJxVy0EOwiAQheG7sDaEDgwwLr2DazLQITQm2hRZGe-uTbrQ7ffe_1KJx7Ol0WVLy6zOalKnX8tcbnLfB17Xrnfr-sCur33wtjwux-mvbNzbN7PoI5pQiURsJrAlO8o1QvEzcTZewJAHCLWIw2oRAyFHcX6yBjyo9wc02zSW:1w5sJo:Ap-bKuT7sdXB9FgaRRe5jC00d8rtPVXiOKk-rsafaJg','2026-04-09 21:32:44.533338'),('4nk6l59nkgax7mmibt6g6aio3hsh9cs5','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5u68:VbpXcZnLQ6d3Y-0-MMcxQ-Q5hbrOm2uIQ9r3G0bwa8Y','2026-04-09 23:26:44.931672'),('4qy9eitc3fp8vr9s9v0i2sjn17093cuh','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5u69:ZxrzEH3U4QR1YFQRA8O33cLdDyObVClccWI1mWiG5Y0','2026-04-09 23:26:45.917522'),('4s1syfy93h44zfj9qeiv1vcpggo7xycg','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5vSI:jJvAW6kxxh34roKuOqBft3IO4p-EsCM9iQGHqT6uHHw','2026-04-10 00:53:42.654068'),('4to6emmtfqy8wfjlj6azvqp1iuiro4by','.eJxVi70OwiAQgN-F2ZA7wQKOvoMzOQ4IjYk2PZmM796SdND1-_moSP3dYpeyxjmrqzLq9MsS8aM8h6BlET2Y6AOKvkundX7djujvbCRtbJVzcWgoWaoWIWTAGnzgGpAncMQWE-yNd2f2k6m-XBKBD4DsMoP6bmw4NiQ:1w5e58:IRMOK7L-sK9jRzC0V80m5sj2BC0PyglUWDQHFFCIY1M','2026-04-09 06:20:38.992722'),('52irbvlg3yyg9enlwb3gshlx33jmnug8','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5uia:bm3deuFxMqKwUaG7cGWwAgKz58sViqTDndGi_UZbHmA','2026-04-10 00:06:28.574517'),('548yawttmp5im9mmn29dv37kgb728j4u','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5tIZ:xW3iqm8nohtl6Qs5FT0vKrWXULNhbUJe9uTWwulxHIo','2026-04-09 22:35:31.198971'),('55faf7c4wy82w3gidul9uvcb41wtdzkm','.eJxVy0EOwiAQheG7sDaEDgwwLr2DazLQITQm2hRZGe-uTbrQ7ffe_1KJx7Ol0WVLy6zOalKnX8tcbnLfB17Xrnfr-sCur33wtjwux-mvbNzbN7PoI5pQiURsJrAlO8o1QvEzcTZewJAHCLWIw2oRAyFHcX6yBjyo9wc02zSW:1w5eWK:RqPQjRg3GRk_fFEvqShOK1b8U2RtYZqgXc4hbwn6cfo','2026-04-09 06:48:44.744668'),('57w96acsxbzp77b4rxu9z7p5du56mbjy','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5vSI:jJvAW6kxxh34roKuOqBft3IO4p-EsCM9iQGHqT6uHHw','2026-04-10 00:53:42.331382'),('5afob7pm3iuiw2azkf1ncglhalzic508','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5uqu:Ag8dNd0Nj7Kx9XeDP9TXOQWgrP8vs8VsGUMDDqAFEME','2026-04-10 00:15:04.445913'),('5b02yfw4tt80hl9860slnzgjn2hy1w7q','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5w0w:QzLbEUFGgssmYjsqGCIGBD7FtO0fgcx8R-jG3D9xYPg','2026-04-10 01:29:30.523428'),('5j4e978lhy438he49z22w9hb7zj49tyy','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5uia:bm3deuFxMqKwUaG7cGWwAgKz58sViqTDndGi_UZbHmA','2026-04-10 00:06:28.249387'),('5k5442jow8u28fzk58v6ltd85755y05v','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5tIY:48qJX3P2LqmAYlUbFlOFuxNZmXWstfi7f-TGE-TFKIw','2026-04-09 22:35:30.041647'),('5ondi4ub31f4quw3qvfbdou4gjfzunet','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5uia:-0n5vb54ufipmHjmfaiyNR962MPeKbLABr0MvnZOqzo','2026-04-10 00:06:28.985170'),('5qajvo56pzltfox8t09ilbvh52hgotaj','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5uib:FGPLZidYS5fMLXkDjIgviH9Qn7SyRXRbKkgrNRQidBA','2026-04-10 00:06:29.145822'),('5zysg9msdrpu3fjy92h7cnqsjb4neiu0','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5uwG:gc992u07mXs5HpYM2o3uqfgZZ_X9cYzlUHnJny4jwMA','2026-04-10 00:20:36.439032'),('60ynscvmrp04bwa7d0ej5pvopsnh35os','.eJxVyzEOwjAMheG7ZEYRdpzEYeQOzJHTpEqFBFXdToi7Q6UOsH7v_S-TZVt73rQtearmYsicfq3IcG-PfZB5Vrub2gPV3nSTZXpej9Nf2UX7N2scwHFxWCthauwjAHvvUnGJYwVGSIKF6AwVAgGCx5hIhiBuHBHM-wMJ-zO-:1w5eWK:2aFPvdQYnUeMyFoI-r5tQNt5uPIK8mEipJPxDrfoBUQ','2026-04-09 06:48:44.411849'),('6854dp26ucdbvv09ut04bu0pywe8zu7y','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5tyc:WwyiRRHMEHz5U_Oouna2575ikV7y8kWmA557-XHTMcA','2026-04-09 23:18:58.337352'),('6aii4j7ic5yimgssvnoc43dzxalux675','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5uqu:MpxFWhgxB0FLsgPL6edhZtR1iQwLkCe_L3UYXfozdAQ','2026-04-10 00:15:04.078668'),('6cwxql6e2mvnohwpcp9mxl8nh501ithq','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5u69:Mv_C0GNnbKL5wnOixhLuWHdDkrm2RTGGIc46WjXzfY0','2026-04-09 23:26:45.859092'),('6ltthejt3lyjd2ph2tg4quytystjo7no','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5vSI:8ANsgoc7BTF3G09QGJYOSe7WWUu-mkqyaS4u7Vqtw2M','2026-04-10 00:53:42.361493'),('6q45n3ypbybt5kd1m6njj567fqpkxvdd','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5uSl:Jge_RRM9bI8Faa-ieuCTQC0DxuwjDOZYk1aZAbaF-yA','2026-04-09 23:50:07.047663'),('6u529f4tcl1rkycoi2uacoyn4y4ib9lz','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5u69:Mv_C0GNnbKL5wnOixhLuWHdDkrm2RTGGIc46WjXzfY0','2026-04-09 23:26:45.667492'),('6u9pqh130ibbnkowf3p4xrsrd1ehnnb9','.eJxVyzEOwjAMheG7ZEYRdpzEYeQOzJHTpEqFBFXdToi7Q6UOsH7v_S-TZVt73rQtearmYsicfq3IcG-PfZB5Vrub2gPV3nSTZXpej9Nf2UX7N2scwHFxWCthauwjAHvvUnGJYwVGSIKF6AwVAgGCx5hIhiBuHBHM-wMJ-zO-:1w5sJq:y94fgcBdzV6IIdnnFjIwAlRo2cMMQkPh2G6o-urbZ_4','2026-04-09 21:32:46.222302'),('74jx75bmfj3uf142lwep423ml1b8230d','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5uqu:yrvqDqsPU8R623HbkbfRo8KJzDo0Nu_aMRtx4uoov28','2026-04-10 00:15:04.435942'),('76knwot41ldfijjnkhsq378mpx8c9la9','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5uib:FGPLZidYS5fMLXkDjIgviH9Qn7SyRXRbKkgrNRQidBA','2026-04-10 00:06:29.040178'),('781vhfn6bhwydv58jp6d2mp19962d21r','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5w0v:fdG1APBh8NOUcBazL-m3ZT_WjFPZFVEGAKlmcsIoQ8o','2026-04-10 01:29:29.709172'),('7at2h297h3qgtsbxt9xyly4jx81kg7d6','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5u69:ZxrzEH3U4QR1YFQRA8O33cLdDyObVClccWI1mWiG5Y0','2026-04-09 23:26:45.633767'),('7d6ondlndujzwbdo8lf34fy3nq6wv1jn','.eJxVyzEOwjAMheG7ZEYRdpzEYeQOzJHTpEqFBFXdToi7Q6UOsH7v_S-TZVt73rQtearmYsicfq3IcG-PfZB5Vrub2gPV3nSTZXpej9Nf2UX7N2scwHFxWCthauwjAHvvUnGJYwVGSIKF6AwVAgGCx5hIhiBuHBHM-wMJ-zO-:1w5eMH:zuO2MWoBs1fuvKDN25ABdCvhHCtImGTRaIC6MR-frqI','2026-04-09 06:38:21.139571'),('7nkdhmlcjowb3bjcjktjvbp1d02qbfmo','eyJvYXV0aF9nb29nbGVfc3RhdGUiOiJ2aVZyUmVGTW52bnJEcmhFYlUwNXY5RjFlU2RvOTdWdCJ9:1w5vlE:RujGqTnrNuTr8Zvw0ZQGyUE_LB68qr2-J7I0g8OwSrU','2026-04-10 01:13:16.302778'),('80rdup1f02ze70ri1bttk6w5ztbhmvlm','.eJxVy0EOwiAQheG7sDaEDgwwLr2DazLQITQm2hRZGe-uTbrQ7ffe_1KJx7Ol0WVLy6zOalKnX8tcbnLfB17Xrnfr-sCur33wtjwux-mvbNzbN7PoI5pQiURsJrAlO8o1QvEzcTZewJAHCLWIw2oRAyFHcX6yBjyo9wc02zSW:1w5doX:pCFSMrMfbSP8N1Dygn2CIC7IflZLVGv2CcUMnw5VYl8','2026-04-09 06:03:29.219817'),('81neadgft1wv8qr27hvcbxserd0bqovq','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5eWK:3S5NnWq99urpgEQDt3qMHsqeGrEpcHonJB8SwW_Nzbk','2026-04-09 06:48:44.694059'),('8g02t3nqxfgzvmtpbaj3541c77hnhso7','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5uwG:fIdTdxc4qZ6ej_f_7S8ujvXtOyB4Y4UmnuKxZVgQfSA','2026-04-10 00:20:36.426075'),('8kl4gu112c6nrmkuawk0123qgl6wt60n','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5uSl:Jge_RRM9bI8Faa-ieuCTQC0DxuwjDOZYk1aZAbaF-yA','2026-04-09 23:50:07.254443'),('8kvuyzxmw9icwa4es4tz1f0p9suwsef6','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5tIZ:xW3iqm8nohtl6Qs5FT0vKrWXULNhbUJe9uTWwulxHIo','2026-04-09 22:35:31.064369'),('8lb89hpgeytthjv3d4zusizcvot5pylv','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5uqu:MpxFWhgxB0FLsgPL6edhZtR1iQwLkCe_L3UYXfozdAQ','2026-04-10 00:15:04.724432'),('8lylh1sid88tcl5qlrewrzmjgpwkvbaj','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5uSl:1wZPHjS1KHc4BT5IVIA2VWy-UIMyXFLSO7zqRrpDZpA','2026-04-09 23:50:07.035131'),('8rrgd0m85vm6qgpu4v067iikyv2lm615','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5u69:8mrMpuaMyoKaBPUxhb5e6HvJD7AqA9OE0fh9oAvPy6Y','2026-04-09 23:26:45.743464'),('90aq35jayyjozsertflx5j5l3axawnqr','.eJxVy0EOwiAQheG7sDaEDgwwLr2DazLQITQm2hRZGe-uTbrQ7ffe_1KJx7Ol0WVLy6zOalKnX8tcbnLfB17Xrnfr-sCur33wtjwux-mvbNzbN7PoI5pQiURsJrAlO8o1QvEzcTZewJAHCLWIw2oRAyFHcX6yBjyo9wc02zSW:1w5sJp:kaC8WNLScAWSu7UWJKZFBEJrx5KMbTRsEpnLwPRb11g','2026-04-09 21:32:45.103249'),('92doxm3xme49lvfm9j463cv4i1enms02','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5tIY:48qJX3P2LqmAYlUbFlOFuxNZmXWstfi7f-TGE-TFKIw','2026-04-09 22:35:30.622276'),('92ok82eefm16lrkuq7kz9veq7z8ioydx','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5sJo:rVsKrd3TmxzuJcJq0ZkYdwpeiiWzImsEWDZClRsR-Lo','2026-04-09 21:32:44.584319'),('96zrotoxmoj1mqgvp6ojpz882ntamr1f','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5tyb:jLlpsjAcZPygKmex4ufrjar7j3yIFIyepQkcUCenmCg','2026-04-09 23:18:57.876274'),('9tuli4rjd77gkj5hqty3gv3p61v1dxqb','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5sJq:eSCYuUInNQuKXt0jeGIk8pxJHpzMHYZSnf16urojp4c','2026-04-09 21:32:46.016293'),('9yey5hiioxck08sxdh65jcgg65qupnve','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5tyb:jLlpsjAcZPygKmex4ufrjar7j3yIFIyepQkcUCenmCg','2026-04-09 23:18:57.423966'),('a9gjvinjcey264tw4lor61crw566r0cw','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5tIZ:1EduapwIGuZjId9mK_4aITxeeqPWNtNm8v5i7wldwqQ','2026-04-09 22:35:31.479065'),('ah35trg7bzm2vmc4ttci1mb0qsu2leo3','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5vSI:jJvAW6kxxh34roKuOqBft3IO4p-EsCM9iQGHqT6uHHw','2026-04-10 00:53:42.504391'),('akueyf0xj90egnyd9duhr8fk4h83cpm7','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5tyc:Na-h_rlcOHZfLb5u9YTy5rqDM3ZElYyO3AuYagFt3ZM','2026-04-09 23:18:58.285371'),('amkezti2do90lprsa86cacsfdggj2y8m','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5u69:8mrMpuaMyoKaBPUxhb5e6HvJD7AqA9OE0fh9oAvPy6Y','2026-04-09 23:26:45.620060'),('an2quymruuii7yt1dn5fu6cpd521406f','.eJxVyzEOwjAMheG7ZEYRdpzEYeQOzJHTpEqFBFXdToi7Q6UOsH7v_S-TZVt73rQtearmYsicfq3IcG-PfZB5Vrub2gPV3nSTZXpej9Nf2UX7N2scwHFxWCthauwjAHvvUnGJYwVGSIKF6AwVAgGCx5hIhiBuHBHM-wMJ-zO-:1w5eT0:BgSpZEAFcN_HXAQlkJOlVNwpdUH7_zfHA5IybgNX1ts','2026-04-09 06:45:18.841964'),('avxfn84ci0068vmawjnmizn6b3ize4aa','eyJvYXV0aF9nb29nbGVfc3RhdGUiOiIxd0p6WFZLeDcxOVNEMjE0a3BqR0JkRjJPRGJyRElSdSJ9:1w5vmi:G9-r11gg1DmteYCK90oN0q_Vfa5HzMGG4poMVw5BBE0','2026-04-10 01:14:48.203773'),('b0jv45lau5296ba09sao2oawthzap5s8','.eJxVy0EOwiAQheG7sDaEDgwwLr2DazLQITQm2hRZGe-uTbrQ7ffe_1KJx7Ol0WVLy6zOalKnX8tcbnLfB17Xrnfr-sCur33wtjwux-mvbNzbN7PoI5pQiURsJrAlO8o1QvEzcTZewJAHCLWIw2oRAyFHcX6yBjyo9wc02zSW:1w5e1C:gfBB4UWKAxzHu0AUorNYa_wVL3I4owDocAUK-TQrWBE','2026-04-09 06:16:34.453445'),('b6sf2ad9mt51bejm086kcj5ueabpj9h1','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5vSH:n0ir81gkE3Z-XHCKDhHeJbHhjJHb9MOvuGoHfcIsKCA','2026-04-10 00:53:41.795106'),('bcjerkc1o5bht531ydx2wgnnwywe16d3','.eJxVy0EOwiAQheG7sDaEDgwwLr2DazLQITQm2hRZGe-uTbrQ7ffe_1KJx7Ol0WVLy6zOalKnX8tcbnLfB17Xrnfr-sCur33wtjwux-mvbNzbN7PoI5pQiURsJrAlO8o1QvEzcTZewJAHCLWIw2oRAyFHcX6yBjyo9wc02zSW:1w5doX:pCFSMrMfbSP8N1Dygn2CIC7IflZLVGv2CcUMnw5VYl8','2026-04-09 06:03:29.220664'),('bi5uir6sadkgr2t5nidshw55lymxeliu','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5eWK:3S5NnWq99urpgEQDt3qMHsqeGrEpcHonJB8SwW_Nzbk','2026-04-09 06:48:44.399782'),('biduix10vp9zewoiiisv4urowdgr9es6','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5uqv:i4S-uzNXee-ylNVCqGUCq2UtPz00wGXJBHYJj5-kZIY','2026-04-10 00:15:05.103643'),('bp3dx89492iqjjtyvgjh7ysq3hixnzdm','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5u69:Mv_C0GNnbKL5wnOixhLuWHdDkrm2RTGGIc46WjXzfY0','2026-04-09 23:26:45.767729'),('btatzawmc3teichr9e7xve6hijgr1eaq','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5tyc:Na-h_rlcOHZfLb5u9YTy5rqDM3ZElYyO3AuYagFt3ZM','2026-04-09 23:18:58.744229'),('bwzinyatjhp0jlec1mucq31nhaikezvj','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5tIY:B_OUIMLyXmE-OtpEy4u3kreiuaGPUKh-421w5fSJ37s','2026-04-09 22:35:30.589512'),('by27zuz5v1ha2utvhutozug0pzcxvkqd','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5uwH:ob0-jczUAE3ZrLJGCOCFAngt29k6KqJNRNhIWl7q_Fc','2026-04-10 00:20:37.101499'),('c028zqtudmvjqwnpj3zak8lauxjxzn9q','.eJxVy0EOwiAQheG7sDaEDgwwLr2DazLQITQm2hRZGe-uTbrQ7ffe_1KJx7Ol0WVLy6zOalKnX8tcbnLfB17Xrnfr-sCur33wtjwux-mvbNzbN7PoI5pQiURsJrAlO8o1QvEzcTZewJAHCLWIw2oRAyFHcX6yBjyo9wc02zSW:1w5cUn:7jE9Ps-YZkWgJe7y1behbGPZqxhu5NFvjwMi4O265pw','2026-04-09 04:39:01.565923'),('c59631bhrwpdswuvf6zbbcu184ll05nr','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5vSI:jJvAW6kxxh34roKuOqBft3IO4p-EsCM9iQGHqT6uHHw','2026-04-10 00:53:42.105247'),('c5o9c0oerbv6bmqsvvtq16f0jcr64xyo','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5uib:ZGPWVbq46mpyvmQ0C2yf-fx5Fqtfi7dlnt9dsV_E20I','2026-04-10 00:06:29.201104'),('c88rkijyogmtgncmeltylcud5tssrohv','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5w0w:Ice3rGPwSh0JmRq42LLxZQYZO6DIwRU6ztMcron3JyY','2026-04-10 01:29:30.678550'),('cayhpqo3ran0hd1ni88j9anc1mkhck75','.eJxVyzEOwjAMheG7ZEYRdpzEYeQOzJHTpEqFBFXdToi7Q6UOsH7v_S-TZVt73rQtearmYsicfq3IcG-PfZB5Vrub2gPV3nSTZXpej9Nf2UX7N2scwHFxWCthauwjAHvvUnGJYwVGSIKF6AwVAgGCx5hIhiBuHBHM-wMJ-zO-:1w5sJq:y94fgcBdzV6IIdnnFjIwAlRo2cMMQkPh2G6o-urbZ_4','2026-04-09 21:32:46.036284'),('ccowfzadezqfe6wq2icz0rmnv7pzyrck','e30:1w5vzn:9bz_40TmpRSXPIgVacFucP4FxgS3hyHZBGvou2iQOpo','2026-04-10 01:28:19.115465'),('cijpb9wps3bmr96ezzmuj0kdy42op6mr','.eJxVyzEOwjAMheG7ZEYRdpzEYeQOzJHTpEqFBFXdToi7Q6UOsH7v_S-TZVt73rQtearmYsicfq3IcG-PfZB5Vrub2gPV3nSTZXpej9Nf2UX7N2scwHFxWCthauwjAHvvUnGJYwVGSIKF6AwVAgGCx5hIhiBuHBHM-wMJ-zO-:1w5sJo:Dyt2v8-FwrUc_aSCROzEuPtbxHwMD7c0TQjeB1T65YM','2026-04-09 21:32:44.634794'),('cr32v3be2zeg2j9vkol5jw1svzs68rs8','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5w0v:X4UqyA-phpcgjrW4z2K_R7AM7DUUQD0z3fX_G2_nYOk','2026-04-10 01:29:29.737414'),('d60hu70113j97ysnyuyk1srt9vbj7lhd','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5w0w:R-zouCT0KYGK8E1VvMHJsbjUVBfgvPv66ODvb1xFusg','2026-04-10 01:29:30.700484'),('dawz8u5yay15n8dsx5dcupmswk1l64iz','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5uwG:G4ZzGoIZhSxC6_pYVldjMI6lm8nq2WKznNBO9ZPdv6k','2026-04-10 00:20:36.453416'),('dfvfjvo87z8c72itnxypieorfxxbu7jg','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5uwH:ob0-jczUAE3ZrLJGCOCFAngt29k6KqJNRNhIWl7q_Fc','2026-04-10 00:20:37.249490'),('djphbnsxebxu5f80fmcfnxgfs7t42y9n','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5tyc:WwyiRRHMEHz5U_Oouna2575ikV7y8kWmA557-XHTMcA','2026-04-09 23:18:58.498355'),('dpfo5cf2fgfd2n6k6crrj7qnw0azpr2v','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5uSk:L7c_i0DpQYnPj8NQSM-Muv2Gf87E0W4lQQ6Niu0hUbg','2026-04-09 23:50:06.747414'),('dr9ibz9xm4580hxnffp3ezvlm41kdi2j','.eJxVyzEOwjAMheG7ZEYRdpzEYeQOzJHTpEqFBFXdToi7Q6UOsH7v_S-TZVt73rQtearmYsicfq3IcG-PfZB5Vrub2gPV3nSTZXpej9Nf2UX7N2scwHFxWCthauwjAHvvUnGJYwVGSIKF6AwVAgGCx5hIhiBuHBHM-wMJ-zO-:1w5eWK:2aFPvdQYnUeMyFoI-r5tQNt5uPIK8mEipJPxDrfoBUQ','2026-04-09 06:48:44.862778'),('dsps8kr7nmsz4nlgz06yywwrzsk4563p','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5uSl:Jge_RRM9bI8Faa-ieuCTQC0DxuwjDOZYk1aZAbaF-yA','2026-04-09 23:50:07.417896'),('dyxhp3g8ypeh9rqyfvl4fvkdyysqwa8p','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5w0w:QzLbEUFGgssmYjsqGCIGBD7FtO0fgcx8R-jG3D9xYPg','2026-04-10 01:29:30.119521'),('e6gor0knou8249l0re1qd525i2sevs95','.eJxVy0EOwiAQheG7sDaEDgwwLr2DazLQITQm2hRZGe-uTbrQ7ffe_1KJx7Ol0WVLy6zOalKnX8tcbnLfB17Xrnfr-sCur33wtjwux-mvbNzbN7PoI5pQiURsJrAlO8o1QvEzcTZewJAHCLWIw2oRAyFHcX6yBjyo9wc02zSW:1w5e28:KHsYyZEsQo4UiQuZ_tySFVvDDBp7v213MpMElrq8GBM','2026-04-09 06:17:32.007692'),('e99mx00e1dpohcofwt0yyals2cgowud5','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5vSH:yXRjvCA_W0KhkIVE1DvdRV3tMiAmGcHMngbusEwZdkw','2026-04-10 00:53:41.381324'),('edz2ky9h9kb0kgmst45cz6a51haejwcb','.eJxVy0EOwiAQheG7sDaEDgwwLr2DazLQITQm2hRZGe-uTbrQ7ffe_1KJx7Ol0WVLy6zOalKnX8tcbnLfB17Xrnfr-sCur33wtjwux-mvbNzbN7PoI5pQiURsJrAlO8o1QvEzcTZewJAHCLWIw2oRAyFHcX6yBjyo9wc02zSW:1w5sJp:kaC8WNLScAWSu7UWJKZFBEJrx5KMbTRsEpnLwPRb11g','2026-04-09 21:32:45.616154'),('el8mp7abtow5c2aoy9l8ap3pxi65v98e','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5u69:ZxrzEH3U4QR1YFQRA8O33cLdDyObVClccWI1mWiG5Y0','2026-04-09 23:26:45.755191'),('evt9b41jbezv6682zs5h6vqf3im2n3uz','.eJxVy0EOwiAQheG7sDaEDgwwLr2DazLQITQm2hRZGe-uTbrQ7ffe_1KJx7Ol0WVLy6zOalKnX8tcbnLfB17Xrnfr-sCur33wtjwux-mvbNzbN7PoI5pQiURsJrAlO8o1QvEzcTZewJAHCLWIw2oRAyFHcX6yBjyo9wc02zSW:1w5dvd:3mQCVrOFW7bF6lHnIawm_oOLm55MzLYzy6Sm4Uu5cYA','2026-04-09 06:10:49.496371'),('f91wry0lziu1www2de1jyg01nj753hd2','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5u69:ZxrzEH3U4QR1YFQRA8O33cLdDyObVClccWI1mWiG5Y0','2026-04-09 23:26:45.248946'),('fa0wjs70t6akghcfywh4p0bzf3bg1tw7','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5tyb:osNLXDzAWxrF19cqMNVu5ywo5g8eWim9g6_C_Uifn6Q','2026-04-09 23:18:57.446354'),('foj4n6upp1d0hsnu3u8rbgyh2e34oy6c','.eJxVy0EOwiAQheG7sDaEDgwwLr2DazLQITQm2hRZGe-uTbrQ7ffe_1KJx7Ol0WVLy6zOalKnX8tcbnLfB17Xrnfr-sCur33wtjwux-mvbNzbN7PoI5pQiURsJrAlO8o1QvEzcTZewJAHCLWIw2oRAyFHcX6yBjyo9wc02zSW:1w5sJp:kaC8WNLScAWSu7UWJKZFBEJrx5KMbTRsEpnLwPRb11g','2026-04-09 21:32:45.992203'),('foqchc4lth4yro6qw5uy35gk8b0e4kgw','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5u68:jLw_amvYBHK7QruNlWvdQt7szIhWqa2APAaZf55Mm6k','2026-04-09 23:26:44.921320'),('fw3b6wn0knu964dkx2xg2e5yhf7aisib','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5uwH:jlmuwF5fU_TuS4rWaGhDi--nkke6m_kjTaWTzjeQdKQ','2026-04-10 00:20:37.156595'),('g27dgg72qvshisxpyc5dy34nwswa9ogl','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5uwH:ioIK5jEPcuhV1J1z-WHmDjDdnBiKwmrZCnjQATPvhWI','2026-04-10 00:20:37.417008'),('g35fmmu60u8zmkunbrogdvvobi5pi3it','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5u69:ZxrzEH3U4QR1YFQRA8O33cLdDyObVClccWI1mWiG5Y0','2026-04-09 23:26:45.548907'),('g9yoe39um9lvkn1appypx4xwd60j95b2','.eJxVy0EOwiAQheG7sDaEDgwwLr2DazLQITQm2hRZGe-uTbrQ7ffe_1KJx7Ol0WVLy6zOalKnX8tcbnLfB17Xrnfr-sCur33wtjwux-mvbNzbN7PoI5pQiURsJrAlO8o1QvEzcTZewJAHCLWIw2oRAyFHcX6yBjyo9wc02zSW:1w5dw5:YboVD__N5biGQb55VrJajvhkTNYmJZwwVU-3ucFIm84','2026-04-09 06:11:17.303022'),('ge9zwulxeyz5dnfph9lxkbadlu5x2cch','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5eT0:hlV6lH0MUUQesnhb4--0YZNzQnw_OrFcV1S-_FhZ230','2026-04-09 06:45:18.794125'),('ghyzd32fhilfduel8r6ivldr9zg3mvbu','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5uwH:jlmuwF5fU_TuS4rWaGhDi--nkke6m_kjTaWTzjeQdKQ','2026-04-10 00:20:37.323747'),('gi5bha0euoswaqep65uzszjhatsfpwld','e30:1w5vzn:9bz_40TmpRSXPIgVacFucP4FxgS3hyHZBGvou2iQOpo','2026-04-10 01:28:19.122380'),('gpza6o6p5wpuclnm9yhmbfh5v9cd20yi','.eJxVy0EOwiAQheG7sDaEDgwwLr2DazLQITQm2hRZGe-uTbrQ7ffe_1KJx7Ol0WVLy6zOalKnX8tcbnLfB17Xrnfr-sCur33wtjwux-mvbNzbN7PoI5pQiURsJrAlO8o1QvEzcTZewJAHCLWIw2oRAyFHcX6yBjyo9wc02zSW:1w5cVX:wxkbgLDDMPUUz_ggOXPtCkUTfUcFq4Ss1r3tFxL8TV0','2026-04-09 04:39:47.990665'),('h1hp8h65u4psi0pjyuycz3twnzwr8yh4','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5vSI:8ANsgoc7BTF3G09QGJYOSe7WWUu-mkqyaS4u7Vqtw2M','2026-04-10 00:53:42.253491'),('h4h68zrzhmd4f6spar7c5qowrzzaerhr','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5w0w:Ice3rGPwSh0JmRq42LLxZQYZO6DIwRU6ztMcron3JyY','2026-04-10 01:29:30.511004'),('h5w24copin30jlagb9mnig4ev6ah0v5w','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5u69:8mrMpuaMyoKaBPUxhb5e6HvJD7AqA9OE0fh9oAvPy6Y','2026-04-09 23:26:45.238327'),('hbt3eygbn5k8unpknl9esprunjp8l6wj','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5tyc:RlSwUgxxg2DzFfLA6AKNyS35bbndKsf6sxSp2FYt0BE','2026-04-09 23:18:58.481099'),('hdnauk6y2nsk7walq8cuh17mdfhh2m74','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5u69:Mv_C0GNnbKL5wnOixhLuWHdDkrm2RTGGIc46WjXzfY0','2026-04-09 23:26:45.260168'),('hg76pczzjv6d2xovvxy5y7fxivqcnd4n','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5vSI:8ANsgoc7BTF3G09QGJYOSe7WWUu-mkqyaS4u7Vqtw2M','2026-04-10 00:53:42.126236'),('hhzkxwwreb7khij39rv92bo9bbj35isw','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5uqu:Ag8dNd0Nj7Kx9XeDP9TXOQWgrP8vs8VsGUMDDqAFEME','2026-04-10 00:15:04.755923'),('hjdqhiymo60dfqo0z55dehiv6zqwai04','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5uqu:MpxFWhgxB0FLsgPL6edhZtR1iQwLkCe_L3UYXfozdAQ','2026-04-10 00:15:04.425762'),('hn8a0ct6gv64nadv8p10hr0i3b53iuf3','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5uia:bm3deuFxMqKwUaG7cGWwAgKz58sViqTDndGi_UZbHmA','2026-04-10 00:06:28.881505'),('hua4xurv8n2yo8rxu8ysvf54q5s5mjro','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5uSl:Jge_RRM9bI8Faa-ieuCTQC0DxuwjDOZYk1aZAbaF-yA','2026-04-09 23:50:07.143954'),('hxarovoc70houd70jsc1lvtvtqf1l9h5','.eJxVy0EOwiAQheG7sDaEDgwwLr2DazLQITQm2hRZGe-uTbrQ7ffe_1KJx7Ol0WVLy6zOalKnX8tcbnLfB17Xrnfr-sCur33wtjwux-mvbNzbN7PoI5pQiURsJrAlO8o1QvEzcTZewJAHCLWIw2oRAyFHcX6yBjyo9wc02zSW:1w5dt2:GpVazuOO-ktAzj5mBLHXX1gSyVMZ7P3UH4DMyx0AU0Y','2026-04-09 06:08:08.112687'),('hygg03pfnd0mbvp9t7rkjgqopjxgayns','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5uia:6D23FwvDOu6w1jxCKfD5mU1TyE_vZSd_eOAYqOSNXcw','2026-04-10 00:06:28.889861'),('i0eumaup63htu62sxma29qzgg51fmtxr','.eJxVyzEOwjAMheG7ZEYRdpzEYeQOzJHTpEqFBFXdToi7Q6UOsH7v_S-TZVt73rQtearmYsicfq3IcG-PfZB5Vrub2gPV3nSTZXpej9Nf2UX7N2scwHFxWCthauwjAHvvUnGJYwVGSIKF6AwVAgGCx5hIhiBuHBHM-wMJ-zO-:1w5eFZ:NDG47XrxQsSDQlshyEd6Kl-l6ubTEGqat9wV74BVRlY','2026-04-09 06:31:25.748352'),('i6tjyiwxwg39givv3d171bfizpn2u2lt','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5uib:ZGPWVbq46mpyvmQ0C2yf-fx5Fqtfi7dlnt9dsV_E20I','2026-04-10 00:06:29.134463'),('iel4f9fnf15akmvxlct51on8waxvzmnc','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5eRF:CtlQ-ITcocatmA8RwwHDdd1x-0eo3kDOEfOJq2D224M','2026-04-09 06:43:29.685902'),('iikd0lwe53gq83opxs6lunqecxnalq9f','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5tIY:WkgOjd2IOnQ2y7OG_nM1M3Emgb6AX9JKu8CzXbZKmkI','2026-04-09 22:35:30.023904'),('ip0ftynvdrgrll2pz00h7t2mr0y2ojcz','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5tIZ:1EduapwIGuZjId9mK_4aITxeeqPWNtNm8v5i7wldwqQ','2026-04-09 22:35:31.326654'),('isfhqr6gdck7xtwogkikq24shwrd7szy','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5tyc:RlSwUgxxg2DzFfLA6AKNyS35bbndKsf6sxSp2FYt0BE','2026-04-09 23:18:58.768000'),('iy3zmr35yo5x0adcnix6aq3z1ja6njwk','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5vSH:oCMKPevBF5jROi4lnkTfIOFK3ee3qMrmLJqCgh0eCZw','2026-04-10 00:53:41.828729'),('jg59cpcmgncdkp1l7rvb5b0dul69vmrc','.eJxVy0EOwiAQheG7sDaEDgwwLr2DazLQITQm2hRZGe-uTbrQ7ffe_1KJx7Ol0WVLy6zOalKnX8tcbnLfB17Xrnfr-sCur33wtjwux-mvbNzbN7PoI5pQiURsJrAlO8o1QvEzcTZewJAHCLWIw2oRAyFHcX6yBjyo9wc02zSW:1w5sJp:kaC8WNLScAWSu7UWJKZFBEJrx5KMbTRsEpnLwPRb11g','2026-04-09 21:32:45.861410'),('jitdnjs6nadbtdul35cr5u4dcc1qcgir','eyJvYXV0aF9nb29nbGVfc3RhdGUiOiJQMVB1bGEyNEJjM1pLSXdPUWxmS1BJemwxUm9OSVBiNyJ9:1w5vq5:149g1KTCQ2leHVr4tMPZiAxQIMYxQjha9PmbdihTLiA','2026-04-10 01:18:17.419924'),('jnvu9jiqcq8u72kl4cv0oldpljnqoe1h','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5w0w:R-zouCT0KYGK8E1VvMHJsbjUVBfgvPv66ODvb1xFusg','2026-04-10 01:29:30.132151'),('joqv6p2gepl1f7je51hua1tbr9ig8f5n','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5w0w:R-zouCT0KYGK8E1VvMHJsbjUVBfgvPv66ODvb1xFusg','2026-04-10 01:29:30.534833'),('k6fjnjtheantc6yjyxtmjpmgdj748s0n','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5w0w:R-zouCT0KYGK8E1VvMHJsbjUVBfgvPv66ODvb1xFusg','2026-04-10 01:29:30.445243'),('k7uu1ei4lzqx03pvk5slv4zvsbt9k33j','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5uqu:Ag8dNd0Nj7Kx9XeDP9TXOQWgrP8vs8VsGUMDDqAFEME','2026-04-10 00:15:04.908651'),('l26pn4fuinkrq9zsubeif72ckgtqnoe8','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5tyc:RlSwUgxxg2DzFfLA6AKNyS35bbndKsf6sxSp2FYt0BE','2026-04-09 23:18:58.579856'),('l8717xauu6h7q31cjemv2zwg21w1tvyw','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5uia:-0n5vb54ufipmHjmfaiyNR962MPeKbLABr0MvnZOqzo','2026-04-10 00:06:28.270298'),('lgkf3v7fnkb2w0c7uixl1cchrat9daaj','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5uwH:ioIK5jEPcuhV1J1z-WHmDjDdnBiKwmrZCnjQATPvhWI','2026-04-10 00:20:37.091290'),('llscxv9zyt27cf4unv0n3j6b6yafuwpz','.eJxVy0EOwiAQheG7sDaEDgwwLr2DazLQITQm2hRZGe-uTbrQ7ffe_1KJx7Ol0WVLy6zOalKnX8tcbnLfB17Xrnfr-sCur33wtjwux-mvbNzbN7PoI5pQiURsJrAlO8o1QvEzcTZewJAHCLWIw2oRAyFHcX6yBjyo9wc02zSW:1w5drW:XiCtxsTW-DJJB6zjXMw-Tsq-m_qLamo1YJHJR5yj38A','2026-04-09 06:06:34.530905'),('lqd8l9mf58v4szdda48jd13jzisw0oey','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5uSl:1wZPHjS1KHc4BT5IVIA2VWy-UIMyXFLSO7zqRrpDZpA','2026-04-09 23:50:07.406160'),('lxefwdcnaxqhkqalo177jiyu0wxgfr16','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5tIZ:xW3iqm8nohtl6Qs5FT0vKrWXULNhbUJe9uTWwulxHIo','2026-04-09 22:35:31.307839'),('m0bhqrgwzdmp69k48foasbnltrdwvuyy','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5w0w:R-zouCT0KYGK8E1VvMHJsbjUVBfgvPv66ODvb1xFusg','2026-04-10 01:29:30.600579'),('m0zbqrofyf7m3q6784s5pmaqjflo2kay','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5uqu:yrvqDqsPU8R623HbkbfRo8KJzDo0Nu_aMRtx4uoov28','2026-04-10 00:15:04.091747'),('max0o9plg95zpp11756v3bdiybh6myy1','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5uwH:ioIK5jEPcuhV1J1z-WHmDjDdnBiKwmrZCnjQATPvhWI','2026-04-10 00:20:37.335552'),('menkjldw52t22j6z4x543pcg7iop2w0z','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1wJJpo:rWl6yeyQncxzrMoMMmAh_l6gOw8sOTV7k8--1HS1Brw','2026-05-16 23:33:20.506513'),('mg5dxdgmankohnzjvd1z9voqw17mar7p','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5w0w:QzLbEUFGgssmYjsqGCIGBD7FtO0fgcx8R-jG3D9xYPg','2026-04-10 01:29:30.690158'),('mokn09sqpeat40ekh3osls31q77vhyf8','.eJxVyzEOwjAMheG7ZEYRdpzEYeQOzJHTpEqFBFXdToi7Q6UOsH7v_S-TZVt73rQtearmYsicfq3IcG-PfZB5Vrub2gPV3nSTZXpej9Nf2UX7N2scwHFxWCthauwjAHvvUnGJYwVGSIKF6AwVAgGCx5hIhiBuHBHM-wMJ-zO-:1w5eWK:2aFPvdQYnUeMyFoI-r5tQNt5uPIK8mEipJPxDrfoBUQ','2026-04-09 06:48:44.764177'),('mt8t59flw3fuqk26c0ekfxo7tmbhwltx','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5vSI:BZH2dejptf4mKZGqVyeIz0wGLGYCSpOtJg3q4lnCZxI','2026-04-10 00:53:42.115408'),('n46jf2ebv8np7fmcxto6cmivvbbi8yzb','.eJxVi70OwiAQgN-F2ZA7wQKOvoMzOQ4IjYk2PZmM796SdND1-_moSP3dYpeyxjmrqzLq9MsS8aM8h6BlET2Y6AOKvkundX7djujvbCRtbJVzcWgoWaoWIWTAGnzgGpAncMQWE-yNd2f2k6m-XBKBD4DsMoP6bmw4NiQ:1w5eCf:Mk87qkDf0jnFEMWTBuxjMQqd8M646SUHjlRn79awVW0','2026-04-09 06:28:25.338994'),('n74wxyxna2nkbu13f3gmztxdfv16qdhh','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5uSl:Jge_RRM9bI8Faa-ieuCTQC0DxuwjDOZYk1aZAbaF-yA','2026-04-09 23:50:07.345713'),('nfshyle3brwkaykji4unira38shf7zrq','.eJxVyzEOwjAMheG7ZEYRdpzEYeQOzJHTpEqFBFXdToi7Q6UOsH7v_S-TZVt73rQtearmYsicfq3IcG-PfZB5Vrub2gPV3nSTZXpej9Nf2UX7N2scwHFxWCthauwjAHvvUnGJYwVGSIKF6AwVAgGCx5hIhiBuHBHM-wMJ-zO-:1w5ceL:uP7bJrs6UtlOD38p6FiMaG0VgJ0aeZdkgNtrjV44ce0','2026-04-09 04:48:53.473853'),('ngaajp0bvk0n8r6wlhfp6coij062ccc5','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5uSl:PsmNtO6NaYwNDuYIPWcVcn7bAJRVr8wuWQa0_TZjKUc','2026-04-09 23:50:07.027107'),('njecnbv6r258ukef31td2j03qrivv6vg','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5vSH:n0ir81gkE3Z-XHCKDhHeJbHhjJHb9MOvuGoHfcIsKCA','2026-04-10 00:53:41.366297'),('nkjbret53jbnohh3d3jy7rjedtcdozqp','.eJxVy0EOwiAQheG7sDaEDgwwLr2DazLQITQm2hRZGe-uTbrQ7ffe_1KJx7Ol0WVLy6zOalKnX8tcbnLfB17Xrnfr-sCur33wtjwux-mvbNzbN7PoI5pQiURsJrAlO8o1QvEzcTZewJAHCLWIw2oRAyFHcX6yBjyo9wc02zSW:1w5dut:OxmF62hqxTJdhdo_Nh3MJmcbVky8KAoi2QmZvJ8J1s4','2026-04-09 06:10:03.833945'),('nsnz6rgdcf7ik5jwm2jxvlaiq94ahggh','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5uwH:ob0-jczUAE3ZrLJGCOCFAngt29k6KqJNRNhIWl7q_Fc','2026-04-10 00:20:37.351998'),('o8yfp2pl98r7iy12cdn6hwd3myan3k8l','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5tIZ:E0yk5pqYFcCF-fh_p17YIQptqTxz_2lFBS6AbCwzKtA','2026-04-09 22:35:31.177938'),('ob8zshaha6aqfs6em5mgwx9lgkkxpkfz','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5uSl:PsmNtO6NaYwNDuYIPWcVcn7bAJRVr8wuWQa0_TZjKUc','2026-04-09 23:50:07.322063'),('okkjb8cs6xbqzm6ptsq84iolel6p18jn','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5tyc:RlSwUgxxg2DzFfLA6AKNyS35bbndKsf6sxSp2FYt0BE','2026-04-09 23:18:58.311255'),('otbnhf40o3zg3epfk4v9l95lu3z51pfb','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5w0w:R-zouCT0KYGK8E1VvMHJsbjUVBfgvPv66ODvb1xFusg','2026-04-10 01:29:30.809328'),('oz519n4c3b0kk7icrngmzdgscmx5x3nm','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5uib:FGPLZidYS5fMLXkDjIgviH9Qn7SyRXRbKkgrNRQidBA','2026-04-10 00:06:29.210214'),('p6q10szjnlvygzcbi0b0pair6pywejsz','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5eWK:3S5NnWq99urpgEQDt3qMHsqeGrEpcHonJB8SwW_Nzbk','2026-04-09 06:48:44.753062'),('p8uwbipuuufzbqux246w9kkrik5ebp9c','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5uib:ZGPWVbq46mpyvmQ0C2yf-fx5Fqtfi7dlnt9dsV_E20I','2026-04-10 00:06:29.029342'),('pcbc0wvbgdmmyal235c999tf20qnctap','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5uSl:PsmNtO6NaYwNDuYIPWcVcn7bAJRVr8wuWQa0_TZjKUc','2026-04-09 23:50:07.394943'),('pdlz1wrnw8nb7nbgl85nut117qux5e9t','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5vSI:BZH2dejptf4mKZGqVyeIz0wGLGYCSpOtJg3q4lnCZxI','2026-04-10 00:53:42.664086'),('play375vf0vt5lwmre2qai704dsm5d4l','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5tIZ:1EduapwIGuZjId9mK_4aITxeeqPWNtNm8v5i7wldwqQ','2026-04-09 22:35:31.622705'),('pqvjvtvndbnecu3bb9cw1i4jjnsl7nhc','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5uwG:fIdTdxc4qZ6ej_f_7S8ujvXtOyB4Y4UmnuKxZVgQfSA','2026-04-10 00:20:36.767212'),('psjwmjpobbew01yel4l8egtlj40092hl','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5uSk:WsyQuJ6dyxHSEZ4k8OIsEJfkYyhr2HOqNoDWEf0f05M','2026-04-09 23:50:06.385530'),('psmuymnyrkxnpjloflg1zizvguc6gudx','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5uqu:Ag8dNd0Nj7Kx9XeDP9TXOQWgrP8vs8VsGUMDDqAFEME','2026-04-10 00:15:04.841043'),('pva9gjmvxukpbgdrt43jenzgikcttlz5','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5uwH:jlmuwF5fU_TuS4rWaGhDi--nkke6m_kjTaWTzjeQdKQ','2026-04-10 00:20:37.079825'),('pyx89dhik2v945uakyjkzgfonaiajz32','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5uqv:ALfImCAkMkmrCPekvavZPO5fK1-5gJuxaJi6bBvr0l8','2026-04-10 00:15:05.092630'),('q4vld37y3k1mjtnx7ofwcv7dghh1jb2u','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5uSk:WsyQuJ6dyxHSEZ4k8OIsEJfkYyhr2HOqNoDWEf0f05M','2026-04-09 23:50:06.726229'),('q6i9zkn4gpqv31d8zyxv2kw46g1njy22','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5uia:6D23FwvDOu6w1jxCKfD5mU1TyE_vZSd_eOAYqOSNXcw','2026-04-10 00:06:28.259653'),('q9nlx88l75asnjkgcqnh9uz9rc6v7a8z','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5vSI:BZH2dejptf4mKZGqVyeIz0wGLGYCSpOtJg3q4lnCZxI','2026-04-10 00:53:42.347869'),('qbgwaa4ngkld3jurt9d3tme4t91aroga','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5tyc:Na-h_rlcOHZfLb5u9YTy5rqDM3ZElYyO3AuYagFt3ZM','2026-04-09 23:18:58.463202'),('qjboa3uxjoukpko60ghlenlgd5w24ode','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5sJq:eSCYuUInNQuKXt0jeGIk8pxJHpzMHYZSnf16urojp4c','2026-04-09 21:32:46.201278'),('qorpaixmmx3zo6exrymnmp4vv2nvksa6','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5vSI:BZH2dejptf4mKZGqVyeIz0wGLGYCSpOtJg3q4lnCZxI','2026-04-10 00:53:42.518916'),('r7nd4afk4t9n6i3axf4r9dubvu9vb3j8','.eJxVy0EOwiAQheG7sDaEDgwwLr2DazLQITQm2hRZGe-uTbrQ7ffe_1KJx7Ol0WVLy6zOalKnX8tcbnLfB17Xrnfr-sCur33wtjwux-mvbNzbN7PoI5pQiURsJrAlO8o1QvEzcTZewJAHCLWIw2oRAyFHcX6yBjyo9wc02zSW:1w5eWK:RqPQjRg3GRk_fFEvqShOK1b8U2RtYZqgXc4hbwn6cfo','2026-04-09 06:48:44.839173'),('ra9tixw6w638za9l2ch50upopvb6x4oi','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5uSl:PsmNtO6NaYwNDuYIPWcVcn7bAJRVr8wuWQa0_TZjKUc','2026-04-09 23:50:07.108658'),('rcqumil4s5fv7bp47rye0qv37beyf3qm','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5tyb:ll512YrUK2g3UQKW0EINWJMA2QADWzwS3fp9uMHCbRY','2026-04-09 23:18:57.408111'),('rcvkhsx6r4yygjjs5yjq7fgwhnaejkwo','.eJxVi70OwiAQgN-F2ZA7wQKOvoMzOQ4IjYk2PZmM796SdND1-_moSP3dYpeyxjmrqzLq9MsS8aM8h6BlET2Y6AOKvkundX7djujvbCRtbJVzcWgoWaoWIWTAGnzgGpAncMQWE-yNd2f2k6m-XBKBD4DsMoP6bmw4NiQ:1w5cTx:jQaWSS-BngJRvHklQhD29FpV8pCAULCijyWhIKX3AAI','2026-04-09 04:38:09.745553'),('reddcm5zhsart0rs05e7w0uzazxiwzaj','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5vSI:8ANsgoc7BTF3G09QGJYOSe7WWUu-mkqyaS4u7Vqtw2M','2026-04-10 00:53:42.673515'),('rh1echqufmcq626cjxrnm5r3acuzn61f','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5u69:Mv_C0GNnbKL5wnOixhLuWHdDkrm2RTGGIc46WjXzfY0','2026-04-09 23:26:45.559985'),('rqynyhym0qlw8t1zdmim50g1i0d6zr7t','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5tyb:osNLXDzAWxrF19cqMNVu5ywo5g8eWim9g6_C_Uifn6Q','2026-04-09 23:18:57.893037'),('rspqrijc21miheqicf9o55rxrzq8mfzs','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5sJq:eSCYuUInNQuKXt0jeGIk8pxJHpzMHYZSnf16urojp4c','2026-04-09 21:32:46.368237'),('rya1gnizxj1m8g7ruqwom9dc3tka9024','.eJxVy0EOwiAQheG7sDaEDgwwLr2DazLQITQm2hRZGe-uTbrQ7ffe_1KJx7Ol0WVLy6zOalKnX8tcbnLfB17Xrnfr-sCur33wtjwux-mvbNzbN7PoI5pQiURsJrAlO8o1QvEzcTZewJAHCLWIw2oRAyFHcX6yBjyo9wc02zSW:1w5sJq:lhiaA_S7g4cbNBF1sb_E4G-MJ7ducMKo8pUXGQhn9mA','2026-04-09 21:32:46.340649'),('s1tbrei3f05nf8fn19o0pnxjxwv1itud','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5tIY:B_OUIMLyXmE-OtpEy4u3kreiuaGPUKh-421w5fSJ37s','2026-04-09 22:35:30.006037'),('s2ro1ezrhuvgbjdw0awk9bprbkl53e6w','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5eT0:hlV6lH0MUUQesnhb4--0YZNzQnw_OrFcV1S-_FhZ230','2026-04-09 06:45:18.732347'),('s8ljqkz755h0jkifs0enhgel0et4e525','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5uSk:VmlEbznN1VBOUk5zZKWYFWF1DfIa6CwM2s8d3Mh-gGM','2026-04-09 23:50:06.736143'),('sstkt8mzinwb1jgv4cfo67t2vd2clyyd','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5vSI:jJvAW6kxxh34roKuOqBft3IO4p-EsCM9iQGHqT6uHHw','2026-04-10 00:53:42.214401'),('sto8knvym495ntntdc1w5uyi8tkp1vno','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5uia:6D23FwvDOu6w1jxCKfD5mU1TyE_vZSd_eOAYqOSNXcw','2026-04-10 00:06:28.974288'),('t0sh99f90rvv76v8tcvf3qtj46ba07hs','.eJxVyzEOwjAMheG7ZEYRdpzEYeQOzJHTpEqFBFXdToi7Q6UOsH7v_S-TZVt73rQtearmYsicfq3IcG-PfZB5Vrub2gPV3nSTZXpej9Nf2UX7N2scwHFxWCthauwjAHvvUnGJYwVGSIKF6AwVAgGCx5hIhiBuHBHM-wMJ-zO-:1w5eT0:BgSpZEAFcN_HXAQlkJOlVNwpdUH7_zfHA5IybgNX1ts','2026-04-09 06:45:18.506513'),('tcmgio1mvlm7001jg7i3kf7d2e3q9rn5','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5tyc:WwyiRRHMEHz5U_Oouna2575ikV7y8kWmA557-XHTMcA','2026-04-09 23:18:58.976922'),('tdwqctdd8pad4nek9x3oe6gka4kk8d90','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5uwH:jlmuwF5fU_TuS4rWaGhDi--nkke6m_kjTaWTzjeQdKQ','2026-04-10 00:20:37.404892'),('thhu1b4dkovkr1hpwjmigfdnqazt6sxw','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5tIZ:1EduapwIGuZjId9mK_4aITxeeqPWNtNm8v5i7wldwqQ','2026-04-09 22:35:31.218585'),('tkx91uwuzn4dd8g8ubwx1fdfy8e2shus','.eJxVyzEOwjAMheG7ZEYRdpzEYeQOzJHTpEqFBFXdToi7Q6UOsH7v_S-TZVt73rQtearmYsicfq3IcG-PfZB5Vrub2gPV3nSTZXpej9Nf2UX7N2scwHFxWCthauwjAHvvUnGJYwVGSIKF6AwVAgGCx5hIhiBuHBHM-wMJ-zO-:1w5eQN:rjlrWSYlP9kEm_H0f062CpzDcmPyi2e29qo4hzEeTNM','2026-04-09 06:42:35.979264'),('tneodfaeh8s20wydi32fmfz3kb5ocvee','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5eWK:3S5NnWq99urpgEQDt3qMHsqeGrEpcHonJB8SwW_Nzbk','2026-04-09 06:48:44.849911'),('tqiewzov8j4vydke9mt041qnbjd0h5ig','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5u69:Mv_C0GNnbKL5wnOixhLuWHdDkrm2RTGGIc46WjXzfY0','2026-04-09 23:26:45.929098'),('trvd9cq0z5ma31dyi3xy4vgyl7yphjf9','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5uwH:jlmuwF5fU_TuS4rWaGhDi--nkke6m_kjTaWTzjeQdKQ','2026-04-10 00:20:37.225142'),('ttdzle7ezt8002o8zhx5nki5ghhu7l6b','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5w0w:QzLbEUFGgssmYjsqGCIGBD7FtO0fgcx8R-jG3D9xYPg','2026-04-10 01:29:30.434270'),('u2qhqf0g14e2ynuw8cxsfz5gq5igaf8m','eyJvYXV0aF9nb29nbGVfc3RhdGUiOiJVWC05YUh3NFBrUFRISmNTMkZ6NmlmS3Y3TEFHREZ0TSJ9:1w5veg:TyBJTgVLsJ69AuUR0dGRbaf0EkKOmGWQm8A7oEH-a14','2026-04-10 01:06:30.384764'),('u32c5blmq4oeecm1sg5aitmgklcdtrh2','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5tIZ:E0yk5pqYFcCF-fh_p17YIQptqTxz_2lFBS6AbCwzKtA','2026-04-09 22:35:31.044476'),('ualp6y0o408kq6u77z4hdk3338gjljkd','.eJxVyzEOwjAMheG7ZEYRdpzEYeQOzJHTpEqFBFXdToi7Q6UOsH7v_S-TZVt73rQtearmYsicfq3IcG-PfZB5Vrub2gPV3nSTZXpej9Nf2UX7N2scwHFxWCthauwjAHvvUnGJYwVGSIKF6AwVAgGCx5hIhiBuHBHM-wMJ-zO-:1w5sJq:y94fgcBdzV6IIdnnFjIwAlRo2cMMQkPh2G6o-urbZ_4','2026-04-09 21:32:46.391460'),('ucq4uomuf4kzsxqvlasqxjzmbva89lvs','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5vSI:BZH2dejptf4mKZGqVyeIz0wGLGYCSpOtJg3q4lnCZxI','2026-04-10 00:53:42.228337'),('uokc1glpfz2ilzt56vod4ypwr1inp5sb','.eJxVyzEOwjAMheG7ZEYRdpzEYeQOzJHTpEqFBFXdToi7Q6UOsH7v_S-TZVt73rQtearmYsicfq3IcG-PfZB5Vrub2gPV3nSTZXpej9Nf2UX7N2scwHFxWCthauwjAHvvUnGJYwVGSIKF6AwVAgGCx5hIhiBuHBHM-wMJ-zO-:1w5sJp:eEPmhi8N7TiEnvcPCSXmtFToDmpcEKEZUNFQ2B6WOn4','2026-04-09 21:32:45.723836'),('uottfqdhu0xore81mrotsrol6ycg0qe8','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5uqu:yrvqDqsPU8R623HbkbfRo8KJzDo0Nu_aMRtx4uoov28','2026-04-10 00:15:04.741137'),('uta33bglubsj4poolg0p3khkgzb1t3ba','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5uqu:MpxFWhgxB0FLsgPL6edhZtR1iQwLkCe_L3UYXfozdAQ','2026-04-10 00:15:04.883142'),('utw0stqvdvyamndtnp4jicqu2duimawb','.eJxVi70OwiAQgN-F2ZA7wQKOvoMzOQ4IjYk2PZmM796SdND1-_moSP3dYpeyxjmrqzLq9MsS8aM8h6BlET2Y6AOKvkundX7djujvbCRtbJVzcWgoWaoWIWTAGnzgGpAncMQWE-yNd2f2k6m-XBKBD4DsMoP6bmw4NiQ:1w5e87:TYRqeSzR62zFM3PtJmg2G5uspbGlIHPXAdFmnDOW6sc','2026-04-09 06:23:43.254696'),('uvyve0oimib8zgv5anu23bpwpn0q7fsj','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5uwH:ioIK5jEPcuhV1J1z-WHmDjDdnBiKwmrZCnjQATPvhWI','2026-04-10 00:20:37.236387'),('uwc25ue3kcy2l85ascxbt2yt0wgpe3sn','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5tyc:Na-h_rlcOHZfLb5u9YTy5rqDM3ZElYyO3AuYagFt3ZM','2026-04-09 23:18:58.946328'),('uytk96tll2fpytjtce43zwe9nkjpwmo4','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5uqv:i4S-uzNXee-ylNVCqGUCq2UtPz00wGXJBHYJj5-kZIY','2026-04-10 00:15:05.037717'),('v6k6h1yfr04b3r3tptkvhcs7dly3ygqq','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5w0v:cw-CLB_T4UtrvYkO_aRR2_XJvm3UdGKsPVmUTcpv1ZQ','2026-04-10 01:29:29.721687'),('vim3yebcbe65u615kgc7g9iczqz5f7nl','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5u69:8mrMpuaMyoKaBPUxhb5e6HvJD7AqA9OE0fh9oAvPy6Y','2026-04-09 23:26:45.539885'),('vm0zby6nsr40eiyi96rbvecdf162lmak','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5tyc:RlSwUgxxg2DzFfLA6AKNyS35bbndKsf6sxSp2FYt0BE','2026-04-09 23:18:58.962873'),('vz60mnrczgqg583ndy84phbcugci2p3a','.eJxVi70OwiAQgN-F2ZA7wQKOvoMzOQ4IjYk2PZmM796SdND1-_moSP3dYpeyxjmrqzLq9MsS8aM8h6BlET2Y6AOKvkundX7djujvbCRtbJVzcWgoWaoWIWTAGnzgGpAncMQWE-yNd2f2k6m-XBKBD4DsMoP6bmw4NiQ:1w5e9M:Jr8gfxmZ3DSHOHCXDpHsxwBdiJY9jk7El8zYFrs174w','2026-04-09 06:25:00.542299'),('w56f6q4e7vx6krceb0f00xoegj3otwdi','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5uSl:1wZPHjS1KHc4BT5IVIA2VWy-UIMyXFLSO7zqRrpDZpA','2026-04-09 23:50:07.241553'),('wbq4za5d2cv6ix2h9cmj9h5f7ccsqgo0','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5w0w:Ice3rGPwSh0JmRq42LLxZQYZO6DIwRU6ztMcron3JyY','2026-04-10 01:29:30.108070'),('wesu9zpwg2g61nbqpk466x3mkrt8j2rk','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5uwH:ioIK5jEPcuhV1J1z-WHmDjDdnBiKwmrZCnjQATPvhWI','2026-04-10 00:20:37.168355'),('wi8veo9v69a30dm8dck0z65m4h0tsu8u','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5w0w:QzLbEUFGgssmYjsqGCIGBD7FtO0fgcx8R-jG3D9xYPg','2026-04-10 01:29:30.589741'),('wnhaf8xlzcvia42jodguocwdbuauviux','.eJxVy0EOwiAQheG7sDaEDgwwLr2DazLQITQm2hRZGe-uTbrQ7ffe_1KJx7Ol0WVLy6zOalKnX8tcbnLfB17Xrnfr-sCur33wtjwux-mvbNzbN7PoI5pQiURsJrAlO8o1QvEzcTZewJAHCLWIw2oRAyFHcX6yBjyo9wc02zSW:1w5dt2:GpVazuOO-ktAzj5mBLHXX1gSyVMZ7P3UH4DMyx0AU0Y','2026-04-09 06:08:08.109306'),('wnwaad0ypbetzakk5eurcwsrpsuw0hda','.eJxVy0EOwiAQheG7sDaEDgwwLr2DazLQITQm2hRZGe-uTbrQ7ffe_1KJx7Ol0WVLy6zOalKnX8tcbnLfB17Xrnfr-sCur33wtjwux-mvbNzbN7PoI5pQiURsJrAlO8o1QvEzcTZewJAHCLWIw2oRAyFHcX6yBjyo9wc02zSW:1w5dvP:GUEAgU_xqQN4U7w-TZi1L-TNHdIlD1svQjXCZPFw4Io','2026-04-09 06:10:35.673924'),('wo9x4thiri5w5zyau8qwd6mgmsenw8nq','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5vSH:oCMKPevBF5jROi4lnkTfIOFK3ee3qMrmLJqCgh0eCZw','2026-04-10 00:53:41.394546'),('xch89pmpn39w41i12hqq88egkkopjsot','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5tyc:Na-h_rlcOHZfLb5u9YTy5rqDM3ZElYyO3AuYagFt3ZM','2026-04-09 23:18:58.561901'),('xhywds1z3d6bjx8sw6jjmigqgc7zpabz','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5uqu:Ag8dNd0Nj7Kx9XeDP9TXOQWgrP8vs8VsGUMDDqAFEME','2026-04-10 00:15:04.108560'),('xiuckwwfnaljchh47t2pqh1872kdjr0p','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5tIZ:E0yk5pqYFcCF-fh_p17YIQptqTxz_2lFBS6AbCwzKtA','2026-04-09 22:35:31.578979'),('xjj5utahjjzrg3j7077ehjkmhuq1ub8j','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5uqv:ALfImCAkMkmrCPekvavZPO5fK1-5gJuxaJi6bBvr0l8','2026-04-10 00:15:05.026709'),('xlcg2nykjaqrcix5rwmd752h380aak5w','.eJxVyzEOwjAMheG7ZEYRdpzEYeQOzJHTpEqFBFXdToi7Q6UOsH7v_S-TZVt73rQtearmYsicfq3IcG-PfZB5Vrub2gPV3nSTZXpej9Nf2UX7N2scwHFxWCthauwjAHvvUnGJYwVGSIKF6AwVAgGCx5hIhiBuHBHM-wMJ-zO-:1w5eEi:vJ0Ww-Mou2bnH01dMWGW9J-45p8BKVXlJ5rDtYXqrng','2026-04-09 06:30:32.861651'),('xlkck6tsdey1tse7pc5oh9mewavvzecg','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5uwH:ob0-jczUAE3ZrLJGCOCFAngt29k6KqJNRNhIWl7q_Fc','2026-04-10 00:20:37.428647'),('xqy3mm0jzh7mj87hfnlowstw9nbvv7z8','.eJxVyzEOwjAMheG7ZEYRdpzEYeQOzJHTpEqFBFXdToi7Q6UOsH7v_S-TZVt73rQtearmYsicfq3IcG-PfZB5Vrub2gPV3nSTZXpej9Nf2UX7N2scwHFxWCthauwjAHvvUnGJYwVGSIKF6AwVAgGCx5hIhiBuHBHM-wMJ-zO-:1w5sJp:eEPmhi8N7TiEnvcPCSXmtFToDmpcEKEZUNFQ2B6WOn4','2026-04-09 21:32:45.902637'),('xrx2n7qo4jo5dzfz9wisclsjyu7l8c0y','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5w0w:Ice3rGPwSh0JmRq42LLxZQYZO6DIwRU6ztMcron3JyY','2026-04-10 01:29:30.782775'),('xstw27d7d5jgv3h7uyjkc82ufz16wciw','e30:1w5vzn:9bz_40TmpRSXPIgVacFucP4FxgS3hyHZBGvou2iQOpo','2026-04-10 01:28:19.118142'),('xvhpou5qv0n64iscd8igc5va063046wh','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5w0w:Ice3rGPwSh0JmRq42LLxZQYZO6DIwRU6ztMcron3JyY','2026-04-10 01:29:30.576291'),('xxlc80doqza1xk1nzjplw8ppku4kyupg','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5tIZ:xW3iqm8nohtl6Qs5FT0vKrWXULNhbUJe9uTWwulxHIo','2026-04-09 22:35:31.600600'),('y2op49hzdqv825bb5ujghjgcqzme63lf','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5tIZ:1EduapwIGuZjId9mK_4aITxeeqPWNtNm8v5i7wldwqQ','2026-04-09 22:35:31.084177'),('y309fv5vfou90o9194ipv5lmq9n7a7w3','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5uqu:yrvqDqsPU8R623HbkbfRo8KJzDo0Nu_aMRtx4uoov28','2026-04-10 00:15:04.896212'),('y8uj0cxfq6s6sc07w27bjzlt0hecvw71','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5uia:-0n5vb54ufipmHjmfaiyNR962MPeKbLABr0MvnZOqzo','2026-04-10 00:06:28.902676'),('y998wp6yd0a6auron62nmat70675d2bu','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5tyc:WwyiRRHMEHz5U_Oouna2575ikV7y8kWmA557-XHTMcA','2026-04-09 23:18:58.599127'),('yaedwve12jkq4qd9b3kzhzgg34j4oinj','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5uSk:VmlEbznN1VBOUk5zZKWYFWF1DfIa6CwM2s8d3Mh-gGM','2026-04-09 23:50:06.399166'),('yfacl6hs175n4lz0j2wavg5dsi3698i8','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5tyb:ll512YrUK2g3UQKW0EINWJMA2QADWzwS3fp9uMHCbRY','2026-04-09 23:18:57.861034'),('yl66j9f4v8f81xvx6nk843uka7o7qf61','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5u69:8mrMpuaMyoKaBPUxhb5e6HvJD7AqA9OE0fh9oAvPy6Y','2026-04-09 23:26:45.836339'),('ynj6onk8s9awhvekzzmzyw6tmfa3onrh','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5uqu:MpxFWhgxB0FLsgPL6edhZtR1iQwLkCe_L3UYXfozdAQ','2026-04-10 00:15:04.818964'),('yo96z4zq2g06gtfguyuks461nrg1jyrv','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5uwG:G4ZzGoIZhSxC6_pYVldjMI6lm8nq2WKznNBO9ZPdv6k','2026-04-10 00:20:36.796143'),('yyaxuw7pv3fq2ws1hhwo9xspdzn6s73k','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5uwH:ob0-jczUAE3ZrLJGCOCFAngt29k6KqJNRNhIWl7q_Fc','2026-04-10 00:20:37.182064'),('z23ueugel189tbai3y4r7c8v5dtlxh21','.eJxVyzEOwjAMheG7ZEYVIXGcMnIH5sixE6VCgqpuJsTdaaUOsH7v_W-TqK8tdS1LmsRczcWcfi0TP8pzH2ieddhNhwN1uGunZXrdjtNf2UjblkmoeHZSC9lcXIjkMroIYkdCxFg3YrDgPIiTiNEjj4GtL-w5AGTz-QJI8jUs:1w5uSl:1wZPHjS1KHc4BT5IVIA2VWy-UIMyXFLSO7zqRrpDZpA','2026-04-09 23:50:07.333770'),('z40gbvlwgjc2c3gfmvd0nnyyqruqf983','.eJxVizsOAjEMBe-SGkU4ZvOh5A7UkTd2lBUSrNakQtwdIm0Bet28mZfJ1J8td5UtL2zOBszhl81UbnIfB62r2sHU7lDtVTtty-OyS39lI23fbI7iITrPCQtVRz5MhWNwInRE8CWwdzwR4gmQgEpNnARhrCZJYt4fTR41mw:1w5w0w:Ice3rGPwSh0JmRq42LLxZQYZO6DIwRU6ztMcron3JyY','2026-04-10 01:29:30.424865'),('zj2iq79nk761dwrkzbyu8nlspbx6st7j','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5tyc:WwyiRRHMEHz5U_Oouna2575ikV7y8kWmA557-XHTMcA','2026-04-09 23:18:58.795149'),('zq3j9ler64i1ckbwt0g3ezga5vj0kvb6','.eJxVy8sOwiAQheF3YW2aAaZcXPoOrsnADKEx0abIyvju2qQL3X7n_C-VaDxbGl22tLA6K1SnX8tUbnLfB1rXPu3WpwP7dO2DtuVxOU5_ZaPevhlk0bEgFaiAzjA68Gi4hgLOFowVbBCbrXZ1lhkiYTAsXjiz81aLen8AP6A1Zw:1w5uib:Vl3nElbMyuN6X9sZOhXQoVSAkiMWklW_wToXQaMVLoI','2026-04-10 00:06:29.065488'),('zrmi2e3t5tw41xqolwnj9ij3jq6vlam5','.eJxVyzEOwjAMheG7ZEYRdpzEYeQOzJHTpEqFBFXdToi7Q6UOsH7v_S-TZVt73rQtearmYsicfq3IcG-PfZB5Vrub2gPV3nSTZXpej9Nf2UX7N2scwHFxWCthauwjAHvvUnGJYwVGSIKF6AwVAgGCx5hIhiBuHBHM-wMJ-zO-:1w5sJp:eEPmhi8N7TiEnvcPCSXmtFToDmpcEKEZUNFQ2B6WOn4','2026-04-09 21:32:45.138121');
/*!40000 ALTER TABLE `django_session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inscripciones`
--

DROP TABLE IF EXISTS `inscripciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inscripciones` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `enrolled_at` datetime(6) NOT NULL,
  `course_id` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `inscripciones_user_id_course_id_59975708_uniq` (`user_id`,`course_id`),
  KEY `inscripciones_course_id_0053714d_fk_cursos_course_id` (`course_id`),
  CONSTRAINT `inscripciones_course_id_0053714d_fk_cursos_course_id` FOREIGN KEY (`course_id`) REFERENCES `cursos` (`course_id`),
  CONSTRAINT `inscripciones_user_id_22b6a55d_fk_usuarios_user_id` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inscripciones`
--

LOCK TABLES `inscripciones` WRITE;
/*!40000 ALTER TABLE `inscripciones` DISABLE KEYS */;
INSERT INTO `inscripciones` VALUES (1,'2026-03-27 00:02:57.250465',8,12),(2,'2026-04-01 16:50:52.522488',6,22),(3,'2026-04-06 20:03:30.503175',6,32),(4,'2026-04-13 23:50:42.793634',8,22),(5,'2026-04-28 20:55:57.057856',8,33),(7,'2026-04-30 14:22:32.404431',8,39);
/*!40000 ALTER TABLE `inscripciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lecciones`
--

DROP TABLE IF EXISTS `lecciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lecciones` (
  `lesson_id` int NOT NULL AUTO_INCREMENT,
  `course_id` int NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `contenido` text,
  `codigo_ejemplo` text,
  `orden` int DEFAULT NULL,
  `estado` enum('pendiente','aprobada','rechazada') DEFAULT 'pendiente',
  PRIMARY KEY (`lesson_id`),
  KEY `fk_lecciones_cursos` (`course_id`),
  CONSTRAINT `fk_lecciones_cursos` FOREIGN KEY (`course_id`) REFERENCES `cursos` (`course_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lecciones`
--

LOCK TABLES `lecciones` WRITE;
/*!40000 ALTER TABLE `lecciones` DISABLE KEYS */;
INSERT INTO `lecciones` VALUES (13,8,'Introducción a Java y tu primer programa','Java es un lenguaje de programación muy popular que se utiliza para crear aplicaciones, páginas web, juegos y mucho más.\r\n\r\nEn esta lección aprenderás:\r\n\r\nQué es Java\r\nCómo funciona un programa en Java\r\nCómo escribir tu primer código\r\n\r\nUn programa en Java siempre empieza con una estructura básica llamada clase principal.\r\n\r\nLa parte más importante es el método main, ya que es donde inicia la ejecución del programa.\r\n\r\nTu primer programa será mostrar un mensaje en pantalla usando System.out.println().','public class Main {\r\n    public static void main(String[] args) {\r\n        System.out.println(\"Hola Mundo\");\r\n    }\r\n}',1,'aprobada'),(15,8,'Variables y tipos de datos','Las variables son espacios donde podemos guardar información dentro de un programa.\r\n\r\nEn Java existen diferentes tipos de datos:\r\n\r\nint: números enteros (ej: 10, 25)\r\ndouble: números decimales (ej: 3.14)\r\nString: texto (ej: \"Hola\")\r\nboolean: verdadero o falso (true / false)\r\n\r\nPara crear una variable usamos esta estructura:\r\n\r\ntipo nombre = valor;\r\n\r\nEjemplo:\r\n\r\nint edad = 20;\r\nString nombre = \"Ana\";\r\n\r\nLas variables nos permiten trabajar con datos y mostrarlos en pantalla.','public class Main {\r\n    public static void main(String[] args) {\r\n        int edad = 20;\r\n        String nombre = \"Ana\";\r\n\r\n        System.out.println(\"Nombre: \" + nombre);\r\n        System.out.println(\"Edad: \" + edad);\r\n    }\r\n}',2,'aprobada'),(16,6,'Introducción a Python y tu primer programa','Python es un lenguaje de programación muy fácil de aprender y muy utilizado en el mundo.\r\n\r\nSe usa para:\r\n\r\nCrear páginas web\r\nInteligencia artificial\r\nAutomatización de tareas\r\nDesarrollo de software\r\n\r\nEn Python no necesitas escribir mucho código para empezar. Tu primer programa será mostrar un mensaje en pantalla usando print().','print(\"Hola Mundo\")',1,'aprobada'),(17,6,'Variables y tipos de datos','Las variables nos permiten guardar información en un programa.\r\n\r\nEn Python no necesitas definir el tipo de dato, el lenguaje lo detecta automáticamente.\r\n\r\nTipos de datos básicos:\r\n\r\nint → números enteros (10, 20)\r\nfloat → números decimales (3.5)\r\nstr → texto (\"Hola\")\r\nbool → verdadero o falso (True / False)\r\n\r\nEjemplo de variables:\r\n\r\nnombre = \"Juan\"\r\nedad = 20','nombre = \"Ana\"\r\nedad = 21\r\n\r\nprint(nombre)\r\nprint(edad)',2,'aprobada'),(18,6,'Condicionales (if, else)','Las condicionales permiten que el programa tome decisiones.\r\n\r\nSe usan palabras clave como:\r\n\r\nif → si se cumple una condición\r\nelse → si no se cumple\r\n\r\nEjemplo: verificar si una persona es mayor de edad.','edad = 18\r\n\r\nif edad >= 18:\r\n    print(\"Eres mayor de edad\")\r\nelse:\r\n    print(\"Eres menor de edad\")',3,'aprobada');
/*!40000 ALTER TABLE `lecciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lenguajes`
--

DROP TABLE IF EXISTS `lenguajes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lenguajes` (
  `language_id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text,
  PRIMARY KEY (`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lenguajes`
--

LOCK TABLES `lenguajes` WRITE;
/*!40000 ALTER TABLE `lenguajes` DISABLE KEYS */;
INSERT INTO `lenguajes` VALUES (1,'Python','Lenguaje versatil para backend, data y automatizacion.'),(2,'JavaScript','Lenguaje principal para web interactiva y aplicaciones modernas.'),(3,'Java','Lenguaje orientado a objetos para aplicaciones empresariales.'),(4,'SQL','Lenguaje estandar para consultas y administracion de datos.');
/*!40000 ALTER TABLE `lenguajes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logros`
--

DROP TABLE IF EXISTS `logros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `logros` (
  `achievement_id` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `icono` varchar(50) DEFAULT NULL,
  `puntos_requeridos` int DEFAULT NULL,
  `tipo` enum('lecciones','desafios','racha','especial') DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`achievement_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logros`
--

LOCK TABLES `logros` WRITE;
/*!40000 ALTER TABLE `logros` DISABLE KEYS */;
INSERT INTO `logros` VALUES (1,'Primera Lección','Completa tu primera lección','?',1,'lecciones',1,'2025-10-02 07:14:21'),(2,'Estudiante Dedicado','Completa 5 lecciones','?',5,'lecciones',1,'2025-10-02 07:14:21'),(3,'Experto en Programación','Completa 20 lecciones','?',20,'lecciones',1,'2025-10-02 07:14:21'),(4,'Primer Desafío','Completa tu primer desafío de código','⚡',1,'desafios',1,'2025-10-02 07:14:21'),(5,'Solucionador de Problemas','Completa 10 desafíos','?',10,'desafios',1,'2025-10-02 07:14:21'),(6,'Racha de 3 Días','Mantén una racha de aprendizaje de 3 días consecutivos','?',3,'racha',1,'2025-10-02 07:14:21'),(7,'Racha de 7 Días','Mantén una racha de aprendizaje de 7 días consecutivos','?',7,'racha',1,'2025-10-02 07:14:21'),(8,'Bienvenido a SlyCipher','Completa tu perfil y comienza tu aventura de aprendizaje','?',0,'especial',1,'2025-10-02 07:14:21'),(9,'Primeros pasos','Completa tu primera leccion.','fa-seedling',1,'lecciones',1,'2026-03-29 04:54:59'),(10,'Constancia inicial','Mantiene una racha de 3 dias.','fa-fire',3,'racha',1,'2026-03-29 04:54:59'),(11,'Cazador de desafios','Resuelve 5 desafios correctamente.','fa-bullseye',5,'desafios',1,'2026-03-29 04:54:59'),(12,'Explorador Slycipher','Completa hitos clave del onboarding.','fa-compass',10,'especial',1,'2026-03-29 04:54:59');
/*!40000 ALTER TABLE `logros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logros_usuarios`
--

DROP TABLE IF EXISTS `logros_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `logros_usuarios` (
  `user_achievement_id` bigint NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `achievement_id` bigint NOT NULL,
  `desbloqueado_en` datetime DEFAULT NULL,
  PRIMARY KEY (`user_achievement_id`),
  KEY `fk_logros_usuarios_user` (`user_id`),
  KEY `fk_logros_usuarios_achievement` (`achievement_id`),
  CONSTRAINT `fk_logros_usuarios_achievement` FOREIGN KEY (`achievement_id`) REFERENCES `logros` (`achievement_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_logros_usuarios_user` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logros_usuarios`
--

LOCK TABLES `logros_usuarios` WRITE;
/*!40000 ALTER TABLE `logros_usuarios` DISABLE KEYS */;
/*!40000 ALTER TABLE `logros_usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profiles`
--

DROP TABLE IF EXISTS `profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profiles` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `tipo_documento` varchar(10) NOT NULL,
  `numero_documento` varchar(50) NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `profiles_user_id_36580373_fk_usuarios_user_id` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profiles`
--

LOCK TABLES `profiles` WRITE;
/*!40000 ALTER TABLE `profiles` DISABLE KEYS */;
INSERT INTO `profiles` VALUES (1,'','',1),(2,'','',10),(4,'CC','1030380318',12),(6,'','',4),(15,'','',22),(16,'','',23),(20,'','',27),(22,'','',29),(24,'CC','12233455',31),(25,'TI','1025330410',32),(26,'CC','190238049',33),(27,'CC','5287865',34),(29,'CC','4543555',36),(30,'CC','684684685',37),(32,'CC','5637738',39);
/*!40000 ALTER TABLE `profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `progreso_usuarios`
--

DROP TABLE IF EXISTS `progreso_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `progreso_usuarios` (
  `progress_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `lesson_id` int NOT NULL,
  `estado` enum('en_progreso','completado') DEFAULT 'en_progreso',
  `completado_en` datetime DEFAULT NULL,
  `puntaje` float DEFAULT '0',
  PRIMARY KEY (`progress_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_lesson_id` (`lesson_id`),
  CONSTRAINT `fk_progreso_leccion` FOREIGN KEY (`lesson_id`) REFERENCES `lecciones` (`lesson_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_progreso_usuario` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `progreso_usuarios`
--

LOCK TABLES `progreso_usuarios` WRITE;
/*!40000 ALTER TABLE `progreso_usuarios` DISABLE KEYS */;
INSERT INTO `progreso_usuarios` VALUES (13,12,13,'completado','2026-03-27 00:07:49',100),(14,12,15,'completado','2026-03-27 00:08:11',100),(19,22,16,'completado','2026-04-01 16:51:22',100),(20,22,18,'completado','2026-04-01 16:51:40',100),(21,32,16,'completado','2026-04-06 20:04:12',100),(22,32,18,'completado','2026-04-06 20:05:28',100),(23,22,13,'completado','2026-04-13 23:50:47',100),(24,22,15,'completado','2026-04-13 23:50:53',100),(25,22,17,'completado','2026-04-14 00:09:19',100),(26,33,13,'completado','2026-04-28 20:56:24',100),(27,33,15,'completado','2026-04-28 20:57:35',100),(30,39,13,'completado','2026-04-30 14:23:48',100);
/*!40000 ALTER TABLE `progreso_usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `rol` enum('admin','estudiante','desarrollador') NOT NULL,
  `creado_en` datetime DEFAULT CURRENT_TIMESTAMP,
  `ultimo_login` datetime DEFAULT NULL,
  `estado` tinyint(1) DEFAULT '1',
  `activo` tinyint(1) DEFAULT '1',
  `racha` int DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'admin1@example.com','Juan','Lopez','admin1@example.com',NULL,'bcrypt$$2b$12$6XDEWLMHjObCMPMlDjnjeOCjZMwuFlxl8oCPeIcw5wlxncR/yUn16','admin','2025-09-24 21:56:17','2026-05-02 23:33:20',1,1,3),(2,'Camilo Gutierrez','Camilo','Gutierrez','est1@example.com',NULL,'$2y$12$6buhGIXDfY4M47E1fxmKFe8nMMNqQj3I6SSXqYZsEdKrLAKnEVnp6','estudiante','2025-09-24 21:56:17','2026-03-27 01:29:31',1,1,7),(3,'Sandra Gomez','Sandra','Gomez','est2@example.com',NULL,'bcrypt$$2b$12$Tefe6X./rwnUGY/U6oB.HOx2TV9ZfAZMM99tzBuhxUULAkyoeNlQG','estudiante','2025-09-24 21:56:17','2026-03-26 06:53:47',1,1,5),(4,'dev1@example.com','Alejandro','Torres','dev1@example.com','2003-02-28','bcrypt$$2b$12$0YASyZ4OlIJo7MtQwv62TeznomXEi0yZS7Guoey59Ck3m0RC7BtYC','desarrollador','2025-09-24 21:56:17','2026-04-30 17:35:51',1,1,2),(8,'norma@gmail.com','Norma','Solano','norma@gmail.com',NULL,'$2y$12$jrhepgkh6pjqmyq8k2P2se2MbLLM4BNicx.rMbzotAiJ1wFyp7vQ6','estudiante','2025-10-02 13:29:48',NULL,1,1,0),(10,'dianadelgado','Lizeth','Delgado','dianadelgado@gmail.com','1990-12-29','$2y$12$lOVGC3.c9YlEtcQbyIcQ/uM1sTgtoGStyJky678V28ieqFUvaxHLG','estudiante','2025-10-02 19:54:35',NULL,1,1,0),(12,'carla@gmail.com','Carla','Lopez','carla@gmail.com','2015-12-07','bcrypt$$2b$12$irBuMuvrnmqOqxHveJh8Mu85bBJCyBGrvNul1lNrL/4hUXtGumf7K','estudiante','2026-03-26 21:51:37','2026-03-27 00:02:34',1,1,0),(22,'johissolano4@gmail.com','Lesly Johana','Barbosa Solano','johissolano4@gmail.com',NULL,'bcrypt$$2b$12$/U.FBEr1HhXqaIzf2X9smejGgDfQypUMn5wpuGj2f3wzl8rjQPVJe','estudiante','2026-03-27 01:22:15','2026-04-30 18:24:36',1,1,0),(23,'michib0504@gmail.com','Michel','Barbosa','michib0504@gmail.com',NULL,'bcrypt$$2b$12$Xb.uLHRL1477RT/OvIq9TOTM4FCsy3a.vVW5DipJyPr1lpWyDNNf6','estudiante','2026-03-27 01:22:46','2026-03-27 01:22:46',1,1,0),(27,'michelbarbosa0504@gmail.com','Michel','Barbosa','michelbarbosa0504@gmail.com',NULL,'bcrypt$$2b$12$vJP82BovMuB3dgeK7BVO9enZFZls.56NyQCvQFl183hz7.QFGUjky','estudiante','2026-03-27 01:30:28','2026-03-27 01:30:35',1,1,0),(29,'elportaldeadonai@gmail.com','el portal','de Adonai','elportaldeadonai@gmail.com',NULL,'bcrypt$$2b$12$bZ4exIZvcmOrc7Gd7yacJOJ.GaP5KHFc8FMr3hiXbL5kIN47YiSFe','estudiante','2026-04-01 16:34:42','2026-04-01 16:34:51',1,1,0),(31,'liesel@gmail.com','Liesel','Clavijo','liesel@gmail.com','1998-01-06','bcrypt$$2b$12$gU3EinnBKd9x0ovsJ5zHkeL1C73KM/vxtkODLpB29IfO2RPItUnzi','estudiante','2026-04-01 19:37:01','2026-04-01 19:37:18',1,1,0),(32,'dilang24@hotmail.com','Dilan','Gonzales','dilang24@hotmail.com','2015-06-15','bcrypt$$2b$12$o2vhPdee9JKWeCPr5ngeg.buvU4p6N5HOd6XfBrf6LFB5Qaa47zlG','estudiante','2026-04-06 20:00:04','2026-04-06 20:02:57',1,1,0),(33,'bronio@gmail.com','Bronio','Marrugo','bronio@gmail.com','2003-01-28','bcrypt$$2b$12$.nfunjg8M60DSIJLCu0TK.lRUSI/.ZiVNzPkiKFL/0MQxBhLhPlFS','estudiante','2026-04-28 20:54:47','2026-04-28 20:55:20',1,1,0),(34,'nubia@gmail.com','Nubia','Pava','nubia@gmail.com','1977-02-28','bcrypt$$2b$12$K43eF/YfBp6BnC3pv8nh.uvCTX.H4FqZx/2HMaJFgQvdv.sSd0pPW','estudiante','2026-04-28 21:07:28',NULL,1,1,0),(36,'bernanda@gmail.com','Bernanda','Alvarez','bernanda@gmail.com','2015-12-14','bcrypt$$2b$12$0PKhkwYo8wnF91b61lRB6e.fIM7xEVyMtDB2tJtpsFZV2TBO9VnkK','estudiante','2026-04-28 21:28:27',NULL,1,1,0),(37,'fernanda@gmail.com','Fernanda','Gonzales','fernanda@gmail.com','2003-02-04','bcrypt$$2b$12$o2WNGURKJ1qroDsd0IXlQOHJII8vaeNBFfA.RMR6D8/gGirp1W.ke','estudiante','2026-04-28 21:53:36','2026-04-30 21:21:05',1,1,0),(39,'juanita@gmail.com','Juanita','Valderrama','juanita@gmail.com','1996-01-31','bcrypt$$2b$12$ZkPUDsAMJaOIZIZMh2l8nOxOXMSq8DdaOHrdKGN6mL0zmONq5yE6y','estudiante','2026-04-30 14:17:41','2026-04-30 14:19:05',1,1,0);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-02 19:54:48
