<?php
class PagosController extends Controller {
    private $pagoModel;
    private $academicoModel;

    public function __construct() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $this->pagoModel = $this->model('Pago');
        $this->academicoModel = $this->model('Academico');
    }

    public function index() {
        $anioActivo = $this->academicoModel->getAnioActivo();
        $estado = $this->getQuery('estado');
        $mes = $this->getQuery('mes');

        $pagos = $anioActivo ? $this->pagoModel->getAll($anioActivo->id, $estado, $mes) : [];

        // Calcular totales
        $totalMonto = 0;
        $totalPagado = 0;
        foreach ($pagos as $pago) {
            $totalMonto += $pago->monto;
            $totalPagado += $pago->monto_pagado;
        }

        $data = [
            'pagos' => $pagos,
            'anioActivo' => $anioActivo,
            'estadoFiltro' => $estado,
            'mesFiltro' => $mes,
            'totalMonto' => $totalMonto,
            'totalPagado' => $totalPagado
        ];

        $this->view('layouts/main', [
            'content' => 'pagos/index',
            'data' => $data,
            'title' => 'Gestión de Pagos'
        ]);
    }

    public function registrar($id = null) {
        $this->requireRole(['ADMIN', 'DIRECTOR', 'SECRETARIA']);

        if (!$id) {
            $this->redirect('pagos');
        }

        $pago = $this->pagoModel->findById($id);

        if (!$pago) {
            $_SESSION['error'] = 'Pago no encontrado';
            $this->redirect('pagos');
        }

        $data = [
            'pago' => $pago,
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $montoPagado = floatval($this->getPost('monto_pagado'));
            $metodoPago = $this->getPost('metodo_pago');
            $observacion = $this->getPost('observacion');

            if ($this->pagoModel->registrarPago($id, $montoPagado, $metodoPago, $observacion)) {
                $_SESSION['success'] = 'Pago registrado correctamente';
                $this->redirect('pagos');
            } else {
                $data['error'] = 'Error al registrar pago';
            }
        }

        $this->view('layouts/main', [
            'content' => 'pagos/registrar',
            'data' => $data,
            'title' => 'Registrar Pago'
        ]);
    }

    public function generar($estudianteId = null) {
        $this->requireRole(['ADMIN', 'DIRECTOR', 'SECRETARIA']);

        if (!$estudianteId) {
            $this->redirect('estudiantes');
        }

        $estudianteModel = $this->model('Estudiante');
        $estudiante = $estudianteModel->findById($estudianteId);
        $anioActivo = $this->academicoModel->getAnioActivo();
        $conceptos = $this->pagoModel->getConceptos();

        $data = [
            'estudiante' => $estudiante,
            'anioActivo' => $anioActivo,
            'conceptos' => $conceptos,
            'error' => '',
            'success' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $conceptoId = $this->getPost('concepto_id');
            $meses = $_POST['meses'] ?? [];

            if (empty($meses)) {
                $data['error'] = 'Seleccione al menos un mes';
            } else {
                $creados = $this->pagoModel->generarCuotas($estudianteId, $anioActivo->id, $conceptoId, $meses);

                if ($creados > 0) {
                    $_SESSION['success'] = "Se generaron $creados cuotas correctamente";
                    $this->redirect('estudiantes/ver/' . $estudianteId);
                } else {
                    $data['error'] = 'No se generaron cuotas. Posiblemente ya existen.';
                }
            }
        }

        $this->view('layouts/main', [
            'content' => 'pagos/generar',
            'data' => $data,
            'title' => 'Generar Cuotas'
        ]);
    }

    public function morosidad() {
        $this->requireRole(['ADMIN', 'DIRECTOR', 'SECRETARIA']);

        $anioActivo = $this->academicoModel->getAnioActivo();
        $morosidad = $anioActivo ? $this->pagoModel->getMorosidad($anioActivo->id) : [];

        // Agrupar por estudiante
        $porEstudiante = [];
        foreach ($morosidad as $item) {
            if (!isset($porEstudiante[$item->estudiante_id])) {
                $porEstudiante[$item->estudiante_id] = [
                    'estudiante' => $item,
                    'pagos' => [],
                    'totalDeuda' => 0
                ];
            }
            $deuda = $item->monto - $item->monto_pagado;
            $porEstudiante[$item->estudiante_id]['pagos'][] = $item;
            $porEstudiante[$item->estudiante_id]['totalDeuda'] += $deuda;
        }

        $data = [
            'morosidad' => $porEstudiante,
            'anioActivo' => $anioActivo
        ];

        $this->view('layouts/main', [
            'content' => 'pagos/morosidad',
            'data' => $data,
            'title' => 'Reporte de Morosidad'
        ]);
    }

    public function ingresos() {
        $this->requireRole(['ADMIN', 'DIRECTOR']);

        $anioActivo = $this->academicoModel->getAnioActivo();
        $mes = $this->getQuery('mes');

        $ingresos = $anioActivo ? $this->pagoModel->getIngresos($anioActivo->id, $mes) : [];

        $total = 0;
        foreach ($ingresos as $ingreso) {
            $total += $ingreso->total;
        }

        $data = [
            'ingresos' => $ingresos,
            'anioActivo' => $anioActivo,
            'mesFiltro' => $mes,
            'total' => $total
        ];

        $this->view('layouts/main', [
            'content' => 'pagos/ingresos',
            'data' => $data,
            'title' => 'Reporte de Ingresos'
        ]);
    }

    public function nuevo() {
        $this->requireRole(['ADMIN', 'DIRECTOR', 'SECRETARIA']);

        $estudianteModel = $this->model('Estudiante');
        $anioActivo = $this->academicoModel->getAnioActivo();
        $buscar = $this->getQuery('buscar');

        $estudiantes = [];
        if ($buscar) {
            $estudiantes = $estudianteModel->search($buscar);
        }

        $data = [
            'estudiantes' => $estudiantes,
            'anioActivo' => $anioActivo,
            'buscar' => $buscar
        ];

        $this->view('layouts/main', [
            'content' => 'pagos/nuevo',
            'data' => $data,
            'title' => 'Nuevo Pago - Buscar Estudiante'
        ]);
    }

    // CRUD Conceptos de Pago
    public function conceptos() {
        $this->requireRole(['ADMIN', 'DIRECTOR']);

        $conceptos = $this->pagoModel->getConceptos();

        $this->view('layouts/main', [
            'content' => 'pagos/conceptos/index',
            'data' => ['conceptos' => $conceptos],
            'title' => 'Conceptos de Pago'
        ]);
    }

    public function crearConcepto() {
        $this->requireRole(['ADMIN', 'DIRECTOR']);

        $data = ['error' => ''];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $this->getPost('nombre');
            $descripcion = $this->getPost('descripcion');
            $monto = floatval($this->getPost('monto'));
            $esRecurrente = isset($_POST['es_recurrente']) ? 1 : 0;

            if ($this->pagoModel->crearConcepto($nombre, $descripcion, $monto, $esRecurrente)) {
                $_SESSION['success'] = 'Concepto creado correctamente';
                $this->redirect('pagos/conceptos');
            } else {
                $data['error'] = 'Error al crear concepto';
            }
        }

        $this->view('layouts/main', [
            'content' => 'pagos/conceptos/crear',
            'data' => $data,
            'title' => 'Nuevo Concepto de Pago'
        ]);
    }

    public function editarConcepto($id = null) {
        $this->requireRole(['ADMIN', 'DIRECTOR']);

        if (!$id) {
            $this->redirect('pagos/conceptos');
        }

        $concepto = $this->pagoModel->getConceptoById($id);

        if (!$concepto) {
            $_SESSION['error'] = 'Concepto no encontrado';
            $this->redirect('pagos/conceptos');
        }

        $data = [
            'concepto' => $concepto,
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $this->getPost('nombre');
            $descripcion = $this->getPost('descripcion');
            $monto = floatval($this->getPost('monto'));
            $esRecurrente = isset($_POST['es_recurrente']) ? 1 : 0;

            if ($this->pagoModel->actualizarConcepto($id, $nombre, $descripcion, $monto, $esRecurrente)) {
                $_SESSION['success'] = 'Concepto actualizado correctamente';
                $this->redirect('pagos/conceptos');
            } else {
                $data['error'] = 'Error al actualizar concepto';
            }
        }

        $this->view('layouts/main', [
            'content' => 'pagos/conceptos/editar',
            'data' => $data,
            'title' => 'Editar Concepto de Pago'
        ]);
    }

    public function eliminarConcepto($id = null) {
        $this->requireRole(['ADMIN', 'DIRECTOR']);

        if ($id) {
            if ($this->pagoModel->eliminarConcepto($id)) {
                $_SESSION['success'] = 'Concepto eliminado correctamente';
            } else {
                $_SESSION['error'] = 'No se puede eliminar. El concepto tiene pagos asociados.';
            }
        }

        $this->redirect('pagos/conceptos');
    }
}
