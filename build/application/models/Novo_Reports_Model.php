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
		$headerCardsRep = [];

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$reportsList[] = (object) [
					'key' => '',
					'text' => 'Selecciona un reporte'
				];
				$IdTypeList[] = (object) [
					'key' => '',
					'text' => 'Selecciona el tipo de identificación'
				];

				foreach ($response->listaConfigReportesCEO AS $reports) {
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
							case 'listFilter':
								if(count($value) > 0 && isset($value[0]->listDataSelection)) {
									foreach($value[0]->listDataSelection AS $IdTypeObject) {
										$idType = [];
										$idType['key'] = $IdTypeObject->codData;
										$idType['text'] = $IdTypeObject->description;
										$IdTypeList[] = (object) $idType;
									}
								}
								break;
							case 'listTableHeader':
								if(count($value) > 0 && $reports->idReporte == '5') {
									foreach($value AS $tableHeader) {
										$headerCardsRep[] = $tableHeader->description;
									}
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
			$IdTypeList[] = (object) [
				'key' => '',
				'text' => lang('RESP_TRY_AGAIN')
			];
		}

		$this->response->data->reportsList = (object) $reportsList;
		$this->response->data->IdTypeList = (object) $IdTypeList;
		$this->response->data->headerCardsRep = $headerCardsRep;
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
		$this->dataRequest->rutaArchivo = DOWNLOAD_ROUTE;

		switch ($dataRequest->operation) {
			case 'repListadoTarjetas':
				$this->cardsList($dataRequest);
				break;
			case 'repMovimientoPorEmpresa':
				$this->movementsByEnterprise($dataRequest);
			break;
			case 'repTarjeta':
				$this->cardReport($dataRequest);
			break;
			case 'repTarjetasPorPersona':
				$this->cardsPeople($dataRequest);
			break;
			case 'repComprobantesVisaVale':
				$this->VISAproofpayment($dataRequest);
				break;
		}



		return $this->responseToTheView('GetReport: '.$dataRequest->operation);
	}
	/**
	 * @info Método para obtener el listado de tarjetas de una empresa
	 * @author J. Enrique Peñaloza Piñero
	 * @date Janury 05th, 2020
	 */
	private function cardsList($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: repListadoTarjetas Method Initialized');

		$this->dataAccessLog->function = 'Listado de tarjetas';
		$this->dataAccessLog->operation = 'Descargar archivo';

		$this->className = 'ReporteCEOTO.class';
		$this->dataRequest->empresaCliente = [
			'rif' => $this->session->enterpriseInf->idFiscal
		];

		$response = $this->sendToService('GetReport: '.$dataRequest->operation);

		switch($this->isResponseRc) {
			case 0:
				$this->response->icon = lang('GEN_ICON_DANGER');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_FILE_EXIST');
				$this->response->data['btn1']['action'] = 'close';

				if(file_exists(assetPath('downloads/'.$response->bean))) {
					$this->response->code = 0;
					$this->response->msg = lang('RESP_RC_0');
					$this->response->data = [
						'file' => assetUrl('downloads/'.$response->bean),
						'name' => $response->bean
					];
				}
				break;
			case -30:
				$this->response->icon = lang('GEN_ICON_INFO');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_CARDS');
				$this->response->data['btn1']['action'] = 'close';
				break;
		}

		return $this->response;
	}
	/**
	 * @info Método para obtener el de movimientos por empresa
	 * @author J. Enrique Peñaloza Piñero
	 * @date Janury 08th, 2020
	 */
	private function movementsByEnterprise($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: movementsByEnterprise Method Initialized');

		$this->dataAccessLog->function = 'Moviminetos por empresa';
		$this->dataAccessLog->operation = 'Descargar archivo';

		$this->className = 'ReporteCEOTO.class';

		$this->dataRequest->movPorEmpresa = [
			'empresa' => [
				'rif' => $this->session->enterpriseInf->idFiscal
			],
			'fechaDesde' => convertDate($dataRequest->enterpriseDateBegin),
			'fechaHasta' => convertDate($dataRequest->enterpriseDateEnd)
		];

		$response = $this->sendToService('GetReport: '.$dataRequest->operation);

		switch($this->isResponseRc) {
			case 0:
				$this->response->icon = lang('GEN_ICON_DANGER');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_FILE_EXIST');
				$this->response->data['btn1']['action'] = 'close';

				if(file_exists(assetPath('downloads/'.$response->bean))) {
					$this->response->code = 0;
					$this->response->msg = lang('RESP_RC_0');
					$this->response->data = [
						'file' => assetUrl('downloads/'.$response->bean),
						'name' => $response->bean
					];
				}
				break;
			case -30:
				$this->response->icon = lang('GEN_ICON_INFO');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_MOVES_ENTERPRISE');
				$this->response->data['btn1']['action'] = 'close';
				break;
		}

		return $this->response;
	}
	/**
	 * @info Método para obtener el listado de tarjetas de una empresa
	 * @author J. Enrique Peñaloza Piñero
	 * @date Janury 05th, 2020
	 */
	private function VISAproofpayment($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: VISAproofpayment Method Initialized');

		$this->dataAccessLog->function = 'Comprobante de pago VISA';
		$this->dataAccessLog->operation = 'Descargar archivo';

		$this->className = 'ReporteCEOTO.class';
		$date = explode('/', $dataRequest->date);
		$this->dataRequest->movPorEmpresa = [
			'empresa' => [
				'rif' => $this->session->enterpriseInf->idFiscal
			],
			'mes' => $date[0],
			'anio' => $date[1]
		];

		$response = $this->sendToService('GetReport: '.$dataRequest->operation);

		switch($this->isResponseRc) {
			case 0:
				$this->response->icon = lang('GEN_ICON_DANGER');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_FILE_EXIST');
				$this->response->data['btn1']['action'] = 'close';

				if(file_exists(assetPath('downloads/'.$response->bean))) {
					$this->response->code = 0;
					$this->response->msg = lang('RESP_RC_0');
					$this->response->data = [
						'file' => assetUrl('downloads/'.$response->bean),
						'name' => $response->bean
					];
				}
				break;
			case -30:
			case -150:
				$this->response->icon = lang('GEN_ICON_INFO');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_MOVES_ENTERPRISE');
				$this->response->data['btn1']['action'] = 'close';
				break;
		}

		return $this->response;
	}
	/**
	 * @info Método para obtener el detalle de una tarjeta
	 * @author J. Enrique Peñaloza Piñero
	 * @date March 10th, 2020
	 */
	private function cardReport($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: cardReport Method Initialized');

		$this->dataAccessLog->function = 'Reporte de tarjetas';
		$this->dataAccessLog->operation = 'Lista de tarjetas';

		$this->className = 'TarjetaTO.class';

		$this->dataRequest->noTarjeta = $dataRequest->cardNumber;
		$this->dataRequest->rif = $this->session->enterpriseInf->idFiscal;


		$response = $this->sendToService('GetReport: '.$dataRequest->operation);

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;

				break;
			case -30:
			case -150:
				$this->response->icon = lang('GEN_ICON_INFO');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_MOVES_ENTERPRISE');
				$this->response->data['btn1']['action'] = 'close';
				break;
		}

		return $this->response;
	}
	/**
	 * @info Método para obtener el listado de tarjetas por persona
	 * @author J. Enrique Peñaloza Piñero
	 * @date March 10th, 2020
	 */
	private function cardsPeople($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: cardsPeople Method Initialized');

		$this->dataAccessLog->function = 'Tarjetas por persona';
		$this->dataAccessLog->operation = 'Lista de tarjetas';

		$this->className = 'ReporteCEOTO.class';

		$this->dataRequest->tarjetaHabiente = [
			'id_ext_per' => $dataRequest->idType.'_'.$dataRequest->idNumber,
			'id_ext_emp' => $this->session->enterpriseInf->idFiscal
		];

		$response = $this->sendToService('GetReport: '.$dataRequest->operation);

		switch($this->isResponseRc) {
			case 0:
				$this->response->icon = lang('GEN_ICON_DANGER');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_FILE_EXIST');
				$this->response->data['btn1']['action'] = 'close';

				if(file_exists(assetPath('downloads/'.$response->bean))) {
					$this->response->code = 0;
					$this->response->msg = lang('RESP_RC_0');
					$this->response->data = [
						'file' => assetUrl('downloads/'.$response->bean),
						'name' => $response->bean
					];
				}
				break;
			case -30:
			case -150:
				$this->response->icon = lang('GEN_ICON_INFO');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_MOVES_ENTERPRISE');
				$this->response->data['btn1']['action'] = 'close';
				break;
		}

		return $this->response;
	}
	/**
	 * @info Método para obtener el listado de tarjetas por persona
	 * @author J. Enrique Peñaloza Piñero
	 * @date March 10th, 2020
	 */
	private function movementsByCards($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: movementsByCards Method Initialized');

		$this->dataAccessLog->function = 'Movimientos por tarjeta';
		$this->dataAccessLog->operation = 'Descargar archivo';

		$this->className = 'ReporteCEOTO.class';

		$this->dataRequest->movTarjeta = [
			'tarjeta' => [
				'noTarjeta' => $dataRequest->cardNumberId,
				'id_ext_per' => $dataRequest->idType.'_'.$dataRequest->idNumber,
				'rif' => $this->session->enterpriseInf->idFiscal
			],
			'fechaInicio' => convertDate($dataRequest->peopleDateBegin),
			'fechaFin' => convertDate($dataRequest->peopleDateEnd)
		];

		$response = $this->sendToService('GetReport: '.$dataRequest->operation);

		switch($this->isResponseRc) {
			case 0:
				$this->response->icon = lang('GEN_ICON_DANGER');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_FILE_EXIST');
				$this->response->data['btn1']['action'] = 'close';

				if(file_exists(assetPath('downloads/'.$response->bean))) {
					$this->response->code = 0;
					$this->response->msg = lang('RESP_RC_0');
					$this->response->data = [
						'file' => assetUrl('downloads/'.$response->bean),
						'name' => $response->bean
					];
				}
				break;
			case -30:
			case -150:
				$this->response->icon = lang('GEN_ICON_INFO');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_MOVES_ENTERPRISE');
				$this->response->data['btn1']['action'] = 'close';
				break;
		}

		return $this->response;
	}
}
