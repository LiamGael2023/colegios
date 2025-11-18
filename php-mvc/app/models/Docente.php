<?php
class Docente {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAll() {
        $this->db->query('SELECT p.*, u.nombre, u.apellidos, u.email, u.dni, u.telefono, u.activo
                         FROM profesores p
                         INNER JOIN usuarios u ON p.usuario_id = u.id
                         ORDER BY u.apellidos, u.nombre');
        return $this->db->resultSet();
    }

    public function getById($id) {
        $this->db->query('SELECT p.*, u.nombre, u.apellidos, u.email, u.dni, u.telefono
                         FROM profesores p
                         INNER JOIN usuarios u ON p.usuario_id = u.id
                         WHERE p.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getUsuariosSinDocente() {
        $this->db->query('SELECT u.id, u.nombre, u.apellidos, u.dni
                         FROM usuarios u
                         WHERE u.rol = "PROFESOR"
                         AND u.activo = TRUE
                         AND u.id NOT IN (SELECT usuario_id FROM profesores)
                         ORDER BY u.apellidos, u.nombre');
        return $this->db->resultSet();
    }

    public function crear($usuarioId, $especialidad) {
        $this->db->query('INSERT INTO profesores (usuario_id, especialidad) VALUES (:usuario_id, :especialidad)');
        $this->db->bind(':usuario_id', $usuarioId);
        $this->db->bind(':especialidad', $especialidad);
        return $this->db->execute();
    }

    public function actualizar($id, $especialidad) {
        $this->db->query('UPDATE profesores SET especialidad = :especialidad WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':especialidad', $especialidad);
        return $this->db->execute();
    }

    public function eliminar($id) {
        // Verificar que no tenga asignaciones
        $this->db->query('SELECT COUNT(*) as total FROM asignaciones_profesor WHERE profesor_id = :id');
        $this->db->bind(':id', $id);
        $result = $this->db->single();

        if ($result->total > 0) {
            return false;
        }

        $this->db->query('DELETE FROM profesores WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // ========== ASIGNACIONES ==========
    public function getAsignaciones($docenteId) {
        $this->db->query('SELECT ap.*, c.nombre as curso_nombre, s.nombre as seccion_nombre,
                         g.nombre as grado_nombre, n.nombre as nivel_nombre
                         FROM asignaciones_profesor ap
                         INNER JOIN cursos c ON ap.curso_id = c.id
                         INNER JOIN secciones s ON ap.seccion_id = s.id
                         INNER JOIN grados g ON s.grado_id = g.id
                         INNER JOIN niveles n ON g.nivel_id = n.id
                         WHERE ap.profesor_id = :docente_id
                         ORDER BY n.id, g.numero, s.nombre, c.nombre');
        $this->db->bind(':docente_id', $docenteId);
        return $this->db->resultSet();
    }

    public function getAsignacionById($id) {
        $this->db->query('SELECT * FROM asignaciones_profesor WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function agregarAsignacion($docenteId, $cursoId, $seccionId) {
        $this->db->query('INSERT INTO asignaciones_profesor (profesor_id, curso_id, seccion_id)
                         VALUES (:profesor_id, :curso_id, :seccion_id)');
        $this->db->bind(':profesor_id', $docenteId);
        $this->db->bind(':curso_id', $cursoId);
        $this->db->bind(':seccion_id', $seccionId);
        return $this->db->execute();
    }

    public function eliminarAsignacion($id) {
        $this->db->query('DELETE FROM asignaciones_profesor WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function countAsignaciones($docenteId) {
        $this->db->query('SELECT COUNT(*) as total FROM asignaciones_profesor WHERE profesor_id = :id');
        $this->db->bind(':id', $docenteId);
        $result = $this->db->single();
        return $result->total;
    }
}
