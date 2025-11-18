<?php
class DashboardController extends Controller {
    private $estudianteModel;
    private $pagoModel;
    private $academicoModel;

    public function __construct() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $this->estudianteModel = $this->model('Estudiante');
        $this->pagoModel = $this->model('Pago');
        $this->academicoModel = $this->model('Academico');
    }

    public function index() {
        $anioActivo = $this->academicoModel->getAnioActivo();

        // Estadísticas
        $db = new Database();

        $db->query('SELECT COUNT(*) as total FROM estudiantes WHERE activo = TRUE');
        $totalEstudiantes = $db->single()->total;

        $db->query('SELECT COUNT(*) as total FROM profesores');
        $totalProfesores = $db->single()->total;

        if ($anioActivo) {
            $db->query('SELECT COUNT(*) as total FROM matriculas WHERE anio_escolar_id = :id AND estado = "ACTIVA"');
            $db->bind(':id', $anioActivo->id);
            $totalMatriculas = $db->single()->total;

            $db->query('SELECT COUNT(*) as total FROM pagos WHERE anio_escolar_id = :id AND estado IN ("PENDIENTE", "PARCIAL")');
            $db->bind(':id', $anioActivo->id);
            $pagosPendientes = $db->single()->total;

            $db->query('SELECT COALESCE(SUM(monto_pagado), 0) as total FROM pagos WHERE anio_escolar_id = :id AND estado = "PAGADO"');
            $db->bind(':id', $anioActivo->id);
            $ingresosTotales = $db->single()->total;
        } else {
            $totalMatriculas = 0;
            $pagosPendientes = 0;
            $ingresosTotales = 0;
        }

        $data = [
            'anioActivo' => $anioActivo,
            'totalEstudiantes' => $totalEstudiantes,
            'totalProfesores' => $totalProfesores,
            'totalMatriculas' => $totalMatriculas,
            'pagosPendientes' => $pagosPendientes,
            'ingresosTotales' => $ingresosTotales
        ];

        $this->view('layouts/main', [
            'content' => 'dashboard/index',
            'data' => $data,
            'title' => 'Dashboard'
        ]);
    }
}
