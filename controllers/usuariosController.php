<?php

/*
 * -----------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Este controlador se encarga de gestionar el inicio y/o finalización de
 * una sesión
 * -----------------------------------------------------------------------------
 */

class usuariosController extends Controller {

	protected $_master;

	public function __construct()
	{
		parent::__construct();
		$this->_master = $this->loadModel('master');
	}

	public function index(){}

}
