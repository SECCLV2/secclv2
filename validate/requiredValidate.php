<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Esta clase se encargara de realizar todas las validaciones de campos requeridos en el aplicativo. 
 * ---------------------------------------------------------------------------------------------------------------------
 */

class Required {

	public function __construct($data, $message = 'standar')
	{
		return $result = $this->standard($data);;
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
