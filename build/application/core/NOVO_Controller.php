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
	protected $folder;
	private $ValidateBrowser;
	public $singleSession;

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO Controller Class Initialized');

		$this->includeAssets = new stdClass();
		$this->request = new stdClass();
		$this->dataResponse = new stdClass();
		$this->render = new stdClass();
		$this->rule = lcfirst(str_replace('Novo_', '', $this->router->fetch_method()));
		$this->model = ucfirst($this->router->fetch_class()).'_Model';
		$this->method = 'callWs_'.ucfirst($this->router->fetch_method()).'_'.str_replace('Novo_', '', $this->router->fetch_class());
		$this->countryUri = $this->uri->segment(1, 0) ?? 'null';
		$this->render->widget =  FALSE;
		$this->render->prefix = '';
		$this->render->sessionTime = $this->config->item('session_time');
		$this->render->callModal = $this->render->sessionTime < 180000 ? ceil($this->render->sessionTime * 50 / 100) : 15000;
		$this->render->callServer = $this->render->callModal;
		$this->ValidateBrowser = FALSE;
		$this->singleSession = base64_decode($this->input->cookie($this->config->item('cookie_prefix').'singleSession'));
		$this->optionsCheck();
	}
	/**
	 * Método para varificar datos génericos de la solcitud
	 * @author J. Enrique Peñaloza Piñero
	 * @date April 20th, 2019
	 */
	private function optionsCheck()
	{
		log_message('INFO', 'NOVO Controller: optionsCheck Method Initialized');

		languageLoad('generic', $this->router->fetch_class());
		clientUrlValidate($this->countryUri);
		languageLoad('specific', $this->router->fetch_class());
		if($this->session->has_userdata('userId')) {
			if($this->session->countrySess !== $this->config->item('country')) {
				clientUrlValidate($this->session->countryUri);
				$urlRedirect = str_replace($this->countryUri.'/', $this->session->countryUri.'/', base_url('cerrar-sesion/inicio'));
				redirect($urlRedirect, 'location', 301);
				exit();
			}
		}
		$this->skin = $this->config->item('client');
		$this->form_validation->set_error_delimiters('', '---');
		$this->config->set_item('language', 'spanish-base');

		if ($this->rule !== 'suggestion') {
			$this->ValidateBrowser = $this->checkBrowser();
		}

		if ($this->session->has_userdata('time')) {
			$customerTime = $this->session->time->customerTime;
			$serverTime = $this->session->time->serverTime;
			$currentTime = (int) date("H");
			$currentTime2 = date("Y-d-m H:i:s");
			$serverelapsed = $currentTime - $serverTime;
			$serverelapsed = $serverelapsed >= 0 ? $serverelapsed : $serverelapsed + 24;
			$elapsed = $customerTime + $serverelapsed;
			$this->greeting = $elapsed < 24 ? $elapsed : $elapsed - 24;
		}

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

		if ($this->input->is_ajax_request()) {
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

			if ($_POST && $access) {
				$valid = $this->verify_access->validateForm($this->rule, $this->countryUri, $this->appUserName);

				if ($valid) {
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
		log_message('INFO', 'NOVO Controller: preloadView Method Initialized');

		if ($auth) {

			$this->render->favicon = lang('GEN_FAVICON');
			$this->render->ext = lang('GEN_FAVICON_EXT');
			$this->render->loader = lang('GEN_LOADER');
			$this->render->countryConf = $this->config->item('country');
			$this->render->countryUri = $this->countryUri;
			$this->render->novoName = $this->security->get_csrf_token_name();
			$this->render->novoCook = $this->security->get_csrf_hash();
			$this->folder = lang('CONF_VIEW_SUFFIX') === '-core' ? $this->countryUri.'/' : '';
			$this->includeAssets->cssFiles = [
				"$this->folder"."$this->skin-base"
			];

			if (gettype($this->ValidateBrowser) !== 'boolean') {
				array_push(
					$this->includeAssets->cssFiles,
					"$this->countryUri/$this->skin-$this->ValidateBrowser-base"
				);
			}

			if (lang('CONF_VIEW_SUFFIX') === '-core') {
				array_unshift(
					$this->includeAssets->cssFiles,
					"$this->countryUri/root-$this->skin",
					"reboot"
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

			if ($this->session->has_userdata('logged')) {
				array_push(
					$this->includeAssets->jsFiles,
					"third_party/jquery.balloon",
					"sessionControl"
				);

				if (lang('CONF_REMOTE_AUTH') == 'ON') {
					array_push(
						$this->includeAssets->jsFiles,
						"remote_connect/$this->countryUri-remoteConnect",
					);
				}
			}

		} else {
			$linkredirect = uriRedirect(AUTO_LOGIN);
			redirect(base_url($linkredirect), 'location');
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
		log_message('INFO', 'NOVO Controller: loadModel Method Initialized. Model loaded: '.$this->model);

		$this->load->model($this->model,'modelLoaded');
		$method = $this->method;

		return $this->modelLoaded->$method($request);
	}
	/**
	 * Método para extraer mensaje al usuario
	 * @author J. Enrique Peñaloza Piñero
	 * @date Auguts 22nd, 2019
	 */
	protected function responseAttr($responseView = 0, $active = TRUE)
	{
		log_message('INFO', 'NOVO Controller: responseAttr Method Initialized');

		$this->render->code = $responseView;
		$download = FALSE;
		$this->render->enterpriseList = $this->session->enterpriseSelect->list;
		$this->render->enterpriseData =  $this->session->enterpriseInf;

		if (is_object($responseView)) {
			$this->render->code = $responseView->code;
			$download = !isset($responseView->download) ? $download : $responseView->download;
		}

		if ($this->session->has_userdata('productInf')) {
			$this->render->prefix = $this->session->productInf->productPrefix;
		}

		if (($this->render->code == 0  && $active) || $download) {

			if (count($this->render->enterpriseList) > 1 || $this->session->has_userdata('products')) {
				array_push(
					$this->includeAssets->jsFiles,
					"business/widget-enterprise"
				);

				$this->render->widget =  new stdClass();
				$this->render->widget->widgetBtnTitle = lang('GEN_MUST_SELECT_ENTERPRISE');
				$this->render->widget->countProducts = $this->session->has_userdata('products');
				$this->render->widget->actionForm = 'detalle-producto';
			}
		}

		log_message('info', '*********************'.json_encode($responseView));

		//if ($this->render->code > 2) {
			$this->render->title = $responseView->title;
			$this->render->msg = $responseView->msg;
			$this->render->icon = $responseView->icon;
			$this->render->data = json_encode($responseView->data->resp);
			$this->render->data = json_encode($responseView->modalBtn);
		//} elseif(isset($responseView->data->params))  {
			//$this->render->params = json_encode($responseView->data->params);
		//}
	}
	/**
	 * Método para validar la versión de browser
	 * @author J. Enrique Peñaloza Piñero
	 * @date January 23nd, 2019
	 */
	protected function checkBrowser()
	{
		log_message('INFO', 'NOVO Controller: checkBrowser Method Initialized');
		$this->load->library('Tool_Browser');

		$valid = $this->tool_browser->validBrowser($this->skin);

		if (!$valid) {
			redirect(base_url('sugerencia'),'location', 301);
			exit();
		}

		return $valid;
	}
	/**
	 * Método para renderizar una vista
	 * @author J. Enrique Peñaloza Piñero
	 * @date May 14th, 2019
	 */
	protected function loadView($module)
	{
		log_message('INFO', 'NOVO Controller: loadView Method Initialized. Module loaded: '.$module);

		$userMenu = new stdClass();
		$userMenu->userAccess = $this->session->user_access;
		$userMenu->enterpriseUrl = lang('GEN_ENTERPRISE_LIST');
		$userMenu->currentClass = $this->router->fetch_class();
		$this->render->logged = $this->session->has_userdata('logged');
		$this->appUserName = $this->session->userName;
		$this->render->fullName = $this->session->fullName;
		$this->render->productName = !$this->session->has_userdata('productInf') ?:
			$this->session->productInf->productName.' / '.$this->session->productInf->brand;
		$this->render->settingsMenu = $userMenu;
		$this->render->goOut = ($this->session->has_userdata('logged') || $this->session->flashdata('changePassword'))
			? 'cerrar-sesion/inicio' : 'inicio';
		$this->render->module = $module;
		$this->render->viewPage = $this->views;
		$this->asset->initialize($this->includeAssets);
		$this->load->view('master_content'.lang('CONF_VIEW_SUFFIX'), $this->render);
	}
}
