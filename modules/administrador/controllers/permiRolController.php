<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Este controlador se encargara de gestionar el los permisos de los usuarios y los roles.
 * ---------------------------------------------------------------------------------------------------------------------
 */

class permiRolController extends administradorController {

	private $_pag;

	public function __construct()
	{
		parent::__construct();
		$this->_pag = $this->loadModel('paginacion');
	}

	public function roles($pagina)
	{
//		if (Session::get('logueado'))
//			$this->_acl->acceso('ACCES_PERMISO', true, '5050');
//		else
//			$this->redireccionar();

		$condicion = array('ROL_ESTADO' => '1');
		$this->_view->ddlRoles = $this->_master->masterSelect('*', 'T_ROLES', $condicion);

		$this->_view->titulo = 'Gestionar Permisos';
		$this->_view->setPlugins($plugins = array('jquery', 'pagPost'));

		if ($this->getInt('enviar') == 1)
		{
//			$this->_acl->acceso('INSERT_PERMISO', true, '5050');
			$parametros['ddlRoles'] = array(
				'requerido' => true,
				'valCode' => array(
					'V001',
					'V108'
				),
				'mensaje' => 'Roles:'
			);

			$val = $this->validar($parametros);
			if ($val != 1)
			{
				$this->_view->_error = $val;
				$this->_view->renderizar('roles', 'permisos', 'login');
				exit;
			}

			$this->cargar($this->getPostParam('ddlRoles'), $pagina);
		}
		else if ($this->getInt('enviar') == 'hola')
		{
			$this->modificar();
		}

		$this->_view->renderizar('roles', 'permisos', 'login');
	}

	public function cargar($rol, $pagina)
	{
		$this->_view->filtros = $_POST;
//		if ($this->getPostParam('btnFiltros') == 'limpiar')
//		{
//			$this->_view->filtros = false;
		$filtros = array();
//		}
//		else if ($this->getPostParam('enviar') == '2')
//		{
//			$parametros['txtFLlave'] = array(
//				'requerido' => false,
//				'valCode' => array(
//					'V109'
//				)
//			);
//			$parametros['txtFDetalle'] = array(
//				'requerido' => false,
//				'valCode' => array(
//					'V103'
//				)
//			);
//			$parametros['txtFEstado'] = array(
//				'requerido' => false,
//				'valCode' => array(
//					'V101'
//				)
//			);
//			$parametros['txtFFecha'] = array(
//				'requerido' => false,
//				'valCode' => array(
//					'V107'
//				)
//			);
//			$parametros['txtFHora'] = array(
//				'requerido' => false,
//				'valCode' => array(
//					'V110'
//				)
//			);
//
//			$val = $this->validar($parametros);
//
//			if ($val == 1)
//			{
//				$filtros = array(
//					'PERMISO_DETALLE' => $this->getPostParam('txtFDetalle'),
//					'PERMISO_KEY' => $this->getPostParam('txtFLlave'),
//					'EST_REG_TIP_EST' => $this->getPostParam('txtFEstado'),
//					'EST_REG_FECHA_REGISTRO' => $this->getPostParam('txtFFecha'),
//					'EST_REG_HORA_REGISTRO' => $this->getPostParam('txtFHora')
//				);
//			}
//			else
//			{
//				$filtros = array();
//			}
//		}
//		else
//		{
//			$filtros = array();
//		}

		$tablas = 'T_PERMI_ROLES PR '
				. 'LEFT JOIN T_PERMISOS TP'
				. ' ON TP.PERMISO_ID = PR.PERMROL_ID_PERMISO '
				. 'INNER JOIN T_ESTADOS_REG ESR'
				. ' ON ESR.EST_REG_ID = TP.PERMISO_EST_REG';
		$extra = array(
			'orderBy' => true,
			'campos' => 'PERMISO_ID',
			'sentido' => 'ASC',
			'condiciones' => array(
				'EST_REG_TIP_EST' => 1,
				'PERMROL_ID_ROL' => $rol
			)
		);

		$count = $this->_view->permisos = $this->_pag->count($tablas, $filtros, $extra);
		$this->_view->permisos = $this->_pag->rownumSelect($tablas, '*', $count, 5, $pagina, $filtros, $extra);
		$num = $count['REGISTROS'][0] / 5;
		$this->_view->paginas = round($num, 5, PHP_ROUND_HALF_EVEN);
		$this->_view->actual = $pagina;
	}

	public function modificar()
	{
		
	}

}
