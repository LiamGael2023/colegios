<?php
class ComunicadosController extends Controller {
    private $comunicadoModel;
    private $academicoModel;

    public function __construct() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $this->comunicadoModel = $this->model('Comunicado');
        $this->academicoModel = $this->model('Academico');
    }

    public function index() {
        $comunicados = $this->comunicadoModel->getAll();

        $this->view('layouts/main', [
            'content' => 'comunicados/index',
            'data' => [
                'comunicados' => $comunicados
            ],
            'title' => 'Comunicados'
        ]);
    }

    public function crear() {
        // Solo ADMIN, DIRECTOR y SECRETARIA pueden crear comunicados
        if (!in_array($_SESSION['usuario_rol'], ['ADMIN', 'DIRECTOR', 'SECRETARIA'])) {
            $_SESSION['error'] = 'No tiene permisos para crear comunicados';
            $this->redirect('comunicados');
        }

        $niveles = $this->academicoModel->getNiveles();
        $grados = $this->academicoModel->getGrados();
        $secciones = $this->academicoModel->getSecciones();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'titulo' => $this->getPost('titulo'),
                'contenido' => $this->getPost('contenido'),
                'tipo' => $this->getPost('tipo'),
                'destinatario_id' => $this->getPost('destinatario_id'),
                'fecha_expiracion' => $this->getPost('fecha_expiracion'),
                'usuario_id' => $_SESSION['usuario_id']
            ];

            if (empty($data['titulo']) || empty($data['contenido'])) {
                $_SESSION['error'] = 'El título y contenido son requeridos';
            } else {
                if ($this->comunicadoModel->crear($data)) {
                    $_SESSION['success'] = 'Comunicado publicado correctamente';
                    $this->redirect('comunicados');
                } else {
                    $_SESSION['error'] = 'Error al publicar el comunicado';
                }
            }
        }

        $this->view('layouts/main', [
            'content' => 'comunicados/crear',
            'data' => [
                'niveles' => $niveles,
                'grados' => $grados,
                'secciones' => $secciones
            ],
            'title' => 'Nuevo Comunicado'
        ]);
    }

    public function editar($id = null) {
        if (!$id) {
            $this->redirect('comunicados');
        }

        // Solo ADMIN, DIRECTOR y SECRETARIA pueden editar
        if (!in_array($_SESSION['usuario_rol'], ['ADMIN', 'DIRECTOR', 'SECRETARIA'])) {
            $_SESSION['error'] = 'No tiene permisos para editar comunicados';
            $this->redirect('comunicados');
        }

        $comunicado = $this->comunicadoModel->getById($id);
        if (!$comunicado) {
            $_SESSION['error'] = 'Comunicado no encontrado';
            $this->redirect('comunicados');
        }

        $niveles = $this->academicoModel->getNiveles();
        $grados = $this->academicoModel->getGrados();
        $secciones = $this->academicoModel->getSecciones();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'titulo' => $this->getPost('titulo'),
                'contenido' => $this->getPost('contenido'),
                'tipo' => $this->getPost('tipo'),
                'destinatario_id' => $this->getPost('destinatario_id'),
                'fecha_expiracion' => $this->getPost('fecha_expiracion')
            ];

            if (empty($data['titulo']) || empty($data['contenido'])) {
                $_SESSION['error'] = 'El título y contenido son requeridos';
            } else {
                if ($this->comunicadoModel->actualizar($id, $data)) {
                    $_SESSION['success'] = 'Comunicado actualizado correctamente';
                    $this->redirect('comunicados');
                } else {
                    $_SESSION['error'] = 'Error al actualizar el comunicado';
                }
            }
        }

        $this->view('layouts/main', [
            'content' => 'comunicados/editar',
            'data' => [
                'comunicado' => $comunicado,
                'niveles' => $niveles,
                'grados' => $grados,
                'secciones' => $secciones
            ],
            'title' => 'Editar Comunicado'
        ]);
    }

    public function ver($id = null) {
        if (!$id) {
            $this->redirect('comunicados');
        }

        $comunicado = $this->comunicadoModel->getById($id);
        if (!$comunicado) {
            $_SESSION['error'] = 'Comunicado no encontrado';
            $this->redirect('comunicados');
        }

        // Obtener nombre del destinatario si aplica
        $destinatarioNombre = null;
        if ($comunicado->destinatario_id) {
            $db = new Database();
            switch ($comunicado->tipo) {
                case 'NIVEL':
                    $db->query('SELECT nombre FROM niveles WHERE id = :id');
                    break;
                case 'GRADO':
                    $db->query('SELECT nombre FROM grados WHERE id = :id');
                    break;
                case 'SECCION':
                    $db->query('SELECT CONCAT(g.nombre, " ", s.nombre) as nombre
                               FROM secciones s
                               INNER JOIN grados g ON s.grado_id = g.id
                               WHERE s.id = :id');
                    break;
            }
            if ($comunicado->tipo != 'GENERAL') {
                $db->bind(':id', $comunicado->destinatario_id);
                $result = $db->single();
                $destinatarioNombre = $result ? $result->nombre : null;
            }
        }

        $this->view('layouts/main', [
            'content' => 'comunicados/ver',
            'data' => [
                'comunicado' => $comunicado,
                'destinatarioNombre' => $destinatarioNombre
            ],
            'title' => $comunicado->titulo
        ]);
    }

    public function cambiarEstado($id = null) {
        if (!$id) {
            $this->redirect('comunicados');
        }

        // Solo ADMIN, DIRECTOR y SECRETARIA
        if (!in_array($_SESSION['usuario_rol'], ['ADMIN', 'DIRECTOR', 'SECRETARIA'])) {
            $_SESSION['error'] = 'No tiene permisos para esta acción';
            $this->redirect('comunicados');
        }

        $comunicado = $this->comunicadoModel->getById($id);
        if (!$comunicado) {
            $_SESSION['error'] = 'Comunicado no encontrado';
            $this->redirect('comunicados');
        }

        $nuevoEstado = !$comunicado->activo;

        if ($this->comunicadoModel->cambiarEstado($id, $nuevoEstado)) {
            $_SESSION['success'] = 'Estado del comunicado actualizado';
        } else {
            $_SESSION['error'] = 'Error al cambiar el estado';
        }

        $this->redirect('comunicados');
    }

    public function eliminar($id = null) {
        if (!$id) {
            $this->redirect('comunicados');
        }

        // Solo ADMIN y DIRECTOR pueden eliminar
        if (!in_array($_SESSION['usuario_rol'], ['ADMIN', 'DIRECTOR'])) {
            $_SESSION['error'] = 'No tiene permisos para eliminar comunicados';
            $this->redirect('comunicados');
        }

        if ($this->comunicadoModel->eliminar($id)) {
            $_SESSION['success'] = 'Comunicado eliminado correctamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el comunicado';
        }

        $this->redirect('comunicados');
    }
}
