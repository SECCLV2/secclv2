<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Esta clase se encargara de realizar todas las validaciones alfanumericas del aplicativo. 
 * ---------------------------------------------------------------------------------------------------------------------
 */

class Alphanumeric {

	public function __construct($data, $type = null, $message = 'standar')
	{
		switch ($type)
		{
			default:
				$result = $this->standard($data);
				break;
		}

		return $result;
	}

	private function standard($data)
	{
		if (!preg_match("/^[A-Za-z0-9]{1,}$/", $data))
		{
			if ($message = 'standar')
				return 'Este campo solo debe contener letras, números y espacios';
			else
				return $message;
		}
		else
		{
			return true;
		}
	}

}
