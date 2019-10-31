<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Clase contralador de Conexión Empresas Online (CEO)
 *
 * Esta clase es la súper clase de la que heredarán todos los controladores
 * de la aplicación.
 *
 * @package controllers
 * @author J. Enrique Peñaloza P
 */
class NOVO_Controller extends CI_Controller {
	protected 	$skin;
	protected $includeAssets;
	protected $countryUri;
	protected $views;
	protected $render;
	protected $dataRequest;
	protected $model;
	protected $method;
	protected $request;
	protected $dataResponse;

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO_Controller Class Initialized');

		$this->includeAssets = new stdClass();
		$this->request = new stdClass();
		$this->dataResponse = new stdClass();
		$this->render = new stdClass();
		$this->model = 'Novo_'.$this->router->fetch_class().'_Model';
		$this->method = 'callWs_'.$this->router->fetch_method().'_'.$this->router->fetch_class();
		$this->countryUri = $this->uri->segment(1, 0) ? $this->uri->segment(1, 0) : 'pe';
		$this->render->logged = $this->session->userdata('logged');
		$this->render->userId = $this->session->userdata('idUsuario');
		$this->render->fullName = $this->session->userdata('fullName');
		$this->render->activeRecaptcha = $this->config->item('active_recaptcha');
		$this->optionsCheck();
	}

	private function optionsCheck()
	{
		log_message('INFO', 'NOVO optionsCheck Method Initialized');
		languageLoad();
		countryCheck($this->countryUri);
		languageLoad($this->countryUri);
		$this->skin = $this->config->item('client');
		$this->render->newViews = $this->config->item('new-views');
		$this->form_validation->set_error_delimiters('', '---');
		$this->config->set_item('language', 'spanish-base');
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
			$faviconLoader = getFaviconLoader($this->countryUri);
			$this->render->favicon = $faviconLoader->favicon;
			$this->render->ext = $faviconLoader->ext;
			$this->render->loader = $faviconLoader->loader;
			$this->render->countryConf = $this->config->item('country');
			$this->render->settingContents = $this->config->item('settingContents');
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
				"$this->skin-validate",
				"third_party/jquery-ui",
				//"$structure-structure",
				//"$this->skin-appearance",
				"$this->skin-base",
			];
			if($this->render->newViews === '-core') {
				array_unshift(
					$this->includeAssets->cssFiles,
					"format/root",
					"format/reboot"
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
		}
	}

	protected function loadView($module)
	{
		log_message('INFO', 'NOVO loadView Method Initialized Module loaded: '.$module);
		$auth = FALSE;
		switch($module) {
			case 'login':
			case 'benefits':
			case 'terms':
			case 'pass-recovery':
			case 'rates':
				$auth = TRUE;
				break;
			case 'change-password':
				$auth = ($this->session->flashdata('changePassword'));
				break;
			case 'products':
			case 'enterprise':
				$auth = ($this->render->logged);
				break;
			case 'rates':
				$auth = ($this->render->logged && $this->countryUri === 've');
				break;
			default:

		}
		if($auth) {
			$userAccess = $this->session->userdata('user_access');
			$menu = createMenu($userAccess);
			$userMenu = new stdClass();
			$userMenu->menu = $menu;
			$userMenu->pais = '';
			$userMenu->enterpriseList = lang('GEN_ENTERPRISE_LIST');
			$this->render->settingsMenu = $userMenu;
			$this->render->goOut = ($this->render->logged || $this->session->flashdata('changePassword'))
			? 'cerrar-sesion' : 'inicio';
			$this->render->module = $module;
			$this->render->viewPage = $this->views;
			$this->asset->initialize($this->includeAssets);
			$this->load->view('master_content', $this->render);
		} else {
			redirect(base_url('inicio'), 'location');
		}
	}

	/**
	 * Llama un metodo especifico de un modelo
	 *
	 * @return void
	 * @author Pedro Torres
	 */
	protected function callMethodNotAsync($params = '')
	{
		$this->load->model($this->model,'modelLoaded');
		$method = $this->method;
		return $this->modelLoaded->$method($params);
	}
}
