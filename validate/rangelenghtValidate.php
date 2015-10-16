<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Esta clase se encargara de realizar todas las validaciones de un rango de caracteres en el aplicativo. 
 * ---------------------------------------------------------------------------------------------------------------------
 */

class Rangelenght {

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
		if (strlen($data) < $min || strlen($data) > $max)
		{
			if ($message = 'standar')
				return "Este campo solo puede contener un rango de caracteres entre $min y $max";
			else
				return $message;
		}
		else
		{
			return true;
		}
	}

}
