<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Controlador para manejar las peticiones referentes a empresas
 * @author J. Enrique Peñaloza Piñero
 * @date October 30th, 2019
*/
class Business extends NOVO_Controller {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO Business Controller class Initialized');
	}
	/**
	 * @info Método para renderizar las empresas asociadas al usuario
	 * @author Pedro Torres
	 * @date 24/08/2019
	 */
	public function getEnterprises()
	{
		log_message('INFO', 'NOVO Business: getCompanies Method Initialized');
		$view = 'enterprise';

		array_push(
			$this->includeAssets->jsFiles,
			"third_party/isotope.pkgd-3.0.6",
			"third_party/pagination-2.1.4",
			"business/enterprise"
		);

		$this->views = ['business/'.$view];
		$responseList = $this->loadModel();
		$this->render->titlePage = "Empresas";
		$this->render->category = "";
		$this->render->lastSession = $this->session->userdata('lastSession');
		$this->render->enterprisesTotal = $responseList->data->enterprisesTotal;
		$this->render->enterpriseList = $responseList->data->list;
		$this->render->filters = $responseList->data->filters;
		$this->render->recordsPage = ceil($responseList->data->enterprisesTotal/$responseList->data->recordsPage);
		$this->loadView($view);
	}

	public function getProducts()
	{
		log_message('INFO', 'NOVO Business: getProducts Method Initialized');
		$view = 'products';

		array_push(
			$this->includeAssets->jsFiles,
			'select-tools'
		);

		$this->views = ['business/'.$view];
		$this->render->titlePage = "Productos";
		$this->loadView($view);
	}

	public function showDetailProduct()
	{

	}

}
