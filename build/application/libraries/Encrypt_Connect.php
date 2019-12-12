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
		$this->logMessage->msg = $response->msg;
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
		$uploadBulk = $this->CI->config->item('upload_bulk').'/'.'bulk/';
		$respUpload = new stdClass;
		$respUpload->rc = 0;

		log_message('INFO', 'NOVO UPLOAD FILE BY: '.$urlBulkService.' AND: '.$userpassBulk);

		$ch = curl_init();
		$fp = fopen($uploadBulk.$file, 'r');
		curl_setopt($ch, CURLOPT_URL, $urlBulkService.$file);
		curl_setopt($ch, CURLOPT_USERPWD, $userpassBulk);
		curl_setopt($ch, CURLOPT_UPLOAD, 1);
		curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_SFTP);
		curl_setopt($ch, CURLOPT_INFILE, $fp);
		curl_setopt($ch, CURLOPT_INFILESIZE, filesize($uploadBulk.$file));
		curl_exec ($ch);
		$error = curl_errno($ch);

		if($error =! 0) {
			log_message('ERROR','NOVO UPLOAD FILE BULK SFTP '.$error.'/ '.lang('RESP_UPLOAD_SFTP('.$error.')'));
			$respUpload->rc = -65;
			$respUpload->msg = 'No fue posible mover el archivo, por favor vuelve a intentarlo';
		}

		curl_close ($ch);
		unlink($uploadBulk.$file);

		return $respUpload;
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
		$rc = $logMessage->rc;
		$msg = $logMessage->msg;
		$response = isset($logMessage->response) ? $logMessage->response : '';
		$country = $logMessage->pais;
		log_message('DEBUG', 'NOVO ['.$userName.'] RESPONSE '.$model.'= rc: '.$rc.', msg: '.$msg.', country: '.$country);

		if(RESPONSE_SERV_COMPLETE) {
			log_message('DEBUG', 'NOVO ['.$userName.'] COMPLETE RESPONSE '.$model.': '.json_encode($response, JSON_UNESCAPED_UNICODE));
		}
	}
}
