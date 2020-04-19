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
	 * @info Método para obtener las ordenes de servicio
	 * @author J. Enrique Peñaloza Piñero
	 * @date Janury 09th, 2019
	 */
	public function callWs_GetServiceOrders_Inquiries($dataRequest)
	{

		log_message('INFO', 'NOVO Inquiries Model: ServiceOrderStatus Method Initialized');
		$this->className = 'com.novo.objects.MO.ListadoOrdenServicioMO';

		$this->dataAccessLog->modulo = 'Consultas';
		$this->dataAccessLog->function = 'Lista de ordenes de servicio';
		$this->dataAccessLog->operation = 'Ordenes de servicios';

		$this->dataRequest->idOperation = 'buscarOrdenServicio';
		$this->dataRequest->rifEmpresa = $this->session->enterpriseInf->idFiscal;
		$this->dataRequest->acCodCia = $this->session->enterpriseInf->enterpriseCode;
		$this->dataRequest->idProducto = $this->session->productInf->productPrefix;
		$this->dataRequest->fechaIni = $dataRequest->initialDate;
		$this->dataRequest->fechaFin = $dataRequest->finalDate;
		$this->dataRequest->status = $dataRequest->status;
		$this->dataRequest->statusText = $dataRequest->statusText;
		$statusText = $dataRequest->statusText;

   	$response = $this->sendToService('ServiceOrderStatus');

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->data = lang('GEN_LINK_CONS_ORDERS_SERV');

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
				$this->session->set_userdata('serviceOrdersListMemory', $serviceOrdersList);
				break;
			case -5:
				$this->response->title = 'Generar orden de servicio';
				$this->response->msg = 'No fue posible generar la orden de servicio';
				$this->response->icon = lang('GEN_ICON_WARNING');
				$this->response->data['btn1']['action'] = 'close';
				break;
			case -150:
				$this->response->title = 'Ordenes de servicio';
				$this->response->msg = novoLang(lang('RESP_SERVICE_ORDES'), $statusText);
				$this->response->icon = lang('GEN_ICON_INFO');
				$this->response->data['btn1']['action'] = 'close';
				break;
		}
			$serviceOrdersList = $this->session->flashdata('serviceOrdersList');
			$bulkNotBillable = $this->session->flashdata('bulkNotBillable');
			$this->session->set_flashdata('serviceOrdersList', $serviceOrdersList);
			$this->session->set_flashdata('bulkNotBillable', $bulkNotBillable);

			return $this->responseToTheView('ServiceOrder');
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
		$this->dataAccessLog->modulo = 'anularOS';
		$this->dataAccessLog->function = 'anularOS';
		$this->dataAccessLog->operation = 'Anular orden de servicio';

		$rifEmpresa=$this->session->userdata('enterpriseInf')->idFiscal;

		unset($dataRequest->modalReq);

		$this->dataRequest->idOperation = 'desconciliarOS';
		$this->dataRequest->idOS = $dataRequest->idOS;
		$this->dataRequest->rifEmpresa = $rifEmpresa;

		$password = json_decode(base64_decode($dataRequest->pass));
		$password = $this->cryptography->decrypt(
			base64_decode($password->plot),
			utf8_encode($password->passWord)
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
				$bulkRecordsHeader = [];

				switch($response->ctipolote) {
					case '1':
					case '10':
						if(isset($response->registrosLoteEmision) && count($response->registrosLoteEmision) > 0) {
							$bulkRecordsHeader = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_FULL_NAME'), lang('GEN_TABLE_STATUS')];
							$detailInfo['bulkRecords'] = $this->buildEmisionRecords_Bulk($response->registrosLoteEmision);
						}
						break;
					case '3':
					case '6':
					case 'A':
						if(isset($response->registrosLoteEmision) && count($response->registrosLoteEmision) > 0) {
							$bulkRecordsHeader = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_FULL_NAME'), lang('GEN_TABLE_ACCOUNT_NUMBER')];
							$detailInfo['bulkRecords'] = $this->buildEmisionRecords_Bulk($response->registrosLoteEmision);
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
						break;
				}
				break;
		}

		$detailInfo['bulkHeader'] = $bulkRecordsHeader;
		$this->response->data->bulkInfo = (object) $detailInfo;

		return $this->responseToTheView('BulkDetail');
	}
	/**
	 * @info Construir el cuerpo de la table del detalle de un lote de emisión
	 * @author J. Enrique Peñaloza Piñero
	 * @date April 17th, 2020
	 * @modified
	 * @date
	 */
	private function buildEmisionRecords_Bulk($emisionRecords)
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
	 * @info Construir el cuerpo de la table del detalle de un lote de recarga
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
	 * @info Construir el cuerpo de la table del detalle de un lote de guardería
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
						if($bulkType == 'G'){
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
	 * @info Consulta detalle ordenes de servicios
	 * @author Luis Molina
	 * @date Mar 10 Tue, 2020
	 */
	public function callWs_DetailServiceOrders_Inquiries($dataRequest)
	{
		log_message('INFO', 'NOVO Inquiries Model: DetailServiceOrders Method Initialized');

		$this->className = 'com.novo.objects.TOs.LoteTO';
		$this->dataAccessLog->modulo = 'Ordenes de servicio';
		$this->dataAccessLog->function = 'Detalle de la orden de servicio';
		$this->dataAccessLog->operation = 'Detalle Lote';

		$this->dataRequest->idOperation = 'detalleLote';
		$this->dataRequest->acidlote = $dataRequest->bulk_id;

		$response = $this->sendToService('DetailServiceOrders');

		$definitive['acrif'] = $response->acrif;
		$definitive['acnomcia'] = $response->acnomcia;
		$definitive['acnombre'] = $response->acnombre;
		$definitive['acnumlote'] = $response->acnumlote;
		$definitive['ncantregs'] = $response->ncantregs;
		$definitive['accodusuarioc'] = $response->accodusuarioc;
		$definitive['dtfechorcarga'] = $response->dtfechorcarga;
		$definitive['status'] = $response->status;
		$definitive['nmonto'] = $response->nmonto;
		$definitive['acidlote'] = $response->acidlote;

		foreach($response->registrosLoteRecarga AS $key => $final) {
			$final_2['id_ext_per']=$final->id_ext_per;
			$final_2['nro_cuenta']=$final->nro_cuenta;
			$final_2['monto']=$final->monto;
			$definitive_2[]= (object) $final_2;
		}

		$definitive['registrosLoteRecarga'] = $definitive_2;

		switch ($this->isResponseRc) {
			case 0:
				$this->response->detail= (object) $definitive;
				$this->session->set_flashdata('detailServiceOrders',$this->response);
				$this->session->set_userdata('detailServiceOrdersMemory', $this->response);
				break;
		}
			return $this->responseToTheView('DetailServiceOrders');
	}

	/**
	 * @info Exporta archivo .pdf en ordenes de servicios
	 * @author Luis Molina
	 * @date Mar 10 Tue, 2020
	 */
	public function callWs_exportFiles_Inquiries($dataRequest)
	{
		log_message('INFO', 'NOVO DownloadFiles Model: exportFiles Method Initialized');

		$rifEmpresa = $this->session->userdata('enterpriseInf')->idFiscal;
		$accodciaS = $this->session->userdata('enterpriseInf')->enterpriseCode;
		$acprefix = $this->session->userdata('productInf')->productPrefix;

		$this->dataAccessLog->modulo = 'descargarPDFOS';
		$this->dataAccessLog->function = 'descargarPDFOS';
		$this->dataAccessLog->operation = 'visualizarOS';

		$this->className = 'com.novo.objects.TOs.OrdenServicioTO';
		$this->dataRequest->idOperation = 'visualizarOS';
		$this->dataRequest->rifEmpresa = $rifEmpresa;
		$this->dataRequest->acCodCia = $accodciaS;
		$this->dataRequest->acprefix = $acprefix;
		$this->dataRequest->idOrden =$dataRequest->idOS;

		$response = $this->sendToService('exportFiles');

		switch ($this->isResponseRc) {
			case 0:
			exportFile($response->archivo,'pdf',str_replace(' ', '_', 'OrdenServicio'.date("d/m/Y H:i")));
			exit;
			break;
			default:
			$this->response->code = 3;
			$this->response->downloadModel = TRUE;
			$this->response->title = lang('GEN_DOWNLOAD_FILE');
			$this->response->msg = lang('GEN_WARNING_DOWNLOAD_FILE');
			$this->response->icon = lang('GEN_ICON_WARNING');
			$this->response->data->resp['btn1']['action'] = 'close';
			$this->session->set_flashdata('response-order',$this->response);
			$this->session->set_flashdata('serviceOrdersList',$this->session->userdata('serviceOrdersListMemory'));
			$this->session->unset_userdata('serviceOrdersListMemory');
			redirect(base_url('consulta-orden-de-servicio'), 'location');
		}
	}

	/**
	 * @info Exporta archivo .pdf,.xls en detalle ordenes de servicios
	 * @author Luis Molina
	 * @date Mar 10 Tue, 2020
	 */
	public function callWs_DetailExportFiles_Inquiries($dataRequest)
	{
		log_message('INFO', 'NOVO Inquiries Model: DetailExportFiles Method Initialized');

		$operation='detalleLoteExcel';

    if ($dataRequest->file_type=='pdf'){
			$operation='detalleLotePDF';
		}

		$this->dataAccessLog->modulo = 'lotes';
		$this->dataAccessLog->function = 'verdetallelote';
		$this->dataAccessLog->operation = 'Ver detalle Lote';
		$this->className = 'com.novo.objects.TOs.LoteTO';
		$this->dataRequest->idOperation = $operation;
		$this->dataRequest->acidlote =$dataRequest->data_lote;
		$this->dataRequest->numberOrder=$dataRequest->data_lote;

		$response = $this->sendToService('DetailExportFiles');

		switch ($this->isResponseRc) {
			case 0:
			exportFile($response->archivo,$dataRequest->file_type,$response->nombre);
			break;
			default:
			$this->response->code = 3;
			$this->response->downloadModel = TRUE;
			$this->response->title = lang('GEN_DOWNLOAD_FILE');
			$this->response->msg = lang('GEN_WARNING_DOWNLOAD_FILE');
			$this->response->icon = lang('GEN_ICON_WARNING');
			$this->response->data->resp['btn1']['action'] = 'close';
			$this->session->set_flashdata('detailServiceOrders', $this->session->userdata('detailServiceOrdersMemory'));
			$this->session->unset_userdata('detailServiceOrdersMemory');
			$this->session->set_flashdata('response-msg-detail-order',$this->response);
			redirect(base_url('detalle-orden-de-servicio'), 'location');
		}
	}
}
