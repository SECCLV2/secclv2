<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Esta clase gestionara la conexión a la base de datos y la ejecución de consultas en la misma.
 * ---------------------------------------------------------------------------------------------------------------------
 */

class Model extends PDO {

	protected $_statement;
	protected $_exception;

	/*
	 * Constructor que crea la conexión a la bd y crea los objetos de las calses de PDO para ser usados.
	 */

	public function __construct()
	{
		parent::__construct("oci:dbname=" . DB_HOST . ';' . DB_CHAR, DB_NAME, DB_PASS);
		parent::setAttribute(parent::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->_statement = new PDOStatement();
		$this->_exception = new PDOException();
	}

	/*
	 * Función que organiza el resultado de una consulta en un arreglo de dos dimención con la estructura 
	 * $array['Nombre_Campo']['Registro_Tabla']
	 */

	protected function organizar($array)
	{
		$rows = array();

		for ($i = 0; $i < count($array); $i++)
		{
			foreach ($array[$i] as $key => $value)
			{
				$rows[$key][$i] = $value;
			}
		}

		$rows = array_filter($rows, function($key) {
			if (!is_int($key))
				return $key;
		}, ARRAY_FILTER_USE_KEY);

		return $rows;
	}

	/*
	 * Función que ejecuta una consulta sin preparar.
	 */

	public function masterEspecial($query)
	{
		$sql = parent::prepare($query);
		return $this->queryGet($sql);
	}

	/*
	 * Función que ejecuta una consulta de tipo SELECT y retorna organizado el resultado.
	 */

	public function queryGet($statement)
	{
		if ($statement)
		{
			$resp = $statement->execute();
			$rows = $statement->fetchAll();

			if ($resp != 1)
			{
				throw new PDOException();
			}
			else if (count($rows) > 0)
			{
				$numRows = count($rows);
				$rows = $this->organizar($rows);
				$rows['numRows'] = $numRows;
			}
			else
			{
				$rows['numRows'] = 0;
			}

			return $rows;
		}
		else
		{
			throw new PDOException();
		}
	}

	/*
	 * Función que ejecuta una consulta de tipo INSERT, UPDATE, DELETE y retorna el id de los registros afectados.
	 */

	public function querySet($statement, $commit = false)
	{
		if ($statement)
		{
			$statement->bindParam(':id', $id, PDO::PARAM_INT, 8);
			$resp = $statement->execute();
			if (!$resp)
			{
				parent::rollBack();
				throw new PDOException();
			}
			else
			{
				$datos = (int) $id;

				if ($commit)
				{
					parent::commit();
				}
			}

			return $datos;
		}
		else
		{
			parent::rollBack();
			throw new PDOException();
		}
	}

}
