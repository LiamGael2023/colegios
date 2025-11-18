<?php
class DocentesController extends Controller {
    private $docenteModel;
    private $usuarioModel;
    private $academicoModel;

    public function __construct() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $this->requireRole(['ADMIN', 'DIRECTOR']);
        $this->docenteModel = $this->model('Docente');
        $this->usuarioModel = $this->model('Usuario');
        $this->academicoModel = $this->model('Academico');
    }

    public function index() {
        $docentes = $this->docenteModel->getAll();

        $this->view('layouts/main', [
            'content' => 'docentes/index',
            'data' => ['docentes' => $docentes],
            'title' => 'Docentes'
        ]);
    }

    public function crear() {
        $usuariosSinDocente = $this->docenteModel->getUsuariosSinDocente();
        $data = ['usuarios' => $usuariosSinDocente, 'error' => ''];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuarioId = $this->getPost('usuario_id');
            $especialidad = $this->getPost('especialidad');

            if ($this->docenteModel->crear($usuarioId, $especialidad)) {
                $_SESSION['success'] = 'Docente registrado correctamente';
                $this->redirect('docentes');
            } else {
                $data['error'] = 'Error al registrar docente';
            }
        }

        $this->view('layouts/main', [
            'content' => 'docentes/crear',
            'data' => $data,
            'title' => 'Nuevo Docente'
        ]);
    }

    public function editar($id = null) {
        if (!$id) {
            $this->redirect('docentes');
        }

        $docente = $this->docenteModel->getById($id);
        if (!$docente) {
            $_SESSION['error'] = 'Docente no encontrado';
            $this->redirect('docentes');
        }

        $data = ['docente' => $docente, 'error' => ''];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $especialidad = $this->getPost('especialidad');

            if ($this->docenteModel->actualizar($id, $especialidad)) {
                $_SESSION['success'] = 'Docente actualizado correctamente';
                $this->redirect('docentes');
            } else {
                $data['error'] = 'Error al actualizar docente';
            }
        }

        $this->view('layouts/main', [
            'content' => 'docentes/editar',
            'data' => $data,
            'title' => 'Editar Docente'
        ]);
    }

    public function asignaciones($docenteId = null) {
        if (!$docenteId) {
            $this->redirect('docentes');
        }

        $docente = $this->docenteModel->getById($docenteId);
        if (!$docente) {
            $_SESSION['error'] = 'Docente no encontrado';
            $this->redirect('docentes');
        }

        $asignaciones = $this->docenteModel->getAsignaciones($docenteId);
        $secciones = $this->academicoModel->getSecciones();
        $cursos = $this->academicoModel->getCursos();

        $this->view('layouts/main', [
            'content' => 'docentes/asignaciones',
            'data' => [
                'docente' => $docente,
                'asignaciones' => $asignaciones,
                'secciones' => $secciones,
                'cursos' => $cursos
            ],
            'title' => 'Asignaciones de ' . $docente->nombre
        ]);
    }

    public function agregarAsignacion($docenteId = null) {
        if (!$docenteId || $_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->redirect('docentes');
        }

        $cursoId = $this->getPost('curso_id');
        $seccionId = $this->getPost('seccion_id');

        if ($this->docenteModel->agregarAsignacion($docenteId, $cursoId, $seccionId)) {
            $_SESSION['success'] = 'Asignación agregada correctamente';
        } else {
            $_SESSION['error'] = 'Error al agregar asignación (puede que ya exista)';
        }

        $this->redirect('docentes/asignaciones/' . $docenteId);
    }

    public function eliminarAsignacion($asignacionId = null) {
        if (!$asignacionId) {
            $this->redirect('docentes');
        }

        $asignacion = $this->docenteModel->getAsignacionById($asignacionId);
        if (!$asignacion) {
            $_SESSION['error'] = 'Asignación no encontrada';
            $this->redirect('docentes');
        }

        if ($this->docenteModel->eliminarAsignacion($asignacionId)) {
            $_SESSION['success'] = 'Asignación eliminada';
        } else {
            $_SESSION['error'] = 'Error al eliminar asignación';
        }

        $this->redirect('docentes/asignaciones/' . $asignacion->profesor_id);
    }

    public function eliminar($id = null) {
        if ($id) {
            if ($this->docenteModel->eliminar($id)) {
                $_SESSION['success'] = 'Docente eliminado';
            } else {
                $_SESSION['error'] = 'No se puede eliminar (tiene asignaciones)';
            }
        }
        $this->redirect('docentes');
    }
}
