<?php
class ConfiguracionController extends Controller {
    private $academicoModel;

    public function __construct() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $this->requireRole(['ADMIN', 'DIRECTOR']);
        $this->academicoModel = $this->model('Academico');
    }

    // ========== AÑOS ESCOLARES ==========
    public function anios() {
        $anios = $this->academicoModel->getAnios();

        $this->view('layouts/main', [
            'content' => 'configuracion/anios/index',
            'data' => ['anios' => $anios],
            'title' => 'Años Escolares'
        ]);
    }

    public function crearAnio() {
        $data = ['error' => ''];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $anio = $this->getPost('anio');
            $fechaInicio = $this->getPost('fecha_inicio');
            $fechaFin = $this->getPost('fecha_fin');

            if ($this->academicoModel->crearAnio($anio, $fechaInicio, $fechaFin)) {
                $_SESSION['success'] = 'Año escolar creado correctamente';
                $this->redirect('configuracion/anios');
            } else {
                $data['error'] = 'Error al crear año escolar';
            }
        }

        $this->view('layouts/main', [
            'content' => 'configuracion/anios/crear',
            'data' => $data,
            'title' => 'Nuevo Año Escolar'
        ]);
    }

    public function activarAnio($id = null) {
        if ($id) {
            if ($this->academicoModel->activarAnio($id)) {
                $_SESSION['success'] = 'Año escolar activado';
            } else {
                $_SESSION['error'] = 'Error al activar año escolar';
            }
        }
        $this->redirect('configuracion/anios');
    }

    // ========== GRADOS ==========
    public function grados() {
        $grados = $this->academicoModel->getGrados();
        $niveles = $this->academicoModel->getNiveles();

        $this->view('layouts/main', [
            'content' => 'configuracion/grados/index',
            'data' => ['grados' => $grados, 'niveles' => $niveles],
            'title' => 'Grados'
        ]);
    }

    public function crearGrado() {
        $niveles = $this->academicoModel->getNiveles();
        $data = ['niveles' => $niveles, 'error' => ''];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nivelId = $this->getPost('nivel_id');
            $nombre = $this->getPost('nombre');
            $numero = $this->getPost('numero');

            if ($this->academicoModel->crearGrado($nivelId, $nombre, $numero)) {
                $_SESSION['success'] = 'Grado creado correctamente';
                $this->redirect('configuracion/grados');
            } else {
                $data['error'] = 'Error al crear grado';
            }
        }

        $this->view('layouts/main', [
            'content' => 'configuracion/grados/crear',
            'data' => $data,
            'title' => 'Nuevo Grado'
        ]);
    }

    // ========== SECCIONES ==========
    public function secciones() {
        $secciones = $this->academicoModel->getSecciones();

        $this->view('layouts/main', [
            'content' => 'configuracion/secciones/index',
            'data' => ['secciones' => $secciones],
            'title' => 'Secciones'
        ]);
    }

    public function crearSeccion() {
        $grados = $this->academicoModel->getGrados();
        $data = ['grados' => $grados, 'error' => ''];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $gradoId = $this->getPost('grado_id');
            $nombre = $this->getPost('nombre');
            $capacidad = $this->getPost('capacidad');

            if ($this->academicoModel->crearSeccion($gradoId, $nombre, $capacidad)) {
                $_SESSION['success'] = 'Sección creada correctamente';
                $this->redirect('configuracion/secciones');
            } else {
                $data['error'] = 'Error al crear sección';
            }
        }

        $this->view('layouts/main', [
            'content' => 'configuracion/secciones/crear',
            'data' => $data,
            'title' => 'Nueva Sección'
        ]);
    }

    // ========== CURSOS ==========
    public function cursos() {
        $cursos = $this->academicoModel->getCursos();

        $this->view('layouts/main', [
            'content' => 'configuracion/cursos/index',
            'data' => ['cursos' => $cursos],
            'title' => 'Cursos'
        ]);
    }

    public function crearCurso() {
        $grados = $this->academicoModel->getGrados();
        $areas = $this->academicoModel->getAreas();
        $data = ['grados' => $grados, 'areas' => $areas, 'error' => ''];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $gradoId = $this->getPost('grado_id');
            $areaId = $this->getPost('area_id');
            $nombre = $this->getPost('nombre');
            $horasSemanales = $this->getPost('horas_semanales');

            if ($this->academicoModel->crearCurso($gradoId, $areaId, $nombre, $horasSemanales)) {
                $_SESSION['success'] = 'Curso creado correctamente';
                $this->redirect('configuracion/cursos');
            } else {
                $data['error'] = 'Error al crear curso';
            }
        }

        $this->view('layouts/main', [
            'content' => 'configuracion/cursos/crear',
            'data' => $data,
            'title' => 'Nuevo Curso'
        ]);
    }

    // ========== ÁREAS CURRICULARES ==========
    public function areas() {
        $areas = $this->academicoModel->getAreas();

        $this->view('layouts/main', [
            'content' => 'configuracion/areas/index',
            'data' => ['areas' => $areas],
            'title' => 'Áreas Curriculares'
        ]);
    }

    public function crearArea() {
        $data = ['error' => ''];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $this->getPost('nombre');
            $descripcion = $this->getPost('descripcion');

            if ($this->academicoModel->crearArea($nombre, $descripcion)) {
                $_SESSION['success'] = 'Área curricular creada correctamente';
                $this->redirect('configuracion/areas');
            } else {
                $data['error'] = 'Error al crear área curricular';
            }
        }

        $this->view('layouts/main', [
            'content' => 'configuracion/areas/crear',
            'data' => $data,
            'title' => 'Nueva Área Curricular'
        ]);
    }

    public function editarArea($id = null) {
        if (!$id) {
            $this->redirect('configuracion/areas');
        }

        $area = $this->academicoModel->getAreaById($id);
        if (!$area) {
            $_SESSION['error'] = 'Área no encontrada';
            $this->redirect('configuracion/areas');
        }

        $data = ['area' => $area, 'error' => ''];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $this->getPost('nombre');
            $descripcion = $this->getPost('descripcion');

            if ($this->academicoModel->actualizarArea($id, $nombre, $descripcion)) {
                $_SESSION['success'] = 'Área curricular actualizada';
                $this->redirect('configuracion/areas');
            } else {
                $data['error'] = 'Error al actualizar área';
            }
        }

        $this->view('layouts/main', [
            'content' => 'configuracion/areas/editar',
            'data' => $data,
            'title' => 'Editar Área Curricular'
        ]);
    }

    public function eliminarArea($id = null) {
        if ($id) {
            if ($this->academicoModel->eliminarArea($id)) {
                $_SESSION['success'] = 'Área curricular eliminada';
            } else {
                $_SESSION['error'] = 'No se puede eliminar el área (tiene cursos asociados)';
            }
        }
        $this->redirect('configuracion/areas');
    }

    // ========== INSTITUCIÓN ==========
    public function institucion() {
        $institucion = $this->academicoModel->getInstitucion();
        $data = ['institucion' => $institucion, 'error' => ''];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $this->getPost('nombre');
            $codigo = $this->getPost('codigo_modular');
            $direccion = $this->getPost('direccion');
            $telefono = $this->getPost('telefono');
            $email = $this->getPost('email');
            $director = $this->getPost('director');
            $ugel = $this->getPost('ugel');

            if ($this->academicoModel->actualizarInstitucion($nombre, $codigo, $direccion, $telefono, $email, $director, $ugel)) {
                $_SESSION['success'] = 'Datos de institución actualizados';
                $this->redirect('configuracion/institucion');
            } else {
                $data['error'] = 'Error al actualizar datos';
            }
        }

        $this->view('layouts/main', [
            'content' => 'configuracion/institucion',
            'data' => $data,
            'title' => 'Datos de Institución'
        ]);
    }
}
