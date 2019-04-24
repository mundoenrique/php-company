<?php defined('BASEPATH') or exit('No direct script access allowed');

class NOVO_Model extends CI_Model {
	protected $dataAccessLog;
	protected $className;
	protected $accessLog;
	protected $token;
	protected $countryConf;
	protected $dataRequest;
	protected $response;
	protected $isResponseRc;
	protected $userName;

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO_Model  Class Initialized');
		$this->dataAccessLog = new stdClass();
		$this->dataRequest = new stdClass();
		$this->response = new stdClass();
		$this->countryConf = $this->config->item('country');
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
		$this->userName = $this->userName ? $this->userName : $this->dataAccessLog->userName;

		$this->dataRequest->idOperation = $this->dataAccessLog->operation;
		$this->dataRequest->className = $this->className;
		$this->dataRequest->logAccesoObject = $this->accessLog;
		$this->dataRequest->token = $this->token;
		$this->dataRequest->pais = $this->countryConf;

		$encryptData = $this->encrypt_connect->encode($this->dataRequest, $this->userName, $model);
		$request = ['bean'=> $encryptData, 'pais'=> $this->countryConf];
		$response = $this->encrypt_connect->connectWs($request, $this->userName);
		$responseDecript = $this->encrypt_connect->decode($response, $this->userName, $model);
		$this->isResponseRc = FALSE;
		$this->response->title = lang('SYSTEM_NAME');

		if(isset($responseDecript->rc)) {
			$this->isResponseRc = $responseDecript->rc;
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

		return $responseDecript;
	}

	/*
	public function callWs_Xxxx_User($dataRequest)
	{
		log_message('INFO', 'NOVO User Model: Xxxx method Initialized');
		$this->className = '';

		$this->dataAccessLog->modulo = '';
		$this->dataAccessLog->function = '';
		$this->dataAccessLog->operation = '';

		$this->dataRequest->userName = $dataRequest->user;

		$response = $this->sendToService('Xxxx');

		if($this->isResponseRc !== FALSE) {
			switch($this->isResponseRc) {
				case 0:
					$this->response->code = 0;
					$this->response->title = '';
					$this->response->msg = 'Debe cambiar la clave';
					$this->response->data = '';
					break;
				case -xxx:
					$this->response->code = 0;
					$this->response->title = '';
					$this->response->msg = '';
					$this->response->data = '';
					break;
			}
		}

		return $this->response;
	}
	*/
}
