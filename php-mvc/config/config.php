<?php
/**
 * Configuración de la base de datos
 */
define('DB_HOST', 'localhost');
define('DB_PORT', '3307');  // Puerto de MySQL
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sistema_escolar');

/**
 * Configuración de la aplicación
 */
define('APP_NAME', 'Sistema de Gestión Escolar');
define('APP_URL', 'http://localhost/sistema-escolar/php-mvc/public');
define('APP_ROOT', dirname(dirname(__FILE__)));

/**
 * Configuración de sesión
 */
define('SESSION_NAME', 'sistema_escolar_session');

/**
 * Zona horaria
 */
date_default_timezone_set('America/Lima');
