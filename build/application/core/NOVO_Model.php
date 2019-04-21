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
		$this->lang->load('erroreseol');
		$this->lang->load('dashboard');
	}

	public function sendToService($model)
	{
		log_message('INFO', 'NOVO sendToService Method Initialized');

		$this->accessLog = accessLog($this->dataAccessLog);

		$this->dataRequest->idOperation = $this->dataAccessLog->operation;
		$this->dataRequest->className = $this->className;
		$this->dataRequest->logAccesoObject = $this->accessLog;
		$this->dataRequest->token = $this->token;
		$this->dataRequest->pais = $this->countryConf;

		$encryptData = $this->encrypt_connect->encode($this->dataRequest, $model);
		$request = ['bean'=> $encryptData, 'pais'=> $this->countryConf];
		$response = $this->encrypt_connect->connectWs($request);
		$responseDecript = $this->encrypt_connect->decode($response, $model);
		$this->isResponseRc = FALSE;
		$this->response->title = lang('SYSTEM_NAME');

		if(isset($responseDecript->rc)) {
			$this->isResponseRc = $responseDecript->rc;
			switch($this->isResponseRc) {
				case -29:
				case -61:
					$this->response->code = 3;
					$this->response->msg = lang('ERROR_(-29)');
					$this->response->data = base_url('home');
					$this->session->sess_destroy();
					break;
				default:
					$view = $model !== 'login' ? 'dashboard' : 'home';
					$this->response->code = 3;
					$this->response->msg = lang('ERROR_GENERAL');
					$this->response->data = base_url($view);
			}
		} else {
			$this->response->code = 3;
			$this->response->msg = lang('ERROR_GENERAL');
			$this->response->data = base_url('dashboard');
		}

		return $responseDecript;
	}
}
