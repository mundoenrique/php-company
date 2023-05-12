<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NOVO_Model extends CI_Model {
	public $dataAccessLog;
	public $accessLog;
	public $token;
	public $autoLogin;
	public $customer;
	public $customerUri;
	public $dataRequest;
	public $isResponseRc;
	public $response;
	public $userName;
	public $singleSession;

	public function __construct()
	{
		parent:: __construct();
		writeLog('INFO', 'Model Class Initialized');

		$this->dataAccessLog = new stdClass();
		$this->dataRequest = new stdClass();
		$this->response = new stdClass();
		$this->customer = $this->session->has_userdata('customerSess') ? $this->session->customerSess : $this->config->item('customer');
		$this->customerUri = $this->session->customerUri;
		$this->token = $this->session->token ?? '';
		$this->autoLogin = $this->session->autoLogin ?? '';
		$this->userName = $this->session->userName;
		$this->singleSession = base64_decode(get_cookie('singleSession', TRUE));
	}
	/**
	 * @info Método para comunicación con el servicio
	 * @author J. Enrique Peñaloza Piñero.
	 * @date April 20th, 2019
	 */
	public function sendToService($model)
	{
		writeLog('INFO', 'Model: sendToService Method Initialized');

		$this->accessLog = accessLog($this->dataAccessLog);
		$this->userName = $this->userName ?: mb_strtoupper($this->dataAccessLog->userName);
		$device = 'desktop';

		$this->dataRequest->pais = $this->customer;
		$this->dataRequest->token = $this->token;
		$this->dataRequest->autoLogin = $this->autoLogin;

		if (lang('CONF_AGENT_INFO') == 'ON') {
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

		$this->isResponseRc = (int) $responseModel->rc;
		$this->response->code = lang('CONF_DEFAULT_CODE');
		$this->response->icon = lang('CONF_ICON_WARNING');
		$this->response->title = lang('GEN_SYSTEM_NAME');
		$this->response->data = new stdClass();
		$this->response->msg = '';

		switch ($model) {
			case 'callWs_GetProductDetail':
				$linkredirect = lang('CONF_LINK_PRODUCTS');
			break;
			case 'callWs_GetProducts':
				$linkredirect = lang('CONF_LINK_ENTERPRISES');
			break;
			default:
				$linkredirect = lang('CONF_LINK_SIGNIN');

				if ($this->session->has_userdata('logged')) {
					$linkredirect = lang('CONF_LINK_ENTERPRISES');
				}
		}

		$linkredirect = $this->session->has_userdata('productInf') ? lang('CONF_LINK_PRODUCT_DETAIL') : $linkredirect;
		$linkredirect = $this->singleSession == 'SignThird' && ($this->isResponseRc == -29 || $this->isResponseRc == -61)
			? 'ingresar/'.lang('CONF_LINK_SIGNOUT_END') : $linkredirect;
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
				$this->response->icon = lang('CONF_ICON_DANGER');
				$this->response->msg = lang('GEN_DUPLICATED_SESSION');
				if($this->session->has_userdata('logged') || $this->session->has_userdata('userId')) {
					$this->session->sess_destroy();
				}
			break;
			case -259:
				$this->response->icon = lang('CONF_ICON_DANGER');
				$this->response->msg = lang('GEN_WITHOUT_AUTHORIZATION');
			break;
			case -437:
				$this->response->icon = lang('CONF_ICON_DANGER');
				$this->response->msg = novoLang(lang('GEN_FAILED_THIRD_PARTY'), '');
			break;
			case 502:
				$this->response->icon = lang('CONF_ICON_DANGER');
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

		writeLog('DEBUG', '['.$this->userName.'] IP ' . $this->input->ip_address() . ' RESULT ' .$model .
			' SENT TO THE VIEW '.json_encode($responsetoView, JSON_UNESCAPED_UNICODE));

		unset($responsetoView);

		return $this->response;
	}
}
