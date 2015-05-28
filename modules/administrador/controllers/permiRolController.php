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
		/* 		if (Session::get('logueado'))
		 * 			$this->_acl->acceso('ACCES_PERMISO', true, '5050');
		 * 		else
		 * 			$this->redireccionar();
		 */

		$condicion = array('ROL_ESTADO' => '1');
		$this->_view->ddlRoles = $this->_master->masterSelect('*', 'T_ROLES', $condicion);

		$this->_view->titulo = 'Gestionar Permisos';
		$this->_view->setPlugins($plugins = array('jquery', 'pagPost'));

		if ($this->getInt('enviar') == 1)
		{
			$this->_view->datos = $_POST;

			$parametros['ddlRoles'] = array(
				'requerido' => true,
				'valCode' => array(
					'V001',
					'V108'
				),
				'mensaje' => 'Roles: '
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
		else if ($this->getInt('enviar') == '2' && $this->getInt('btnGuardar') == 'Guardar')
		{
			$this->modificar($registros);
			$this->cargar($this->getPostParam('ddlRoles'), $pagina);
		}

		$this->_view->renderizar('roles', 'permisos', 'login');
	}

	public function cargar($rol, $pagina)
	{
		$this->_view->filtros = $_POST;
		/* 		if ($this->getPostParam('btnFiltros') == 'limpiar')
		 * 		{
		 * 			$this->_view->filtros = false;
		 */
		$filtros = array();
		/* 		}
		 * 		else if ($this->getPostParam('btnFiltros') == 'filtrar')
		 * 		{
		 * 			$parametros['txtFLlave'] = array(
		 * 				'requerido' => false,
		 * 				'valCode' => array(
		 * 					'V109'
		 * 				)
		 * 			);
		 * 			$parametros['txtFDetalle'] = array(
		 * 				'requerido' => false,
		 * 				'valCode' => array(
		 * 					'V103'
		 * 				)
		 * 			);
		 * 			$parametros['txtFEstado'] = array(
		 * 				'requerido' => false,
		 * 				'valCode' => array(
		 * 					'V101'
		 * 				)
		 * 			);
		 * 			$parametros['txtFFecha'] = array(
		 * 				'requerido' => false,
		 * 				'valCode' => array(
		 * 					'V107'
		 * 				)
		 * 			);
		 * 			$parametros['txtFHora'] = array(
		 * 				'requerido' => false,
		 * 				'valCode' => array(
		 * 					'V110'
		 * 				)
		 * 			);
		 *
		 * 			$val = $this->validar($parametros);
		 *
		 * 			if ($val == 1)
		 * 			{
		 * 				$filtros = array(
		 * 					'PERMISO_DETALLE' => $this->getPostParam('txtFDetalle'),
		 * 					'PERMISO_KEY' => $this->getPostParam('txtFLlave'),
		 * 					'EST_REG_TIP_EST' => $this->getPostParam('txtFEstado'),
		 * 					'EST_REG_FECHA_REGISTRO' => $this->getPostParam('txtFFecha'),
		 * 					'EST_REG_HORA_REGISTRO' => $this->getPostParam('txtFHora')
		 * 				);
		 * 			}
		 * 			else
		 * 			{
		 * 				$filtros = array();
		 * 			}
		 * 		}
		 * 		else
		 * 		{
		 * 			$filtros = array();
		 * 		}
		 */

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
		$permisos = $this->_pag->rownumSelect($tablas, '*', $count, $numFilas, $pagina, $filtros, $extra);
		$this->_view->permisos = $permisos;

		$permRoles = array();
		for ($i = 0; $i < $permisos['numRows']; $i++)
		{
			$tablaRol = 'T_PERMI_ROLES PR '
					. 'INNER JOIN T_ESTADOS_REG ESR'
					. ' ON ESR.EST_REG_ID = PR.PERMROL_EST_REG';
			$condicionRol = array(
				'PERMROL_ID_ROL' => $rol,
				'PERMROL_ID_PERMISO' => $permisos['PERMISO_ID'][$i]
			);
			$resp = $this->_master->masterSelect('*', $tablaRol, $condicionRol, 'ORDER BY PERMROL_ID DESC');
			$permRoles = array_merge_recursive($permRoles, $resp);
		}
		$this->_view->countRows = $count;
		$this->_view->permRoles = $permRoles;
		$num = $count['REGISTROS'][0] / $numFilas;
		$this->_view->paginas = round($num, 5, PHP_ROUND_HALF_EVEN);
		$this->_view->actual = $pagina;
	}

	public function modificar()
	{
		$parametros['hidRol'] = array(
			'requerido' => true,
			'valCode' => array(
				'V101'
			)
		);

		for ($i = 0; $i < count($_POST['hidId']); $i++)
		{
			$parametros['hidId' . $i] = array(
				'requerido' => true,
				'valCode' => array(
					'V101'
				)
			);
		}

		for ($i = 0; $i < count($_POST['chkEstado']); $i++)
		{
			$parametros['chkEstado' . $i] = array(
				'requerido' => false,
				'valCode' => array(
					'V109'
				)
			);
		}

		$val = $this->validar($parametros);
		if ($val == 1)
		{
			extract($_POST);
			for ($i = 0; $i < count($hidId); $i++)
			{
				$tablas = 'T_PERMI_ROLES PR '
						. 'INNER JOIN T_ESTADOS_REG ESR'
						. ' ON ESR.EST_REG_ID = PR.EST_REG_ID';
				$condiciones = array(
					'PERMROL_ID_ROL' => $hidRol[$i]
				);
				$permiRoles = $this->_master->masterSelect('ESR.EST_REG_TIP_EST,PR.PERMROL_ID', $tablas, $condiciones);

				if ($permiRoles['numRows'] > 0)
				{
					$clave = array_search($hidId[$i], $permiRoles['PERMROL_ID']);
					if (is_int($clave))
					{
						$verificar = array_search($hidId[$i], $chkEstado);
						if (!is_int($verificar))
						{
							if ($permiRoles['EST_REG_TIP_EST'][$clave] == 1)
							{
								$desactivar[] = $permiRoles['PERMROL_ID'][$clave];
							}
						}
						else
						{
							if ($permiRoles['EST_REG_TIP_EST'][$clave] == 2)
							{
								$activar[] = $permiRoles['PERMROL_ID'][$clave];
							}
						}
					}
				}
			}
		}
	}

}
