<?php defined('BASEPATH') or exit('No direct script access allowed');

class NOVO_Model extends CI_Model {
	protected $dataAccessLog;
	protected $className;
	protected $accessLog;
	protected $token;
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

		$encryptData = $this->encrypt_connect->encode($this->dataRequest, $model);
		$response = $this->encrypt_connect->connectWs($encryptData);
		$responseDecript = $this->encrypt_connect->decode($response, $model);
		$this->response->title = lang('SYSTEM_NAME');

		if(isset($responseDecript->rc)) {
			$this->isResponseRc = $responseDecript->rc;
			switch($this->isResponseRc) {
				case -29:
				case -61:
					$this->response->code = 302;
					$this->response->msg = lang('ERROR_(-29)');
					break;
				default:
					$this->response->code = 301;
					$this->response->msg = lang('ERROR_GENERAL');
			}
		} else {
			$this->response->code = 301;
			$this->response->msg = lang('ERROR_GENERAL');
		}

		return $responseDecript;
	}
}
