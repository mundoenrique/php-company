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
		$this->dataRequest->enterpriseCode = $this->session->enterpriseInf->enterpriseCode;
		$this->dataRequest->enterpriseGroup = $this->session->enterpriseInf->enterpriseGroup;
		$this->dataRequest->rif = $this->session->enterpriseInf->idFiscal;
		$this->dataRequest->nombre = $this->session->enterpriseInf->enterpriseName;

		$response = $this->sendToService('GetReportsList');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$reportsList[] = (object) [
					'key' => '',
					'text' => 'Selecciona un reporte'
				];

				foreach ($response->listaConfigReportesCEO AS $index => $reports) {
					$report = [];
					foreach ($reports AS $key => $value) {
						switch ($key) {
							case 'idOperation':
								$report['key'] = $value;
								break;
							case 'description':
								$report['text'] = $value;
								break;
							case 'result':
								$report['type'] = $value;
								if(count($reports->listFilter) > 0 && $value === 'DOWNLOAD') {
									$report['type'] = 'FILTER';
								}
								break;
						}
					}
					$reportsList[] = (object) $report;
				}
				break;
		}

		if($this->isResponseRc != 0) {
			$reportsList[] = (object) [
				'key' => '',
				'text' => lang('RESP_TRY_AGAIN')
			];
		}

		$this->response->data->reportsList = (object) $reportsList;
		return $this->responseToTheView('GetReportsList');
	}
}
