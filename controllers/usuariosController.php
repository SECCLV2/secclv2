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
        protected $_registro;

        public function __construct()
	{
		parent::__construct();
		$this->_master = $this->loadModel('master');
		$this->_reg = $this->loadModel('registro');
	}

	public function index(){}

}
