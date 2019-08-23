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
	public $response;
	public $isResponseRc;
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
		$this->isResponseRc = 'No web service';
		$this->token = $this->session->userdata('token') ? $this->session->userdata('token') : '';
		$this->userName = $this->session->userdata('userName');
		$this->lang->load('erroreseol');
		$this->lang->load('dashboard');
	}

	public function sendToService($model)
	{
		log_message('INFO', 'NOVO sendToService Method Initialized');

		$this->accessLog = accessLog($this->dataAccessLog);
		$this->userName = $this->userName ?: mb_strtoupper($this->accessLog->userName);

		$this->dataRequest->idOperation = $this->dataAccessLog->operation;
		$this->dataRequest->className = $this->className;
		$this->dataRequest->logAccesoObject = $this->accessLog;
		$this->dataRequest->token = $this->token;
		$this->dataRequest->pais = $this->country;

		$encryptData = $this->encrypt_connect->encode($this->dataRequest, $this->userName, $model);
		$request = ['bean'=> $encryptData, 'pais'=> $this->country];
		$response = $this->encrypt_connect->connectWs($request, $this->userName);
		$responseDecrypt = $this->encrypt_connect->decode($response, $this->userName, $model);
		$this->isResponseRc = FALSE;
		$this->response->title = lang('SYSTEM_NAME');

		if(isset($responseDecrypt->rc)) {
			$this->isResponseRc = $responseDecrypt->rc;
			switch($this->isResponseRc) {
				case -29:
				case -61:
					$this->response->code = 303;
					$this->response->msg = lang('ERROR_(-29)');
					$this->response->data = base_url('inicio');
					$this->response->icon = 'ui-icon-alert';
					$this->response->data = [
						'btn1'=> [
							'text'=> 'Aceptar',
							'link'=> base_url('inicio'),
							'action'=> 'redirect'
						]
					];
					$this->session->sess_destroy();
					break;
				default:
					$this->response->code = 303;
					$this->response->msg = lang('ERROR_GENERAL');
					$this->response->icon = 'ui-icon-alert';
					$this->response->data = [
						'btn1'=> [
							'text'=> 'Aceptar',
							'link'=> base_url('dashboard'),
							'action'=> 'redirect'
						]
					];
			}
		} else {
			$this->response->code = 303;
			$this->response->msg = lang('ERROR_GENERAL');
			$this->response->icon = 'ui-icon-alert';
			$this->response->data = [
				'btn1'=> [
					'text'=> 'Aceptar',
					'link'=> base_url('dashboard'),
					'action'=> 'redirect'
				]
			];
		}

		return $responseDecrypt;
	}

	public function cypherData() {
		log_message('INFO', 'NOVO cypherData Method Initialized');
		log_message('DEBUG', 'NOVO RESPONSE TO VIEW: '.json_encode($this->response));

		return $this->cryptography->encrypt($this->response);
	}
}
