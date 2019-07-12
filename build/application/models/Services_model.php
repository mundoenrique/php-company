<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Services_model extends CI_Model {
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

	public function __construct()
	{
		log_message('INFO', 'NOVO Services Model Class Initialized');
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
		$this->lang->load('dashboard');
	}

	public function getBanckAccountlist() {
		//log_message('INFO', '');

		$canal = 'ceo';
		$modulo = 'TM';
		$function = 'buscarCuentas';
		$operacion = 'CuentasCliente';
		$idOperation = 'consultaCuentaClientesPrepago';
		$className = 'com.novo.objects.Tos.MaestroDepositoTO';

		$logAcceso = np_hoplite_log(
			$this->sessionId, $this->userName, $canal, $modulo, $function, $operacion, $this->rc,
			$this->ip, $this->timeLog
		);

		$requestData = json_encode([
			'idOperation' => $idOperation,
			'className' => $className,
			'idExtEmp' => $this->rif,
			'logAccesoObject' => $logAcceso,
			'token' => $this->token,
			'pais' => $this->country
		]);

		$dataEncrypt = np_Hoplite_Encryption($requestData, 'getBanckAccountlist');
		$request = json_encode(['bean' => $dataEncrypt, 'pais' => $this->country]);
		$responseWs = np_Hoplite_GetWS('eolwebInterfaceWS', $request);
		$responseJson = np_Hoplite_Decrypt($responseWs, 'getBanckAccountlist');
		$responseWs = json_decode($responseJson);
		/*
			$responseWs = new stdClass();
			$responseWs->rc = -150;
		*/
		if($responseWs) {
			$this->data = 'No fue posible obtener las cuentas';
			$this->title = lang('REG_CTA_CONCEN');
			switch($responseWs->rc) {
				case 0:
					log_message('INFO', '[' . $this->userName . '] ctasClientes: '.$responseWs->bean);
					$ctasCliente = [];
					foreach(json_decode($responseWs->bean) AS $pos => $ctas) {
						foreach($ctas AS $cta) {
							$cta = [
								'descrip'=> mask_account($cta->numeroCuenta, 0, 4),
								'value'=> $cta->numeroCuenta,
								'saldo'=> $cta->saldoDisponible
							];
							array_push($ctasCliente, $cta);
						}
					}
					$this->code = 0;
					$this->data = $ctasCliente;
					break;
				case -150:
					$this->code = 1;
					$this->msg = 'La empresa no tiene cuentas asociadas';
					$this->data = 'La empresa no tiene cuentas asociadas';
					break;
				case -400:
					$this->code = 1;
					$this->msg = 'No fue posible obtener las cuentas de la empresa';
					$this->data = 'No fue posible obtener las cuentas de la empresa';
					break;
				case -29:
				case -61:
					$this->code = 3;
					$this->title = lang('SYSTEM_NAME');
					$this->msg = lang('ERROR_(-29)');
					break;
				default:
					$this->code = 1;
					$this->title = lang('REG_CTA_CONCEN');
					$this->msg = lang('TITULO_ERROR');
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

		return $this->response;
	}
}
