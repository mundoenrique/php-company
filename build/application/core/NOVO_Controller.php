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
	protected $includeAssets;
	protected $countryUri;
	protected $skin;
	protected $views;
	protected $render;
	protected $dataRequest;
	protected $idProductos;
	public $accessControl;

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO_Controller Class Initialized');

		$this->includeAssets = new stdClass();
		$this->countryUri = $this->uri->segment(1, 0) ? $this->uri->segment(1, 0) : 'pe';
		$this->render = new stdClass();
		$this->render->logged = $this->session->userdata('logged');
		$this->render->fullName = $this->session->userdata('fullName');
		$this->idProductos = $this->session->userdata('idProductos');
		$this->optionsCheck();
	}

	private function optionsCheck()
	{
		log_message('INFO', 'NOVO optionsCheck Method Initialized');
		countryCheck($this->countryUri);
		$this->lang->load('erroreseol');
		$this->lang->load('dashboard');
		$this->lang->load('users');
		if($this->input->is_ajax_request()) {
			$this->dataRequest = json_decode(
				$this->security->xss_clean(
					strip_tags(utf8_encode(base64_decode($this->input->get_post('request'))))
				)
			);
		} else {
			$faviconLoader = getFaviconLoader();
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
					$this->skin = 'pichincha';
					$structure = 'pichincha';
					break;
				default:
					$this->skin = 'novo';
					$structure = 'novo';
			}
			if($this->skin !== 'pichincha') {
				$structure = 'novo';
			}
			$this->includeAssets->cssFiles = [
				"$this->skin-validate",
				"third_party/jquery-ui",
				"$structure-structure",
				"$this->skin-appearance"
			];
			$this->includeAssets->jsFiles = [
				"third_party/html5",
				"third_party/jquery-3.4.0",
				"third_party/jquery-ui-1.12.1",
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
			case 'pass-recovery':
				$auth = TRUE;
				break;
			case 'terms':
			case 'companies':
				$auth = ($this->render->logged);
				break;
			default:

		}
		$this->render->goOut = ($this->render->logged) ? 'cerrar-sesion' : 'inicio';
		if($auth) {
			$this->render->module = $module;
			$this->render->viewPage = $this->views;
			$this->asset->initialize($this->includeAssets);
			$this->load->view('master_content', $this->render);
		} else {
			redirect(base_url('inicio'), 'location');
		}
	}
}

