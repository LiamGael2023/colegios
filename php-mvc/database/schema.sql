-- Sistema de Gestión Escolar - Perú
-- Esquema de Base de Datos MySQL

CREATE DATABASE IF NOT EXISTS sistema_escolar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_escolar;

-- ==================== USUARIOS ====================
CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    dni VARCHAR(8) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    rol ENUM('ADMIN', 'DIRECTOR', 'PROFESOR', 'SECRETARIA') DEFAULT 'PROFESOR',
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ==================== INSTITUCIÓN ====================
CREATE TABLE institucion (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(200) NOT NULL,
    codigo_modular VARCHAR(20) UNIQUE NOT NULL,
    direccion VARCHAR(300) NOT NULL,
    telefono VARCHAR(20),
    email VARCHAR(100),
    director VARCHAR(200),
    ugel VARCHAR(100),
    region VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ==================== AÑO ESCOLAR ====================
CREATE TABLE anios_escolares (
    id INT PRIMARY KEY AUTO_INCREMENT,
    anio INT UNIQUE NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    activo BOOLEAN DEFAULT FALSE,
    institucion_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (institucion_id) REFERENCES institucion(id)
);

CREATE TABLE periodos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    tipo ENUM('BIMESTRE', 'TRIMESTRE') DEFAULT 'BIMESTRE',
    numero INT NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    activo BOOLEAN DEFAULT FALSE,
    anio_escolar_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (anio_escolar_id) REFERENCES anios_escolares(id)
);

-- ==================== ESTRUCTURA ACADÉMICA ====================
CREATE TABLE niveles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) UNIQUE NOT NULL,
    codigo VARCHAR(10) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE grados (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    numero INT NOT NULL,
    nivel_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (nivel_id) REFERENCES niveles(id),
    UNIQUE KEY (nivel_id, numero)
);

CREATE TABLE secciones (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(10) NOT NULL,
    capacidad INT DEFAULT 30,
    grado_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (grado_id) REFERENCES grados(id),
    UNIQUE KEY (grado_id, nombre)
);

-- ==================== CURSOS ====================
CREATE TABLE areas_curriculares (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) UNIQUE NOT NULL,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE cursos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    horas_semanales INT DEFAULT 2,
    area_curricular_id INT NOT NULL,
    grado_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (area_curricular_id) REFERENCES areas_curriculares(id),
    FOREIGN KEY (grado_id) REFERENCES grados(id),
    UNIQUE KEY (nombre, grado_id)
);

-- ==================== PROFESORES ====================
CREATE TABLE profesores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT UNIQUE NOT NULL,
    especialidad VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE asignaciones_profesor (
    id INT PRIMARY KEY AUTO_INCREMENT,
    profesor_id INT NOT NULL,
    curso_id INT NOT NULL,
    seccion_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (profesor_id) REFERENCES profesores(id),
    FOREIGN KEY (curso_id) REFERENCES cursos(id),
    FOREIGN KEY (seccion_id) REFERENCES secciones(id),
    UNIQUE KEY (profesor_id, curso_id, seccion_id)
);

-- ==================== ESTUDIANTES ====================
CREATE TABLE estudiantes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    dni VARCHAR(8) UNIQUE,
    nombres VARCHAR(100) NOT NULL,
    apellido_paterno VARCHAR(100) NOT NULL,
    apellido_materno VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    genero ENUM('MASCULINO', 'FEMENINO') NOT NULL,
    direccion VARCHAR(300),
    telefono VARCHAR(20),
    email VARCHAR(100),
    lugar_nacimiento VARCHAR(100),
    nacionalidad VARCHAR(50) DEFAULT 'Peruana',
    lengua VARCHAR(50) DEFAULT 'Castellano',
    religion VARCHAR(50),
    tipo_sangre VARCHAR(10),
    alergias TEXT,
    discapacidad TEXT,
    observaciones TEXT,
    foto VARCHAR(255),
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE apoderados (
    id INT PRIMARY KEY AUTO_INCREMENT,
    estudiante_id INT NOT NULL,
    dni VARCHAR(8) NOT NULL,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    parentesco ENUM('PADRE', 'MADRE', 'TUTOR', 'ABUELO', 'ABUELA', 'TIO', 'TIA', 'HERMANO', 'HERMANA', 'OTRO') NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    telefono_trabajo VARCHAR(20),
    email VARCHAR(100),
    ocupacion VARCHAR(100),
    direccion VARCHAR(300),
    lugar_trabajo VARCHAR(200),
    es_principal BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id)
);

-- ==================== MATRÍCULA ====================
CREATE TABLE matriculas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    codigo VARCHAR(20) UNIQUE NOT NULL,
    estudiante_id INT NOT NULL,
    seccion_id INT NOT NULL,
    anio_escolar_id INT NOT NULL,
    fecha_matricula DATE NOT NULL,
    estado ENUM('ACTIVA', 'RETIRADO', 'TRASLADADO', 'FINALIZADA') DEFAULT 'ACTIVA',
    tipo_matricula ENUM('REGULAR', 'TRASLADO', 'REINGRESO') DEFAULT 'REGULAR',
    procedencia VARCHAR(200),
    observaciones TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id),
    FOREIGN KEY (seccion_id) REFERENCES secciones(id),
    FOREIGN KEY (anio_escolar_id) REFERENCES anios_escolares(id),
    UNIQUE KEY (estudiante_id, anio_escolar_id)
);

-- ==================== ASISTENCIA ====================
CREATE TABLE asistencias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    estudiante_id INT NOT NULL,
    anio_escolar_id INT NOT NULL,
    fecha DATE NOT NULL,
    estado ENUM('PRESENTE', 'AUSENTE', 'TARDANZA', 'JUSTIFICADO') NOT NULL,
    hora_entrada TIME,
    hora_salida TIME,
    observacion TEXT,
    justificado BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id),
    FOREIGN KEY (anio_escolar_id) REFERENCES anios_escolares(id),
    UNIQUE KEY (estudiante_id, fecha)
);

-- ==================== NOTAS ====================
CREATE TABLE notas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    estudiante_id INT NOT NULL,
    curso_id INT NOT NULL,
    periodo_id INT NOT NULL,
    calificacion VARCHAR(5) NOT NULL,
    tipo ENUM('LITERAL', 'VIGESIMAL') NOT NULL,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id),
    FOREIGN KEY (curso_id) REFERENCES cursos(id),
    FOREIGN KEY (periodo_id) REFERENCES periodos(id)
);

-- ==================== PAGOS ====================
CREATE TABLE conceptos_pago (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) UNIQUE NOT NULL,
    descripcion TEXT,
    monto DECIMAL(10, 2) NOT NULL,
    es_recurrente BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE pagos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    numero_recibo VARCHAR(20) UNIQUE NOT NULL,
    estudiante_id INT NOT NULL,
    concepto_id INT NOT NULL,
    anio_escolar_id INT NOT NULL,
    monto DECIMAL(10, 2) NOT NULL,
    monto_pagado DECIMAL(10, 2) DEFAULT 0,
    fecha_vencimiento DATE NOT NULL,
    fecha_pago DATETIME,
    estado ENUM('PENDIENTE', 'PAGADO', 'PARCIAL', 'VENCIDO', 'ANULADO') DEFAULT 'PENDIENTE',
    metodo_pago ENUM('EFECTIVO', 'TRANSFERENCIA', 'TARJETA', 'YAPE', 'PLIN'),
    mes INT,
    observacion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id),
    FOREIGN KEY (concepto_id) REFERENCES conceptos_pago(id),
    FOREIGN KEY (anio_escolar_id) REFERENCES anios_escolares(id)
);

-- ==================== HORARIOS ====================
CREATE TABLE horarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    asignacion_id INT NOT NULL,
    dia ENUM('LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO') NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    aula VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (asignacion_id) REFERENCES asignaciones_profesor(id) ON DELETE CASCADE,
    UNIQUE KEY (asignacion_id, dia, hora_inicio)
);

-- ==================== COMUNICADOS ====================
CREATE TABLE comunicados (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(200) NOT NULL,
    contenido TEXT NOT NULL,
    tipo ENUM('GENERAL', 'NIVEL', 'GRADO', 'SECCION') DEFAULT 'GENERAL',
    destinatario_id INT,
    fecha_publicacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_expiracion DATE,
    activo BOOLEAN DEFAULT TRUE,
    usuario_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- ==================== ÍNDICES ====================
CREATE INDEX idx_estudiantes_codigo ON estudiantes(codigo);
CREATE INDEX idx_estudiantes_dni ON estudiantes(dni);
CREATE INDEX idx_matriculas_anio ON matriculas(anio_escolar_id);
CREATE INDEX idx_asistencias_fecha ON asistencias(fecha);
CREATE INDEX idx_notas_periodo ON notas(periodo_id);
CREATE INDEX idx_pagos_estado ON pagos(estado);
CREATE INDEX idx_pagos_mes ON pagos(mes);
CREATE INDEX idx_horarios_dia ON horarios(dia);
CREATE INDEX idx_comunicados_fecha ON comunicados(fecha_publicacion);
