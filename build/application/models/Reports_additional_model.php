<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info		Módelo obtener reportes adicionales
 * @date		2018/05/15
 * @author	J. Enrique Peñaloza P.
*/
class Reports_additional_model extends CI_Model {
	protected $pais;
	protected $canal;
	protected $timeLog;
	protected $ip;
	protected $sessionId;
	protected $userName;
	protected $token;
	protected $prefix;
	protected $companyCod;

	public function __construct()
	{
		log_message('INFO', 'NOVO Reports_additional Model Class Initialized');
		$this->sessionId = $this->session->userdata('sessionId');
		$this->userName = $this->session->userdata('userName');
		$this->canal = "ceo";
		$this->ip = $this->input->ip_address();
		$this->timeLog = date('m/d/Y H:i');
		$this->token = $this->session->userdata('token');
		$this->pais = $this->session->userdata('pais');
		$this->prefix = $this->session->userdata('idProductoS');
		$this->companyCod = $this->session->userdata('accodciaS');
		//Agrega lenguajes a utilizar
		$this->lang->load('erroreseol');
	}
	/**
	 * @info		Método para obtener el listado recargas con comisión
	 * @date		2018/05/10
	 * @author	J Enrique Peñaloza
	 * @param  	object $dataRquest{'firstDate', 'lastDate', 'companyCod'}
	 * @return array $response['code', 'title', 'msg', 'css', 'date', 'data']
	*/
	public function callWSReportRecharWithComm($dataRequest = NULL) {
		log_message('INFO', '--[' . $this->userName . '] DATAREQUEST ReportRecharWithComm:' . $dataRequest);
		//Construye log de acceso
		$modulo = 'reportes';
		$function = 'recargasRealizadas';
		$operation = 'recargasRealizadas';
		$rc = 0;
		$className = 'com.novo.objects.TOs.RecargasRealizadasTO';

		$logAcceso = np_hoplite_log($this->sessionId, $this->userName, $this->canal, $modulo, $function, $operation, $rc, $this->ip, $this->timeLog);
		//Parametro adicional log de acceso
		$logAcceso['tipoServicio'] = 'comisionRecargas';

		$datareport = json_decode($dataRequest);
		$month = date('m');
		$year = date('Y');
		$firstDate = date('d/m/Y', mktime(0,0,0, $month, 1, $year)) . ' 00:00';
		$lastDate = date('d/m/Y') . ' 23:59';
		$companyCod = $this->companyCod;
		$currentPage = 1;
		if(!empty($datareport)) {
			$firstDate = $datareport->firstDate  . ' 00:00';;
			$lastDate = $datareport->lastDate . ' 23:59';
			$companyCod = $datareport->company;
		}

		$dataRequest = [
			"idOperation" => $operation,
			"className" => $className,
			"fecha1" => $firstDate,
			"fecha2" => $lastDate,
			"tamanoPagina" => 10,
			"accodcia" => $companyCod,
			"prefijo" => $this->prefix,
			"paginaActual" => $currentPage,
			"logAccesoObject" => $logAcceso,
			"token" => $this->token,
			"pais" => $this->pais
		];

		$dataRequest = json_encode($dataRequest, JSON_UNESCAPED_UNICODE);

		log_message('INFO', '--[' . $this->userName . '] REQUEST recarga/comision: ' . $dataRequest);

		$dataEncry = np_Hoplite_Encryption($dataRequest, 'callWSReportRecharWithComm');
		$dataRequest = json_encode(['bean' => $dataEncry, 'pais' => $this->pais]);
		$response = np_Hoplite_GetWS('eolwebInterfaceWS', $dataRequest);
		$jsonResponse = np_Hoplite_Decrypt($response, 'callWSReportRecharWithComm');

		log_message('INFO', '--[' . $this->userName . '] RESPONSE recarga/comision: ' . $jsonResponse);

		$responseServ = json_decode($jsonResponse);

		$title = lang('SYSTEM_NAME');
		$data = [];
		$firstDate = substr($firstDate, 0, 10);
		$lastDate = substr($lastDate, 0, 10);
		$date = "Recargas desde $firstDate hasta $lastDate";
		$css = 'disabled';
		if(isset($responseServ->rc)) {
			switch($responseServ->rc) {
				case 0:
					$code = 0;
					foreach($responseServ AS $item => $list) {
						if($item === 'recargas') {
							$data = $list;
						}
					}
					$css = 'available';
					break;
				case -150:
					$code = 0;
					break;
				case -29:
				case -61:
					$code = 2;
					$msg = lang('ERROR_(-29)');
					break;
				default:
					$code = 1;
					$title = lang('BREADCRUMB_REPORTES_COMISION');
					$msg = lang('ERROR_RECARGAS');

			}
		} else {
			$code = 2;
			$msg = lang('ERROR_GENERAL');
		}

		if($code === 2) {
			$this->session->sess_destroy();
		}


		return $response = [
			'code' => $code,
			'title' => $title,
			'msg' => isset($msg) ? $msg : '',
			'css' => $css,
			'date' => $date,
			'data' => isset($data)  ? $data : ''
		];
	}
	/**
	 * @info		Método para obtener los archivos xls y pdf de los reportes
	 * @date		2018/05/10
	 * @author	J Enrique Peñaloza
	 * @param  	object $dataRquest{'report', 'firstDate', 'lastDate', 'companyCod'}
	 * @return array $response['code', 'title', 'msg']
	*/
	public function callWSDownloadReport($dataRequest = NULL) {
		log_message('INFO', '--[' . $this->userName . '] DATAREQUEST ReportRecharWithComm:' . $dataRequest);
		//Construye log de acceso
		$datareport = json_decode($dataRequest);
		$report = $datareport->report;
		$modulo = $report === 'comisiones-xls' ? 'generarExcelRecargasRealizadas' : 'generarPdfRecargasRealizadas';
		$function = $modulo;
		$operation = $modulo;
		$rc = 0;
		$className = 'com.novo.objects.TOs.RecargasRealizadasTO';

		$logAcceso = np_hoplite_log($this->sessionId, $this->userName, $this->canal, $modulo, $function, $operation, $rc, $this->ip, $this->timeLog);
		//Parametro adicional log de acceso
		$logAcceso['tipoServicio'] = 'comisionRecargas';

		$month = date('m');
		$year = date('Y');
		$firstDate = date('d/m/Y', mktime(0,0,0, $month, 1, $year)) . ' 00:00';
		$lastDate = date('d/m/Y') . ' 23:59';
		$companyCod = $this->companyCod;
		$currentPage = 1;
		if(!empty($datareport->firstDate)) {
			$firstDate = $datareport->firstDate  . ' 00:00';;
			$lastDate = $datareport->lastDate . ' 23:59';
			$companyCod = $datareport->company;
		}

		$dataRequest = [
			"idOperation" => $operation,
			"className" => $className,
			"fecha1" => $firstDate,
			"fecha2" => $lastDate,
			"tamanoPagina" => 10,
			"accodcia" => $companyCod,
			"prefijo" => $this->prefix,
			"paginaActual" => $currentPage,
			"logAccesoObject" => $logAcceso,
			"token" => $this->token,
			"pais" => $this->pais
		];

		$dataRequest = json_encode($dataRequest, JSON_UNESCAPED_UNICODE);

		log_message('INFO', '--[' . $this->userName . '] REQUEST Descarga archivo: ' . $dataRequest);

		$dataEncry = np_Hoplite_Encryption($dataRequest, 'callWSDownloadReport');
		$dataRequest = json_encode(['bean' => $dataEncry, 'pais' => $this->pais]);
		$response = np_Hoplite_GetWS('eolwebInterfaceWS', $dataRequest);
		$jsonResponse = np_Hoplite_Decrypt($response, 'callWSDownloadReport');
		$responseServ = json_decode($jsonResponse);

		if($responseServ->rc === 0 && $responseServ->archivo != '') {
			$file = $responseServ->archivo;
			unset($responseServ->archivo);
		}

		log_message('INFO', '--[' . $this->userName . '] RESPONSE Descarga archivo: ' . json_encode($responseServ));

		$title = lang('SYSTEM_NAME');

		if(isset($responseServ->rc)) {
			switch($responseServ->rc) {
				case 0:
					$code = 0;
					$fileName = $report . '-' . date('dmY.B');
					$this->session->set_flashdata($fileName, $file);
					$msg = $fileName;
					break;
				case -29:
				case -61:
					$code = 2;
					$msg = lang('ERROR_(-29)');
					break;
				default:
					$code = 1;
					$title = lang('BREADCRUMB_REPORTES_COMISION');
					$msg = lang('ERROR_DESCARGA');

			}
		} else {
			$code = 2;
			$msg = lang('ERROR_GENERAL');
		}

		if($code === 2) {
			$this->session->sess_destroy();
		}


		return $response = [
			'code' => $code,
			'title' => $title,
			'msg' => isset($msg) ? $msg : '',
		];
	}
}
