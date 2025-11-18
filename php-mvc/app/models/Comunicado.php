<?php
class Comunicado {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAll($activo = null) {
        $sql = 'SELECT c.*, u.nombre as usuario_nombre, u.apellidos as usuario_apellidos
                FROM comunicados c
                INNER JOIN usuarios u ON c.usuario_id = u.id';

        if ($activo !== null) {
            $sql .= ' WHERE c.activo = :activo';
        }

        $sql .= ' ORDER BY c.fecha_publicacion DESC';

        $this->db->query($sql);

        if ($activo !== null) {
            $this->db->bind(':activo', $activo);
        }

        return $this->db->resultSet();
    }

    public function getById($id) {
        $this->db->query('SELECT c.*, u.nombre as usuario_nombre, u.apellidos as usuario_apellidos
                         FROM comunicados c
                         INNER JOIN usuarios u ON c.usuario_id = u.id
                         WHERE c.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getActivos() {
        $this->db->query('SELECT c.*, u.nombre as usuario_nombre, u.apellidos as usuario_apellidos
                         FROM comunicados c
                         INNER JOIN usuarios u ON c.usuario_id = u.id
                         WHERE c.activo = TRUE
                         AND (c.fecha_expiracion IS NULL OR c.fecha_expiracion >= CURDATE())
                         ORDER BY c.fecha_publicacion DESC');
        return $this->db->resultSet();
    }

    public function crear($data) {
        $this->db->query('INSERT INTO comunicados (titulo, contenido, tipo, destinatario_id,
                         fecha_expiracion, usuario_id)
                         VALUES (:titulo, :contenido, :tipo, :destinatario_id, :fecha_expiracion, :usuario_id)');
        $this->db->bind(':titulo', $data['titulo']);
        $this->db->bind(':contenido', $data['contenido']);
        $this->db->bind(':tipo', $data['tipo']);
        $this->db->bind(':destinatario_id', $data['destinatario_id'] ?: null);
        $this->db->bind(':fecha_expiracion', $data['fecha_expiracion'] ?: null);
        $this->db->bind(':usuario_id', $data['usuario_id']);
        return $this->db->execute();
    }

    public function actualizar($id, $data) {
        $this->db->query('UPDATE comunicados SET titulo = :titulo, contenido = :contenido,
                         tipo = :tipo, destinatario_id = :destinatario_id,
                         fecha_expiracion = :fecha_expiracion WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':titulo', $data['titulo']);
        $this->db->bind(':contenido', $data['contenido']);
        $this->db->bind(':tipo', $data['tipo']);
        $this->db->bind(':destinatario_id', $data['destinatario_id'] ?: null);
        $this->db->bind(':fecha_expiracion', $data['fecha_expiracion'] ?: null);
        return $this->db->execute();
    }

    public function cambiarEstado($id, $activo) {
        $this->db->query('UPDATE comunicados SET activo = :activo WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':activo', $activo);
        return $this->db->execute();
    }

    public function eliminar($id) {
        $this->db->query('DELETE FROM comunicados WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function countActivos() {
        $this->db->query('SELECT COUNT(*) as total FROM comunicados WHERE activo = TRUE');
        return $this->db->single()->total;
    }
}
