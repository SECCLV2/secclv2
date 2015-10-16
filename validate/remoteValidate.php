<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Esta clase se encargara de realizar todas las validaciones que requieran que se realicen verificaciones en la 
 * base de datos. 
 * ---------------------------------------------------------------------------------------------------------------------
 */

class Remote {

	public function __construct($data, $message = 'standar')
	{
		return $result = $this->standard($data);
	}

	private function standard($data, $message)
	{
		if ($data == null)
		{
			if ($message = 'standar')
				return 'Este campo es obligatorio';
			else
				return $message;
		}
		else
		{
			return true;
		}
	}

}
