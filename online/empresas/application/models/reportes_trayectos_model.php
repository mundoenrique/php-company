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
		$this->company = $this->session->userdata('acrifS');
		$this->idProducto = $this->session->userdata('idProductoS');
		$this->userName = $this->session->userdata('userName');
		log_message('INFO', 'TOKEN------------------------------' . $this->token);		//Agrega lenguajes
		$this->lang->load('dashboard');
		$this->lang->load('combustible');
		$this->lang->load('users');
		$this->lang->load('erroreseol');
	}
	/**
	 * @Method: callAPIConductoresExcel
	 * @access public
	 * @params: string $urlCountry
	 * @params: array $dataRequest = ['status']
	 * @info: Método para obtener el reporte del detalle de los conductores
	 * @autor: Edinson Cabello
	 * @date: 27/03/2018
	 */
	public function callAPIConductoresExcel($urlCountry, $dataRequest)
	{
		log_message('INFO', '[' . $this->userName . '] DATAREQUEST descarga EXCEL Conductores===============>>>>>>>>'. $dataRequest);

		//cabecera del REQUEST al API
		$header = [
			'x-country: ' . $this->pais,
			'x-token: ' . $this->token,
			'x-company: ' . $this->company
		];

		$dataReport = json_decode($dataRequest);
		$status = $dataReport->status;
		log_message('INFO', 'ESTATUS DEL CONDUCTOR ---------------> ' . $status);

		//url API
		$urlAPI = 'driver/report/excel';
		if ($status !== '') {

			$urlAPI.='?status='.$status;
		}

		$headerAPI = $header;
		$bodyAPI = '';
		$method = 'GET';

		log_message('INFO', '[' . $this->userName . '] REQUEST ConductoresReport======>>>>>' . $urlAPI);

		$jsonResponse = GetAPIServ($urlAPI, $headerAPI, $bodyAPI, $method);

		$httpCode = $jsonResponse->httpCode;
		$resAPILog = $httpCode != 200 ? $jsonResponse->resAPI : '';
		$resAPI = $jsonResponse->resAPI;

		log_message('INFO',  '[' . $this->userName . '] RESPONSE ConductoresReport: ==>> httpCode: ' . $httpCode . ' $resAPI: ' . $resAPILog);

		//$httpCode = 400;
		if($httpCode === 200) {
			$this->session->set_flashdata('ConductoresExcel', $resAPI);
		}

		$code = '';
		$title = '';
		$msg = '';

		switch($httpCode) {
			case 200:
				$code = 0;
				break;
			case 404:
				$code = 1;
				$title = lang('TAG_REPORTE_DRIVERS');
				$statusId = [
					'1'=>lang('VEHI_ACTIVE'),
					'0'=>lang('VEHI_INACTIVE'),
				];
				$msg = lang('ERROR_DRIVERS').' '.$statusId[$status];
				break;
			default:
				$code = 1;
				$title = lang('TAG_REPORTE_DRIVERS');
				$msg = lang('ERROR_REPORT');
		}

		return $response = [
			'code' => $code,
			'title' => $title,
			'msg' => $msg
		];
	}
	/**
	 * @Method: callAPIConductoresExcel
	 * @access public
	 * @params: string $urlCountry
	 * @params: array $dataRequest = ['status']
	 * @info: Método para obtener el reporte del estatus de los vehiculos
	 * @autor: Humberto Zapata
	 * @date: 26/04/2018
	 */
	public function callAPIVehiculosExcel($urlCountry, $dataRequest)
	{
		log_message('INFO', '['.$this->userName.'] DATAREQUEST descarga EXCEL Vehiculos --->>> '.$dataRequest);

		//cabecera del REQUEST al API
		$header = [
			'x-country: ' . $this->pais,
			'x-token: ' . $this->token,
			'x-company: ' . $this->company
		];

		$dataReport = json_decode($dataRequest);
		$status = $dataReport->status;

		//URL API
		$urlAPI = 'fleet/report/excel';
		if ($status !== '') {
			$statusId = [
				'ACTIVE'=>1,
				'BUSY'=>2,
				'GARAGE'=>3,
				'DISASSOCIATE'=>5,
			];
			$urlAPI.='?status='.$statusId[$status];
		}

		$headerAPI = $header;
		$bodyAPI = '';
		$method = 'GET';

		log_message('INFO', '['.$this->userName.'] REQUEST Vehicles Report ==>> '.$urlAPI);

		$jsonResponse = GetAPIServ($urlAPI, $headerAPI, $bodyAPI, $method);

		$httpCode = $jsonResponse->httpCode;
		$resAPILog = $httpCode != 200 ? $jsonResponse->resAPI : '';
		$resAPI = $jsonResponse->resAPI;

		log_message('INFO',  '[' . $this->userName . '] RESPONSE VehiclesReport: ==>> httpCode: ' . $httpCode . ' $resAPI: ' . $resAPILog);

		//$httpCode = 400;
		if($httpCode === 200) {
			$this->session->set_flashdata('VehiculosExcel', $resAPI);
		}

		$code = '';
		$title = '';
		$msg = '';

		switch($httpCode) {
			case 200:
				$code = 0;
				break;
			case 404:
				$code = 1;
				$title = lang('TAG_REPORTE_VEHICLES');
				$statusId = [
					'ACTIVE'=>'Disponibles',
					'BUSY'=>'Ocupados',
					'GARAGE'=>'En Garage',
					'DISASSOCIATE'=>'Desincorporados',
				];
				$msg = lang('ERROR_VEHICLES').' '.$statusId[$status];
				break;
			default:
				$code = 1;
				$title = lang('TAG_REPORTE_VEHICLES');
				$msg = lang('ERROR_REPORT');
		}

		return $response = [
			'code' => $code,
			'title' => $title,
			'msg' => $msg
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
			$this->session->set_flashdata('CuentasExcel', $resAPI);
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

		//cabecera del REQUEST al API
		$header = [
			'x-country: ' . $this->pais,
			'x-token: ' . $this->token,
			'x-company: ' . $this->company
		];

		$dataReport = json_decode($dataRequest);
		$type = $dataReport->type;
		$date = explode('/', $dataReport->beginDate);
		$beginDate = $date[0] . '-' . $date[1] . '-' . $date[2];
		$date = explode('/', $dataReport->finalDate);
		$finalDate = $date[0] . '-' . $date[1] . '-' . $date[2];

		//url API
		$urlAPI = 'travel/report/excel?from=' . $beginDate . '&to=' . $finalDate;

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

			$urlAPI = 'travel/report/excel?quantity=30';
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
			$this->session->set_flashdata('ViajesExcel', $resAPI);
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
			$this->session->set_flashdata('ViajesPdf', $resAPI);
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
	 * @Method: callAPIdwonloadFile
	 * @access public
	 * @params: string $urlCountry
	 * @params: string $dataRquest
	 * @info: Método para la descarga de los archivos de reporte
	 * @autor: J. Enrique Peñaloza
	 * @date: 25/04/2018
	 */
	public function callAPIdownloadFile($urlCountry, $dataRequest)
	{
		log_message('INFO',  '[' . $this->userName . '] REQUEST download file:---- ' . $dataRequest);
		$dataFile = explode(',', $dataRequest);
		$flashData = $dataFile[0];
		$dataFile = explode('-', $dataFile[1]);
		$filename = $dataFile[0] . date('dmY-B');
		$ext = $dataFile[1];
		log_message('INFO',  '[' . $this->userName . '] download file Nombre:----- ' . $filename . ' Extensión:----- ' . $ext);
		$file = $this->session->flashdata($flashData);
		np_hoplite_byteArrayToFile($file, $ext, $filename, FALSE);

	}

}
