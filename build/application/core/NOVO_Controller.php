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
	public $accessControl;

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO_Controller Class Initialized');

		$this->includeAssets = new stdClass();
		$this->countryUri = $this->uri->segment(1, 0);
		$this->render = new stdClass();
		$this->optionsCheck();
	}

	private function optionsCheck()
	{
		log_message('INFO', 'NOVO optionsCheck Method Initialized');
		countryCheck($this->countryUri);
		$arrayUri = explode('/', $this->uri->uri_string());
		$lang = end($arrayUri);
		$this->render->lang = $lang === 'en' ? 'en' : 'es';
		$faviconData = setFavicon();
		$this->render->favicon = $faviconData->favicon;
		$this->render->ext = $faviconData->ext;
		$this->render->countryUri = $this->countryUri;
		switch($this->countryUri) {
			case 'bp':
				$this->skin = 'pichincha';
				break;
			default:
				$this->skin = 'novo';
		}
		$this->includeAssets->cssFiles = [
			"general-structure",
			"$this->skin-appearance",
			"general"
		];
		$this->includeAssets->jsFiles = [
			"third_party/html5",
			"third_party/jquery-3.4.0",
			"third_party/jquery-ui-1.12.1"
		];
		$this->lang->load('erroreseol');
		$this->lang->load('dashboard');
	}

	protected function loadView($module)
	{
		log_message('INFO', 'NOVO loadView Method Initialized Module loaded: '.$module);
		$this->render->logged = $this->session->userdata('logged');
		$this->render->module = $module;
		$this->render->viewPage = $this->views;
		$this->asset->initialize($this->includeAssets);
		$this->load->view('master_content', $this->render);
	}
}

