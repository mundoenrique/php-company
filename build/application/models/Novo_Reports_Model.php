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

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'Lista de reportes';
		$this->dataAccessLog->operation = 'Obtener lista de reportes';

		$this->dataRequest->idOperation = 'listadoReportesCEO';
		$this->dataRequest->className = 'com.novo.objects.TOs.EmpresaTO';
		$this->dataRequest->enterpriseGroup = $this->session->enterpriseInf->enterpriseGroup;
		$this->dataRequest->rif = $this->session->enterpriseInf->idFiscal;
		$this->dataRequest->accodcia = $this->session->enterpriseInf->enterpriseCode;
		$this->dataRequest->nombre = $this->session->enterpriseInf->enterpriseName;

		$response = $this->sendToService('callWs_GetReportsList');
		$headerCardsRep = [];

		switch ($this->isResponseRc) {
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

		return $this->responseToTheView('callWs_GetReportsList');
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
		$this->dataRequest->className = 'ReporteCEOTO.class';
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

		$this->dataRequest->empresaCliente = [
			'rif' => $this->session->enterpriseInf->idFiscal,
			'accodcia' => $this->session->enterpriseInf->enterpriseCode
		];

		$response = $this->sendToService('GetReport: '.$dataRequest->operation);

		switch ($this->isResponseRc) {
			case 0:
				$this->response->icon = lang('CONF_ICON_DANGER');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_FILE_EXIST');
				$this->response->data['btn1']['action'] = 'close';

				if(file_exists(assetPath('downloads/'.$response->bean))) {
					$this->response->code = 0;
					$this->response->msg = lang('GEN_RC_0');
					$this->response->data = [
						'file' => assetUrl('downloads/'.$response->bean),
						'name' => $response->bean
					];
				}
			break;
			case -30:
				$this->response->icon = lang('CONF_ICON_INFO');
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

		$this->dataRequest->movPorEmpresa = [
			'fechaDesde' => convertDate($dataRequest->enterpriseDateBegin),
			'fechaHasta' => convertDate($dataRequest->enterpriseDateEnd)
		];
		$this->dataRequest->empresaCliente = [
			'rif' => $this->session->enterpriseInf->idFiscal,
			'accodcia' => $this->session->enterpriseInf->enterpriseCode
		];

		$response = $this->sendToService('GetReport: '.$dataRequest->operation);

		switch ($this->isResponseRc) {
			case 0:
				$this->response->icon = lang('CONF_ICON_DANGER');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_FILE_EXIST');
				$this->response->data['btn1']['action'] = 'close';

				if(file_exists(assetPath('downloads/'.$response->bean))) {
					$this->response->code = 0;
					$this->response->msg = lang('GEN_RC_0');
					$this->response->data = [
						'file' => assetUrl('downloads/'.$response->bean),
						'name' => $response->bean
					];
				}
			break;
			case -30:
				$this->response->icon = lang('CONF_ICON_INFO');
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

		switch ($this->isResponseRc) {
			case 0:
				$this->response->icon = lang('CONF_ICON_DANGER');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_FILE_EXIST');
				$this->response->data['btn1']['action'] = 'close';

				if(file_exists(assetPath('downloads/'.$response->bean))) {
					$this->response->code = 0;
					$this->response->msg = lang('GEN_RC_0');
					$this->response->data = [
						'file' => assetUrl('downloads/'.$response->bean),
						'name' => $response->bean
					];
				}
			break;
			case -30:
			case -150:
				$this->response->icon = lang('CONF_ICON_INFO');
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

		switch ($this->isResponseRc) {
			case 0:
				$this->response->icon = lang('CONF_ICON_DANGER');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_FILE_EXIST');
				$this->response->data['btn1']['action'] = 'close';

				if(file_exists(assetPath('downloads/'.$response->bean))) {
					$this->response->code = 0;
					$this->response->msg = lang('GEN_RC_0');
					$this->response->data = [
						'file' => assetUrl('downloads/'.$response->bean),
						'name' => $response->bean
					];
				}
			break;
			case -423:
				$this->response->icon = lang('CONF_ICON_INFO');
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

		$this->dataRequest->className = 'TarjetaTO.class';
		$this->dataRequest->noTarjeta = $dataRequest->cardNumber;
		$this->dataRequest->rif = $this->session->enterpriseInf->idFiscal;
		$this->dataRequest->acCodCia = $this->session->enterpriseInf->enterpriseCode;

		$response = $this->sendToService('GetReport: '.$dataRequest->operation);

		switch ($this->isResponseRc) {
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
				$this->response->icon = lang('CONF_ICON_INFO');
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

		$this->dataRequest->tarjetaHabiente = [
			'id_ext_per' => $dataRequest->idType.'_'.$dataRequest->idNumber,
			'id_ext_emp' => $this->session->enterpriseInf->idFiscal,
			'acCodCia' => $this->session->enterpriseInf->enterpriseCode
		];

		$response = $this->sendToService('GetReport: '.$dataRequest->operation);

		switch ($this->isResponseRc) {
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
				$this->response->icon = lang('CONF_ICON_INFO');
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

		switch ($this->isResponseRc) {
			case 0:
				$this->response->icon = lang('CONF_ICON_DANGER');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_FILE_EXIST');
				$this->response->data['btn1']['action'] = 'close';

				if(file_exists(assetPath('downloads/'.$response->bean))) {
					$this->response->code = 0;
					$this->response->msg = lang('GEN_RC_0');
					$this->response->data = [
						'file' => assetUrl('downloads/'.$response->bean),
						'name' => $response->bean
					];
				}
			break;
			case -30:
			case -150:
				$this->session->set_flashdata('cardsPeople', $this->session->flashdata('cardsPeople'));
				$this->response->icon = lang('CONF_ICON_INFO');
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

		$this->dataRequest->certificadoGmf = [
			'anio' => $dataRequest->dateG
		];
		$this->dataRequest->empresaCliente = [
			'rif' => $this->session->enterpriseInf->idFiscal,
			'accodcia' => $this->session->enterpriseInf->enterpriseCode
		];

		$response = $this->sendToService('GetReport: '.$dataRequest->operation);

		switch ($this->isResponseRc) {
			case 0:
				$this->response->icon = lang('CONF_ICON_DANGER');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_FILE_EXIST');
				$this->response->data['btn1']['action'] = 'close';

				if(file_exists(assetPath('downloads/'.$response->bean))) {
					$this->response->code = 0;
					$this->response->msg = lang('GEN_RC_0');
					$this->response->data = [
						'file' => assetUrl('downloads/'.$response->bean),
						'name' => $response->bean
					];
				}
			break;
			case -30:
			case -150:
				$this->response->icon = lang('CONF_ICON_INFO');
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

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'Estado de lote';
		$this->dataAccessLog->operation = 'Obtener lista lotes por estado';

		$this->dataRequest->idOperation = 'buscarEstatusLotes';
		$this->dataRequest->className = 'com.novo.objects.TOs.EmpresaTO';
		$this->dataRequest->acCodCia = $dataRequest->enterpriseCode;
		$this->dataRequest->idProducto = $dataRequest->productCode;
		$this->dataRequest->dtfechorcargaIni = $dataRequest->initialDate;
		$this->dataRequest->dtfechorcargaFin = $dataRequest->finalDate;

		$response = $this->sendToService('callWs_StatusBulk');
		$statusBulkList = [];


		switch ($this->isResponseRc) {
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

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'Id empresa';
		$this->dataAccessLog->operation = 'Obtener id de empresa';

		$this->dataRequest->idOperation = 'buscarIdEmpresa';
		$response =  $dataRequest;

		switch ($this->isResponseRc = 0) {
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

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'Saldos Amanecidos';
		$this->dataAccessLog->operation = 'Obtener saldos';

		$this->dataRequest->idOperation = 'saldosAmanecidos';
		$this->dataRequest->className = 'com.novo.objects.MO.SaldosAmanecidosMO';
		$this->dataRequest->idExtPer = $dataRequest->idExtPer;
		$this->dataRequest->producto =  $dataRequest->product;
		$this->dataRequest->idExtEmp =  $dataRequest->idExt;
		$this->dataRequest->tamanoPagina = 10;
		$this->dataRequest->paginar = TRUE;
		$this->dataRequest->paginaActual = (int) ($dataRequest->start / 10) + 1;


		$response = $this->sendToService('callWs_closingBudgets');
		$this->response->recordsTotal = 0;
		$this->response->recordsFiltered = 0;

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$user = $response->saldo->lista;
				$this->response->data =  (array)$user;
				$this->response->access = [
					'RESPSAL' => $this->verify_access->verifyAuthorization('REPSAL'),
				];
				$this->response->draw = (int) $dataRequest->draw;
				$this->response->recordsTotal = $response->totalSaldos;
				$this->response->recordsFiltered =  $response->totalSaldos;

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

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'Saldos amanecidos';
		$this->dataAccessLog->operation = 'Obtener excel de tabla';

		$this->dataRequest->idOperation = 'generaArchivoXls';
		$this->dataRequest->className = 'com.novo.objects.MO.SaldosAmanecidosMO';
		$this->dataRequest->producto =  $dataRequest->product;
		$this->dataRequest->idExtEmp =  $dataRequest->identificationCard;
		$this->dataRequest->tamanoPagina = $dataRequest->pageLenght;
		$this->dataRequest->paginar = $dataRequest->paged;
		$this->dataRequest->paginaActual = $dataRequest->actualPage;
		$this->dataRequest->descProd =  $dataRequest->descProd;

		$response = $this->sendToService('callWs_exportToExcel');

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$user = $response;
				$this->response->data =  (array)$user;
			break;
			case -3:
				$this->response->code = 4;
				$this->response->icon = lang('CONF_ICON_DANGER');
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

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'Obtener resultados de busqueda';
		$this->dataAccessLog->operation = 'Cuenta maestra';

		$this->dataRequest->idOperation = 'buscarDepositoGarantia';
		$this->dataRequest->className = 'com.novo.objects.MO.DepositosGarantiaMO';
		$this->dataRequest->idExtEmp = $dataRequest->idExtEmp;
		$this->dataRequest->fechaIni = $dataRequest->dateStart;
		$this->dataRequest->fechaFin =  $dataRequest->dateEnd;
		$this->dataRequest->tipoNota =  $dataRequest->typeNote;
		$this->dataRequest->filtroFecha = $dataRequest->dateFilter;
		$this->dataRequest->tamanoPagina = $dataRequest->pageSize;
		$this->dataRequest->paginaActual = 1;

		$response = $this->sendToService('callWs_masterAccount');

		switch ($this->isResponseRc) {
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

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'cuenta maestra';
		$this->dataAccessLog->operation = 'Obtener excel de tabla cuenta maestra';

		$this->dataRequest->idOperation = 'generarDepositoGarantia';
		$this->dataRequest->className = 'com.novo.objects.MO.DepositosGarantiaMO';
		$this->dataRequest->idExtEmp = $dataRequest->idExtEmp;
		$this->dataRequest->fechaIni =  $dataRequest->dateStart;
		$this->dataRequest->fechaFin =  $dataRequest->dateEnd;
		$this->dataRequest->filtroFecha = $dataRequest->dateFilter;
		$this->dataRequest->nombreEmpresa = $dataRequest->nameEnterprise;
		$this->dataRequest->paginaActual = $dataRequest->actualPage;
		$this->dataRequest->producto =  $this->session->userdata('productInf')->productPrefix;
		$this->dataRequest->tamanoPagina =  $dataRequest->pageSize;

		$response = $this->sendToService('callWs_exportToExcelMasterAccount');

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$user = $response;
				$this->response->data =  (array)$user;
			break;
			case -3:
				$this->response->code = 4;
				$this->response->icon = lang('CONF_ICON_DANGER');
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

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'cuenta maestra';
		$this->dataAccessLog->operation = 'Obtener pdf de tabla cuenta maestra';

		$this->dataRequest->idOperation = 'generarDepositoGarantiaPdf';
		$this->dataRequest->className = 'com.novo.objects.MO.DepositosGarantiaMO';
		$this->dataRequest->idExtEmp = $dataRequest->idExtEmp;
		$this->dataRequest->fechaIni =  $dataRequest->dateStart;
		$this->dataRequest->fechaFin =  $dataRequest->dateEnd;
		$this->dataRequest->filtroFecha = $dataRequest->dateFilter;
		$this->dataRequest->nombreEmpresa = $dataRequest->nameEnterprise;
		$this->dataRequest->paginaActual = $dataRequest->actualPage;
		$this->dataRequest->producto =  $this->session->userdata('productInf')->productPrefix;
		$this->dataRequest->tamanoPagina =  $dataRequest->pageSize;

		$response = $this->sendToService('callWs_exportToPDFMasterAccount');

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$user = $response;
				$this->response->data =  (array)$user;
			break;
			case -3:
				$this->response->code = 4;
				$this->response->icon = lang('CONF_ICON_DANGER');
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

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'cuenta maestra';
		$this->dataAccessLog->operation = 'Obtener excel de tabla cuenta maestra';

		$this->dataRequest->idOperation = 'generaArchivoXlsConcil';
		$this->dataRequest->className = 'com.novo.objects.MO.DepositosGarantiaMO';
		$this->dataRequest->anio = $dataRequest->year;
		$this->dataRequest->idExtEmp = $dataRequest->idExtEmp;
		$this->dataRequest->fechaIni =  $dataRequest->dateStart;
		$this->dataRequest->fechaFin =  $dataRequest->dateEnd;
		$this->dataRequest->filtroFecha = $dataRequest->dateFilter;
		$this->dataRequest->nombreEmpresa = $dataRequest->nameEnterprise;
		$this->dataRequest->paginaActual = $dataRequest->actualPage;
		$this->dataRequest->producto =  $this->session->userdata('productInf')->productPrefix;
		$this->dataRequest->tamanoPagina =  $dataRequest->pageSize;

		$response = $this->sendToService('callWs_exportToExcelMasterAccountConsolid');

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$user = $response;
				$this->response->data =  (array)$user;
			break;
			case -3:
				$this->response->code = 4;
				$this->response->icon = lang('CONF_ICON_DANGER');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_FILE');
				$this->response->data['btn1']['action'] = 'close';
			break;
			default:
				$this->response->code = 4;
				$this->response->icon = lang('CONF_ICON_DANGER');
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

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'cuenta maestra';
		$this->dataAccessLog->operation = 'Obtener pdf de tabla cuenta maestra consolidado';

		$this->dataRequest->idOperation = 'generaArchivoConcilPdf';
		$this->dataRequest->className = 'com.novo.objects.MO.DepositosGarantiaMO';
		$this->dataRequest->anio = $dataRequest->year;
		$this->dataRequest->idExtEmp = $dataRequest->idExtEmp;
		$this->dataRequest->fechaIni =  $dataRequest->dateStart;
		$this->dataRequest->fechaFin =  $dataRequest->dateEnd;
		$this->dataRequest->filtroFecha = $dataRequest->dateFilter;
		$this->dataRequest->nombreEmpresa = $dataRequest->nameEnterprise;
		$this->dataRequest->paginaActual = $dataRequest->actualPage;
		$this->dataRequest->producto =  $this->session->userdata('productInf')->productPrefix;
		$this->dataRequest->tamanoPagina =  $dataRequest->pageSize;

		$response = $this->sendToService('callWs_exportToPDFMasterAccountConsolid');

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$user = $response;
				$this->response->data =  (array)$user;
			break;
			case -3:
				$this->response->code = 4;
				$this->response->icon = lang('CONF_ICON_DANGER');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_FILE');
				$this->response->data['btn1']['action'] = 'close';
			break;
			default:
				$this->response->code = 4;
				$this->response->icon = lang('CONF_ICON_DANGER');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_FILE_CONSOLID');
				$this->response->data['btn1']['action'] = 'close';
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
		$this->dataRequest->className = 'com.novo.objects.MO.TarjetaHabientesMO';
		$this->dataRequest->paginaActual = 1;
		$this->dataRequest->tamanoPagina = 10;
		$this->dataRequest->paginar = FALSE;
		$this->dataRequest->rifEmpresa = $dataRequest->enterpriseCode;
		$this->dataRequest->idProducto = $dataRequest->productCode;
		$response = $this->sendToService('callWS_StatusCardHolders');
		$cardHoldersList = [];

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        foreach($response->listadoTarjetaHabientes AS $cardHolders) {
          $record = new stdClass();
					$record->cardHoldersId = $cardHolders->idExtPer;
					$record->cardHoldersNum = $cardHolders->nroTarjeta ?? '' ;
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

	public function callWs_RechargeMade_Reports($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: RechargeMade Method Initialized');

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'Reportes Recargas Realizadas';
		$this->dataAccessLog->operation = 'Recargas Realizadas';

		$this->dataRequest->idOperation = 'recargasRealizadas';
		$this->dataRequest->className = 'com.novo.objects.TOs.RecargasRealizadasTO';
		$this->dataRequest->paginaActual = 1;
		$this->dataRequest->tamanoPagina = 10;
		$this->dataRequest->fecha = '';
		$this->dataRequest->fecha1 = '';
		$this->dataRequest->fecha2 = '';
		$this->dataRequest->accodcia = $dataRequest->enterpriseCode;
    $fecha=$dataRequest->initialDatemy;
    $arreglo=explode ("/",$fecha);
    $mes=$arreglo[0];
    $anio=$arreglo[1];
		$this->dataRequest->mesSeleccionado = $mes;
		$this->dataRequest->anoSeleccionado = $anio;
		$response = $this->sendToService('callWs_RechargeMadeReport');
		$rechargeMadeList = [];

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
          $record = new stdClass();
					$record->monthRecharge1 = $response->mesRecarga1;
					$record->monthRecharge2 = $response->mesRecarga2;
					$record->monthRecharge3 =$response->mesRecarga3;
					$record->totalRecharge1 = $response->totalRecargas1;
					$record->totalRecharge2 = $response->totalRecargas2;
					$record->totalRecharge3 =$response->totalRecargas3;
					$record->totalRecharge =$response->totalRecargas;
					$record->recharge = $response->recargas;
          array_push(
            $rechargeMadeList,
            $record
          );

      break;
      case -150:
        $this->response->code = 0;
      break;
		}

    $this->response->data['rechargeMadeList'] = $rechargeMadeList;

    return $this->responseToTheView('callWS_RechargeMadeReport');
	}

	public function callWs_IssuedCards_Reports($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: IssuedCards Method Initialized');

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'buscarTarjetasEmitidas';
		$this->dataAccessLog->operation = 'buscarTarjetasEmitidas';

		$this->dataRequest->idOperation = 'buscarTarjetasEmitidas';
		$this->dataRequest->className = 'com.novo.objects.MO.ListadoEmisionesMO';
		$this->dataRequest->tipoConsulta = $dataRequest->radioButton;
		$this->dataRequest->fechaMes = $dataRequest->monthYear;
		$this->dataRequest->accodcia = $dataRequest->enterpriseCode;
		$this->dataRequest->fechaIni = '';
		$this->dataRequest->fechaFin = '';
		$response = $this->sendToService('callWs_IssuedCardsReport');
		$issuedCardsList = [];

    switch($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
				$record = new stdClass();
				$record->lista = isset($response->lista) ? $response->lista : '';
				array_push(
					$issuedCardsList,
					$record
				);
      break;
      case -150:
        $this->response->code = 0;
      break;
    }
		$this->response->data['issuedCardsList'] = $issuedCardsList;
		$this->response->data['tipoConsulta'] = $this->dataRequest->tipoConsulta;

    return $this->responseToTheView('callWS_IssuedCardsReport');
	}




		/**
	 * @info Método para obtener actividad por ususario
	 * @author Diego Acosta García
	 * @date May 27, 2020
	 */
	public function callWs_userActivity_Reports($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: userActivity Method Initialized');

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'Actividad por usuario';
		$this->dataAccessLog->operation = 'Obtener actividades por usuario';

		$this->dataRequest->idOperation = 'buscarActividadesXUsuario';
		$this->dataRequest->className = 'com.novo.objects.MO.ListadoEmpresasMO';
		$this->dataRequest->fechaIni =  $dataRequest->fechaIni;
		$this->dataRequest->fechaFin =  $dataRequest->fechaFin;
		$this->dataRequest->acCodCia = $dataRequest->acCodCia;

		$response = $this->sendToService('callWs_userActivity');

		switch ($this->isResponseRc) {
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
  * @info Método para obtener actividad por usuario (Produbanco)
  * @author Jhonnatan Vega
  * @date October 13, 2020
 */
	public function callWs_usersActivity_Reports($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: usersActivity Method Initialized');

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'Actividad por usuario';
		$this->dataAccessLog->operation = 'Obtener actividades por usuario';

		$this->dataRequest->idOperation = 'genericBusiness';
		$this->dataRequest->className = 'com.novo.objects.MO.GenericBusinessObject';
		$this->dataRequest->opcion = 'reporteLogAcceso';
		$this->dataRequest->userName = $this->userName;
		$this->dataRequest->accodcia = $dataRequest->enterpriseCode;
		$this->dataRequest->acprefix = $this->session->productInf->productPrefix;
		$this->dataRequest->fechaInicio =  $dataRequest->initialDate;
		$this->dataRequest->fechaFin =  $dataRequest->finalDate;

		$response = $this->sendToService('callWs_usersActivity');
		$usersActivity = [];

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;

				foreach($response->bean AS $userActivity) {
					$record = new stdClass();
					$record->user = $userActivity->usuario;
					$record->userStatus = $userActivity->estadoUsuario;
					$record->lastConnectionDate = $userActivity->fechaUltimaConexion;
					$lastActions = [];

					foreach($userActivity->opciones->ultimasAcciones AS $lastActionsList){
						array_push(
							$lastActions,
							$lastActionsList
						);
					}

					$record->lastActions = $lastActions;
					$enabledFunctions = [];

					foreach($userActivity->opciones->funcionesHabilitadas AS $enabledFunctionsList){
						array_push(
							$enabledFunctions,
							$enabledFunctionsList
						);
					}

					$record->enabledFunctions = $enabledFunctions;
					array_push(
						$usersActivity,
						$record
					);
				}
			break;
			case -104:
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->msg = lang('REPORTS_REQUEST_NO_RESULTS');
				$this->response->data = [
					'btn1'=> [
						'text'=> lang('GEN_BTN_ACCEPT'),
						'action'=> 'destroy'
					]
				];
			break;
		}

		$this->response->data['usersActivity'] = $usersActivity;

		return $this->responseToTheView('callWs_usersActivity');
	}

	/**
  * @info Método para descargar reporte de actividad por usuario (Produbanco)
  * @author Jhonnatan Vega
  * @date October 22, 2020
 */
	public function callWs_exportExcelUsersActivity_Reports($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: exportExcelUsersActivity Method Initialized');

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'Actividad por usuario';
		$this->dataAccessLog->operation = 'Descarga reporte';

		$this->dataRequest->idOperation = 'genericBusiness';
		$this->dataRequest->className = 'com.novo.objects.MO.GenericBusinessObject';
		$this->dataRequest->opcion = 'reporteLogAccesoExcel';
		$this->dataRequest->userName = $this->userName;
		$this->dataRequest->accodcia = $dataRequest->enterpriseCode;
		$this->dataRequest->acprefix = $this->session->productInf->productPrefix;
		$this->dataRequest->fechaInicio =  $dataRequest->initialDate;
		$this->dataRequest->fechaFin =  $dataRequest->finalDate;

		$response = $this->sendToService('callWs_exportExcelUsersActivity');

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$file = $response->bean->archivo;
				$name = $response->bean->nombreArchivo;
				$ext =  '.xlsx';
				$this->response->data['file'] = $file;
				$this->response->data['name'] = $name;
				$this->response->data['ext'] = $ext;
			break;
			default:
				$this->response->code = 4;
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->msg = lang('GEN_WARNING_DOWNLOAD_FILE');
				$this->response->data['btn1']['action'] = 'destroy';
			break;
		}

		return $this->responseToTheView('callWs_exportExcelUsersActivity');
	}

		/**
	 * @info Método para obtener excel de tabla cuenta maestra
	 * @author Diego Acosta García
	 * @date May 27, 2020
	 */
	public function callWs_exportToExcelUserActivity_Reports($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: exportToExcelUserActivity Method Initialized');

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'Actividad por usuario';
		$this->dataAccessLog->operation = 'Obtener actividad por usuario en excel';

		$this->dataRequest->idOperation = 'generarArchivoXlsActividadesXUsuario';
		$this->dataRequest->className = 'com.novo.objects.MO.DepositosGarantiaMO';
		$this->dataRequest->rifEmpresa = $dataRequest->rifEmpresa;
		$this->dataRequest->fechaIni =  $dataRequest->fechaIni;
		$this->dataRequest->fechaFin =  $dataRequest->fechaFin;
		$this->dataRequest->acCodCia = $dataRequest->acCodCia;

		$response = $this->sendToService('callWs_exportToExcelUserActivity');

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$user = $response;
				$this->response->data =  (array)$user;
			break;
			case -3:
				$this->response->code = 4;
				$this->response->icon = lang('CONF_ICON_DANGER');
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

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'Actividad por usuario';
		$this->dataAccessLog->operation = 'Obtener actividad por usuario en pdf';

		$this->dataRequest->idOperation = 'generarPdfActividadesXUsuario';
		$this->dataRequest->className = 'com.novo.objects.MO.DepositosGarantiaMO';
		$this->dataRequest->rifEmpresa = $dataRequest->rifEmpresa;
		$this->dataRequest->fechaIni =  $dataRequest->fechaIni;
		$this->dataRequest->fechaFin =  $dataRequest->fechaFin;
		$this->dataRequest->acCodCia = $dataRequest->acCodCia;

		$response = $this->sendToService('callWs_exportToPDFUserActivity');

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$user = $response;
				$this->response->data =  (array)$user;
			break;
		}

		return $this->response;
	}
	/**
	 * @info Método para obtener busqueda de estado de cuenta
	 * @author Diego Acosta García
	 * @date Aug 18, 2020
	 */
	public function callWs_searchStatusAccount_Reports($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: searchStatusAccount Method Initialized');

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'movimientoEstadoCuentaDetalle';
		$this->dataAccessLog->operation = 'movimientoEstadoCuentaDetalle';

		$this->dataRequest->idOperation = 'movimientoEstadoCuentaDetalle';
		$this->dataRequest->className = 'com.novo.objects.MO.EstadoCuentaMO';

		if ($dataRequest->resultByNIT === 'all') {
			$dataRequest->resultByNIT = '';
			$typeSearch = '0';
		} else {
			$typeSearch = '1';
		}

		$this->dataRequest->idExtPer = strtoupper($dataRequest->resultByNIT);

		if(lang('CONF_INPUT_UPPERCASE') == 'ON'){
			$this->dataRequest->idExtPer = $dataRequest->resultByNIT;
		}

		$this->dataRequest->idExtEmp = $dataRequest->enterpriseCode;
    $lastDayMonyh = date("t-m-Y", strtotime(str_replace( '/', '-', "1/".$dataRequest->initialDateAct)));
		$this->dataRequest->fechaFin = str_replace( '-', '/', $lastDayMonyh);
		$this->dataRequest->fechaIni = "1/".$dataRequest->initialDateAct;
		$this->dataRequest->tamanoPagina = '10';
		$this->dataRequest->tipoConsulta = $typeSearch;
		$this->dataRequest->pagActual = '2';
		$this->dataRequest->prefix = $dataRequest->productCode;
		$this->dataRequest->paginar = false;

		$response = $this->sendToService('callWs_searchStatusAccount');

		switch ($this->isResponseRc) {
			case 0:
				$table =[];
				$this->response->code = 0;

				foreach ((array)$response->listadoEstadosCuentas as $key => $val) {
					$table[$key] = $val;
					$valAccountStatus[$key]= $response->listadoEstadosCuentas[$key]->listaMovimientos;
				}

				$usersData = $valAccountStatus;
				$usersTables = [];
				$data = [];

				foreach ($usersData as $key1 => $val) {
					$data[$key1] = $usersData[$key1];
				}

				foreach ($data as $key => $val) {
					foreach ($data[$key] as $key1 => $val) {
						$usersTables =(($data[$key])[$key1]);
						$dataAccount = [];
						$debit = '';
						$credit = '';

						if(lang('CONF_STATUS_ACCOUNT_ADD_COLUMNS') == 'ON') {
							$usersTables->secuencia = $usersTables->secuence;
							$usersTables->terminal = $usersTables->terminal;
							$usersTables->fid = $usersTables->fid;
						}

						if ($usersTables->tipoTransaccion == '+') {
							$debit = $usersTables->monto;
							$credit = '0';
						} else {
							$credit = $usersTables->monto;
							$debit = '0';
						}

						$objUserData[$key] = [
							'secuence' => "",
							'terminal' => "",
							'fid' => "",
							'reference' => $usersTables->referencia,
							'description' => $usersTables->descripcion,
							'date' => $usersTables->fecha,
							'credit' => $credit,
							'debit' => $debit,
							'client' => $usersTables->cliente
						];
						($data[$key])[$key1]= $objUserData[$key];
					}
				}

				foreach($response->listadoEstadosCuentas as $key => $value){
					($dataAccount[$key])['account'] = $response->listadoEstadosCuentas[$key]->cuenta;
					($dataAccount[$key])['client'] = $response->listadoEstadosCuentas[$key]->cliente;
					($dataAccount[$key])['id'] = $response->listadoEstadosCuentas[$key]->idExtPer;
				}

				$this->response->data['users'] = $data;
				$this->response->data['accounts'] = $dataAccount;
			break;
			case -444:
				$this->response->icon = lang('CONF_ICON_DANGER');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_REGISTRY_FOUND');
				$this->response->data['btn1']['action'] = 'close';
			break;
			case -150:
				$this->response->code = 1;
				$this->response->data['users'] = '';
			break;
		}

		return $this->responseToTheView('callWs_searchStatusAccount');
	}
	/**
	 * @info Método para obtener EXCEL de estado de cuenta
	 * @author Diego Acosta García
	 * @date Aug 18, 2020
	 */
	public function callWs_statusAccountExcelFile_Reports($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: statusAccountExcelFile Method Initialized');

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'generaArchivoXlsEdoCta';
		$this->dataAccessLog->operation = 'generaArchivoXlsEdoCta';

		$this->dataRequest->idOperation = 'generaArchivoXlsEdoCta';
		$this->dataRequest->className = 'com.novo.objects.MO.EstadoCuentaMO';

		if ($dataRequest->resultByNIT === 'all') {
			$dataRequest->resultByNIT = '';
			$typeSearch = '0';
		} else {
			$typeSearch = '1';
		}

		$this->dataRequest->idExtEmp = $dataRequest->enterpriseCode;
		$this->dataRequest->idExtPer = $dataRequest->resultByNIT;
    $lastDayMonyh = date("t-m-Y", strtotime(str_replace( '/', '-', "1/".$dataRequest->initialDateAct)));
		$this->dataRequest->fechaFin = str_replace( '-', '/', $lastDayMonyh);
		$this->dataRequest->fechaIni = "1/".$dataRequest->initialDateAct;
		$this->dataRequest->tamanoPagina = '5';
		$this->dataRequest->tipoConsulta = $typeSearch;
		$this->dataRequest->pagActual = '1';
		$this->dataRequest->prefix = $dataRequest->productCode;
		$this->dataRequest->paginar = false;
		$this->dataRequest->nombreEmpresa = $dataRequest->enterpriseName;
		$this->dataRequest->descProducto = $dataRequest->descProduct;

		$response = $this->sendToService('callWs_statusAccountExcelFile');

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->data = (array)$response;
			break;
			case -3:
				$this->response->icon = lang('CONF_ICON_DANGER');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_FILE_CONSOLID');
				$this->response->data['btn1']['action'] = 'close';
			break;
		}

		return $this->responseToTheView('callWs_statusAccountExcelFile');
	}
	/**
	 * @info Método para obtener PDF de estado de cuenta
	 * @author Diego Acosta García
	 * @date Aug 18, 2020
	 */
	public function callWs_statusAccountpdfFile_Reports($dataRequest)
	{
		log_message('INFO', 'NOVO Reports Model: statusAccountpdfFile Method Initialized');

		$this->dataAccessLog->modulo = 'Reportes';
		$this->dataAccessLog->function = 'generarComprobante';
		$this->dataAccessLog->operation = 'generarComprobante';

		$this->dataRequest->idOperation = 'generarComprobante';
		$this->dataRequest->className = 'com.novo.objects.MO.EstadoCuentaMO';

		if ($dataRequest->resultByNIT === 'all') {
			$dataRequest->resultByNIT = '';
			$typeSearch = '0';
		} else {
			$typeSearch = '1';
		}

		$this->dataRequest->idExtEmp = $dataRequest->enterpriseCode;
		$this->dataRequest->idExtPer = $dataRequest->resultByNIT;
    $lastDayMonyh = date("t-m-Y", strtotime(str_replace( '/', '-', "1/".$dataRequest->initialDateAct)));
		$this->dataRequest->fechaFin = str_replace( '-', '/', $lastDayMonyh);
		$this->dataRequest->fechaIni = "1/".$dataRequest->initialDateAct;
		$this->dataRequest->tamanoPagina = '5';
		$this->dataRequest->tipoConsulta = $typeSearch;
		$this->dataRequest->pagActual = '1';
		$this->dataRequest->prefix = $dataRequest->productCode;
		$this->dataRequest->paginar = false;
		$this->dataRequest->nombreEmpresa = $dataRequest->enterpriseName;
		$this->dataRequest->descProducto = $dataRequest->descProduct;

		$response = $this->sendToService('callWs_statusAccountPdfFile');

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->data = (array)$response;
			break;
			case -3:
				$this->response->icon = lang('CONF_ICON_DANGER');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_FILE_CONSOLID');
				$this->response->data['btn1']['action'] = 'close';
			break;
		}

		return $this->responseToTheView('callWs_statusAccountpdfFile');
	}
}
