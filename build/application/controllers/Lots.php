<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Controlador acciones relacionadas con los lotes
 * @author J. Enrique Peñaloza Piñero
 * @date December 7th, 2019
*/
class Lots extends NOVO_Controller {

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
	public function getPendingLots()
	{
		log_message('INFO', 'NOVO Lots: getPendingLots Method Initialized');

		$view = lang('GEN_GET_PEN_LOTS');
		array_push(
			$this->includeAssets->cssFiles,
			"third_party/datatables"
		);
		array_push(
			$this->includeAssets->jsFiles,
			"third_party/datatables",
			"lots/load_lots"
		);
		$responseList = $this->loadModel();
		$this->responseAttr($responseList);
		$this->render->titlePage = lang('GEN_MENU_LOT_LOAD');
		$this->load->model('Novo_Lots_Model', 'Lots');
		$typesLot = $this->Lots->callWs_getTypeLots_Lots(TRUE);
		$this->render->typesLot = $typesLot->data->typesLot;
		$this->render->pendinglots = $responseList->data->pendinglots;
		$this->render->productName = $this->session->productInf->productName.' / '.$this->session->productInf->brand;
		$this->views = ['lots/'.$view];
		$this->loadView($view);
	}
}
