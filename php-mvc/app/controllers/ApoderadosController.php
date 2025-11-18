<?php
class ApoderadosController extends Controller {
    private $apoderadoModel;

    public function __construct() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $this->apoderadoModel = $this->model('Apoderado');
    }

    public function index() {
        $buscar = $this->getGet('buscar') ?? '';
        $apoderados = $this->apoderadoModel->getAll($buscar);

        $this->view('layouts/main', [
            'content' => 'apoderados/index',
            'data' => ['apoderados' => $apoderados, 'buscar' => $buscar],
            'title' => 'Apoderados'
        ]);
    }

    public function crear() {
        $estudianteModel = $this->model('Estudiante');
        $estudiantes = $estudianteModel->getAll();
        $data = ['estudiantes' => $estudiantes, 'error' => ''];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $apoderadoData = [
                'estudiante_id' => $this->getPost('estudiante_id'),
                'dni' => $this->getPost('dni'),
                'nombres' => $this->getPost('nombres'),
                'apellidos' => $this->getPost('apellidos'),
                'parentesco' => $this->getPost('parentesco'),
                'telefono' => $this->getPost('telefono'),
                'telefono_trabajo' => $this->getPost('telefono_trabajo'),
                'email' => $this->getPost('email'),
                'ocupacion' => $this->getPost('ocupacion'),
                'direccion' => $this->getPost('direccion'),
                'lugar_trabajo' => $this->getPost('lugar_trabajo'),
                'es_principal' => $this->getPost('es_principal') ? true : false
            ];

            if ($this->apoderadoModel->crear($apoderadoData)) {
                $_SESSION['success'] = 'Apoderado registrado correctamente';
                $this->redirect('apoderados');
            } else {
                $data['error'] = 'Error al registrar apoderado';
            }
        }

        $this->view('layouts/main', [
            'content' => 'apoderados/crear',
            'data' => $data,
            'title' => 'Nuevo Apoderado'
        ]);
    }

    public function editar($id = null) {
        if (!$id) {
            $this->redirect('apoderados');
        }

        $apoderado = $this->apoderadoModel->getById($id);
        if (!$apoderado) {
            $_SESSION['error'] = 'Apoderado no encontrado';
            $this->redirect('apoderados');
        }

        $estudianteModel = $this->model('Estudiante');
        $estudiantes = $estudianteModel->getAll();
        $data = ['apoderado' => $apoderado, 'estudiantes' => $estudiantes, 'error' => ''];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $apoderadoData = [
                'id' => $id,
                'estudiante_id' => $this->getPost('estudiante_id'),
                'dni' => $this->getPost('dni'),
                'nombres' => $this->getPost('nombres'),
                'apellidos' => $this->getPost('apellidos'),
                'parentesco' => $this->getPost('parentesco'),
                'telefono' => $this->getPost('telefono'),
                'telefono_trabajo' => $this->getPost('telefono_trabajo'),
                'email' => $this->getPost('email'),
                'ocupacion' => $this->getPost('ocupacion'),
                'direccion' => $this->getPost('direccion'),
                'lugar_trabajo' => $this->getPost('lugar_trabajo'),
                'es_principal' => $this->getPost('es_principal') ? true : false
            ];

            if ($this->apoderadoModel->actualizar($apoderadoData)) {
                $_SESSION['success'] = 'Apoderado actualizado correctamente';
                $this->redirect('apoderados');
            } else {
                $data['error'] = 'Error al actualizar apoderado';
            }
        }

        $this->view('layouts/main', [
            'content' => 'apoderados/editar',
            'data' => $data,
            'title' => 'Editar Apoderado'
        ]);
    }

    public function eliminar($id = null) {
        if ($id) {
            if ($this->apoderadoModel->eliminar($id)) {
                $_SESSION['success'] = 'Apoderado eliminado';
            } else {
                $_SESSION['error'] = 'Error al eliminar apoderado';
            }
        }
        $this->redirect('apoderados');
    }

    public function porEstudiante($estudianteId = null) {
        if (!$estudianteId) {
            $this->redirect('estudiantes');
        }

        $estudianteModel = $this->model('Estudiante');
        $estudiante = $estudianteModel->findById($estudianteId);

        if (!$estudiante) {
            $_SESSION['error'] = 'Estudiante no encontrado';
            $this->redirect('estudiantes');
        }

        $apoderados = $this->apoderadoModel->getByEstudiante($estudianteId);

        $this->view('layouts/main', [
            'content' => 'apoderados/por_estudiante',
            'data' => ['estudiante' => $estudiante, 'apoderados' => $apoderados],
            'title' => 'Apoderados de ' . $estudiante->nombres
        ]);
    }
}
