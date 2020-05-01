<?php
defined('BASEPATH') OR  exit('No direct script access allowed');
/**
 * @info Controlador para manejar las peticiones referentes a configuraciones
 * @author Luis Molina
 * @date Marz 20Fri, 2020
*/
class Novo_Settings extends NOVO_Controller {

	public function __construct()
	{
		parent :: __construct();
		log_message('INFO', 'NOVO Settings Controller Class Initialized');
	}

	/**
	 * @info Método para renderizar el modulo de configuración
	 * @author Luis Molina
	 * @modified Diego Acosta García
	 * @date Mar 30/04/2020
	 */
	public function options()
	{
		log_message('INFO', 'NOVO Settings: options Method Initialized');

		$view = 'settings';

		array_push(
			$this->includeAssets->cssFiles,
			"third_party/dataTables-1.10.20"
		);
		array_push(
			$this->includeAssets->jsFiles,
			"third_party/dataTables-1.10.20",
			"settings/ceo_load_lots",
			"third_party/jquery.validate",
			"validate".$this->render->newViews."-forms",
			"third_party/additional-methods",
			"settings/settings",
			"user/pass_validate"
		);

		$this->load->model('Novo_User_Model', 'User');
		$this->render->fullName = $this->session->fullName;
		$this->render->name = $this->session->name;
		$this->render->firstName = $this->session->firstName;
		$this->render->job = $this->session->job;
		$this->render->area = $this->session->area;
		$CI = &get_instance();
		$this->render->email = $CI->session->userdata('email') ;

		$this->load->model('Novo_Business_Model', 'Business');
		$enterpriseList = $this->Business->callWs_getEnterprises_Business(TRUE);
		$this->render->enterpriseList1 = $enterpriseList->data->list;

		$this->render->titlePage =lang('GEN_SETTINGS_TITLE');
		$this->views = ['settings/'.$view];
		$this->loadView($view);
	}

}
