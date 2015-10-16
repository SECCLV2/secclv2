<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Esta clase se encargara de realizar todas las validaciones de minimo numero de caracteres en el aplicativo. 
 * ---------------------------------------------------------------------------------------------------------------------
 */

class Minlenght {

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
		if (strlen($data) < $number)
		{
			if ($message = 'standar')
				return "Este campo solo puede contener un minimo de $number caracteres";
			else
				return $message;
		}
		else
		{
			return true;
		}
	}

}
