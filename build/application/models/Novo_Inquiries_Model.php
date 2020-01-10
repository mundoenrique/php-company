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
	 * @info Método para obtener las ordenes de servicio
	 * @author J. Enrique Peñaloza Piñero
	 * @date Janury 09th, 2019
	 */
	public function callWs_ServiceOrderStatus_Inquiries()
	{
		log_message('INFO', 'NOVO Inquiries Model: ServiceOrderStatus Method Initialized');
		$this->className = '';

		$this->dataAccessLog->modulo = 'Consultas';
		$this->dataAccessLog->function = 'Lista de ordenes de servicio';
		$this->dataAccessLog->operation = 'Estados de orden de servicio';

		$this->dataRequest->idOperation = 'estatusLotes';
		$this->dataRequest->tipoEstatus = 'TIPO_B';

		$response = $this->sendToService('ServiceOrderStatus');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				log_message('DEBUG', 'NOVO ['.$this->userName.'] RESPONSE: ServiceOrderStatus: ' . json_encode($response));
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
									$serviceOrders['bulk'][] = (object) $bulkList;
								}
								break;
						}
					}

					$serviceOrdersList[] = (object) $serviceOrders;
				}

				$this->session->set_flashdata('serviceOrdersList', $serviceOrdersList);
				break;

			case -5:
			$this->response->title = 'Generar orden de servicio';
			$this->response->msg = 'No fue posible generar la orden de servicio';
			$this->response->icon = lang('GEN_ICON_WARNING');
			$this->response->data['btn1']['action'] = 'close';
				break;
		}

		$serviceOrdersList = $this->session->flashdata('serviceOrdersList');
		$bulkNotBillable = $this->session->flashdata('bulkNotBillable');
		$this->session->set_flashdata('serviceOrdersList', $serviceOrdersList);
		$this->session->set_flashdata('bulkNotBillable', $bulkNotBillable);

		return $this->responseToTheView('ServiceOrder');
	}
}
