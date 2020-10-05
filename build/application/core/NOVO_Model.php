<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NOVO_Model extends CI_Model {
	public $dataAccessLog;
	public $accessLog;
	public $token;
	public $autoLogin;
	public $country;
	public $countryUri;
	public $dataRequest;
	public $isResponseRc;
	public $response;
	public $userName;
	public $singleSession;

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO Model Class Initialized');

		$this->dataAccessLog = new stdClass();
		$this->dataRequest = new stdClass();
		$this->response = new stdClass();
		$this->country = $this->session->has_userdata('countrySess') ? $this->session->countrySess : $this->config->item('country');
		$this->countryUri = $this->session->countryUri;
		$this->token = $this->session->token ?: '';
		$this->autoLogin = $this->session->autoLogin ?: '';
		$this->userName = $this->session->userName;
		$this->singleSession = base64_decode($this->input->cookie($this->config->item('cookie_prefix').'singleSession'));
	}
	/**
	 * @info Método para comunicación con el servicio
	 * @author J. Enrique Peñaloza Piñero.
	 * @date April 20th, 2019
	 */
	public function sendToService($model)
	{
		log_message('INFO', 'NOVO Model: sendToService Method Initialized');

		$this->accessLog = accessLog($this->dataAccessLog);
		$this->userName = $this->userName ?: mb_strtoupper($this->dataAccessLog->userName);
		$device = 'desktop';

		$this->dataRequest->pais = $this->country;
		$this->dataRequest->token = $this->token;
		$this->dataRequest->autoLogin = $this->autoLogin;

		if (lang('CONF_AGEN_INFO') == 'ON') {
			$this->dataRequest->aplicacion = $this->session->enterpriseInf->thirdApp ?? '';
			$this->dataRequest->dispositivo = $this->agent->is_mobile() ? 'mobile' : 'desktop';
			$this->dataRequest->marca = $this->agent->is_mobile() ? $this->agent->mobile() : '';
			$this->dataRequest->navegador = $this->agent->browser().' V-'.floatval($this->agent->version());
		}

		$this->dataRequest->logAccesoObject = $this->accessLog;
		$encryptData = $this->encrypt_connect->encode($this->dataRequest, $this->userName, $model);
		$request = ['bean'=> $encryptData, 'pais'=> $this->country];
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
		log_message('INFO', 'NOVO Model: sendFile Method Initialized');

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
		log_message('INFO', 'NOVO Model: makeAnswer Method Initialized');

		$this->isResponseRc = (int) $responseModel->rc;
		$this->response->code = lang('GEN_DEFAULT_CODE');
		$this->response->title = lang('GEN_SYSTEM_NAME');
		$this->response->msg = '';
		$this->response->icon = lang('CONF_ICON_WARNING');

		switch ($model) {
			case 'callWs_GetProductDetail':
				$linkredirect = 'productos';
			break;
			case 'callWs_GetProducts':
				$linkredirect = 'empresas';
			break;
			default:
				$linkredirect = 'inicio';

				if ($this->session->has_userdata('logged')) {
					$linkredirect = lang('GEN_ENTERPRISE_LIST');
				}
		}

		$linkredirect = $this->session->has_userdata('productInf') ? 'detalle-producto' : $linkredirect;
		$linkredirect = $this->singleSession == 'SignThird' && ($this->isResponseRc == -29 || $this->isResponseRc == -61)
			? 'ingresar/fin' : $linkredirect;
		$arrayResponse = [
			'btn1'=> [
				'text'=> lang('GEN_BTN_ACCEPT'),
				'link'=> $linkredirect,
				'action'=> 'redirect'
			]
		];
		$this->response->data = $arrayResponse;

		if(!$this->input->is_ajax_request()) {
			$this->response->data = new stdClass();
			$this->response->data->resp = $arrayResponse;
		}

		switch($this->isResponseRc) {
			case -29:
			case -61:
				$this->response->msg = lang('RESP_DUPLICATED_SESSION');
				if($this->session->has_userdata('logged') || $this->session->has_userdata('userId')) {
					$this->session->sess_destroy();
				}
			break;
			case -259:
				$this->response->msg = lang('GEN_WITHOUT_AUTHORIZATION');
			break;
			case -437:
				$this->response->msg = novoLang(lang('GEN_FAILED_THIRD_PARTY'), '');
			break;
			case 504:
				$this->response->msg = lang('GEN_TIMEOUT');
			break;
			default:
				$this->response->msg = lang('GEN_MESSAGE_SYSTEM');
				$this->response->icon = lang('CONF_ICON_DANGER');
			break;
		}

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
		log_message('INFO', 'NOVO Model: responseToView Method Initialized');
		$responsetoView = new stdClass();

		foreach ($this->response AS $pos => $response) {
			if (is_array($response) && isset($response['file'])) {
				continue;
			}
			$responsetoView->$pos = $response;
		}

		log_message('DEBUG', 'NOVO ['.$this->userName.'] RESULT '.$model.' SENT TO THE VIEW '.json_encode($responsetoView));

		unset($responsetoView);

		return $this->response;
	}
}
