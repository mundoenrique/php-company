<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @info Modelo para comunicacón con el servicio de cunsultas adicionales
 * @date 2018/08/09
 * @author J. Enrique Peñaloza P.
 * @package models
 */
class additional_inquiries_model extends CI_Model {
	//Atributos de clase
	protected $country;
	protected $token;
	protected $company;
	protected $idProduct;
	protected $userName;
	protected $canal;
	protected $timeLog;
	protected $ip;
	protected $sessionId;
	protected $prefix;
	protected $companyCod;

	public function __construct()
	{
		$this->country = $this->session->userdata('pais');
		$this->token = $this->session->userdata('token');
		$this->company = $this->session->userdata('acrifS');
		$this->idProduct = $this->session->userdata('idProductoS');
		$this->userName = $this->session->userdata('userName');
		$this->canal = "ceo";
		$this->timeLog = date('m/d/Y H:i');
		$this->ip = $this->input->ip_address();
		$this->sessionId = $this->session->userdata('sessionId');
		$this->prefix = $this->session->userdata('idProductoS');
		$this->companyCod = $this->session->userdata('accodciaS');
		//Agrega lenguajes
		$this->lang->load('dashboard');
		$this->lang->load('users');
		$this->lang->load('consultas');
		$this->lang->load('erroreseol');
	}
	/**
	 * @info Metodo para la conección con el servicio de lotes por facturar
	 * @date 2018/08/09
	 * @author J. Enrique Peñaloza P.
	 */
	public function callWsGetBatchesByInvoice($report = NULL)
	{
		//Construye log de acceso
		$modulo = 'Reportes';
		$function = 'Lotes por Facturar';
		$operation = 'Visualizar Lotes por Facturar';
		$rc = 0;
		$className = 'com.novo.objects.TOs.OrdenServicioTO';

		$logAcceso = np_hoplite_log($this->sessionId, $this->userName, $this->canal, $modulo, $function, $operation, $rc, $this->ip, $this->timeLog);

		$dataRequest = [
			"idOperation" => !$report ? "lotesPorFacturar" : "lotesPorFacturarXLS",
			"className" => $className,
			"acprefix" => $this->prefix,
			"datosEmpresa" => [
				"acrif" => $this->company
			],
			"logAccesoObject" => $logAcceso,
			"token" => $this->token,
			"pais" => $this->country
		];

		$dataRequest = json_encode($dataRequest, JSON_UNESCAPED_UNICODE);

		log_message('DEBUG', '--[' . $this->userName . '] REQUEST LOTES POR FACTURAR: ' . $dataRequest);

		$encryptionData = np_Hoplite_Encryption($dataRequest);
		$dataRequest = json_encode(["bean" => $encryptionData, "pais" => $this->country]);
		$response = np_Hoplite_GetWS('eolwebInterfaceWS', $dataRequest);
		$jsonResponse = np_Hoplite_Decrypt($response);

		log_message('INFO', '--[' . $this->userName . '] RESPONSE LOTES POR FACTURAR: ' . $jsonResponse);

		$responseServ = json_decode($jsonResponse);

		$title = lang('SYSTEM_NAME');
		$data = [];
		if(isset($responseServ->rc)) {
			switch($responseServ->rc) {
				case 0:
					$code = 0;
					if(!$report) {
						$data = (array)$responseServ->listaLotes;
					} else {
						$msg = [
							'url' => $this->config->item('base_url_cdn').'downloads/reports/',
							'file' => $responseServ->nombre
						];
					}
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
					$code = !$report ? 1 : 3;
					$title = lang('TITULO_LOTES_POR_FACTURAR');
					$msg = !$report ? lang('ERROR_GET_LIST') : lang('ERROR_(-137)');

			}
		} else {
			$code = 1;
			$msg = sprintf(lang('ERROR_GENERAL'), 'la lista de lotes por facturar');
		}

		if($code === 2) {
			$this->session->sess_destroy();
			$this->session->unset_userdata($this->session->all_userdata());
		}

		return $response = [
			'code' => $code,
			'title' => $title,
			'msg' => isset($msg) ? $msg : '',
			'data' => isset($data)  ? $data : ''
		];


	}
}
