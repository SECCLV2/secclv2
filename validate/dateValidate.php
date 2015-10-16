<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Esta clase se encargara de realizar todas las validaciones de fecha del aplicativo. 
 * ---------------------------------------------------------------------------------------------------------------------
 */

class Date {

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
		if (!preg_match("/^(?:(?:0?[1-9]|1\d|2[0-8])(\/|-)(?:0?[1-9]|1[0-2]))(\/|-)"
						. "(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(?:(?:31(\/|-)"
						. "(?:0?[13578]|1[02]))|(?:(?:29|30)(\/|-)(?:0?[1,3-9]|1[0-2])))(\/|-)"
						. "(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(29(\/|-)0?2)"
						. "(\/|-)(?:(?:0[48]00|[13579][26]00|[2468][048]00)|(?:\d\d)?(?:0[48]|"
						. "[2468][048]|[13579][26]))$/", $data))
		{
			if ($message = 'standar')
				return 'La fecha ingresada es invalida';
			else
				return $message;
		}
		else
		{
			return true;
		}
	}

}
