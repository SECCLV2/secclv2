<?php

/*
 * -----------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: ########.
 * -----------------------------------------------------------------------------
 */

class errorController extends Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->_view->titulo = 'Error';
		$this->_view->mensaje = $this->_getError();
		$this->_view->renderizar('index');
	}

	public function access($codigo)
	{
		$this->_view->titulo = 'Error';
		$this->_view->mensaje = $this->_getError($codigo);
		$this->_view->renderizar('access');
	}

	private function _getError($codigo = false)
	{
		if ($codigo)
		{
			$codigo = (int) $codigo;
			if (is_int($codigo))
				$codigo = $codigo;
		}
		else
		{
			$codigo = 'default';
		}

		$error['default'] = 'Ha ocurrido un error y la pagina no puede mostrarse';
		$error['5050'] = 'Acceso restringido!';
		$error['8080'] = 'Tiempo de la sesión agotado';
		$error['404'] = 'Error 404: Pagina no encontrada';

		if (array_key_exists($codigo, $error))
		{
			return $error[$codigo];
		}
		else
		{
			return $error['default'];
		}
	}

}
