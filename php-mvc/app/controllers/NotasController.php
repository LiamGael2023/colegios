<?php
class NotasController extends Controller {
    private $notaModel;
    private $academicoModel;
    private $estudianteModel;

    public function __construct() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $this->notaModel = $this->model('Nota');
        $this->academicoModel = $this->model('Academico');
        $this->estudianteModel = $this->model('Estudiante');
    }

    public function index() {
        $niveles = $this->academicoModel->getNiveles();
        $anioActivo = $this->academicoModel->getAnioActivo();
        $periodos = $anioActivo ? $this->academicoModel->getPeriodos($anioActivo->id) : [];

        $data = [
            'niveles' => $niveles,
            'anioActivo' => $anioActivo,
            'periodos' => $periodos,
            'secciones' => [],
            'cursos' => [],
            'estudiantes' => []
        ];

        $this->view('layouts/main', [
            'content' => 'notas/index',
            'data' => $data,
            'title' => 'Registro de Notas'
        ]);
    }

    public function registrar() {
        $this->requireRole(['ADMIN', 'DIRECTOR', 'PROFESOR']);

        $seccionId = $this->getQuery('seccion_id');
        $cursoId = $this->getQuery('curso_id');
        $periodoId = $this->getQuery('periodo_id');

        if (!$seccionId || !$cursoId || !$periodoId) {
            $this->redirect('notas');
        }

        $seccion = $this->academicoModel->getSeccionById($seccionId);
        $anioActivo = $this->academicoModel->getAnioActivo();
        $estudiantes = $this->notaModel->getBySeccionCurso($seccionId, $cursoId, $periodoId);

        // Obtener curso
        $db = new Database();
        $db->query('SELECT c.*, g.nivel_id FROM cursos c INNER JOIN grados g ON c.grado_id = g.id WHERE c.id = :id');
        $db->bind(':id', $cursoId);
        $curso = $db->single();

        // Determinar tipo de calificación
        $tipoCalificacion = $seccion->nivel_nombre == 'SECUNDARIA' ? 'VIGESIMAL' : 'LITERAL';

        // Obtener período
        $db->query('SELECT * FROM periodos WHERE id = :id');
        $db->bind(':id', $periodoId);
        $periodo = $db->single();

        $data = [
            'seccion' => $seccion,
            'curso' => $curso,
            'periodo' => $periodo,
            'estudiantes' => $estudiantes,
            'tipoCalificacion' => $tipoCalificacion,
            'error' => '',
            'success' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $notas = $_POST['notas'] ?? [];
            $errores = 0;

            foreach ($notas as $estudianteId => $calificacion) {
                if (!empty($calificacion)) {
                    $notaData = [
                        'estudiante_id' => $estudianteId,
                        'curso_id' => $cursoId,
                        'periodo_id' => $periodoId,
                        'calificacion' => $calificacion,
                        'tipo' => $tipoCalificacion
                    ];

                    if (!$this->notaModel->registrar($notaData)) {
                        $errores++;
                    }
                }
            }

            if ($errores == 0) {
                $data['success'] = 'Notas guardadas correctamente';
                // Recargar estudiantes
                $data['estudiantes'] = $this->notaModel->getBySeccionCurso($seccionId, $cursoId, $periodoId);
            } else {
                $data['error'] = 'Error al guardar algunas notas';
            }
        }

        $this->view('layouts/main', [
            'content' => 'notas/registrar',
            'data' => $data,
            'title' => 'Registrar Notas'
        ]);
    }

    // AJAX endpoints
    public function getSecciones() {
        $gradoId = $this->getQuery('grado_id');
        $secciones = $this->academicoModel->getSecciones($gradoId);
        header('Content-Type: application/json');
        echo json_encode($secciones);
        exit;
    }

    public function getCursos() {
        $gradoId = $this->getQuery('grado_id');
        $cursos = $this->academicoModel->getCursos($gradoId);
        header('Content-Type: application/json');
        echo json_encode($cursos);
        exit;
    }

    public function getGrados() {
        $nivelId = $this->getQuery('nivel_id');
        $grados = $this->academicoModel->getGrados($nivelId);
        header('Content-Type: application/json');
        echo json_encode($grados);
        exit;
    }
}
