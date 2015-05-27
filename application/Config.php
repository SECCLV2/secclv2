<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Este archivo aloja las constantes de configuración del aplicativo.
 * ---------------------------------------------------------------------------------------------------------------------
 */

/*
 * Constantes principales del aplicativo, definen la base de la url y los metodos y controladores por default.
 */
define('BASE_URL', 'http://localhost/SECCLV2/');
define('DEFAULT_CONTROLLER', 'index');
define('DEFAULT_LAYOUT', 'default');

/*
 * Constantes con los datos del aplicativo (datos a mostrar en el mismo).
 */
define('APP_NAME', 'SECCL');
define('APP_VERSION', 'versión 2.0');
define('APP_COMPANY', 'SENA');

/*
 * Constante que maneja el tiempo por defecto en las sesiones de los usuarios.
 */
define('SESSION_TIME', 0);

/*
 * Constante que define la clave base generada de forma especial y usada para encriptar.
 */
define('HASH_KEY', '552093d8dd36d');

/*
 * Constantes de conexión con la base de datos.
 */
define('DB_HOST', '10.96.108.90');
define('DB_USER', 'BDPRODUCCIONV2');
define('DB_PASS', 'admin');
define('DB_NAME', 'BDPRODUCCIONV2');
define('DB_CHAR', 'utf8');

/*
 * ConstanteS que define el estado del aplicativo
 */
define('PRUEBAS', 'On');
define('PRUEBAS_BD', 'On');
define('PRUEBAS_RUTAS', 'On');
