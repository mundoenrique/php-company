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
		$this->dataRequest->enterpriseGroup = $this->session->enterpriseInf->enterpriseGroup;
		$this->dataRequest->rif = $this->session->enterpriseInf->idFiscal;
		$this->dataRequest->accodcia = $this->session->enterpriseInf->enterpriseCode;
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
								if(count($value) > 0 && $value[0]->idFilter == '3' && isset($value[0]->listDataSelection)) {
									foreach($value[0]->listDataSelection AS $IdTypeObject) {
										$idType = [];
										$idType['key'] = $IdTypeObject->codData;
										$idType['text'] = $IdTypeObject->description;
										$IdTypeList[] = (object) $idType;
									}
								}

								if(count($value) > 0 && $value[0]->idFilter == '7') {
									$mindateGmfReport = $value[0]->minValue;
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
			$mindateGmfReport = '';
		}

		$this->response->data->reportsList = (object) $reportsList;
		$this->response->data->IdTypeList = (object) $IdTypeList;
		$this->response->data->mindateGmfReport = $mindateGmfReport;
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
			case 'repMovimientoPorTarjeta':
				$this->movementsByCards($dataRequest);
				break;
			case 'repComprobantesVisaVale':
				$this->VISAproofpayment($dataRequest);
				break;
			case 'repExtractoCliente':
				$this->clientStatement($dataRequest);
				break;
			case 'repCertificadoGmf':
				$this->GMPCertificate($dataRequest);
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
			'rif' => $this->session->enterpriseInf->idFiscal,
			'accodcia' => $this->session->enterpriseInf->enterpriseCode
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
			'fechaDesde' => convertDate($dataRequest->enterpriseDateBegin),
			'fechaHasta' => convertDate($dataRequest->enterpriseDateEnd)
		];
		$this->dataRequest->empresaCliente = [
			'rif' => $this->session->enterpriseInf->idFiscal,
			'accodcia' => $this->session->enterpriseInf->enterpriseCode
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
			'mes' => $date[0],
			'anio' => $date[1]
		];
		$this->dataRequest->empresaCliente = [
			'rif' => $this->session->enterpriseInf->idFiscal,
			'accodcia' => $this->session->enterpriseInf->enterpriseCode
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
	 * @info Método para obtener el listado de tarjetas de una empresa
	 * @author J. Enrique Peñaloza Piñero
	 * @date Janury 05th, 2020
	 */
	private function clientStatement($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: clientStatement Method Initialized');

		$this->dataAccessLog->function = 'Extracto del cliente';
		$this->dataAccessLog->operation = 'Descargar archivo';

		$this->className = 'ReporteCEOTO.class';
		$date = explode('/', $dataRequest->dateEx);
		$this->dataRequest->extractoEmpresa = [
			'mes' => $date[0],
			'anio' => $date[1],
			'producto' => $this->session->productInf->productPrefix
		];
		$this->dataRequest->empresaCliente = [
			'rif' => $this->session->enterpriseInf->idFiscal,
			'accodcia' => $this->session->enterpriseInf->enterpriseCode
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
			case -423:
				$this->response->icon = lang('GEN_ICON_INFO');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_CLIENT_STATEMENT');
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
		$this->dataRequest->acCodCia = $this->session->enterpriseInf->enterpriseCode;

		$response = $this->sendToService('GetReport: '.$dataRequest->operation);

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$cardsReport = is_array($response) ? $response : array ($response);
				$cardsMove = [];
				foreach ($response as $key => $value) {
					switch ($key) {
						case 'id_ext_per':
							$value = explode('_', $value);
							$cardsMove['idType'] = $value[0];
							$cardsMove['idNumber'] = $value[1];
							break;
						case 'NombreCliente':
							$cardsMove['userName'] = $value;
							break;
						case 'noTarjeta':
							$cardsMove['cardNumber'] = $value;
							break;
						case 'nombre_producto':
							$cardsMove['product'] = $value;
							break;
						case 'fechaAsignacion':
							$cardsMove['createDate'] = $value;
							break;
						case 'fechaExp':
							$cardsMove['Expirydate'] = $value;
							break;
						case 'estatus':
							$cardsMove['currentState'] = $value;
							break;
						case 'fechaRegistro':
							$cardsMove['activeDate'] = $value;
							break;
						case 'bloque':
							$cardsMove['reasonBlock'] = $value;
							break;
						case 'fechaBloqueo':
							$cardsMove['dateBlock'] = $value;
							break;
						case 'saldos':
							$cardsMove['currentBalance'] = $value->actual;
							break;
						case 'fechaUltimoCargue':
							$cardsMove['lastCredit'] = $value;
							break;
						case 'montoUltimoCargue':
							$cardsMove['lastAmoutn'] = $value;
							break;
						case 'gmf':
							$cardsMove['chargeGMF'] = $value;
							break;
					}
				}

				$this->response->data = $cardsMove;
				break;
			case -30:
			case -150:
				$this->response->icon = lang('GEN_ICON_INFO');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_FOUND_CARD');
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
			'id_ext_emp' => $this->session->enterpriseInf->idFiscal,
			'acCodCia' => $this->session->enterpriseInf->enterpriseCode
		];

		$response = $this->sendToService('GetReport: '.$dataRequest->operation);

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$count = 0;
				$cardsPeople = [];
				foreach ($response->lista AS $cardsList) {
					foreach ($cardsList as $key => $value) {
						switch ($key) {
							case 'noTarjeta':
								$cards = [
									'key' => $count,
									'cardMask' => maskString($value, 6, 4)
								];
								array_push(
									$cardsPeople,
									$value
								);

								break;
						}
					}
					$count++;
					$cardsToView[] = $cards;

				}

				if(count($cardsToView) > 1) {
					$cards = [
						'key' => '',
						'cardMask' => 'Selecciona una tarjeta'
					];
					array_unshift(
						$cardsToView,
						$cards
					);
				}

				$this->session->set_flashdata('cardsPeople', $cardsPeople);
				$this->response->data = $cardsToView;
				break;
			case -30:
			case -150:
				$this->response->icon = lang('GEN_ICON_INFO');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_CARDS_PEOPLE');
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
				'noTarjeta' => $this->session->flashdata('cardsPeople')[$dataRequest->cardNumberId],
				'id_ext_per' => $dataRequest->idType.'_'.$dataRequest->idNumber,
				'rif' => $this->session->enterpriseInf->idFiscal,
				'acCodCia' => $this->session->enterpriseInf->enterpriseCode
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
				$this->session->set_flashdata('cardsPeople', $this->session->flashdata('cardsPeople'));
				$this->response->icon = lang('GEN_ICON_INFO');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_MOVES_ENTERPRISE');
				$this->response->data['btn1']['action'] = 'close';
				break;
		}

		return $this->response;
	}
	/**
	 * @info Método para obtener el certificado GMF
	 * @author J. Enrique Peñaloza Piñero
	 * @date March 10th, 2020
	 */
	private function GMPCertificate($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: GMPCertificate Method Initialized');

		$this->dataAccessLog->function = 'Obtener certificado GMF';
		$this->dataAccessLog->operation = 'Descargar archivo';

		$this->className = 'ReporteCEOTO.class';
		$this->dataRequest->certificadoGmf = [
			'anio' => $dataRequest->dateG
		];
		$this->dataRequest->empresaCliente = [
			'rif' => $this->session->enterpriseInf->idFiscal,
			'accodcia' => $this->session->enterpriseInf->enterpriseCode
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
				$this->response->msg = novoLang(lang('REPORTS_NO_GMF_FOR_YEAR'), $dataRequest->dateG);
				$this->response->data['btn1']['action'] = 'close';
				break;
		}

		return $this->response;
	}
	/**
	 * @info Método para obtener la lista de reportes
	 * @author J. Enrique Peñaloza Piñero
	 * @date March 02nd, 2020
	 */
	public function callWs_StatusBulk_Reports($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: StatusBulk Method Initialized');

		$this->className = 'com.novo.objects.TOs.EmpresaTO';

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'Estado de lote';
		$this->dataAccessLog->operation = 'Obtener lista lotes por estado';

		$this->dataRequest->idOperation = 'buscarEstatusLotes';
		$this->dataRequest->acCodCia = $dataRequest->enterpriseCode;
		$this->dataRequest->idProducto = $dataRequest->productCode;
		$this->dataRequest->dtfechorcargaIni = $dataRequest->initialDate;
		$this->dataRequest->dtfechorcargaFin = $dataRequest->finalDate;

		$response = $this->sendToService('callWs_StatusBulk');
		$statusBulkList = [];


		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				foreach($response->lista AS $statusBulk) {
					$record = new stdClass();
					$record->bulkType = ucfirst(mb_strtolower($statusBulk->acnombre));
					$record->bulkNumber = $statusBulk->acnumlote;
					$record->bulkStatus = ucfirst(mb_strtolower($statusBulk->status));
					$record->uploadDate = $statusBulk->dtfechorcarga;
					$record->valueDate = $statusBulk->dtfechorvalor;
					$record->records = $statusBulk->ncantregs;
					$record->amount = $statusBulk->nmonto;

					array_push(
						$statusBulkList,
						$record
					);
				}

			break;
			case -150:
				$this->response->code = 0;
			break;
		}
		$this->response->data['statusBulkList'] = $statusBulkList;

		return $this->responseToTheView('callWs_StatusBulk');
	}
				/**
	 * @info Método para Obtener la posicion de la empresa
	 * @author Diego Acosta García
	 * @date May 21, 2020
	 */
	public function callWs_obtenerIdEmpresa_Reports($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: obtenerIdEmpresa Method Initialized');
		$this->className = 'com.novo.objects.TOs.EmpresaTO';
		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'Id empresa';
		$this->dataAccessLog->operation = 'Obtener id de empresa';
		$this->dataRequest->idOperation = 'buscarIdEmpresa';
		$response =  $dataRequest;

		switch($this->isResponseRc = 0) {
			case 0:
			$user = $response;
			$this->response->data =  (array)$user;
				}

		return $this->response;
	}
		/**
	 * @info Método para obtener la lista de saldos amanecidos
	 * @author Diego Acosta García
	 * @date May 21, 2020
	 */
	public function callWs_closingBudgets_Reports($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: closingBudgets Method Initialized');

		$this->className = 'com.novo.objects.MO.SaldosAmanecidosMO';

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'Estado de lote';
		$this->dataAccessLog->operation = 'Obtener lista lotes por estado';
		$this->dataRequest->idOperation = 'saldosAmanecidos';
		$this->dataRequest->idExtPer = $dataRequest->idExtPer;
		$this->dataRequest->producto =  $dataRequest->producto;
		$this->dataRequest->idExtEmp =  $dataRequest->idExtEmp;
		$this->dataRequest->tamanoPagina = (int) $dataRequest->length;
		$this->dataRequest->paginar = TRUE;
		$this->dataRequest->paginaActual = (int) ($dataRequest->start / 10) + 1;


		$response = $this->sendToService('callWs_closingBudgets');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$user = $response->saldo->lista;
				$this->response->data =  (array)$user;
			break;
			case -150:
				$this->response->code = 1;

			break;
		}

		return $this->responseToTheView('callWs_closingBudgets');
	}
		/**
	 * @info Método para obtener excel de tabla saldos al cierre
	 * @author Diego Acosta García
	 * @date May 21, 2020
	 */
	public function callWs_exportToExcel_Reports($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: exportToExcel Method Initialized');

		$this->className = 'com.novo.objects.MO.SaldosAmanecidosMO';

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'Saldos amanecidos';
		$this->dataAccessLog->operation = 'Obtener excel de tabla';
		$this->dataRequest->idOperation = 'generaArchivoXls';
		$this->dataRequest->producto =  $dataRequest->producto;
		$this->dataRequest->idExtEmp =  $dataRequest->cedula;
		$this->dataRequest->tamanoPagina = $dataRequest->tamPg;
		$this->dataRequest->paginar = $dataRequest->paginar;
		$this->dataRequest->paginaActual = $dataRequest->paginaActual;
		$this->dataRequest->descProd =  $dataRequest->descProd;


		$response = $this->sendToService('callWs_exportToExcel');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$user = $response;
				$this->response->data =  (array)$user;

			break;

			case -3:
				$this->response->code = 4;
				$this->response->icon = lang('GEN_ICON_DANGER');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_BUDGET');
				$this->response->data['btn1']['action'] = 'close';

			break;
		}

		return $this->response;
	}

		/**
	 * @info Método para obtener resultados de cuenta maestra
	 * @author Diego Acosta García
	 * @date May 26, 2020
	 */
	public function callWs_masterAccount_Reports($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: masterAccount Method Initialized');

		$this->className = 'com.novo.objects.MO.DepositosGarantiaMO';
		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'Obtener resultados de busqueda';
		$this->dataAccessLog->operation = 'Cuenta maestra';
		$this->dataRequest->idOperation = 'buscarDepositoGarantia';
		$this->dataRequest->idExtEmp = $dataRequest->idExtEmp;
		$this->dataRequest->fechaIni = $dataRequest->fechaIni;
		$this->dataRequest->fechaFin =  $dataRequest->fechaFin;
		$this->dataRequest->tipoNota =  $dataRequest->tipoNota;
		$this->dataRequest->filtroFecha = $dataRequest->filtroFecha;
		$this->dataRequest->tamanoPagina = $dataRequest->tamanoPagina;
		$this->dataRequest->paginaActual = 1;

		$response = $this->sendToService('callWs_masterAccount');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$user = $response;
				$this->response->data =  (array)$user;
			break;

			case -150:
				$this->response->code = 1;
			break;
		}

		return $this->response;
	}

		/**
	 * @info Método para obtener excel de tabla cuenta maestra
	 * @author Diego Acosta García
	 * @date May 21, 2020
	 */
	public function callWs_exportToExcelMasterAccount_Reports($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: exportToExcelMasterAccount Method Initialized');

		$this->className = 'com.novo.objects.MO.DepositosGarantiaMO';

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'cuenta maestra';
		$this->dataAccessLog->operation = 'Obtener excel de tabla cuenta maestra';
		$this->dataRequest->idOperation = 'generarDepositoGarantia';
		$this->dataRequest->idExtEmp = $dataRequest->idExtEmp;
		$this->dataRequest->fechaIni =  $dataRequest->fechaIni;
		$this->dataRequest->fechaFin =  $dataRequest->fechaFin;
		$this->dataRequest->filtroFecha = $dataRequest->filtroFecha;
		$this->dataRequest->nombreEmpresa = $dataRequest->nombreEmpresa;
		$this->dataRequest->paginaActual = $dataRequest->paginaActual;
		$this->dataRequest->producto =  $this->session->userdata('productInf')->productPrefix;
		$this->dataRequest->tamanoPagina =  $dataRequest->tamanoPagina;


		$response = $this->sendToService('callWs_exportToExcelMasterAccount');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$user = $response;
				$this->response->data =  (array)$user;

			break;

			case -3:
				$this->response->code = 4;
				$this->response->icon = lang('GEN_ICON_DANGER');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_BUDGET');
				$this->response->data['btn1']['action'] = 'close';

			break;
		}

		return $this->response;
	}

		/**
	 * @info Método para obtener excel de tabla cuenta maestra
	 * @author Diego Acosta García
	 * @date May 21, 2020
	 */
	public function callWs_exportToPDFMasterAccount_Reports($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: exportToPDFMasterAccount Method Initialized');

		$this->className = 'com.novo.objects.MO.DepositosGarantiaMO';

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'cuenta maestra';
		$this->dataAccessLog->operation = 'Obtener pdf de tabla cuenta maestra';
		$this->dataRequest->idOperation = 'generarDepositoGarantiaPdf';
		$this->dataRequest->idExtEmp = $dataRequest->idExtEmp;
		$this->dataRequest->fechaIni =  $dataRequest->fechaIni;
		$this->dataRequest->fechaFin =  $dataRequest->fechaFin;
		$this->dataRequest->filtroFecha = $dataRequest->filtroFecha;
		$this->dataRequest->nombreEmpresa = $dataRequest->nombreEmpresa;
		$this->dataRequest->paginaActual = $dataRequest->paginaActual;
		$this->dataRequest->producto =  $this->session->userdata('productInf')->productPrefix;
		$this->dataRequest->tamanoPagina =  $dataRequest->tamanoPagina;


		$response = $this->sendToService('callWs_exportToPDFMasterAccount');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$user = $response;
				$this->response->data =  (array)$user;

			break;

			case -3:
				$this->response->code = 4;
				$this->response->icon = lang('GEN_ICON_DANGER');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_BUDGET');
				$this->response->data['btn1']['action'] = 'close';

			break;
		}

		return $this->response;
	}


			/**
	 * @info Método para obtener excel de tabla cuenta maestra consolidado
	 * @author Diego Acosta García
	 * @date May 21, 2020
	 */
	public function callWs_exportToExcelMasterAccountConsolid_Reports($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: exportToExcelMasterAccountConsolid Method Initialized');

		$this->className = 'com.novo.objects.MO.DepositosGarantiaMO';

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'cuenta maestra';
		$this->dataAccessLog->operation = 'Obtener excel de tabla cuenta maestra';
		$this->dataRequest->idOperation = 'generaArchivoXlsConcil';
		$this->dataRequest->anio = $dataRequest->anio;
		$this->dataRequest->idExtEmp = $dataRequest->idExtEmp;
		$this->dataRequest->fechaIni =  $dataRequest->fechaIni;
		$this->dataRequest->fechaFin =  $dataRequest->fechaFin;
		$this->dataRequest->filtroFecha = $dataRequest->filtroFecha;
		$this->dataRequest->nombreEmpresa = $dataRequest->nombreEmpresa;
		$this->dataRequest->paginaActual = $dataRequest->paginaActual;
		$this->dataRequest->producto =  $this->session->userdata('productInf')->productPrefix;
		$this->dataRequest->tamanoPagina =  $dataRequest->tamanoPagina;


		$response = $this->sendToService('callWs_exportToExcelMasterAccountConsolid');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$user = $response;
				$this->response->data =  (array)$user;

			break;

			case -3:
				$this->response->code = 4;
				$this->response->icon = lang('GEN_ICON_DANGER');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_FILE');
				$this->response->data['btn1']['action'] = 'close';
			break;

			default:
			$this->response->code = 4;
			$this->response->icon = lang('GEN_ICON_DANGER');
			$this->response->title = lang('REPORTS_TITLE');
			$this->response->msg = lang('REPORTS_NO_FILE_CONSOLID');
			$this->response->data['btn1']['action'] = 'close';

		}

		return $this->response;
	}

		/**
	 * @info Método para obtener excel de tabla cuenta maestra consolidado
	 * @author Diego Acosta García
	 * @date May 21, 2020
	 */
	public function callWs_exportToPDFMasterAccountConsolid_Reports($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: exportToPDFMasterAccountConsolid Method Initialized');

		$this->className = 'com.novo.objects.MO.DepositosGarantiaMO';

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'cuenta maestra';
		$this->dataAccessLog->operation = 'Obtener pdf de tabla cuenta maestra consolidado';
		$this->dataRequest->idOperation = 'generaArchivoConcilPdf';
		$this->dataRequest->anio = $dataRequest->anio;
		$this->dataRequest->idExtEmp = $dataRequest->idExtEmp;
		$this->dataRequest->fechaIni =  $dataRequest->fechaIni;
		$this->dataRequest->fechaFin =  $dataRequest->fechaFin;
		$this->dataRequest->filtroFecha = $dataRequest->filtroFecha;
		$this->dataRequest->nombreEmpresa = $dataRequest->nombreEmpresa;
		$this->dataRequest->paginaActual = $dataRequest->paginaActual;
		$this->dataRequest->producto =  $this->session->userdata('productInf')->productPrefix;
		$this->dataRequest->tamanoPagina =  $dataRequest->tamanoPagina;


		$response = $this->sendToService('callWs_exportToPDFMasterAccountConsolid');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$user = $response;
				$this->response->data =  (array)$user;

			break;

			case -3:
				$this->response->code = 4;
				$this->response->icon = lang('GEN_ICON_DANGER');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_BUDGET');
				$this->response->data['btn1']['action'] = 'close';

			break;
		}

		return $this->response;
	}



	public function callWs_CardHolders_Reports($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: CardHolders Method Initialized');
		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'TarjetaHabientes';
		$this->dataAccessLog->operation = 'Lista TarjetaHabientes';
		$this->dataRequest->idOperation = 'getConsultarTarjetaHabientes';
		$this->className = 'com.novo.objects.MO.TarjetaHabientesMO';
		$this->dataRequest->paginaActual = 1;
		$this->dataRequest->tamanoPagina = 10;
		$this->dataRequest->paginar = true;
		$this->dataRequest->rifEmpresa = $dataRequest->enterpriseCode;
		$this->dataRequest->idProducto = $dataRequest->productCode;
		$response = $this->sendToService('callWS_StatusCardHolders');
		$cardHoldersList = [];

    switch($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        foreach($response->listadoTarjetaHabientes AS $cardHolders) {
          $record = new stdClass();
          $record->cardHoldersId = $cardHolders->idExtPer;
          $record->cardHoldersName = ucwords(mb_strtolower($cardHolders->Tarjetahabiente));
          array_push(
            $cardHoldersList,
            $record
          );
        }
      break;
      case -150:
        $this->response->code = 0;
      break;
    }
    $this->response->data['cardHoldersList'] = $cardHoldersList;

    return $this->responseToTheView('callWS_StatusCardHolders');
	}

		/**
	 * @info Método para obtener actividad por ususario
	 * @author Diego Acosta García
	 * @date May 27, 2020
	 */
	public function callWs_userActivity_Reports($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: userActivity Method Initialized');

		$this->className = 'com.novo.objects.MO.ListadoEmpresasMO';
		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'Actividad por usuario';
		$this->dataAccessLog->operation = 'Obtener actividades por usuario';
		$this->dataRequest->idOperation = 'buscarActividadesXUsuario';
		$this->dataRequest->fechaIni =  $dataRequest->fechaIni;
		$this->dataRequest->fechaFin =  $dataRequest->fechaFin;
		$this->dataRequest->acCodCia = $dataRequest->acCodCia;

		$response = $this->sendToService('callWs_userActivity');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$user = $response;
				$this->response->data =  (array)$user;

			break;

			case -3:
				$this->response->code = 4;
				$this->response->icon = lang('GEN_ICON_DANGER');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_BUDGET');
				$this->response->data['btn1']['action'] = 'close';

			break;
		}

		return $this->response;
	}

		/**
	 * @info Método para obtener excel de tabla cuenta maestra
	 * @author Diego Acosta García
	 * @date May 27, 2020
	 */
	public function callWs_exportToExcelUserActivity_Reports($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: exportToExcelUserActivity Method Initialized');

		$this->className = 'com.novo.objects.MO.DepositosGarantiaMO';

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'Actividad por usuario';
		$this->dataAccessLog->operation = 'Obtener actividad por usuario en excel';
		$this->dataRequest->idOperation = 'generarArchivoXlsActividadesXUsuario';
		$this->dataRequest->rifEmpresa = $dataRequest->rifEmpresa;
		$this->dataRequest->fechaIni =  $dataRequest->fechaIni;
		$this->dataRequest->fechaFin =  $dataRequest->fechaFin;
		$this->dataRequest->acCodCia = $dataRequest->acCodCia;

		$response = $this->sendToService('callWs_exportToExcelUserActivity');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$user = $response;
				$this->response->data =  (array)$user;

			break;

			case -3:
				$this->response->code = 4;
				$this->response->icon = lang('GEN_ICON_DANGER');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_BUDGET');
				$this->response->data['btn1']['action'] = 'close';

			break;
		}

		return $this->response;
	}

		/**
	 * @info Método para obtener pdf de tabla cuenta maestra
	 * @author Diego Acosta García
	 * @date May 27, 2020
	 */
	public function callWs_exportToPDFUserActivity_Reports($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: exportToPDFUserActivity Method Initialized');

		$this->className = 'com.novo.objects.MO.DepositosGarantiaMO';

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'Actividad por usuario';
		$this->dataAccessLog->operation = 'Obtener actividad por usuario en pdf';
		$this->dataRequest->idOperation = 'generarPdfActividadesXUsuario';
		$this->dataRequest->rifEmpresa = $dataRequest->rifEmpresa;
		$this->dataRequest->fechaIni =  $dataRequest->fechaIni;
		$this->dataRequest->fechaFin =  $dataRequest->fechaFin;
		$this->dataRequest->acCodCia = $dataRequest->acCodCia;

		$response = $this->sendToService('callWs_exportToPDFUserActivity');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$user = $response;
				$this->response->data =  (array)$user;

			break;
		}

		return $this->response;
	}
}
