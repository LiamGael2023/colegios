-- =============================================
-- DATOS DE PRUEBA - SISTEMA ESCOLAR PERÚ
-- =============================================

-- Limpiar datos existentes (en orden por dependencias)
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE horarios;
TRUNCATE TABLE comunicados;
TRUNCATE TABLE pagos;
TRUNCATE TABLE notas;
TRUNCATE TABLE asistencia;
TRUNCATE TABLE matriculas;
TRUNCATE TABLE asignaciones;
TRUNCATE TABLE estudiantes;
TRUNCATE TABLE apoderados;
TRUNCATE TABLE docentes;
TRUNCATE TABLE conceptos_pago;
TRUNCATE TABLE cursos;
TRUNCATE TABLE secciones;
TRUNCATE TABLE grados;
TRUNCATE TABLE areas;
TRUNCATE TABLE niveles;
TRUNCATE TABLE anios_escolares;
TRUNCATE TABLE usuarios;
SET FOREIGN_KEY_CHECKS = 1;

-- =============================================
-- USUARIOS DEL SISTEMA
-- Contraseña por defecto: 123456
-- =============================================
INSERT INTO usuarios (nombre, email, password, rol, activo) VALUES
('Administrador Sistema', 'admin@colegio.edu.pe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ADMIN', 1),
('María García López', 'director@colegio.edu.pe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'DIRECTOR', 1),
('Carlos Mendoza Ríos', 'docente1@colegio.edu.pe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'DOCENTE', 1),
('Ana Torres Vega', 'docente2@colegio.edu.pe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'DOCENTE', 1),
('Luis Pérez Soto', 'docente3@colegio.edu.pe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'DOCENTE', 1),
('Rosa Díaz Campos', 'secretaria@colegio.edu.pe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'SECRETARIA', 1);

-- =============================================
-- AÑOS ESCOLARES
-- =============================================
INSERT INTO anios_escolares (anio, fecha_inicio, fecha_fin, activo) VALUES
(2024, '2024-03-01', '2024-12-20', 1),
(2023, '2023-03-01', '2023-12-20', 0);

-- =============================================
-- NIVELES EDUCATIVOS
-- =============================================
INSERT INTO niveles (nombre, orden) VALUES
('INICIAL', 1),
('PRIMARIA', 2),
('SECUNDARIA', 3);

-- =============================================
-- GRADOS
-- =============================================
INSERT INTO grados (nombre, nivel_id, orden) VALUES
-- Inicial
('3 años', 1, 1),
('4 años', 1, 2),
('5 años', 1, 3),
-- Primaria
('1° Primaria', 2, 4),
('2° Primaria', 2, 5),
('3° Primaria', 2, 6),
('4° Primaria', 2, 7),
('5° Primaria', 2, 8),
('6° Primaria', 2, 9),
-- Secundaria
('1° Secundaria', 3, 10),
('2° Secundaria', 3, 11),
('3° Secundaria', 3, 12),
('4° Secundaria', 3, 13),
('5° Secundaria', 3, 14);

-- =============================================
-- SECCIONES
-- =============================================
INSERT INTO secciones (nombre, grado_id, capacidad) VALUES
-- Inicial
('A', 1, 20), ('B', 1, 20),
('A', 2, 20), ('B', 2, 20),
('A', 3, 25), ('B', 3, 25),
-- Primaria
('A', 4, 30), ('B', 4, 30),
('A', 5, 30), ('B', 5, 30),
('A', 6, 30), ('B', 6, 30),
('A', 7, 30), ('B', 7, 30),
('A', 8, 30), ('B', 8, 30),
('A', 9, 30), ('B', 9, 30),
-- Secundaria
('A', 10, 35), ('B', 10, 35),
('A', 11, 35), ('B', 11, 35),
('A', 12, 35), ('B', 12, 35),
('A', 13, 35), ('B', 13, 35),
('A', 14, 35), ('B', 14, 35);

-- =============================================
-- ÁREAS CURRICULARES
-- =============================================
INSERT INTO areas (nombre, descripcion) VALUES
('Comunicación', 'Desarrollo de competencias comunicativas'),
('Matemática', 'Resolución de problemas matemáticos'),
('Ciencia y Tecnología', 'Indagación científica'),
('Personal Social', 'Desarrollo personal y ciudadano'),
('Arte y Cultura', 'Expresión artística'),
('Educación Física', 'Desarrollo corporal y motriz'),
('Educación Religiosa', 'Formación espiritual'),
('Inglés', 'Segunda lengua'),
('Computación', 'Tecnologías de información');

-- =============================================
-- CURSOS
-- =============================================
INSERT INTO cursos (nombre, area_id, grado_id, horas_semanales) VALUES
-- 1° Primaria
('Comunicación', 1, 4, 6),
('Matemática', 2, 4, 6),
('Ciencia y Tecnología', 3, 4, 3),
('Personal Social', 4, 4, 3),
('Arte y Cultura', 5, 4, 2),
('Educación Física', 6, 4, 2),
('Educación Religiosa', 7, 4, 1),
('Inglés', 8, 4, 2),
-- 2° Primaria
('Comunicación', 1, 5, 6),
('Matemática', 2, 5, 6),
('Ciencia y Tecnología', 3, 5, 3),
('Personal Social', 4, 5, 3),
('Arte y Cultura', 5, 5, 2),
('Educación Física', 6, 5, 2),
('Educación Religiosa', 7, 5, 1),
('Inglés', 8, 5, 2),
-- 1° Secundaria
('Comunicación', 1, 10, 5),
('Matemática', 2, 10, 5),
('Ciencia y Tecnología', 3, 10, 4),
('Historia, Geografía y Economía', 4, 10, 3),
('Formación Cívica', 4, 10, 2),
('Arte y Cultura', 5, 10, 2),
('Educación Física', 6, 10, 2),
('Educación Religiosa', 7, 10, 2),
('Inglés', 8, 10, 3),
('Computación', 9, 10, 2);

-- =============================================
-- DOCENTES
-- =============================================
INSERT INTO docentes (dni, nombres, apellido_paterno, apellido_materno, telefono, email, especialidad, fecha_ingreso, activo) VALUES
('12345678', 'Carlos Alberto', 'Mendoza', 'Ríos', '987654321', 'carlos.mendoza@colegio.edu.pe', 'Matemática', '2020-03-01', 1),
('23456789', 'Ana María', 'Torres', 'Vega', '987654322', 'ana.torres@colegio.edu.pe', 'Comunicación', '2019-03-01', 1),
('34567890', 'Luis Fernando', 'Pérez', 'Soto', '987654323', 'luis.perez@colegio.edu.pe', 'Ciencia y Tecnología', '2021-03-01', 1),
('45678901', 'Patricia Elena', 'Vargas', 'Luna', '987654324', 'patricia.vargas@colegio.edu.pe', 'Personal Social', '2018-03-01', 1),
('56789012', 'Jorge Manuel', 'Castro', 'Díaz', '987654325', 'jorge.castro@colegio.edu.pe', 'Inglés', '2022-03-01', 1),
('67890123', 'Carmen Rosa', 'Flores', 'Medina', '987654326', 'carmen.flores@colegio.edu.pe', 'Arte y Cultura', '2020-03-01', 1),
('78901234', 'Roberto Carlos', 'Sánchez', 'Guzmán', '987654327', 'roberto.sanchez@colegio.edu.pe', 'Educación Física', '2019-03-01', 1),
('89012345', 'María del Carmen', 'Ruiz', 'Paredes', '987654328', 'maria.ruiz@colegio.edu.pe', 'Educación Religiosa', '2021-03-01', 1);

-- =============================================
-- APODERADOS
-- =============================================
INSERT INTO apoderados (dni, nombres, apellidos, telefono, email, direccion, ocupacion) VALUES
('11111111', 'Juan Carlos', 'Ramírez Huamán', '999111111', 'juan.ramirez@email.com', 'Av. Los Pinos 123, San Isidro', 'Ingeniero'),
('22222222', 'María Elena', 'Gonzáles Chávez', '999222222', 'maria.gonzales@email.com', 'Jr. Las Flores 456, Miraflores', 'Doctora'),
('33333333', 'Pedro Antonio', 'López Quispe', '999333333', 'pedro.lopez@email.com', 'Calle Los Álamos 789, Surco', 'Contador'),
('44444444', 'Rosa María', 'Martínez Vargas', '999444444', 'rosa.martinez@email.com', 'Av. Principal 321, La Molina', 'Abogada'),
('55555555', 'Carlos Eduardo', 'Fernández Rojas', '999555555', 'carlos.fernandez@email.com', 'Jr. Los Cedros 654, San Borja', 'Empresario'),
('66666666', 'Ana Lucía', 'Rodríguez Silva', '999666666', 'ana.rodriguez@email.com', 'Av. El Sol 987, Pueblo Libre', 'Profesora'),
('77777777', 'Miguel Ángel', 'Torres Espinoza', '999777777', 'miguel.torres@email.com', 'Calle Luna 147, Jesús María', 'Arquitecto'),
('88888888', 'Lucía Fernanda', 'Díaz Mendoza', '999888888', 'lucia.diaz@email.com', 'Jr. Estrella 258, Lince', 'Enfermera'),
('99999999', 'Roberto José', 'García Paredes', '999999999', 'roberto.garcia@email.com', 'Av. Central 369, Breña', 'Comerciante'),
('10101010', 'Patricia Carmen', 'Vega Castillo', '991010101', 'patricia.vega@email.com', 'Calle Paz 741, Magdalena', 'Psicóloga');

-- =============================================
-- ESTUDIANTES
-- =============================================
INSERT INTO estudiantes (codigo, dni, nombres, apellido_paterno, apellido_materno, fecha_nacimiento, genero, direccion, telefono, email, apoderado_id) VALUES
-- Estudiantes de 1° Primaria A
('EST-2024-001', '70000001', 'Alejandro José', 'Ramírez', 'Soto', '2017-03-15', 'MASCULINO', 'Av. Los Pinos 123', '999111111', NULL, 1),
('EST-2024-002', '70000002', 'Valentina María', 'Gonzáles', 'Luna', '2017-05-20', 'FEMENINO', 'Jr. Las Flores 456', '999222222', NULL, 2),
('EST-2024-003', '70000003', 'Sebastián André', 'López', 'Vargas', '2017-01-10', 'MASCULINO', 'Calle Los Álamos 789', '999333333', NULL, 3),
('EST-2024-004', '70000004', 'Camila Lucía', 'Martínez', 'Rojas', '2017-07-25', 'FEMENINO', 'Av. Principal 321', '999444444', NULL, 4),
('EST-2024-005', '70000005', 'Matías Gabriel', 'Fernández', 'Silva', '2017-04-08', 'MASCULINO', 'Jr. Los Cedros 654', '999555555', NULL, 5),
-- Estudiantes de 1° Primaria B
('EST-2024-006', '70000006', 'Isabella Sofía', 'Rodríguez', 'Espinoza', '2017-09-12', 'FEMENINO', 'Av. El Sol 987', '999666666', NULL, 6),
('EST-2024-007', '70000007', 'Lucas Daniel', 'Torres', 'Mendoza', '2017-02-28', 'MASCULINO', 'Calle Luna 147', '999777777', NULL, 7),
('EST-2024-008', '70000008', 'Emma Victoria', 'Díaz', 'Paredes', '2017-11-05', 'FEMENINO', 'Jr. Estrella 258', '999888888', NULL, 8),
('EST-2024-009', '70000009', 'Thiago Nicolás', 'García', 'Castillo', '2017-06-18', 'MASCULINO', 'Av. Central 369', '999999999', NULL, 9),
('EST-2024-010', '70000010', 'Mía Antonella', 'Vega', 'Huamán', '2017-08-30', 'FEMENINO', 'Calle Paz 741', '991010101', NULL, 10),
-- Estudiantes de 2° Primaria A
('EST-2024-011', '70000011', 'Santiago Alonso', 'Ramírez', 'Chávez', '2016-04-22', 'MASCULINO', 'Av. Los Pinos 123', '999111111', NULL, 1),
('EST-2024-012', '70000012', 'Luciana Belén', 'Gonzáles', 'Quispe', '2016-10-14', 'FEMENINO', 'Jr. Las Flores 456', '999222222', NULL, 2),
('EST-2024-013', '70000013', 'Diego Martín', 'López', 'Luna', '2016-12-03', 'MASCULINO', 'Calle Los Álamos 789', '999333333', NULL, 3),
('EST-2024-014', '70000014', 'Catalina Isabel', 'Martínez', 'Silva', '2016-07-09', 'FEMENINO', 'Av. Principal 321', '999444444', NULL, 4),
('EST-2024-015', '70000015', 'Joaquín Emilio', 'Fernández', 'Espinoza', '2016-01-27', 'MASCULINO', 'Jr. Los Cedros 654', '999555555', NULL, 5),
-- Estudiantes de 1° Secundaria A
('EST-2024-016', '70000016', 'Rodrigo Alejandro', 'Rodríguez', 'Mendoza', '2012-03-11', 'MASCULINO', 'Av. El Sol 987', '999666666', NULL, 6),
('EST-2024-017', '70000017', 'Valeria Nicole', 'Torres', 'Paredes', '2012-08-19', 'FEMENINO', 'Calle Luna 147', '999777777', NULL, 7),
('EST-2024-018', '70000018', 'Andrés Felipe', 'Díaz', 'Castillo', '2012-05-07', 'MASCULINO', 'Jr. Estrella 258', '999888888', NULL, 8),
('EST-2024-019', '70000019', 'Fernanda Alessia', 'García', 'Huamán', '2012-11-24', 'FEMENINO', 'Av. Central 369', '999999999', NULL, 9),
('EST-2024-020', '70000020', 'Gabriel Esteban', 'Vega', 'Soto', '2012-09-16', 'MASCULINO', 'Calle Paz 741', '991010101', NULL, 10);

-- =============================================
-- MATRÍCULAS 2024
-- =============================================
INSERT INTO matriculas (estudiante_id, anio_id, seccion_id, fecha_matricula, estado, observaciones) VALUES
-- 1° Primaria A (seccion_id = 7)
(1, 1, 7, '2024-02-15', 'ACTIVA', 'Matrícula regular'),
(2, 1, 7, '2024-02-16', 'ACTIVA', 'Matrícula regular'),
(3, 1, 7, '2024-02-17', 'ACTIVA', 'Matrícula regular'),
(4, 1, 7, '2024-02-18', 'ACTIVA', 'Matrícula regular'),
(5, 1, 7, '2024-02-19', 'ACTIVA', 'Matrícula regular'),
-- 1° Primaria B (seccion_id = 8)
(6, 1, 8, '2024-02-15', 'ACTIVA', 'Matrícula regular'),
(7, 1, 8, '2024-02-16', 'ACTIVA', 'Matrícula regular'),
(8, 1, 8, '2024-02-17', 'ACTIVA', 'Matrícula regular'),
(9, 1, 8, '2024-02-18', 'ACTIVA', 'Matrícula regular'),
(10, 1, 8, '2024-02-19', 'ACTIVA', 'Matrícula regular'),
-- 2° Primaria A (seccion_id = 9)
(11, 1, 9, '2024-02-15', 'ACTIVA', 'Matrícula regular'),
(12, 1, 9, '2024-02-16', 'ACTIVA', 'Matrícula regular'),
(13, 1, 9, '2024-02-17', 'ACTIVA', 'Matrícula regular'),
(14, 1, 9, '2024-02-18', 'ACTIVA', 'Matrícula regular'),
(15, 1, 9, '2024-02-19', 'ACTIVA', 'Matrícula regular'),
-- 1° Secundaria A (seccion_id = 19)
(16, 1, 19, '2024-02-15', 'ACTIVA', 'Matrícula regular'),
(17, 1, 19, '2024-02-16', 'ACTIVA', 'Matrícula regular'),
(18, 1, 19, '2024-02-17', 'ACTIVA', 'Matrícula regular'),
(19, 1, 19, '2024-02-18', 'ACTIVA', 'Matrícula regular'),
(20, 1, 19, '2024-02-19', 'ACTIVA', 'Matrícula regular');

-- =============================================
-- ASIGNACIONES DOCENTE-CURSO
-- =============================================
INSERT INTO asignaciones (docente_id, curso_id, seccion_id, anio_id) VALUES
-- 1° Primaria A
(2, 1, 7, 1),  -- Ana Torres - Comunicación
(1, 2, 7, 1),  -- Carlos Mendoza - Matemática
(3, 3, 7, 1),  -- Luis Pérez - Ciencia
(4, 4, 7, 1),  -- Patricia Vargas - Personal Social
(6, 5, 7, 1),  -- Carmen Flores - Arte
(7, 6, 7, 1),  -- Roberto Sánchez - Ed. Física
(8, 7, 7, 1),  -- María Ruiz - Religión
(5, 8, 7, 1),  -- Jorge Castro - Inglés
-- 1° Primaria B
(2, 1, 8, 1),  -- Ana Torres - Comunicación
(1, 2, 8, 1),  -- Carlos Mendoza - Matemática
(3, 3, 8, 1),  -- Luis Pérez - Ciencia
(4, 4, 8, 1),  -- Patricia Vargas - Personal Social
-- 2° Primaria A
(2, 9, 9, 1),  -- Ana Torres - Comunicación
(1, 10, 9, 1), -- Carlos Mendoza - Matemática
(3, 11, 9, 1), -- Luis Pérez - Ciencia
(4, 12, 9, 1), -- Patricia Vargas - Personal Social
-- 1° Secundaria A
(2, 17, 19, 1),  -- Ana Torres - Comunicación
(1, 18, 19, 1),  -- Carlos Mendoza - Matemática
(3, 19, 19, 1),  -- Luis Pérez - Ciencia
(4, 20, 19, 1),  -- Patricia Vargas - Historia
(5, 25, 19, 1);  -- Jorge Castro - Inglés

-- =============================================
-- CONCEPTOS DE PAGO
-- =============================================
INSERT INTO conceptos_pago (nombre, monto, descripcion, tipo, activo) VALUES
('Matrícula 2024', 350.00, 'Pago único de matrícula', 'MATRICULA', 1),
('Pensión Mensual - Primaria', 450.00, 'Pensión mensual nivel primaria', 'PENSION', 1),
('Pensión Mensual - Secundaria', 500.00, 'Pensión mensual nivel secundaria', 'PENSION', 1),
('APAFA', 100.00, 'Cuota APAFA anual', 'OTRO', 1),
('Agenda Escolar', 25.00, 'Agenda del estudiante', 'OTRO', 1),
('Uniforme Completo', 180.00, 'Uniforme escolar completo', 'OTRO', 1),
('Buzo Deportivo', 80.00, 'Buzo para educación física', 'OTRO', 1),
('Seguro Escolar', 50.00, 'Seguro contra accidentes', 'OTRO', 1);

-- =============================================
-- PAGOS
-- =============================================
INSERT INTO pagos (estudiante_id, concepto_id, monto, fecha_pago, metodo_pago, numero_recibo, observaciones) VALUES
-- Pagos de Alejandro (estudiante 1)
(1, 1, 350.00, '2024-02-15', 'EFECTIVO', 'REC-001', 'Pago de matrícula'),
(1, 2, 450.00, '2024-03-05', 'TRANSFERENCIA', 'REC-002', 'Pensión Marzo'),
(1, 2, 450.00, '2024-04-03', 'YAPE', 'REC-003', 'Pensión Abril'),
(1, 2, 450.00, '2024-05-06', 'EFECTIVO', 'REC-004', 'Pensión Mayo'),
-- Pagos de Valentina (estudiante 2)
(2, 1, 350.00, '2024-02-16', 'TRANSFERENCIA', 'REC-005', 'Pago de matrícula'),
(2, 2, 450.00, '2024-03-04', 'EFECTIVO', 'REC-006', 'Pensión Marzo'),
(2, 2, 450.00, '2024-04-05', 'YAPE', 'REC-007', 'Pensión Abril'),
-- Pagos de Sebastián (estudiante 3)
(3, 1, 350.00, '2024-02-17', 'EFECTIVO', 'REC-008', 'Pago de matrícula'),
(3, 2, 450.00, '2024-03-06', 'PLIN', 'REC-009', 'Pensión Marzo'),
-- Pagos de estudiantes de secundaria
(16, 1, 350.00, '2024-02-15', 'TRANSFERENCIA', 'REC-010', 'Pago de matrícula'),
(16, 3, 500.00, '2024-03-05', 'EFECTIVO', 'REC-011', 'Pensión Marzo'),
(16, 3, 500.00, '2024-04-04', 'YAPE', 'REC-012', 'Pensión Abril'),
(16, 3, 500.00, '2024-05-03', 'TRANSFERENCIA', 'REC-013', 'Pensión Mayo'),
(17, 1, 350.00, '2024-02-16', 'EFECTIVO', 'REC-014', 'Pago de matrícula'),
(17, 3, 500.00, '2024-03-07', 'PLIN', 'REC-015', 'Pensión Marzo');

-- =============================================
-- NOTAS (Bimestre 1)
-- =============================================
INSERT INTO notas (estudiante_id, curso_id, bimestre, nota1, nota2, nota3, nota4, promedio, anio_id) VALUES
-- Notas de Alejandro (estudiante 1) - 1° Primaria
(1, 1, 1, 16, 17, 15, 18, 17, 1),  -- Comunicación
(1, 2, 1, 18, 19, 17, 18, 18, 1),  -- Matemática
(1, 3, 1, 15, 16, 14, 17, 16, 1),  -- Ciencia
(1, 4, 1, 17, 18, 16, 17, 17, 1),  -- Personal Social
-- Notas de Valentina (estudiante 2)
(2, 1, 1, 18, 19, 18, 19, 19, 1),
(2, 2, 1, 17, 16, 18, 17, 17, 1),
(2, 3, 1, 16, 17, 16, 18, 17, 1),
(2, 4, 1, 18, 17, 19, 18, 18, 1),
-- Notas de Sebastián (estudiante 3)
(3, 1, 1, 14, 15, 13, 16, 15, 1),
(3, 2, 1, 15, 16, 14, 15, 15, 1),
(3, 3, 1, 13, 14, 12, 15, 14, 1),
(3, 4, 1, 16, 15, 14, 16, 15, 1),
-- Notas de Rodrigo (estudiante 16) - 1° Secundaria
(16, 17, 1, 17, 18, 16, 17, 17, 1),  -- Comunicación
(16, 18, 1, 19, 18, 20, 19, 19, 1),  -- Matemática
(16, 19, 1, 16, 17, 15, 18, 17, 1),  -- Ciencia
(16, 20, 1, 15, 16, 14, 17, 16, 1),  -- Historia
-- Notas de Valeria (estudiante 17)
(17, 17, 1, 18, 19, 17, 18, 18, 1),
(17, 18, 1, 16, 17, 15, 16, 16, 1),
(17, 19, 1, 17, 18, 16, 17, 17, 1),
(17, 20, 1, 18, 17, 19, 18, 18, 1);

-- =============================================
-- ASISTENCIA (Última semana)
-- =============================================
INSERT INTO asistencia (estudiante_id, fecha, estado, observaciones) VALUES
-- Asistencia de estudiantes 1° Primaria A
(1, '2024-05-13', 'PRESENTE', NULL),
(1, '2024-05-14', 'PRESENTE', NULL),
(1, '2024-05-15', 'TARDANZA', 'Llegó 10 minutos tarde'),
(1, '2024-05-16', 'PRESENTE', NULL),
(1, '2024-05-17', 'PRESENTE', NULL),
(2, '2024-05-13', 'PRESENTE', NULL),
(2, '2024-05-14', 'FALTA', 'Justificada por enfermedad'),
(2, '2024-05-15', 'PRESENTE', NULL),
(2, '2024-05-16', 'PRESENTE', NULL),
(2, '2024-05-17', 'PRESENTE', NULL),
(3, '2024-05-13', 'PRESENTE', NULL),
(3, '2024-05-14', 'PRESENTE', NULL),
(3, '2024-05-15', 'PRESENTE', NULL),
(3, '2024-05-16', 'FALTA', NULL),
(3, '2024-05-17', 'FALTA', NULL),
-- Asistencia de estudiantes 1° Secundaria A
(16, '2024-05-13', 'PRESENTE', NULL),
(16, '2024-05-14', 'PRESENTE', NULL),
(16, '2024-05-15', 'PRESENTE', NULL),
(16, '2024-05-16', 'PRESENTE', NULL),
(16, '2024-05-17', 'PRESENTE', NULL),
(17, '2024-05-13', 'TARDANZA', NULL),
(17, '2024-05-14', 'PRESENTE', NULL),
(17, '2024-05-15', 'PRESENTE', NULL),
(17, '2024-05-16', 'PRESENTE', NULL),
(17, '2024-05-17', 'PRESENTE', NULL);

-- =============================================
-- COMUNICADOS
-- =============================================
INSERT INTO comunicados (titulo, contenido, tipo, fecha_publicacion, fecha_expiracion, dirigido_a, usuario_id, activo) VALUES
('Inicio del Año Escolar 2024',
'Estimados padres de familia, les comunicamos que el inicio de clases será el día lunes 4 de marzo. Los estudiantes deberán asistir con el uniforme completo y los útiles escolares indicados en la lista.',
'GENERAL', '2024-02-20', '2024-03-10', 'TODOS', 1, 1),

('Reunión de Padres - Primer Bimestre',
'Se convoca a todos los padres de familia a la reunión informativa del primer bimestre que se realizará el viernes 10 de mayo a las 4:00 PM en el auditorio del colegio.',
'REUNION', '2024-05-01', '2024-05-10', 'APODERADOS', 2, 1),

('Día del Maestro',
'Con motivo de celebrarse el Día del Maestro, las clases serán suspendidas el día 6 de julio. Los estudiantes retornarán el lunes 8 de julio.',
'EVENTO', '2024-06-25', '2024-07-08', 'TODOS', 2, 1),

('Pago de Pensiones',
'Se recuerda a los padres de familia que el pago de pensiones debe realizarse dentro de los primeros 5 días de cada mes. Evite recargos por mora.',
'ADMINISTRATIVO', '2024-05-01', '2024-12-31', 'APODERADOS', 1, 1),

('Exámenes Bimestrales',
'Se comunica a los estudiantes que los exámenes del primer bimestre se realizarán del 20 al 24 de mayo. Revisar el cronograma en el mural del colegio.',
'ACADEMICO', '2024-05-10', '2024-05-24', 'ESTUDIANTES', 2, 1);

-- =============================================
-- HORARIOS
-- =============================================
INSERT INTO horarios (asignacion_id, dia, hora_inicio, hora_fin, aula) VALUES
-- 1° Primaria A - Lunes
(1, 'LUNES', '08:00:00', '09:30:00', 'Aula 101'),   -- Comunicación
(2, 'LUNES', '09:45:00', '11:15:00', 'Aula 101'),   -- Matemática
(4, 'LUNES', '11:30:00', '12:15:00', 'Aula 101'),   -- Personal Social
-- 1° Primaria A - Martes
(2, 'MARTES', '08:00:00', '09:30:00', 'Aula 101'),  -- Matemática
(1, 'MARTES', '09:45:00', '11:15:00', 'Aula 101'),  -- Comunicación
(3, 'MARTES', '11:30:00', '12:15:00', 'Lab. Ciencias'), -- Ciencia
-- 1° Primaria A - Miércoles
(1, 'MIERCOLES', '08:00:00', '09:30:00', 'Aula 101'), -- Comunicación
(5, 'MIERCOLES', '09:45:00', '10:30:00', 'Sala Arte'), -- Arte
(6, 'MIERCOLES', '10:45:00', '11:30:00', 'Patio'),    -- Ed. Física
(8, 'MIERCOLES', '11:30:00', '12:15:00', 'Aula 101'), -- Inglés
-- 1° Primaria A - Jueves
(2, 'JUEVES', '08:00:00', '09:30:00', 'Aula 101'),   -- Matemática
(4, 'JUEVES', '09:45:00', '11:15:00', 'Aula 101'),   -- Personal Social
(7, 'JUEVES', '11:30:00', '12:15:00', 'Capilla'),    -- Religión
-- 1° Primaria A - Viernes
(1, 'VIERNES', '08:00:00', '09:30:00', 'Aula 101'),  -- Comunicación
(2, 'VIERNES', '09:45:00', '11:15:00', 'Aula 101'),  -- Matemática
(3, 'VIERNES', '11:30:00', '12:15:00', 'Lab. Ciencias'); -- Ciencia

-- =============================================
-- MENSAJE FINAL
-- =============================================
SELECT 'Datos de prueba insertados correctamente!' AS mensaje;
SELECT CONCAT('Usuarios: ', COUNT(*)) AS total FROM usuarios
UNION ALL
SELECT CONCAT('Estudiantes: ', COUNT(*)) FROM estudiantes
UNION ALL
SELECT CONCAT('Docentes: ', COUNT(*)) FROM docentes
UNION ALL
SELECT CONCAT('Matrículas: ', COUNT(*)) FROM matriculas
UNION ALL
SELECT CONCAT('Pagos: ', COUNT(*)) FROM pagos
UNION ALL
SELECT CONCAT('Notas: ', COUNT(*)) FROM notas;
