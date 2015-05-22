<?php

/*
 * -----------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Este controlador se encarga de gestionar los controladores del modulo del administrador.
 * -----------------------------------------------------------------------------
 */

class administradorController extends Controller {

	protected $_master;
	protected $_reg;
	protected $_acl;

	public function __construct()
	{
		parent::__construct();
		$this->_master = $this->loadModel('master');
		$this->_reg = $this->loadModel('registro');
		$this->_acl = new Acl();
	}

	public function index()
	{
		$this->_view->renderizar('index', 'admin', 'login', 'administrador');
	}

}
