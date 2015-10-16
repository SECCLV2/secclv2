<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Esta clase se encargara de realizar todas las validaciones de campos de texto que requieran mas caracteres que 
 * los alfabeticos en el aplicativo. 
 * ---------------------------------------------------------------------------------------------------------------------
 */

class Text {

	public function __construct($data, $format = null, $message = 'standar')
	{
		switch ($format)
		{
			default:
				$result = $this->standard($data, $message = 'standar');
				break;
		}

		return $result;
	}

	private function standard($data, $message = 'standar')
	{
		if (!preg_match("/^[A-ZÁÉÍÓÚÜÑa-záéíóúüñ0-9!@#\$%\^&\*\?¿\-_~\/\.:,;\(\)\[\]\{\}\"']{1,}$/", $data))
		{
			if ($message = 'standar')
				return 'Este campo tiene caracteres invalidos';
			else
				return $message;
		}
		else
		{
			return true;
		}
	}

}
