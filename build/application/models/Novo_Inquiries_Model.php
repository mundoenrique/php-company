<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info clase para obtener información relacionada con los lotes
 * @author
 *
 */
class Novo_Inquiries_Model extends NOVO_Model {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO Inquiries Model Class Initialized');
	}
	/**
	 * @info Método para obtener la lista de estados de las ordenes de servicio
	 * @author J. Enrique Peñaloza Piñero
	 * @date Janury 09th, 2019
	 */
	public function callWs_ServiceOrderStatus_Inquiries()
	{
		log_message('INFO', 'NOVO Inquiries Model: ServiceOrderStatus Method Initialized');

		$this->dataAccessLog->modulo = 'Consultas';
		$this->dataAccessLog->function = 'Lista de ordenes de servicio';
		$this->dataAccessLog->operation = 'Estados de orden de servicio';

		$this->dataRequest->idOperation = 'estatusLotes';
		$this->dataRequest->className = 'com.novo.objects.MO.EstatusLotesMO';
		$this->dataRequest->tipoEstatus = 'TIPO_B';

		$response = $this->sendToService('callWs_ServiceOrderStatus');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$orderStatus[] = (object) [
					'key' => '',
					'text' => 'Selecciona un estado'
				];

				foreach($response->lista AS $pos => $types) {
					$type = [];
					$type['key'] = mb_strtoupper($response->lista[$pos]->codEstatus);
					$type['text'] = ucfirst(mb_strtolower($response->lista[$pos]->descEstatus));
					$orderStatus[] = (object) $type;
				}
				break;
		}

		if($this->isResponseRc != 0) {
			$orderStatus[] = (object) [
				'key' => '',
				'text' => lang('RESP_TRY_AGAIN')
			];
		}

		$this->response->data->orderStatus = (object) $orderStatus;

		return $this->responseToTheView('callWs_ServiceOrderStatus');
	}
	/**
	 * @info Método para obtener la lista de ordenes de servicio en rango de fecha dado
	 * @author J. Enrique Peñaloza Piñero
	 * @date Janury 09th, 2019
	 */
	public function callWs_GetServiceOrders_Inquiries($dataRequest)
	{

		log_message('INFO', 'NOVO Inquiries Model: ServiceOrderStatus Method Initialized');

		$this->dataAccessLog->modulo = 'Consultas';
		$this->dataAccessLog->function = 'Ordenes de servicio';
		$this->dataAccessLog->operation = 'Lista de ordenes de servicio';

		$this->dataRequest->idOperation = 'buscarOrdenServicio';
		$this->dataRequest->className = 'com.novo.objects.MO.ListadoOrdenServicioMO';
		$this->dataRequest->rifEmpresa = $this->session->enterpriseInf->idFiscal;
		$this->dataRequest->acCodCia = $this->session->enterpriseInf->enterpriseCode;
		$this->dataRequest->idProducto = $this->session->productInf->productPrefix;
		$this->dataRequest->fechaIni = $dataRequest->initialDate;
		$this->dataRequest->fechaFin = $dataRequest->finalDate;
		$this->dataRequest->status = $dataRequest->status;
		$this->dataRequest->statusText = $dataRequest->statusText;
		$statusText = $dataRequest->statusText;

   	$response = $this->sendToService('callWs_GetServiceOrders');

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->data = 'consulta-orden-de-servicio';

				foreach($response->lista AS $list) {
					$orderList = [];

					foreach($list AS $key => $value) {
						switch ($key) {
							case 'idOrden':
								$serviceOrders['OrderNumber'] = $value;
							break;
							case 'estatus':
								$serviceOrders['OrderStatus'] = $value;
								$serviceOrders['OrderVoidable'] = FALSE;

								if($value == '0') {
									$serviceOrders['OrderVoidable'] = $list->nofactura != '' && $list->fechafactura != '' ?: TRUE;
								}
							break;
							case 'fechaGeneracion':
								$serviceOrders['Orderdate'] = $value;
							break;
							case 'montoComision':
								$serviceOrders['OrderCommission'] = currencyFormat($value);
							break;
							case 'montoIVA':
								$serviceOrders['OrderTax'] = currencyFormat($value);
							break;
							case 'montoOS':
								$serviceOrders['OrderAmount'] = currencyFormat($value);
							break;
							case 'montoDeposito':
								$serviceOrders['OrderDeposit'] = currencyFormat($value);
							break;
							case 'lotes':
								$serviceOrders['bulk'] = [];

								foreach($value AS $bulk) {
									$bulkList['bulkNumber'] = $bulk->acnumlote;
									$bulkList['bulkLoadDate'] = $bulk->dtfechorcarga;
									$bulkList['bulkLoadType'] = ucfirst(mb_strtolower($bulk->acnombre));
									$bulkList['bulkRecords'] = $bulk->ncantregs;
									$bulkList['bulkStatus'] = ucfirst(mb_strtolower($bulk->status));
									$bulkList['bulkAmount'] = currencyFormat($bulk->montoRecarga);
									$bulkList['bulkCommisAmount'] = currencyFormat($bulk->montoComision);
									$bulkList['bulkTotalAmount'] = currencyFormat($bulk->montoNeto);
									$bulkList['bulkId'] = $bulk->acidlote;
									$bulkList['bulkObservation'] = isset($bulk->obs) && ($bulk->cestatus == lang('CONF_STATUS_REJECTED')) ? $bulk->obs : '';

									$serviceOrders['bulk'][] = (object) $bulkList;
								}

								$serviceOrders['bulkEnabled'] = FALSE;
								foreach($serviceOrders['bulk'] AS $list) {
									if($list->bulkObservation != ''){
										$serviceOrders['bulkEnabled'] = TRUE;
									}
								}

							break;
						}
					}
					$serviceOrdersList[] = (object) $serviceOrders;
				}

				$this->session->set_flashdata('serviceOrdersList', $serviceOrdersList);
				$this->session->set_flashdata('requestOrdersList', $dataRequest);
			break;
			case -5:
				$this->response->title = 'Órdenes de servicio';
				$this->response->msg = lang('INQ_NO_SERVICE_ORDER');
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->modalBtn['btn1']['action'] = 'destroy';
			break;
			case -150:
				$this->response->title = 'Órdenes de servicio';
				$this->response->msg = novoLang(lang('RESP_SERVICE_ORDES'), $statusText);
				$this->response->icon = lang('CONF_ICON_INFO');
				$this->response->modalBtn['btn1']['action'] = 'destroy';
			break;
		}

		return $this->responseToTheView('callWs_GetServiceOrders');
	}
	/**
	 * @info Elimina un lote
	 * @author Luis Molina
	 * @date febrero 20 th, 2020
	 */
	public function callWs_ClearServiceOrders_Inquiries($dataRequest)
	{
		log_message('INFO', 'NOVO Inquiries Model: ClearServiceOrders Method Initialized');

		$this->dataAccessLog->modulo = 'Consultas';
		$this->dataAccessLog->function = 'Ordenes de servicio';
		$this->dataAccessLog->operation = 'Anular orden de servicio';

		$rifEmpresa=$this->session->userdata('enterpriseInf')->idFiscal;

		unset($dataRequest->modalReq);

		$this->dataRequest->idOperation = 'desconciliarOS';
		$this->dataRequest->className = 'com.novo.objects.TOs.OrdenServicioTO';
		$this->dataRequest->idOrden = $dataRequest->OrderNumber;
		$this->dataRequest->rifEmpresa = $this->session->enterpriseInf->idFiscal;

		$password = isset($dataRequest->pass) ? $this->cryptography->decryptOnlyOneData($dataRequest->pass) : $this->session->passWord;

		if (lang('CONF_HASH_PASS') == 'ON' && $this->singleSession == 'signIn') {
			$password = $this->session->passWord ?: md5($password);
		}

		$this->dataRequest->usuario = [
			'userName' => $this->userName,
			'password' => $password
		];

		$response = $this->sendToService('callWs_ClearServiceOrders');

		switch ($this->isResponseRc) {
			case 0:
				$this->response->cod = 0;
				$this->response->title = 'Anular Orden';
				$this->response->msg = 'La orden fue anulada exitosamente';
				$this->response->icon = lang('CONF_ICON_SUCCESS');
				$this->response->modalBtn['btn1']['action'] = 'destroy';
			break;
			case -1:
				$this->response->title = 'Anular Orden';
				$this->response->msg = lang('GEN_PASSWORD_NO_VALID');
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->modalBtn['btn1']['action'] = 'destroy';
			break;
		}

		return $this->responseToTheView('callWs_ClearServiceOrders');
	}
	/**
	 * @info Ver el detalle de un lote
	 * @author J. Enrique Peñaloza Piñero
	 * @date February 09th, 2020
	 * @modified J. Enrique Peñaloza Piñero
	 * @date April 17st, 2019
	 */
	public function callWs_BulkDetail_Inquiries($dataRequest)
	{
		log_message('INFO', 'NOVO Bulk Model: BulkDetail Method Initialized');

		$this->dataAccessLog->modulo = 'Consultas';
		$this->dataAccessLog->function = $dataRequest->bulkfunction;
		$this->dataAccessLog->operation = 'Ver detalle del lote';

		$this->dataRequest->idOperation = 'detalleLote';
		$this->dataRequest->className = 'com.novo.objects.MO.AutorizarLoteMO';
		$this->dataRequest->acidlote = $dataRequest->bulkId;

		$response = $this->sendToService('callWs_BulkDetail');

		$detailInfo = [
			'fiscalId' => '--',
			'enterpriseName' => '--',
			'bulkType' => '--',
			'bulkTypeText' => '--',
			'bulkNumber' => '--',
			'totalRecords' => '--',
			'loadUserName' => '--',
			'bulkDate' => '--',
			'bulkStatus' => '--',
			'bulkStatusText' => '--',
			'bulkAmount' => '--',
			'bulkHeader' => [],
			'bulkRecords' => [],
		];
		$tableContent = new stdClass();
		$tableContent->header = [];

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$detailInfo['fiscalId'] = $response->acrif;
				$detailInfo['enterpriseName'] = mb_strtoupper(mb_strtolower($response->acnomcia));
				$detailInfo['bulkType'] = $response->ctipolote;
				$detailInfo['bulkTypeText'] = mb_strtoupper(mb_strtolower($response->acnombre));
				$detailInfo['bulkNumber'] = $response->acnumlote;
				$detailInfo['totalRecords'] = $response->ncantregs;
				$detailInfo['loadUserName'] = trim($response->accodusuarioc);
				$detailInfo['bulkDate'] = $response->dtfechorcarga;
				$detailInfo['bulkStatus'] = $response->cestatus;
				$detailInfo['bulkStatusText'] = ucfirst(mb_strtolower($response->status));
				$detailInfo['bulkAmount'] = currencyFormat($response->nmonto);
				$detailInfo['bulkId'] = $response->acidlote;

				switch($response->ctipolote) {
					case '1':
					case '10':
						if(isset($response->registrosLoteEmision) && count($response->registrosLoteEmision) > 0) {
							$tableContent = BulkAttrEmissionA();
							$detailInfo['bulkRecords'] = $this->buildEmisionRecords_Bulk($response->registrosLoteEmision, $tableContent->body);
						}
					break;
					case '3':
					case '6':
					case 'A':
						if (isset($response->registrosLoteEmision) && count($response->registrosLoteEmision) > 0) {
							$tableContent = BulkAttrEmissionB();
							$detailInfo['bulkRecords'] = $this->buildEmisionRecords_Bulk($response->registrosLoteEmision, $tableContent->body);
						}
					break;
					case 'V':
						if (isset($response->registrosLoteEmision) && count($response->registrosLoteEmision) > 0) {
							$tableContent = BulkAttrEmissionC();
							$detailInfo['bulkRecords'] = $this->buildEmisionRecords_Bulk($response->registrosLoteEmision, $tableContent->body);
						}
					break;
					case '2':
						if (isset($response->registrosLoteRecarga) && count($response->registrosLoteRecarga) > 0) {
							$tableContent = BulkAttrCreditsA();
							$detailInfo['bulkRecords'] = $this->buildCreditRecords_Bulk($response->registrosLoteRecarga, $tableContent->body);
						}
					break;
					case '5':
					case 'L':
					case 'F':
						if (isset($response->registrosLoteRecarga) && count($response->registrosLoteRecarga) > 0) {
							$tableContent = BulkAttrCreditsB();
							$detailInfo['bulkRecords'] = $this->buildCreditRecords_Bulk($response->registrosLoteRecarga, $tableContent->body);
						}
					break;
					case 'M':
						if (isset($response->registrosLoteRecarga) && count($response->registrosLoteRecarga) > 0) {
							$tableContent = BulkAttrCreditsC();
							$detailInfo['bulkRecords'] = $this->buildCreditRecords_Bulk($response->registrosLoteRecarga, $tableContent->body);
						}
					break;
					case 'E':
						if(isset($response->registrosLoteGuarderia) && count($response->registrosLoteGuarderia) > 0) {
							$tableContent = BulkAttrKindergastenA();
							$detailInfo['bulkRecords'] = $this->buildKindergartenRecords_Bulk($response->registrosLoteGuarderia, $tableContent->body);
						}
					break;
					case 'G':
						if(isset($response->registrosLoteGuarderia) && count($response->registrosLoteGuarderia) > 0) {
							$tableContent = BulkAttrKindergastenB();
							$detailInfo['bulkRecords'] = $this->buildKindergartenRecords_Bulk($response->registrosLoteGuarderia, $tableContent->body);
						}
					break;
					case 'R':
					case 'C':
					case 'N':
						if(isset($response->registrosLoteReposicion) && count($response->registrosLoteReposicion) > 0) {
							$tableContent = BulkAttrReplacementA();
							$detailInfo['bulkRecords'] = $this->buildReplacement_Bulk($response->registrosLoteReposicion, $tableContent->body);
						}
					break;
					default:
						if(isset($response->registros) && count($response->registros->detalle) > 0) {
							array_shift($response->registros->ordenAtributos);
							$attrOrder = $response->registros->ordenAtributos;
							array_shift($response->registros->nombresColumnas);
							$headerName = $response->registros->nombresColumnas;

							foreach ($response->registros->nombresColumnas as $key => $value) {
								$value = ucfirst(mb_strtolower($value));
								array_push(
									$tableContent->header,
									$value
								);
							}

							foreach ($response->registros->detalle AS $key => $records) {
								$record = new stdClass();
								foreach ($attrOrder AS $attr) {
									if ($attr == 'NUMERO_CUENTA') {
										$records->$attr = maskString($records->$attr, 6, 4);
									}

									$record->$attr = $records->$attr;
								}

								array_push(
									$detailInfo['bulkRecords'],
									$record
								);
							}
						}
				}
			break;
		}

		$detailInfo['bulkHeader'] = $tableContent->header;
		$this->response->data->bulkInfo = (object) $detailInfo;

		return $this->responseToTheView('callWs_BulkDetail');
	}
	/**
	 * @info Construye el cuerpo de la tabla del detalle de un lote de emisión
	 * @author J. Enrique Peñaloza Piñero
	 * @date April 17th, 2020
	 * @modified J. Enrique Peñaloza Piñero
	 * @date October 01st, 2020
	 */
	private function buildEmisionRecords_Bulk($emisionRecords, $acceptAttr)
	{
		log_message('INFO', 'NOVO Inquiries Model: buildEmisionRecords Method Initialized');

		$bulkDetail = [];

		foreach($emisionRecords AS $key => $records) {
			$recordDetail = new stdClass();

			foreach ($acceptAttr AS $pos => $attr) {
				$recordDetail->$attr = $records->$attr ?? '';

				if ($attr == 'status') {
					$status = [
						'0' => 'En proceso',
						'1' => 'Procesado',
						'7' => 'Rechazado',
					];
					$recordDetail->$attr = is_numeric($records->$attr) ? $status[$records->$attr] : $records->$attr;
				}

				if ($attr == 'nombres') {
					$recordDetail->$attr = ucwords(mb_strtolower($recordDetail->$attr));
				}

				if ($attr == 'apellidos') {
					$recordDetail->$attr = ucwords(mb_strtolower($recordDetail->$attr));
				}

				if ($attr == 'typeIdentification') {
					$recordDetail->$attr = lang('GEN_RECOVER_DOC_TYPE')[$recordDetail->$attr];
				}
			}

			if (in_array('nombres', $acceptAttr) && in_array('apellidos', $acceptAttr)) {
				$recordDetail->nombres = $recordDetail->nombres.' '.$recordDetail->apellidos;
				unset($recordDetail->apellidos);
			}

			$bulkDetail[] = $recordDetail;
		}

		return $bulkDetail;
	}
	/**
	 * @info Construye el cuerpo de la tabla del detalle de un lote de recarga
	 * @author J. Enrique Peñaloza Piñero
	 * @date April 17th, 2020
	 * @modified J. Enrique Peñaloza Piñero
	 * @date October 01st, 2020
	 */
	private function buildCreditRecords_Bulk($creditRecords, $acceptAttr)
	{
		log_message('INFO', 'NOVO Inquiries Model: buildCreditRecords Method Initialized');

		$bulkDetail = [];

		foreach($creditRecords AS $key => $records) {
			$recordDetail = new stdClass();

			foreach ($acceptAttr AS $pos => $attr) {
				$recordDetail->$attr = $records->$attr ?? '';

				if ($attr == 'monto') {
					$recordDetail->$attr = currencyFormat($recordDetail->$attr);
				}

				if ($attr == 'nro_cuenta') {
					$recordDetail->$attr = maskString($recordDetail->$attr, 6, 4);
				}

				if ($attr == 'status') {
					$status = [
						'0' => 'Pendiente',
						'1' => 'Procesado',
						'2' => 'Inválida',
						'3' => 'En proceso',
						'6' => 'Procesado',
						'7' => 'Rechazado',
					];
					$recordDetail->$attr = is_numeric($records->$attr) ? $status[$records->$attr] : $records->$attr;
				}
			}

			$bulkDetail[] = $recordDetail;
		}

		return $bulkDetail;
	}
	/**
	 * @info Construye el cuerpo de la table del detalle de un lote de guardería
	 * @author J. Enrique Peñaloza Piñero
	 * @date April 17th, 2020
	 * @modified
	 * @date
	 */
	private function buildKindergartenRecords_Bulk($gardenRecords, $acceptAttr)
	{
		log_message('INFO', 'NOVO Inquiries Model: buildKindergartenRecords Method Initialized');
		$bulkDetail = [];

		foreach($emisionRecords AS $key => $records) {
			$recordDetail = new stdClass();

			foreach ($acceptAttr AS $pos => $attr) {
				$recordDetail->$attr = $records->$attr ?? '';

				if ($attr == 'nombre') {
					$recordDetail->$attr = ucwords(mb_strtolower($recordDetail->$attr));
				}

				if ($attr == 'apellido') {
					$recordDetail->$attr = ucwords(mb_strtolower($recordDetail->$attr));
				}

				if ($attr == 'nro_cuenta') {
					$recordDetail->$attr = maskString($recordDetail->$attr, 6, 4);
				}
			}

			if (in_array('nombre', $acceptAttr) && in_array('apellido', $acceptAttr)) {
				$recordDetail->nombre = $recordDetail->nombre.' '.$recordDetail->apellido;
				unset($recordDetail->apellidos);
			}

			$bulkDetail[] = $recordDetail;
		}

		return $bulkDetail;
	}
	/**
	 * @info Construir el cuerpo de la tabla del detalle de un lote de reposición
	 * @author J. Enrique Peñaloza Piñero
	 * @date April 17th, 2020
	 * @modified
	 * @date
	 */
	private function buildReplacement_Bulk($replaceRecords, $acceptAttr)
	{
		log_message('INFO', 'NOVO Inquiries Model: buildreplacement Method Initialized');

		$detailRecords = [];

		foreach($replaceRecords AS $records) {
			$record = new stdClass();
			foreach($records AS $pos => $value) {
				switch ($pos) {
					case 'nocuenta':
						if (in_array($pos, $acceptAttr)) {
							$record->cardHoldAccount = maskString($value, 6, 4);
						}
					break;
					case 'aced_rif':
						if (in_array($pos, $acceptAttr)) {
							$cardHoldId = $value != '' ? $value : '- -';
							$record->cardHoldId = $value;
						}
					break;
				}
			}

			$detailRecords[] = $record;

		}

		return $detailRecords;
	}
	/**
	 * @info Exporta archivo .pdf de una orden de servicio
	 * @author Luis Molina
	 * @date March 10th, 2020
	 * @mofied J. Enrique Peñaloza Piñero
	 * @date March 19th, 2020
	 */
	public function callWs_ExportFiles_Inquiries($dataRequest)
	{
		log_message('INFO', 'NOVO DownloadFiles Model: exportFiles Method Initialized');

		$this->dataAccessLog->modulo = 'Consultas';
		$this->dataAccessLog->function = 'Ordenes de servicio';
		$this->dataAccessLog->operation = 'Descargar pdf orden de servicio';

		$this->dataRequest->idOperation = 'visualizarOS';
		$this->dataRequest->className = 'com.novo.objects.TOs.OrdenServicioTO';
		$this->dataRequest->rifEmpresa = $this->session->userdata('enterpriseInf')->idFiscal;
		$this->dataRequest->acCodCia = $this->session->userdata('enterpriseInf')->enterpriseCode;
		$this->dataRequest->acprefix = $this->session->userdata('productInf')->productPrefix;
		$this->dataRequest->idOrden = $dataRequest->OrderNumber;

		$response = $this->sendToService('callWs_ExportFiles');

		switch ($this->isResponseRc) {
			case 0:
				$nameFile = ltrim($response->nombre, 'OS');
				$nameFile = rtrim($nameFile, '.pdf');
				exportFile($response->archivo, 'pdf', 'Orden_de_servicio'.$nameFile);
			break;
			case -52:
				$this->response->title = lang('GEN_ORDER_TITLE');
				$this->response->msg = lang('RESP_NO_BULK_AUTHORIZATION');
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->modalBtn['btn1']['action'] = 'destroy';
			break;
			default:
				$requestOrdersList = $this->session->flashdata('requestOrdersList');
				$this->load->model('Novo_inquiries_Model', 'getOrders');
				$response = $this->getOrders->callWs_GetServiceOrders_Inquiries($requestOrdersList);
				$this->response->code =  $response->code != 0 ? $response->code : 3;
				$this->response->title = $response->code != 0 ? $response->title : lang('GEN_DOWNLOAD_FILE');
				$this->response->msg = $response->code != 0 ? $response->msg : lang('GEN_WARNING_DOWNLOAD_FILE');
				$this->response->icon =  $response->code != 0 ? $response->icon : lang('CONF_ICON_WARNING');
				$this->response->download =  $response->modalBtn['btn1']['action'] == 'redirect' ? FALSE : TRUE;
				$this->response->modalBtn['btn1']['text'] = lang('GEN_BTN_ACCEPT');
				$this->response->modalBtn['btn1']['action'] = $response->code != 0 ? $response->modalBtn['btn1']['action'] : 'close';
				$this->session->set_flashdata('download', $this->response);
				redirect(base_url('consulta-orden-de-servicio'), 'location', 301);
		}
	}
}
