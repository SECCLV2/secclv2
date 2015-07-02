<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Esta clase contendra todos los metodos y atributos que seran utilizados por los controladores.
 * ---------------------------------------------------------------------------------------------------------------------
 */

abstract class Controller {

	protected $_acl;
	protected $_view;
	protected $_request;

	/*
	 * Constructor que llama las vistas para ser usadas.
	 */

	public function __construct()
	{
		$this->_acl = new Acl();
		$this->_request = new Request();
		$this->_view = new View($this->_request, $this->_acl, $this->loadModel('application'));
	}

	/*
	 * Función abstracta que sera el metodo por default de los controladores de las vistas.
	 */

	abstract public function index();

	/*
	 * Función que carga los modelos requeridos por los controladores.
	 */

	protected function loadModel($modelo, $modulo = 'default')
	{
		$modelo = $modelo . 'Model';
		$rutaModelo = ROOT . 'models' . DS . $modelo . '.php';

		if (!$modulo)
		{
			$modulo = $this->_request->getModulo();
		}

		if ($modulo)
		{
			if ($modulo != 'default')
			{
				$rutaModelo = ROOT . 'modules' . DS . $modulo . DS . 'models' . DS . $modelo . '.php';
			}
		}

		if (is_readable($rutaModelo))
		{
			require_once $rutaModelo;
			$modelo = new $modelo();

			return $modelo;
		}
		else
		{
			throw new Exception('Modelo no encontrado ' . $rutaModelo);
		}
	}

	/*
	 * Función que carga las librerias requeridas por los controladores.
	 */

	protected function getLibrary($carpeta, $libreria)
	{
		$rutaLibreria = ROOT . 'libs' . DS . $carpeta . DS . $libreria . '.php';

		if (is_readable($rutaLibreria))
		{
			require_once $rutaLibreria;
		}
		else
		{
			throw new Exception('Error en la ruta de la libreria: </br> is_readable = false </br> Ruta Libreria: ' . $libreria);
		}
	}

	/*
	 * Función que redirecciona el controlador.
	 */

	public function redireccionar($ruta = false)
	{
		if ($ruta)
		{
			header('location:' . BASE_URL . Hash::urlEncrypt($ruta));
			exit;
		}
		else
		{
			header('location:' . BASE_URL . Hash::urlEncrypt('sistem'));
			exit;
		}
	}

	/*
	 * Función que trae un valor enviado por post y evalua si es un entero
	 */

	protected function getInt($clave)
	{
		if (isset($_POST[$clave]) && !empty($_POST[$clave]))
		{
			$_POST[$clave] = filter_input(INPUT_POST, $clave, FILTER_VALIDATE_INT);
			return (int) $_POST[$clave];
		}

		return 0;
	}

	/*
	 * Función que trae un valor enviado por post.
	 */

	protected function getPostParam($clave)
	{
		if (strpos($clave, '//'))
		{
			$array = explode('//', $clave);
			if (array_key_exists($array[0], $_POST))
			{
				if (isset($_POST[$array[0]][$array[1]]) && !empty($_POST[$array[0]][$array[1]]))
				{
					$_POST[$array[0]][$array[1]] = strip_tags($_POST[$array[0]][$array[1]]);
					$_POST[$array[0]][$array[1]] = htmlspecialchars($_POST[$array[0]][$array[1]], ENT_QUOTES);
					return trim($_POST[$array[0]][$array[1]]);
				}
			}
			else
			{
				return '-1';
			}
		}
		else
		{
			if (array_key_exists($clave, $_POST))
			{
				if (isset($_POST[$clave]) && !empty($_POST[$clave]))
				{
					$_POST[$clave] = strip_tags($_POST[$clave]);
					$_POST[$clave] = htmlspecialchars($_POST[$clave], ENT_QUOTES);
					return trim($_POST[$clave]);
				}
			}
			else
			{
				return '-1';
			}
		}

		return 0;
	}

	/*
	 * Función que valida los datos enviados por post en los formularios.
	 * 
	 * La función como parametro un array con la siguiente estructura:
	 * $parametros['name'] = array(
	 * 			'requerido' => booleano,
	 * 			'valCode' => array(
	 * 				'codigo'
	 * 			),
	 * 			'mensaje' => 'string',
	 * 			'max' => int,
	 * 			'min' => int,
	 * 			'table' => 'string',
	 * 			'campo' => 'string',
	 * 			'extra' => array(
	 * 				'campo_tabla' => valor
	 * 			)
	 * 		);
	 * 
	 * Estructura del arreglo:
	 * - name: debe ser el nombre (name) del input al cual realizar la validación (este define la posición del arreglo).
	 * - requerido: debe ser un valor de booleano (este define si el campo validado es obligatorio o no).
	 * - valCode: debe ser un arreglo con los codigos de las validaciones a realizar en el campo.
	 * 		* codigo: debe ser un código de validacion implementado en la función (ver lista de códigos).
	 * - mensaje: debe ser un string, este contiene el prefijo del mensaje, Ej - el nombre del campo validado. 
	 * - max: debe ser un int, este campo se usa para realizar validaciones con código V2...
	 * - mix: debe ser un int, este campo se usa para realizar validaciones con código V2...
	 * - table: debe ser un string, es el nombre de la tabla en la base de datos donde se realizara la verificación este
	 * campo se usa para realizar validaciones con código V3...
	 * - campo: debe ser un string, es el campo de la tabla en la base de datos que sera verificado este campo se usa 
	 * para realizar validaciones con código V3...
	 * - extra: debe ser un array, se usa cuando hay campos extra a verificar en la tabla este campo se usa para 
	 * realizar validaciones con código V3...
	 * 		* campo_tabla: debe ser un string, es el nombre del campo en la tabla.
	 * 		* valor: sebe ser un string, es el valor del campo a verificar.
	 * 
	 * Los códigos de validación se estructuran de la siguiente forma:
	 * 
	 * Una V antes del número de la validación, el primer dígito hace referencia al tipo de validación (ver tipos de 
	 * validaciones) y los 2 ultimos dígitos hacen referencia al número de la validación.
	 * 
	 * Códigos:
	 * - V001: Campo obligatorio.
	 * - V101: Enteros.
	 * - V102: Letras y espacios.
	 * - V103: Números, letras, espacios y caracteres especiales (!¡¿?.:,;)
	 * - V104: Correo electronico.
	 * - V105: Números, letras y guión bajo
	 * - V106: Letras y números.
	 * - V107: Fecha.
	 * - V108: Listas desplegables.
	 * - V109: Letras mayusculas y guión bajo.
	 * - V110: Hora.
	 * - V201: Maximo número de caracteres.
	 * - V202: Minimo número de caracteres.
	 * - V203: Número no mayor que.
	 * - V204: Número no menor que.
	 * - V301: Consultar si un valor existe en la base de datos.
	 * 
	 * Retorna el mensaje de error de la primera validación que no pase, en caso de haber pasado todas las validaciones
	 * retorna 1.
	 */

	protected function validar(array $parametros)
	{
		$array = array_keys($parametros);
		for ($i = 0; $i < count($array); $i++)
		{
			$$array[$i] = $parametros[$array[$i]];
			$result = array();
			$dato = $this->getPostParam($array[$i]);

			for ($c = 0; $c < count(${$array[$i]}['valCode']); $c++)
			{
				if ($dato != '-1')
				{
					switch (${$array[$i]}['valCode'][$c])
					{
						case 'V001':
							if ($dato == null)
								$result['V001'] = 'Este campo es obligatorio';
							else
								$result['V001'] = 1;
							break;
						case 'V101':
							$dato = (int) $dato;
							if (!is_int($dato))
								$result['V101'] = 'Este campo debe ser un número';
							else
								$result['V101'] = 1;
							break;
						case 'V102':
							if (!preg_match("/^[A-ZÁÉÍÓÚÜÑa-záéíóúüñ ]{1,}$/", $dato))
								$result['V102'] = 'Este campo solo debe contener letras y espacios';
							else
								$result['V102'] = 1;
							break;
						case 'V103':
							if (!preg_match("/^[0-9A-ZÁÉÍÓÚÜÑa-záéíóúüñ!¡¿?:,;\.\s\(\)]{1,}$/", $dato))
								$result['V103'] = 'Este campo solo debe contener números, letras, espacios y caracteres especiales (!¡¿?.:,;)';
							else
								$result['V103'] = 1;
							break;
						case 'V104':
							if (!filter_var($dato, FILTER_VALIDATE_EMAIL))
								$result['V104'] = 'Dirección de correo electronico invalida';
							else
								$result['V104'] = 1;
							break;
						case 'V105':
							if (!preg_match("/^[0-9A-ZÁÉÍÓÚÜÑa-záéíóúüñ_]{1,}$/", $dato))
								$result['V105'] = 'Este campo solo debe contener números, letras y guión bajo';
							else
								$result['V105'] = 1;
							break;
						case 'V106':
							if (!preg_match("/^[A-Za-z0-9]{1,}$/", $dato))
								$result['V106'] = 'Este campo solo debe contener letras y números';
							else
								$result['V106'] = 1;
							break;
						case 'V107':
							if (!preg_match("/^(?:(?:0?[1-9]|1\d|2[0-8])(\/|-)(?:0?[1-9]|1[0-2]))(\/|-)"
											. "(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(?:(?:31(\/|-)"
											. "(?:0?[13578]|1[02]))|(?:(?:29|30)(\/|-)(?:0?[1,3-9]|1[0-2])))(\/|-)"
											. "(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(29(\/|-)0?2)"
											. "(\/|-)(?:(?:0[48]00|[13579][26]00|[2468][048]00)|(?:\d\d)?(?:0[48]|"
											. "[2468][048]|[13579][26]))$/", $dato))
								$result['V107'] = 'La fecha ingresada es invalida';
							else
								$result['V107'] = 1;
							break;
						case 'V108':
							$dato = (int) $dato;

							if ($dato == 0)
								$result['V108'] = 'Debe seleccionar un elemento de la lista';
							else
								$result['V108'] = 1;
							break;
						case 'V109':
							if (!preg_match("/^[A-Z_]{1,}$/", $dato))
								$result['V109'] = 'Este campo solo debe contener letras mayusculas y guión bajo';
							else
								$result['V109'] = 1;
							break;
						case 'V110':
							if (!preg_match("/^(0[0-9]|1[0-2]):[0-5][0-9] [AaPp][Mm]$/", $dato))
								$result['V110'] = 'La hora ingresada invalida';
							else
								$result['V110'] = 1;
							break;
						case 'V201':
							if (array_key_exists('max', ${$array[$i]}))
							{
								${$array[$i]}['max'] = (int) ${$array[$i]}['max'];

								if (is_int(${$array[$i]}['max']))
								{
									if (strlen($dato) > ${$array[$i]}['max'])
										$result['V201'] = 'Este campo no puede contener más de ' . ${$array[$i]}['max'] . ' caracteres';
									else
										$result['V201'] = 1;
								}
								else
								{
									throw new Exception('Parametro rango max no esta definido como entero');
								}
							}
							else
							{
								throw new Exception('Parametro rango max no declarado');
							}
							break;
						case 'V202':
							if (array_key_exists('min', ${$array[$i]}))
							{
								${$array[$i]}['min'] = (int) ${$array[$i]}['min'];

								if (is_int(${$array[$i]}['min']))
								{
									if (strlen($dato) < ${$array[$i]}['min'])
										$result['V202'] = 'Este campo no puede contener menos de ' . ${$array[$i]}['min'] . ' caracteres';
									else
										$result['V202'] = 1;
								}
								else
								{
									throw new Exception('Parametro rango min no esta definido como entero');
								}
							}
							else
							{
								throw new Exception('Parametro rango min no declarado');
							}
							break;
						case 'V203':
							if (array_key_exists('max', ${$array[$i]}))
							{
								${$array[$i]}['max'] = (int) ${$array[$i]}['max'];

								if (is_int(${$array[$i]}['max']))
								{
									if ($dato > ${$array[$i]}['max'])
										$result['V203'] = 'El número no puede ser mayor que ' . ${$array[$i]}['max'];
									else
										$result['V203'] = 1;
								}
								else
								{
									throw new Exception('Parametro rango max no esta definido como entero');
								}
							}
							else
							{
								throw new Exception('Parametro rango max no declarado');
							}
							break;
						case 'V204':
							if (array_key_exists('min', ${$array[$i]}))
							{
								${$array[$i]}['min'] = (int) ${$array[$i]}['min'];

								if (is_int(${$array[$i]}['min']))
								{
									if ($dato < ${$array[$i]}['min'])
										$result['V204'] = 'El número no puede ser menor que ' . ${$array[$i]}['min'];
									else
										$result['V204'] = 1;
								}
								else
								{
									throw new Exception('Parametro rango min no esta definido como entero');
								}
							}
							else
							{
								throw new Exception('Parametro rango min no declarado');
							}
							break;
						case 'V301':
							if (array_key_exists('table', ${$array[$i]}) && array_key_exists('campo', ${$array[$i]}))
							{
								$condicion .= ${$array[$i]}['campo'] . " = $dato AND ";

								if (array_key_exists('extra', ${$array[$i]}))
								{
									$arrayCondicion = array_keys(${$array[$i]}['extra']);

									for ($e = 1; $e <= count($arrayCondicion); $e++)
									{
										$condicion .= $arrayCondicion[$e - 1] . ' = ' . ${$array[$i]}['extra'][$arrayCondicion[$e - 1]] . ' AND ';
									}
								}

								$masterModel = $this->loadModel('application');
								$resp = $masterModel->masterSelect(${$array[$i]}['table'], $condicion);

								if (array_key_exists('estado', ${$array[$i]}) && $resp['numRows'] > 0)
								{
									$masterModel = $this->loadModel('application');
									$resp = $masterModel->validarEstado($resp[end($resp)][0], ${$array[$i]}['estado']);
								}
								else
								{
									$resp['numRows'] = 0;
								}

								if ($resp['numRows'] > 0)
									$result['V301'] = "El valor $dato ya se ecuentra registrado";
								else
									$result['V301'] = 1;
							}
							else
							{
								throw new Exception('Parametros table y/o campo no declarados');
							}
							break;
						default:
							throw new Exception('Código de validación invalido');
							break;
					}
				}
				else
				{
					throw new Exception("El campo $array[$i] no esta definido");
				}
			}

			foreach ($result as $keyResult => $valueResult)
			{
				if (array_key_exists('requerido', ${$array[$i]}) && ${$array[$i]}['requerido'] == false)
				{
					if (array_key_exists('V001', $result))
					{
						if ($result['V001'] == 1 && $keyResult != 'V001' && $valueResult != 1)
						{
							if (array_key_exists('mensaje', ${$array[$i]}))
								return ${$array[$i]}['mensaje'] . ' ' . $valueResult;
							else
								return $valueResult;
						}
					}
				}
				else
				{
                                    
					if ($valueResult != 1)
						if (array_key_exists('mensaje', ${$array[$i]}))
							return ${$array[$i]}['mensaje'] . ' ' . $valueResult;
						else
							return $valueResult;
				}
                                
			}
                        
		}
                
		return 1;
	}

}
