<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Clase contralador de Conexión Empresas Online (CEO)
 *
 * Esta clase es la súper clase de la que heredarán todos los controladores
 * de la aplicación.
 *
 * @package controllers
 * @author J. Enrique Peñaloza Piñero
 */
class NOVO_Controller extends CI_Controller {
	protected $skin;
	protected $rule;
	protected $includeAssets;
	protected $countryUri;
	protected $views;
	protected $render;
	protected $dataRequest;
	protected $model;
	protected $method;
	protected $request;
	protected $dataResponse;
	protected $appUserName;
	protected $greeting;
	protected $products;

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO_Controller Class Initialized');

		$this->includeAssets = new stdClass();
		$this->request = new stdClass();
		$this->dataResponse = new stdClass();
		$this->render = new stdClass();
		$this->rule = $this->router->fetch_method();
		$this->model = 'Novo_'.ucfirst($this->router->fetch_class()).'_Model';
		$this->method = 'callWs_'.ucfirst($this->router->fetch_method()).'_'.$this->router->fetch_class();
		$this->countryUri = $this->uri->segment(1, 0) ? $this->uri->segment(1, 0) : 'pe';
		$this->render->logged = $this->session->logged;
		$this->appUserName = $this->session->userName;
		$this->products = $this->session->has_userdata('products');
		$this->render->userId = $this->session->userId;
		$this->render->fullName = $this->session->fullName;
		$this->render->activeRecaptcha = $this->config->item('active_recaptcha');
		$this->render->widget =  FALSE;
		$this->greeting = (int) $this->session->greeting;
		$this->optionsCheck();
	}
	/**
	 * Método para varificar datos génericos de la solcitud
	 * @author J. Enrique Peñaloza Piñero
	 * @date April 20th, 2019
	 */
	private function optionsCheck()
	{
		log_message('INFO', 'NOVO_Controller: optionsCheck Method Initialized');

		languageLoad('generic');
		countryCheck($this->countryUri);
		languageLoad('specific', $this->countryUri);
		$this->skin = $this->config->item('client');
		$this->render->newViews = $this->config->item('new-views');
		$this->form_validation->set_error_delimiters('', '---');
		$this->config->set_item('language', 'spanish-base');

		switch ($this->greeting) {
			case $this->greeting >= 19 && $this->greeting <= 23:
				$this->render->greeting = lang('GEN_EVENING');
				break;
			case $this->greeting >= 12 && $this->greeting < 19:
				$this->render->greeting = lang('GEN_AFTERNOON');
				break;
			case $this->greeting >= 0 && $this->greeting < 12:
				$this->render->greeting = lang('GEN_MORNING');
				break;
		}

		if($this->input->is_ajax_request()) {
			$this->dataRequest = json_decode(
				$this->security->xss_clean(
					strip_tags(
						$this->cryptography->decrypt(
							base64_decode($this->input->get_post('plot')),
							utf8_encode($this->input->get_post('request'))
						)
					)
				)
			);
		} else {
			$access = $this->verify_access->accessAuthorization($this->router->fetch_method(), $this->countryUri, $this->appUserName);
			$valid = TRUE;

			if($_POST && $access) {
				$valid = $this->verify_access->validateForm($this->rule, $this->countryUri, $this->appUserName);

				if($valid) {
					$this->request = $this->verify_access->createRequest($this->rule, $this->appUserName);
				}

			}

			$this->preloadView($access && $valid);
		}

	}
	/**
	 * Método para realizar la precarga de las vistas
	 * @author J. Enrique Peñaloza Piñero
	 * @date October 31th, 2019
	 */
	protected function preloadView($auth)
	{
		log_message('INFO', 'NOVO_Controller: preloadView method initialized');

		if($auth) {
			$faviconLoader = getFaviconLoader($this->countryUri);
			$this->render->favicon = $faviconLoader->favicon;
			$this->render->ext = $faviconLoader->ext;
			$this->render->loader = $faviconLoader->loader;
			$this->render->countryConf = $this->config->item('country');
			$this->render->countryUri = $this->countryUri;
			$this->render->novoName = $this->security->get_csrf_token_name();
			$this->render->novoCook = $this->security->get_csrf_hash();
			$this->session->set_userdata('countryUri', $this->countryUri);

			switch($this->countryUri) {
				case 'bp':
					$structure = 'pichincha';
					break;
				default:
					$structure = 'novo';
			}

			if($this->skin !== 'pichincha') {
				$structure = 'novo';
			}

			$this->includeAssets->cssFiles = [
				//"$structure-structure",
				//"$this->skin-appearance",
				"$this->skin-base",
				"$this->skin-validate",
			];

			if($this->render->newViews === '-core') {
				array_unshift(
					$this->includeAssets->cssFiles,
					"format/root-$this->skin",
					"format/reboot-$this->skin"
				);
			} else {
				array_unshift(
					$this->includeAssets->cssFiles,
					"$this->skin-validate",
					"third_party/jquery-ui"
				);
			}

			$this->includeAssets->jsFiles = [
				"third_party/html5",
				"third_party/jquery-3.4.0",
				"third_party/jquery-ui-1.12.1",
				"third_party/aes",
				"aes-json-format",
				"helper"
			];

			if($this->render->logged) {
				array_push(
					$this->includeAssets->jsFiles,
					"third_party/jquery.balloon",
					"menu-datepicker"
				);
			}

		} else {
			redirect(base_url('inicio'), 'location');
		}

	}
	/**
	 * Método para cargar un modelo especifico
	 * @author Pedro Torres
	 * @date Auguts 23rd, 2019
	 * @modified J. Enrique Peñaloza Piñero
	 * @date October 31st, 2019
	 */
	protected function loadModel($request = FALSE)
	{
		log_message('INFO', 'NOVO_Controller: loadModel method initialized. Model loaded: '.$this->model);

		$this->load->model($this->model,'modelLoaded');
		$method = $this->method;
		return $this->modelLoaded->$method($request);
	}
	/**
	 * Método para extraer mensaje al usuario
	 * @author J. Enrique Peñaloza Piñero
	 * @date Auguts 22nd, 2019
	 */
	protected function responseAttr($responseView, $active = TRUE)
	{
		log_message('INFO', 'NOVO_Controller: responseAttr method initialized');

		$this->render->code = $responseView->code;

		if($responseView->code == 0  && $active) {
			$this->load->model('Novo_Business_Model', 'Business');
			$enterpriseList = $this->Business->callWs_getEnterprises_Business(TRUE);

			if(count($enterpriseList->data->list) > 1 || $this->products) {
				array_push(
					$this->includeAssets->jsFiles,
					"business/widget-enterprise"
				);
				$this->render->widget =  new stdClass();
				$this->render->widget->widgetBtnTitle = lang('GEN_MUST_SELECT_ENTERPRISE');
				$this->render->widget->enterpriseData =  $this->session->enterpriseInf;
				$this->render->widget->enterpriseList = $enterpriseList->data->list;
				$this->render->widget->countProducts = $this->products;
				$this->render->widget->actionForm = 'detalle-producto';
				$this->render->widget->widgetBtnTitle = lang('GEN_MUST_SELECT_ENTERPRISE');
			}

		}

		if($this->render->code > 2) {
			$this->render->title = $responseView->title;
			$this->render->msg = $responseView->msg;
			$this->render->icon = $responseView->icon;
			$this->render->data = json_encode($responseView->data->resp);
		}

	}
	/**
	 * Método para renderizar una vista
	 * @author J. Enrique Peñaloza Piñero
	 * @date May 14th, 2019
	 */
	protected function loadView($module)
	{
		log_message('INFO', 'NOVO_Controller: loadView method initialized. View loaded: '.$module);

		$userMenu = new stdClass();
		$userMenu->userAccess = $this->session->user_access;
		$userMenu->enterpriseUrl = lang('GEN_ENTERPRISE_LIST');
		$this->render->settingsMenu = $userMenu;
		$this->render->goOut = ($this->render->logged || $this->session->flashdata('changePassword'))
		? 'cerrar-sesion' : 'inicio';
		$this->render->module = $module;
		$this->render->viewPage = $this->views;
		$this->asset->initialize($this->includeAssets);
		$this->load->view('master_content', $this->render);
	}
}
