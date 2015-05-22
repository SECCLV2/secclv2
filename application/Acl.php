<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Esta clase se encargara de manejar las listas de acceso y los permisos que tienen los roles y usuarios. 
 * ---------------------------------------------------------------------------------------------------------------------
 */

class Acl {

	private $_db;
	private $_id;
	private $_rol;
	private $_permRol;
	private $_permUsu;

	/*
	 * Constructor que permisos del usuario y el rol
	 */

	public function __construct()
	{
		if (Session::get('id'))
		{
			$this->_id = Session::get('id');
		}
		else
		{
			$this->_id = 0;
		}

		if (Session::get('rol'))
		{
			$this->_rol = Session::get('rol');
		}
		else
		{
			$this->_rol = 0;
		}

		$this->_db = new Model();
		if ($this->_id != 0 && $this->_rol != 0)
		{
			$this->_permRol = $this->getPermisosRol();
			$this->_permUsu = $this->getPermisosUsuario();
		}
	}

	/*
	 * Función que se encarga de traer los permisos de los roles.
	 */

	public function getPermisosRol()
	{
		for ($i = 0; $i < count($this->_rol['CUENTA_ID_ROL']); $i++)
		{
			$permisos[$this->_rol['ROL_DESCRIPCION'][$i]] = $this->_db->masterEspecial(
					'SELECT * '
					. 'FROM T_PERMI_ROLES PR '
					. 'INNER JOIN T_PERMISOS TP'
					. ' ON TP.PERMISO_ID = PR.PERMROL_ID_PERMISO '
					. 'INNER JOIN T_ESTADOS_REG ESR'
					. ' ON ESR.EST_REG_ID = PR.PERMROL_EST_REG '
					. "WHERE PR.PERMROL_ID_ROL = '{$this->_rol['CUENTA_ID_ROL'][$i]}'"
					. ' AND ESR.EST_REG_TIP_EST = 1'
			);
		}

		return $permisos;
	}

	/*
	 * Función que se encarga de traer los permisos de los usuarios.
	 */

	public function getPermisosUsuario()
	{
		$permisos = $this->_db->masterEspecial(
				'SELECT * '
				. 'FROM T_PERMI_USUS PU '
				. 'INNER JOIN T_PERMISOS TP'
				. ' ON TP.PERMISO_ID = PU.PERMUSU_ID_PERMISO '
				. 'INNER JOIN T_ESTADOS_REG ESR'
				. ' ON ESR.EST_REG_ID = PU.PERMUSU_EST_REG '
				. "WHERE PU.PERMUSU_ID_USUARIO = '{$this->_id}'"
				. ' AND ESR.EST_REG_TIP_EST = 1'
		);

		return $permisos;
	}

	/*
	 * Función que se encarga de evaluar si un usuario tiene los permisos para realizar una acción.
	 */

	public function acceso($key, $error, $codigo = 'default')
	{
		if (isset($this->_permRol[Session::get('nomRol')]['PERMISO_KEY']))
			$permRol = array_search($key, $this->_permRol[Session::get('nomRol')]['PERMISO_KEY']);

		if (isset($this->_permUsu['PERMISO_KEY']))
			$permUsu = array_search($key, $this->_permUsu['PERMISO_KEY']);

		if (($permRol) && ($this->_permRol[Session::get('nomRol')]['PERMISO_ESTADO'][$permRol] == 1 &&
				$this->_permRol[Session::get('nomRol')]['PERMROL_ESTADO'][$permRol] == 1))
			return true;

		if (($permUsu) && ($this->_permUsu['PERMISO_ESTADO'][$permUsu] == 1 &&
				$this->_permUsu['PERMUSU_ESTADO'][$permUsu] == 1))
			return true;

		if ($error)
			header('location:' . BASE_URL . Hash::urlEncrypt('sistem/error/access/' . $codigo));
		else
			return false;
	}

}
