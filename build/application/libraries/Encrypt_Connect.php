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

		if (ENVIRONMENT == 'development') {
			error_reporting(E_ALL & ~E_DEPRECATED);
		}
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

		if(!$response) {
			log_message('ERROR', 'NOVO ['.$userName.'] NO SERVICE RESPONSE');
			$response = new stdClass();
			$response->rc = lang('RESP_RC_DEFAULT');
			$response->msg = lang('RESP_MESSAGE_SYSTEM');
		}

		if(!isset($response->pais)) {
			log_message('INFO', 'NOVO ['.$userName.'] INSERTING COUNTRY TO THE RESPONSE');
			$response->pais = $this->CI->config->item('country');
		}

		$this->logMessage->rc = $response->rc;
		$this->logMessage->msg = isset($response->msg) ? $response->msg : 'Sin mensaje del servicio';
		$this->logMessage->response = $response;
		$this->logMessage->pais = $this->CI->config->item('country');
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

		$fail = FALSE;
		$subFix = '_'.strtoupper($this->CI->config->item('country-uri'));

		if(isset($_SERVER['WS_URL'.$subFix])) {
			$this->CI->config->set_item('urlWS', $_SERVER['WS_URL'.$subFix]);
		}

		$urlWS = $this->CI->config->item('urlWS').'eolwebInterfaceWS';

		log_message('DEBUG', 'NOVO ['.$userName.'] REQUEST BY COUNTRY: '.$request['pais'].', AND WEBSERVICE URL: '.$urlWS);

		$requestSerV = json_encode($request, JSON_UNESCAPED_UNICODE);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $urlWS);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 59);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $requestSerV);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: text/plain',
			'Content-Length: ' . strlen($requestSerV))
		);
		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$CurlError = curl_error($ch);
		curl_close($ch);

		log_message('DEBUG','NOVO ['.$userName.'] RESPONSE CURL HTTP CODE: ' . $httpCode);

		$failResponse = json_decode($response);

		if(is_object($failResponse)) {
			$response = $failResponse;
			$fail = TRUE;
		}

		if($httpCode != 200 || !$response) {
			log_message('ERROR','NOVO ['.$userName.'] ERROR CURL: '.json_encode($CurlError, JSON_UNESCAPED_UNICODE));
			$failResponse = new stdClass();
			$failResponse->rc = lang('RESP_RC_DEFAULT');
			$failResponse->msg = lang('RESP_MESSAGE_SYSTEM');
			$response = $failResponse;
			$fail = TRUE;
		}

		if($fail) {
			$this->logMessage = $failResponse;
			$this->logMessage->userName = $userName;
			$this->logMessage->model = $model;
			$this->logMessage->pais = $request['pais'];
			$this->writeLog($this->logMessage);
		}

		return $response;
	}
	/**
	 * @info método para enviar archivos al servidor de backend
	 * @author J. Enrique Peñaloza Piñero
	 * @date December113th, 2019
	 */
	public function moveFile($file, $userName, $model)
	{
		log_message('INFO', 'NOVO Encrypt_Connect: moveFile Method Initialized');

		$urlBulkService = $this->CI->config->item('url_bulk_service');
		$userpassBulk = $this->CI->config->item('userpass_bulk');
		$uploadBulk = $this->CI->config->item('upload_bulk');
		$respUpload = new stdClass;
		$respUpload->rc = 0;

		log_message('INFO', 'NOVO UPLOAD FILE BY: '.$urlBulkService.' AND: '.$userpassBulk);

		$ch = curl_init();
		$Fclose = $fOpen = fopen($uploadBulk.$file, 'r');
		curl_setopt($ch, CURLOPT_URL, $urlBulkService.$file);
		curl_setopt($ch, CURLOPT_USERPWD, $userpassBulk);
		curl_setopt($ch, CURLOPT_UPLOAD, 1);
		curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_SFTP);
		curl_setopt($ch, CURLOPT_INFILE, $fOpen);
		curl_setopt($ch, CURLOPT_INFILESIZE, filesize($uploadBulk.$file));
		curl_exec ($ch);
		$result = curl_errno($ch);

		log_message('DEBUG','NOVO ['.$userName.'] UPLOAD FILE BULK SFTP '.$model.': '.$result.' '.lang('RESP_UPLOAD_SFTP('.$result.')'));

		if($result != 0) {
			$respUpload->rc = -105;
		}

		curl_close ($ch);
		fclose($Fclose);
		unlink($uploadBulk.$file);

		return $respUpload;
	}
	/**
	 * @info Método para escribir el log de la respuesta del servicio
	 * @author J. Enrique Peñaloza Piñero
	 * @date October 25th, 2019
	 */
	private function writeLog($logMessage)
	{
		$userName = $logMessage->userName;
		$model = $logMessage->model;
		$rc = $logMessage->rc;
		$msg = $logMessage->msg;
		$response = isset($logMessage->response) ? $logMessage->response : '';
		$country = $logMessage->pais;
		log_message('DEBUG', 'NOVO ['.$userName.'] RESPONSE '.$model.'= rc: '.$rc.', msg: '.$msg.', country: '.$country);

		if(RESPONSE_SERV_COMPLETE) {
			$wirteLog = new stdClass();
			$isBean = '';

			if (isset($response->bean) && is_object($response->bean)) {
				$isBean = 'IN BEAN ';
				$response->bean = gettype($response->bean) != 'object'  ? json_decode($response->bean) : $response->bean;
			}

			if(is_object($response)) {
				foreach ($response AS $pos => $responseAttr) {
					if($pos == 'archivo') {
						$wirteLog->archivo = 'OK';
						if(!is_array($responseAttr)) {
							$wirteLog->archivo = 'Sin arreglo binario';
						}
						continue;
					}
					$wirteLog->$pos = $responseAttr;
				}
			}

			log_message('DEBUG', 'NOVO ['.$userName.'] COMPLETE RESPONSE '.$isBean.$model.': '.json_encode($wirteLog, JSON_UNESCAPED_UNICODE));
		}
	}
}
