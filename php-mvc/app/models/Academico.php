<?php
class Academico {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAnioActivo() {
        $this->db->query('SELECT * FROM anios_escolares WHERE activo = TRUE LIMIT 1');
        return $this->db->single();
    }

    public function getAnios() {
        $this->db->query('SELECT * FROM anios_escolares ORDER BY anio DESC');
        return $this->db->resultSet();
    }

    public function getPeriodos($anioEscolarId) {
        $this->db->query('SELECT * FROM periodos WHERE anio_escolar_id = :id ORDER BY numero');
        $this->db->bind(':id', $anioEscolarId);
        return $this->db->resultSet();
    }

    public function getAnioById($id) {
        $this->db->query('SELECT * FROM anios_escolares WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getPeriodoById($id) {
        $this->db->query('SELECT * FROM periodos WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function actualizarPeriodo($id, $fechaInicio, $fechaFin) {
        $this->db->query('UPDATE periodos SET fecha_inicio = :fecha_inicio, fecha_fin = :fecha_fin WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':fecha_inicio', $fechaInicio);
        $this->db->bind(':fecha_fin', $fechaFin);
        return $this->db->execute();
    }

    public function activarPeriodo($id) {
        $periodo = $this->getPeriodoById($id);
        if (!$periodo) return false;

        // Desactivar todos los períodos del mismo año
        $this->db->query('UPDATE periodos SET activo = FALSE WHERE anio_escolar_id = :anio_id');
        $this->db->bind(':anio_id', $periodo->anio_escolar_id);
        $this->db->execute();

        // Activar el período seleccionado
        $this->db->query('UPDATE periodos SET activo = TRUE WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getNiveles() {
        $this->db->query('SELECT * FROM niveles ORDER BY id');
        return $this->db->resultSet();
    }

    public function getGrados($nivelId = null) {
        $sql = 'SELECT g.*, n.nombre as nivel_nombre
                FROM grados g
                INNER JOIN niveles n ON g.nivel_id = n.id';

        if ($nivelId) {
            $sql .= ' WHERE g.nivel_id = :nivel_id';
        }

        $sql .= ' ORDER BY n.id, g.numero';

        $this->db->query($sql);

        if ($nivelId) {
            $this->db->bind(':nivel_id', $nivelId);
        }

        return $this->db->resultSet();
    }

    public function getSecciones($gradoId = null) {
        $sql = 'SELECT s.*, g.nombre as grado_nombre, n.nombre as nivel_nombre
                FROM secciones s
                INNER JOIN grados g ON s.grado_id = g.id
                INNER JOIN niveles n ON g.nivel_id = n.id';

        if ($gradoId) {
            $sql .= ' WHERE s.grado_id = :grado_id';
        }

        $sql .= ' ORDER BY n.id, g.numero, s.nombre';

        $this->db->query($sql);

        if ($gradoId) {
            $this->db->bind(':grado_id', $gradoId);
        }

        return $this->db->resultSet();
    }

    public function getCursos($gradoId = null) {
        $sql = 'SELECT c.*, a.nombre as area_nombre, g.nombre as grado_nombre
                FROM cursos c
                INNER JOIN areas_curriculares a ON c.area_curricular_id = a.id
                INNER JOIN grados g ON c.grado_id = g.id';

        if ($gradoId) {
            $sql .= ' WHERE c.grado_id = :grado_id';
        }

        $sql .= ' ORDER BY a.nombre, c.nombre';

        $this->db->query($sql);

        if ($gradoId) {
            $this->db->bind(':grado_id', $gradoId);
        }

        return $this->db->resultSet();
    }

    public function getAreas() {
        $this->db->query('SELECT * FROM areas_curriculares ORDER BY nombre');
        return $this->db->resultSet();
    }

    public function getSeccionById($id) {
        $this->db->query('SELECT s.*, g.nombre as grado_nombre, g.id as grado_id,
                         n.nombre as nivel_nombre, n.id as nivel_id
                         FROM secciones s
                         INNER JOIN grados g ON s.grado_id = g.id
                         INNER JOIN niveles n ON g.nivel_id = n.id
                         WHERE s.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getInstitucion() {
        $this->db->query('SELECT * FROM institucion LIMIT 1');
        return $this->db->single();
    }

    // ========== CRUD AÑOS ESCOLARES ==========
    public function crearAnio($anio, $fechaInicio, $fechaFin) {
        $this->db->query('INSERT INTO anios_escolares (anio, fecha_inicio, fecha_fin, activo)
                         VALUES (:anio, :fecha_inicio, :fecha_fin, FALSE)');
        $this->db->bind(':anio', $anio);
        $this->db->bind(':fecha_inicio', $fechaInicio);
        $this->db->bind(':fecha_fin', $fechaFin);

        if ($this->db->execute()) {
            $anioId = $this->db->lastInsertId();
            $periodos = [
                ['Primer Bimestre', 1],
                ['Segundo Bimestre', 2],
                ['Tercer Bimestre', 3],
                ['Cuarto Bimestre', 4]
            ];
            foreach ($periodos as $p) {
                $this->db->query('INSERT INTO periodos (anio_escolar_id, nombre, numero) VALUES (:anio_id, :nombre, :numero)');
                $this->db->bind(':anio_id', $anioId);
                $this->db->bind(':nombre', $p[0]);
                $this->db->bind(':numero', $p[1]);
                $this->db->execute();
            }
            return true;
        }
        return false;
    }

    public function activarAnio($id) {
        $this->db->query('UPDATE anios_escolares SET activo = FALSE');
        $this->db->execute();
        $this->db->query('UPDATE anios_escolares SET activo = TRUE WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // ========== CRUD GRADOS ==========
    public function getGradoById($id) {
        $this->db->query('SELECT g.*, n.nombre as nivel_nombre FROM grados g
                         INNER JOIN niveles n ON g.nivel_id = n.id WHERE g.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function crearGrado($nivelId, $nombre, $numero) {
        $this->db->query('INSERT INTO grados (nivel_id, nombre, numero) VALUES (:nivel_id, :nombre, :numero)');
        $this->db->bind(':nivel_id', $nivelId);
        $this->db->bind(':nombre', $nombre);
        $this->db->bind(':numero', $numero);
        return $this->db->execute();
    }

    public function actualizarGrado($id, $nivelId, $nombre, $numero) {
        $this->db->query('UPDATE grados SET nivel_id = :nivel_id, nombre = :nombre, numero = :numero WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':nivel_id', $nivelId);
        $this->db->bind(':nombre', $nombre);
        $this->db->bind(':numero', $numero);
        return $this->db->execute();
    }

    public function eliminarGrado($id) {
        // Verificar que no tenga secciones
        $this->db->query('SELECT COUNT(*) as total FROM secciones WHERE grado_id = :id');
        $this->db->bind(':id', $id);
        $result = $this->db->single();
        if ($result->total > 0) return false;

        $this->db->query('DELETE FROM grados WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // ========== CRUD SECCIONES ==========
    public function crearSeccion($gradoId, $nombre, $capacidad) {
        $this->db->query('INSERT INTO secciones (grado_id, nombre, capacidad) VALUES (:grado_id, :nombre, :capacidad)');
        $this->db->bind(':grado_id', $gradoId);
        $this->db->bind(':nombre', $nombre);
        $this->db->bind(':capacidad', $capacidad);
        return $this->db->execute();
    }

    public function actualizarSeccion($id, $gradoId, $nombre, $capacidad) {
        $this->db->query('UPDATE secciones SET grado_id = :grado_id, nombre = :nombre, capacidad = :capacidad WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':grado_id', $gradoId);
        $this->db->bind(':nombre', $nombre);
        $this->db->bind(':capacidad', $capacidad);
        return $this->db->execute();
    }

    public function eliminarSeccion($id) {
        // Verificar que no tenga matrículas
        $this->db->query('SELECT COUNT(*) as total FROM matriculas WHERE seccion_id = :id');
        $this->db->bind(':id', $id);
        $result = $this->db->single();
        if ($result->total > 0) return false;

        // Verificar que no tenga asignaciones
        $this->db->query('SELECT COUNT(*) as total FROM asignaciones_profesor WHERE seccion_id = :id');
        $this->db->bind(':id', $id);
        $result = $this->db->single();
        if ($result->total > 0) return false;

        $this->db->query('DELETE FROM secciones WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // ========== CRUD CURSOS ==========
    public function getCursoById($id) {
        $this->db->query('SELECT c.*, a.nombre as area_nombre, g.nombre as grado_nombre
                         FROM cursos c
                         INNER JOIN areas_curriculares a ON c.area_curricular_id = a.id
                         INNER JOIN grados g ON c.grado_id = g.id
                         WHERE c.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function crearCurso($gradoId, $areaId, $nombre, $horasSemanales) {
        $this->db->query('INSERT INTO cursos (grado_id, area_curricular_id, nombre, horas_semanales)
                         VALUES (:grado_id, :area_id, :nombre, :horas)');
        $this->db->bind(':grado_id', $gradoId);
        $this->db->bind(':area_id', $areaId);
        $this->db->bind(':nombre', $nombre);
        $this->db->bind(':horas', $horasSemanales);
        return $this->db->execute();
    }

    public function actualizarCurso($id, $gradoId, $areaId, $nombre, $horasSemanales) {
        $this->db->query('UPDATE cursos SET grado_id = :grado_id, area_curricular_id = :area_id,
                         nombre = :nombre, horas_semanales = :horas WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':grado_id', $gradoId);
        $this->db->bind(':area_id', $areaId);
        $this->db->bind(':nombre', $nombre);
        $this->db->bind(':horas', $horasSemanales);
        return $this->db->execute();
    }

    public function eliminarCurso($id) {
        // Verificar que no tenga notas
        $this->db->query('SELECT COUNT(*) as total FROM notas WHERE curso_id = :id');
        $this->db->bind(':id', $id);
        $result = $this->db->single();
        if ($result->total > 0) return false;

        // Verificar que no tenga asignaciones
        $this->db->query('SELECT COUNT(*) as total FROM asignaciones_profesor WHERE curso_id = :id');
        $this->db->bind(':id', $id);
        $result = $this->db->single();
        if ($result->total > 0) return false;

        $this->db->query('DELETE FROM cursos WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // ========== CRUD ÁREAS CURRICULARES ==========
    public function getAreaById($id) {
        $this->db->query('SELECT * FROM areas_curriculares WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function crearArea($nombre, $descripcion) {
        $this->db->query('INSERT INTO areas_curriculares (nombre, descripcion) VALUES (:nombre, :descripcion)');
        $this->db->bind(':nombre', $nombre);
        $this->db->bind(':descripcion', $descripcion);
        return $this->db->execute();
    }

    public function actualizarArea($id, $nombre, $descripcion) {
        $this->db->query('UPDATE areas_curriculares SET nombre = :nombre, descripcion = :descripcion WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':nombre', $nombre);
        $this->db->bind(':descripcion', $descripcion);
        return $this->db->execute();
    }

    public function eliminarArea($id) {
        // Verificar que no tenga cursos asociados
        $this->db->query('SELECT COUNT(*) as total FROM cursos WHERE area_curricular_id = :id');
        $this->db->bind(':id', $id);
        $result = $this->db->single();

        if ($result->total > 0) {
            return false;
        }

        $this->db->query('DELETE FROM areas_curriculares WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // ========== INSTITUCIÓN ==========
    public function actualizarInstitucion($nombre, $codigo, $direccion, $telefono, $email, $director, $ugel) {
        $this->db->query('UPDATE institucion SET nombre = :nombre, codigo_modular = :codigo,
                         direccion = :direccion, telefono = :telefono, email = :email,
                         director = :director, ugel = :ugel WHERE id = 1');
        $this->db->bind(':nombre', $nombre);
        $this->db->bind(':codigo', $codigo);
        $this->db->bind(':direccion', $direccion);
        $this->db->bind(':telefono', $telefono);
        $this->db->bind(':email', $email);
        $this->db->bind(':director', $director);
        $this->db->bind(':ugel', $ugel);
        return $this->db->execute();
    }
}
