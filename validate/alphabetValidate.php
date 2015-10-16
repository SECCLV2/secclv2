<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Esta clase se encargara de realizar todas las validaciones alfabeticas del aplicativo. 
 * ---------------------------------------------------------------------------------------------------------------------
 */

class Alphabet {

	public function __construct($data, $language = 1, $message = 'standar')
	{
		switch ($language)
		{
			case 1:
				$result = $this->español($data, $message);
				break;
		}

		return $result;
	}

	private function español($data, $message)
	{
		if (!preg_match("/^[A-ZÁÉÍÓÚÜÑa-záéíóúüñ ]{1,}$/", $data))
		{
			if ($message = 'standar')
				return 'Este campo solo debe contener letras y espacios';
			else
				return $message;
		}
		else
		{
			return true;
		}
	}

}
