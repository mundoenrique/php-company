<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Libreria para el cifrtado y descifrado de datos
 * @author J. Enrique Peñaloza Piñero
 */
class Encrypt_Connect {
	private $CI;
	private $userName;
	private $countryConf;
	private $iv;
	private $keyNovo;
	private $logMessage;

	public function __construct()
	{
		log_message('INFO', 'NOVO Encrypt_Connect Library Class Initialized');
		$this->CI = &get_instance();
		$this->keyNovo = $this->CI->config->item('keyNovo');
		$this->iv = "\0\0\0\0\0\0\0\0";
		$this->logMessage = new stdClass();
	}
	/**
	 * @info método para cifrar las petiones al servicio
	 * @author J. Enrique Peñaloza Piñero
	 */
	public function encode($data, $userName, $model) {
		log_message('INFO', 'NOVO Encrypt_Connect: encode Method Initialized');

		if($model !== 'REMOTE_ADDR') {
			$data = json_encode($data, JSON_UNESCAPED_UNICODE);
		}
		log_message('DEBUG', 'NOVO ['.$userName.'] REQUEST '.$model.': '.$data);

		$dataB = base64_encode($data);
		while((strlen($dataB)%8) != 0) {
			$dataB .= " ";
		}

		$cryptData = mcrypt_encrypt(
			MCRYPT_DES, $this->keyNovo, $dataB, MCRYPT_MODE_CBC, $this->iv
		);

		return base64_encode($cryptData);
	}
	/**
	 * @info método para descifrar las respuesta al servicio
	 * @author J. Enrique Peñaloza Piñero
	 */
	public function decode($cryptData, $userName, $model)
	{
		log_message('INFO', 'NOVO Encrypt_Connect: decode Method Initialized');
		$data = base64_decode($cryptData);
		$descryptData = mcrypt_decrypt(
			MCRYPT_DES, $this->keyNovo, $data, MCRYPT_MODE_CBC, $this->iv
		);
		$decryptData = base64_decode(trim($descryptData));
		$response = json_decode($decryptData);

		$rc = isset($response->rc) ? 'RC '.$response->rc : lang('RESP_RC_DEFAULT');
		$msg = isset($response->msg) ? 'MSG '.$response->msg : lang('RESP_MESSAGE_SYSTEM');
		$country = isset($response->pais) ? 'COUNTRY '.$response->pais : $this->CI->config->item('country');

		if(!$response) {
			log_message('DEBUG', 'NOVO ['.$userName.'] Sin respuesta del servicio');
			$response = new stdClass();
			$response->rc = lang('RESP_RC_DEFAULT');
			$response->msg = lang('RESP_MESSAGE_SYSTEM');
		}
		if(!isset($response->pais)) {
			log_message('DEBUG', 'NOVO ['.$userName.'] Insertando pais al RESPONSE');
			$response->pais = $this->CI->config->item('country');
		}

		$this->logMessage = $response;
		$this->logMessage->model = $model;
		$this->logMessage->userName = $userName;
		$this->writeLog($this->logMessage);

		return $response;

	}
	/**
	 * @info método para realizar la petición al servicio
	 * @author J. Enrique Peñaloza Piñero
	 * @date May 13th, 2019
	 */
	public function connectWs($request, $userName, $model)
	{
		log_message('INFO', 'NOVO Encrypt_Connect: connectWs Method Initialized');
		$failResponse = new stdClass();
		$fail = FALSE;
		$urlWS = $this->CI->config->item('urlWS').'eolwebInterfaceWS';

		log_message('DEBUG', 'NOVO ['.$userName.'] REQUEST BY COUNTRY: '.$request['pais'].', AND WEBSERVICE URL: '.$urlWS);

		$request = json_encode($request);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $urlWS);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 59);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: text/plain',
			'Content-Length: ' . strlen($request))
		);
		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		log_message('DEBUG','NOVO ['.$userName.'] RESPONSE CURL HTTP CODE: ' . $httpCode);

		$failResponse = json_decode($response);
		if(is_object($failResponse)) {
			$response = $failResponse;
			$fail = TRUE;
		}
		if(!$response) {
			$failResponse->rc = 'RC Def '.lang('RESP_RC_DEFAULT');
			$failResponse->msg = 'MSG Def '.lang('RESP_MESSAGE_SYSTEM');
			$response = $failResponse;
			$fail = TRUE;
		}
		if($fail) {
			$this->logMessage = $failResponse;
			$this->logMessage->userName = $userName;
			$this->logMessage->model = $model;
			$this->logMessage->pais = $this->CI->config->item('country');
			$this->writeLog($this->logMessage);
		}


		return $response;
	}
	/**
	 * @info Método para es cribir el log de la respuesta del servicio
	 * @author J. Enrique Peñaloza Piñero
	 * @date October 25th, 2019
	 */
	private function writeLog($logMessage)
	{
		$userName = $logMessage->userName;
		$model = $logMessage->model;
		$msg = $logMessage->msg;
		$rc = $logMessage->rc;
		$country = $logMessage->pais;
		log_message('DEBUG', 'NOVO ['.$userName.'] RESPONSE '.$model.'= rc: '.$rc.', msg: '.$msg.', country'.$country);
	}
}
