<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Esta clase se encargara de realizar todas las validaciones numericas del aplicativo. 
 * ---------------------------------------------------------------------------------------------------------------------
 */

class Number {

	public function __construct($data, $format = null, $message = 'standar')
	{
		switch ($format)
		{
			default:
				$result = $this->standard($data);
				break;
		}

		return $result;
	}

	private function standard($data)
	{
		if (!preg_match("/^\d{1,}\.\d{1,2]$/", $data) && !is_int($data))
		{
			if ($message = 'standar')
				return 'Este campo solo puede contener digitos';
			else
				return $message;
		}
		else
		{
			return true;
		}
	}

}
