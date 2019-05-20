<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Libreria para el cifrtado y descifrado de request y response con el cliente
 * @author J. Enrique Peñaloza Piñero
 */
class Cryptography {
	private $CI;
	private $CypherBase;
	private $keyPhrase;

	public function __construct()
	{
		log_message('INFO', 'NOVO Cryptography Library Class Initialized');
		$this->CI = &get_instance();
		$this->CypherBase = $this->CI->config->item('cypher_base');
	}

	public function encrypt($object)
	{
		log_message('INFO', 'NOVO Cryptography: Encrypt Method Initialized');
		$keyStr = $this->generateKey();
		$salt = openssl_random_pseudo_bytes(8);
    $salted = '';
    $dx = '';
    while (strlen($salted) < 48) {
        $dx = md5($dx.$keyStr.$salt, true);
        $salted .= $dx;
    }
    $key = substr($salted, 0, 32);
    $iv  = substr($salted, 32,16);
    $encrypted_data = openssl_encrypt(json_encode($object), 'aes-256-cbc', $key, true, $iv);
		$data = [
			"ct" => base64_encode($encrypted_data),
			"iv" => bin2hex($iv),
			"s" => bin2hex($salt),
		];

		$response = [
			'plot' => $keyStr,
			'code' => json_encode($data)
		];

    return $response;
	}

	public function decrypt($passphrase, $jsonString)
	{
		$jsondata = json_decode($jsonString, true);
    try {
        $salt = hex2bin($jsondata["s"]);
        $iv  = hex2bin($jsondata["iv"]);
    } catch(Exception $e) { return null; }
    $ct = base64_decode($jsondata["ct"]);
    $concatedPassphrase = $passphrase.$salt;
    $md5 = array();
    $md5[0] = md5($concatedPassphrase, true);
    $result = $md5[0];
    for ($i = 1; $i < 3; $i++) {
        $md5[$i] = md5($md5[$i - 1].$concatedPassphrase, true);
        $result .= $md5[$i];
    }
    $key = substr($result, 0, 32);
		$data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);
		log_message('INFO', 'NOVO decrip---------------------------------------------'.$data);
    return $data;
	}

	private function generateKey()
	{
		$length = 32;
    $CypherBaseLength = strlen($this->CypherBase);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
			$randomString .= $this->CypherBase[rand(0, $CypherBaseLength - 1)];
    }
    return $randomString;
	}
}
