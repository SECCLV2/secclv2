<?php

/*
 * -----------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Este modelo se encargara de traer los datos de todas las listas 
 * desplegables de los formularios.
 * -----------------------------------------------------------------------------
 */

class paginacionModel extends Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function count($table, array $filtros = array(), array $extra = array())
	{
		if (!key_exists('condiciones', $extra))
			$extra['condiciones'] = false;
		if (!key_exists('otros', $extra))
			$extra['otros'] = '';

		if ($extra['condiciones'])
		{
			$arrayCondicion = array_keys($extra['condiciones']);
			$condicion = 'WHERE ';
			for ($i = 0; $i < count($arrayCondicion); $i++)
			{
				$condicion .= $arrayCondicion[$i] . ' = '
						. $extra['condiciones'][$arrayCondicion[$i]] . ' AND ';
			}
			$condicion = substr($condicion, 0, -5);
		}
		else
		{
			$condicion = '';
		}

		if (isset($filtros) && !empty($filtros))
		{
			$arrayFiltros = array_keys($filtros);
			if (isset($extra['condiciones']) && !empty($extra['condiciones']))
				$filtro = 'AND ';
			else
				$filtro = 'WHERE ';
			for ($i = 0; $i < count($arrayFiltros); $i++)
			{
				if ($filtros[$arrayFiltros[$i]])
				{
					$filtro .= $arrayFiltros[$i] . ' LIKE '
							. '\'%' . $filtros[$arrayFiltros[$i]] . '%\' AND ';
				}
			}
			$filtro = substr($filtro, 0, -5);
		}
		else
		{
			$filtro = '';
		}

		$count = parent::prepare("SELECT COUNT(*) AS REGISTROS FROM $table "
						. "$condicion "
						. "$filtro "
						. "$extra[otros]");

		if (PRUEBAS_PAGINADOR)
		{
			echo '<pre>';
			echo 'Paginador Count: <br/>';
			print_r($count);
			echo '</pre><hr/>';
		}

		return $this->queryGet($count);
	}

	public function rownumSelect($table, $campos, $count, $numRows, $pagina, array $filtros = array(), array $extra = array())
	{
		if (!key_exists('condiciones', $extra))
			$extra['condiciones'] = '';
		if (!key_exists('otros', $extra))
			$extra['otros'] = '';
		if (!key_exists('orderBy', $extra))
			$extra['orderBy'] = false;
		if (!key_exists('campos', $extra))
			$extra['campos'] = '';
		if (!key_exists('sentido', $extra))
			$extra['sentido'] = '';

		$pagina *= $numRows;
		$numRows = ($pagina - $numRows) + 1;

		if (key_exists('condiciones', $extra) && !empty($extra['condiciones']))
		{
			$arrayCondicion = array_keys($extra['condiciones']);
			$condicion = 'WHERE ';
			for ($i = 0; $i < count($arrayCondicion); $i++)
			{
				$condicion .= $arrayCondicion[$i] . ' = '
						. $extra['condiciones'][$arrayCondicion[$i]] . ' AND ';
			}
			$condicion = substr($condicion, 0, -5);
		}
		else
		{
			$condicion = '';
		}

		if (isset($filtros) && !empty($filtros))
		{
			$arrayFiltros = array_keys($filtros);
			if (isset($extra['condiciones']) && !empty($extra['condiciones']))
				$filtro = 'AND ';
			else
				$filtro = 'WHERE ';

			for ($i = 0; $i < count($arrayFiltros); $i++)
			{
				if ($filtros[$arrayFiltros[$i]])
				{
					$filtro .= $arrayFiltros[$i] . ' LIKE '
							. '\'%' . $filtros[$arrayFiltros[$i]] . '%\' AND ';
				}
			}
			$filtro = substr($filtro, 0, -5);
		}
		else
		{
			$filtro = '';
		}

		if ($extra['orderBy'] && !empty($extra['sentido']) && !empty($extra['campos']))
		{
			strtoupper($extra['sentido']);
			unset($order);

			if ($extra['sentido'] == 'ASC')
			{
				$order[] = "ORDER BY $extra[campos] ASC";
				$order[] = "ORDER BY $extra[campos] DESC";
			}
			else if ($extra['sentido'] == 'DESC')
			{
				$order[] = "ORDER BY $extra[campos] DESC";
				$order[] = "ORDER BY $extra[campos] ASC";
			}
		}

		$sql = parent::prepare('SELECT * FROM '
						. '(SELECT TAB.*,ROWNUM R FROM'
						. "	 (SELECT $campos"
						. "	 FROM $table"
						. "	 $condicion"
						. "  $extra[otros]"
						. "  $filtro"
						. "	 $order[0])TAB)"
						. " WHERE R BETWEEN $numRows AND $pagina");

		if (PRUEBAS_PAGINADOR)
		{
			echo '<pre>';
			echo 'Paginador Select: <br/>';
			print_r($sql);
			echo '</pre><hr/>';
		}

		return $this->queryGet($sql);
	}

}
