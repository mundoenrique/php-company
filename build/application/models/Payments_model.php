<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @Class:  payment_model
 * @package models
 * @INFO:   Clase para la comunicación con el servicio y el API de los pagos a proveedores
 * @author: J Enrique Peñaloza P
 * Date: 27/10/2017
 * Time: 9:30 am
 */
class Payments_model extends CI_Model {
	//Atributos de Clase
	protected $sessionId;
	protected $userName;
	protected $rc = 0;
	protected $rif;
	protected $token;
	protected $ip;
	protected $timeLog;
	protected $code;
	protected $title;
	protected $msg;
	protected $data;
	protected $response = [];

	public function __construct()
	{
		log_message('INFO', 'NOVO Payments Model Class Initialized');
		//Inicializar Atributos de clase
		$this->sessionId = $this->session->userdata('sessionId');
		$this->userName = $this->session->userdata('userName');
		$this->rif = $this->session->userdata('acrifS');
		$this->idProductoS = $this->session->userdata('idProductoS');
		$this->token = $this->session->userdata('token');
		$this->ip = $this->input->ip_address();
		$this->timeLog = date("m/d/Y H:i");
		//Incorporar languages
		$this->lang->load('dashboard');
		$this->lang->load('erroreseol');
		$this->lang->load('reportes');
		$this->lang->load('visa');
	}
	/**
	 * @Method: callWsConsultaSaldo
	 * @access public
	 * @params: void
	 * @info: Método consultar el saldo de la empresa
	 * @autor: Enrique Peñaloza
	 * @date:  04/10/2017
	 */
	public function callWsConsultaSaldo($urlCountry)
	{
		$canal = 'ceo';
		$modulo = 'TM';
		$function = 'buscarTransferenciaM';
		$operacion = 'SaldoCuentaM';
		$idOperation = 'saldoCuentaMaestraTM';
		$className = 'com.novo.objects.TOs.TarjetaTO';

		$logAcceso = np_hoplite_log($this->sessionId, $this->userName, $canal, $modulo,
		                            $function, $operacion, $this->rc, $this->ip, $this->timeLog);

		$data = json_encode([
            'idOperation' => $idOperation,
            'className' => $className,
            'rifEmpresa' => $this->rif,
            'logAccesoObject' => $logAcceso,
            'token' => $this->token,
            'pais' => $urlCountry
        ]);

		log_message('INFO',
		                '[' . $this->userName . '] REQUEST -- callWsConsultaSaldo --> '.
		                $data);

		$dataEncrypt = np_Hoplite_Encryption($data);
		$request = json_encode([
           'bean' => $dataEncrypt,
           'pais' => $urlCountry
       ]);
		$responseWs = np_Hoplite_GetWS('eolwebInterfaceWS', $request);
		$responseJson = np_Hoplite_Decrypt($responseWs);
		$responseWs = json_decode($responseJson);

		log_message('INFO', '[' . $this->userName . '] RESPONSE -- ' .
                    'callWsConsultaSaldo --> ' . json_encode($responseWs));

		if($responseWs) {
			switch ($responseWs->rc) {
				case 0:
					$this->code = 0;
					$this->data = lang('MONEDA') . ' ' .
					              trim($responseWs->maestroDeposito->saldoDisponible);

					break;
				case -29:
				case -61:
					$this->code = 3;
					$this->title = lang('SYSTEM_NAME');
					$this->msg = lang('ERROR_(-29)');
					break;
				case -17:
				case -3:
				case -33:
					$this->code = 3;
					$this->title = lang('SYSTEM_NAME');
					$this->msg = lang('ERROR_GENERICO_USER');
					break;

				case -233:
					$this->code = 2;
					$this->title = lang('BREADCRUMB_PAYMENTS');
					$this->msg = lang('VISA_NON_BALANCE');
					break;

				default:
					$this->code = 2;
					$this->title = lang('BREADCRUMB_PAYMENTS');
					$this->msg = lang('ERROR_(' . $responseWs->rc . ')');
					break;
			}
		} else {
			$this->code = 3;
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

		return json_encode($this->response);

	}
	//----------------------------------------------------------------------------------------------

	/**
	 * @Method: callCeoApiPaymentSuppliers
	 * @access public
	 * @params: $urlCountry,
	 * @params: $dataRequest = array (amount, code)
	 * @info: Método realizar el pago de proveedores visa
	 * @autor: Enrique Peñaloza
	 * @date:  31/10/2017
	 */
	public function callCeoApiPaymentSuppliers($urlCountry, $dataRequest)
	{
		log_message('INFO', '[' . $this->userName . '] '.
		                    'Datos para request===============> ' . $dataRequest);
		$dataPayment = json_decode($dataRequest);

		$amount = $dataPayment->amount;
		$code = $dataPayment->code;
		$reference = $dataPayment->reference;
		$desc = $dataPayment->desc;

		$responseOath = GettokenOauth();

		$httpCode = $responseOath->httpCode;
		$resAPI = json_decode($responseOath->resAPI);

		$token = $httpCode === 200 ? trim($resAPI->access_token) : '';

		log_message('INFO', '[' . $this->userName . '] '.
		                    'RESPONSE OATH access_token===============> ' . $token);

		//cabecera del REQUEST al API
		$header = [
			'x-country: ' . $urlCountry,
			'Authorization: Bearer ' . $token
		];

		$body = [
			'amount' => $amount,
			'reference' => $reference,
			'description' => $desc,
		  'id_ext_emp' => $this->rif,
		  'prefix' => $this->idProductoS
		];

		$urlAPI = 'supplier/' . $code . '/payments?trxid=123';
		$headerAPI = $header;
		$bodyAPI = json_encode($body);
		$method = 'POST';

		log_message('INFO', '[' . $this->userName . '] REQUEST PaymentSuppliers'.
		                    json_encode($headerAPI) . " " . json_encode($bodyAPI));

		$responseCeoApi = GetCeoApi($urlAPI, $headerAPI, $bodyAPI, $method);

		$httpCode = $responseCeoApi->httpCode;
		$resAPI = $responseCeoApi->resAPI;

		log_message('INFO', '[' . $this->userName . '] '.
		                    'RESPONSE PaymentSuppliers====>> httpCode: ' . $httpCode .
		                    " resAPI: " . $resAPI);

		$dataResponse = json_decode($resAPI);
		$this->title = lang('BREADCRUMB_PAYMENTS');
		switch($httpCode) {
			case 200:
				$this->code = 0;
				$this->msg = 'El pago fue realizado exitosamente';
				break;
			case 404:
				$this->code = 2;
				$this->msg = 'En este momento no podemos atender tu solicitud, intenta más tarde';
				break;
			default:
				$this->code = 2;
				$this->msg = $dataResponse->msg;
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

		return json_encode($this->response);

	}
}
