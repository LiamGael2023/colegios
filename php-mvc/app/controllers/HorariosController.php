<?php
class HorariosController extends Controller {
    private $horarioModel;
    private $academicoModel;
    private $docenteModel;

    public function __construct() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $this->horarioModel = $this->model('Horario');
        $this->academicoModel = $this->model('Academico');
        $this->docenteModel = $this->model('Docente');
    }

    public function index() {
        $secciones = $this->academicoModel->getSecciones();

        $this->view('layouts/main', [
            'content' => 'horarios/index',
            'data' => [
                'secciones' => $secciones
            ],
            'title' => 'Horarios'
        ]);
    }

    public function seccion($seccionId = null) {
        if (!$seccionId) {
            $this->redirect('horarios');
        }

        $seccion = $this->academicoModel->getSeccionById($seccionId);
        if (!$seccion) {
            $_SESSION['error'] = 'Sección no encontrada';
            $this->redirect('horarios');
        }

        $horarios = $this->horarioModel->getBySeccion($seccionId);
        $asignaciones = $this->horarioModel->getAsignacionesSinHorario($seccionId);

        // Organizar horarios por día para la vista de grilla
        $dias = ['LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES'];
        $horariosPorDia = [];
        foreach ($dias as $dia) {
            $horariosPorDia[$dia] = array_filter($horarios, function($h) use ($dia) {
                return $h->dia === $dia;
            });
        }

        $this->view('layouts/main', [
            'content' => 'horarios/seccion',
            'data' => [
                'seccion' => $seccion,
                'horarios' => $horarios,
                'horariosPorDia' => $horariosPorDia,
                'asignaciones' => $asignaciones,
                'dias' => $dias
            ],
            'title' => 'Horario - ' . $seccion->grado_nombre . ' ' . $seccion->nombre
        ]);
    }

    public function docente($docenteId = null) {
        if (!$docenteId) {
            $this->redirect('horarios');
        }

        $docente = $this->docenteModel->getById($docenteId);
        if (!$docente) {
            $_SESSION['error'] = 'Docente no encontrado';
            $this->redirect('horarios');
        }

        $horarios = $this->horarioModel->getByDocente($docenteId);

        // Organizar horarios por día
        $dias = ['LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES'];
        $horariosPorDia = [];
        foreach ($dias as $dia) {
            $horariosPorDia[$dia] = array_filter($horarios, function($h) use ($dia) {
                return $h->dia === $dia;
            });
        }

        $this->view('layouts/main', [
            'content' => 'horarios/docente',
            'data' => [
                'docente' => $docente,
                'horarios' => $horarios,
                'horariosPorDia' => $horariosPorDia,
                'dias' => $dias
            ],
            'title' => 'Horario - ' . $docente->nombre . ' ' . $docente->apellidos
        ]);
    }

    public function agregar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('horarios');
        }

        // Solo ADMIN y DIRECTOR pueden agregar horarios
        if (!in_array($_SESSION['usuario_rol'], ['ADMIN', 'DIRECTOR'])) {
            $_SESSION['error'] = 'No tiene permisos para esta acción';
            $this->redirect('horarios');
        }

        $asignacionId = $this->getPost('asignacion_id');
        $dia = $this->getPost('dia');
        $horaInicio = $this->getPost('hora_inicio');
        $horaFin = $this->getPost('hora_fin');
        $aula = $this->getPost('aula');
        $seccionId = $this->getPost('seccion_id');

        if (empty($asignacionId) || empty($dia) || empty($horaInicio) || empty($horaFin)) {
            $_SESSION['error'] = 'Todos los campos son requeridos';
            $this->redirect('horarios/seccion/' . $seccionId);
        }

        if ($horaInicio >= $horaFin) {
            $_SESSION['error'] = 'La hora de fin debe ser mayor a la hora de inicio';
            $this->redirect('horarios/seccion/' . $seccionId);
        }

        if ($this->horarioModel->crear($asignacionId, $dia, $horaInicio, $horaFin, $aula)) {
            $_SESSION['success'] = 'Horario agregado correctamente';
        } else {
            $_SESSION['error'] = 'Error al agregar horario. Puede haber conflicto de horarios.';
        }

        $this->redirect('horarios/seccion/' . $seccionId);
    }

    public function editar($id = null) {
        if (!$id) {
            $this->redirect('horarios');
        }

        // Solo ADMIN y DIRECTOR pueden editar horarios
        if (!in_array($_SESSION['usuario_rol'], ['ADMIN', 'DIRECTOR'])) {
            $_SESSION['error'] = 'No tiene permisos para esta acción';
            $this->redirect('horarios');
        }

        $horario = $this->horarioModel->getById($id);
        if (!$horario) {
            $_SESSION['error'] = 'Horario no encontrado';
            $this->redirect('horarios');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dia = $this->getPost('dia');
            $horaInicio = $this->getPost('hora_inicio');
            $horaFin = $this->getPost('hora_fin');
            $aula = $this->getPost('aula');

            if ($horaInicio >= $horaFin) {
                $_SESSION['error'] = 'La hora de fin debe ser mayor a la hora de inicio';
                $this->redirect('horarios/editar/' . $id);
            }

            if ($this->horarioModel->actualizar($id, $dia, $horaInicio, $horaFin, $aula)) {
                $_SESSION['success'] = 'Horario actualizado correctamente';
                $this->redirect('horarios/seccion/' . $horario->seccion_id);
            } else {
                $_SESSION['error'] = 'Error al actualizar. Puede haber conflicto de horarios.';
                $this->redirect('horarios/editar/' . $id);
            }
        }

        $dias = ['LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO'];

        $this->view('layouts/main', [
            'content' => 'horarios/editar',
            'data' => [
                'horario' => $horario,
                'dias' => $dias
            ],
            'title' => 'Editar Horario'
        ]);
    }

    public function eliminar($id = null) {
        if (!$id) {
            $this->redirect('horarios');
        }

        // Solo ADMIN y DIRECTOR pueden eliminar horarios
        if (!in_array($_SESSION['usuario_rol'], ['ADMIN', 'DIRECTOR'])) {
            $_SESSION['error'] = 'No tiene permisos para esta acción';
            $this->redirect('horarios');
        }

        $horario = $this->horarioModel->getById($id);
        if (!$horario) {
            $_SESSION['error'] = 'Horario no encontrado';
            $this->redirect('horarios');
        }

        $seccionId = $horario->seccion_id;

        if ($this->horarioModel->eliminar($id)) {
            $_SESSION['success'] = 'Horario eliminado correctamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el horario';
        }

        $this->redirect('horarios/seccion/' . $seccionId);
    }
}
