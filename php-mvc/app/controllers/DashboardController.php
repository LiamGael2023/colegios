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

        $db->query('SELECT COUNT(*) as total FROM apoderados');
        $totalApoderados = $db->single()->total;

        // Estudiantes por nivel
        $db->query('SELECT n.nombre, COUNT(DISTINCT m.estudiante_id) as total
                    FROM niveles n
                    LEFT JOIN grados g ON n.id = g.nivel_id
                    LEFT JOIN secciones s ON g.id = s.grado_id
                    LEFT JOIN matriculas m ON s.id = m.seccion_id AND m.estado = "ACTIVA"
                    GROUP BY n.id, n.nombre
                    ORDER BY n.id');
        $estudiantesPorNivel = $db->resultSet();

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

            // Estado de pagos
            $db->query('SELECT estado, COUNT(*) as total FROM pagos WHERE anio_escolar_id = :id GROUP BY estado');
            $db->bind(':id', $anioActivo->id);
            $estadoPagos = $db->resultSet();

            // Ingresos por mes
            $db->query('SELECT MONTH(fecha_pago) as mes, SUM(monto_pagado) as total
                        FROM pagos
                        WHERE anio_escolar_id = :id AND fecha_pago IS NOT NULL
                        GROUP BY MONTH(fecha_pago)
                        ORDER BY mes');
            $db->bind(':id', $anioActivo->id);
            $ingresosPorMes = $db->resultSet();

            // Asistencia del día
            $db->query('SELECT estado, COUNT(*) as total FROM asistencias
                        WHERE fecha = CURDATE() GROUP BY estado');
            $asistenciaHoy = $db->resultSet();
        } else {
            $totalMatriculas = 0;
            $pagosPendientes = 0;
            $ingresosTotales = 0;
            $estadoPagos = [];
            $ingresosPorMes = [];
            $asistenciaHoy = [];
        }

        $data = [
            'anioActivo' => $anioActivo,
            'totalEstudiantes' => $totalEstudiantes,
            'totalProfesores' => $totalProfesores,
            'totalApoderados' => $totalApoderados,
            'totalMatriculas' => $totalMatriculas,
            'pagosPendientes' => $pagosPendientes,
            'ingresosTotales' => $ingresosTotales,
            'estudiantesPorNivel' => $estudiantesPorNivel,
            'estadoPagos' => $estadoPagos,
            'ingresosPorMes' => $ingresosPorMes,
            'asistenciaHoy' => $asistenciaHoy
        ];

        $this->view('layouts/main', [
            'content' => 'dashboard/index',
            'data' => $data,
            'title' => 'Dashboard'
        ]);
    }
}
