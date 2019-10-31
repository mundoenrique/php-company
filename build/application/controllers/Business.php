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
			"third_party/jquery.paginate",
			"third_party/jquery.isotope",
			"business/enterprise"
		);

		$this->views = ['business/'.$view];
		$this->render->titlePage = "Empresas";
		$this->render->listaEmpresas = $this->loadModel();
		$this->render->pais = $this->session->userdata('countrySess');
		$this->render->uniqueMenuUser = $this->config->item('uniqueMenuUser');
		$this->render->lastSession = $this->session->userdata('lastSession');
		$this->loadView($view);
	}

	public function getProducts($urlCountry)
	{
		log_message('INFO', 'NOVO Business: getProducts Method Initialized');
		$view = 'enterprise';
		$responseService = new stdClass();

		$result = $this->form_validation->run('dash-products');
		log_message('DEBUG', 'NOVO VALIDATION FORM dash-products: '.json_encode($result));

		if(!$result) {
			log_message('DEBUG', 'NOVO VALIDATION ERRORS: '.json_encode(validation_errors()));
			redirect(base_url($urlCountry.'/empresas'), 'location');
			exit();
		}

		$menu = ['menuArrayPorProducto'];
		$this->session->unset_userdata($menu);

		if($this->input->post()){

			$newdata = array(
				'acrifS'=> $this->input->post('data-acrif'),
				'acnomciaS'=> $this->input->post('data-acnomcia'),
				'acrazonsocialS'=> $this->input->post('data-acrazonsocial'),
				'acdescS'=> $this->input->post('data-acdesc'),
				'accodciaS'=> $this->input->post('data-accodcia'),
				'accodgrupoeS'=> $this->input->post('data-accodgrupoe')
			);
			$this->session->set_userdata($newdata);
		}

		if($newdata['acrifS']){

			$this->load->helper('form');
			$this->model = 'Novo_'.$this->router->fetch_class().'_Model';
			$this->method = 'callWs_'.$this->router->fetch_method().'_'.$this->router->fetch_class();

			array_push(
				$this->includeAssets->cssFiles,
				"$this->countryUri/default"
			);
			array_push(
				$this->includeAssets->jsFiles,
				"third_party/jquery.paginate",
				"third_party/jquery.isotope",
				"business/products"
			);

			$this->views = ['business/products'];
			$this->render->titlePage = "Productos";

			$responseService = $this->loadModel($newdata);

			$this->render->productos = $responseService->productos ?: [];
			$this->render->listaCategorias = $responseService->listaCategorias ?: [];
			$this->render->listaMarcas = $responseService->listaMarcas ?: [];

			$this->render->pais = $this->session->userdata('countrySess');
			$this->render->uniqueMenuUser = $this->config->item('uniqueMenuUser');
			$this->render->lastSession = $this->session->userdata('lastSession');
			$this->loadView('products');

		}else{
			redirect($urlCountry.'/empresas');
		}
	}

	public function showDetailProduct()
	{

	}

}
