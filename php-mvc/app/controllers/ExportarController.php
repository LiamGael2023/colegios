<?php
class ExportarController extends Controller {
    public function __construct() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }
    }

    public function estudiantes() {
        $db = new Database();
        $db->query('SELECT e.codigo, e.dni, e.nombres, e.apellido_paterno, e.apellido_materno,
                    e.fecha_nacimiento, e.genero, e.direccion, e.telefono, e.email,
                    s.nombre as seccion, g.nombre as grado, n.nombre as nivel
                    FROM estudiantes e
                    LEFT JOIN matriculas m ON e.id = m.estudiante_id AND m.estado = "ACTIVA"
                    LEFT JOIN secciones s ON m.seccion_id = s.id
                    LEFT JOIN grados g ON s.grado_id = g.id
                    LEFT JOIN niveles n ON g.nivel_id = n.id
                    WHERE e.activo = TRUE
                    ORDER BY e.apellido_paterno, e.apellido_materno, e.nombres');
        $estudiantes = $db->resultSet();

        $filename = 'estudiantes_' . date('Y-m-d') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM for UTF-8

        // Header
        fputcsv($output, ['Código', 'DNI', 'Nombres', 'Ap. Paterno', 'Ap. Materno',
                         'Fecha Nac.', 'Género', 'Dirección', 'Teléfono', 'Email',
                         'Nivel', 'Grado', 'Sección']);

        // Data
        foreach ($estudiantes as $est) {
            fputcsv($output, [
                $est->codigo,
                $est->dni ?? '',
                $est->nombres,
                $est->apellido_paterno,
                $est->apellido_materno,
                $est->fecha_nacimiento,
                $est->genero,
                $est->direccion ?? '',
                $est->telefono ?? '',
                $est->email ?? '',
                $est->nivel ?? '',
                $est->grado ?? '',
                $est->seccion ?? ''
            ]);
        }

        fclose($output);
        exit;
    }

    public function matriculas() {
        $academicoModel = $this->model('Academico');
        $anioActivo = $academicoModel->getAnioActivo();

        $db = new Database();
        $db->query('SELECT m.codigo as codigo_matricula, m.fecha_matricula, m.estado, m.tipo_matricula,
                    e.codigo, e.dni, e.nombres, e.apellido_paterno, e.apellido_materno,
                    s.nombre as seccion, g.nombre as grado, n.nombre as nivel
                    FROM matriculas m
                    INNER JOIN estudiantes e ON m.estudiante_id = e.id
                    INNER JOIN secciones s ON m.seccion_id = s.id
                    INNER JOIN grados g ON s.grado_id = g.id
                    INNER JOIN niveles n ON g.nivel_id = n.id
                    WHERE m.anio_escolar_id = :anio_id
                    ORDER BY n.id, g.numero, s.nombre, e.apellido_paterno');
        $db->bind(':anio_id', $anioActivo->id);
        $matriculas = $db->resultSet();

        $filename = 'matriculas_' . $anioActivo->anio . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($output, ['Código Matrícula', 'Fecha', 'Estado', 'Tipo', 'Código Est.',
                         'DNI', 'Nombres', 'Apellidos', 'Nivel', 'Grado', 'Sección']);

        foreach ($matriculas as $mat) {
            fputcsv($output, [
                $mat->codigo_matricula,
                $mat->fecha_matricula,
                $mat->estado,
                $mat->tipo_matricula,
                $mat->codigo,
                $mat->dni ?? '',
                $mat->nombres,
                $mat->apellido_paterno . ' ' . $mat->apellido_materno,
                $mat->nivel,
                $mat->grado,
                $mat->seccion
            ]);
        }

        fclose($output);
        exit;
    }

    public function pagos() {
        $academicoModel = $this->model('Academico');
        $anioActivo = $academicoModel->getAnioActivo();

        $db = new Database();
        $db->query('SELECT p.numero_recibo, p.fecha_pago, p.monto, p.monto_pagado, p.estado,
                    p.metodo_pago, p.mes, c.nombre as concepto,
                    e.codigo, e.dni, e.nombres, e.apellido_paterno, e.apellido_materno
                    FROM pagos p
                    INNER JOIN estudiantes e ON p.estudiante_id = e.id
                    INNER JOIN conceptos_pago c ON p.concepto_id = c.id
                    WHERE p.anio_escolar_id = :anio_id
                    ORDER BY p.fecha_pago DESC');
        $db->bind(':anio_id', $anioActivo->id);
        $pagos = $db->resultSet();

        $filename = 'pagos_' . $anioActivo->anio . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($output, ['N° Recibo', 'Fecha Pago', 'Estudiante', 'DNI', 'Concepto',
                         'Mes', 'Monto', 'Pagado', 'Estado', 'Método']);

        foreach ($pagos as $pago) {
            fputcsv($output, [
                $pago->numero_recibo,
                $pago->fecha_pago ?? '',
                $pago->apellido_paterno . ' ' . $pago->apellido_materno . ', ' . $pago->nombres,
                $pago->dni ?? '',
                $pago->concepto,
                $pago->mes ?? '',
                $pago->monto,
                $pago->monto_pagado,
                $pago->estado,
                $pago->metodo_pago ?? ''
            ]);
        }

        fclose($output);
        exit;
    }

    public function asistencia() {
        $fecha = $this->getGet('fecha') ?? date('Y-m-d');
        $seccionId = $this->getGet('seccion_id');

        if (!$seccionId) {
            $_SESSION['error'] = 'Debe seleccionar una sección';
            $this->redirect('asistencia');
        }

        $db = new Database();

        // Obtener sección
        $db->query('SELECT s.nombre as seccion, g.nombre as grado, n.nombre as nivel
                    FROM secciones s
                    INNER JOIN grados g ON s.grado_id = g.id
                    INNER JOIN niveles n ON g.nivel_id = n.id
                    WHERE s.id = :id');
        $db->bind(':id', $seccionId);
        $seccion = $db->single();

        // Obtener asistencias
        $db->query('SELECT e.codigo, e.dni, e.nombres, e.apellido_paterno, e.apellido_materno,
                    a.estado, a.hora_entrada, a.observacion
                    FROM estudiantes e
                    INNER JOIN matriculas m ON e.id = m.estudiante_id AND m.estado = "ACTIVA"
                    LEFT JOIN asistencias a ON e.id = a.estudiante_id AND a.fecha = :fecha
                    WHERE m.seccion_id = :seccion_id
                    ORDER BY e.apellido_paterno, e.apellido_materno, e.nombres');
        $db->bind(':fecha', $fecha);
        $db->bind(':seccion_id', $seccionId);
        $asistencias = $db->resultSet();

        $filename = 'asistencia_' . $seccion->grado . '_' . $seccion->seccion . '_' . $fecha . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($output, ['Código', 'DNI', 'Apellidos y Nombres', 'Estado', 'Hora Entrada', 'Observación']);

        foreach ($asistencias as $asist) {
            fputcsv($output, [
                $asist->codigo,
                $asist->dni ?? '',
                $asist->apellido_paterno . ' ' . $asist->apellido_materno . ', ' . $asist->nombres,
                $asist->estado ?? 'SIN REGISTRO',
                $asist->hora_entrada ?? '',
                $asist->observacion ?? ''
            ]);
        }

        fclose($output);
        exit;
    }

    public function notas() {
        $seccionId = $this->getGet('seccion_id');
        $cursoId = $this->getGet('curso_id');
        $periodoId = $this->getGet('periodo_id');

        if (!$seccionId || !$cursoId || !$periodoId) {
            $_SESSION['error'] = 'Faltan parámetros';
            $this->redirect('notas');
        }

        $db = new Database();

        // Info del curso y sección
        $db->query('SELECT c.nombre as curso FROM cursos c WHERE c.id = :id');
        $db->bind(':id', $cursoId);
        $curso = $db->single();

        $db->query('SELECT s.nombre as seccion, g.nombre as grado
                    FROM secciones s INNER JOIN grados g ON s.grado_id = g.id WHERE s.id = :id');
        $db->bind(':id', $seccionId);
        $seccion = $db->single();

        $db->query('SELECT nombre FROM periodos WHERE id = :id');
        $db->bind(':id', $periodoId);
        $periodo = $db->single();

        // Notas
        $db->query('SELECT e.codigo, e.dni, e.nombres, e.apellido_paterno, e.apellido_materno,
                    n.calificacion
                    FROM estudiantes e
                    INNER JOIN matriculas m ON e.id = m.estudiante_id AND m.estado = "ACTIVA"
                    LEFT JOIN notas n ON e.id = n.estudiante_id AND n.curso_id = :curso_id AND n.periodo_id = :periodo_id
                    WHERE m.seccion_id = :seccion_id
                    ORDER BY e.apellido_paterno, e.apellido_materno, e.nombres');
        $db->bind(':curso_id', $cursoId);
        $db->bind(':periodo_id', $periodoId);
        $db->bind(':seccion_id', $seccionId);
        $notas = $db->resultSet();

        $filename = 'notas_' . $curso->curso . '_' . $seccion->grado . $seccion->seccion . '_' . $periodo->nombre . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($output, ['N°', 'Código', 'DNI', 'Apellidos y Nombres', 'Calificación']);

        $i = 1;
        foreach ($notas as $nota) {
            fputcsv($output, [
                $i++,
                $nota->codigo,
                $nota->dni ?? '',
                $nota->apellido_paterno . ' ' . $nota->apellido_materno . ', ' . $nota->nombres,
                $nota->calificacion ?? ''
            ]);
        }

        fclose($output);
        exit;
    }
}
