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

            // Procesar foto si se subió
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $foto = $this->subirFoto($_FILES['foto']);
                if ($foto) {
                    $formData['foto'] = $foto;
                } else {
                    $data['error'] = 'Error al subir la foto. Verifique formato y tamaño (máx 2MB).';
                    $this->view('layouts/main', [
                        'content' => 'estudiantes/crear',
                        'data' => $data,
                        'title' => 'Nuevo Estudiante'
                    ]);
                    return;
                }
            }

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

    private function subirFoto($file) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        if (!in_array($file['type'], $allowedTypes)) {
            return false;
        }

        if ($file['size'] > $maxSize) {
            return false;
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'est_' . uniqid() . '.' . strtolower($extension);
        $uploadPath = dirname(dirname(__DIR__)) . '/public/uploads/fotos/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return $filename;
        }

        return false;
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

            // Procesar foto si se subió una nueva
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $foto = $this->subirFoto($_FILES['foto']);
                if ($foto) {
                    // Eliminar foto anterior si existe
                    if (!empty($estudiante->foto)) {
                        $oldPath = dirname(dirname(__DIR__)) . '/public/uploads/fotos/' . $estudiante->foto;
                        if (file_exists($oldPath)) {
                            unlink($oldPath);
                        }
                    }
                    $formData['foto'] = $foto;
                } else {
                    $data['error'] = 'Error al subir la foto. Verifique formato y tamaño (máx 2MB).';
                    $this->view('layouts/main', [
                        'content' => 'estudiantes/editar',
                        'data' => $data,
                        'title' => 'Editar Estudiante'
                    ]);
                    return;
                }
            }

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
