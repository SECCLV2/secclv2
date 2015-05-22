<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Esta clase llama al metodo y el controlador que son solicitdaos en la
 * url.
 * ---------------------------------------------------------------------------------------------------------------------
 */

class Bootstrap {

	public static function run(Request $peticion)
	{
		$modulo = $peticion->getModulo();
		$controllers = $peticion->getControlador() . 'Controller';
		$metodo = $peticion->getMetodo();
		$args = $peticion->getArgs();

		if ($modulo)
		{
			$rutaModulo = ROOT . 'controllers' . DS . $modulo . 'Controller.php';

			if (is_readable($rutaModulo))
			{
				require_once $rutaModulo;
				$rutaControlador = ROOT . 'modules' . DS . $modulo . DS . 'controllers' . DS . $controllers . '.php';
			}
			else
			{
				if (PRUEBAS == 'On')
					throw new Exception('Bootstrap // Error en la ruta del modulo: </br> is_readable = false </br> Ruta Modulo: ' . $rutaModulo);
				else
					header('location:' . BASE_URL . Hash::urlEncrypt('sistem/error/access/404'));
			}
		}
		else
		{
			$rutaControlador = ROOT . 'controllers' . DS . $controllers . '.php';
		}

		if (is_readable($rutaControlador))
		{
			require_once $rutaControlador;
			$controllers = new $controllers();
			if (!is_callable(array($controllers, $metodo)))
				$metodo = 'index';

			if (isset($args))
			{
				call_user_func_array(array($controllers, $metodo), $args);
			}
			else
			{
				call_user_func(array($controllers, $metodo));
			}
		}
		else
		{
			if (PRUEBAS == 'On')
				throw new Exception('Bootstrap // Error en la ruta del controlador: </br> is_readable = false </br> Ruta Controlador: ' . $rutaControlador);
			else
				header('location:' . BASE_URL . Hash::urlEncrypt('sistem/error/access/404'));
		}
	}

}
