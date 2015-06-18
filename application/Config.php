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
define('BASE_URL', 'http://localhost:81/SECCLV2/');
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
 * DB_HOST = Dirección del host de la base de datos.
 * DB_USER = Nombre del usuario de la base de datos.
 * DB_PASS = Contraseña del usuario de la base de datos.
 * DB_NAME = Nombre de la base de datos.
 * DB_CHAR = Codificación de la base de datos.
 */
define('DB_HOST', '10.96.108.90');
define('DB_USER', 'BDPRODUCCIONV2');
define('DB_PASS', 'admin');
define('DB_NAME', 'BDPRODUCCIONV2');
define('DB_CHAR', 'utf8');

/*
 * ConstanteS que define el estado del aplicativo
 */
define('PRUEBAS', 0);
define('PRUEBAS_ACL', 0);
define('PRUEBAS_BD', 0);
define('PRUEBAS_SELECT', 0);
define('PRUEBAS_INSERT', 0);
define('PRUEBAS_UPDATE', 0);
define('PRUEBAS_HIST_EST', 0);
define('PRUEBAS_QUERYGET', 0);
define('PRUEBAS_QUERYSET', 0);
define('PRUEBAS_PAGINADOR', 0);
define('PRUEBAS_REGISTRO', 0);
define('PRUEBAS_RUTAS', 0);
