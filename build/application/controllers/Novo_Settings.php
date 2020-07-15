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
	 * @date Mar 30/04/2020
	 * @modified Diego Acosta García
	 * @date  02/04/2020
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
			"third_party/jquery.validate",
			"validate".$this->render->newViews."-forms",
			"third_party/additional-methods",
			"settings/settings",
			"user/passValidate"
		);

		//LLama lista de empresas
		$this->load->model('Novo_Business_Model', 'getEnterprises');
		$enterpriseList = $this->getEnterprises->callWs_getEnterprises_Business(TRUE);
		if ( lang('CONF_COMPANIES_BOOL') != FALSE ){
		$this->render->enterpriseList1 = $enterpriseList->data->list;
		}

		//LLama datos del usuario
		$this->load->model('Novo_Settings_Model', 'getUser');
		$user = $this->getUser->CallWs_GetUser_Settings(TRUE);
		if ( lang('CONF_USER_BOOL') != FALSE ){
		$this->render->name = $user->data->primerNombre;
		$this->render->firstName = $user->data->primerApellido;
		$this->render->position = $user->data->cargo;
		$this->render->area = $user->data->area;
		$this->render->email = strtolower($user->data->email);
		}

		//Parámetros para validar descarga de archivo.ini
		$countEnterprise = count($enterpriseList->data->list);
		$enterpriseInf = $this->session->has_userdata('enterpriseInf') ? 1 : 0;

		$this->render->countEnterprise = $countEnterprise;
		$this->render->enterpriseInf = $enterpriseInf;
		$this->render->titlePage =lang('GEN_SETTINGS_TITLE');
		$this->views = ['settings/'.$view];
		$this->loadView($view);
	}


}
