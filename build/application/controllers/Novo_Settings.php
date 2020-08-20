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
			"validate-core-forms",
			"third_party/additional-methods",
			"settings/settings",
			"user/changePassword-core"
		);
		$userType = '';
		$idFiscal = '';
		$name = '';
		$businessName = '';
		$contact = '';
		$address = '';
		$billingAddress = '';
		$phone1 = '';
		$phone2 = '';
		$phone3 = '';
		$title = '';
		$disabled = 'big-modal';

		if (lang('CONF_SETTINGS_USER') == 'ON') {
			$this->load->model('Novo_Settings_Model', 'getUser');
			$user = $this->getUser->CallWs_GetUser_Settings();
			$userType = $this->session->userType;

			foreach ($user->data->dataUser AS $index => $render) {
				$this->render->$index = $render;
			}


		}

		if (lang('CONF_SETTINGS_ENTERPRISE') == 'ON') {
			$this->load->model('Novo_Business_Model', 'getEnterprises');
			$enterpriseList = $this->getEnterprises->callWs_getEnterprises_Business(TRUE);
			$this->render->enterpriseList1 = $enterpriseList->data->list;
			$this->render->enterpriseSettList = $enterpriseList->data->list;
			$this->render->countEnterpriseList = count($enterpriseList->data->list);

			if ($this->render->countEnterpriseList == 1) {
				$idFiscal = $this->render->enterpriseSettList[0]->acrif;
				$name = $this->render->enterpriseSettList[0]->acnomcia;
				$businessName = $this->render->enterpriseSettList[0]->acrazonsocial;
				$contact = $this->render->enterpriseSettList[0]->acpercontac;
				$address = $this->render->enterpriseSettList[0]->acdirubica;
				$billingAddress = $this->render->enterpriseSettList[0]->acdirenvio;
				$phone1 = $this->render->enterpriseSettList[0]->actel;
				$phone2 = $this->render->enterpriseSettList[0]->actel2;
				$phone3 = $this->render->enterpriseSettList[0]->actel3;
			}
		}

		if (!$this->session->has_userdata('enterpriseInf') && count($this->session->enterpriseSelect->list) > 1) {
			$title = lang('GEN_BTN_INI');
			$disabled = '';
		}

		$this->render->titlePage = lang('GEN_SETTINGS_TITLE');
		$this->render->userType = $userType;
		$this->render->idFiscal = $idFiscal;
		$this->render->name = $name;
		$this->render->businessName = $businessName;
		$this->render->contact = $contact;
		$this->render->address = $address;
		$this->render->billingAddress = $billingAddress;
		$this->render->phone1 = $phone1;
		$this->render->phone2 = $phone2;
		$this->render->phone3 = $phone3;
		$this->render->titleIniFile = $title;
		$this->render->disabled = $disabled;
		$this->views = ['settings/'.$view];
		$this->loadView($view);
	}
}
