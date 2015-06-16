<?php

class ddlWidget extends Widget {

	private $_modelo;

	public function __construct()
	{
		$this->_modelo = $this->loadModel('ddl');
	}

	public function cargar($nombreCampo, $nombreDdl, $tabla, $campo)
	{
		$datos['datos'] = $this->_modelo->cargar($tabla,$campo);
		$datos['nombreCampo'] = $nombreCampo;
		$datos['nombreDdl'] = $nombreDdl;
		return $this->render('ddl', $datos);
	}

}
