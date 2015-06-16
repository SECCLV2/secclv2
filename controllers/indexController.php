<?php

/*
 * -----------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Controlador para la vista index.
 * -----------------------------------------------------------------------------
 */

class indexController extends Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->_view->titulo = 'Portada';
//		$this->_view->widget = $this->_view->setWidget('menuRol', 'menu', 'hola');

		if (Session::get('logueado'))
		{
			$this->_view->renderizar('index', 'Inicio', 'login');
		}
		else
		{
			$this->_view->renderizar('index', 'Inicio');
		}
	}

	public function Q_CCL()
	{
		$this->_view->titulo = 'Portada';

		if (Session::get('logueado'))
		{
			$this->_view->renderizar('q_ccl', 'Q_CCL', 'login');
		}
		else
		{
			$this->_view->renderizar('q_ccl', 'Q_CCL');
		}
	}

	public function PC_ECCL()
	{
		$this->_view->titulo = 'Portada';

		if (Session::get('logueado'))
		{
			$this->_view->renderizar('pc_eccl', 'PC_ECCL', 'login');
		}
		else
		{
			$this->_view->renderizar('pc_eccl', 'PC_ECCL');
		}
	}

	public function inicio()
	{
		$this->_view->titulo = 'SECCLV2';

		if (Session::get('logueado'))
		{
			$this->_view->renderizar('inicio', 'inicio', 'login');
		}
	}

	public function rol($rol, $centro = false)
	{
		$this->_view->titulo = 'Rol';

		if (Session::get('logueado'))
		{
			Session::set('rolAct', $rol);
			Session::set('cenAct', $centro);
			$key = array_search($rol, Session::get('rol')['USU_ROL_ID_ROL']);
			Session::set('nomRol', Session::get('rol')['ROL_DESCRIPCION'][$key]);
			switch ($rol)
			{
				case 1:
					$this->rolAdministrador();
					break;
				case 2:
					$this->rolSoporteTecnico();
					break;
				default:
					$this->_view->renderizar('index', 'Inicio', 'login');
					break;
			}
		}
	}

	public function rolAdministrador()
	{
		$this->_view->renderizar('rolAdministrador', 'nn', 'login');
	}

	public function rolSoporteTecnico()
	{
		$this->_view->renderizar('rolSoporteTecnico', 'nn', 'login');
	}

}
