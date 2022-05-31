<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Libreria para el cifrtado y descifrado de datos
 * @author J. Enrique Peñaloza Piñero
 */
class Encrypt_Connect {
	private $CI;
	private $userName;
	private $iv;
	private $logMessage;

	public function __construct()
	{
		log_message('INFO', 'NOVO Encrypt_Connect Library Class Initialized');

		$this->CI = &get_instance();
		$this->iv = "\0\0\0\0\0\0\0\0";
		$this->logMessage = new stdClass();

		if (ENVIRONMENT == 'development') {
			error_reporting(~E_DEPRECATED);
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

		log_message('DEBUG', 'NOVO ['.$userName.'] IP ' . $this->CI->input->ip_address() . ' REQUEST ' . $model . ': ' . $data);

		$dataB = base64_encode($data);
		while((strlen($dataB)%8) != 0) {
			$dataB .= " ";
		}

		$cryptData = mcrypt_encrypt(
			MCRYPT_DES, base64_decode(WS_KEY), $dataB, MCRYPT_MODE_CBC, $this->iv
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
			MCRYPT_DES, base64_decode(WS_KEY), $data, MCRYPT_MODE_CBC, $this->iv
		);
		$decryptData = base64_decode(trim($descryptData));
		$response = json_decode($decryptData);

		if(!$response) {
			log_message('ERROR', 'NOVO ['.$userName.'] NO SERVICE RESPONSE');
			$response = new stdClass();
			$response->rc = lang('CONF_RC_DEFAULT');
			$response->msg = lang('GEN_SYSTEM_MESSAGE');
		}

		if(!isset($response->pais)) {
			log_message('INFO', 'NOVO ['.$userName.'] INSERTING CUSTOMER TO THE RESPONSE');
			$response->pais = $this->CI->config->item('customer');
		}

		if (isset($response->bean)) {
			if (gettype($response->bean) == 'object' || gettype($response->bean) == 'array') {
				$response->bean = $response->bean;
			} elseif (gettype(json_decode($response->bean)) == 'object' || gettype(json_decode($response->bean)) == 'array') {
				$response->bean = json_decode($response->bean);
			} else {
				$response->bean = $response->bean;
			}

			$this->logMessage->inBean = 'IN BEAN';
		}

		foreach ($response AS $pos => $responseAttr) {
			switch ($pos) {
				case 'archivo':
					$this->logMessage->archivo = 'OK';

					if(!is_array($responseAttr)) {
						$this->logMessage->archivo = 'Sin arreglo binario';
					}
				break;
				case 'bean':
					$this->logMessage->bean = new stdClass();

					if (isset($responseAttr->archivo)) {
						$this->logMessage->bean->archivo = 'OK';

						if(!is_array($responseAttr->archivo)) {
							$this->logMessage->bean->archivo = 'Sin arreglo binario';
						}
					} else {
						$this->logMessage->bean = $responseAttr;
					}
				break;
				case 'msg':
					$this->logMessage->msg = $responseAttr;
				break;
				default:
					$this->logMessage->$pos = $responseAttr;
			}
		}

		$this->logMessage->msg = $this->logMessage->msg ?? 'Sin mensaje del servicio';
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
		$subFix = '_' . strtoupper($this->CI->config->item('customer-uri'));
		$wsUrl = $_SERVER['WS_URL'];

		if (isset($_SERVER['WS_URL' . $subFix])) {
			$wsUrl = $_SERVER['WS_URL' . $subFix];
		}

		log_message('DEBUG', 'NOVO [' . $userName . '] IP ' . $this->CI->input->ip_address() . ' REQUEST BY CUSTOMER: ' .
			$request['pais'] . ', AND WEBSERVICE URL: '	. $wsUrl);

		$requestSerV = json_encode($request, JSON_UNESCAPED_UNICODE);
		$start = microtime(true);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $wsUrl);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 58);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $requestSerV);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: text/plain',
			'Content-Length: ' . strlen($requestSerV))
		);
		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$CurlError = curl_error($ch);
		$CurlErrorNo = curl_errno($ch);

		curl_close($ch);
		$final = microtime(true);
		$executionTime = round($final - $start, 2, PHP_ROUND_HALF_UP) ;

		log_message('DEBUG','NOVO [' . $userName . '] RESPONSE IN ' . $executionTime . ' sec CURL HTTP CODE: ' . $httpCode);

		$failResponse = json_decode($response);

		if(is_object($failResponse)) {
			$response = $failResponse;
			$fail = TRUE;
		}

		if($httpCode != 200 || !$response) {
			$CurlError = novoLang('ERROR CURL NUMBER: %s, MESSAGE: %s ', [$CurlErrorNo, json_encode($CurlError, JSON_UNESCAPED_UNICODE)]);

			log_message('ERROR','NOVO ['.$userName.'] '.$CurlError);

			$failResponse = new stdClass();
			$failResponse->msg = lang('GEN_SYSTEM_MESSAGE');

			switch ($CurlErrorNo) {
				case 28:
					$failResponse->rc = 504;
					$failResponse->msg = lang('GEN_TIMEOUT');
				break;
				default:
					$failResponse->rc = lang('CONF_RC_DEFAULT');
			}

			switch ($httpCode) {
				case 502:
					$failResponse->rc = 502;
				break;
			}

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

		$urlBulkService = BULK_FTP_URL.$this->CI->config->item('customer').'/';
		$userpassBulk =  BULK_FTP_USERNAME.':'.BULK_FTP_PASSWORD;
		$respUpload = new stdClass;
		$respUpload->rc = 0;

		log_message('INFO', 'NOVO UPLOAD FILE BY: '.$urlBulkService.' AND: '.$userpassBulk);

		$ch = curl_init();
		$sftp = fopen(UPLOAD_PATH.$file, 'r');
		curl_setopt($ch, CURLOPT_URL, $urlBulkService.$file);
		curl_setopt($ch, CURLOPT_USERPWD, $userpassBulk);
		curl_setopt($ch, CURLOPT_UPLOAD, 1);
		curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_SFTP);
		curl_setopt($ch, CURLOPT_INFILE, $sftp);
		curl_setopt($ch, CURLOPT_INFILESIZE, filesize(UPLOAD_PATH.$file));
		curl_exec ($ch);
		$result = curl_errno($ch);

		log_message('DEBUG', 'NOVO [' . $userName . '] UPLOAD FILE BULK SFTP ' . $model .': ' . $result . ' ' .
			lang('CONF_UPLOAD_SFTP(' . $result . ')'));

		if($result != 0) {
			$respUpload->rc = -105;
		}

		curl_close ($ch);
		fclose($sftp);
		unlink(UPLOAD_PATH.$file);

		return $respUpload;
	}
	/**
	 * @info Método para escribir el log de la respuesta del servicio
	 * @author J. Enrique Peñaloza Piñero
	 * @date October 25th, 2019
	 */
	private function writeLog($logMessage)
	{
		$writeLog = novoLang('%s = rc: %s, msg: %s, client: %s', [$logMessage->model, $logMessage->rc, $logMessage->msg, $logMessage->pais]);
		$inBean = $logMessage->inBean ?? '';

		log_message('DEBUG', 'NOVO [' . $logMessage->userName . '] IP ' . $this->CI->input->ip_address() . ' RESPONSE ' .
			$writeLog);

		$writeLog = novoLang('%s %s: %s', [$inBean, $logMessage->model, json_encode($logMessage, JSON_UNESCAPED_UNICODE)]);

		log_message('DEBUG', 'NOVO [' . $logMessage->userName . '] IP ' . $this->CI->input->ip_address() .
			' COMPLETE RESPONSE ' . $writeLog);
	}
}
