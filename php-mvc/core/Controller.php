<?php
/**
 * Clase Controller base
 */
class Controller {
    /**
     * Cargar modelo
     */
    public function model($model) {
        require_once APP_ROOT . '/app/models/' . $model . '.php';
        return new $model();
    }

    /**
     * Cargar vista
     */
    public function view($view, $data = []) {
        $viewFile = APP_ROOT . '/app/views/' . $view . '.php';

        if (file_exists($viewFile)) {
            extract($data);
            require_once $viewFile;
        } else {
            die('Vista no encontrada: ' . $view);
        }
    }

    /**
     * Redireccionar
     */
    public function redirect($url) {
        header('Location: ' . APP_URL . '/' . $url);
        exit;
    }

    /**
     * Verificar si está autenticado
     */
    public function isLoggedIn() {
        return isset($_SESSION['usuario_id']);
    }

    /**
     * Verificar rol
     */
    public function requireRole($roles) {
        if (!$this->isLoggedIn()) {
            $this->redirect('auth/login');
        }

        if (!is_array($roles)) {
            $roles = [$roles];
        }

        if (!in_array($_SESSION['usuario_rol'], $roles)) {
            $_SESSION['error'] = 'No tiene permisos para acceder a esta sección';
            $this->redirect('dashboard');
        }
    }

    /**
     * Obtener datos POST de forma segura
     */
    public function getPost($key, $default = '') {
        return isset($_POST[$key]) ? htmlspecialchars(trim($_POST[$key])) : $default;
    }

    /**
     * Obtener datos GET de forma segura
     */
    public function getQuery($key, $default = '') {
        return isset($_GET[$key]) ? htmlspecialchars(trim($_GET[$key])) : $default;
    }
}
