<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NOVO_Model extends CI_Model {
	public $dataAccessLog;
	public $className;
	public $accessLog;
	public $token;
	public $country;
	public $countryUri;
	public $dataRequest;
	public $isResponseRc;
	public $response;
	public $userName;

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO_Model  Class Initialized');

		$this->dataAccessLog = new stdClass();
		$this->dataRequest = new stdClass();
		$this->response = new stdClass();
		$this->country = $this->session->userdata('countrySess') ? $this->session->userdata('countrySess')
			: $this->config->item('country');
		$this->countryUri = $this->session->userdata('countryUri');
		$this->token = $this->session->userdata('token') ?: '';
		$this->userName = $this->session->userdata('userName');
	}

	public function sendToService($model)
	{
		log_message('INFO', 'NOVO sendToService Method Initialized');

		$this->accessLog = accessLog($this->dataAccessLog);
		$this->userName = $this->userName ?: mb_strtoupper($this->dataAccessLog->userName);

		$this->dataRequest->className = $this->className;
		$this->dataRequest->logAccesoObject = $this->accessLog;
		$this->dataRequest->token = $this->token;
		$this->dataRequest->pais = $this->country;

		$encryptData = $this->encrypt_connect->encode($this->dataRequest, $this->userName, $model);
		$request = ['bean'=> $encryptData, 'pais'=> $this->country];
		$response = $this->encrypt_connect->connectWs($request, $this->userName, $model);

		if(isset($response->rc)) {
			$responseDecrypt = $response;
		} else {
			$responseDecrypt = $this->encrypt_connect->decode($response, $this->userName, $model);
		}

		$this->isResponseRc = (int) $responseDecrypt->rc;
		$this->response->code = lang('RESP_DEFAULT_CODE');
		$this->response->title = lang('GEN_SYSTEM_NAME');
		$this->response->msg = '';
		$this->response->icon = 'ui-icon-alert';
		$this->response->data = [
			'btn1'=> [
				'text'=> FALSE,
				'link'=> base_url(lang('GEN_ENTERPRISE_LIST')),
				'action'=> 'redirect'
			]
		];
		switch($this->isResponseRc) {
			case -29:
			case -61:
				$this->response->msg = lang('RES_DUPLICATED_SESSION');
				$this->session->sess_destroy();
				break;
			default:
				$this->response->msg = lang('RESP_MESSAGE_SYSTEM');
				break;
		}
		$this->response->msg = $this->isResponseRc == 0 ? lang('RESP_RC_0') : $this->response->msg;

		return $responseDecrypt;
	}

	protected function responseToTheView($model)
	{
		log_message('INFO', 'NOVO_Model CLASS: responseToView method Initialized');
		log_message('DEBUG', 'NOVO ['.$this->userName.'] RESULT '.$model.' SENT TO THE VIEW '.json_encode($this->response));

		return $this->response;
	}
}
