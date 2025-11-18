<?php
class Estudiante {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAll($buscar = '', $activo = true) {
        $sql = 'SELECT e.*,
                m.id as matricula_id, m.codigo as matricula_codigo, m.estado as matricula_estado,
                s.nombre as seccion_nombre, g.nombre as grado_nombre, n.nombre as nivel_nombre,
                a.nombres as apoderado_nombres, a.apellidos as apoderado_apellidos, a.telefono as apoderado_telefono
                FROM estudiantes e
                LEFT JOIN matriculas m ON e.id = m.estudiante_id AND m.estado = "ACTIVA"
                LEFT JOIN secciones s ON m.seccion_id = s.id
                LEFT JOIN grados g ON s.grado_id = g.id
                LEFT JOIN niveles n ON g.nivel_id = n.id
                LEFT JOIN apoderados a ON e.id = a.estudiante_id AND a.es_principal = TRUE
                WHERE e.activo = :activo';

        if (!empty($buscar)) {
            $sql .= ' AND (e.nombres LIKE :buscar OR e.apellido_paterno LIKE :buscar
                     OR e.apellido_materno LIKE :buscar OR e.dni LIKE :buscar OR e.codigo LIKE :buscar)';
        }

        $sql .= ' ORDER BY e.apellido_paterno, e.apellido_materno, e.nombres';

        $this->db->query($sql);
        $this->db->bind(':activo', $activo);

        if (!empty($buscar)) {
            $this->db->bind(':buscar', '%' . $buscar . '%');
        }

        return $this->db->resultSet();
    }

    public function findById($id) {
        $this->db->query('SELECT * FROM estudiantes WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getWithDetails($id) {
        $sql = 'SELECT e.*,
                m.id as matricula_id, m.codigo as matricula_codigo, m.estado as matricula_estado,
                m.fecha_matricula, m.tipo_matricula,
                s.id as seccion_id, s.nombre as seccion_nombre,
                g.id as grado_id, g.nombre as grado_nombre,
                n.id as nivel_id, n.nombre as nivel_nombre
                FROM estudiantes e
                LEFT JOIN matriculas m ON e.id = m.estudiante_id AND m.estado = "ACTIVA"
                LEFT JOIN secciones s ON m.seccion_id = s.id
                LEFT JOIN grados g ON s.grado_id = g.id
                LEFT JOIN niveles n ON g.nivel_id = n.id
                WHERE e.id = :id';

        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function create($data) {
        // Generar código
        $this->db->query('SELECT COUNT(*) as total FROM estudiantes');
        $count = $this->db->single()->total;
        $codigo = 'EST' . date('Y') . str_pad($count + 1, 5, '0', STR_PAD_LEFT);

        $this->db->query('INSERT INTO estudiantes (codigo, dni, nombres, apellido_paterno, apellido_materno,
                         fecha_nacimiento, genero, direccion, telefono, email, lugar_nacimiento,
                         nacionalidad, lengua, religion, tipo_sangre, alergias, discapacidad, observaciones, foto)
                         VALUES (:codigo, :dni, :nombres, :apellido_paterno, :apellido_materno,
                         :fecha_nacimiento, :genero, :direccion, :telefono, :email, :lugar_nacimiento,
                         :nacionalidad, :lengua, :religion, :tipo_sangre, :alergias, :discapacidad, :observaciones, :foto)');

        $this->db->bind(':codigo', $codigo);
        $this->db->bind(':dni', $data['dni']);
        $this->db->bind(':nombres', $data['nombres']);
        $this->db->bind(':apellido_paterno', $data['apellido_paterno']);
        $this->db->bind(':apellido_materno', $data['apellido_materno']);
        $this->db->bind(':fecha_nacimiento', $data['fecha_nacimiento']);
        $this->db->bind(':genero', $data['genero']);
        $this->db->bind(':direccion', $data['direccion'] ?? '');
        $this->db->bind(':telefono', $data['telefono'] ?? '');
        $this->db->bind(':email', $data['email'] ?? '');
        $this->db->bind(':lugar_nacimiento', $data['lugar_nacimiento'] ?? '');
        $this->db->bind(':nacionalidad', $data['nacionalidad'] ?? 'Peruana');
        $this->db->bind(':lengua', $data['lengua'] ?? 'Castellano');
        $this->db->bind(':religion', $data['religion'] ?? '');
        $this->db->bind(':tipo_sangre', $data['tipo_sangre'] ?? '');
        $this->db->bind(':alergias', $data['alergias'] ?? '');
        $this->db->bind(':discapacidad', $data['discapacidad'] ?? '');
        $this->db->bind(':observaciones', $data['observaciones'] ?? '');
        $this->db->bind(':foto', $data['foto'] ?? null);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function update($id, $data) {
        $sql = 'UPDATE estudiantes SET dni = :dni, nombres = :nombres,
                apellido_paterno = :apellido_paterno, apellido_materno = :apellido_materno,
                fecha_nacimiento = :fecha_nacimiento, genero = :genero, direccion = :direccion,
                telefono = :telefono, email = :email, observaciones = :observaciones';

        // Agregar foto solo si se proporciona
        if (isset($data['foto'])) {
            $sql .= ', foto = :foto';
        }

        $sql .= ' WHERE id = :id';

        $this->db->query($sql);

        $this->db->bind(':id', $id);
        $this->db->bind(':dni', $data['dni']);
        $this->db->bind(':nombres', $data['nombres']);
        $this->db->bind(':apellido_paterno', $data['apellido_paterno']);
        $this->db->bind(':apellido_materno', $data['apellido_materno']);
        $this->db->bind(':fecha_nacimiento', $data['fecha_nacimiento']);
        $this->db->bind(':genero', $data['genero']);
        $this->db->bind(':direccion', $data['direccion'] ?? '');
        $this->db->bind(':telefono', $data['telefono'] ?? '');
        $this->db->bind(':email', $data['email'] ?? '');
        $this->db->bind(':observaciones', $data['observaciones'] ?? '');

        if (isset($data['foto'])) {
            $this->db->bind(':foto', $data['foto']);
        }

        return $this->db->execute();
    }

    public function getApoderados($estudianteId) {
        $this->db->query('SELECT * FROM apoderados WHERE estudiante_id = :id ORDER BY es_principal DESC');
        $this->db->bind(':id', $estudianteId);
        return $this->db->resultSet();
    }

    public function getBySeccion($seccionId, $anioEscolarId = null) {
        $sql = 'SELECT e.*, m.codigo as matricula_codigo
                FROM estudiantes e
                INNER JOIN matriculas m ON e.id = m.estudiante_id
                WHERE m.seccion_id = :seccion_id AND m.estado = "ACTIVA"';

        if ($anioEscolarId) {
            $sql .= ' AND m.anio_escolar_id = :anio_escolar_id';
        }

        $sql .= ' ORDER BY e.apellido_paterno, e.apellido_materno, e.nombres';

        $this->db->query($sql);
        $this->db->bind(':seccion_id', $seccionId);

        if ($anioEscolarId) {
            $this->db->bind(':anio_escolar_id', $anioEscolarId);
        }

        return $this->db->resultSet();
    }

    public function matricular($data) {
        // Generar código de matrícula
        $this->db->query('SELECT COUNT(*) as total FROM matriculas WHERE anio_escolar_id = :anio');
        $this->db->bind(':anio', $data['anio_escolar_id']);
        $count = $this->db->single()->total;

        $this->db->query('SELECT anio FROM anios_escolares WHERE id = :id');
        $this->db->bind(':id', $data['anio_escolar_id']);
        $anio = $this->db->single()->anio;

        $codigo = 'MAT' . $anio . str_pad($count + 1, 5, '0', STR_PAD_LEFT);

        $this->db->query('INSERT INTO matriculas (codigo, estudiante_id, seccion_id, anio_escolar_id,
                         fecha_matricula, tipo_matricula, observaciones)
                         VALUES (:codigo, :estudiante_id, :seccion_id, :anio_escolar_id,
                         CURDATE(), :tipo_matricula, :observaciones)');

        $this->db->bind(':codigo', $codigo);
        $this->db->bind(':estudiante_id', $data['estudiante_id']);
        $this->db->bind(':seccion_id', $data['seccion_id']);
        $this->db->bind(':anio_escolar_id', $data['anio_escolar_id']);
        $this->db->bind(':tipo_matricula', $data['tipo_matricula'] ?? 'REGULAR');
        $this->db->bind(':observaciones', $data['observaciones'] ?? '');

        return $this->db->execute();
    }

    public function getMatriculaActual($estudianteId, $anioEscolarId) {
        $sql = 'SELECT m.*, s.nombre as seccion_nombre, g.nombre as grado_nombre, n.nombre as nivel_nombre
                FROM matriculas m
                INNER JOIN secciones s ON m.seccion_id = s.id
                INNER JOIN grados g ON s.grado_id = g.id
                INNER JOIN niveles n ON g.nivel_id = n.id
                WHERE m.estudiante_id = :estudiante_id AND m.anio_escolar_id = :anio_escolar_id';

        $this->db->query($sql);
        $this->db->bind(':estudiante_id', $estudianteId);
        $this->db->bind(':anio_escolar_id', $anioEscolarId);

        return $this->db->single();
    }

    public function getBySeccion($seccionId, $anioEscolarId) {
        $sql = 'SELECT e.*, m.codigo as matricula_codigo
                FROM estudiantes e
                INNER JOIN matriculas m ON e.id = m.estudiante_id
                WHERE m.seccion_id = :seccion_id AND m.anio_escolar_id = :anio_escolar_id
                AND m.estado = "ACTIVA"
                ORDER BY e.apellido_paterno, e.apellido_materno, e.nombres';

        $this->db->query($sql);
        $this->db->bind(':seccion_id', $seccionId);
        $this->db->bind(':anio_escolar_id', $anioEscolarId);

        return $this->db->resultSet();
    }
}
