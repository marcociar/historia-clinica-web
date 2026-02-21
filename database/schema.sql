-- =====================================================
-- SISTEMA HISTORIA CLINICA WEB
-- Schema base sin datos
-- =====================================================

CREATE DATABASE IF NOT EXISTS historiaclinicafinal1;
USE historiaclinicafinal1;

-- -----------------------------------------------------
-- TABLA USUARIOS
-- -----------------------------------------------------
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('medico','paciente') NOT NULL,
    id_paciente INT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- -----------------------------------------------------
-- TABLA PACIENTES
-- -----------------------------------------------------
CREATE TABLE pacientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

-- -----------------------------------------------------
-- TABLA HISTORIAS CLINICAS
-- -----------------------------------------------------
CREATE TABLE historias_clinicas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_paciente INT NOT NULL,
    diagnostico TEXT,
    tratamiento TEXT,
    comentario_paciente TEXT,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_paciente) REFERENCES pacientes(id) ON DELETE CASCADE
);

-- -----------------------------------------------------
-- TABLA ARCHIVOS HISTORIA
-- -----------------------------------------------------
CREATE TABLE archivos_historia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_historia INT NOT NULL,
    archivo VARCHAR(255) NOT NULL,
    nombre_archivo VARCHAR(255),
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_historia) REFERENCES historias_clinicas(id) ON DELETE CASCADE
);

-- -----------------------------------------------------
-- TABLA LOGS DE ACCESO
-- -----------------------------------------------------
CREATE TABLE logs_acceso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    rol VARCHAR(20),
    ip VARCHAR(45),
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP
);