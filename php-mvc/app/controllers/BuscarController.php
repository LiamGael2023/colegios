<?php
class BuscarController extends Controller {
    public function __construct() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }
    }

    public function index() {
        $q = $this->getGet('q') ?? '';

        if (strlen($q) < 2) {
            $this->view('layouts/main', [
                'content' => 'buscar/index',
                'data' => [
                    'q' => $q,
                    'resultados' => [],
                    'mensaje' => 'Ingrese al menos 2 caracteres para buscar'
                ],
                'title' => 'Búsqueda'
            ]);
            return;
        }

        $resultados = [];
        $db = new Database();
        $termino = '%' . $q . '%';

        // Buscar en estudiantes
        $db->query('SELECT e.id, e.codigo, e.dni, e.nombres, e.apellido_paterno, e.apellido_materno,
                    "estudiante" as tipo
                    FROM estudiantes e
                    WHERE e.activo = TRUE
                    AND (e.codigo LIKE :q OR e.dni LIKE :q OR e.nombres LIKE :q
                         OR e.apellido_paterno LIKE :q OR e.apellido_materno LIKE :q)
                    LIMIT 10');
        $db->bind(':q', $termino);
        $estudiantes = $db->resultSet();

        foreach ($estudiantes as $est) {
            $resultados[] = [
                'tipo' => 'Estudiante',
                'titulo' => $est->apellido_paterno . ' ' . $est->apellido_materno . ', ' . $est->nombres,
                'subtitulo' => 'DNI: ' . ($est->dni ?? 'Sin DNI') . ' | Código: ' . $est->codigo,
                'url' => APP_URL . '/estudiantes/ver/' . $est->id,
                'icono' => 'ti-user'
            ];
        }

        // Buscar en apoderados
        $db->query('SELECT a.id, a.dni, a.nombres, a.apellidos, a.telefono,
                    e.nombres as est_nombres, e.apellido_paterno as est_ap
                    FROM apoderados a
                    INNER JOIN estudiantes e ON a.estudiante_id = e.id
                    WHERE a.dni LIKE :q OR a.nombres LIKE :q OR a.apellidos LIKE :q
                    LIMIT 10');
        $db->bind(':q', $termino);
        $apoderados = $db->resultSet();

        foreach ($apoderados as $ap) {
            $resultados[] = [
                'tipo' => 'Apoderado',
                'titulo' => $ap->nombres . ' ' . $ap->apellidos,
                'subtitulo' => 'DNI: ' . $ap->dni . ' | Est: ' . $ap->est_ap . ', ' . $ap->est_nombres,
                'url' => APP_URL . '/apoderados/editar/' . $ap->id,
                'icono' => 'ti-users-group'
            ];
        }

        // Buscar en pagos (por número de recibo)
        $db->query('SELECT p.id, p.numero_recibo, p.monto, p.estado, p.fecha_pago,
                    e.nombres, e.apellido_paterno
                    FROM pagos p
                    INNER JOIN estudiantes e ON p.estudiante_id = e.id
                    WHERE p.numero_recibo LIKE :q
                    LIMIT 10');
        $db->bind(':q', $termino);
        $pagos = $db->resultSet();

        foreach ($pagos as $pago) {
            $resultados[] = [
                'tipo' => 'Pago',
                'titulo' => 'Recibo: ' . $pago->numero_recibo,
                'subtitulo' => $pago->apellido_paterno . ', ' . $pago->nombres . ' | S/ ' . $pago->monto,
                'url' => APP_URL . '/pagos',
                'icono' => 'ti-receipt'
            ];
        }

        // Buscar en comunicados
        $db->query('SELECT id, titulo, tipo, fecha_publicacion
                    FROM comunicados
                    WHERE titulo LIKE :q OR contenido LIKE :q
                    ORDER BY fecha_publicacion DESC
                    LIMIT 5');
        $db->bind(':q', $termino);
        $comunicados = $db->resultSet();

        foreach ($comunicados as $com) {
            $resultados[] = [
                'tipo' => 'Comunicado',
                'titulo' => $com->titulo,
                'subtitulo' => $com->tipo . ' | ' . date('d/m/Y', strtotime($com->fecha_publicacion)),
                'url' => APP_URL . '/comunicados/ver/' . $com->id,
                'icono' => 'ti-speakerphone'
            ];
        }

        // Buscar en docentes
        $db->query('SELECT p.id, u.nombre, u.apellidos, u.dni, p.especialidad
                    FROM profesores p
                    INNER JOIN usuarios u ON p.usuario_id = u.id
                    WHERE u.nombre LIKE :q OR u.apellidos LIKE :q OR u.dni LIKE :q
                    LIMIT 5');
        $db->bind(':q', $termino);
        $docentes = $db->resultSet();

        foreach ($docentes as $doc) {
            $resultados[] = [
                'tipo' => 'Docente',
                'titulo' => $doc->apellidos . ', ' . $doc->nombre,
                'subtitulo' => 'DNI: ' . $doc->dni . ' | ' . ($doc->especialidad ?? 'Sin especialidad'),
                'url' => APP_URL . '/docentes/editar/' . $doc->id,
                'icono' => 'ti-chalkboard'
            ];
        }

        $this->view('layouts/main', [
            'content' => 'buscar/index',
            'data' => [
                'q' => $q,
                'resultados' => $resultados,
                'total' => count($resultados)
            ],
            'title' => 'Búsqueda: ' . $q
        ]);
    }
}
