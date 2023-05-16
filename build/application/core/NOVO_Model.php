<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Clase Modelo de Conexión Empresas Online (CEO)
 *
 * Esta clase es la súper clase de la que heredarán todos los modelos
 * de la aplicación.
 *
 * @package models
 * @author J. Enrique Peñaloza Piñero
 * @date May 16th, 2020
 */
class NOVO_Model extends CI_Model {
	public $dataAccessLog;
	public $accessLog;
	public $customer;
	public $customerUri;
	public $customerImages;
	public $dataRequest;
	public $userName;
	public $autoLogin;
	public $token;
	public $isResponseRc;
	public $singleSession;
	public $response;

	public function __construct()
	{
		parent:: __construct();
		writeLog('INFO', 'Model Class Initialized');

		$this->dataAccessLog = new stdClass();
		$this->customer = $this->session->customerSess ?? $this->config->item('customer');
		$this->customerUri = $this->session->customerUri ?? $this->config->item('customer_uri');
		$this->customerImages = $this->config->item('customer_images');
		$this->dataRequest = new stdClass();
		$this->userName = $this->session->userName;
		$this->autoLogin = $this->session->autoLogin ?? '';
		$this->token = $this->session->token ?? '';
		$this->singleSession = base64_decode(get_cookie('singleSession', TRUE));
		$this->response = new stdClass();
		$this->response->code = lang('SETT_DEFAULT_CODE');
		$this->response->icon = lang('SETT_ICON_WARNING');
		$this->response->title = lang('GEN_SYSTEM_NAME');
		$this->response->msg = '';
		$this->response->data = new stdClass();
	}
	/**
	 * @info Método para comunicación con el servicio
	 * @author J. Enrique Peñaloza Piñero.
	 * @date April 20th, 2019
	 */
	public function sendToWebServices($model)
	{
		writeLog('INFO', 'Model: sendToWebServices Method Initialized');

		$logResponse = new stdClass();
		$request = [];
		$this->accessLog = accessLog($this->dataAccessLog);

		$this->dataRequest->pais = $this->customer;
		$this->dataRequest->token = $this->token;
		$this->dataRequest->autoLogin = $this->autoLogin;

		if (lang('SETT_AGENT_INFO') === 'ON') {
			$this->dataRequest->aplicacion = $this->session->enterpriseInf->thirdApp ?? '';
			$this->dataRequest->dispositivo = $this->agent->is_mobile() ? 'mobile' : 'desktop';
			$this->dataRequest->marca = $this->agent->is_mobile() ? $this->agent->mobile() : '';
			$this->dataRequest->navegador = $this->agent->browser() . ' V-' . floatval($this->agent->version());
		}

		$this->dataRequest->logAccesoObject = $this->accessLog;
		$request['bean'] = $this->dataRequest;
		$request['pais'] = $this->customer;
		$dataRequest = json_encode($this->dataRequest, JSON_UNESCAPED_UNICODE);

		writeLog('DEBUG', 'WEB SERVICES REQUEST ' . $model . ': ' . json_encode($request, JSON_UNESCAPED_UNICODE));

		$encryptRequest = $this->encrypt_decrypt->encryptWebServices($dataRequest);
		$request['bean'] = $encryptRequest;
		$encryptResponse = $this->connect_services_apis->connectWebServices($request);
		$response = $this->encrypt_decrypt->decryptWebServices($encryptResponse);
		$response = handleResponseServer($response);

		foreach ($response as $key => $value) {
			if (isset($value->archivo) || isset($value->bean->archivo)) {
				continue;
			}

			$logResponse->$key = $value;
		}

		writeLog('DEBUG', 'WEB SERVICES RESPONSE COMPLETE ' . $model . ': '
			. json_encode($logResponse, JSON_UNESCAPED_UNICODE));

		unset($logResponse);

		return $this->makeAnswer($response, $model);
	}

	public function sendToService($model)
	{
		writeLog('INFO', 'Model: sendToService Method Initialized');

		$this->accessLog = accessLog($this->dataAccessLog);
		$this->userName = $this->userName ?: mb_strtoupper($this->dataAccessLog->userName);
		$device = 'desktop';

		$this->dataRequest->pais = $this->customer;
		$this->dataRequest->token = $this->token;
		$this->dataRequest->autoLogin = $this->autoLogin;

		if (lang('SETT_AGENT_INFO') == 'ON') {
			$this->dataRequest->aplicacion = $this->session->enterpriseInf->thirdApp ?? '';
			$this->dataRequest->dispositivo = $this->agent->is_mobile() ? 'mobile' : 'desktop';
			$this->dataRequest->marca = $this->agent->is_mobile() ? $this->agent->mobile() : '';
			$this->dataRequest->navegador = $this->agent->browser().' V-'.floatval($this->agent->version());
		}

		$this->dataRequest->logAccesoObject = $this->accessLog;
		$encryptData = $this->encrypt_connect->encode($this->dataRequest, $this->userName, $model);
		$request = ['bean'=> $encryptData, 'pais'=> $this->customer];
		$response = $this->encrypt_connect->connectWs($request, $this->userName, $model);

		if(isset($response->rc)) {
			$responseDecrypt = $response;
		} else {
			$responseDecrypt = $this->encrypt_connect->decode($response, $this->userName, $model);
		}

		return $this->makeAnswer($responseDecrypt, $model);
	}
	/**
	 * @info Método para comunicación con el servicio
	 * @author J. Enrique Peñaloza Piñero.
	 * @date April 20th, 2019
	 */
	public function sendFile($file, $model)
	{
		writeLog('INFO', 'Model: sendFile Method Initialized');

		$responseUpload = $this->encrypt_connect->moveFile($file, $this->userName, $model);

		return $this->makeAnswer($responseUpload, $model);
	}
	/**
	 * @info Método armar la respuesta a los modelos
	 * @author J. Enrique Peñaloza Piñero.
	 * @date December 11th, 2019
	 */
	protected function makeAnswer($responseModel, $model)
	{
		writeLog('INFO', 'Model: makeAnswer Method Initialized');
		$responseCode = $responseModel->rc ?? $responseModel->responseCode;
		$this->isResponseRc = (int) $responseCode;

		switch ($model) {
			case 'callWs_GetProductDetail':
				$linkredirect = lang('SETT_LINK_PRODUCTS');
			break;
			case 'callWs_GetProducts':
				$linkredirect = lang('SETT_LINK_ENTERPRISES');
			break;
			default:
				$linkredirect = lang('SETT_LINK_SIGNIN');

				if ($this->session->has_userdata('logged')) {
					$linkredirect = lang('SETT_LINK_ENTERPRISES');
				}
		}

		$linkredirect = $this->session->has_userdata('productInf') ? lang('SETT_LINK_PRODUCT_DETAIL') : $linkredirect;
		$linkredirect = $this->singleSession == 'SignThird' && ($this->isResponseRc == -29 || $this->isResponseRc == -61)
			? 'ingresar/'.lang('SETT_LINK_SIGNOUT_END') : $linkredirect;
		$arrayResponse = [
			'btn1'=> [
				'text'=> lang('GEN_BTN_ACCEPT'),
				'link'=> $linkredirect,
				'action'=> 'redirect'
			]
		];

		switch($this->isResponseRc) {
			case -29:
			case -61:
				$this->response->icon = lang('SETT_ICON_DANGER');
				$this->response->msg = lang('GEN_DUPLICATED_SESSION');
				if($this->session->has_userdata('logged') || $this->session->has_userdata('userId')) {
					$this->session->sess_destroy();
				}
			break;
			case -259:
				$this->response->icon = lang('SETT_ICON_DANGER');
				$this->response->msg = lang('GEN_WITHOUT_AUTHORIZATION');
			break;
			case -437:
				$this->response->icon = lang('SETT_ICON_DANGER');
				$this->response->msg = novoLang(lang('GEN_FAILED_THIRD_PARTY'), '');
			break;
			case 502:
				$this->response->icon = lang('SETT_ICON_DANGER');
				$this->response->msg = lang('GEN_SYSTEM_MESSAGE');
				$this->session->sess_destroy();
			break;
			case 504:
				$this->response->msg = lang('GEN_TIMEOUT');
			break;
			default:
				$this->response->msg = lang('GEN_SYSTEM_MESSAGE');
			break;
		}

		$this->response->modalBtn = $arrayResponse;
		$this->response->msg = $this->isResponseRc == 0 ? lang('GEN_RC_0') : $this->response->msg;

		return $responseModel;
	}
	/**
	 * @info Método enviar el resultado de la consulta a la vista
	 * @author J. Enrique Peñaloza Piñero.
	 * @date November 21st, 2019
	 */
	public function responseToTheView($model)
	{
		writeLog('INFO', 'Model: responseToView Method Initialized');
		$responsetoView = new stdClass();

		foreach ($this->response AS $pos => $response) {
			if (is_object($response) && isset($response->file)) {
				continue;
			}

			$responsetoView->$pos = $response;
		}

		writeLog('DEBUG', 'RESULT ' . $model . ' SENT TO THE VIEW ' . json_encode($responsetoView, JSON_UNESCAPED_UNICODE));

		unset($responsetoView);

		return $this->response;
	}
}
