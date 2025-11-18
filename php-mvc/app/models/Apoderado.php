<?php
class Apoderado {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAll($buscar = '') {
        $sql = 'SELECT a.*, e.nombres as estudiante_nombres, e.apellido_paterno, e.apellido_materno, e.codigo
                FROM apoderados a
                INNER JOIN estudiantes e ON a.estudiante_id = e.id
                WHERE 1=1';

        if (!empty($buscar)) {
            $sql .= ' AND (a.nombres LIKE :buscar OR a.apellidos LIKE :buscar
                     OR a.dni LIKE :buscar OR e.nombres LIKE :buscar
                     OR e.apellido_paterno LIKE :buscar)';
        }

        $sql .= ' ORDER BY a.apellidos, a.nombres';

        $this->db->query($sql);

        if (!empty($buscar)) {
            $this->db->bind(':buscar', '%' . $buscar . '%');
        }

        return $this->db->resultSet();
    }

    public function getById($id) {
        $this->db->query('SELECT a.*, e.nombres as estudiante_nombres, e.apellido_paterno, e.apellido_materno
                         FROM apoderados a
                         INNER JOIN estudiantes e ON a.estudiante_id = e.id
                         WHERE a.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getByEstudiante($estudianteId) {
        $this->db->query('SELECT * FROM apoderados WHERE estudiante_id = :estudiante_id ORDER BY es_principal DESC, apellidos');
        $this->db->bind(':estudiante_id', $estudianteId);
        return $this->db->resultSet();
    }

    public function crear($data) {
        // Si es principal, quitar el flag de los demás
        if ($data['es_principal']) {
            $this->db->query('UPDATE apoderados SET es_principal = FALSE WHERE estudiante_id = :estudiante_id');
            $this->db->bind(':estudiante_id', $data['estudiante_id']);
            $this->db->execute();
        }

        $this->db->query('INSERT INTO apoderados (estudiante_id, dni, nombres, apellidos, parentesco,
                         telefono, telefono_trabajo, email, ocupacion, direccion, lugar_trabajo, es_principal)
                         VALUES (:estudiante_id, :dni, :nombres, :apellidos, :parentesco,
                         :telefono, :telefono_trabajo, :email, :ocupacion, :direccion, :lugar_trabajo, :es_principal)');

        $this->db->bind(':estudiante_id', $data['estudiante_id']);
        $this->db->bind(':dni', $data['dni']);
        $this->db->bind(':nombres', $data['nombres']);
        $this->db->bind(':apellidos', $data['apellidos']);
        $this->db->bind(':parentesco', $data['parentesco']);
        $this->db->bind(':telefono', $data['telefono']);
        $this->db->bind(':telefono_trabajo', $data['telefono_trabajo']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':ocupacion', $data['ocupacion']);
        $this->db->bind(':direccion', $data['direccion']);
        $this->db->bind(':lugar_trabajo', $data['lugar_trabajo']);
        $this->db->bind(':es_principal', $data['es_principal']);

        return $this->db->execute();
    }

    public function actualizar($data) {
        // Si es principal, quitar el flag de los demás
        if ($data['es_principal']) {
            $this->db->query('UPDATE apoderados SET es_principal = FALSE WHERE estudiante_id = :estudiante_id AND id != :id');
            $this->db->bind(':estudiante_id', $data['estudiante_id']);
            $this->db->bind(':id', $data['id']);
            $this->db->execute();
        }

        $this->db->query('UPDATE apoderados SET estudiante_id = :estudiante_id, dni = :dni, nombres = :nombres,
                         apellidos = :apellidos, parentesco = :parentesco, telefono = :telefono,
                         telefono_trabajo = :telefono_trabajo, email = :email, ocupacion = :ocupacion,
                         direccion = :direccion, lugar_trabajo = :lugar_trabajo, es_principal = :es_principal
                         WHERE id = :id');

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':estudiante_id', $data['estudiante_id']);
        $this->db->bind(':dni', $data['dni']);
        $this->db->bind(':nombres', $data['nombres']);
        $this->db->bind(':apellidos', $data['apellidos']);
        $this->db->bind(':parentesco', $data['parentesco']);
        $this->db->bind(':telefono', $data['telefono']);
        $this->db->bind(':telefono_trabajo', $data['telefono_trabajo']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':ocupacion', $data['ocupacion']);
        $this->db->bind(':direccion', $data['direccion']);
        $this->db->bind(':lugar_trabajo', $data['lugar_trabajo']);
        $this->db->bind(':es_principal', $data['es_principal']);

        return $this->db->execute();
    }

    public function eliminar($id) {
        $this->db->query('DELETE FROM apoderados WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function countAll() {
        $this->db->query('SELECT COUNT(*) as total FROM apoderados');
        $result = $this->db->single();
        return $result->total;
    }
}
