<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Controlador para manejar las peticiones referentes a empresas
 * @author Pedro Torres
 * @date 23/08/2019
 *
*/
class Business extends NOVO_Controller {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO Business Controller class Initialized');
	}
	/**
	 * @info Método para obtener las renderizar las empresas asociadas a un usuarios
	 * @author Pedro Torres
	 * @date 24/08/2019
	 */
	public function getEnterprises()
	{
		log_message('INFO', 'NOVO Business: getCompanies Method Initialized');

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
			"business/enterprise",
			"header",
			"routes"
		);

		$this->views = ['business/enterprise'];
		$this->render->titlePage = "Empresas";
		$this->render->listaEmpresas = $this->callMethodNotAsync();
		$this->render->pais = $this->session->userdata('countrySess');
		$this->render->uniqueMenuUser = $this->config->item('uniqueMenuUser');
		$this->render->lastSession = $this->session->userdata('lastSession');
		$this->loadView('enterprise');
	}
}
