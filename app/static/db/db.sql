-- ================================
-- 1. Creación de la base de datos
-- ================================
CREATE DATABASE IF NOT EXISTS lacanchadelsaber
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

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
    titulo VARCHAR(100) NOT NULL,
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
    estado ENUM('pendiente', 'aceptada', 'bloqueada') DEFAULT 'pendiente',
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
VALUES ('daanigp', 'danielgarciapascual23@gmail.com', '1234', 'Daniel', 'Garcia', 'Pascual', 'España');

INSERT INTO user_role (id_user, id_role)
VALUES (1, 1);

-- ============================
--  EJEMPLOS DE DATOS
-- ============================
-- ================================
-- USUARIOS (contraseña: 'password123' hasheada con bcrypt)
-- ================================
INSERT INTO users (nick, email, password, nombre, apellido1, apellido2, nacionalidad, avatar_url) VALUES
('cr7fan',     'carlos@gmail.com',   '1234', 'Carlos',   'Romero',    'López',     'España',     NULL),
('messiforever','leo@gmail.com',     '1234', 'Leonardo', 'Fernández', NULL,        'Argentina',  NULL),
('pedreitor',  'pedro@gmail.com',    '1234', 'Pedro',    'Martínez',  'Gil',       'España',     NULL),
('zlatanadmin','zlatan@gmail.com',   '1234', 'Zlatan',   'Ibrahimović',NULL,       'Suecia',     NULL),
('neymarjr',   'neymar@gmail.com',   '1234', 'Neymar',   'Da Silva',  'Santos',    'Brasil',     NULL),
('modricmagic', 'luka@gmail.com',    '1234', 'Luka',     'Modrić',    NULL,        'Croacia',    NULL),
('xavimaster', 'xavi@gmail.com',     '1234', 'Xavi',     'Hernández', 'Creus',     'España',     NULL);

-- ================================
-- ROLES (ya insertados, solo asignamos)
-- daanigp y zlatanadmin → ADMIN | resto → USER
-- ================================
INSERT INTO user_role (id_user, id_role) VALUES
(2, 2), -- cr7fan      → USER
(3, 2), -- messiforever→ USER
(4, 2), -- pedreitor   → USER
(5, 1), -- zlatanadmin → ADMIN
(6, 2), -- neymarjr    → USER
(7, 2), -- modricmagic → USER
(8, 2); -- xavimaster  → USER

-- ================================
-- PREGUNTAS (mezcla de categorías y dificultades)
-- autor_id 1-8, validadas por admin id 1 o 5
-- ================================
INSERT INTO preguntas (titulo, respuesta_correcta, respuesta_A, respuesta_B, respuesta_C, respuesta_D, categoria_id, autor_id, validada_por, dificultad_id, validada) VALUES

-- Historia / Fácil
('¿En qué año se fundó el FC Barcelona?',
 'B', '1895', '1899', '1902', '1910', 1, 2, 1, 1, TRUE),

('¿Quién marcó el gol de la victoria en la final del Mundial 2010?',
 'A', 'Andrés Iniesta', 'David Villa', 'Fernando Torres', 'Xavi Hernández', 1, 3, 5, 1, TRUE),

('¿Cuántos Mundiales ha ganado Brasil?',
 'C', '4', '3', '5', '6', 1, 4, 1, 1, TRUE),

('¿En qué ciudad se disputó la final del Mundial 2002?',
 'B', 'Tokio', 'Yokohama', 'Seúl', 'Osaka', 1, 6, 5, 2, TRUE),

('¿Qué país organizó el primer Mundial de fútbol en 1930?',
 'D', 'Brasil', 'Argentina', 'Italia', 'Uruguay', 1, 7, 1, 1, TRUE),

-- Selecciones / Media
('¿Cuántos jugadores tiene un equipo de fútbol en el campo?',
 'A', '11', '10', '12', '9', 2, 2, 1, 1, TRUE),

('¿Qué selección ganó la Eurocopa 2021?',
 'C', 'Francia', 'Inglaterra', 'Italia', 'España', 2, 3, 5, 2, TRUE),

('¿Cuántos goles marcó Ronaldo (R9) en el Mundial de 2002?',
 'B', '6', '8', '7', '5', 2, 8, 1, 2, TRUE),

('¿Qué selección tiene más Eurocopas ganadas?',
 'A', 'España', 'Alemania', 'Francia', 'Italia', 2, 4, 5, 2, TRUE),

('¿Qué país ganó el Mundial de 2018?',
 'D', 'Croacia', 'Argentina', 'Brasil', 'Francia', 2, 6, 1, 1, TRUE),

-- Ligueras / Difícil
('¿Cuántas Ligas de Campeones ha ganado el Real Madrid?',
 'C', '12', '13', '15', '14', 3, 7, 5, 3, TRUE),

('¿Qué club ha ganado más veces La Liga española?',
 'A', 'Real Madrid', 'FC Barcelona', 'Atlético de Madrid', 'Athletic Club', 3, 2, 1, 2, TRUE),

('¿En qué temporada el Leicester City ganó la Premier League?',
 'B', '2014-15', '2015-16', '2016-17', '2013-14', 3, 3, 5, 3, TRUE),

('¿Quién fue el máximo goleador de la Champions 2021-22?',
 'A', 'Karim Benzema', 'Robert Lewandowski', 'Kylian Mbappé', 'Cristiano Ronaldo', 3, 8, 1, 3, TRUE),

-- Pendientes de validar (validada = FALSE)
('¿Cuántos balones de oro tiene Messi?',
 'D', '6', '7', '5', '8', 1, 4, NULL, 2, FALSE),

('¿En qué equipo debutó Cristiano Ronaldo profesionalmente?',
 'B', 'Manchester United', 'Sporting de Lisboa', 'Real Madrid', 'Juventus', 1, 6, NULL, 1, FALSE);

-- ================================
-- PARTIDAS
-- ================================
INSERT INTO partidas (id_user, puntuacion, dificultad_id) VALUES
(2, 800, 1),
(2, 650, 2),
(3, 920, 3),
(3, 700, 2),
(4, 450, 1),
(4, 880, 3),
(6, 760, 2),
(7, 990, 3),
(7, 430, 1),
(8, 670, 1),
(8, 820, 2),
(2, 910, 3);

-- ================================
-- PARTIDA_RESPUESTAS (detalle de la partida id=1)
-- ================================
INSERT INTO partida_respuestas (id_partida, id_pregunta, respuesta_dada, es_correcta) VALUES
(1, 1,  'B', TRUE),
(1, 2,  'A', TRUE),
(1, 3,  'B', FALSE),  -- falló esta
(1, 4,  'B', TRUE),
(1, 5,  'D', TRUE);

-- Detalle partida id=3 (messiforever, dificultad alta)
INSERT INTO partida_respuestas (id_partida, id_pregunta, respuesta_dada, es_correcta) VALUES
(3, 11, 'C', TRUE),
(3, 12, 'A', TRUE),
(3, 13, 'B', TRUE),
(3, 14, 'A', TRUE);

-- ================================
-- AMISTADES
-- ================================
INSERT INTO amistades (id_user1, id_user2, estado) VALUES
(2, 3, 'aceptada'),   -- cr7fan       ↔ messiforever
(2, 4, 'aceptada'),   -- cr7fan       ↔ pedreitor
(3, 6, 'aceptada'),   -- messiforever ↔ neymarjr
(7, 8, 'aceptada'),   -- modricmagic  ↔ xavimaster
(4, 6, 'pendiente'),  -- pedreitor   →  neymarjr (sin respuesta)
(6, 7, 'pendiente'),  -- neymarjr    →  modricmagic (sin respuesta)
(8, 2, 'bloqueada');  -- xavimaster  bloqueó a cr7fan


-- ================================================
-- PARTIDA_RESPUESTAS
-- Partidas 1-12, preguntas 1-14 (validadas)
-- Categoría 1=Historia, 2=Selecciones, 3=Ligueras
-- ================================================

-- PARTIDA 1 | user 2 | categoria 1 (Historia) | preguntas 1,2,3,4,5
INSERT INTO partida_respuestas (id_partida, id_pregunta, respuesta_dada, es_correcta) VALUES
(1, 1, 'B', TRUE),   -- ¿Año fundación Barça?        correcta: B
(1, 2, 'A', TRUE),   -- ¿Gol final Mundial 2010?     correcta: A
(1, 3, 'A', FALSE),  -- ¿Mundiales Brasil?            correcta: C
(1, 4, 'B', TRUE),   -- ¿Final Mundial 2002?          correcta: B
(1, 5, 'C', FALSE);  -- ¿Primer Mundial 1930?         correcta: D

-- PARTIDA 2 | user 2 | categoria 2 (Selecciones) | preguntas 6,7,8,9,10
INSERT INTO partida_respuestas (id_partida, id_pregunta, respuesta_dada, es_correcta) VALUES
(2, 6,  'A', TRUE),  -- ¿Jugadores en campo?          correcta: A
(2, 7,  'B', FALSE), -- ¿Eurocopa 2021?               correcta: C
(2, 8,  'B', TRUE),  -- ¿Goles Ronaldo R9 2002?       correcta: B
(2, 9,  'A', TRUE),  -- ¿Más Eurocopas?               correcta: A
(2, 10, 'C', FALSE); -- ¿Mundial 2018?                correcta: D

-- PARTIDA 3 | user 3 | categoria 3 (Ligueras) | preguntas 11,12,13,14
INSERT INTO partida_respuestas (id_partida, id_pregunta, respuesta_dada, es_correcta) VALUES
(3, 11, 'C', TRUE),  -- ¿Champions Real Madrid?       correcta: C
(3, 12, 'A', TRUE),  -- ¿Más veces La Liga?           correcta: A
(3, 13, 'B', TRUE),  -- ¿Leicester Premier?           correcta: B
(3, 14, 'A', TRUE);  -- ¿Máximo goleador Champions?   correcta: A

-- PARTIDA 4 | user 3 | categoria 1 (Historia) | preguntas 1,2,3,4,5
INSERT INTO partida_respuestas (id_partida, id_pregunta, respuesta_dada, es_correcta) VALUES
(4, 1, 'B', TRUE),
(4, 2, 'C', FALSE),
(4, 3, 'C', TRUE),
(4, 4, 'A', FALSE),
(4, 5, 'D', TRUE);

-- PARTIDA 5 | user 4 | categoria 2 (Selecciones) | preguntas 6,7,8,9,10
INSERT INTO partida_respuestas (id_partida, id_pregunta, respuesta_dada, es_correcta) VALUES
(5, 6,  'A', TRUE),
(5, 7,  'C', TRUE),
(5, 8,  'D', FALSE),
(5, 9,  'B', FALSE),
(5, 10, 'D', TRUE);

-- PARTIDA 6 | user 4 | categoria 3 (Ligueras) | preguntas 11,12,13,14
INSERT INTO partida_respuestas (id_partida, id_pregunta, respuesta_dada, es_correcta) VALUES
(6, 11, 'C', TRUE),
(6, 12, 'A', TRUE),
(6, 13, 'B', TRUE),
(6, 14, 'D', FALSE);

-- PARTIDA 7 | user 6 | categoria 1 (Historia) | preguntas 1,2,3,4,5
INSERT INTO partida_respuestas (id_partida, id_pregunta, respuesta_dada, es_correcta) VALUES
(7, 1, 'A', FALSE),
(7, 2, 'A', TRUE),
(7, 3, 'C', TRUE),
(7, 4, 'B', TRUE),
(7, 5, 'D', TRUE);

-- PARTIDA 8 | user 7 | categoria 2 (Selecciones) | preguntas 6,7,8,9,10
INSERT INTO partida_respuestas (id_partida, id_pregunta, respuesta_dada, es_correcta) VALUES
(8, 6,  'A', TRUE),
(8, 7,  'C', TRUE),
(8, 8,  'B', TRUE),
(8, 9,  'A', TRUE),
(8, 10, 'D', TRUE);

-- PARTIDA 9 | user 7 | categoria 3 (Ligueras) | preguntas 11,12,13,14
INSERT INTO partida_respuestas (id_partida, id_pregunta, respuesta_dada, es_correcta) VALUES
(9, 11, 'A', FALSE),
(9, 12, 'B', FALSE),
(9, 13, 'A', FALSE),
(9, 14, 'A', TRUE);

-- PARTIDA 10 | user 8 | categoria 1 (Historia) | preguntas 1,2,3,4,5
INSERT INTO partida_respuestas (id_partida, id_pregunta, respuesta_dada, es_correcta) VALUES
(10, 1, 'B', TRUE),
(10, 2, 'A', TRUE),
(10, 3, 'C', TRUE),
(10, 4, 'C', FALSE),
(10, 5, 'D', TRUE);

-- PARTIDA 11 | user 8 | categoria 2 (Selecciones) | preguntas 6,7,8,9,10
INSERT INTO partida_respuestas (id_partida, id_pregunta, respuesta_dada, es_correcta) VALUES
(11, 6,  'A', TRUE),
(11, 7,  'C', TRUE),
(11, 8,  'B', TRUE),
(11, 9,  'A', TRUE),
(11, 10, 'A', FALSE);

-- PARTIDA 12 | user 2 | categoria 3 (Ligueras) | preguntas 11,12,13,14
INSERT INTO partida_respuestas (id_partida, id_pregunta, respuesta_dada, es_correcta) VALUES
(12, 11, 'C', TRUE),
(12, 12, 'A', TRUE),
(12, 13, 'B', TRUE),
(12, 14, 'A', TRUE);