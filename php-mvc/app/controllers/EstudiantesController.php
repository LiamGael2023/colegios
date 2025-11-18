<?php
class EstudiantesController extends Controller {
    private $estudianteModel;
    private $academicoModel;

    public function __construct() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $this->estudianteModel = $this->model('Estudiante');
        $this->academicoModel = $this->model('Academico');
    }

    public function index() {
        $buscar = $this->getQuery('buscar');
        $estudiantes = $this->estudianteModel->getAll($buscar);

        $data = [
            'estudiantes' => $estudiantes,
            'buscar' => $buscar
        ];

        $this->view('layouts/main', [
            'content' => 'estudiantes/index',
            'data' => $data,
            'title' => 'Estudiantes'
        ]);
    }

    public function crear() {
        $this->requireRole(['ADMIN', 'DIRECTOR', 'SECRETARIA']);

        $data = [
            'error' => '',
            'success' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $formData = [
                'dni' => $this->getPost('dni'),
                'nombres' => $this->getPost('nombres'),
                'apellido_paterno' => $this->getPost('apellido_paterno'),
                'apellido_materno' => $this->getPost('apellido_materno'),
                'fecha_nacimiento' => $this->getPost('fecha_nacimiento'),
                'genero' => $this->getPost('genero'),
                'direccion' => $this->getPost('direccion'),
                'telefono' => $this->getPost('telefono'),
                'email' => $this->getPost('email')
            ];

            $id = $this->estudianteModel->create($formData);

            if ($id) {
                $_SESSION['success'] = 'Estudiante creado correctamente';
                $this->redirect('estudiantes/ver/' . $id);
            } else {
                $data['error'] = 'Error al crear estudiante';
            }
        }

        $this->view('layouts/main', [
            'content' => 'estudiantes/crear',
            'data' => $data,
            'title' => 'Nuevo Estudiante'
        ]);
    }

    public function ver($id = null) {
        if (!$id) {
            $this->redirect('estudiantes');
        }

        $estudiante = $this->estudianteModel->getWithDetails($id);

        if (!$estudiante) {
            $_SESSION['error'] = 'Estudiante no encontrado';
            $this->redirect('estudiantes');
        }

        $apoderados = $this->estudianteModel->getApoderados($id);
        $anioActivo = $this->academicoModel->getAnioActivo();

        // Obtener pagos
        $pagoModel = $this->model('Pago');
        $pagos = $anioActivo ? $pagoModel->getByEstudiante($id, $anioActivo->id) : [];

        // Obtener asistencia
        $asistenciaModel = $this->model('Asistencia');
        $asistencia = $anioActivo ? $asistenciaModel->getResumenEstudiante($id, $anioActivo->id) : null;

        $data = [
            'estudiante' => $estudiante,
            'apoderados' => $apoderados,
            'pagos' => $pagos,
            'asistencia' => $asistencia,
            'anioActivo' => $anioActivo
        ];

        $this->view('layouts/main', [
            'content' => 'estudiantes/ver',
            'data' => $data,
            'title' => 'Detalle del Estudiante'
        ]);
    }

    public function editar($id = null) {
        $this->requireRole(['ADMIN', 'DIRECTOR', 'SECRETARIA']);

        if (!$id) {
            $this->redirect('estudiantes');
        }

        $estudiante = $this->estudianteModel->findById($id);

        if (!$estudiante) {
            $_SESSION['error'] = 'Estudiante no encontrado';
            $this->redirect('estudiantes');
        }

        $data = [
            'estudiante' => $estudiante,
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $formData = [
                'dni' => $this->getPost('dni'),
                'nombres' => $this->getPost('nombres'),
                'apellido_paterno' => $this->getPost('apellido_paterno'),
                'apellido_materno' => $this->getPost('apellido_materno'),
                'fecha_nacimiento' => $this->getPost('fecha_nacimiento'),
                'genero' => $this->getPost('genero'),
                'direccion' => $this->getPost('direccion'),
                'telefono' => $this->getPost('telefono'),
                'email' => $this->getPost('email'),
                'observaciones' => $this->getPost('observaciones')
            ];

            if ($this->estudianteModel->update($id, $formData)) {
                $_SESSION['success'] = 'Estudiante actualizado correctamente';
                $this->redirect('estudiantes/ver/' . $id);
            } else {
                $data['error'] = 'Error al actualizar estudiante';
            }
        }

        $this->view('layouts/main', [
            'content' => 'estudiantes/editar',
            'data' => $data,
            'title' => 'Editar Estudiante'
        ]);
    }

    public function matricular($id = null) {
        $this->requireRole(['ADMIN', 'DIRECTOR', 'SECRETARIA']);

        if (!$id) {
            $this->redirect('estudiantes');
        }

        $estudiante = $this->estudianteModel->findById($id);
        $anioActivo = $this->academicoModel->getAnioActivo();
        $niveles = $this->academicoModel->getNiveles();
        $secciones = $this->academicoModel->getSecciones();

        $data = [
            'estudiante' => $estudiante,
            'anioActivo' => $anioActivo,
            'niveles' => $niveles,
            'secciones' => $secciones,
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $formData = [
                'estudiante_id' => $id,
                'seccion_id' => $this->getPost('seccion_id'),
                'anio_escolar_id' => $anioActivo->id,
                'tipo_matricula' => $this->getPost('tipo_matricula'),
                'observaciones' => $this->getPost('observaciones')
            ];

            if ($this->estudianteModel->matricular($formData)) {
                $_SESSION['success'] = 'Estudiante matriculado correctamente';
                $this->redirect('estudiantes/ver/' . $id);
            } else {
                $data['error'] = 'Error al matricular. Verifique que no esté ya matriculado.';
            }
        }

        $this->view('layouts/main', [
            'content' => 'estudiantes/matricular',
            'data' => $data,
            'title' => 'Matricular Estudiante'
        ]);
    }
}
