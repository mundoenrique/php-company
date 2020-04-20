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
		$this->className = 'com.novo.objects.MO.EstatusLotesMO';

		$this->dataAccessLog->modulo = 'Consultas';
		$this->dataAccessLog->function = 'Lista de ordenes de servicio';
		$this->dataAccessLog->operation = 'Estados de orden de servicio';

		$this->dataRequest->idOperation = 'estatusLotes';
		$this->dataRequest->tipoEstatus = 'TIPO_B';

		$response = $this->sendToService('ServiceOrderStatus');

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
			$this->response->code = 1;
			$orderStatus[] = (object) [
				'format' => '',
				'text' => lang('RESP_TRY_AGAIN')
			];
		}

		$this->response->data->orderStatus = (object) $orderStatus;

		return $this->responseToTheView('ServiceOrderStatus');
	}
	/**
	 * @info Método para obtener la lista de ordenes de servicio en rango de fecha dado
	 * @author J. Enrique Peñaloza Piñero
	 * @date Janury 09th, 2019
	 */
	public function callWs_GetServiceOrders_Inquiries($dataRequest)
	{

		log_message('INFO', 'NOVO Inquiries Model: ServiceOrderStatus Method Initialized');

		$this->className = 'com.novo.objects.MO.ListadoOrdenServicioMO';
		$this->dataAccessLog->modulo = 'Consultas';
		$this->dataAccessLog->function = 'Ordenes de servicio';
		$this->dataAccessLog->operation = 'Lista de ordenes de servicio';

		$this->dataRequest->idOperation = 'buscarOrdenServicio';
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
								$serviceOrders['OrderCommission'] = $value;
								break;
							case 'montoIVA':
								$serviceOrders['OrderTax'] = $value;
								break;
							case 'montoOS':
								$serviceOrders['OrderAmount'] = $value;
								break;
							case 'montoDeposito':
								$serviceOrders['OrderDeposit'] = $value;
								break;
							case 'lotes':
								$serviceOrders['bulk'] = [];
								foreach($value AS $bulk) {
									$bulkList['bulkNumber'] = $bulk->acnumlote;
									$bulkList['bulkLoadDate'] = $bulk->dtfechorcarga;
									$bulkList['bulkLoadType'] = ucfirst(mb_strtolower($bulk->acnombre));
									$bulkList['bulkRecords'] = $bulk->ncantregs;
									$bulkList['bulkStatus'] = ucfirst(mb_strtolower($bulk->status));
									$bulkList['bulkAmount'] = floatval($bulk->montoRecarga);
									$bulkList['bulkCommisAmount'] = floatval($bulk->montoComision);
									$bulkList['bulkTotalAmount'] = floatval($bulk->montoRecarga) + floatval($bulk->montoComision);
									$bulkList['bulkId'] = $bulk->acidlote;
									$serviceOrders['bulk'][] = (object) $bulkList;
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
				$this->response->title = 'Ordenes de servicio';
				$this->response->msg = 'No fue posible obtener las ordenes de servicio';
				$this->response->icon = lang('GEN_ICON_WARNING');
				if($this->input->is_ajax_request()) {
					$this->response->data['btn1']['action'] = 'close';
				} else {
					$this->response->data->resp['btn1']['action'] = 'close';
				}
				break;
			case -150:
				$this->response->title = 'Ordenes de servicio';
				$this->response->msg = novoLang(lang('RESP_SERVICE_ORDES'), $statusText);
				$this->response->icon = lang('GEN_ICON_INFO');
				if($this->input->is_ajax_request()) {
					$this->response->data['btn1']['action'] = 'close';
				} else {
					$this->response->data->resp['btn1']['action'] = 'close';
				}
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

		$this->className = 'com.novo.objects.TOs.OrdenServicioTO';
		$this->dataAccessLog->modulo = 'Consultas';
		$this->dataAccessLog->function = 'Ordenes de servicio';
		$this->dataAccessLog->operation = 'Anular orden de servicio';

		$rifEmpresa=$this->session->userdata('enterpriseInf')->idFiscal;

		unset($dataRequest->modalReq);

		$this->dataRequest->idOperation = 'desconciliarOS';
		$this->dataRequest->idOS = $dataRequest->OrderNumber;
		$this->dataRequest->rifEmpresa = $this->session->enterpriseInf->idFiscal;

		$password = json_decode(base64_decode($dataRequest->pass));
		$password = $this->cryptography->decrypt(
			base64_decode($password->plot),
			utf8_encode($password->password)
		);
		$this->dataRequest->usuario = [
			'userName' => $this->userName,
			'password' => md5($password)
		];

		$response = $this->sendToService('ClearServiceOrders');

		switch ($this->isResponseRc) {
			case 0:
				$this->response->cod = 0;
				$this->response->title = 'Anular Orden';
				$this->response->msg = 'La orden fue anulada exitosamente';
				$this->response->icon = lang('GEN_ICON_SUCCESS');
				$this->response->data['btn1'] = [
					'text' => lang('GEN_BTN_ACCEPT'),
					'action' => 'close'
				];
				break;
			case -1:
				$this->response->title = lang('BULK_DELETE_TITLE');
				$this->response->msg = lang('RESP_PASSWORD_NO_VALID');
				$this->response->icon = lang('GEN_ICON_WARNING');
				$this->response->data['btn1'] = [
					'text' => lang('GEN_BTN_ACCEPT'),
					'action' => 'close'
				];
				break;
		}

		return $this->responseToTheView('ClearServiceOrders');
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

		$this->className = 'com.novo.objects.MO.AutorizarLoteMO';
		$this->dataAccessLog->modulo = 'Consultas';
		$this->dataAccessLog->function = $dataRequest->bulkfunction;
		$this->dataAccessLog->operation = 'Ver detalle del lote';

		$this->dataRequest->idOperation = 'detalleLote';
		$this->dataRequest->acidlote = $dataRequest->bulkId;

		$response = $this->sendToService('BulkDetail');

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
		$bulkRecordsHeader = [];

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$detailInfo['fiscalId'] = $response->acrif;
				$detailInfo['enterpriseName'] = mb_strtoupper(mb_strtolower($response->acnomcia));
				$detailInfo['bulkType'] = $response->ctipolote;
				$detailInfo['bulkTypeText'] = mb_strtoupper(mb_strtolower($response->acnombre));
				$detailInfo['bulkNumber'] = $response->acnumlote;
				$detailInfo['totalRecords'] = $response->ncantregs;
				$detailInfo['loadUserName'] = mb_strtoupper(mb_strtolower($response->accodusuarioc));
				$detailInfo['bulkDate'] = $response->dtfechorcarga;
				$detailInfo['bulkStatus'] = $response->cestatus;
				$detailInfo['bulkStatusText'] = ucfirst(mb_strtolower($response->status));
				$detailInfo['bulkAmount'] = $response->montoNeto;


				switch($response->ctipolote) {
					case '1':
					case '10':
						if(isset($response->registrosLoteEmision) && count($response->registrosLoteEmision) > 0) {
							$bulkRecordsHeader = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_FULL_NAME'), lang('GEN_TABLE_STATUS')];
							$detailInfo['bulkRecords'] = $this->buildEmisionRecords_Bulk($response->registrosLoteEmision, $response->ctipolote);
						}
						break;
					case '3':
					case '6':
					case 'A':
						if(isset($response->registrosLoteEmision) && count($response->registrosLoteEmision) > 0) {
							$bulkRecordsHeader = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_FULL_NAME'), lang('GEN_TABLE_ACCOUNT_NUMBER')];
							$detailInfo['bulkRecords'] = $this->buildEmisionRecords_Bulk($response->registrosLoteEmision, $response->ctipolote);
						}
						break;
					case '2':
						if(isset($response->registrosLoteRecarga) && count($response->registrosLoteRecarga) > 0) {
							$bulkRecordsHeader = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_AMOUNT'), lang('GEN_TABLE_ACCOUNT_NUMBER')];
							$detailInfo['bulkRecords'] = $this->buildCreditRecords_Bulk($response->registrosLoteRecarga, $response->ctipolote);
						}
						break;
					case '5':
					case 'L':
					case 'M':
						if(isset($response->registrosLoteRecarga) && count($response->registrosLoteRecarga) > 0) {
							$bulkRecordsHeader = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_AMOUNT'), lang('GEN_TABLE_ACCOUNT_NUMBER'), lang('GEN_TABLE_STATUS')];
							$detailInfo['bulkRecords'] = $this->buildCreditRecords_Bulk($response->registrosLoteRecarga, $response->ctipolote);
						}
						break;
					case 'E':
						if(isset($response->registrosLoteGuarderia) && count($response->registrosLoteGuarderia) > 0) {
							$bulkRecordsHeader = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_EMPLOYEE'), lang('GEN_TABLE_BENEFICIARY')];
							$detailInfo['bulkRecords'] = $this->buildKindergartenRecords_Bulk($response->registrosLoteGuarderia, $response->ctipolote);
						}
						break;
					case 'G':
						if(isset($response->registrosLoteGuarderia) && count($response->registrosLoteGuarderia) > 0) {
							$bulkRecordsHeader = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_EMPLOYEE'), lang('GEN_TABLE_BENEFICIARY'), lang('GEN_TABLE_ACCOUNT_BENEFICIARY')];
							$detailInfo['bulkRecords'] = $this->buildKindergartenRecords_Bulk($response->registrosLoteGuarderia, $response->ctipolote);
						}
						break;
					case 'R':
					case 'C':
					case 'N':
						if(isset($response->registrosLoteReposicion) && count($response->registrosLoteReposicion) > 0) {
							$bulkRecordsHeader = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_ACCOUNT_NUMBER')];
							$detailInfo['bulkRecords'] = $this->buildReplacement_Bulk($response->registrosLoteReposicion, $response->ctipolote);
						}
						break;
					default:
						if(isset($response->registros) && count($response->registros) > 0) {
							array_shift($response->registros->ordenAtributos);
							$attrOrder = $response->registros->ordenAtributos;
							array_shift($response->registros->nombresColumnas);
							$headerName = $response->registros->nombresColumnas;

							foreach ($response->registros->nombresColumnas as $key => $value) {
								$value = ucfirst(mb_strtolower($value));
								array_push(
									$bulkRecordsHeader,
									$value
								);
							}

							foreach ($response->registros->detalle AS $key => $records) {
								$record = new stdClass();
								foreach ($attrOrder AS $attr) {
									if($attr == 'NUMERO_CUENTA') {
										$records->$attr = maskString($records->$attr, 4, 6);
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

		$detailInfo['bulkHeader'] = $bulkRecordsHeader;
		$this->response->data->bulkInfo = (object) $detailInfo;

		return $this->responseToTheView('BulkDetail');
	}
	/**
	 * @info Construye el cuerpo de la tabla del detalle de un lote de emisión
	 * @author J. Enrique Peñaloza Piñero
	 * @date April 17th, 2020
	 * @modified
	 * @date
	 */
	private function buildEmisionRecords_Bulk($emisionRecords, $bulkType)
	{
		log_message('INFO', 'NOVO Inquiries Model: buildEmisionRecords Method Initialized');

		$detailRecords = [];

		foreach($emisionRecords AS $records) {
			$record = new stdClass();
			foreach($records AS $pos => $value) {
				switch ($pos) {
					case 'idExtPer':
						$record->cardHoldId = $value;
						break;
					case 'idExtEmp':
						if(!isset($records->idExtPer)) {
							$bulkRecordsHeader[0] = lang('GEN_FISCAL_REGISTRY');
							$record->cardHoldId = $value;
						}
						break;
					case 'nombres':
						$record->cardHoldName = ucwords(mb_strtolower($value));
						break;
					case 'apellidos':
						$record->cardHoldLastName = ucwords(mb_strtolower($value));
						break;
					case 'nroTarjeta':
						$record->cardnumber = maskString($value, 6, 4);
						break;
					case 'status':
						$status = [
							'0' => 'En proceso',
							'1' => 'Procesado',
							'7' => 'Rechazado',
						];
						$record->bulkstatus = is_numeric($value) ? $status[$value] : $value;
						break;
				}
			}
			$record->cardHoldName = $record->cardHoldName.' '.$record->cardHoldLastName;
			unset($record->cardHoldLastName);
			$detailRecords[] = $record;
		}

		return $detailRecords;
	}
	/**
	 * @info Construye el cuerpo de la tabla del detalle de un lote de recarga
	 * @author J. Enrique Peñaloza Piñero
	 * @date April 17th, 2020
	 * @modified
	 * @date
	 */
	private function buildCreditRecords_Bulk($creditRecords, $bulkType)
	{
		log_message('INFO', 'NOVO Inquiries Model: buildCreditRecords Method Initialized');

		$detailRecords = [];

		foreach($creditRecords AS $records) {
			$record = new stdClass();
			foreach($records AS $pos => $value) {
				switch ($pos) {
					case 'id_ext_per':
						$record->cardHoldId = $value;
						break;
						case 'monto':
							$record->cardHoldAmount = $value;
						break;
					case 'nro_cuenta':
						$record->cardHoldAccount = maskString($value, 6, 4);
						break;
					case 'status':
						if($bulkType == '2'){
							continue;
						}

						if($bulkType == '5') {
							$status = [
								'3' => 'En proceso',
								'6' => 'Procesada',
								'7' => 'Rechazado',
							];
						}

						if($bulkType == 'L' || $bulkType == 'M') {
							$status = [
								'0' => 'Pendiente',
								'1' => 'Procesada',
								'2' => 'Inválida',
								'7' => 'Rechazado',
							];
						}

						$record->bulkstatus = is_numeric($value) ? $status[$value] : $value;
						break;
				}
			}
			$detailRecords[] = $record;
		}

		return $detailRecords;
	}
	/**
	 * @info Construye el cuerpo de la table del detalle de un lote de guardería
	 * @author J. Enrique Peñaloza Piñero
	 * @date April 17th, 2020
	 * @modified
	 * @date
	 */
	private function buildKindergartenRecords_Bulk($gardenRecords, $bulkType)
	{
		log_message('INFO', 'NOVO Inquiries Model: buildKindergartenRecords Method Initialized');

		$detailRecords = [];

		foreach($gardenRecords AS $records) {
			$record = new stdClass();
			foreach($records AS $pos => $value) {
				switch ($pos) {
					case 'id_per':
						$record->cardHoldId = $value;
						break;
					case 'nombre':
						$record->cardHoldName = ucwords(mb_strtolower($value));
						break;
					case 'apellido':
						$record->cardHoldLastName = ucwords(mb_strtolower($value));
						break;
						case 'beneficiario':
							$record->cardHoldbeneficiary = $value;
						break;
					case 'nro_cuenta':
						if ($bulkType == 'G') {
							continue;
						}

						$record->cardHoldAccount = maskString($value, 6, 4);
						break;
				}
			}

			$record->cardHoldName = $record->cardHoldName.' '.$record->cardHoldLastName;
			unset($record->cardHoldLastName);
			$detailRecords[] = $record;
		}

		return $detailRecords;
	}
	/**
	 * @info Construir el cuerpo de la tabla del detalle de un lote de reposición
	 * @author J. Enrique Peñaloza Piñero
	 * @date April 17th, 2020
	 * @modified
	 * @date
	 */
	private function buildReplacement_Bulk($replaceRecords, $bulkType)
	{
		log_message('INFO', 'NOVO Inquiries Model: buildreplacement Method Initialized');

		$detailRecords = [];

		foreach($replaceRecords AS $records) {
			$record = new stdClass();
			foreach($records AS $pos => $value) {
				switch ($pos) {
					case 'aced_rif':
						$cardHoldId = $value != '' ? $value : '';
						$record->cardHoldId = $value;
						break;
					case 'nocuenta':
						$record->cardHoldAccount = maskString($value, 6, 4);
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
	public function callWs_exportFiles_Inquiries($dataRequest)
	{
		log_message('INFO', 'NOVO DownloadFiles Model: exportFiles Method Initialized');

		$this->className = 'com.novo.objects.TOs.OrdenServicioTO';
		$this->dataAccessLog->modulo = 'Consultas';
		$this->dataAccessLog->function = 'Ordenes de servicio';
		$this->dataAccessLog->operation = 'Descargar pdf orden de servicio';

		$this->dataRequest->idOperation = 'visualizarOS';
		$this->dataRequest->rifEmpresa = $this->session->userdata('enterpriseInf')->idFiscal;
		$this->dataRequest->acCodCia = $this->session->userdata('enterpriseInf')->enterpriseCode;
		$this->dataRequest->acprefix = $this->session->userdata('productInf')->productPrefix;
		$this->dataRequest->idOrden = $dataRequest->OrderNumber;

		$response = $this->sendToService('exportFiles');

		switch ($this->isResponseRc) {
			case 0:
				$nameFile = ltrim($response->nombre, 'OS');
				$nameFile = rtrim($nameFile, '.pdf');
				exportFile($response->archivo, 'pdf', 'Orden_de_servicio'.$nameFile);
				break;
			default:
				$requestOrdersList = $this->session->flashdata('requestOrdersList');
				$this->load->model('Novo_inquiries_Model', 'getOrders');
				$response = $this->getOrders->callWs_GetServiceOrders_Inquiries($requestOrdersList);
				$this->response->code =  $response->code != 0 ? $response->code : 3;
				$this->response->title = $response->code != 0 ? $response->title : lang('GEN_DOWNLOAD_FILE');
				$this->response->msg = $response->code != 0 ? $response->msg : lang('GEN_WARNING_DOWNLOAD_FILE');
				$this->response->icon =  $response->code != 0 ? $response->icon : lang('GEN_ICON_WARNING');
				$this->response->download =  $response->data->resp['btn1']['action'] == 'redirect' ? FALSE : TRUE;
				$this->response->data->resp['btn1']['text'] = lang('GEN_BTN_ACCEPT');
				$this->response->data->resp['btn1']['action'] = $response->code != 0 ? $response->data->resp['btn1']['action'] : 'close';
				$this->session->set_flashdata('download', $this->response);
				redirect(base_url('consulta-orden-de-servicio'), 'location', 301);
		}
	}
}
