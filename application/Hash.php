<?php

/*
 * ---------------------------------------------------------------------------------------------------------------------
 * Creador: David Alejandro Domínguez Rivera.
 * Uso: esta clase aloja las funciones de cifrado de que seran usadas en el aplicativo.
 * ---------------------------------------------------------------------------------------------------------------------
 */

class Hash {
	
	/*
	 * Función que se encarga de cifrar las contraseñas del aplicativo.
	 */
	public static function getHash($data)
	{
		$data .= HASH_KEY;

		$crypt_options = array(
			'cost' => 10,
			'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM)
		);
		return password_hash($data, PASSWORD_BCRYPT, $crypt_options);
	}
	
	/*
	 * Función que se encarga de verificar que las contraseñas coincidan.
	 */
	public static function verificarPassword($password, $password_hash)
	{
		$password_entered = $password . HASH_KEY;
		return password_verify($password_entered, $password_hash);
	}
	
	/*
	 * Función encargada de cifrar las url con un sistema de cifrado de doble sentido.
	 */
	public static function urlEncrypt($url, $clave = HASH_KEY)
	{
		$url .= 'encrypt';
		$algoritmo = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
		$vector = mcrypt_create_iv(mcrypt_enc_get_iv_size($algoritmo), MCRYPT_DEV_URANDOM);
		
		mcrypt_generic_init($algoritmo, $clave, $vector);
		
		$encrypted_data_bin = mcrypt_generic($algoritmo, $url);
		
		mcrypt_generic_deinit($algoritmo);
		mcrypt_module_close($algoritmo);
		
		$encrypted = bin2hex($vector) . bin2hex($encrypted_data_bin);
		
		return $encrypted;
	}
	
	/*
	 * Función encargada de decifrar las url con un sistema de cifrado de doble sentido.
	 */
	public static function urlDecrypt($encrypted, $clave = HASH_KEY)
	{
		$algoritmo = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
		$vector_size_hex = mcrypt_enc_get_iv_size($algoritmo) * 2;
		$vector = pack("H*", substr($encrypted, 0, $vector_size_hex));
		$encrypted_data_bin = pack("H*", substr($encrypted, $vector_size_hex));
		
		mcrypt_generic_init($algoritmo, $clave, $vector);
		
		$decrypted = mdecrypt_generic($algoritmo, $encrypted_data_bin);
		
		mcrypt_generic_deinit($algoritmo);
		mcrypt_module_close($algoritmo);
		
		return $decrypted;
	}

}
