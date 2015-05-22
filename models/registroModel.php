<?php

/*
 * -----------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Este modelo se encargara insertar los datos en la tabla estados registro.
 * -----------------------------------------------------------------------------
 */

class registroModel extends Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function registroInsert(array $campos)
	{
		if (key_exists('EST_REG_TIP_EST', $campos) && key_exists('EST_REG_DESCRIPCION', $campos) && key_exists('EST_REG_TABLA', $campos))
		{
			$sql = parent::prepare("INSERT INTO T_ESTADOS_REG("
							. 'EST_REG_USU_REG,'
							. 'EST_REG_TIP_EST,'
							. 'EST_REG_DESCRIPCION,'
							. 'EST_REG_TABLA,'
							. 'EST_REG_ID_REGISTRO'
							. ') VALUES ('
							. Session::get('id') . ','
							. $campos['EST_REG_TIP_EST'] . ','
							. "'" . $campos['EST_REG_DESCRIPCION'] . "',"
							. $campos['EST_REG_TABLA'] . ","
							. '0'
							. ")returning EST_REG_ID into :id");
		}

		if (PRUEBAS_BD == 'On')
		{
			echo '<pre>';
			echo 'Registro Insert: <br/>';
			print_r($sql);
			echo '</pre><hr/>';
		}

		return $this->querySet($sql, false);
	}

	public function registroUpdate($id, $idReg, $commit)
	{
		$sql = parent::prepare('UPDATE T_ESTADOS_REG '
						. "SET EST_REG_ID_REGISTRO = $id "
						. "WHERE EST_REG_ID = $idReg "
						. "returning EST_REG_ID into :id");

		if (PRUEBAS_BD == 'On')
		{
			echo '<pre>';
			echo 'Registro Update: <br/>';
			print_r($sql);
			echo '</pre><hr/>';
		}

		return $this->querySet($sql, $commit);
	}

}
