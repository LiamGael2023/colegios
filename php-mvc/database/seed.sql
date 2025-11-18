-- Datos iniciales para el Sistema de Gestión Escolar

USE sistema_escolar;

-- ==================== INSTITUCIÓN ====================
INSERT INTO institucion (nombre, codigo_modular, direccion, telefono, email, director, ugel, region)
VALUES ('I.E.P. San José', '1234567', 'Av. Principal 123, Lima', '01-1234567', 'info@iepsanjose.edu.pe', 'Dr. Juan Pérez García', 'UGEL 03', 'Lima');

-- ==================== USUARIOS ====================
-- Contraseña para todos los usuarios: password
INSERT INTO usuarios (email, password, nombre, apellidos, dni, telefono, rol, activo) VALUES
('admin@colegio.edu.pe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 'Sistema', '00000001', '999000001', 'ADMIN', TRUE),
('director@colegio.edu.pe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Juan', 'Pérez García', '12345678', '999888777', 'DIRECTOR', TRUE),
('secretaria@colegio.edu.pe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'María', 'López Sánchez', '87654321', '999777666', 'SECRETARIA', TRUE),
('profesor@colegio.edu.pe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Carlos', 'Rodríguez Mendoza', '11223344', '999666555', 'PROFESOR', TRUE);

-- Profesor
INSERT INTO profesores (usuario_id, especialidad) VALUES (4, 'Matemáticas');

-- ==================== AÑO ESCOLAR ====================
INSERT INTO anios_escolares (anio, fecha_inicio, fecha_fin, activo, institucion_id)
VALUES (2024, '2024-03-01', '2024-12-20', TRUE, 1);

INSERT INTO periodos (nombre, tipo, numero, fecha_inicio, fecha_fin, activo, anio_escolar_id) VALUES
('Bimestre 1', 'BIMESTRE', 1, '2024-03-01', '2024-05-10', FALSE, 1),
('Bimestre 2', 'BIMESTRE', 2, '2024-05-13', '2024-07-26', FALSE, 1),
('Bimestre 3', 'BIMESTRE', 3, '2024-08-12', '2024-10-11', TRUE, 1),
('Bimestre 4', 'BIMESTRE', 4, '2024-10-14', '2024-12-20', FALSE, 1);

-- ==================== NIVELES ====================
INSERT INTO niveles (nombre, codigo) VALUES
('INICIAL', 'INI'),
('PRIMARIA', 'PRI'),
('SECUNDARIA', 'SEC');

-- ==================== GRADOS ====================
-- Inicial
INSERT INTO grados (nombre, numero, nivel_id) VALUES
('3 años', 1, 1),
('4 años', 2, 1),
('5 años', 3, 1);

-- Primaria
INSERT INTO grados (nombre, numero, nivel_id) VALUES
('1er Grado', 1, 2),
('2do Grado', 2, 2),
('3er Grado', 3, 2),
('4to Grado', 4, 2),
('5to Grado', 5, 2),
('6to Grado', 6, 2);

-- Secundaria
INSERT INTO grados (nombre, numero, nivel_id) VALUES
('1er Año', 1, 3),
('2do Año', 2, 3),
('3er Año', 3, 3),
('4to Año', 4, 3),
('5to Año', 5, 3);

-- ==================== SECCIONES ====================
-- Crear secciones A y B para cada grado
INSERT INTO secciones (nombre, capacidad, grado_id)
SELECT 'A', 30, id FROM grados
UNION ALL
SELECT 'B', 30, id FROM grados;

-- ==================== ÁREAS CURRICULARES ====================
INSERT INTO areas_curriculares (nombre, descripcion) VALUES
('Matemática', 'Área de Matemática'),
('Comunicación', 'Área de Comunicación'),
('Ciencias Sociales', 'Personal Social / Historia, Geografía y Economía'),
('Ciencia y Tecnología', 'Ciencia y Ambiente / CTA'),
('Educación Física', 'Área de Educación Física'),
('Arte y Cultura', 'Área de Arte y Cultura'),
('Inglés', 'Área de Inglés'),
('Educación Religiosa', 'Área de Educación Religiosa'),
('Tutoría', 'Tutoría y Orientación Educativa');

-- ==================== CONCEPTOS DE PAGO ====================
INSERT INTO conceptos_pago (nombre, descripcion, monto, es_recurrente) VALUES
('Matrícula', 'Pago de matrícula anual', 350.00, FALSE),
('Pensión Mensual', 'Pensión de enseñanza mensual', 450.00, TRUE),
('APAFA', 'Cuota de APAFA', 100.00, FALSE),
('Agenda Escolar', 'Agenda escolar', 25.00, FALSE),
('Uniforme', 'Uniforme escolar completo', 200.00, FALSE);

-- ==================== ESTUDIANTES DE EJEMPLO ====================
INSERT INTO estudiantes (codigo, dni, nombres, apellido_paterno, apellido_materno, fecha_nacimiento, genero, direccion, telefono, activo) VALUES
('EST202400001', '70123456', 'Pedro Alberto', 'García', 'López', '2010-05-15', 'MASCULINO', 'Av. Los Pinos 456, Lima', '999111222', TRUE),
('EST202400002', '70123457', 'María Elena', 'Torres', 'Quispe', '2010-08-20', 'FEMENINO', 'Jr. Las Flores 789, Lima', '999222333', TRUE),
('EST202400003', '70123458', 'Luis Fernando', 'Mendoza', 'Ruiz', '2011-02-10', 'MASCULINO', 'Calle Sol 321, Lima', '999333444', TRUE);

-- Apoderados
INSERT INTO apoderados (estudiante_id, dni, nombres, apellidos, parentesco, telefono, email, ocupacion, es_principal) VALUES
(1, '40123456', 'Roberto', 'García Mendoza', 'PADRE', '999444555', 'roberto.garcia@email.com', 'Ingeniero', TRUE),
(2, '40123457', 'Ana María', 'Quispe Flores', 'MADRE', '999555666', 'ana.quispe@email.com', 'Profesora', TRUE),
(3, '40123458', 'Carlos', 'Mendoza Vargas', 'PADRE', '999666777', 'carlos.mendoza@email.com', 'Contador', TRUE);

-- Matrículas (en 4to grado de primaria, sección A)
INSERT INTO matriculas (codigo, estudiante_id, seccion_id, anio_escolar_id, fecha_matricula, estado, tipo_matricula) VALUES
('MAT202400001', 1, 13, 1, '2024-02-15', 'ACTIVA', 'REGULAR'),
('MAT202400002', 2, 13, 1, '2024-02-16', 'ACTIVA', 'REGULAR'),
('MAT202400003', 3, 13, 1, '2024-02-17', 'ACTIVA', 'REGULAR');
