<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info		Módelo obtener los listados y otros requerimientos comunes
 * @date		2018/05/15
 * @author	J. Enrique Peñaloza P.
*/
class Lists_and_requirements_model extends CI_Model {
	protected $pais;
	protected $canal;
	protected $timeLog;
	protected $ip;
	protected $sessionId;
	protected $userName;
	protected $token;

	public function __construct()
	{
		log_message('INFO', 'NOVO Lists_and_requirements Model Class Initialized');
		$this->sessionId = $this->session->userdata('sessionId');
		$this->userName = $this->session->userdata('userName');
		$this->canal = "ceo";
		$this->ip = $this->input->ip_address();
		$this->timeLog = date('m/d/Y H:i');
		$this->token = $this->session->userdata('token');
		$this->pais = $this->session->userdata('pais');
		//Agrega lenguajes
		$this->lang->load('dashboard');
		$this->lang->load('reportes');
		$this->lang->load('erroreseol');

	}
	/**
	 * @info		Método que obtiene el listado de empresas filtrado segun parámetro de busqueda
	 * @date		2018/05/15
	 * @author	J Enrique Peñaloza
	 * @param  	string $paginar
	 * @param  	string $tamanoPagina
	 * @param  	string $paginaActual
	 * @param  	string $filtroEmpresas
	 * @return 	array $companyList[]
	*/
	public function callWSListaEmpresasPaginar($paginar, $tamanoPagina, $paginaActual, $filtroEmpresas) {

		$modulo = 'listaEmpresas';
		$function = 'listarEmpreas';
		$operation = 'getPaginar';
		$className = 'com.novo.objects.MO.ListadoEmpresasMO';

		$logAcceso = np_hoplite_log($this->sessionId, $this->userName, $this->canal, $modulo, $function, $operation, 0, $this->ip, $this->timeLog);

		$dataRequest = [
			"idOperation" => $operation,
			"className" => $className,
			"accodusuario" => $this->userName,
			"paginaActual" => $paginaActual,
			"tamanoPagina" => $tamanoPagina,
			"paginar" => $paginar,
			"filtroEmpresas" => $filtroEmpresas,
			"logAccesoObject" => $logAcceso,
			"token" => $this->token,
			"pais" => $this->pais
		];

		$dataRequest = json_encode($dataRequest, JSON_UNESCAPED_UNICODE);

		log_message('INFO', '--[' . $this->userName . '] REQUEST Lista de empresas: ' . $dataRequest);

		$dataEncry = np_Hoplite_Encryption($dataRequest, 'callWSListaEmpresasPaginar');
		$dataRequest = json_encode(['bean' => $dataEncry, 'pais' => $this->pais]);
		$response = np_Hoplite_GetWS('eolwebInterfaceWS', $dataRequest);
		$jsonResponse = np_Hoplite_Decrypt($response, 'callWSListaEmpresasPaginar');
		log_message('INFO', '--[' . $this->userName . '] RESPONSE Lista de empresas: '. $jsonResponse);
		$responseSerV = json_decode($jsonResponse);

		$title = lang('SYSTEM_NAME');

		if(isset($responseSerV->rc)) {
			switch($responseSerV->rc) {
				case 0:
					$code = 0;
					foreach($responseSerV AS $item => $list) {
						if($item === 'lista') {
							foreach($list AS $pos => $obj) {
								$companyList[$obj->accodcia] = $obj->acnomcia;
							}
						}
					}
					$data = $companyList;
					break;
				case -29:
				case -61:
					$code = 2;
					$msg = lang('ERROR_(-29)');
					break;
				default:
					$code = 1;
					$title = lang('BREADCRUMB_REPORTES_COMISION');
					$msg = lang('ERROR_EMPRESAS');
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
			'data' => isset($data)  ? $data : ''
		];
	}
	/**
	 * @info		Método para la descarga de los archivos de reporte
	 * @date		2018/05/15
	 * @author	J Enrique Peñaloza
	 * @param  	string $dataRquest(Nombre del archivo)
	 * @return file
	*/
	public function callWSdownloadFile($dataRequest)
	{
		log_message('INFO', '--[' . $this->userName . '] REQUEST download file: ' . $dataRequest);

		$flashdataName = $dataRequest;
		$dataFile = explode('-', $dataRequest);
		$ext = $dataFile[1];
		$filename = $dataFile[0] . $dataFile[2];
		log_message('INFO', '--[' . $this->userName . '] FileName: ' . $filename . ' FileExt: ' . $ext);
		$file = $this->session->flashdata($flashdataName);
		np_hoplite_byteArrayToFile($file, $ext, $filename, TRUE);
	}
}
