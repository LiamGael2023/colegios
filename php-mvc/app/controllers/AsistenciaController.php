<?php
class AsistenciaController extends Controller {
    private $asistenciaModel;
    private $academicoModel;

    public function __construct() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $this->asistenciaModel = $this->model('Asistencia');
        $this->academicoModel = $this->model('Academico');
    }

    public function index() {
        $niveles = $this->academicoModel->getNiveles();
        $anioActivo = $this->academicoModel->getAnioActivo();

        $data = [
            'niveles' => $niveles,
            'anioActivo' => $anioActivo,
            'fecha' => date('Y-m-d')
        ];

        $this->view('layouts/main', [
            'content' => 'asistencia/index',
            'data' => $data,
            'title' => 'Control de Asistencia'
        ]);
    }

    public function registrar() {
        $this->requireRole(['ADMIN', 'DIRECTOR', 'PROFESOR', 'SECRETARIA']);

        $seccionId = $this->getQuery('seccion_id');
        $fecha = $this->getQuery('fecha') ?: date('Y-m-d');

        if (!$seccionId) {
            $this->redirect('asistencia');
        }

        $seccion = $this->academicoModel->getSeccionById($seccionId);
        $anioActivo = $this->academicoModel->getAnioActivo();
        $estudiantes = $this->asistenciaModel->getBySeccion($seccionId, $fecha, $anioActivo->id);

        $data = [
            'seccion' => $seccion,
            'fecha' => $fecha,
            'estudiantes' => $estudiantes,
            'anioActivo' => $anioActivo,
            'error' => '',
            'success' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $asistencias = $_POST['asistencia'] ?? [];
            $errores = 0;

            foreach ($asistencias as $estudianteId => $estado) {
                $asistenciaData = [
                    'estudiante_id' => $estudianteId,
                    'anio_escolar_id' => $anioActivo->id,
                    'fecha' => $fecha,
                    'estado' => $estado,
                    'observacion' => $_POST['observacion'][$estudianteId] ?? ''
                ];

                if (!$this->asistenciaModel->registrar($asistenciaData)) {
                    $errores++;
                }
            }

            if ($errores == 0) {
                $data['success'] = 'Asistencia guardada correctamente';
                // Recargar estudiantes
                $data['estudiantes'] = $this->asistenciaModel->getBySeccion($seccionId, $fecha, $anioActivo->id);
            } else {
                $data['error'] = 'Error al guardar asistencia';
            }
        }

        $this->view('layouts/main', [
            'content' => 'asistencia/registrar',
            'data' => $data,
            'title' => 'Registrar Asistencia'
        ]);
    }

    public function reporte() {
        $niveles = $this->academicoModel->getNiveles();
        $anioActivo = $this->academicoModel->getAnioActivo();

        $data = [
            'niveles' => $niveles,
            'anioActivo' => $anioActivo,
            'reporte' => []
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST' || $this->getQuery('seccion_id')) {
            $seccionId = $this->getPost('seccion_id') ?: $this->getQuery('seccion_id');
            $fechaInicio = $this->getPost('fecha_inicio') ?: $this->getQuery('fecha_inicio');
            $fechaFin = $this->getPost('fecha_fin') ?: $this->getQuery('fecha_fin');

            if ($seccionId && $fechaInicio && $fechaFin) {
                $data['reporte'] = $this->asistenciaModel->getReporte($seccionId, $anioActivo->id, $fechaInicio, $fechaFin);
                $data['seccionSeleccionada'] = $seccionId;
                $data['fechaInicio'] = $fechaInicio;
                $data['fechaFin'] = $fechaFin;
            }
        }

        $this->view('layouts/main', [
            'content' => 'asistencia/reporte',
            'data' => $data,
            'title' => 'Reporte de Asistencia'
        ]);
    }
}
