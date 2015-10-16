<?php

/*
 * -----------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Este modelo se encargara de traer los datos de todas las listas 
 * desplegables de los formularios.
 * -----------------------------------------------------------------------------
 */

class applicationModel extends Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function AclGetPermisosRol($rol)
	{
		$select = array(
			'campos' => '*',
			'tablas' => 'T_PERMI_ROLES PR '
			. 'INNER JOIN T_PERMISOS TP '
			. ' ON TP.PERMISO_ID = PR.PERMROL_ID_PERMISO '
			. 'INNER JOIN T_HIST_ESTS HIS '
			. ' ON HIS.HIST_EST_ID = PR.PERMROL_ID_HIST_EST ',
			'condiciones' => "WHERE PR.PERMROL_ID_ROL = '$rol'"
			. ' ADN HIS.HIST_EST_ID_ESTADO = 1'
		);
		$resp = $this->masterSelect($select);

		return $resp;
	}

	public function AclGetPermisosUsuario($usuario)
	{
		$select = array(
			'campos' => '*',
			'tablas' => 'T_PERMI_USUS PU '
			. 'INNER JOIN T_PERMISOS TP'
			. ' ON TP.PERMISO_ID = PU.PERMUSU_ID_PERMISO '
			. 'INNER JOIN T_HIST_ESTS HIS '
			. ' ON HIS.HIST_EST_ID = PU.PERMUSU_ID_HIST_EST ',
			'condiciones' => "WHERE PU.PERMUSU_ID_USUARIO = '$usuario'"
			. ' AND HIS.HIST_EST_ID_ESTADO = 1'
		);
		$resp = $this->masterSelect($select);

		return $resp;
	}

	public function ControllerValidar($tabla)
	{
		$select = array(
			'campos' => '*',
			'tablas' => $tabla,
			'condiciones' => "WHERE PU.PERMUSU_ID_USUARIO = '$usuario'"
			. ' AND ESR.EST_REG_TIP_EST = 1'
		);
		$resp = $this->masterSelect($select);

		return $resp;
	}

	public function menuRol($cont)
	{
		$tablas = 'T_USUS_ROLES USR '
				. 'LEFT JOIN T_CEN_USUROLS CUR'
				. ' ON CUR.CEN_USU_ROL_ID_USU_ROL = USR.USU_ROL_ID '
				. 'INNER JOIN T_HIST_ESTS HIS'
				. ' ON HIS.HIST_EST_ID = CUR.CEN_USU_ROL_ID_HIST_EST';
		
		$select = array(
			'campos' => '*',
			'tablas' => $tablas,
			'condiciones' => 'WHERE USR.USU_ROL_ID_USUARIO = ' . Session::get('id')
			. ' AND USR.USU_ROL_ID_ROL = ' . Session::get('rol')['USU_ROL_ID_ROL'][$cont]
			. 'HIS.HIST_EST_ID_ESTADO = 1'
		);
		$resp = $this->masterSelect($select);

		return $resp;
	}
	
	public function validarEstado($id,$estado)
	{
		$select = array(
			'campos' => '*',
			'tablas' => 'T_HIST_ESTS ',
			'condiciones' => "WHERE HIST_EST_ID = '$id'"
			. " AND HIST_EST_ID_ESTADO = '$estado'"
		);
		$resp = $this->masterSelect($select);

		return $resp;
	}

}
