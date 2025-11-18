<?php
class Nota {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getByEstudiante($estudianteId, $anioEscolarId = null) {
        $sql = 'SELECT n.*, c.nombre as curso_nombre, a.nombre as area_nombre, p.nombre as periodo_nombre
                FROM notas n
                INNER JOIN cursos c ON n.curso_id = c.id
                INNER JOIN areas_curriculares a ON c.area_curricular_id = a.id
                INNER JOIN periodos p ON n.periodo_id = p.id';

        if ($anioEscolarId) {
            $sql .= ' WHERE n.estudiante_id = :estudiante_id AND p.anio_escolar_id = :anio_escolar_id';
        } else {
            $sql .= ' WHERE n.estudiante_id = :estudiante_id';
        }

        $sql .= ' ORDER BY a.nombre, c.nombre, p.numero';

        $this->db->query($sql);
        $this->db->bind(':estudiante_id', $estudianteId);

        if ($anioEscolarId) {
            $this->db->bind(':anio_escolar_id', $anioEscolarId);
        }

        return $this->db->resultSet();
    }

    public function getBySeccionCurso($seccionId, $cursoId, $periodoId) {
        $sql = 'SELECT e.id as estudiante_id, e.codigo, e.nombres, e.apellido_paterno, e.apellido_materno,
                n.id as nota_id, n.calificacion, n.tipo
                FROM estudiantes e
                INNER JOIN matriculas m ON e.id = m.estudiante_id
                LEFT JOIN notas n ON e.id = n.estudiante_id AND n.curso_id = :curso_id AND n.periodo_id = :periodo_id
                WHERE m.seccion_id = :seccion_id AND m.estado = "ACTIVA"
                ORDER BY e.apellido_paterno, e.apellido_materno, e.nombres';

        $this->db->query($sql);
        $this->db->bind(':seccion_id', $seccionId);
        $this->db->bind(':curso_id', $cursoId);
        $this->db->bind(':periodo_id', $periodoId);

        return $this->db->resultSet();
    }

    public function registrar($data) {
        // Verificar si ya existe
        $this->db->query('SELECT id FROM notas WHERE estudiante_id = :estudiante_id
                         AND curso_id = :curso_id AND periodo_id = :periodo_id');
        $this->db->bind(':estudiante_id', $data['estudiante_id']);
        $this->db->bind(':curso_id', $data['curso_id']);
        $this->db->bind(':periodo_id', $data['periodo_id']);

        $existe = $this->db->single();

        if ($existe) {
            // Actualizar
            $this->db->query('UPDATE notas SET calificacion = :calificacion, descripcion = :descripcion
                             WHERE id = :id');
            $this->db->bind(':id', $existe->id);
            $this->db->bind(':calificacion', $data['calificacion']);
            $this->db->bind(':descripcion', $data['descripcion'] ?? '');
        } else {
            // Insertar
            $this->db->query('INSERT INTO notas (estudiante_id, curso_id, periodo_id, calificacion, tipo, descripcion)
                             VALUES (:estudiante_id, :curso_id, :periodo_id, :calificacion, :tipo, :descripcion)');
            $this->db->bind(':estudiante_id', $data['estudiante_id']);
            $this->db->bind(':curso_id', $data['curso_id']);
            $this->db->bind(':periodo_id', $data['periodo_id']);
            $this->db->bind(':calificacion', $data['calificacion']);
            $this->db->bind(':tipo', $data['tipo']);
            $this->db->bind(':descripcion', $data['descripcion'] ?? '');
        }

        return $this->db->execute();
    }

    public function getLibreta($estudianteId, $anioEscolarId) {
        // Obtener todas las notas organizadas por área y curso
        $sql = 'SELECT a.nombre as area_nombre, c.nombre as curso_nombre,
                p.numero as periodo_numero, p.nombre as periodo_nombre, n.calificacion
                FROM notas n
                INNER JOIN cursos c ON n.curso_id = c.id
                INNER JOIN areas_curriculares a ON c.area_curricular_id = a.id
                INNER JOIN periodos p ON n.periodo_id = p.id
                WHERE n.estudiante_id = :estudiante_id AND p.anio_escolar_id = :anio_escolar_id
                ORDER BY a.nombre, c.nombre, p.numero';

        $this->db->query($sql);
        $this->db->bind(':estudiante_id', $estudianteId);
        $this->db->bind(':anio_escolar_id', $anioEscolarId);

        return $this->db->resultSet();
    }

    public function getByEstudiantePeriodo($estudianteId, $periodoId) {
        $sql = 'SELECT n.*, c.id as curso_id, c.nombre as curso_nombre
                FROM notas n
                INNER JOIN cursos c ON n.curso_id = c.id
                WHERE n.estudiante_id = :estudiante_id AND n.periodo_id = :periodo_id';

        $this->db->query($sql);
        $this->db->bind(':estudiante_id', $estudianteId);
        $this->db->bind(':periodo_id', $periodoId);

        return $this->db->resultSet();
    }
}
