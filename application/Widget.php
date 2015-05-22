<?php

/*
 * -----------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Este archivo llama al metodo y el controlador que son solicitdaos en la
 * url (es el que procesa la llamada de los controladores, metodos y argumentos).
 * -----------------------------------------------------------------------------
 */

abstract class Widget {

	protected function loadModel($model)
	{
		if (is_readable(ROOT . 'widgets' . DS . 'models' . DS . $model . 'ModelWidget.php'))
		{
			include_once ROOT . 'widgets' . DS . 'models' . DS . $model . 'ModelWidget.php';

			$modelClass = $model . 'ModelWidget';
			
			if (class_exists($modelClass))
			{
				return new $modelClass;
			}

			if (PRUEBAS == 'On')
				throw new Exception('Widget // Error en la clase del modelo del widget: </br> class_exists = false </br> Nombre de la Clase: ' . $modelClass);
		}
		if (PRUEBAS == 'On')
			throw new Exception('Widget // Error en la ruta del modelo del widget: </br> is_readable = false </br> Ruta ModelWidget: ' . ROOT . 'widgets' . DS . 'models' . DS . $model . 'ModelWidget.php');
	}

	protected function render($view, $data = array(), $ext = 'phtml')
	{
		if (is_readable(ROOT . 'widgets' . DS . 'views' . DS . $view . '.' . $ext))
		{
			ob_start();
			extract($data);
			include ROOT . 'widgets' . DS . 'views' . DS . $view . '.' . $ext;
			$content = ob_get_contents();
			ob_end_clean();
			return $content;
		}
		if (PRUEBAS == 'On')
			throw new Exception('Widget // Error en la ruta de la vista del widget: </br> is_readable = false </br> Ruta VistaWidget: ' . ROOT . 'widgets' . DS . 'views' . DS . $view . '.' . $ext);
	}

}
