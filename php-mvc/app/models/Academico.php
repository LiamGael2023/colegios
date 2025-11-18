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
}
