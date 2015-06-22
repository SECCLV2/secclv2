<?php

/*
 * -----------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Este modelo se encargara de traer los datos de todas las listas 
 * desplegables de los formularios.
 * -----------------------------------------------------------------------------
 */

class ddlModelWidget extends Model {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function cargar($tabla,$campo)
	{
		$select = array(
			'campos' => '*',
			'tablas' => $tabla,
			'condiciones' => "WHERE $campo = 1"
		);
		$resp = $this->masterSelect($select);
		
//		echo '<pre>';
//		var_dump($resp);
//		echo '</pre>';

		return $resp;
	}

}
