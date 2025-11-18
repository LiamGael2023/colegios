<?php
session_name(SESSION_NAME);
session_start();

// Cargar configuración
require_once '../config/config.php';

// Autoload de clases core
require_once APP_ROOT . '/core/Database.php';
require_once APP_ROOT . '/core/Controller.php';
require_once APP_ROOT . '/core/Router.php';

// Iniciar router
$router = new Router();
