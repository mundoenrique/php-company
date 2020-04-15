<?php
defined('BASEPATH') OR  exit('No direct script access allowed');
/**
 * @info Controlador para manejar las peticiones referentes a consultas de ordenes de servicio
 * @author J. Enrique Peñaloza Piñero
 * @date January 09th, 2019
*/
class Novo_Inquiries extends NOVO_Controller {

	public function __construct()
	{
		parent :: __construct();
		log_message('INFO', 'NOVO Inquiries Controller Class Initialized');
	}
	/**
	 * @info Método para renderizar las empresas asociadas al usuario
	 * @author J. Enrique Peñaloza Piñero
	 * @date November 05th, 2019
	 */
	public function serviceOrders()
	{
		log_message('INFO', 'NOVO Inquiries: serviceOrders Method Initialized');

		$view = 'serviceOrders';

		array_push(
			$this->includeAssets->cssFiles,
			"third_party/dataTables-1.10.20"
		);
		array_push(
			$this->includeAssets->jsFiles,
			"third_party/dataTables-1.10.20",
			"third_party/jquery.validate",
			"validate".$this->render->newViews."-forms",
			"third_party/additional-methods",
			"inquiries/service_orders"
		);
		$renderOrderList = FALSE;
		$orderList = [];
		$result_order=FALSE;

		if($this->session->flashdata('serviceOrdersList')) {
			$orderList = $this->session->flashdata('serviceOrdersList');
			$renderOrderList = TRUE;
		}

		if($this->session->flashdata('response-order')) {
			$result_order = $this->session->flashdata('response-order');
			$this->responseAttr($result_order);
		}else{
			$this->responseAttr();
		}

		$this->load->model('Novo_Inquiries_Model', 'Inquiries');
		$responseList = $this->Inquiries->callWs_ServiceOrderStatus_Inquiries();
		$this->render->orderStatus = $responseList->data->orderStatus;
		$this->render->renderOrderList = $renderOrderList;
		$this->render->orderList = $orderList;
		$this->render->titlePage = lang('GEN_SERVICE_ORDERS_TITLE');
		$this->views = ['inquiries/'.$view];
		$this->loadView($view);
	}

	/**
	 * @info Método para renderizar el detalle de consulta de lotes
	 * @author Luis Molina
	 * @date Febrero 29Sat, 2020
	 */
	public function detailServiceOrders()
	{
		log_message('INFO', 'NOVO Inquiries: detailServiceOrders Method Initialized');

		if(!isset($this->request->numberOrder) && !$this->session->flashdata('detailServiceOrders'))  {
			redirect(base_url('detalle-producto'), 'location');
		}

		$view = 'detailServiceOrders';

		if($this->session->flashdata('detailServiceOrders')) {
			$response = $this->session->flashdata('detailServiceOrders');
		} else {
			$response = $this->loadModel($this->request);
		}

		if($this->session->flashdata('response-msg-detail-order')) {
			$result_detail_order = $this->session->flashdata('response-msg-detail-order');
			$this->responseAttr($result_detail_order);
		}else{
			$this->responseAttr();
		}

		array_push(
			$this->includeAssets->cssFiles,
			"third_party/dataTables-1.10.20"
		);
		array_push(
			$this->includeAssets->jsFiles,
			"third_party/dataTables-1.10.20",
			"inquiries/detail_service_orders",
			"business/widget-enterprise"
		);

		$this->render->detail = $response;
		$this->render->titlePage = lang('GEN_DETAIL_SERVICE_ORDERS_TITLE');
		$this->views = ['inquiries/'.$view];
		$this->loadView($view);
	}
}
