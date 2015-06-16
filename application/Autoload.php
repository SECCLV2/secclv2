<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Este archivo se encarga de gestionar la autocarga de clases.
 * ---------------------------------------------------------------------------------------------------------------------
 */

function autoloadCore($class)
{
	if (file_exists(APP_PATH . $class . '.php'))
		include APP_PATH . $class . '.php';

	if (file_exists(ROOT . 'models' . DS . $class . '.php'))
		include ROOT . 'models' . DS . $class . '.php';
}

spl_autoload_register('autoloadCore');
