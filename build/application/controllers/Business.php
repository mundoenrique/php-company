<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Controlador para
 * @author
*/
class Business extends NOVO_Controller {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO Business Controller class Initialized');
		//$this->lang->load('XXX');
	}
	/**
	 * @info Método para obtener las renderizar las empresas asociadas a un usuarios
	 * @author J. Enrique Peñaloza
	 */
	public function getEnterprises()
	{
		log_message('INFO', 'NOVO Business: getCompanies Method Initialized');
		/* array_push(
			$this->includeAssets->cssFiles,
			"xxx/xxx",
			"third_party/xxx"
		);
		array_push(
			$this->includeAssets->jsFiles,
			"header"
		);
		*/
		$this->views = ['business/enterprise'];
		$this->render->titlePage = "Empresas";
		$this->render->lastSession = $this->session->userdata('lastSession');
		$this->loadView('companies');
	}
}
