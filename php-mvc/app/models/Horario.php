<?php
class Horario {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getBySeccion($seccionId) {
        $this->db->query('SELECT h.*, ap.curso_id, ap.seccion_id, ap.profesor_id,
                         c.nombre as curso_nombre,
                         CONCAT(u.nombre, " ", u.apellidos) as docente_nombre
                         FROM horarios h
                         INNER JOIN asignaciones_profesor ap ON h.asignacion_id = ap.id
                         INNER JOIN cursos c ON ap.curso_id = c.id
                         INNER JOIN profesores p ON ap.profesor_id = p.id
                         INNER JOIN usuarios u ON p.usuario_id = u.id
                         WHERE ap.seccion_id = :seccion_id
                         ORDER BY FIELD(h.dia, "LUNES", "MARTES", "MIERCOLES", "JUEVES", "VIERNES", "SABADO"), h.hora_inicio');
        $this->db->bind(':seccion_id', $seccionId);
        return $this->db->resultSet();
    }

    public function getByDocente($docenteId) {
        $this->db->query('SELECT h.*, ap.curso_id, ap.seccion_id,
                         c.nombre as curso_nombre,
                         s.nombre as seccion_nombre,
                         g.nombre as grado_nombre
                         FROM horarios h
                         INNER JOIN asignaciones_profesor ap ON h.asignacion_id = ap.id
                         INNER JOIN cursos c ON ap.curso_id = c.id
                         INNER JOIN secciones s ON ap.seccion_id = s.id
                         INNER JOIN grados g ON s.grado_id = g.id
                         WHERE ap.profesor_id = :docente_id
                         ORDER BY FIELD(h.dia, "LUNES", "MARTES", "MIERCOLES", "JUEVES", "VIERNES", "SABADO"), h.hora_inicio');
        $this->db->bind(':docente_id', $docenteId);
        return $this->db->resultSet();
    }

    public function getById($id) {
        $this->db->query('SELECT h.*, ap.curso_id, ap.seccion_id, ap.profesor_id
                         FROM horarios h
                         INNER JOIN asignaciones_profesor ap ON h.asignacion_id = ap.id
                         WHERE h.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function crear($asignacionId, $dia, $horaInicio, $horaFin, $aula = null) {
        // Verificar conflictos
        if ($this->hayConflicto($asignacionId, $dia, $horaInicio, $horaFin)) {
            return false;
        }

        $this->db->query('INSERT INTO horarios (asignacion_id, dia, hora_inicio, hora_fin, aula)
                         VALUES (:asignacion_id, :dia, :hora_inicio, :hora_fin, :aula)');
        $this->db->bind(':asignacion_id', $asignacionId);
        $this->db->bind(':dia', $dia);
        $this->db->bind(':hora_inicio', $horaInicio);
        $this->db->bind(':hora_fin', $horaFin);
        $this->db->bind(':aula', $aula);
        return $this->db->execute();
    }

    public function actualizar($id, $dia, $horaInicio, $horaFin, $aula = null) {
        $horario = $this->getById($id);
        if (!$horario) return false;

        // Verificar conflictos excluyendo el horario actual
        if ($this->hayConflicto($horario->asignacion_id, $dia, $horaInicio, $horaFin, $id)) {
            return false;
        }

        $this->db->query('UPDATE horarios SET dia = :dia, hora_inicio = :hora_inicio,
                         hora_fin = :hora_fin, aula = :aula WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':dia', $dia);
        $this->db->bind(':hora_inicio', $horaInicio);
        $this->db->bind(':hora_fin', $horaFin);
        $this->db->bind(':aula', $aula);
        return $this->db->execute();
    }

    public function eliminar($id) {
        $this->db->query('DELETE FROM horarios WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    private function hayConflicto($asignacionId, $dia, $horaInicio, $horaFin, $excluirId = null) {
        // Obtener la asignación para saber la sección y docente
        $this->db->query('SELECT seccion_id, profesor_id FROM asignaciones_profesor WHERE id = :id');
        $this->db->bind(':id', $asignacionId);
        $asignacion = $this->db->single();

        if (!$asignacion) return true;

        // Verificar conflicto en la misma sección (mismo salón, mismo horario)
        $sql = 'SELECT h.id FROM horarios h
                INNER JOIN asignaciones_profesor ap ON h.asignacion_id = ap.id
                WHERE ap.seccion_id = :seccion_id
                AND h.dia = :dia
                AND ((h.hora_inicio < :hora_fin AND h.hora_fin > :hora_inicio))';

        if ($excluirId) {
            $sql .= ' AND h.id != :excluir_id';
        }

        $this->db->query($sql);
        $this->db->bind(':seccion_id', $asignacion->seccion_id);
        $this->db->bind(':dia', $dia);
        $this->db->bind(':hora_inicio', $horaInicio);
        $this->db->bind(':hora_fin', $horaFin);

        if ($excluirId) {
            $this->db->bind(':excluir_id', $excluirId);
        }

        if ($this->db->single()) return true;

        // Verificar conflicto del docente (mismo docente, mismo horario en otra sección)
        $sql = 'SELECT h.id FROM horarios h
                INNER JOIN asignaciones_profesor ap ON h.asignacion_id = ap.id
                WHERE ap.profesor_id = :profesor_id
                AND h.dia = :dia
                AND ((h.hora_inicio < :hora_fin AND h.hora_fin > :hora_inicio))';

        if ($excluirId) {
            $sql .= ' AND h.id != :excluir_id';
        }

        $this->db->query($sql);
        $this->db->bind(':profesor_id', $asignacion->profesor_id);
        $this->db->bind(':dia', $dia);
        $this->db->bind(':hora_inicio', $horaInicio);
        $this->db->bind(':hora_fin', $horaFin);

        if ($excluirId) {
            $this->db->bind(':excluir_id', $excluirId);
        }

        return $this->db->single() ? true : false;
    }

    public function getAsignacionesSinHorario($seccionId) {
        $this->db->query('SELECT ap.id, ap.curso_id, c.nombre as curso_nombre,
                         CONCAT(u.nombre, " ", u.apellidos) as docente_nombre
                         FROM asignaciones_profesor ap
                         INNER JOIN cursos c ON ap.curso_id = c.id
                         INNER JOIN profesores p ON ap.profesor_id = p.id
                         INNER JOIN usuarios u ON p.usuario_id = u.id
                         WHERE ap.seccion_id = :seccion_id
                         ORDER BY c.nombre');
        $this->db->bind(':seccion_id', $seccionId);
        return $this->db->resultSet();
    }
}
