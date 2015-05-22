<?php

/*
 * -----------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Este modelo se encargara de traer los datos de todas las listas 
 * desplegables de los formularios.
 * -----------------------------------------------------------------------------
 */

class masterModel extends Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function transac()
	{
		parent::setAttribute(PDO::ATTR_AUTOCOMMIT, FALSE);
		return parent::beginTransaction();
	}

	public function masterSelect($campos, $table, array $condiciones = array(), $extra = '')
	{
		if (is_array($campos))
		{
			$camp = '';
			foreach ($campos as $key => $value)
			{
				if (strtoupper((string) $key) == 'DISTINCT' && $value)
					$camp = 'DISTINCT ' . $camp;
				else
					$camp .= $value . ',';
			}
			$campos = substr($camp, 0, -1);
		}
		if (!empty($condiciones))
		{
			$arrayCondicion = array_keys($condiciones);
			$condicion = 'WHERE ';
			for ($i = 0; $i < count($arrayCondicion); $i++)
			{
				$condicion .= $arrayCondicion[$i] . ' = '
						. ':' . str_replace('.', '_', $arrayCondicion[$i]) . ' AND ';
			}
			$condicion = substr($condicion, 0, -5);
		}
		else
		{
			$condicion = '';
		}
		$sql = parent::prepare("SELECT $campos "
						. "FROM $table "
						. "$condicion "
						. "$extra");
		if (!empty($condicion))
		{
			for ($i = 0; $i < count($arrayCondicion); $i++)
			{
				$sql->bindParam(':' . str_replace('.', '_', $arrayCondicion[$i]), $condiciones[$arrayCondicion[$i]]);
			}
		}

		if (PRUEBAS_BD == 'On')
		{
			echo '<pre>';
			echo 'Master Select: <br/>';
			print_r($sql);
			echo '</pre><hr/>';
		}

		return $this->queryGet($sql);
	}

	public function masterInsert($commit, $table, array $campos, $id)
	{
		$arrayCampo = array_keys($campos);
		$campo = '';
		$valor = '';
		for ($i = 0; $i < count($arrayCampo); $i++)
		{
			$campo .= $arrayCampo[$i] . ',';
			$valor .= ':' . $arrayCampo[$i] . ',';
		}
		$campo = substr($campo, 0, -1);
		$valor = substr($valor, 0, -1);
		$sql = parent::prepare("INSERT INTO $table($campo)"
						. "VALUES ($valor)returning $id into :id");
		for ($i = 0; $i < count($arrayCampo); $i++)
		{
			$sql->bindParam(':' . $arrayCampo[$i], $campos[$arrayCampo[$i]]);
		}

		return $this->querySet($sql, $commit);
	}

	public function masterUpdate($commit, $table, array $campos, array $condiciones, $id)
	{

		$arrayCampo = array_keys($campos);
		$campo = '';
		for ($i = 0; $i < count($arrayCampo); $i++)
		{
			$campo .= $arrayCampo[$i] . ' = ' . ':C' . $arrayCampo[$i] . ',';
		}
		$campo = substr($campo, 0, -1);

		$arrayCondicion = array_keys($condiciones);
		$condicion = 'WHERE ';
		for ($i = 0; $i < count($arrayCondicion); $i++)
		{
			$condicion .= $arrayCondicion[$i] . ' = ' . ':Q' . $arrayCondicion[$i] . ' AND ';
		}
		$condicion = substr($condicion, 0, -5);

		$sql = parent::prepare("UPDATE $table "
						. "SET $campo "
						. "$condicion "
						. "returning $id into :id");

		for ($i = 0; $i < count($arrayCampo); $i++)
		{
			$sql->bindParam(':C' . $arrayCampo[$i], $campos[$arrayCampo[$i]]);
		}

		for ($i = 0; $i < count($arrayCondicion); $i++)
		{
			$sql->bindParam(':Q' . $arrayCondicion[$i], $condiciones[$arrayCondicion[$i]]);
		}

		return $this->querySet($sql, $commit);
	}

}
