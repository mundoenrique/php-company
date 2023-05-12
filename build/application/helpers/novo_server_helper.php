<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * NOVOPAYMENT server Helpers
 *
 * @subpackage	Helpers
 * @category		Helpers
 * @author			J. Enrique Peñaloza P
 * @date				September 19th, 2022
 */

if (!function_exists('writeLog')) {
	function writeLog($level, $message) {
		$CI =& get_instance();
		$appUserName = $CI->session->appUserName ?? date('U') . '-';
		$customer = '';
		$ip = $CI->input->ip_address();
		$level = mb_strtoupper($level);

		if ($level !== 'INFO') {
			if (!$CI->session->has_userdata('appUserName')) {
				$CI->session->set_userdata('appUserName', $appUserName);
			}

			if ($CI->session->has_userdata('customerSess')) {
				$customer = $CI->session->customerSess;
			} elseif ($CI->config->item('customer') !== NULL) {
				$customer = $CI->config->item('customer');
			}

			if ($customer === '') {
				$message = novoLang('NOVO [%s] IP: %s, %s', [$appUserName, $ip, $message]);
			} else {
				$message = novoLang('NOVO [%s] IP: %s, CUSTOMER: %s, %s', [$appUserName, $ip, $customer, $message]);
			}
		} else {
			$message = novoLang('NOVO %s', $message);
		}

		log_message($level, $message);
	}
}

// generar uuIdV4
if (!function_exists('uuIdV4Generate')) {
	function uuIdV4Generate() {
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}
}

// Maneja respuesta del servicio
if (!function_exists('responseServer')) {
	function responseServer($responseServer) {
		$code = explode('.', $responseServer->code);

		switch ($responseServer->HttpCode) {
			case 200:
				$responseServer->responseCode = 0;
				break;

			case 502:
				$responseServer->responseCode = 502;
				break;

			case 504:
				$responseServer->responseCode = 504;
				break;

			default:
				$responseServer->responseCode = $code[2] ?? $code[0];
		}

		if ($responseServer->errorNo === 28) {
			$responseServer->responseCode = 504;
		}

		return $responseServer;
	}
}

if (!function_exists('handleResponseServer')) {
	function handleResponseServer($webServiceResp) {
		if (isset($webServiceResp->data->rc)) {
			$webServiceResp->responseCode = $webServiceResp->data->rc;
		}

		if (isset($webServiceResp->data->logAcceso)) {
			$accessLog = json_decode($webServiceResp->data->logAcceso);

			if (gettype($accessLog) === 'object') {
				$webServiceResp->data->logAcceso = $accessLog;
			}
		}

		if (isset($webServiceResp->data->archivo)) {
			$webServiceResp->binaryFile = 'success';

			if (!is_array($webServiceResp->data->archivo)  || empty($webServiceResp->data->archivo)) {
				$webServiceResp->binaryFile = 'No binary array';
			}
		}

		if (isset($webServiceResp->data->bean)) {
			$bean = json_decode($webServiceResp->data->bean);

			if (gettype($bean) === 'object' || gettype($bean) === 'array') {
				$webServiceResp->data->bean = $bean;
			}

			if (isset($webServiceResp->data->bean->archivo)) {
				$webServiceResp->binaryFile = 'success';

				if (!is_array($webServiceResp->data->bean->archivo) || empty($webServiceResp->data->bean->archivo)) {
					$webServiceResp->binaryFile = 'No binary array';
				}
			}
		}

		return $webServiceResp;
	}
}
