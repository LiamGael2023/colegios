<?php
// Cargar configuración primero
require_once '../config/config.php';

// Iniciar sesión
session_name(SESSION_NAME);
session_start();

// Autoload de clases core
require_once APP_ROOT . '/core/Database.php';
require_once APP_ROOT . '/core/Controller.php';
require_once APP_ROOT . '/core/Router.php';

// Iniciar router
$router = new Router();
