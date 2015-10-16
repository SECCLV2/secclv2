<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: Esta clase se encargara de realizar todas las validaciones que requieran que se comparen 2 campos o mas en el 
 * aplicativo. 
 * ---------------------------------------------------------------------------------------------------------------------
 */

class EqualTo {

	public function __construct($data, array $compare, $format = null, $message = 'standar')
	{
		switch ($format)
		{
			default:
				$result = $this->standard($data, $compare, $menssage);
				break;
		}

		return $result;
	}

	private function standard($data, $compare, $menssage)
	{
		foreach ($compare as $key => $value)
		{
			if ($data != $value)
			{
				if ($message = 'standar')
					return 'Los campos no coinsiden';
				else
					return $message;
			}
			else
			{
				return true;
			}
		}
	}

}
