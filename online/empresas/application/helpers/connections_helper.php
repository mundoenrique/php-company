<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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

	if ( ! function_exists('np_Hoplite_GetWS')) {
		/**
		 * Realiza el llamado al servicio web
		 * @param  string $nameWS
		 * @param  json $cryptDataBase64
		 * @return json
		 */
		function np_Hoplite_GetWS($nameWS,$cryptDataBase64)
		{
			log_message("DEBUG","INICIANDO LLAMADO WS: ".$nameWS);
			$CI =& get_instance();
			$urlcurlWS=$CI->config->item('urlWS').$nameWS;
			log_message("INFO",$urlcurlWS);
			$ch = curl_init();
			$dataPost = $cryptDataBase64;
			curl_setopt($ch, CURLOPT_URL, $urlcurlWS);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $dataPost);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				               'Content-Type: text/plain',
				               'Content-Length: ' . strlen($dataPost))
			);
			$response = curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			log_message("ERROR","CURL HTTP CODE: " . $httpCode);
			if($httpCode==404){
				return FALSE;
			}else{
				return $response;
			}

		}
	}

	if ( ! function_exists('GetAPIServ')) {
		/**
		 * Realiza el llamado al servicio web
		 * @param  string $nameWS
		 * @param  json $cryptDataBase64
		 * @return json
		 */
		function GetAPIServ($urlAPI, $headerAPI, $bodyAPI, $method)
		{
			$CI =& get_instance();
			log_message("INFO", "INICIANDO LLAMADO API POR EL METODO:===>>> " . $method);
			$header = [
				'Content-Type: application/json',
			];

			foreach ($headerAPI as $item) {
				$item = trim($item);
				array_push($header, $item);
			}

			$urlcurlAPI = $CI->config->item('urlAPI') . $urlAPI;
			log_message("INFO", "URL API: " . $urlcurlAPI);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $urlcurlAPI);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $bodyAPI);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

			$responseAPI = curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			curl_close($ch);

			$response = new stdClass();
			$response->httpCode = $httpCode;
			$response->resAPI = $responseAPI;

			return $response;

		}


	}