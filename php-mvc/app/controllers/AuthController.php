<?php
class AuthController extends Controller {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = $this->model('Usuario');
    }

    public function login() {
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }

        $data = [
            'email' => '',
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $this->getPost('email');
            $password = $this->getPost('password');

            $usuario = $this->usuarioModel->login($email, $password);

            if ($usuario) {
                $_SESSION['usuario_id'] = $usuario->id;
                $_SESSION['usuario_email'] = $usuario->email;
                $_SESSION['usuario_nombre'] = $usuario->nombre . ' ' . $usuario->apellidos;
                $_SESSION['usuario_rol'] = $usuario->rol;

                $this->redirect('dashboard');
            } else {
                $data['error'] = 'Credenciales inválidas';
                $data['email'] = $email;
            }
        }

        $this->view('auth/login', $data);
    }

    public function logout() {
        unset($_SESSION['usuario_id']);
        unset($_SESSION['usuario_email']);
        unset($_SESSION['usuario_nombre']);
        unset($_SESSION['usuario_rol']);
        session_destroy();

        $this->redirect('auth/login');
    }
}
