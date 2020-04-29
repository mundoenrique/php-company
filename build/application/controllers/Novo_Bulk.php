<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Controlador acciones relacionadas con los lotes
 * @author J. Enrique Peñaloza Piñero
 * @date December 7th, 2019
*/
class Novo_Bulk extends NOVO_Controller {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO Bulk Controller Class Initialized');
	}
	/**
	 * @info Método para renderizar los lotes pendientes y cargar nuevos lotes
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 7th, 2019
	 */
	public function getPendingBulk()
	{
		log_message('INFO', 'NOVO Bulk: getPendingBulk Method Initialized');

		$view = 'getPendingBulk';
		array_push(
			$this->includeAssets->cssFiles,
			"third_party/dataTables-1.10.20"
		);
		array_push(
			$this->includeAssets->jsFiles,
			"third_party/dataTables-1.10.20",
			"third_party/fileupload-10.4.0",
			"third_party/jquery.validate",
			"validate".$this->render->newViews."-forms",
			"third_party/additional-methods",
			"bulk/load_bulk"
		);
		$responseList = $this->loadModel();
		$this->request->newGet = $responseList->code;
		$this->responseAttr($responseList);
		$this->load->model('Novo_Bulk_Model', 'Bulk');
		$typesLot = $this->Bulk->callWs_getTypeLots_Bulk($this->request);
		$this->render->typesLot = $typesLot->data->typesLot;

		if(verifyDisplay('body', $view,  lang('GEN_TAG_BRANCHOFFICE'))) {
			$this->request->select = true;
			$this->load->model('Novo_Business_Model', 'Business');
			$this->request->newGet = $typesLot->code;
			$branchOffices = $this->Business->callWs_GetBranchOffices_Bulk($this->request);
			$this->render->branchOffices = $branchOffices->data->branchOffices;
		}

		$this->render->pendingBulk = $responseList->data->pendingBulk;
		$this->render->titlePage = lang('GEN_MENU_BULK_LOAD');
		$this->views = ['bulk/'.$view];
		$this->loadView($view);
	}
	/**
	 * @info Método para renderizar el detalle del lote
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 16th, 2019
	 */
	public function getDetailBulk()
	{
		log_message('INFO', 'NOVO Bulk: getDetailBulk Method Initialized');

		if(!isset($this->request->bulkView) || $this->request->bulkView != 'detail') {
			redirect(base_url('detalle-producto'), 'location');
		}

		$view = 'seeBulkDetail';
		$responseDetail = $this->loadModel($this->request);
		$this->responseAttr($responseDetail);
		$this->render->detailBulk = $responseDetail->data->detailBulk;
		$this->render->titlePage = lang('GEN_DETAIL_BULK_TITLE');
		$this->views = ['bulk/'.$view];
		$this->loadView($view);
	}
	/**
	 * @info Método para confirmar el lote
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 16th, 2019
	 */
	public function confirmBulk()
	{
		log_message('INFO', 'NOVO Bulk: confirmBulk Method Initialized');

		if(!isset($this->request->bulkView) || $this->request->bulkView != 'confirm') {
			redirect(base_url('detalle-producto'), 'location');
		}

		$view = 'confirmBulk';
		array_push(
			$this->includeAssets->jsFiles,
			"third_party/jquery.validate",
			"validate".$this->render->newViews."-forms",
			"third_party/additional-methods",
			"bulk/confirm_bulk"
		);
		$this->load->model('Novo_Bulk_Model', 'Bulk');
		$responseDetail = $this->Bulk->callWs_GetDetailBulk_Bulk($this->request);
		$this->responseAttr($responseDetail);
		$this->render->detailBulk = $responseDetail->data->detailBulk;
		$this->render->titlePage = lang('GEN_CONFIRM_BULK_TITLE');
		$this->views = ['bulk/'.$view];
		$this->loadView($view);
	}
	/**
	 * @info Método para autorizar un lote
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 25th, 2019
	 */
	public function authorizeBulkList()
	{
		log_message('INFO', 'NOVO Bulk: authorizeBulkList Method Initialized');

		/* if(!isset($this->request->bulkView) || $this->request->bulkView != 'confirm') {
			redirect(base_url('detalle-producto'), 'location');
		} */

		$view = 'authorizeBulkList';
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
			"bulk/authorize_bulk"
		);

		if($this->session->flashdata('bulkList')) {
			$responseList = $this->session->flashdata('bulkList');
		} else {
			$responseList = $this->loadModel();
		}

		$this->responseAttr($responseList);
		$this->render->signBulk = $responseList->data->signBulk;
		$this->render->authorizeBulk = $responseList->data->authorizeBulk;
		$this->render->authorizeAttr = $responseList->data->authorizeAttr;
		$this->render->titlePage = lang('GEN_AUTHORIZE_BULK_TITLE');
		$this->views = ['bulk/'.$view];
		$this->loadView($view);
	}
	/**
	 * @info Método para calcular la orden de severvicio
	 * @author J. Enrique Peñaloza Piñero
	 * @date January 04th, 2019
	 */
	public function calculateServiceOrder()
	{
		log_message('INFO', 'NOVO Bulk: calculateServiceOrder Method Initialized');

		if(!$this->session->flashdata('serviceOrdersList')) {
			redirect(base_url('lotes-autorizacion'), 'location');
		}

		$view = 'calculateServiceOrder';
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
			"bulk/calculate_service_order"
		);
		$serviceOrdersList = $this->session->flashdata('serviceOrdersList');
		$bulkNotBillable = $this->session->flashdata('bulkNotBillable');
		$this->session->set_flashdata('serviceOrdersList', $serviceOrdersList);
		$this->session->set_flashdata('bulkNotBillable', $bulkNotBillable);
		$this->render->serviceOrdersList = $serviceOrdersList;
		$this->render->bulkNotBillable = $bulkNotBillable;
		$this->render->tempOrdersId = '';
		$this->render->bulknotBill = '';
		$this->render->titlePage = lang('GEN_CACULATE_ORDER_TITLE');
		$this->views = ['bulk/'.$view];
		$this->loadView($view);
	}
	/**
	 * @info Método la solicitud de innominadas
	 * @author J. Enrique Peñaloza Piñero
	 * @date April 29, 2020
	 */
	public function unnamedRequest()
	{
		log_message('INFO', 'NOVO Bulk: unnamedRequest Method Initialized');
		$view = 'unnamedRequest';
		array_push(
			$this->includeAssets->jsFiles,
			'bulk/unnamed_request'
		);

		$this->responseAttr();
		$this->render->titlePage = 'Solicitud de innominadas';
		$this->views = ['bulk/'.$view];
		$this->loadView($view);
	}
	/**
	 * @info Método la afiliación de innominadas
	 * @author J. Enrique Peñaloza Piñero
	 * @date April 29, 2020
	 */
	public function unnamedAffiliate()
	{
		log_message('INFO', 'NOVO Bulk: unnamedAffiliate Method Initialized');
		$view = 'unnamedAffiliate';
		array_push(
			$this->includeAssets->jsFiles,
			'bulk/unnamed_affiliate'
		);

		$this->responseAttr();
		$this->render->titlePage = 'Inventario de innominadas';
		$this->views = ['bulk/'.$view];
		$this->loadView($view);
	}

}
