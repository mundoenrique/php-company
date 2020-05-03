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
			"settings/ceo_load_lots",
			"third_party/jquery.validate",
			"validate".$this->render->newViews."-forms",
			"third_party/additional-methods",
			"settings/settings",
			"user/pass_validate"
		);

		//LLama datos del usuario
		$this->load->model('Novo_User_Model', 'getUser');
		$user = $this->getUser->CallWs_GetUser_User();
		$this->render->name = $user->data->primerNombre;
		$this->render->firstName = $user->data->primerApellido;
		$this->render->position = $user->data->cargo;
		$this->render->area = $user->data->area;
		$this->render->email = $user->data->email;

		//Cambio de contraseña
		$this->load->model('Novo_User_Model', 'ChangeEmail');

		//LLama lista de empresas
		$this->load->model('Novo_Business_Model', 'Business');
		$enterpriseList = $this->Business->callWs_getEnterprises_Business(TRUE);
		$this->render->enterpriseList1 = $enterpriseList->data->list;
		if($enterpriseSelection = $this->session->userdata('enterpriseInf') != NULL){
			$enterpriseSelection = $this->session->userdata('enterpriseInf');
			$name = $enterpriseSelection->enterpriseName;
		}

		$complementIdEnterprise = 1;


		$this->render->nameSelection = $enterpriseList->data->listaa->list[$complementIdEnterprise]->accodcia;
		$this->render->idSelection = $enterpriseList->data->listaa->list[$complementIdEnterprise]->acnomcia;
		$this->render->rfcSelection = $enterpriseList->data->listaa->list[$complementIdEnterprise]->acrif;
		$this->render->contactSelection = $enterpriseList->data->listaa->list[$complementIdEnterprise]->acpercontac;
		$this->render->directionSelection = $enterpriseList->data->listaa->list[$complementIdEnterprise]->acdirenvio;
		$this->render->factDirectionSelection = $enterpriseList->data->listaa->list[$complementIdEnterprise]->acdirenvio;

		//LLama lista de empresas
		$selection = $this->Business->callWS_SelectionEnterprise_Business();

	// $this->load->model('Novo_Business_Model', 'getEnterprise');
	// $enterprise = $this->Business->callWS_ListaEmpresas_Business();

		$this->render->titlePage =lang('GEN_SETTINGS_TITLE');
		$this->views = ['settings/'.$view];
		$this->loadView($view);
	}

}
