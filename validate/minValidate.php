<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Esta clase se encargara de realizar todas las validaciones de campos que tengan menos de determinado valor en el 
 * aplicativo.
 * ---------------------------------------------------------------------------------------------------------------------
 */

class Min {

	public function __construct($data, $number, $format = null, $message = 'standar')
	{
		switch ($format)
		{
			default:
				$result = $this->standard($data, $number, $message = 'standar');
				break;
		}

		return $result;
	}

	private function standard($data, $number, $message = 'standar')
	{
		if ($data < $number)
		{
			if ($message = 'standar')
				return "Este campo solo puede contener un valor minimo de $number";
			else
				return $message;
		}
		else
		{
			return true;
		}
	}

}
