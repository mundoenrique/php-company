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
		$this->token = $this->session->userdata('token') ? $this->session->userdata('token') : '';
		$this->userName = $this->session->userdata('userName');
	}

	public function sendToService($model)
	{
		log_message('INFO', 'NOVO sendToService Method Initialized');

		$this->accessLog = accessLog($this->dataAccessLog);
		$this->userName = $this->userName ?: mb_strtoupper($this->dataAccessLog->userName);

		$this->dataRequest->idOperation = $this->dataAccessLog->operation;
		$this->dataRequest->className = $this->className;
		$this->dataRequest->logAccesoObject = $this->accessLog;
		$this->dataRequest->token = $this->token;
		$this->dataRequest->pais = $this->country;

		$encryptData = $this->encrypt_connect->encode($this->dataRequest, $this->userName, $model);
		$request = ['bean'=> $encryptData, 'pais'=> $this->country];
		$response = $this->encrypt_connect->connectWs($request, $this->userName);

		if(isset($response->rc)) {
			$responseDecrypt = $response;
		} else {
			$responseDecrypt = $this->encrypt_connect->decode($response, $this->userName, $model);
		}
		$this->isResponseRc = $responseDecrypt->rc;
		$this->response->code = 303;
		$this->response->title = lang('SYSTEM_NAME');
		$this->response->icon = 'ui-icon-alert';
		switch($this->isResponseRc) {
			case -29:
			case -61:
				log_message('INFO', 'NOVO -------------------29'.json_encode($this->isResponseRc));
				$this->response->msg = lang('ERROR_(-29)');
				$this->response->data = base_url('inicio');
				$this->response->data = [
					'btn1'=> [
						'text'=> lang('BUTTON_ACCEPT'),
						'link'=> base_url('inicio'),
						'action'=> 'redirect'
					]
				];
				$this->session->sess_destroy();
				break;
			default:
				log_message('INFO', 'NOVO -------------------default'.json_encode($this->isResponseRc));
				$this->response->msg = lang('ERROR_GENERAL');
				$this->response->data = [
					'btn1'=> [
						'text'=> lang('BUTTON_ACCEPT'),
						'link'=> base_url('empresas'),
						'action'=> 'redirect'
					]
				];
				break;
		}

		return $responseDecrypt;
	}

	public function cypherData()
	{
		log_message('INFO', 'NOVO cypherData Method Initialized');
		log_message('DEBUG', 'NOVO RESPONSE TO VIEW: '.json_encode($this->response));

		return $this->cryptography->encrypt($this->response);
	}
}
