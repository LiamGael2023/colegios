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
}
