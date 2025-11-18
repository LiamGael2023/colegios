<?php
class MatriculasController extends Controller {
    private $estudianteModel;
    private $academicoModel;

    public function __construct() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $this->estudianteModel = $this->model('Estudiante');
        $this->academicoModel = $this->model('Academico');
    }

    public function index() {
        $anioActivo = $this->academicoModel->getAnioActivo();
        $seccionId = $this->getGet('seccion_id');
        $estado = $this->getGet('estado') ?? 'ACTIVA';

        $secciones = $this->academicoModel->getSecciones();

        // Obtener matrículas
        $db = new Database();
        $sql = 'SELECT m.*, e.codigo, e.dni, e.nombres, e.apellido_paterno, e.apellido_materno,
                s.nombre as seccion_nombre, g.nombre as grado_nombre, n.nombre as nivel_nombre
                FROM matriculas m
                INNER JOIN estudiantes e ON m.estudiante_id = e.id
                INNER JOIN secciones s ON m.seccion_id = s.id
                INNER JOIN grados g ON s.grado_id = g.id
                INNER JOIN niveles n ON g.nivel_id = n.id
                WHERE m.anio_escolar_id = :anio_id';

        if ($seccionId) {
            $sql .= ' AND m.seccion_id = :seccion_id';
        }
        if ($estado) {
            $sql .= ' AND m.estado = :estado';
        }

        $sql .= ' ORDER BY n.id, g.numero, s.nombre, e.apellido_paterno, e.apellido_materno';

        $db->query($sql);
        $db->bind(':anio_id', $anioActivo->id);
        if ($seccionId) $db->bind(':seccion_id', $seccionId);
        if ($estado) $db->bind(':estado', $estado);

        $matriculas = $db->resultSet();

        $this->view('layouts/main', [
            'content' => 'matriculas/index',
            'data' => [
                'matriculas' => $matriculas,
                'secciones' => $secciones,
                'seccionId' => $seccionId,
                'estado' => $estado,
                'anio' => $anioActivo
            ],
            'title' => 'Matrículas'
        ]);
    }

    public function nueva() {
        $anioActivo = $this->academicoModel->getAnioActivo();
        $secciones = $this->academicoModel->getSecciones();

        // Estudiantes sin matrícula activa en el año actual
        $db = new Database();
        $db->query('SELECT e.* FROM estudiantes e
                    WHERE e.activo = TRUE
                    AND e.id NOT IN (
                        SELECT estudiante_id FROM matriculas
                        WHERE anio_escolar_id = :anio_id AND estado = "ACTIVA"
                    )
                    ORDER BY e.apellido_paterno, e.apellido_materno, e.nombres');
        $db->bind(':anio_id', $anioActivo->id);
        $estudiantesSinMatricula = $db->resultSet();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $estudianteId = $this->getPost('estudiante_id');
            $seccionId = $this->getPost('seccion_id');
            $tipoMatricula = $this->getPost('tipo_matricula');
            $procedencia = $this->getPost('procedencia');
            $observaciones = $this->getPost('observaciones');

            // Generar código de matrícula
            $codigo = 'MAT-' . $anioActivo->anio . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

            $db->query('INSERT INTO matriculas (codigo, estudiante_id, seccion_id, anio_escolar_id,
                        fecha_matricula, estado, tipo_matricula, procedencia, observaciones)
                        VALUES (:codigo, :estudiante_id, :seccion_id, :anio_id, CURDATE(),
                        "ACTIVA", :tipo, :procedencia, :observaciones)');
            $db->bind(':codigo', $codigo);
            $db->bind(':estudiante_id', $estudianteId);
            $db->bind(':seccion_id', $seccionId);
            $db->bind(':anio_id', $anioActivo->id);
            $db->bind(':tipo', $tipoMatricula);
            $db->bind(':procedencia', $procedencia);
            $db->bind(':observaciones', $observaciones);

            if ($db->execute()) {
                $_SESSION['success'] = 'Matrícula registrada correctamente. Código: ' . $codigo;
                $this->redirect('matriculas');
            } else {
                $_SESSION['error'] = 'Error al registrar la matrícula';
            }
        }

        $this->view('layouts/main', [
            'content' => 'matriculas/nueva',
            'data' => [
                'estudiantes' => $estudiantesSinMatricula,
                'secciones' => $secciones,
                'anio' => $anioActivo
            ],
            'title' => 'Nueva Matrícula'
        ]);
    }

    public function ver($id = null) {
        if (!$id) {
            $this->redirect('matriculas');
        }

        $db = new Database();
        $db->query('SELECT m.*, e.*, s.nombre as seccion_nombre, s.capacidad,
                    g.nombre as grado_nombre, n.nombre as nivel_nombre, ae.anio
                    FROM matriculas m
                    INNER JOIN estudiantes e ON m.estudiante_id = e.id
                    INNER JOIN secciones s ON m.seccion_id = s.id
                    INNER JOIN grados g ON s.grado_id = g.id
                    INNER JOIN niveles n ON g.nivel_id = n.id
                    INNER JOIN anios_escolares ae ON m.anio_escolar_id = ae.id
                    WHERE m.id = :id');
        $db->bind(':id', $id);
        $matricula = $db->single();

        if (!$matricula) {
            $_SESSION['error'] = 'Matrícula no encontrada';
            $this->redirect('matriculas');
        }

        // Obtener apoderados del estudiante
        $apoderadoModel = $this->model('Apoderado');
        $apoderados = $apoderadoModel->getByEstudiante($matricula->estudiante_id);

        $this->view('layouts/main', [
            'content' => 'matriculas/ver',
            'data' => [
                'matricula' => $matricula,
                'apoderados' => $apoderados
            ],
            'title' => 'Detalle de Matrícula'
        ]);
    }

    public function cambiarEstado($id = null) {
        if (!$id) {
            $this->redirect('matriculas');
        }

        // Solo ADMIN y DIRECTOR pueden cambiar estado
        if (!in_array($_SESSION['usuario_rol'], ['ADMIN', 'DIRECTOR', 'SECRETARIA'])) {
            $_SESSION['error'] = 'No tiene permisos para esta acción';
            $this->redirect('matriculas');
        }

        $estado = $this->getGet('estado');
        $estadosValidos = ['ACTIVA', 'RETIRADO', 'TRASLADADO', 'FINALIZADA'];

        if (!in_array($estado, $estadosValidos)) {
            $_SESSION['error'] = 'Estado no válido';
            $this->redirect('matriculas');
        }

        $db = new Database();
        $db->query('UPDATE matriculas SET estado = :estado WHERE id = :id');
        $db->bind(':estado', $estado);
        $db->bind(':id', $id);

        if ($db->execute()) {
            $_SESSION['success'] = 'Estado de matrícula actualizado';
        } else {
            $_SESSION['error'] = 'Error al actualizar el estado';
        }

        $this->redirect('matriculas');
    }

    public function ficha($id = null) {
        if (!$id) {
            $this->redirect('matriculas');
        }

        $db = new Database();
        $db->query('SELECT m.*, e.*, s.nombre as seccion_nombre,
                    g.nombre as grado_nombre, n.nombre as nivel_nombre, ae.anio
                    FROM matriculas m
                    INNER JOIN estudiantes e ON m.estudiante_id = e.id
                    INNER JOIN secciones s ON m.seccion_id = s.id
                    INNER JOIN grados g ON s.grado_id = g.id
                    INNER JOIN niveles n ON g.nivel_id = n.id
                    INNER JOIN anios_escolares ae ON m.anio_escolar_id = ae.id
                    WHERE m.id = :id');
        $db->bind(':id', $id);
        $matricula = $db->single();

        if (!$matricula) {
            $_SESSION['error'] = 'Matrícula no encontrada';
            $this->redirect('matriculas');
        }

        $apoderadoModel = $this->model('Apoderado');
        $apoderados = $apoderadoModel->getByEstudiante($matricula->estudiante_id);
        $institucion = $this->academicoModel->getInstitucion();

        $this->view('layouts/main', [
            'content' => 'matriculas/ficha',
            'data' => [
                'matricula' => $matricula,
                'apoderados' => $apoderados,
                'institucion' => $institucion
            ],
            'title' => 'Ficha de Matrícula'
        ]);
    }
}
