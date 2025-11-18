<?php
class UsuariosController extends Controller {
    private $usuarioModel;

    public function __construct() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('auth/login');
        }

        $this->requireRole(['ADMIN', 'DIRECTOR']);
        $this->usuarioModel = $this->model('Usuario');
    }

    public function index() {
        $usuarios = $this->usuarioModel->getAll();

        $this->view('layouts/main', [
            'content' => 'usuarios/index',
            'data' => ['usuarios' => $usuarios],
            'title' => 'Gestión de Usuarios'
        ]);
    }

    public function crear() {
        $data = ['error' => ''];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $this->getPost('email');
            $password = $this->getPost('password');
            $nombre = $this->getPost('nombre');
            $apellidos = $this->getPost('apellidos');
            $dni = $this->getPost('dni');
            $telefono = $this->getPost('telefono');
            $rol = $this->getPost('rol');

            // Verificar email único
            if ($this->usuarioModel->findByEmail($email)) {
                $data['error'] = 'El email ya está registrado';
            } else {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                if ($this->usuarioModel->crear($email, $passwordHash, $nombre, $apellidos, $dni, $telefono, $rol)) {
                    $_SESSION['success'] = 'Usuario creado correctamente';
                    $this->redirect('usuarios');
                } else {
                    $data['error'] = 'Error al crear usuario';
                }
            }
        }

        $this->view('layouts/main', [
            'content' => 'usuarios/crear',
            'data' => $data,
            'title' => 'Nuevo Usuario'
        ]);
    }

    public function editar($id = null) {
        if (!$id) {
            $this->redirect('usuarios');
        }

        $usuario = $this->usuarioModel->findById($id);

        if (!$usuario) {
            $_SESSION['error'] = 'Usuario no encontrado';
            $this->redirect('usuarios');
        }

        $data = [
            'usuario' => $usuario,
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $this->getPost('email');
            $nombre = $this->getPost('nombre');
            $apellidos = $this->getPost('apellidos');
            $dni = $this->getPost('dni');
            $telefono = $this->getPost('telefono');
            $rol = $this->getPost('rol');
            $activo = isset($_POST['activo']) ? 1 : 0;

            // Verificar email único (excluyendo el actual)
            $existente = $this->usuarioModel->findByEmail($email);
            if ($existente && $existente->id != $id) {
                $data['error'] = 'El email ya está registrado';
            } else {
                if ($this->usuarioModel->actualizar($id, $email, $nombre, $apellidos, $dni, $telefono, $rol, $activo)) {
                    $_SESSION['success'] = 'Usuario actualizado correctamente';
                    $this->redirect('usuarios');
                } else {
                    $data['error'] = 'Error al actualizar usuario';
                }
            }
        }

        $this->view('layouts/main', [
            'content' => 'usuarios/editar',
            'data' => $data,
            'title' => 'Editar Usuario'
        ]);
    }

    public function cambiarPassword($id = null) {
        if (!$id) {
            $this->redirect('usuarios');
        }

        $usuario = $this->usuarioModel->findById($id);

        if (!$usuario) {
            $_SESSION['error'] = 'Usuario no encontrado';
            $this->redirect('usuarios');
        }

        $data = [
            'usuario' => $usuario,
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $password = $this->getPost('password');
            $confirmar = $this->getPost('confirmar');

            if ($password != $confirmar) {
                $data['error'] = 'Las contraseñas no coinciden';
            } elseif (strlen($password) < 6) {
                $data['error'] = 'La contraseña debe tener al menos 6 caracteres';
            } else {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                if ($this->usuarioModel->cambiarPassword($id, $passwordHash)) {
                    $_SESSION['success'] = 'Contraseña actualizada correctamente';
                    $this->redirect('usuarios');
                } else {
                    $data['error'] = 'Error al cambiar contraseña';
                }
            }
        }

        $this->view('layouts/main', [
            'content' => 'usuarios/cambiar_password',
            'data' => $data,
            'title' => 'Cambiar Contraseña'
        ]);
    }

    public function eliminar($id = null) {
        if ($id && $id != $_SESSION['usuario_id']) {
            if ($this->usuarioModel->eliminar($id)) {
                $_SESSION['success'] = 'Usuario eliminado correctamente';
            } else {
                $_SESSION['error'] = 'Error al eliminar usuario';
            }
        } else {
            $_SESSION['error'] = 'No puede eliminar su propio usuario';
        }

        $this->redirect('usuarios');
    }
}
