-- ================================
-- 1. Creación de la base de datos
-- ================================
CREATE DATABASE IF NOT EXISTS lacanchadelsaber
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

USE lacanchadelsaber;

-- ============================
-- 2. Tabla usuarios
-- ============================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nick VARCHAR(20) NOT NULL UNIQUE,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    apellido1 VARCHAR(50),
    apellido2 VARCHAR(50),
    nacionalidad VARCHAR(50),
    avatar_url VARCHAR(255),
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ============================
-- 3. Tabla roles
-- ============================
CREATE TABLE role_names (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_rol VARCHAR(20) NOT NULL UNIQUE
);

INSERT INTO role_names (nombre_rol) VALUES 
    ('ADMIN'),
    ('USER'),
    ('GUEST');

-- ============================
-- 4. Tabla user-rol
-- ============================
CREATE TABLE user_role (
    id_user INT NOT NULL,
    id_role INT NOT NULL,

    PRIMARY KEY (id_user, id_role),

    FOREIGN KEY (id_user)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    FOREIGN KEY (id_role)
        REFERENCES role_names(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

-- ============================
-- 5. Tabla categorias
-- ============================
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO categorias (nombre) VALUES
('Historia'),
('Selecciones'),
('Ligueras');

-- ============================
-- 5. Tabla difucultades
-- ============================
CREATE TABLE dificultades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO dificultades (nombre) VALUES
('Facil'),
('Media'),
('Dificil');


-- ============================
-- 6. Tabla preguntas
-- ============================
CREATE TABLE preguntas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(500) NOT NULL,
    respuesta_correcta CHAR(1) NOT NULL,
    respuesta_A TEXT NOT NULL,
    respuesta_B TEXT NOT NULL,
    respuesta_C TEXT NOT NULL,
    respuesta_D TEXT NOT NULL,
    categoria_id INT NOT NULL,
    autor_id INT NOT NULL,
    validada_por INT,
    dificultad_id INT NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    validada BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (autor_id) REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    FOREIGN KEY (dificultad_id) REFERENCES dificultades(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    FOREIGN KEY (validada_por) REFERENCES users(id)
        ON UPDATE CASCADE 
        ON DELETE SET NULL
);

-- ============================
-- 7. Tabla partidas
-- ============================
CREATE TABLE partidas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    puntuacion INT DEFAULT 0,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    dificultad_id INT NOT NULL,

    FOREIGN KEY (id_user)
        REFERENCES users(id)
        ON DELETE CASCADE,
    FOREIGN KEY (dificultad_id) REFERENCES dificultades(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

-- ============================
-- 7. Tabla detalles partidas
-- ============================
CREATE TABLE partida_respuestas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_partida INT NOT NULL,
    id_pregunta INT NOT NULL,
    respuesta_dada CHAR(1) NOT NULL,
    es_correcta BOOLEAN NOT NULL,
    FOREIGN KEY (id_partida)  REFERENCES partidas(id)  
        ON DELETE CASCADE,
    FOREIGN KEY (id_pregunta) REFERENCES preguntas(id) 
        ON DELETE CASCADE
);

-- ============================
-- 8. Tabla amigos
-- ============================
CREATE TABLE amistades (
    id_user1 INT NOT NULL,
    id_user2 INT NOT NULL,
    estado ENUM('pendiente', 'aceptada') DEFAULT 'pendiente',
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_user1, id_user2),
    FOREIGN KEY (id_user1) REFERENCES users(id) 
        ON DELETE CASCADE,
    FOREIGN KEY (id_user2) REFERENCES users(id) 
        ON DELETE CASCADE
);

-- ============================
-- ADMINISTRADOR INICIAL
-- ============================
INSERT INTO users (nick, email, password, nombre, apellido1, apellido2, nacionalidad)
VALUES ('daanigp', 'danielgarciapascual23@gmail.com', '123456Aa', 'Daniel', 'Garcia', 'Pascual', 'España');

INSERT INTO user_role (id_user, id_role)
VALUES (1, 1);

-- ============================
-- USUARIOS DE EJEMPLO
-- ============================
INSERT INTO users (nick, email, password, nombre, apellido1, apellido2, nacionalidad) VALUES
('mariamv', 'maria.mv@email.com', '123456Aa', 'María', 'Martínez', 'Vidal', 'España'),
('carlostf', 'carlos.tf@email.com', '123456Aa', 'Carlos', 'Torres', 'Fuentes', 'México'),
('anapg', 'ana.pg@email.com', '123456Aa', 'Ana', 'Pérez', 'García', 'Argentina'),
('luisrb', 'luis.rb@email.com', '123456Aa', 'Luis', 'Romero', 'Blanco', 'España');

-- Roles: mariamv = ADMIN, el resto = USER
INSERT INTO user_role (id_user, id_role) VALUES
(2, 1), -- mariamv → ADMIN
(3, 2), -- carlostf → USER
(4, 2), -- anapg → USER
(5, 2); -- luisrb → USER

-- ============================
-- PREGUNTAS DE EJEMPLO
-- ============================

-- Historia / Difícil
INSERT INTO preguntas (titulo, respuesta_correcta, respuesta_A, respuesta_B, respuesta_C, respuesta_D, categoria_id, autor_id, dificultad_id, validada, validada_por) VALUES
('¿En qué año se celebró el primer Mundial de Fútbol?',
 'B',
 '1924', '1930', '1934', '1926',
 1, 3, 3, TRUE, 2);

-- Historia / Fácil
INSERT INTO preguntas (titulo, respuesta_correcta, respuesta_A, respuesta_B, respuesta_C, respuesta_D, categoria_id, autor_id, dificultad_id, validada, validada_por) VALUES
('¿Qué país organizó el Mundial de 2002 de forma conjunta?',
 'C',
 'China y Japón', 'Corea y China', 'Japón y Corea del Sur', 'India y Japón',
 1, 4, 1, TRUE, 2);

-- Selecciones / Media
INSERT INTO preguntas (titulo, respuesta_correcta, respuesta_A, respuesta_B, respuesta_C, respuesta_D, categoria_id, autor_id, dificultad_id, validada, validada_por) VALUES
('¿Cuántos Mundiales ha ganado Brasil?',
 'D',
 '3', '4', '6', '5',
 2, 3, 2, TRUE, 2);

-- Selecciones / Difícil (sin validar)
INSERT INTO preguntas (titulo, respuesta_correcta, respuesta_A, respuesta_B, respuesta_C, respuesta_D, categoria_id, autor_id, dificultad_id) VALUES
('¿Qué selección eliminó a España en los octavos del Mundial 2022?',
 'A',
 'Marruecos', 'Francia', 'Alemania', 'Portugal',
 2, 5, 1);

-- Ligueras / Media
INSERT INTO preguntas (titulo, respuesta_correcta, respuesta_A, respuesta_B, respuesta_C, respuesta_D, categoria_id, autor_id, dificultad_id, validada, validada_por) VALUES
('¿Quién es el máximo goleador histórico de La Liga española?',
 'B',
 'Cristiano Ronaldo', 'Lionel Messi', 'Hugo Sánchez', 'Raúl González',
 3, 4, 2, TRUE, 1);

-- Ligueras / Fácil
INSERT INTO preguntas (titulo, respuesta_correcta, respuesta_A, respuesta_B, respuesta_C, respuesta_D, categoria_id, autor_id, dificultad_id, validada, validada_por) VALUES
('¿Qué equipo ganó la Champions League en 2022?',
 'C',
 'Liverpool', 'Manchester City', 'Real Madrid', 'PSG',
 3, 5, 1, TRUE, 1);

-- ============================
-- PARTIDAS Y RESPUESTAS
-- ============================
INSERT INTO partidas (id_user, puntuacion, dificultad_id) VALUES
(3, 300, 2),  -- carlostf, dificultad media
(4, 150, 1),  -- anapg, dificultad fácil
(5, 200, 3);  -- luisrb, dificultad difícil

-- Detalle partida 1 (carlostf)
INSERT INTO partida_respuestas (id_partida, id_pregunta, respuesta_dada, es_correcta) VALUES
(1, 1, 'B', TRUE),
(1, 3, 'D', TRUE),
(1, 5, 'A', FALSE);

-- Detalle partida 2 (anapg)
INSERT INTO partida_respuestas (id_partida, id_pregunta, respuesta_dada, es_correcta) VALUES
(2, 2, 'C', TRUE),
(2, 6, 'C', TRUE),
(2, 5, 'B', TRUE);

-- Detalle partida 3 (luisrb)
INSERT INTO partida_respuestas (id_partida, id_pregunta, respuesta_dada, es_correcta) VALUES
(3, 1, 'A', FALSE),
(3, 4, 'A', TRUE),
(3, 3, 'D', TRUE);

-- ============================
-- AMISTADES
-- ============================
INSERT INTO amistades (id_user1, id_user2, estado) VALUES
(3, 4, 'aceptada'),  -- carlostf y anapg son amigos
(3, 5, 'pendiente'), -- carlostf envió solicitud a luisrb
(4, 5, 'aceptada');  -- anapg y luisrb son amigos