<?php

/*
 * -----------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Este modelo se encargara de traer los datos de todas las listas 
 * desplegables de los formularios.
 * -----------------------------------------------------------------------------
 */

class loginModel extends Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function login($usuario)
	{
		$tablas = 'T_USUARIOS USU '
				. 'INNER JOIN T_HIST_ESTS HIS'
				. ' ON HIS.HIST_EST_ID = USU.USU_ID_HIST_EST ';

		$select = array(
			'campos' => '*',
			'tablas' => $tablas,
			'condiciones' => "WHERE USU.USU_NOMBRE_USU = '$usuario'"
			. 'AND HIS.HIST_EST_ID_ESTADO = 1'
		);
		$resp = $this->masterSelect($select);

		return $resp;
	}

	public function iniciarSesion($usuarioId)
	{
		$tablas = 'T_USUS_ROLES USR '
				. 'INNER JOIN T_ROLES ROL'
				. ' ON ROL.ROL_ID = USR.USU_ROL_ID_ROL '
				. 'INNER JOIN T_HIST_ESTS HIS'
				. ' ON HIS.HIST_EST_ID = USR.USU_ROL_ID_HIST_EST';
		$select = array(
			'campos' => 'DISTINCT '
			. 'USR.USU_ROL_ID_ROL,'
			. 'ROL.ROL_DESCRIPCION',
			'tablas' => $tablas,
			'condiciones' => "WHERE USR.USU_ROL_ID_USUARIO = '$usuarioiD'"
			. 'AND HIS.HIST_EST_ID_ESTADO = 1',
			'extra' => 'ORDER BY USR.USU_ROL_ID_ROL ASC'
		);
		$resp = $this->masterSelect($select);

		return $resp;
	}

}
