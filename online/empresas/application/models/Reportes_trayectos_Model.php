<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @Class:  Reporte_trayectos_Model
 * @package models
 * @INFO:   Clase para la obtención de los reportes de trayectos
 * @author: J Enrique Peñaloza P
 * Date: 22/03/2017
 * Time: 12:50 pm
 */
class reportes_trayectos_Model extends CI_Model {
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
	public function callAPIDriversExcel($urlCountry, $dataRquest)
	{
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
	public function callAPIVehiculosExcel($urlCountry, $dataRquest)
	{
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
	public function callAPICuentasExcel($urlCountry, $dataRquest)
	{
	}
	/**
	 * @Method: callAPIViajesReport
	 * @access public
	 * @params: string $urlCountry
	 * @params: array $dataRquest = ['type', 'status', 'beginDate', 'finalDate']
	 * @info: Método para obtener el reporte de los viajes de una empresa
	 * @autor: J. Enrique Peñaloza
	 * @date: 26/03/2018
	 */
	public function callAPIViajesExcel($urlCountry, $dataRequest)
	{
		log_message('INFO', '[' . $this->userName . '] DATAREQUEST DESCARGA EXCEL VIAES===============>>>>>>>>'. $dataRequest);

		if($dataRequest === 'file') {
			$filename = 'viajes' . date('Ymd-B');date('Ymd-B');
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
	 * @params: array $dataRquest = ['type', 'status', 'beginDate', 'finalDate']
	 * @info: Método para obtener el reporte del detalle de una viaje
	 * @autor: J. Enrique Peñaloza
	 * @date: 26/03/2018
	 */
	public function callAPIViajesPdf($urlCountry, $dataRquest)
	{
		log_message('INFO', '[' . $this->userName . '] DATAREQUEST DESCARGA PDF VIAES===============>>>>>>>>'. $dataRequest);
		//cabecera del REQUEST al API
		$header = [
			'x-country: ' . $this->pais,
			'x-token: ' . $this->token,
			'x-company: ' . $this->company,
			'x-product: ' .$this->idProducto
		];

		//url API
		$urlAPI = '';
		log_message('INFO', 'REQUEST ViajesReport======>>>>>' . $dataRquest);
		return 'Recibido';
	}
}
