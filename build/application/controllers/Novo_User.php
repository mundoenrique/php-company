<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Controlador para la vista principal de la aplicación
 * @author J. Enrique Peñaloza Piñero
*/
class Novo_User extends NOVO_Controller {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO User Controller Class Initialized');
	}
	/**
	 * @info Método que renderiza la vista de login
	 * @author J. Enrique Peñaloza Piñero.
	 */
	public function login()
	{
		log_message('INFO', 'NOVO User: index Method Initialized');

		if($this->session->has_userdata('logged')) {
			$oldUrl = str_replace($this->countryUri.'/', $this->config->item('country').'/', base_url('dashboard'));
			$urlRedirect = lang('CONF_VIEW_SUFFIX') != '-core' ? $oldUrl : base_url('empresas');
			redirect($urlRedirect, 'location');
			exit();
		}

		if ($this->session->has_userdata('userId')) {
			clearSessionsVars();
		}

		$view = 'login';

		if(ACTIVE_RECAPTCHA) {
			$this->load->library('recaptcha');
			$this->render->scriptCaptcha = $this->recaptcha->getScriptTag();
		}

		$views = ['user/login', 'user/signin'];

		if($this->skin !== 'novo') {
			$views = ['user/signin'];
		}

		array_push(
			$this->includeAssets->jsFiles,
			"third_party/jquery.balloon",
			"third_party/jquery.validate",
			"validate".lang('CONF_VIEW_SUFFIX')."-forms",
			"third_party/additional-methods",
			"user/login"
		);

		if($this->skin !== 'pichincha') {
			array_push(
				$this->includeAssets->jsFiles,
				"third_party/jquery.kwicks",
				"user/kwicks"
			);
		}

		if($this->skin === 'pichincha' && ENVIRONMENT === 'production') {
			array_push(
				$this->includeAssets->jsFiles,
				"third_party/borders"
			);
		}

		$singleSession = [
			'name' => 'singleSession',
			'value' => base64_encode('signIn'),
			'expire' => 0,
			'httponly' => TRUE
		];

		$this->input->set_cookie($singleSession);

		$this->render->skipProductInf = TRUE;
		$this->render->titlePage = lang('GEN_SYSTEM_NAME');
		$this->views = $views;
		$this->loadView($view);
	}
	/**
	 * @info Método para el cierre de sesión
	 * @author J. Enrique Peñaloza Piñero.
	 */
	public function singleSignOn($sessionId = FALSE)
	{
		log_message('INFO', 'NOVO User: singleSignOn Method Initialized');

		$view = 'singleSignOn';
		$this->render->send = FALSE;

		if ($sessionId) {
			$this->render->sessionId = $sessionId;
			$this->render->send = TRUE;
		} else {
			$this->render->sessionId = $this->request->sessionId;
		}

		if($sessionId == 'fin') {
			$view = 'finish';
			$this->render->activeHeader = TRUE;
			$this->render->showBtn = FALSE;
			$this->render->sessionEnd = lang('RESP_SINGLE_SIGNON');

			if ($this->session->flashdata('unauthorized') != NULL) {
				$this->render->sessionEnd = $this->session->flashdata('unauthorized');
			}
		} else {
			array_push(
				$this->includeAssets->jsFiles,
				'user/singleSignOn'
			);
			$this->render->skipmenu = TRUE;
		}

		$singleSession = [
			'name' => 'singleSession',
			'value' => base64_encode('SignThird'),
			'expire' => 0,
			'httponly' => TRUE
		];

		$this->input->set_cookie($singleSession);

		$this->render->titlePage = lang('GEN_SYSTEM_NAME');
		$this->render->skipProductInf = TRUE;
		$this->views = ['user/'.$view];
		$this->loadView($view);

	}
	/**
	 * @info Método que renderiza la vista para recuperar la contraseña
	 * @author J. Enrique Peñaloza Piñero.
	 */
	public function recoverPass()
	{
		log_message('INFO', 'NOVO User: passwordRecovery Method Initialized');

		$view = 'recoverPass';
		array_push(
			$this->includeAssets->jsFiles,
			"user/recoverPass",
			"third_party/jquery.validate",
			"validate".lang('CONF_VIEW_SUFFIX')."-forms",
			"third_party/additional-methods"
		);
		$this->render->titlePage = lang('GEN_RECOVER_PASS_TITLE');
		$this->render->activeHeader = TRUE;
		$this->render->skipProductInf = TRUE;
		$this->views = ['user/'.$view];
		$this->loadView($view);
	}
	/**
	 * @info Método que renderiza la vista para recuperar los datos de acceso
	 * @author Jhonnatan Vega.
	 */
	public function recoverAccess()
	{
		log_message('INFO', 'NOVO User: recoverAccess Method Initialized');

		$view = 'recoverAccess';
		array_push(
			$this->includeAssets->jsFiles,
			"user/recoverAccess",
			"third_party/jquery.validate",
			"validate-core-forms",
			"third_party/additional-methods"
		);
		$this->render->titlePage = lang('GEN_RECOVER_PASS_TITLE');
		$this->render->activeHeader = TRUE;
		$this->render->skipProductInf = TRUE;
		$this->views = ['user/'.$view];
		$this->loadView($view);
	}
	/**
	 * @info Método que renderiza la vista para cambiar la contraseña
	 * @author J. Enrique Peñaloza Piñero.
	 */
	public function changePassword()
	{
		log_message('INFO', 'NOVO User: changePassword Method Initialized');

		$view = 'changePassword';

		if(!$this->session->flashdata('changePassword')) {
			redirect(base_url('inicio'), 'location');
			exit();
		}

		array_push(
			$this->includeAssets->jsFiles,
			"user/changePassword".lang('CONF_VIEW_SUFFIX'),
			"user/passValidate",
			"third_party/jquery.balloon",
			"third_party/jquery.validate",
			"validate".lang('CONF_VIEW_SUFFIX')."-forms",
			"third_party/additional-methods"
		);

		switch($this->session->flashdata('changePassword')) {
			case 'newUser':
			$this->render->message = novoLang(lang("PASSWORD_NEWUSER"), lang('GEN_SYSTEM_NAME'));
			break;
			case 'expiredPass':
			$this->render->message = novoLang(lang("PASSWORD_EXPIRED"), lang('GEN_SYSTEM_NAME'));
			break;
		}

		$this->render->userType = $this->session->flashdata('userType');
		$this->session->set_flashdata('changePassword', $this->session->flashdata('changePassword'));
		$this->session->set_flashdata('userType', $this->session->flashdata('userType'));
		$this->render->titlePage = LANG('GEN_PASSWORD_CHANGE_TITLE');
		$this->render->activeHeader = TRUE;
		$this->views = ['user/'.$view];
		$this->loadView($view);
	}
	/**
	 * @info Método para el cierre de sesión
	 * @author J. Enrique Peñaloza Piñero.
	 */
	public function finishSession($redirect)
	{
		log_message('INFO', 'NOVO User: finishSession Method Initialized');

		$view = 'finish';
		$thirdPartySession = $this->singleSession == 'SignThird';

		if($this->session->has_userdata('userId')) {
			$this->load->model('Novo_User_Model', 'finishSession');
			$this->finishSession->callWs_FinishSession_User();
		}

		if($redirect == 'fin' || $thirdPartySession) {
			$pos = array_search('sessionControl', $this->includeAssets->jsFiles);
			$this->render->action = base_url('inicio');
			$this->render->showBtn = !$thirdPartySession;
			$this->render->sessionEnd = novoLang(lang('GEN_EXPIRED_SESSION'), lang('GEN_SYSTEM_NAME'));

			if ($this->session->flashdata('unauthorized') != NULL) {
				$this->render->sessionEnd = $this->session->flashdata('unauthorized');
			}

			if($redirect == 'inicio') {
				$this->render->sessionEnd = novoLang(lang('GEN_FINISHED_SESSION'), lang('GEN_SYSTEM_NAME'));
			}

			unset($this->includeAssets->jsFiles[$pos]);
			$this->render->activeHeader = TRUE;
			$this->render->skipProductInf = TRUE;
			$this->render->titlePage = LANG('GEN_FINISH_TITLE');
			$this->views = ['user/'.$view];
			$this->loadView($view);
		} else {
			redirect(base_url(lang('GEN_LINK_LOGIN')), 'location');
		}

	}
	/**
	 * @info Método que renderiza la vista de segerencias de navegador
	 * @author J. Enrique Peñaloza Piñero.
	 * @date November 25th, 2020
	 */
	public function suggestion()
	{
		log_message('INFO', 'NOVO User: suggestion Method Initialized');

		$view = 'suggestion';

		if(!$this->session->flashdata('messageBrowser')) {
			redirect(base_url('empresas'), 'location', 301);
			exit();
		}

		$views = ['staticpages/content-browser'];

		if(lang('CONF_VIEW_SUFFIX') != '') {
			$this->includeAssets->cssFiles = [
				"$this->folder"."$this->skin-browser"
			];
		}

		$messageBrowser = $this->session->flashdata('messageBrowser');
		$this->render->activeHeader = TRUE;
		$this->render->platform = $messageBrowser->platform;
		$this->render->title = $messageBrowser->title;
		$this->render->msg1 = $messageBrowser->msg1;
		$this->render->msg2 = $messageBrowser->msg2;
		$this->render->titlePage = lang('GEN_SYSTEM_NAME');
		$this->views = $views;
		$this->loadView($view);
	}
		/**
	 * @info Método que renderiza la vista de consulta de usuario
	 * @author Hector D. Corredor.
	 */
	public function userSettings()

	{
		log_message('INFO', 'NOVO User: userSettings Method Initialized');

		$view = 'userSettings';
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
			"user/userSettings"
		);
		$this->responseAttr();
		$this->render->titlePage = lang('GEN_MENU_USER_CONFIG');
		$this->views = ['user/'.$view];
		$this->loadView($view);

	}
}
