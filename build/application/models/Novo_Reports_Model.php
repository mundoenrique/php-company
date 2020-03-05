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
	 * @info Método para obtener la lista de reportes
	 * @author J. Enrique Peñaloza Piñero
	 * @date March 02nd, 2020
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
	/**
	 * @info Método para obtener un reporte selecionado por el usuario
	 * @author J. Enrique Peñaloza Piñero
	 * @date Janury 04th, 2020
	 */
	public function callWs_GetReport_Reports($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: GetReport Method Initialized');

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataRequest->idOperation = $dataRequest->operation;

		switch ($dataRequest->operation) {
			case 'repListadoTarjetas':
				$this->ListadoTarjetas($dataRequest);
			break;
			case 'repMovimientoPorEmpresa':
				$this->className = 'ReporteCEOTO.class';
			break;
			case 'repComprobantesVisaVale':
				$this->className = 'ReporteCEOTO.class';
			break;
			case 'repTarjeta':
				$this->className = 'TarjetaTO.class';
			break;
			case 'repTarjetasPorPersona':
				$this->className = 'TarjetahabienteTO.class';
			break;
		}

		$this->dataRequest->rutaArchivo = DOWNLOAD_ROUTE;

		$response = $this->sendToService('GetReport: '.$dataRequest->operation);

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				break;
		}
		return $this->responseToTheView('GetReport: '.$dataRequest->operation);
	}
	/**
	 * @info Método para obtener el listado de tarjetas de una empresa
	 * @author J. Enrique Peñaloza Piñero
	 * @date Janury 05th, 2020
	 */
	private function ListadoTarjetas($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: repListadoTarjetas Method Initialized');

		$this->dataAccessLog->function = 'Listado de tarjetas';
		$this->dataAccessLog->operation = 'Descargar archivo';

		$this->className = 'ReporteCEOTO.class';
		$this->dataRequest->empresaCliente = [
			'rif' => $this->session->enterpriseInf->idFiscal
		];

	}
}
