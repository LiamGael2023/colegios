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
    public function crearGrado($nivelId, $nombre, $numero) {
        $this->db->query('INSERT INTO grados (nivel_id, nombre, numero) VALUES (:nivel_id, :nombre, :numero)');
        $this->db->bind(':nivel_id', $nivelId);
        $this->db->bind(':nombre', $nombre);
        $this->db->bind(':numero', $numero);
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

    // ========== CRUD CURSOS ==========
    public function crearCurso($gradoId, $areaId, $nombre, $horasSemanales) {
        $this->db->query('INSERT INTO cursos (grado_id, area_curricular_id, nombre, horas_semanales)
                         VALUES (:grado_id, :area_id, :nombre, :horas)');
        $this->db->bind(':grado_id', $gradoId);
        $this->db->bind(':area_id', $areaId);
        $this->db->bind(':nombre', $nombre);
        $this->db->bind(':horas', $horasSemanales);
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
