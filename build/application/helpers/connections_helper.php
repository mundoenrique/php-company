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
	function np_Hoplite_GetWS($nameWS, $cryptDataBase64)
	{
		$getPais = json_decode($cryptDataBase64);
		$pais = $getPais->pais;

		$CI =& get_instance();
		$urlcurlWS=$CI->config->item('urlWS').$nameWS;

		log_message('DEBUG', 'BY COUNTRY: '.$pais.', AND WEBSERVICE URL: '.$urlcurlWS);

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
		log_message("DEBUG","CURL HTTP CODE: " . $httpCode);
		if(!$httpCode || $httpCode == 404) {
			return FALSE;
		} else {
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
		log_message("DEBUG", "INICIANDO LLAMADO API POR EL METODO:===>>> " . $method);
		$header = [
			'Content-Type: application/json',
		];

		foreach ($headerAPI as $item) {
			$item = trim($item);
			array_push($header, $item);
		}

		$urlcurlAPI = $CI->config->item('urlAPI') . $urlAPI;
		log_message("DEBUG", "URL API: " . $urlcurlAPI);

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
//--------------------------------------------------------------------------------------------------

if ( ! function_exists('GetCeoApi')) {
	/**
	 * Realiza el llamado al api ceoapi
	 * @param  string $urlAPI
	 * @param  string $headerAPI
	 * @param  string $bodyAPI
	 * @param  string $method
	 * @return object
	 */
	function GetCeoApi($urlAPI, $headerAPI, $bodyAPI, $method)
	{
		$CI = &get_instance();
		log_message("INFO", "INICIANDO LLAMADO CEOAPI POR EL METODO:===>>> " . $method);
		$header = [
			'Content-Type: application/json',
			'language: es',
			'channel: API',
			'accept: application/json'
		];

		foreach ($headerAPI as $item) {
			$item = trim($item);
			array_push($header, $item);
		}

		$urlcurlAPI = $CI->config->item('urlServ') . 'ceoapi/1.0/' . $urlAPI;
		log_message("DEBUG", "URL API: " . $urlcurlAPI);

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
//--------------------------------------------------------------------------------------------------

if ( ! function_exists('GetApiContent')) {
	/**
	 * Realiza el llamado al api help_ceo
	 * @param  string $urlAPI
	 * @param  string $headerAPI
	 * @param  string $bodyAPI
	 * @param  string $method
	 * @return object
	 */
	function GetApiContent($urlAPI, $headerAPI, $bodyAPI, $method)
	{
		//set params
		$CI = &get_instance();
		log_message("INFO", "INICIANDO LLAMADO API-CONTENT POR EL METODO:===>>> " . $method);
		$header = [
			'Content-Type: application/json',
			'language: es',
			'channel: API',
			'accept: application/json'
		];

		foreach ($headerAPI as $item) {
			$item = trim($item);
			array_push($header, $item);
		}

		$urlcurlAPI = $CI->config->item('urlAPIContent') . $urlAPI;

		// create curl resource
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

if ( ! function_exists('GettokenOauth')) {
	/**
	 * Realiza el llamado a Oauth
	 * @return object
	 */
	function GettokenOauth()
	{
		$CI = &get_instance();
		log_message("INFO", "INICIANDO LLAMADO OAUTH");
		$header = [
			'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
			'language: es',
			'channel: web',
			'accept: application/json; charset=utf-8'
		];

		$bodyAPI = [
			'grant_type' => 'client_credentials',
			'client_id' => $CI->config->item('client_id'),
			'client_secret' => $CI->config->item('client_secret')
		];

		$urlcurlAPI = $CI->config->item('urlServ') . 'auth2/1.0/token';
		log_message("INFO", "AUTENTICACIÓN oauth: " . $urlcurlAPI . ' client_id: ' .
		                    $CI->config->item('client_id') . ' client_secret: ' .
		                    $CI->config->item('client_secret'));

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $urlcurlAPI);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($bodyAPI));
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
//--------------------------------------------------------------------------------------------------
