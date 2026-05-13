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
    dificultad ENUM('facil','media','dificil') DEFAULT 'media',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    validada BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (autor_id) REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
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

    FOREIGN KEY (id_user)
        REFERENCES users(id)
        ON DELETE CASCADE
);

-- ============================
-- 7. Tabla detalles partidas
-- ============================
CREATE TABLE partida_respuestas (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    id_partida     INT  NOT NULL,
    id_pregunta    INT  NOT NULL,
    respuesta_dada CHAR(1) NOT NULL,
    es_correcta    BOOLEAN NOT NULL,
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