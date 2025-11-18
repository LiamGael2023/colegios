<?php
class ReportesController extends Controller {
    private $academicoModel;
    private $notaModel;
    private $asistenciaModel;

    public function __construct() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $this->academicoModel = $this->model('Academico');
        $this->notaModel = $this->model('Nota');
        $this->asistenciaModel = $this->model('Asistencia');
    }

    public function index() {
        $this->view('layouts/main', [
            'content' => 'reportes/index',
            'data' => [],
            'title' => 'Reportes'
        ]);
    }

    public function libreta($estudianteId = null) {
        if (!$estudianteId) {
            $this->redirect('estudiantes');
        }

        $estudianteModel = $this->model('Estudiante');
        $estudiante = $estudianteModel->getWithDetails($estudianteId);
        $anioActivo = $this->academicoModel->getAnioActivo();
        $periodos = $anioActivo ? $this->academicoModel->getPeriodos($anioActivo->id) : [];

        // Obtener notas
        $notas = $anioActivo ? $this->notaModel->getLibreta($estudianteId, $anioActivo->id) : [];

        // Organizar notas por área y curso
        $notasPorArea = [];
        foreach ($notas as $nota) {
            if (!isset($notasPorArea[$nota->area_nombre])) {
                $notasPorArea[$nota->area_nombre] = [];
            }
            if (!isset($notasPorArea[$nota->area_nombre][$nota->curso_nombre])) {
                $notasPorArea[$nota->area_nombre][$nota->curso_nombre] = [];
            }
            $notasPorArea[$nota->area_nombre][$nota->curso_nombre][$nota->periodo_numero] = $nota->calificacion;
        }

        // Asistencia
        $asistencia = $anioActivo ? $this->asistenciaModel->getResumenEstudiante($estudianteId, $anioActivo->id) : null;

        // Institución
        $institucion = $this->academicoModel->getInstitucion();

        $data = [
            'estudiante' => $estudiante,
            'anioActivo' => $anioActivo,
            'periodos' => $periodos,
            'notasPorArea' => $notasPorArea,
            'asistencia' => $asistencia,
            'institucion' => $institucion
        ];

        $this->view('reportes/libreta', $data);
    }
}
