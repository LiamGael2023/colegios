<?php
class Asistencia {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getBySeccion($seccionId, $fecha, $anioEscolarId) {
        $sql = 'SELECT e.id as estudiante_id, e.codigo, e.nombres, e.apellido_paterno, e.apellido_materno,
                a.id as asistencia_id, a.estado, a.observacion
                FROM estudiantes e
                INNER JOIN matriculas m ON e.id = m.estudiante_id
                LEFT JOIN asistencias a ON e.id = a.estudiante_id AND a.fecha = :fecha
                WHERE m.seccion_id = :seccion_id AND m.estado = "ACTIVA" AND m.anio_escolar_id = :anio_escolar_id
                ORDER BY e.apellido_paterno, e.apellido_materno, e.nombres';

        $this->db->query($sql);
        $this->db->bind(':seccion_id', $seccionId);
        $this->db->bind(':fecha', $fecha);
        $this->db->bind(':anio_escolar_id', $anioEscolarId);

        return $this->db->resultSet();
    }

    public function getByEstudiante($estudianteId, $anioEscolarId = null, $mes = null) {
        $sql = 'SELECT * FROM asistencias WHERE estudiante_id = :estudiante_id';

        if ($anioEscolarId) {
            $sql .= ' AND anio_escolar_id = :anio_escolar_id';
        }

        if ($mes) {
            $sql .= ' AND MONTH(fecha) = :mes';
        }

        $sql .= ' ORDER BY fecha DESC';

        $this->db->query($sql);
        $this->db->bind(':estudiante_id', $estudianteId);

        if ($anioEscolarId) {
            $this->db->bind(':anio_escolar_id', $anioEscolarId);
        }

        if ($mes) {
            $this->db->bind(':mes', $mes);
        }

        return $this->db->resultSet();
    }

    public function registrar($data) {
        // Verificar si ya existe
        $this->db->query('SELECT id FROM asistencias WHERE estudiante_id = :estudiante_id AND fecha = :fecha');
        $this->db->bind(':estudiante_id', $data['estudiante_id']);
        $this->db->bind(':fecha', $data['fecha']);

        $existe = $this->db->single();

        if ($existe) {
            // Actualizar
            $this->db->query('UPDATE asistencias SET estado = :estado, observacion = :observacion WHERE id = :id');
            $this->db->bind(':id', $existe->id);
            $this->db->bind(':estado', $data['estado']);
            $this->db->bind(':observacion', $data['observacion'] ?? '');
        } else {
            // Insertar
            $this->db->query('INSERT INTO asistencias (estudiante_id, anio_escolar_id, fecha, estado, observacion)
                             VALUES (:estudiante_id, :anio_escolar_id, :fecha, :estado, :observacion)');
            $this->db->bind(':estudiante_id', $data['estudiante_id']);
            $this->db->bind(':anio_escolar_id', $data['anio_escolar_id']);
            $this->db->bind(':fecha', $data['fecha']);
            $this->db->bind(':estado', $data['estado']);
            $this->db->bind(':observacion', $data['observacion'] ?? '');
        }

        return $this->db->execute();
    }

    public function getReporte($seccionId, $anioEscolarId, $fechaInicio, $fechaFin) {
        $sql = 'SELECT e.id, e.codigo, e.nombres, e.apellido_paterno, e.apellido_materno,
                SUM(CASE WHEN a.estado = "PRESENTE" THEN 1 ELSE 0 END) as presente,
                SUM(CASE WHEN a.estado = "AUSENTE" THEN 1 ELSE 0 END) as ausente,
                SUM(CASE WHEN a.estado = "TARDANZA" THEN 1 ELSE 0 END) as tardanza,
                SUM(CASE WHEN a.estado = "JUSTIFICADO" THEN 1 ELSE 0 END) as justificado,
                COUNT(a.id) as total
                FROM estudiantes e
                INNER JOIN matriculas m ON e.id = m.estudiante_id
                LEFT JOIN asistencias a ON e.id = a.estudiante_id
                    AND a.fecha BETWEEN :fecha_inicio AND :fecha_fin
                    AND a.anio_escolar_id = :anio_escolar_id
                WHERE m.seccion_id = :seccion_id AND m.estado = "ACTIVA"
                GROUP BY e.id
                ORDER BY e.apellido_paterno, e.apellido_materno, e.nombres';

        $this->db->query($sql);
        $this->db->bind(':seccion_id', $seccionId);
        $this->db->bind(':anio_escolar_id', $anioEscolarId);
        $this->db->bind(':fecha_inicio', $fechaInicio);
        $this->db->bind(':fecha_fin', $fechaFin);

        return $this->db->resultSet();
    }

    public function getResumenEstudiante($estudianteId, $anioEscolarId) {
        $this->db->query('SELECT
                         SUM(CASE WHEN estado = "PRESENTE" THEN 1 ELSE 0 END) as presente,
                         SUM(CASE WHEN estado = "AUSENTE" THEN 1 ELSE 0 END) as ausente,
                         SUM(CASE WHEN estado = "TARDANZA" THEN 1 ELSE 0 END) as tardanza,
                         SUM(CASE WHEN estado = "JUSTIFICADO" THEN 1 ELSE 0 END) as justificado
                         FROM asistencias
                         WHERE estudiante_id = :estudiante_id AND anio_escolar_id = :anio_escolar_id');
        $this->db->bind(':estudiante_id', $estudianteId);
        $this->db->bind(':anio_escolar_id', $anioEscolarId);

        return $this->db->single();
    }
}
