<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Novopayment cryptography Helpers
 *
 * @package CodeIgniter
 * @subpackage Helpers
 * @category Helpers
 * @author desarrolloweb@novopayment.com
 * @date October 1st, 2022
 */
if (!function_exists('decryptData')) {
	/**
	 * @info decrypt data
	 * @author epenaloza
	 * @date October 3rd, 2022
	 * @param json $requestData data to decrypt
	 * @return object|array|string data decrypted
	 */
	function decryptData($requestData)
	{
		$CI = &get_instance();

		if (ACTIVE_SAFETY) {
			$req = json_decode(base64_decode($requestData));
			$requestData = $CI->cryptography->decrypt(base64_decode($req->plot), utf8_encode($req->data));
		} else {
			$requestData = utf8_encode($requestData);
		}

		return $CI->security->xss_clean(strip_tags($requestData));
	}
}

if (!function_exists('encryptData')) {
	/**
	 * @info decrypt data
	 * @author epenaloza
	 * @date October 3rd, 2022
	 * @param object|array|string $responseData data to encrypt
	 * @return json data encrypted
	 */
	function encryptData($responseData)
	{
		$CI = &get_instance();
		$responseData = [
			'response' => $responseData
		];

		if (ACTIVE_SAFETY) {
			$responseData['response']->novoName = $CI->security->get_csrf_token_name();
			$responseData['response']->novoValue = $CI->security->get_csrf_hash();
			$responseData['response'] = base64_encode(
				json_encode($CI->cryptography->encrypt($responseData['response']), JSON_UNESCAPED_UNICODE)
			);
		}

		return json_encode($responseData, JSON_UNESCAPED_UNICODE);
	}
}
