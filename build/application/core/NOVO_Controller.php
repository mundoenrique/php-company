<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Clase contralador de Conexión Empresas Online (CEO)
 *
 * Esta clase es la súper clase de la que heredarán todos los controladores
 * de la aplicación.
 *
 * @package controllers
 * @author J. Enrique Peñaloza Piñero
 */
class NOVO_Controller extends CI_Controller
{
	protected $customerUri;
	protected $customerLang;
	protected $customerFiles;
	protected $customerStyle;
	protected $fileLanguage;
	protected $controllerClass;
	protected $controllerMethod;
	protected $modelClass;
	protected $modelMethod;
	protected $validationMethod;
	protected $includeAssets;
	protected $request;
	protected $dataResponse;
	protected $render;
	protected $dataRequest;
	protected $greeting;
	protected $views;
	protected $isValidRequest;
	protected $before;
	public $singleSession;

	public function __construct()
	{
		parent::__construct();
		writeLog('INFO', 'Controller Class Initialized');

		$class = $this->router->class;
		$method = $this->router->method;
		$customerUri = $this->uri->segment(1, 0);

		$this->customerUri = $customerUri;
		$this->customerLang = $customerUri;
		$this->customerStyle = $customerUri;
		$this->customerFiles = $customerUri;
		$this->fileLanguage = lcfirst(str_replace('Novo_', '', $class));
		$this->controllerClass = $class;
		$this->controllerMethod = $method;
		$this->modelClass = $class . '_Model';
		$this->modelMethod = 'callWs_' . ucfirst($method) . '_' . str_replace('Novo_', '', $class);
		$this->validationMethod = $method;
		$this->includeAssets = new stdClass();
		$this->request = new stdClass();
		$this->dataResponse = new stdClass();
		$this->render = new stdClass();
		$this->isValidRequest = FALSE;
		$this->before = TRUE;
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
		writeLog('INFO', 'Controller: optionsCheck Method Initialized');

		if ($this->controllerMethod !== lang('SETT_LINK_SUGGESTION')) {
			$this->checkBrowser();
		}

		if ($this->input->post('payload')) {
			$this->before = false;
			$request = decryptData($this->input->post('payload'));
			$this->dataRequest = json_decode($request);
			unset($_POST);

			if ($this->input->is_ajax_request()) {
				$this->fileLanguage = lcfirst($this->dataRequest->module);
				$this->modelClass = 'Novo_' . ucfirst($this->dataRequest->module) . '_Model';
				$this->modelMethod = 'callWs_' . ucfirst($this->dataRequest->section) . '_' . $this->dataRequest->module;
				$this->validationMethod = lcfirst($this->dataRequest->section);
			}

			foreach ($this->dataRequest->data as $item => $value) {
				$_POST[$item] = $value;
			}

			unset($this->dataRequest);
			$this->isValidRequest = $this->verify_access->accessAuthorization($this->validationMethod);

			if (!empty($_FILES) && $this->isValidRequest) {
				$this->isValidRequest = $this->manageFile();
			}

			if ($this->isValidRequest) {
				$this->request = $this->verify_access->createRequest($this->modelClass, $this->modelMethod);
				$this->isValidRequest = $this->verify_access->validateForm($this->validationMethod);
			}
		}

		LoadLangFile('generic', $this->fileLanguage, $this->customerLang);
		clientUrlValidate($this->customerUri);
		$this->customerUri = $this->config->item('customer_uri');
		$this->customerLang = $this->config->item('customer_lang');
		$this->customerStyle = $this->config->item('customer_style');
		$this->customerFiles = $this->config->item('customer_files');
		LoadLangFile('specific', $this->fileLanguage, $this->customerLang);

		if ($this->session->has_userdata('userId')) {
			if ($this->session->customerSess !== $this->config->item('customer')) {
				clientUrlValidate($this->session->customerUri);
				$urlRedirect = str_replace(
					$this->customerUri . '/',
					$this->session->customerUri . '/',
					base_url(lang('SETT_LINK_SIGNOUT') . lang('SETT_LINK_SIGNOUT_START'))
				);
				redirect($urlRedirect, 'Location', 302);
				exit;
			}
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

		if ($this->input->is_ajax_request() && $this->before) {
			$this->dataRequest = ACTIVE_SAFETY ? json_decode(
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
			$access = $this->verify_access->accessAuthorization($this->validationMethod);
			$valid = TRUE;

			if ($_POST && $access && $this->before) {
				$this->request = $this->verify_access->createRequest($this->controllerClass, $this->controllerMethod);
				$valid = $this->verify_access->validateForm($this->validationMethod);
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
		writeLog('INFO', 'Controller: preloadView Method Initialized');

		if ($auth) {
			$this->render->favicon = lang('GEN_FAVICON') . '.' . lang('GEN_FAVICON_EXT');
			$this->render->faviconExt = lang('GEN_FAVICON_EXT');
			$this->render->customerUri = $this->customerUri;
			$this->render->customerStyle = $this->customerStyle;
			$this->render->customerLang = $this->customerLang;
			$this->render->customerFiles = $this->customerFiles;
			$validateRecaptcha = in_array($this->controllerMethod, lang('SETT_VALIDATE_CAPTCHA'));

			$this->render->widget =  FALSE;
			$this->render->prefix = '';
			$this->render->sessionTime = $this->config->item('session_time');
			$this->render->callModal = $this->render->sessionTime < 180000 ? ceil($this->render->sessionTime * 50 / 100) : 15000;
			$this->render->callServer = $this->render->callModal;

			if (lang('SETT_VIEW_SUFFIX') === '-core') {
				$this->includeAssets->cssFiles = [
					"$this->customerStyle/$this->customerStyle-root",
					"general-root",
					"reboot",
					"$this->customerStyle/$this->customerStyle-base"
				];
			} else {
				$file = $this->customerUri == 'bpi' ? 'pichincha' : 'novo';
				$this->includeAssets->cssFiles = [
					"$file-validate",
					"third_party/jquery-ui",
					"$file-base"
				];
			}

			$this->includeAssets->jsFiles = [
				"third_party/html5",
				"third_party/jquery-3.7.1",
				"third_party/jquery-ui-1.13.2",
				"third_party/aes",
				"connection/core_app",
				"modal/ui_modal",
				"aes-json-format",
				"encrypt_decrypt",
				"utils",
				"helper",
				"thirdPartyConfig"
			];

			if ($this->session->has_userdata('logged')) {
				array_push(
					$this->includeAssets->jsFiles,
					"third_party/jquery.balloon",
					"sessionControl"
				);

				if (lang('SETT_REMOTE_AUTH') === 'ON') {
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
			$linkredirect = uriRedirect($this->singleSession);
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
		writeLog('INFO', 'Controller: loadModel Method Initialized. Model loaded: ' . $this->modelClass);

		$this->load->model($this->modelClass, 'modelLoaded');
		$method = $this->modelMethod;

		return $this->modelLoaded->$method($request);
	}
	/**
	 * Método para extraer mensaje al usuario
	 * @author J. Enrique Peñaloza Piñero
	 * @date Auguts 22nd, 2019
	 */
	protected function responseAttr($responseView = 0, $active = TRUE)
	{
		writeLog('INFO', 'Controller: responseAttr Method Initialized');

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

		if (($this->render->code === 0  && $active) || $download) {

			if (count($this->render->enterpriseList) > 1 || $this->session->has_userdata('products')) {
				array_push(
					$this->includeAssets->jsFiles,
					"business/widget-enterprise"
				);

				$this->render->widget =  new stdClass();
				$this->render->widget->widgetBtnTitle = lang('GEN_SELECT_ENTERPRISE');
				$this->render->widget->hasProducts = $this->session->has_userdata('products');
				$this->render->widget->actionForm = lang('SETT_LINK_PRODUCT_DETAIL');
			}
		}

		if ($this->render->code > 2) {
			$this->render->title = $responseView->title;
			$this->render->msg = $responseView->msg;
			$this->render->icon = $responseView->icon;
			$this->render->modalBtn = $responseView->modalBtn;
		} elseif (isset($responseView->data->params)) {
			$this->render->params = $responseView->data->params;
		}
	}
	/**
	 * Método para validar la versión de browser
	 * @author J. Enrique Peñaloza Piñero
	 * @date January 23nd, 2019
	 */
	protected function checkBrowser()
	{
		writeLog('INFO', 'Controller: checkBrowser Method Initialized');
		$this->load->library('Tool_Browser');

		$valid = $this->tool_browser->validBrowser();

		if (!$valid) {
			redirect(base_url(lang('SETT_LINK_SUGGESTION')), 'location', 302);
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
		writeLog('INFO', 'Controller: loadView Method Initialized. Module loaded: ' . $module);

		$userMenu = new stdClass();
		$userMenu->userAccess = $this->session->user_access;
		$userMenu->enterpriseUrl = lang('SETT_LINK_ENTERPRISES');
		$userMenu->currentClass = $this->router->fetch_class();
		$this->render->logged = $this->session->has_userdata('logged');
		$this->render->userId = $this->session->has_userdata('userId');
		$this->render->fullName = $this->session->fullName;
		$this->render->productName = !$this->session->has_userdata('productInf') ?:
			$this->session->productInf->productName . ' / ' . $this->session->productInf->brand;
		$this->render->settingsMenu = $userMenu;
		$this->render->goOut = ($this->session->has_userdata('logged') || $this->session->flashdata('changePassword'))
			? lang('SETT_LINK_SIGNOUT') . lang('SETT_LINK_SIGNOUT_START') : lang('SETT_LINK_SIGNIN');
		$this->render->module = $module;
		$this->render->viewPage = $this->views;
		$this->render->singleSession = $this->singleSession;
		$this->asset->initialize($this->includeAssets);
		$this->load->view('master_content' . lang('SETT_VIEW_SUFFIX'), $this->render);
	}
}
