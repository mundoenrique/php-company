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
	protected $rule;
	protected $includeAssets;
	protected $customerUri;
	protected $views;
	protected $render;
	protected $dataRequest;
	protected $model;
	protected $method;
	protected $request;
	protected $dataResponse;
	protected $appUserName;
	protected $greeting;
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
		$this->customerUri = $this->uri->segment(1, 0) ?? 'null';
		$this->render->widget =  FALSE;
		$this->render->prefix = '';
		$this->render->sessionTime = $this->config->item('session_time');
		$this->render->callModal = $this->render->sessionTime < 180000 ? ceil($this->render->sessionTime * 50 / 100) : 15000;
		$this->render->callServer = $this->render->callModal;
		$this->ValidateBrowser = FALSE;
		$this->singleSession = base64_decode(get_cookie('singleSession', TRUE));
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
		clientUrlValidate($this->customerUri);
		languageLoad('specific', $this->router->fetch_class());
		$this->customerUri = $this->config->item('customer-uri');

		if($this->session->has_userdata('userId')) {
			if($this->session->customerSess !== $this->config->item('customer')) {
				clientUrlValidate($this->session->customerUri);
				$urlRedirect = str_replace(
					$this->customerUri.'/', $this->session->customerUri.'/',
					base_url(lang('CONF_LINK_SIGNOUT').lang('CONF_LINK_SIGNOUT_START'))
				);
				redirect($urlRedirect, 'Location', 302);
				exit;
			}
		}

		$this->form_validation->set_error_delimiters('', '---');
		$this->config->set_item('language', 'global');

		if ($this->rule !== lang('CONF_LINK_SUGGESTION')) {
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
			$this->dataRequest = lang('CONF_CYPHER_DATA') == 'ON' ? json_decode(
				$this->security->xss_clean(
					strip_tags(
						$this->cryptography->decrypt(
							base64_decode($this->input->get_post('plot')),
							utf8_encode($this->input->get_post('request'))
						)
					)
				)
			) : json_decode(utf8_encode($this->input->get_post('request')));
		} else {
			$access = $this->verify_access->accessAuthorization($this->router->fetch_method(), $this->customerUri, $this->appUserName);
			$this->appUserName = isset($_POST['userName']) ? mb_strtoupper($_POST['userName']) : $this->session->userName;
			$valid = TRUE;

			if ($_POST && $access) {
				log_message('DEBUG', 'NOVO [' . $this->appUserName . '] IP ' . $this->input->ip_address() . ' REQUEST FROM THE VIEW ' . json_encode($this->input->post(), JSON_UNESCAPED_UNICODE));

				$valid = $this->verify_access->validateForm($this->rule, $this->customerUri, $this->appUserName);

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
	 * @modified Luis Molina
	 * @date January 19th, 2021
	 */
	protected function preloadView($auth)
	{
		log_message('INFO', 'NOVO Controller: preloadView Method Initialized');

		if ($auth) {
			$this->render->favicon = lang('GEN_FAVICON');
			$this->render->ext = lang('GEN_FAVICON_EXT');
			$this->render->loader = lang('IMG_LOADER');
			$this->render->customerUri = $this->customerUri;
			$this->render->novoName = $this->security->get_csrf_token_name();
			$this->render->novoCook = $this->security->get_csrf_hash();
			$validateRecaptcha = in_array($this->router->fetch_method(), lang('CONF_VALIDATE_CAPTCHA'));

			if (lang('CONF_VIEW_SUFFIX') === '-core') {
				$this->includeAssets->cssFiles = [
					"$this->customerUri/root-$this->customerUri",
					"root-general",
					"reboot",
					"$this->customerUri/"."$this->customerUri-base"
				];
			} else {
				$file = $this->customerUri == 'bpi' ? 'pichincha' : 'novo';
				$this->includeAssets->cssFiles = [
					"$file-validate",
					"third_party/jquery-ui",
					"$file-base"
				];
			}

			if (gettype($this->ValidateBrowser) !== 'boolean') {
				array_push(
					$this->includeAssets->cssFiles,
					"$this->customerUri/$this->customerUri-$this->ValidateBrowser-base"
				);
			}

			$this->includeAssets->jsFiles = [
				"third_party/html5",
				"third_party/jquery-3.6.0",
				"third_party/jquery-ui-1.13.1",
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
						"remote_connect/$this->customerUri-remoteConnect"
					);
				}
			}

			if ($validateRecaptcha) {
				array_push(
					$this->includeAssets->jsFiles,
					"googleRecaptcha"
				);

				if (ACTIVE_RECAPTCHA) {
					$this->load->library('recaptcha');
					$this->render->scriptCaptcha = $this->recaptcha->getScriptTag();
				}
			}

		} else {
			$linkredirect = uriRedirect();
			redirect(base_url($linkredirect), 'Location', 'GET');
			exit;
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
				$this->render->widget->widgetBtnTitle = lang('GEN_SELECT_ENTERPRISE');
				$this->render->widget->countProducts = $this->session->has_userdata('products');
				$this->render->widget->actionForm = lang('CONF_LINK_PRODUCT_DETAIL');
			}
		}

		if ($this->render->code > 2) {
			$this->render->title = $responseView->title;
			$this->render->msg = $responseView->msg;
			$this->render->icon = $responseView->icon;
			$this->render->modalBtn = json_encode($responseView->modalBtn);
		} elseif(isset($responseView->data->params))  {
			$this->render->params = json_encode($responseView->data->params);
		}
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

		$valid = $this->tool_browser->validBrowser($this->customerUri);

		if (!$valid) {
			redirect(base_url(lang('CONF_LINK_SUGGESTION')), 'location', 302);
			exit;
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
		$userMenu->enterpriseUrl = lang('CONF_LINK_ENTERPRISES');
		$userMenu->currentClass = $this->router->fetch_class();
		$this->render->logged = $this->session->has_userdata('logged');
		$this->appUserName = $this->session->userName;
		$this->render->fullName = $this->session->fullName;
		$this->render->productName = !$this->session->has_userdata('productInf') ?:
			$this->session->productInf->productName.' / '.$this->session->productInf->brand;
		$this->render->settingsMenu = $userMenu;
		$this->render->goOut = ($this->session->has_userdata('logged') || $this->session->flashdata('changePassword'))
			? lang('CONF_LINK_SIGNOUT').lang('CONF_LINK_SIGNOUT_START') : lang('CONF_LINK_SIGNIN');
		$this->render->module = $module;
		$this->render->viewPage = $this->views;
		$this->asset->initialize($this->includeAssets);
		$this->load->view('master_content'.lang('CONF_VIEW_SUFFIX'), $this->render);
	}
}
