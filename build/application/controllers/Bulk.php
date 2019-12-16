<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Controlador acciones relacionadas con los lotes
 * @author J. Enrique Peñaloza Piñero
 * @date December 7th, 2019
*/
class Bulk extends NOVO_Controller {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO Lots Controller class Initialized');
	}
	/**
	 * @info Método para renderizar los lotes pendientes y cargar nuevos lotes
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 7th, 2019
	 */
	public function getPendingBulk()
	{
		log_message('INFO', 'NOVO Bulk: getPendingBulk Method Initialized');

		$view = lang('GEN_GET_PEN_BULK');
		array_push(
			$this->includeAssets->cssFiles,
			"third_party/datatables"
		);
		array_push(
			$this->includeAssets->jsFiles,
			"third_party/datatables",
			"third_party/fileupload-10.4.0",
			"third_party/jquery.validate",
			"validate".$this->render->newViews."-forms",
			"third_party/additional-methods",
			"bulk/load_bulk"
		);
		$responseList = $this->loadModel();
		$this->responseAttr($responseList);
		$this->render->titlePage = lang('GEN_MENU_BULK_LOAD');
		$this->load->model('Novo_Bulk_Model', 'Bulk');
		$typesLot = $this->Bulk->callWs_getTypeLots_Bulk(TRUE);
		$this->render->typesLot = $typesLot->data->typesLot;
		$this->render->pendingBulk = $responseList->data->pendingBulk;
		$this->render->productName = $this->session->productInf->productName.' / '.$this->session->productInf->brand;
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

		$view = lang('GEN_DETAIL_BULK');
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

		$view = lang('GEN_CONFIRM_BULK');
		$this->views = ['bulk/'.$view];
		$this->loadView($view);
	}
}
