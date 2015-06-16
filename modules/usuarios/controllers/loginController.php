<?php

/*
 * -----------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Este controlador se encarga de gestionar el inicio y/o finalización de
 * una sesión
 * -----------------------------------------------------------------------------
 */

class loginController extends usuariosController {

	public function __construct()
	{
		parent::__construct();
		$this->_bdLogin = $this->loadModel('login','usuarios');
	}

	public function login()
	{
		if (Session::get('logueado'))
		{
			$this->redireccionar();
		}

		$this->_view->titulo = 'Iniciar Sesión';

		if ($this->getInt('enviar') == 1)
		{
			$this->_view->datos = $_POST;

			$parametros['txtUsuario'] = array(
				'requerido' => true,
				'valCode' => array(
					'V001',
					'V105'
				),
				'mensaje' => 'Usuario:'
			);

			$parametros['txtPassword'] = array(
				'requerido' => true,
				'valCode' => array(
					'V001',
					'V105'
				),
				'mensaje' => 'Contraseña:'
			);

			$val = $this->validar($parametros);
			if ($val != 1)
			{
				$this->_view->_error = $val;
				$this->_view->renderizar('login', 'registro');
				exit;
			}

			$txtUsuario = $this->getPostParam('txtUsuario');
			$txtPassword = $this->getPostParam('txtPassword');

			$T_USUARIOS = $this->_bdLogin->login($txtUsuario);
			if ($T_USUARIOS['numRows'] != 1)
			{
				$this->_view->_error = 'Usuario y/o contraseña incorrectos';
				$this->_view->renderizar('login', 'registro');
				exit;
			}
			else if (!Hash::verificarPassword($txtPassword, $T_USUARIOS['USU_CLAVE'][0]))
			{
				$this->_view->_error = 'Usuario y/o contraseña incorrectos';
				$this->_view->renderizar('login', 'login');
				exit;
			}


			if ($T_USUARIOS['HIST_EST_ID_ESTADO'][0] != 1)
			{
				$this->_view->_error = 'Este usuario se encuentra inhabilitado';
				$this->_view->renderizar('login', 'login');
				exit;
			}

			$T_CUENTAS = $this->_bdLogin->iniciarSesion($T_USUARIOS['USU_ID'][0]);

			Session::set('logueado', true);
			Session::set('menu', 0);
			Session::set('rol', $T_CUENTAS);
			Session::set('id', $T_USUARIOS['USU_ID'][0]);
			Session::set('nombres', $T_USUARIOS['USU_NOMBRE'][0]);
			Session::set('apellidos', $T_USUARIOS['USU_PRIMER_APELLIDO'][0] . ' ' . $T_USUARIOS['USU_SEGUNDO_APELLIDO'][0]);
			Session::set('tiempo', time());

			$this->redireccionar();
		}

		$this->_view->renderizar('login', 'login');
	}

	public function logout()
	{
		Session::destroy();
		$this->redireccionar();
	}

}
