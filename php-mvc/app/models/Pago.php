<?php
class Pago {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAll($anioEscolarId, $estado = null, $mes = null) {
        $sql = 'SELECT p.*, e.codigo as estudiante_codigo, e.nombres, e.apellido_paterno, e.apellido_materno,
                c.nombre as concepto_nombre
                FROM pagos p
                INNER JOIN estudiantes e ON p.estudiante_id = e.id
                INNER JOIN conceptos_pago c ON p.concepto_id = c.id
                WHERE p.anio_escolar_id = :anio_escolar_id';

        if ($estado) {
            $sql .= ' AND p.estado = :estado';
        }

        if ($mes) {
            $sql .= ' AND p.mes = :mes';
        }

        $sql .= ' ORDER BY p.fecha_vencimiento ASC';

        $this->db->query($sql);
        $this->db->bind(':anio_escolar_id', $anioEscolarId);

        if ($estado) {
            $this->db->bind(':estado', $estado);
        }

        if ($mes) {
            $this->db->bind(':mes', $mes);
        }

        return $this->db->resultSet();
    }

    public function getByEstudiante($estudianteId, $anioEscolarId = null) {
        $sql = 'SELECT p.*, c.nombre as concepto_nombre
                FROM pagos p
                INNER JOIN conceptos_pago c ON p.concepto_id = c.id
                WHERE p.estudiante_id = :estudiante_id';

        if ($anioEscolarId) {
            $sql .= ' AND p.anio_escolar_id = :anio_escolar_id';
        }

        $sql .= ' ORDER BY p.mes ASC, p.fecha_vencimiento ASC';

        $this->db->query($sql);
        $this->db->bind(':estudiante_id', $estudianteId);

        if ($anioEscolarId) {
            $this->db->bind(':anio_escolar_id', $anioEscolarId);
        }

        return $this->db->resultSet();
    }

    public function findById($id) {
        $sql = 'SELECT p.*, c.nombre as concepto_nombre, e.codigo as estudiante_codigo,
                e.nombres, e.apellido_paterno, e.apellido_materno
                FROM pagos p
                INNER JOIN conceptos_pago c ON p.concepto_id = c.id
                INNER JOIN estudiantes e ON p.estudiante_id = e.id
                WHERE p.id = :id';

        $this->db->query($sql);
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getConceptos() {
        $this->db->query('SELECT * FROM conceptos_pago ORDER BY nombre');
        return $this->db->resultSet();
    }

    public function generarCuotas($estudianteId, $anioEscolarId, $conceptoId, $meses) {
        // Obtener concepto
        $this->db->query('SELECT * FROM conceptos_pago WHERE id = :id');
        $this->db->bind(':id', $conceptoId);
        $concepto = $this->db->single();

        // Obtener año
        $this->db->query('SELECT anio FROM anios_escolares WHERE id = :id');
        $this->db->bind(':id', $anioEscolarId);
        $anio = $this->db->single()->anio;

        $creados = 0;

        foreach ($meses as $mes) {
            // Verificar si ya existe
            $this->db->query('SELECT id FROM pagos WHERE estudiante_id = :estudiante_id
                             AND anio_escolar_id = :anio_escolar_id AND concepto_id = :concepto_id AND mes = :mes');
            $this->db->bind(':estudiante_id', $estudianteId);
            $this->db->bind(':anio_escolar_id', $anioEscolarId);
            $this->db->bind(':concepto_id', $conceptoId);
            $this->db->bind(':mes', $mes);

            if (!$this->db->single()) {
                // Generar número de recibo
                $this->db->query('SELECT COUNT(*) as total FROM pagos');
                $count = $this->db->single()->total;
                $numeroRecibo = 'REC' . $anio . str_pad($count + 1, 6, '0', STR_PAD_LEFT);

                // Fecha de vencimiento: día 10 del mes
                $fechaVencimiento = sprintf('%d-%02d-10', $anio, $mes);

                $this->db->query('INSERT INTO pagos (numero_recibo, estudiante_id, concepto_id, anio_escolar_id,
                                 monto, monto_pagado, fecha_vencimiento, mes)
                                 VALUES (:numero_recibo, :estudiante_id, :concepto_id, :anio_escolar_id,
                                 :monto, 0, :fecha_vencimiento, :mes)');

                $this->db->bind(':numero_recibo', $numeroRecibo);
                $this->db->bind(':estudiante_id', $estudianteId);
                $this->db->bind(':concepto_id', $conceptoId);
                $this->db->bind(':anio_escolar_id', $anioEscolarId);
                $this->db->bind(':monto', $concepto->monto);
                $this->db->bind(':fecha_vencimiento', $fechaVencimiento);
                $this->db->bind(':mes', $mes);

                if ($this->db->execute()) {
                    $creados++;
                }
            }
        }

        return $creados;
    }

    public function registrarPago($id, $montoPagado, $metodoPago, $observacion = '') {
        // Obtener pago actual
        $this->db->query('SELECT * FROM pagos WHERE id = :id');
        $this->db->bind(':id', $id);
        $pago = $this->db->single();

        $nuevoMontoPagado = $pago->monto_pagado + $montoPagado;
        $nuevoEstado = $nuevoMontoPagado >= $pago->monto ? 'PAGADO' : 'PARCIAL';

        $this->db->query('UPDATE pagos SET monto_pagado = :monto_pagado, estado = :estado,
                         metodo_pago = :metodo_pago, fecha_pago = NOW(), observacion = :observacion
                         WHERE id = :id');

        $this->db->bind(':id', $id);
        $this->db->bind(':monto_pagado', $nuevoMontoPagado);
        $this->db->bind(':estado', $nuevoEstado);
        $this->db->bind(':metodo_pago', $metodoPago);
        $this->db->bind(':observacion', $observacion);

        return $this->db->execute();
    }

    public function getMorosidad($anioEscolarId) {
        $sql = 'SELECT p.*, e.id as estudiante_id, e.codigo as estudiante_codigo,
                e.nombres, e.apellido_paterno, e.apellido_materno,
                c.nombre as concepto_nombre,
                a.nombres as apoderado_nombres, a.apellidos as apoderado_apellidos, a.telefono as apoderado_telefono,
                s.nombre as seccion_nombre, g.nombre as grado_nombre
                FROM pagos p
                INNER JOIN estudiantes e ON p.estudiante_id = e.id
                INNER JOIN conceptos_pago c ON p.concepto_id = c.id
                LEFT JOIN apoderados a ON e.id = a.estudiante_id AND a.es_principal = TRUE
                LEFT JOIN matriculas m ON e.id = m.estudiante_id AND m.estado = "ACTIVA"
                LEFT JOIN secciones s ON m.seccion_id = s.id
                LEFT JOIN grados g ON s.grado_id = g.id
                WHERE p.anio_escolar_id = :anio_escolar_id
                AND p.estado IN ("PENDIENTE", "PARCIAL")
                AND p.fecha_vencimiento < CURDATE()
                ORDER BY e.apellido_paterno, e.apellido_materno, p.mes';

        $this->db->query($sql);
        $this->db->bind(':anio_escolar_id', $anioEscolarId);

        return $this->db->resultSet();
    }

    public function getIngresos($anioEscolarId, $mes = null) {
        $sql = 'SELECT c.nombre as concepto, COUNT(*) as cantidad, SUM(p.monto_pagado) as total
                FROM pagos p
                INNER JOIN conceptos_pago c ON p.concepto_id = c.id
                WHERE p.anio_escolar_id = :anio_escolar_id AND p.estado = "PAGADO"';

        if ($mes) {
            $sql .= ' AND MONTH(p.fecha_pago) = :mes';
        }

        $sql .= ' GROUP BY c.id ORDER BY c.nombre';

        $this->db->query($sql);
        $this->db->bind(':anio_escolar_id', $anioEscolarId);

        if ($mes) {
            $this->db->bind(':mes', $mes);
        }

        return $this->db->resultSet();
    }
}
