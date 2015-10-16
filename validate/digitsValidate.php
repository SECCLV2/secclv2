<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Esta clase se encargara de realizar todas las validaciones de digitos del aplicativo. 
 * ---------------------------------------------------------------------------------------------------------------------
 */

class Digits {

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
		if (!preg_match("/^[0-9]{1,}$/", $data) && !is_int($data))
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
