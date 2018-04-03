<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @Class:  Reporte_trayectos_Model
 * @package models
 * @INFO:   Clase para la obtención de los reportes de trayectos
 * @author: J Enrique Peñaloza P
 * Date: 22/03/2017
 * Time: 12:50 pm
 */
class reportes_trayectos_model extends CI_Model {
	//Atributos de clase
	protected $pais;
	protected $token;
	protected $company;
	protected $idProducto;
	protected $userName;

	public function __construct()
	{
		parent::__construct();
		//Inicializa atributos de clase
		$this->pais = $this->session->userdata('pais');
		$this->token = $this->session->userdata('token');
		$this->company = '000000000' . $this->session->userdata('acrifS');
		$this->idProducto = $this->session->userdata('idProductoS');
		$this->userName = $this->session->userdata('userName');
		//Agrega lenguajes
		$this->lang->load('dashboard');
		$this->lang->load('combustible');
		$this->lang->load('users');
		$this->lang->load('erroreseol');
	}
	/**
	 * @Method:
	 * @access public
	 * @params: string $urlCountry
	 * @params: array $dataRquest = []
	 * @info:
	 * @autor:
	 * @date:
	 */
	public function callAPIConductoresExcel($urlCountry, $dataRequest)
	{
		log_message('INFO', '[' . $this->userName . '] DATAREQUEST DESCARGA EXCEL CONDUCTORES===============>>>>>>>>'. $dataRequest);
	}
	/**
	 * @Method: callAPIConductoresExcel
	 * @access public
	 * @params: string $urlCountry
	 * @params: array $dataRquest = []
	 * @info: Método para obtener el reporte del detalle de los conductores
	 * @autor: Edinson Cabello
	 * @date: 27/03/2018
	 */
	public function callAPIVehiculosExcel($urlCountry, $dataRequest)
	{
		log_message('INFO', '['.$this->username.'] DATAREQUEST descarga EXCEL Vehiculos --->>> '.$datarequest);

		if($dataRequest === 'file') {
			$filename = 'vehiculos';
			$file = $this->session->flashdata('file');
			np_hoplite_byteArrayToFile($file, 'xls', $filename, FALSE);
			exit();
		}

		//cabecera del REQUEST al API
		$header = [
			'x-country: ' . $this->pais,
			'x-token: ' . $this->token,
			'x-company: ' . $this->company
		];


	}
	/**
	 * @Method: callAPICuentasExcel
	 * @access public
	 * @param string $urlCountry
	 * @param array $dataRquest = ['status']
	 * @info Método para obtener el reporte de las cuentas de una empresa
	 * @author J. Enrique Peñaloza P.
	 * @date 02/04/2018
	 */
	public function callAPICuentasExcel($urlCountry, $dataRequest)
	{
		log_message('INFO', '[' . $this->userName .'] DATAREQUEST Descarga EXCEL cuentas ==>>' . $dataRequest);

		if($dataRequest === 'file') {
			$filename = 'cuentas' . date('Ymd-B');
			$file = $this->session->flashdata('file');
			np_hoplite_byteArrayToFile($file, 'xls', $filename, FALSE);
			exit();
		}

		//cabecera del REQUEST al API
		$header = [
			'x-country: ' . $this->pais,
			'x-token: ' . $this->token,
			'x-company: ' . $this->company
		];

		$dataReport = json_decode($dataRequest);
		$status = $dataReport->status;
		//url API
		$urlAPI = 'account/report/excel?status=' . $status;

		$headerAPI = $header;
		$bodyAPI = '';
		$method = 'GET';

		log_message('INFO', '[' . $this->userName . '] REQUEST CuentasReport======>>>>>' . $urlAPI);

		$jsonResponse = GetAPIServ($urlAPI, $headerAPI, $bodyAPI, $method);

		$httpCode = $jsonResponse->httpCode;
		$resAPILog = $httpCode != 200 ? $jsonResponse->resAPI : '';
		$resAPI = $jsonResponse->resAPI;

		log_message('INFO',  '[' . $this->userName . '] RESPONSE CuentasReport: ==>> httpCode: ' . $httpCode . ' $resAPI: ' . $resAPILog);

		//$httpCode = 400;
		if($httpCode === 200) {
			$this->session->set_flashdata('file', $resAPI);
		}

		$code = '';
		$title = '';
		$msg = '';

		switch($httpCode) {
			case 200:
				$code = 0;
				break;
			default:
				$code = 1;
				$title = lang('TAG_REPORTE_	ACCOUNTS');
				$msg = lang('ERROR_REPORT');
		}

		return $response = [
			'code' => $code,
			'title' => $title,
			'msg' => $msg
		];
	}
	/**
	 * @Method: callAPIViajesReport
	 * @access public
	 * @param string $urlCountry
	 * @param array $dataRquest = ['type', 'status', 'beginDate', 'finalDate']
	 * @info Método para obtener el reporte de los viajes de una empresa
	 * @author J. Enrique Peñaloza P.
	 * @date: 26/03/2018
	 */
	public function callAPIViajesExcel($urlCountry, $dataRequest)
	{
		log_message('INFO', '[' . $this->userName . '] DATAREQUEST DESCARGA EXCEL VIAES===============>>>>>>>>'. $dataRequest);

		if($dataRequest === 'file') {
			$filename = 'viajes' . date('Ymd-B');
			$file = $this->session->flashdata('file');
			np_hoplite_byteArrayToFile($file, 'xls', $filename, FALSE);
			exit();
		}

		//cabecera del REQUEST al API
		$header = [
			'x-country: ' . $this->pais,
			'x-token: ' . $this->token,
			'x-company: ' . $this->company
		];

		$dataReport = json_decode($dataRequest);
		$type = $dataReport->type;
		$beginDate = $dataReport->beginDate;
		$finalDAte = $dataReport->finalDate;
		//url API
		$urlAPI = 'travel/report/excel?from=' . $beginDate . '&to=' . $finalDAte;
		if($type === 'statusId') {

			$status = $dataReport->status;
			$statusId = [
				'PRECREATED' => 0,
				'CREATED' => 1,
				'STARTED' => 2,
				'FINISHED' => 3,
				'CANCELLED' => 4
			];

			$urlAPI.= '&status=' . $statusId[$status];
		} elseif($type === 'count') {

			$urlAPI = '?quantity=30';
		}

		$headerAPI = $header;
		$bodyAPI = '';
		$method = 'GET';

		log_message('INFO', '[' . $this->userName . '] REQUEST ViajesReport======>>>>>' . $urlAPI);

		$jsonResponse = GetAPIServ($urlAPI, $headerAPI, $bodyAPI, $method);

		$httpCode = $jsonResponse->httpCode;
		$resAPILog = $httpCode != 200 ? $jsonResponse->resAPI : '';
		$resAPI = $jsonResponse->resAPI;

		log_message('INFO',  '[' . $this->userName . '] RESPONSE ViajesReport: ==>> httpCode: ' . $httpCode . ' $resAPI: ' . $resAPILog);

		//$httpCode = 400;
		if($httpCode === 200) {
			$this->session->set_flashdata('file', $resAPI);
		}

		$code = '';
		$title = '';
		$msg = '';

		switch($httpCode) {
			case 200:
				$code = 0;
				break;
			default:
				$code = 1;
				$title = lang('TAG_REPORTE_TRAVELS');
				$msg = lang('ERROR_REPORT');
		}

		return $response = [
			'code' => $code,
			'title' => $title,
			'msg' => $msg
		];
	}
	/**
	 * @Method: callAPIViajesPdf
	 * @access public
	 * @params: string $urlCountry
	 * @params: array $dataRquest = ['travelId']
	 * @info: Método para obtener el reporte del detalle de una viaje
	 * @autor: J. Enrique Peñaloza
	 * @date: 28/03/2018
	 */
	public function callAPIViajesPdf($urlCountry, $dataRequest)
	{
		log_message('INFO', '[' . $this->userName . '] DATAREQUEST DESCARGA PDF VIAES>>'. $dataRequest);

		if($dataRequest === 'file') {
			$filename = 'detalleViaje' . date('Ymd-B');date('Ymd-B');
			$file = $this->session->flashdata('file');
			np_hoplite_byteArrayToFile($file, 'pdf', $filename, FALSE);
			exit();
		}
		//cabecera del REQUEST al API
		$header = [
			'x-country: ' . $this->pais,
			'x-token: ' . $this->token,
			'x-company: ' . $this->company,
			'x-product: ' .$this->idProducto
		];

		$travelDetail = json_decode($dataRequest);
		$travelId = $travelDetail->travelId;
		//url API
		$urlAPI = $urlAPI = 'travel/' . $travelId . '/report/pdf';

		$headerAPI = $header;
		$bodyAPI = '';
		$method = 'GET';

		log_message('INFO', '[' . $this->userName . '] REQUEST ViajesReport======>>>>>' . $urlAPI . '--' . $this->token);

		$jsonResponse = GetAPIServ($urlAPI, $headerAPI, $bodyAPI, $method);

		$httpCode = $jsonResponse->httpCode;
		$resAPILog = $httpCode != 200 ? $jsonResponse->resAPI : '';
		$resAPI = $jsonResponse->resAPI;

		log_message('INFO',  '[' . $this->userName . '] RESPONSE ViajesReport: ==>> httpCode: ' . $httpCode . ' $resAPI: ' . $resAPILog);

		//$httpCode = 400;
		if($httpCode === 200) {
			$this->session->set_flashdata('file', $resAPI);
		}

		$code = '';
		$title = '';
		$msg = '';

		switch($httpCode) {
			case 200:
				$code = 0;
				break;
			default:
				$code = 1;
				$title = lang('TAG_REPORTE_TRAVELS');
				$msg = lang('ERROR_REPORT');
		}

		return $response = [
			'code' => $code,
			'title' => $title,
			'msg' => $msg
		];
	}
}
