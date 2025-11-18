<?php
class AsignacionesController extends Controller {
    private $docenteModel;
    private $academicoModel;

    public function __construct() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        // Solo ADMIN y DIRECTOR pueden gestionar asignaciones
        if (!in_array($_SESSION['usuario_rol'], ['ADMIN', 'DIRECTOR'])) {
            $this->redirect('dashboard');
        }

        $this->docenteModel = $this->model('Docente');
        $this->academicoModel = $this->model('Academico');
    }

    public function index() {
        $docentes = $this->docenteModel->getAll();

        // Obtener asignaciones por docente
        $asignacionesPorDocente = [];
        foreach ($docentes as $docente) {
            $asignacionesPorDocente[$docente->id] = $this->docenteModel->getAsignaciones($docente->id);
        }

        $this->view('layouts/main', [
            'content' => 'asignaciones/index',
            'data' => [
                'docentes' => $docentes,
                'asignacionesPorDocente' => $asignacionesPorDocente
            ],
            'title' => 'Asignaciones Docentes'
        ]);
    }

    public function porDocente($docenteId = null) {
        if (!$docenteId) {
            $this->redirect('asignaciones');
        }

        $docente = $this->docenteModel->getById($docenteId);
        if (!$docente) {
            $_SESSION['error'] = 'Docente no encontrado';
            $this->redirect('asignaciones');
        }

        $asignaciones = $this->docenteModel->getAsignaciones($docenteId);
        $secciones = $this->academicoModel->getSecciones();
        $cursos = $this->academicoModel->getCursos();

        // Organizar cursos por grado
        $cursosPorGrado = [];
        foreach ($cursos as $curso) {
            $cursosPorGrado[$curso->grado_id][] = $curso;
        }

        $this->view('layouts/main', [
            'content' => 'asignaciones/por_docente',
            'data' => [
                'docente' => $docente,
                'asignaciones' => $asignaciones,
                'secciones' => $secciones,
                'cursosPorGrado' => $cursosPorGrado
            ],
            'title' => 'Asignaciones de ' . $docente->nombre
        ]);
    }

    public function agregar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('asignaciones');
        }

        $docenteId = $this->getPost('docente_id');
        $cursoId = $this->getPost('curso_id');
        $seccionId = $this->getPost('seccion_id');

        if (empty($docenteId) || empty($cursoId) || empty($seccionId)) {
            $_SESSION['error'] = 'Todos los campos son requeridos';
            $this->redirect('asignaciones/porDocente/' . $docenteId);
        }

        // Verificar que no exista ya la asignación
        $db = new Database();
        $db->query('SELECT id FROM asignaciones_profesor
                    WHERE profesor_id = :profesor_id AND curso_id = :curso_id AND seccion_id = :seccion_id');
        $db->bind(':profesor_id', $docenteId);
        $db->bind(':curso_id', $cursoId);
        $db->bind(':seccion_id', $seccionId);

        if ($db->single()) {
            $_SESSION['error'] = 'Esta asignación ya existe';
            $this->redirect('asignaciones/porDocente/' . $docenteId);
        }

        if ($this->docenteModel->agregarAsignacion($docenteId, $cursoId, $seccionId)) {
            $_SESSION['success'] = 'Asignación agregada correctamente';
        } else {
            $_SESSION['error'] = 'Error al agregar la asignación';
        }

        $this->redirect('asignaciones/porDocente/' . $docenteId);
    }

    public function eliminar($id = null) {
        if (!$id) {
            $this->redirect('asignaciones');
        }

        $asignacion = $this->docenteModel->getAsignacionById($id);
        if (!$asignacion) {
            $_SESSION['error'] = 'Asignación no encontrada';
            $this->redirect('asignaciones');
        }

        $docenteId = $asignacion->profesor_id;

        if ($this->docenteModel->eliminarAsignacion($id)) {
            $_SESSION['success'] = 'Asignación eliminada correctamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar la asignación';
        }

        $this->redirect('asignaciones/porDocente/' . $docenteId);
    }

    public function porSeccion($seccionId = null) {
        if (!$seccionId) {
            $this->redirect('asignaciones');
        }

        $seccion = $this->academicoModel->getSeccionById($seccionId);
        if (!$seccion) {
            $_SESSION['error'] = 'Sección no encontrada';
            $this->redirect('asignaciones');
        }

        // Obtener asignaciones de esta sección
        $db = new Database();
        $db->query('SELECT ap.*, c.nombre as curso_nombre,
                    CONCAT(u.nombre, " ", u.apellidos) as docente_nombre
                    FROM asignaciones_profesor ap
                    INNER JOIN cursos c ON ap.curso_id = c.id
                    INNER JOIN profesores p ON ap.profesor_id = p.id
                    INNER JOIN usuarios u ON p.usuario_id = u.id
                    WHERE ap.seccion_id = :seccion_id
                    ORDER BY c.nombre');
        $db->bind(':seccion_id', $seccionId);
        $asignaciones = $db->resultSet();

        $this->view('layouts/main', [
            'content' => 'asignaciones/por_seccion',
            'data' => [
                'seccion' => $seccion,
                'asignaciones' => $asignaciones
            ],
            'title' => 'Asignaciones de Sección ' . $seccion->nombre
        ]);
    }

    public function getCursosPorGrado() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([]);
            return;
        }

        $gradoId = $this->getPost('grado_id');

        $db = new Database();
        $db->query('SELECT id, nombre FROM cursos WHERE grado_id = :grado_id ORDER BY nombre');
        $db->bind(':grado_id', $gradoId);
        $cursos = $db->resultSet();

        header('Content-Type: application/json');
        echo json_encode($cursos);
    }
}
