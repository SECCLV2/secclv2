<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Desde este archivo se ejeutaran todos los procesos del aplicativo.
 * ---------------------------------------------------------------------------------------------------------------------
 */

ini_set('display_errors', 1);
ini_set('display_errors', 1);
ini_set('display_errors', 1);

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath(__DIR__) . DS);
define('APP_PATH', ROOT . 'application' . DS);

try
{
	require_once APP_PATH . 'Autoload.php';
	require_once APP_PATH . 'Config.php';
	
	Session::init();
	Bootstrap::run(new Request);
//	echo Hash::getHash('adminSeccl_2015');
}
catch (Exception $e)
{
	echo $e->getMessage();
}
catch (PDOException $e)
{
	echo $e->getMessage();
	echo $e->getLine();
}
