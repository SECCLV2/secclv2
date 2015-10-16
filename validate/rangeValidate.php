<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Esta clase se encargara de realizar todas las validaciones de campos que tengan un rango de valores en el 
 * aplicativo.
 * ---------------------------------------------------------------------------------------------------------------------
 */

class Range {

	public function __construct($data, $min, $max, $format = null, $message = 'standar')
	{
		switch ($format)
		{
			default:
				$result = $this->standard($data, $min, $max, $message = 'standar');
				break;
		}

		return $result;
	}

	private function standard($data, $min, $max, $message = 'standar')
	{
		if ($data < $min || $data > $max)
		{
			if ($message = 'standar')
				return "Este campo solo puede contener un valor minimo de $min y un valor maximo de $max";
			else
				return $message;
		}
		else
		{
			return true;
		}
	}

}
