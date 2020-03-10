<?php
defined('BASEPATH') OR  exit('No direct script access allowed');
/**
 * @info Controlador para manejar las peticiones referentes a consultas de ordenes de servicio
 * @author J. Enrique Peñaloza Piñero
 * @date January 09th, 2019
*/
class DownloadFiles extends NOVO_Controller {

	public function __construct()
	{
		parent :: __construct();
		log_message('INFO', 'NOVO DownloadFiles Controller Class Initialized');
	}

	/**
	 * @info Método para exportar la orden de servicio a pdf
	 ** @author Luis Molina
	 * @date marzo 02Mon, 2020
	 */
	public function exportFiles()
	{
		log_message('INFO', 'NOVO Inquiries: exportFiles Method Initialized');

		$view = $this->request->views;

		$renderOrderList = TRUE;

		$this->model = 'Novo_'.ucfirst($this->request->who).'_Model';
		$this->method = 'callWs_'.ucfirst($this->request->where).'_'.$this->request->who;

		$response=$this->loadModel($this->request);


		/*if($this->session->flashdata('serviceOrdersList')) {
			//$this->session->set_flashdata('serviceOrdersList',$this->session->flashdata('serviceOrdersList'));
			$orderList = $this->session->flashdata('serviceOrdersList');
			$renderOrderList = TRUE;
		}*/

		$this->responseAttr($response);

		$this->render->renderOrderList = $renderOrderList;
		$this->render->titlePage = 'Ordenes de servicios';
		$this->views = [$this->request->who.'/'.$view];
		$this->loadView($view);
	}
}
