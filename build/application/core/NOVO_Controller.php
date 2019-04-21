<?php defined('BASEPATH') or exit('No direct script access allowed');
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
		$this->countryUri = $this->uri->segment(1, 0);
		$this->render = new stdClass();
		$this->render->logged = $this->session->userdata('logged');
		$this->idProductos = $this->session->userdata('idProductos');

		$this->optionsCheck();

		$this->lang->load('erroreseol');
		$this->lang->load('dashboard');
	}

	private function optionsCheck()
	{
		log_message('INFO', 'NOVO optionsCheck Method Initialized');
		countryCheck($this->countryUri);
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
			$this->render->lang = $this->config->item('app_lang');
			$this->render->countryConf = $this->config->item('country');
			$this->render->countryUri = $this->countryUri;
			switch($this->countryUri) {
				case 'bp':
					$this->skin = 'pichincha';
					break;
				default:
					$this->skin = 'novo';
			}
			$this->includeAssets->cssFiles = [
				"validate",
				"$this->skin-structure",
				"$this->skin-appearance"
			];
			$this->includeAssets->jsFiles = [
				"third_party/html5",
				"third_party/jquery-3.4.0",
				"third_party/jquery-ui-1.12.1",
				"helper"
			];
		}
	}

	protected function loadView($module)
	{
		log_message('INFO', 'NOVO loadView Method Initialized Module loaded: '.$module);
		$auth = FALSE;
		switch($module) {
			case 'login':
				$auth = TRUE;
				break;
		}

		$this->render->module = $module;
		$this->render->viewPage = $this->views;
		$this->asset->initialize($this->includeAssets);
		$this->load->view('master_content', $this->render);
	}
}

