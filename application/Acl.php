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

		$this->_db = new applicationModel();
		if ($this->_id != 0 && $this->_rol != 0)
		{
			$this->_permRol = $this->getPermisosRol();
			$this->_permUsu = $this->getPermisosUsuario();
		}

		if (PRUEBAS_ACL)
		{
			echo '<pre>';
			echo 'ACL / Constructor - Permisos Rol:';
			var_dump($this->_permRol);
			echo '<hr>';
			echo '<hr>';
			echo 'ACL / Constructor  - Permisos Usuario:';
			var_dump($this->_permUsu);
			echo '</pre>';
		}
	}

	/*
	 * Función que se encarga de traer los permisos de los roles.
	 */

	public function getPermisosRol()
	{
		for ($i = 0; $i < count($this->_rol['USU_ROL_ID_ROL']); $i++)
		{
			$permisos[$this->_rol['ROL_DESCRIPCION'][$i]] = $this->_db->AclGetPermisosRol($this->_rol['USU_ROL_ID_ROL'][$i]);
		}

		return $permisos;
	}

	/*
	 * Función que se encarga de traer los permisos de los usuarios.
	 */

	public function getPermisosUsuario()
	{
		$permisos = $this->_db->AclGetPermisosUsuario($this->_id);

		return $permisos;
	}

	/*
	 * Función que se encarga de evaluar si un usuario tiene los permisos para realizar una acción.
	 */

	public function acceso($key, $error, $codigo = 'default')
	{
		if (PRUEBAS_ACL)
		{
			echo '<pre>';
			echo 'ACL / Acceso - Rol Actual:';
			var_dump(Session::get('nomRol'));
			echo '</pre>';
		}

		if (isset($this->_permRol[Session::get('nomRol')]['PERMISO_KEY']))
			$permRol = array_search($key, $this->_permRol[Session::get('nomRol')]['PERMISO_KEY']);

		if (PRUEBAS_ACL)
		{
			echo '<pre>';
			echo 'ACL / Acceso - Permiso en rol:';
			var_dump($permRol);
			echo '</pre>';
		}

		if (isset($this->_permUsu['PERMISO_KEY']))
			$permUsu = array_search($key, $this->_permUsu['PERMISO_KEY']);

		if (PRUEBAS_ACL)
		{
			echo '<pre>';
			echo 'ACL / Acceso - Permiso en usuario:';
			var_dump($permUsu);
			echo '</pre>';
		}

		if (isset($permRol) && is_int($permRol) && $this->_permRol[Session::get('nomRol')]['HIST_EST_ID_ESTADO'][$permRol] == 1)
			return true;

		if (isset($permUsu) && is_int($permUsu) && $this->_permUsu['PERMISO_ESTADO'][$permUsu] == 1)
			return true;

		if ($error)
			header('location:' . BASE_URL . Hash::urlEncrypt('sistem/error/access/' . $codigo));
		else
			return false;
	}

}
