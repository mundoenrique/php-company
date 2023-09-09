<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * CodeIgniter XML Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/xml_helper.html
 */

// ------------------------------------------------------------------------

if (!function_exists('np_hoplite_Encryption')) {
	/**
	 * Encripta el texto seleccionado con el algoritmo DES
	 * @param  string $data
	 * @return string
	 */
	function np_Hoplite_Encryption($data, $service = false)
	{
		if (ENVIRONMENT === 'development') {
			error_reporting(E_ALL & ~E_DEPRECATED);
		}

		$CI = &get_instance();
		if ($service) {
			$userName = $CI->session->userdata('userName') != '' ? $CI->session->userdata('userName') : 'NO USERNAME';
			log_message('DEBUG', '[' . $userName . '] REQUEST ' . $service . ': ' . $data);
		}

		$dataB = base64_encode($data);
		$iv = "\0\0\0\0\0\0\0\0";
		while ((strlen($dataB) % 8) != 0) {
			$dataB .= " ";
		}
		$cryptData = mcrypt_encrypt(
			MCRYPT_DES,
			base64_decode(WS_KEY),
			$dataB,
			MCRYPT_MODE_CBC,
			$iv
		);
		return base64_encode($cryptData);
	}
}

if (!function_exists('np_hoplite_Decrypt')) {
	/**
	 * Desencripta el texto seleccionado con el algoritmo DES
	 * @param  string $cryptDataBase64
	 * @return string
	 */
	function np_Hoplite_Decrypt($cryptDataBase64, $service = false)
	{
		if (ENVIRONMENT === 'development') {
			error_reporting(E_ALL & ~E_DEPRECATED);
		}

		$CI = &get_instance();
		$data = base64_decode($cryptDataBase64);
		$iv = "\0\0\0\0\0\0\0\0";
		$descryptData = mcrypt_decrypt(
			MCRYPT_DES,
			base64_decode(WS_KEY),
			$data,
			MCRYPT_MODE_CBC,
			$iv
		);
		$decryptData = base64_decode(trim($descryptData));
		$response = json_decode($decryptData);

		if ($service) {
			$rc = isset($response->rc) ? ' RC: ' . $response->rc : '';
			$msg = isset($response->msg) ? ' MSG: ' . $response->msg : '';
			$country = isset($response->pais) ? ' COUNTRY: ' . $response->pais : '';
			$userName = $CI->session->userdata('userName') != '' ? $CI->session->userdata('userName') : 'NO USERNAME';

			log_message('DEBUG', '[' . $userName . '] RESPONSE: ' . $service . $rc . $msg . $country);
		}
		return $decryptData;
	}
}
