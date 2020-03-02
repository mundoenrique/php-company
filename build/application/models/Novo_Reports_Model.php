<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info clase para obtener información relacionada con los reportes
 * @author
 *
 */
class Novo_Reports_Model extends NOVO_Model {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO Reports Model Class Initialized');
	}
	/**
	 * @info Método para obtener las ordenes de servicio
	 * @author J. Enrique Peñaloza Piñero
	 * @date Janury 09th, 2019
	 */
	public function callWs_GetReportsList_Reports()
	{
		log_message('INFO', 'NOVO Reports Model: ReporstList Method Initialized');
		$this->className = 'com.novo.objects.TOs.EmpresaTO';

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'Lista de reportes';
		$this->dataAccessLog->operation = 'Obtener lista de reportes';

		$this->dataRequest->idOperation = 'listadoReportesCEO';
		$this->dataRequest->empresa = $this->session->enterpriseInf;

		$response = $this->sendToService('ServiceOrderStatus');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				log_message('DEBUG', 'NOVO ['.$this->userName.'] RESPONSE: ReporstList: ' . json_encode($response));

				break;
		}

		return $this->responseToTheView('ServiceOrderStatus');
	}
}
