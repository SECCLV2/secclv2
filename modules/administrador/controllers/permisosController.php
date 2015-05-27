<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Este controlador se encargara de gestionar el los permisos de los usuarios y los roles.
 * ---------------------------------------------------------------------------------------------------------------------
 */

class permisosController extends administradorController {

	private $_pag;

	public function __construct()
	{
		parent::__construct();
		$this->_pag = $this->loadModel('paginacion');
	}

	public function permisos($pagina)
	{
//		if (Session::get('logueado'))
//			$this->_acl->acceso('ACCES_PERMISO', true, '5050');
//		else
//			$this->redireccionar();

		$this->_view->titulo = 'Gestionar Permisos';
		$this->_view->setPlugins($plugins = array('pagPost', 'chk_switch'));
		if ($this->getInt('enviar') == 1)
		{
//			$this->_acl->acceso('INSERT_PERMISO', true, '5050');
			$this->registrar();
		}
		else if ($this->getInt('enviar') == 'hola')
		{
			$this->modificar();
		}

		$this->cargar($pagina);
		$this->_view->renderizar('permisos', 'permisos', 'login');
	}

	public function registrar()
	{
		$this->_view->datos = $_POST;

		$parametros['txtLlave'] = array(
			'requerido' => true,
			'valCode' => array(
				'V001',
				'V109',
				'V201',
				'V202',
				'V301'
			),
			'mensaje' => 'Llave del Permiso:',
			'max' => 50,
			'min' => 5,
			'table' => 'T_PERMISOS',
			'campo' => 'PERMISO_KEY',
		);

		$parametros['txtDPermiso'] = array(
			'requerido' => true,
			'valCode' => array(
				'V001',
				'V103',
				'V201',
				'V202'
			),
			'mensaje' => 'Detalles del Permiso:',
			'max' => 100,
			'min' => 10
		);

		$val = $this->validar($parametros);
		if ($val != 1)
		{
			$this->_view->_error = $val;
			$this->_view->renderizar('permisos', 'permisos', 'login');
			exit;
		}

		$transac = $this->_master->transac();
		if ($transac)
		{
			$campos['T_ESTADOS_REG'] = array(
				'EST_REG_TIP_EST' => 1,
				'EST_REG_DESCRIPCION' => 'INSERT - Nuevo permiso',
				'EST_REG_TABLA' => 21,
			);
			$idEstado = $this->_reg->registroInsert($campos['T_ESTADOS_REG']);

			$campos['T_PERMISOS'] = array(
				'PERMISO_KEY' => $this->getPostParam('txtLlave'),
				'PERMISO_DETALLE' => $this->getPostParam('txtDPermiso'),
				'PERMISO_EST_REG' => $idEstado,
			);
			$T_PERMISOS = $this->_master->masterInsert(true, 'T_PERMISOS', $campos['T_PERMISOS'], 'PERMISO_ID');
		}
		else
		{
			throw new Exception('Error al crear la transacción');
		}

		if (is_int($T_PERMISOS))
			$T_ESTADOS_REG = $this->_reg->registroUpdate($T_PERMISOS, $idEstado, false);

		if (!is_int($idEstado) || !is_int($T_PERMISOS) || !is_int($T_ESTADOS_REG))
		{
			$this->_view->_error = 'Error al registrar el usuario';
			$this->_view->renderizar('permisos', 'permisos', 'login');
			exit;
		}

		$this->_view->datos = false;
		$this->_view->_mensaje = 'Permiso Insertado Correctamente';
	}

	public function cargar($pagina)
	{
		$this->_view->filtros = $_POST;
		if ($this->getPostParam('btnFiltros') == 'limpiar')
		{
			$this->_view->filtros = false;
			$filtros = array();
		}
		else if ($this->getPostParam('btnFiltros') == 'filtrar')
		{
			$parametros['txtFLlave'] = array(
				'requerido' => false,
				'valCode' => array(
					'V109'
				)
			);
			$parametros['txtFDetalle'] = array(
				'requerido' => false,
				'valCode' => array(
					'V103'
				)
			);
			$parametros['txtFEstado'] = array(
				'requerido' => false,
				'valCode' => array(
					'V101'
				)
			);
			$parametros['txtFFecha'] = array(
				'requerido' => false,
				'valCode' => array(
					'V107'
				)
			);
			$parametros['txtFHora'] = array(
				'requerido' => false,
				'valCode' => array(
					'V110'
				)
			);

			$val = $this->validar($parametros);

			if ($val == 1)
			{
				$filtros = array(
					'PERMISO_DETALLE' => $this->getPostParam('txtFDetalle'),
					'PERMISO_KEY' => $this->getPostParam('txtFLlave'),
					'EST_REG_TIP_EST' => $this->getPostParam('txtFEstado'),
					'EST_REG_FECHA_REGISTRO' => $this->getPostParam('txtFFecha'),
					'EST_REG_HORA_REGISTRO' => $this->getPostParam('txtFHora')
				);
			}
			else
			{
				$filtros = array();
			}
		}
		else
		{
			$filtros = array();
		}

		$tablas = 'T_PERMISOS TP '
				. 'INNER JOIN T_ESTADOS_REG ESR'
				. ' ON ESR.EST_REG_ID = TP.PERMISO_EST_REG';
		$extra = array(
			'orderBy' => true,
			'campos' => 'PERMISO_ID',
			'sentido' => 'ASC'
		);
		$numFilas = 1;
		$count = $this->_view->permisos = $this->_pag->count($tablas, $filtros, $extra);
		$this->_view->permisos = $this->_pag->rownumSelect($tablas, '*', $count, $numFilas, $pagina, $filtros, $extra);
		$num = $count['REGISTROS'][0] / $numFilas;
		$this->_view->paginas = round($num, 99, PHP_ROUND_HALF_EVEN);
		$this->_view->actual = $pagina;
	}

	public function modificar()
	{
		
	}

}
