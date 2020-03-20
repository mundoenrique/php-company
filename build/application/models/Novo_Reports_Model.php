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

		//$this->isResponseRc = 0;
		//$response = json_decode('{"listaConfigReportesCEO":[{"idReporte":"1","idOperation":"repListadoTarjetas","description":"Listado Tarjeta","result":"DOWNLOAD","listDownloadFormat":[{"idFormat":"1","descripFormat":"CSV"}],"listFilter":[],"listTableHeader":[]},{"idReporte":"2","idOperation":"repMovimientoPorEmpresa","description":"Movimiento por Empresa","result":"DOWNLOAD","listDownloadFormat":[{"idFormat":"1","descripFormat":"CSV"}],"listFilter":[{"idFilter":"1","orderFilter":1,"typeFiltter":"D","nameFilter":"Fecha Inicial (AAAA-MM-DD)","minValue":"2020-01-01","maxValue":"2999-01-01"},{"idFilter":"2","orderFilter":2,"typeFiltter":"D","nameFilter":"Fecha Final (AAAA-MM-DD)","minValue":"2020-01-01","maxValue":"2999-01-01"}],"listTableHeader":[]},{"idReporte":"4","idOperation":"repMovimientoPorTarjeta","description":"Movimiento por Tarjeta","result":"DOWNLOAD","listDownloadFormat":[{"idFormat":"1","descripFormat":"CSV"}],"listFilter":[{"idFilter":"3","orderFilter":1,"typeFiltter":"S","nameFilter":"Tipo Identificación","minValue":"","maxValue":"","listDataSelection":[{"idData":"1","codData":"C","description":"Cédula de Ciudadanía"},{"idData":"2","codData":"E","description":"Cédula de Extranjería"},{"idData":"3","codData":"P","description":"Pasaporte"},{"idData":"4","codData":"T","description":"Tarjeta de identidad"},{"idData":"5","codData":"N","description":"NIT Persona Jurídica"},{"idData":"6","codData":"L","description":"NIT Persona Natural"},{"idData":"7","codData":"I","description":"NIT Persona Extranjera"}]}],"listTableHeader":[]},{"idReporte":"5","idOperation":"repTarjeta","description":"Tarjeta","result":"TABLE","listDownloadFormat":[],"listFilter":[],"listTableHeader":[{"idHeader":"1","description":"Tipo Identificación"},{"idHeader":"2","description":"Número Identificación"},{"idHeader":"3","description":"Nombre"},{"idHeader":"4","description":"Tarjeta"},{"idHeader":"5","description":"Producto"},{"idHeader":"6","description":"Fecha creación tarjeta"},{"idHeader":"7","description":"Fecha vencimiento tarjeta"},{"idHeader":"8","description":"Estado actual"},{"idHeader":"9","description":"Fecha activación"},{"idHeader":"10","description":"Motivo bloqueo"},{"idHeader":"11","description":"Fecha bloqueo"},{"idHeader":"12","description":"Saldo actual"},{"idHeader":"13","description":"Fecha último cargue"},{"idHeader":"14","description":"Último valor cargado"},{"idHeader":"15","description":"Cobra GMF"}]},{"idReporte":"6","idOperation":"repComprobantesVisaVale","description":"Comprobantes Visa Vale","result":"DOWNLOAD","listDownloadFormat":[],"listFilter":[],"listTableHeader":[]}],"rc":0,"msg":"Proceso OK","pais":"Bdb"}');
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
			case 'repMovimientoPorTarjeta':
				$this->movementsByCards($dataRequest);
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
			'fechaDesde' => convertDate($dataRequest->enterpriseDateBegin),
			'fechaHasta' => convertDate($dataRequest->enterpriseDateEnd)
		];
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
				$cardsReport = is_array($response) ? $response : array ($response);
				$cardsMove = [];
				foreach ($response as $key => $value) {
					switch ($key) {
						case 'noTarjeta':
							$cardsMove['idType'] = '$value';
							break;
						case 'nombre_producto':
							$cardsMove['idNumber'] = '$value';
							break;
						case 'montoComisionTransaccion':
							$cardsMove['userName'] = '$value';
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
						case 'bloque':
							$cardsMove['dateBlock'] = $value;
							break;
						case 'fechaBloqueo':
							$cardsMove['currentBalance'] = $value;
							break;
						case 'fechaUltimoCargue':
							$cardsMove['lastCredit'] = $value;
							break;
						case 'condicion':
							$cardsMove['lastAmoutn'] = '$value';
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

		//$this->isResponseRc = 0;
		//$response = json_decode('{"lista":[{"noTarjeta":"00004193280000300118","montoComisionTransaccion":0,"montoComisionThTransaccion":0,"numLote":0,"paginaActual":"1","tamanoPagina":"0","paginar":false,"idAsignacion":0,"condicion":0,"pinGeneradoUsuario":false,"rc":0},{"noTarjeta":"00004193280000300120","montoComisionTransaccion":0,"montoComisionThTransaccion":0,"numLote":0,"paginaActual":"1","tamanoPagina":"0","paginar":false,"idAsignacion":0,"condicion":0,"pinGeneradoUsuario":false,"rc":0}],"rc":0,"msg":"Proceso OK","pais":"Bdb"}');
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
				$this->session->set_flashdata('cardsPeople', $this->session->flashdata('cardsPeople'));
				$this->response->icon = lang('GEN_ICON_INFO');
				$this->response->title = lang('REPORTS_TITLE');
				$this->response->msg = lang('REPORTS_NO_MOVES_ENTERPRISE');
				$this->response->data['btn1']['action'] = 'close';
				break;
		}

		return $this->response;
	}
}
