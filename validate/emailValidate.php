<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Esta clase se encargara de realizar todas las validaciones de emails en el aplicativo. 
 * ---------------------------------------------------------------------------------------------------------------------
 */

class Email {

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
		if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $data) &&
				!filter_var($data, FILTER_VALIDATE_EMAIL))
		{
			if ($message = 'standar')
				return 'Dirección de e-mail incorrecta';
			else
				return $message;
		}
		else
		{
			return true;
		}
	}

}
