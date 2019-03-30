<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class services_model extends CI_model {
	//Atributos de Clase
	protected $sessionId;
	protected $userName;
	protected $rc;
	protected $rif;
	protected $idProductoS;
	protected $token;
	protected $ip;
	protected $country;
	protected $timeLog;
	protected $code;
	protected $title;
	protected $msg;
	protected $data;
	protected $response = [];

	public function __construct ()
	{
		parent:: __construct();
		$this->sessionId = $this->session->userdata('sessionId');
		$this->userName = $this->session->userdata('userName');
		$this->rc = 0;
		$this->rif = $this->session->userdata('acrifS');
		$this->idProductoS = $this->session->userdata('idProductoS');
		$this->token = $this->session->userdata('token');
		$this->ip = $this->input->ip_address();
		$this->country = $this->session->userdata('pais');
		$this->timeLog = date("m/d/Y H:i");

		//add lenguajes
		$this->lang->load('servicios');
		$this->lang->load('users');
		$this->lang->load('erroreseol');
	}

	public function getBanckAccountlist() {
		//log_message('INFO', '');

		$canal = 'ceo';
		$modulo = '';
		$function = '';
		$operacion = '';
		$idOperation = '';
		$className = '';

		$logAcceso = np_hoplite_log(
			$this->sessionId, $this->userName, $canal, $modulo, $function, $operacion, $this->rc,
			$this->ip, $this->timeLog
		);

		$requestData = json_encode([
			'idOperation' => $idOperation,
			'className' => $className,
			'id_ext_emp' => $this->rif,
			'idprograma' => $this->idProductoS,
			'logAccesoObject' => $logAcceso,
			'token' => $this->token,
			'pais' => $this->country
		]);
		/*
		$dataEncrypt = np_Hoplite_Encryption($requestData, 'getBanckAccountlist');
		$request = json_encode(['bean' => $dataEncrypt, 'pais' => $urlCountry]);
		$responseWs = np_Hoplite_GetWS('eolwebInterfaceWS', $request);
		$responseJson = np_Hoplite_Decrypt($responseWs, 'getBanckAccountlist');
		$responseWs = json_decode($responseJson);
		*/
		$responseWs = new stdClass();
		$responseWs->rc = 0;
		if($responseWs) {
			switch($responseWs->rc) {
				case 0:
					log_message('INFO', '[' . $this->userName . ']');
					$this->code = 0;
					$this->data = [
						[
							'descrip'=> '****6690',
							'value'=> '2100076690',
							'saldo'=> '5,000.00'
						],
						[
							'descrip'=> '****1404',
							'value'=> '3349401404',
							'saldo'=> '6,000.00'
						]
					];
					break;
			}

		} else {
			$this->code = 2;
			$this->title = lang('SYSTEM_NAME');
			$this->msg = lang('ERROR_GENERICO_USER');
		}

		$this->response = [
			'code' => $this->code,
			'title' => $this->title,
			'msg' => $this->msg,
			'data' => $this->data
		];

		if($this->code === 3) {
			$this->session->sess_destroy();
		}

		return $this->response;
	}
}
