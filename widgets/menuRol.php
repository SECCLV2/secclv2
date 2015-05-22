<?php

class menuRolWidget extends Widget {

	private $_modelo;
	private $_modelo;

	public function __construct()
	{
		$this->_modelo = $this->loadModel('extens');
	}

	public function menu($item = false)
	{
		$menuRol = array();
		for ($i = 0; $i < Session::get('rol')['numRows']; $i++)
		{
			unset($innerJoin);
			unset($condicion);
			unset($menuCentroRol);

			$innerJoin = 'T_CUENTAS CUE '
					. 'LEFT JOIN T_CENTRO_CUEN CEC'
					. ' ON CEC.CEN_CUEN_ID_CUENTA = CUE.CUENTA_ID '
					. 'INNER JOIN T_ESTADOS_REG ETR'
					. ' ON ETR.EST_REG_ID = CEC.CEN_CUEN_EST_REG';
			$condicion = array(
				'CUE.CUENTA_ID_USUARIO' => Session::get('id'),
				'CUE.CUENTA_ID_ROL' => Session::get('rol')['CUENTA_ID_ROL'][$i],
				'ETR.EST_REG_TIP_EST' => 1,
			);
			$T_CUENTAS = $this->_modelo->masterSelect('*', $innerJoin, $condicion);

			if ($T_CUENTAS['numRows'] > 0)
				$submenu = true;
			else
			{
				$submenu = false;
				$menuCentroRol = false;
			}

			for ($j = 0; $j < $T_CUENTAS['numRows']; $j++)
			{
				$menuCentroRol[] = array(
					'id' => Session::get('rol')['ROL_DESCRIPCION'][$i] . ':' . $T_CUENTAS['CEN_CUEN_ID_CENTRO'][$j],
					'titulo' => $T_CUENTAS['CEN_CUEN_ID_CENTRO'][$j],
					'enlace' => BASE_URL . Hash::urlEncrypt('sistem/index/rol/' . Session::get('rol')['CUENTA_ID_ROL'][$i] . '/' . $T_CUENTAS['CEN_CUEN_ID_CENTRO'][$j]),
					'submenu' => false,
					'imagen' => ''
				);
			}

			$menuRol[] = array(
				'id' => Session::get('rol')['ROL_DESCRIPCION'][$i],
				'titulo' => Session::get('rol')['ROL_DESCRIPCION'][$i],
				'enlace' => BASE_URL . Hash::urlEncrypt('sistem/index/rol/' . Session::get('rol')['CUENTA_ID_ROL'][$i]),
				'submenu' => $submenu,
				'subArray' => $menuCentroRol,
				'imagen' => ''
			);
		}

		$menu[] = array(
			'id' => 'Roles',
			'titulo' => 'Roles',
			'submenu' => true,
			'subArray' => $menuRol,
			'imagen' => ''
		);
		$datos['menu'] = $menu;
		$datos['item'] = $item;

		return $this->render('menuRol', $datos);
	}

}
