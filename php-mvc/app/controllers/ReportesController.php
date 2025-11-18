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

    public function consolidado() {
        $secciones = $this->academicoModel->getSecciones();
        $anioActivo = $this->academicoModel->getAnioActivo();
        $periodos = $anioActivo ? $this->academicoModel->getPeriodos($anioActivo->id) : [];

        $seccionId = $this->getGet('seccion');
        $periodoId = $this->getGet('periodo');
        $estudiantes = [];
        $cursos = [];
        $notas = [];
        $seccionSeleccionada = null;
        $periodoSeleccionado = null;

        if ($seccionId && $periodoId) {
            $seccionSeleccionada = $this->academicoModel->getSeccionById($seccionId);
            $estudianteModel = $this->model('Estudiante');
            $estudiantes = $estudianteModel->getBySeccion($seccionId, $anioActivo->id);

            // Obtener cursos del grado
            $cursos = $this->academicoModel->getCursos($seccionSeleccionada->grado_id);

            // Obtener notas de todos los estudiantes
            foreach ($estudiantes as $est) {
                $notasEst = $this->notaModel->getByEstudiantePeriodo($est->id, $periodoId);
                foreach ($notasEst as $nota) {
                    $notas[$est->id][$nota->curso_id] = $nota->calificacion;
                }
            }

            // Encontrar período seleccionado
            foreach ($periodos as $p) {
                if ($p->id == $periodoId) {
                    $periodoSeleccionado = $p;
                    break;
                }
            }
        }

        $institucion = $this->academicoModel->getInstitucion();

        $this->view('layouts/main', [
            'content' => 'reportes/consolidado',
            'data' => [
                'secciones' => $secciones,
                'periodos' => $periodos,
                'estudiantes' => $estudiantes,
                'cursos' => $cursos,
                'notas' => $notas,
                'seccionId' => $seccionId,
                'periodoId' => $periodoId,
                'seccion' => $seccionSeleccionada,
                'periodo' => $periodoSeleccionado,
                'anioActivo' => $anioActivo,
                'institucion' => $institucion
            ],
            'title' => 'Consolidado de Notas'
        ]);
    }

    public function constancia($estudianteId = null) {
        if (!$estudianteId) {
            $this->redirect('estudiantes');
        }

        $estudianteModel = $this->model('Estudiante');
        $estudiante = $estudianteModel->getWithDetails($estudianteId);
        $anioActivo = $this->academicoModel->getAnioActivo();
        $institucion = $this->academicoModel->getInstitucion();

        // Obtener matrícula actual
        $matricula = $estudianteModel->getMatriculaActual($estudianteId, $anioActivo ? $anioActivo->id : 0);

        $this->view('reportes/constancia', [
            'estudiante' => $estudiante,
            'matricula' => $matricula,
            'anioActivo' => $anioActivo,
            'institucion' => $institucion
        ]);
    }

    public function asistenciaMensual() {
        $secciones = $this->academicoModel->getSecciones();
        $anioActivo = $this->academicoModel->getAnioActivo();

        $seccionId = $this->getGet('seccion');
        $mes = $this->getGet('mes') ?: date('m');
        $anio = $this->getGet('anio') ?: date('Y');

        $estudiantes = [];
        $asistencias = [];
        $seccionSeleccionada = null;
        $diasDelMes = [];

        if ($seccionId && $anioActivo) {
            $seccionSeleccionada = $this->academicoModel->getSeccionById($seccionId);
            $estudianteModel = $this->model('Estudiante');
            $estudiantes = $estudianteModel->getBySeccion($seccionId, $anioActivo->id);

            // Obtener días del mes (solo días hábiles)
            $diasEnMes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
            for ($d = 1; $d <= $diasEnMes; $d++) {
                $fecha = sprintf('%04d-%02d-%02d', $anio, $mes, $d);
                $diaSemana = date('N', strtotime($fecha));
                if ($diaSemana < 6) { // Lunes a Viernes
                    $diasDelMes[] = $d;
                }
            }

            // Obtener asistencias del mes
            foreach ($estudiantes as $est) {
                $asistenciasMes = $this->asistenciaModel->getByEstudianteMes($est->id, $anio, $mes);
                foreach ($asistenciasMes as $asist) {
                    $dia = (int)date('d', strtotime($asist->fecha));
                    $asistencias[$est->id][$dia] = $asist->estado;
                }
            }
        }

        $this->view('layouts/main', [
            'content' => 'reportes/asistencia_mensual',
            'data' => [
                'secciones' => $secciones,
                'seccionId' => $seccionId,
                'mes' => $mes,
                'anio' => $anio,
                'estudiantes' => $estudiantes,
                'asistencias' => $asistencias,
                'diasDelMes' => $diasDelMes,
                'seccion' => $seccionSeleccionada,
                'anioActivo' => $anioActivo
            ],
            'title' => 'Asistencia Mensual'
        ]);
    }
}
