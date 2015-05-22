<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Esta clase va a recibir las peticiones por la url, y las procesa.
 * ---------------------------------------------------------------------------------------------------------------------
 */

class Request {

	private $_modulo;
	private $_controlador;
	private $_metodo;
	private $_argumentos;

	public function __construct()
	{
		if (isset($_GET['url']))
		{
			$url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
			$url = Hash::urlDecrypt($url);
			$url = filter_var($url, FILTER_SANITIZE_URL);

			if (PRUEBAS_RUTAS == 'On')
			{
				echo BASE_URL.$url;
				echo '<hr/>';
			}

			if (substr($url, -7, 7) == 'encrypt')
			{
				$url = substr($url, 0, -7);
			}
			else
			{
				if (PRUEBAS == 'On')
					throw new Exception('Ruta invalida');
				else
					header('location:' . BASE_URL . Hash::urlEncrypt('sistem/error/access/404'));
			}

			$url = explode('/', $url);
			$url = array_filter($url);

			$this->_modulo = strtolower(array_shift($url));

			if (!$this->_modulo)
			{
				$this->_modulo = false;
			}
			else if ($this->_modulo == 'sistem')
			{
				$this->_modulo = false;
			}

			$this->_controlador = strtolower(array_shift($url));
			$this->_metodo = strtolower(array_shift($url));
			$this->_argumentos = $url;
		}

		if (!$this->_controlador)
		{
			$this->_controlador = DEFAULT_CONTROLLER;
		}

		if (!$this->_metodo)
		{
			$this->_metodo = 'index';
		}

		if (!isset($this->_argumentos))
		{
			$this->_argumentos = array();
		}
	}

	public function getModulo()
	{
		return $this->_modulo;
	}

	public function getControlador()
	{
		return $this->_controlador;
	}

	public function getMetodo()
	{
		return $this->_metodo;
	}

	public function getArgs()
	{
		return $this->_argumentos;
	}

}
