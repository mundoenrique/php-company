<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @info Libreria para el cifrtado y descifrado de request y response con el cliente
 * @author J. Enrique Peñaloza Piñero
 */
class Cryptography
{
	public function __construct()
	{
		writeLog('INFO', 'Cryptography Library Class Initialized');
	}

	public function encrypt($object)
	{
		writeLog('INFO', 'Cryptography: Encrypt Method Initialized');

		$keyStr = $this->generateKey();
		$salt = openssl_random_pseudo_bytes(8);
		$salted = '';
		$dx = '';
		while (strlen($salted) < 48) {
			$dx = md5($dx . $keyStr . $salt, true);
			$salted .= $dx;
		}
		$key = substr($salted, 0, 32);
		$iv  = substr($salted, 32, 16);
		$encrypted_data = openssl_encrypt(json_encode($object, JSON_UNESCAPED_UNICODE), 'aes-256-cbc', $key, true, $iv);
		$data = [
			"res" => base64_encode($encrypted_data),
			"str" => bin2hex($iv),
			"env" => bin2hex($salt)
		];

		$response = [
			'plot' => $keyStr,
			'code' => urlencode(base64_encode(json_encode($data, JSON_UNESCAPED_UNICODE)))
		];

		return $response;
	}

	public function decrypt($passphrase, $jsonString)
	{
		$jsondata = json_decode(base64_decode(urldecode($jsonString)), true);
		try {
			$salt = hex2bin($jsondata["str"]);
			$iv  = hex2bin($jsondata["env"]);
		} catch (Exception $e) {
			return null;
		}
		$ct = base64_decode($jsondata["req"]);
		$concatedPassphrase = $passphrase . $salt;
		$md5 = array();
		$md5[0] = md5($concatedPassphrase, true);
		$result = $md5[0];
		for ($i = 1; $i < 3; $i++) {
			$md5[$i] = md5($md5[$i - 1] . $concatedPassphrase, true);
			$result .= $md5[$i];
		}
		$key = substr($result, 0, 32);
		$data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);

		return $data;
	}

	private function generateKey()
	{
		$length = 32;
		$CypherBaseLength = strlen(CYPHER_BASE);
		$randomString = '';

		for ($i = 0; $i < $length; $i++) {
			$randomString .= CYPHER_BASE[rand(0, $CypherBaseLength - 1)];
		}

		return $randomString;
	}
	/**
	 * @info Método encargado de preparar un dato para su desencriptado
	 * @author Pedro Torres
	 * @date Septiembre 25th, 2020
	 */
	public function decryptOnlyOneData($data)
	{
		if (ACTIVE_SAFETY) {
			$data = json_decode(base64_decode($data));
			$data = $this->decrypt(
				base64_decode($data->plot),
				utf8_encode($data->password)
			);
		}

		return $data;
	}
}
