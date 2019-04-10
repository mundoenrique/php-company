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

		$this->includeAssets->cssFiles = [
			"general"
		];
		$this->includeAssets->jsFiles = [
			"third_party/jquery-3.3.1",
			"third_party/jquery-ui-1.12.1",
			"third_party/html5"
		];
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

