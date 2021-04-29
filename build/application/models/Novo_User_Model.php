<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Módelo para la información del usuario
 * @author J. Enrique Peñaloza Piñero
 * @date May 14th, 2019
 */
class Novo_User_Model extends NOVO_Model {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO User Model Class Initialized');
	}
	/**
	 * @info Método para el inicio de sesión
	 * @author J. Enrique Peñaloza Piñero
	 * @date May 14th, 2019
	 */
	public function callWs_SignIn_User($dataRequest)
	{
		log_message('INFO', 'NOVO User Model: Login Method Initialized');

		$userName = mb_strtoupper($dataRequest->userName);
		$password = $this->cryptography->decryptOnlyOneData($dataRequest->userPass);
		$authToken = $this->session->flashdata('authToken') ?? '';

		$this->dataAccessLog->modulo = 'Usuario';
		$this->dataAccessLog->function = 'Ingreso al sistema';
		$this->dataAccessLog->operation = 'Iniciar sesion';
		$this->dataAccessLog->userName = $userName;

		$this->dataRequest->idOperation = 'loginFull';
		$this->dataRequest->className = 'com.novo.objects.TOs.UsuarioTO';
		$this->dataRequest->userName = $userName;
		$this->dataRequest->password = md5($password);
		$this->dataRequest->ctipo = $dataRequest->active;

		if (IP_VERIFY == 'ON') {
			$this->dataRequest->codigoOtp = [
				'tokenCliente' => $dataRequest->otpCode ?? '',
				'authToken' => $authToken
			];

			if (isset($dataRequest->saveIP)) {
				$this->dataRequest->guardaIp = $dataRequest->saveIP;
			}
		}

		if (lang('CONFIG_MAINTENANCE') == 'ON') {
			$this->isResponseRc = 9997;
		}	elseif (isset($dataRequest->otpCode) && $authToken == '') {
			$this->isResponseRc = 9998;
		} else {
			$this->isResponseRc = ACTIVE_RECAPTCHA && !isset($dataRequest->skipCaptcha) ? $this->callWs_ValidateCaptcha_User($dataRequest) : 0;

			if ($this->isResponseRc === 0) {
				$response = $this->sendToService('callWs_Login');
			}
		}

		if(lang('CONFIG_PASS_EXPIRED') == 'OFF' && ($this->isResponseRc == -2 || $this->isResponseRc == -185)) {
			$this->isResponseRc = 0;
		}

		$time = (object) [
			'customerTime' => (int) $dataRequest->currentTime,
			'serverTime' => (int) date("H")
		];

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$fullName = mb_strtolower($response->usuario->primerNombre).' ';
				$fullName.= mb_strtolower($response->usuario->primerApellido);
				$formatDate = $this->config->item('format_date');
				$formatTime = $this->config->item('format_time');
				$lastSession = date(
					"$formatDate $formatTime", strtotime(
						str_replace('/', '-', $response->usuario->fechaUltimaConexion)
					)
				);
				$userData = [
					'sessionId' => $response->logAccesoObject->sessionId,
					'logged' => TRUE,
					'userId' => $response->usuario->idUsuario,
					'userName' => $response->usuario->userName,
					'passWord' => lang('CONF_REMOTE_AUTH') == 'ON' ? $this->dataRequest->password : FALSE,
					'fullName' => ucwords(mb_strtolower($fullName)),
					'userType' => $response->usuario->ctipo,
					'codigoGrupo' => $response->usuario->codigoGrupo,
					'lastSession' => $lastSession,
					'token' => $response->token,
					'time' => $time,
					'cl_addr' => $this->encrypt_connect->encode($this->input->ip_address(), $userName, 'REMOTE_ADDR'),
					'countrySess' => $this->config->item('country'),
					'countryUri' => $this->config->item('country-uri'),
					'clientAgent' => $this->agent->agent_string(),
					'autoLogin' => 'false',
					'idUsuario' => $response->usuario->idUsuario,
					'pais' => $this->config->item('country'),
					'nombreCompleto' => $fullName,
					'logged_in' => TRUE
				];
				$this->session->set_userdata($userData);
				$this->response->data = base_url(lang('GEN_ENTERPRISE_LIST'));
				$this->response->modal = TRUE;
			break;
			case -2:
			case -185:
				$this->response->code = 0;
				$fullName = mb_strtolower($response->usuario->primerNombre.' '.$response->usuario->primerApellido);
				$userData = [
					'sessionId' => $response->logAccesoObject->sessionId,
					'userId' => $response->usuario->idUsuario,
					'userName' => $response->usuario->userName,
					'fullName' => ucwords(mb_strtolower($fullName)),
					'codigoGrupo' => $response->usuario->codigoGrupo,
					'token' => $response->token,
					'time' => $time,
					'cl_addr' => $this->encrypt_connect->encode($this->input->ip_address(), $userName, 'REMOTE_ADDR'),
					'countrySess' => $this->config->item('country'),
					'countryUri' => $this->config->item('country-uri'),
					'clientAgent' => $this->agent->agent_string()
				];
				$this->session->set_userdata($userData);
				$this->response->data = base_url('inf-condiciones');
				$this->session->set_flashdata('changePassword', 'newUser');
				$this->session->set_flashdata('userType', $response->usuario->ctipo);

				if($this->isResponseRc === -185) {
					$this->response->data = base_url('cambiar-clave');
					$this->session->set_flashdata('changePassword', 'expiredPass');
				}
			break;
			case -1:
				$this->response->code = 1;
				$this->response->msg = lang('USER_SIGNIN_INVALID_USER');
				$this->response->className = lang('CONF_VALID_INVALID_USER');
				$this->response->position = lang('CONF_VALID_POSITION');
			break;
			case -263:
				$this->response->code = 1;
				$this->response->msg = lang('USER_SIGNIN_WILL_BLOKED');
				$this->response->className = lang('CONF_VALID_INVALID_USER');
				$this->response->position = lang('CONF_VALID_POSITION');
			break;
			case -8:
			case -35:
				$this->response->code = 1;
				$this->response->msg = lang('USER_SIGNIN_SUSPENDED');
				$this->response->className = lang('CONF_VALID_INACTIVE_USER');
				$this->response->position = lang('CONF_VALID_POSITION');
			break;
			case -424:
				$this->response->code = 2;
				$this->response->msg = novoLang(lang('GEN_LOGIN_IP_MSG'), $response->usuario->emailEnc);
				$this->response->labelInput = lang('GEN_LOGIN_IP_LABEL_INPUT');
				$this->response->assert = lang('GEN_LOGIN_IP_ASSERT');
				$this->response->modalBtn['btn1']['action'] = 'none';
				$this->response->modalBtn['btn2']['text'] = lang('GEN_BTN_CANCEL');
				$this->response->modalBtn['btn2']['action'] = 'destroy';
				$this->session->set_flashdata('authToken', json_decode($response->usuario->codigoOtp->access_token));
			break;
			case -28:
				$this->response->msg = lang('RESP_INCORRECTLY_CLOSED');
				$this->response->data = 'session-close';
				$this->response->modalBtn['btn1']['action'] = 'none';
				$this->response->modalBtn['btn2']['text'] = lang('GEN_BTN_CANCEL');
				$this->response->modalBtn['btn2']['action'] = 'destroy';
			break;
			break;
			case -229:
				$this->response->icon = lang('CONF_ICON_INFO');
				$this->response->msg = lang('USER_SIGNIN_OLD_APP');
				$this->response->modalBtn['btn1']['action'] = 'destroy';
			break;
			case -262:
				$this->response->icon = lang('CONF_ICON_INFO');
				$this->response->msg = novoLang(lang('USER_SIGNIN_NO_MIGRED'), $dataRequest->userName);
				$this->response->modalBtn['btn1']['action'] = 'destroy';
			break;
			case -286:
					$this->response->msg = lang('GEN_RESP_CODE_INVALID');
					$this->response->modalBtn['btn1']['action'] = 'destroy';
			break;
			case -287:
			case -288:
				$this->response->msg = lang('GEN_RESP_CODE_OTP_INVALID');
				$this->response->modalBtn['btn1']['action'] = 'destroy';
			break;
			case 9997:
				$this->response->code = 4;
				$this->response->icon = lang('CONF_ICON_INFO');
				$this->response->title = lang('GEN_SYSTEM_NAME');
				$this->response->msg = 'estamos haciendo mantenimiento a la plataforma para atenderte mejor';
				$this->response->modalBtn['btn1']['action'] = 'destroy';
				$this->response->modalBtn['btn1']['text'] = lang('GEN_BTN_ACCEPT');
			break;
			case 9998:
				$this->response->code = 4;
				$this->response->icon = lang('CONF_ICON_INFO');
				$this->response->title = lang('GEN_SYSTEM_NAME');
				$this->response->msg = lang('USER_EXPIRE_TIME');
				$this->response->modalBtn['btn1']['action'] = 'destroy';
			break;
		}

		return $this->responseToTheView('callWs_Login');
	}
	/**
	 * @info Método para el inicio de sesión único
	 * @author J. Enrique Peñaloza Piñero
	 * @date May 14th, 2019
	 */
	public function callWs_SingleSignon_User($dataRequest)
	{
		log_message('INFO', 'NOVO User Model: SingleSignon Method Initialized');

		$this->dataAccessLog->modulo = 'Usuario';
		$this->dataAccessLog->function = 'Ingreso al sistema';
		$this->dataAccessLog->operation = 'Inicio de sesión único';
		$this->dataAccessLog->userName = $this->country;

		$this->dataRequest->idOperation = lang('CONF_SINGLE_SIGN_ON');
		$this->dataRequest->className = 'com.novo.objects.TOs.RequestTO';

		switch ($this->country) {
			case 'Bdb':
				$this->token = $dataRequest->sessionId;
			break;
			case 'Mx-Bn':
				$this->dataRequest->userName = '';
				$this->dataRequest->password = '';
				$this->dataRequest->ctipo = $dataRequest->Canal;
				$this->dataRequest->codigoOtp = [
					'tokenCliente' => $dataRequest->ip ?? $this->input->ip_address(),
					'authToken' => $dataRequest->IdServicio,
				];
				$this->dataRequest->guardaIp = FALSE;
				$this->token = $dataRequest->Clave;
			break;
		}

		$response = $this->sendToService('callWs_SingleSignon');

		if(lang('CONFIG_PASS_EXPIRED') == 'OFF' && ($this->isResponseRc == -2 || $this->isResponseRc == -185)) {
			$this->isResponseRc = 0;
		}

		$this->response->code = 0;

		switch ($this->isResponseRc) {
			case 0:
				$fullName = mb_strtolower($response->usuario->primerNombre).' ';
				$fullName.= mb_strtolower($response->usuario->primerApellido);
				$formatDate = $this->config->item('format_date');
				$formatTime = $this->config->item('format_time');
				$lastSession = date(
					"$formatDate $formatTime", strtotime(
						str_replace('/', '-', $response->usuario->fechaUltimaConexion)
					)
				);
				$time = (object) [
					'customerTime' => (int) $dataRequest->currentTime,
					'serverTime' => (int) date("H")
				];
				$userData = [
					'sessionId' => $response->logAccesoObject->sessionId,
					'logged' => TRUE,
					'userId' => $response->usuario->idUsuario,
					'userName' => $response->usuario->userName,
					'passWord' => $response->usuario->password ?? FALSE,
					'fullName' => ucwords(mb_strtolower($fullName)),
					'codigoGrupo' => $response->usuario->codigoGrupo,
					'lastSession' => $lastSession,
					'token' => $response->token,
					'time' => $time,
					'cl_addr' => $this->encrypt_connect->encode($this->input->ip_address(), $this->country, 'REMOTE_ADDR'),
					'countrySess' => $this->config->item('country'),
					'countryUri' => $this->config->item('country-uri'),
					'clientAgent' => $this->agent->agent_string(),
					'autoLogin' => 'true',
          'enterpriseInf' => [
            'thirdApp' => $dataRequest->Canal ?? ''
          ]
				];
				$this->session->set_userdata($userData);
				$this->response->code = 0;
				$this->response->data = base_url(lang('GEN_ENTERPRISE_LIST'));
			break;
			case -28:
				$userData = [
					'userId' => $response->usuario->idUsuario,
					'userName' => $response->usuario->userName,
					'codigoGrupo' => $response->usuario->codigoGrupo,
					'countrySess' => $this->config->item('country')
				];
				$this->session->set_userdata($userData);
				$this->session->set_flashdata('unauthorized', lang('RESP_SESSION_DUPLICATE'));
				$this->response->data = base_url('cerrar-sesion/fin');
			break;
			default:
				$this->response->data = base_url('ingresar/fin');
			break;
		}

		return $this->responseToTheView('callWs_SingleSignon');
	}
	/**
	 * @info Método para recuperar contraseña
	 * @author J. Enrique Peñaloza Piñero
	 * @date April 29th, 2019
	 */
	public function callWs_RecoverPass_User($dataRequest)
	{
		log_message('INFO', 'NOVO User Model: RecoverPass Method Initialized');

		$this->dataAccessLog->modulo = 'Usuario';
		$this->dataAccessLog->function = 'Recuperar Clave';
		$this->dataAccessLog->operation = 'Enviar Clave';
		$userName = mb_strtoupper($dataRequest->user);
		$this->dataAccessLog->userName = $userName;

		$this->dataRequest->idOperation = 'olvidoClave';
		$this->dataRequest->className = 'com.novo.objects.TO.UsuarioTO';
		$this->dataRequest->userName = $userName;
		$this->dataRequest->idEmpresa = $dataRequest->idEmpresa;
		$this->dataRequest->email = $dataRequest->email;
		$maskMail = maskString($dataRequest->email, 4, $end = 6, '@');

		$this->isResponseRc = ACTIVE_RECAPTCHA ? $this->callWs_ValidateCaptcha_User($dataRequest) : 0;

		if ($this->isResponseRc === 0) {
			$response = $this->sendToService('callWs_RecoverPass');
		}

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->msg = novoLang(lang('RESP_TEMP_PASS'), [$this->dataRequest->userName, $maskMail]);
				$this->response->icon = lang('CONF_ICON_SUCCESS');
				$this->response->modalBtn['btn1']['text'] = lang('GEN_BTN_CONTINUE');
				$this->response->modalBtn['btn1']['link'] = 'inicio';
				$this->response->modalBtn['btn1']['action'] = 'redirect';
				break;
			case -6:
				$this->response->code = 1;
				$this->response->msg = novoLang(lang('RESP_COMPANNY_NOT_ASSIGNED'), $this->dataRequest->userName);
				break;
			case -150:
				$this->response->code = 1;
				$this->response->msg = novoLang(lang('RESP_FISCAL_REGISTRY_NO_FOUND'), [lang('RESP_FISCAL_REGISTRY_OF'), lang('GEN_FISCAL_REGISTRY'), lang('RESP_FISCAL_REGISTRY_OF_ENTERPRISE')]);
				break;
			case -159:
				$this->response->code = 1;
				$this->response->msg = novoLang(lang('RESP_EMAIL_NO_FOUND'), $maskMail);
				break;
			case -173:
				$this->response->code = 1;
				$this->response->msg = lang('RESP_EMAIL_NO_SENT');
				break;
			case -205:
				$this->response->code = 1;
				$this->response->msg = lang('RESP_UNREGISTERED_USER');
				$this->response->msg.= novoLang(lang('RESP_SUPPORT'), [lang('RESP_SUPPORT_MAIL'), lang('RESP_SUPPORT_TELF')]);
				break;
		}

		if($this->isResponseRc != 0 && $this->response->code == 1) {
			$this->response->title = lang('GEN_RECOVER_PASS_TITLE');
			$this->response->icon = lang('CONF_ICON_INFO');
			$this->response->modalBtn['btn1']['action'] = 'destroy';
		}

		return $this->responseToTheView('callWs_RecoverPass');
	}
	/**
	 * @info Método para recuperar acceso con OTP
	 * @author J. Enrique Peñaloza Piñero
	 * @date July 14th, 2020
	 */
	public function callWs_RecoverAccess_User($dataRequest)
	{
		log_message('INFO', 'NOVO User Model: RecoverAccess Method Initialized');

		$this->dataAccessLog->modulo = 'Usuario';
		$this->dataAccessLog->function = 'Recuperar Acceso';
		$this->dataAccessLog->operation = 'Generar código OTP';
		$userName = isset($dataRequest->user) ? mb_strtoupper($dataRequest->user) : '';
		$this->dataAccessLog->userName = $userName;

		$this->dataRequest->idOperation = 'genericBusiness';
		$this->dataRequest->className = 'com.novo.objects.MO.GenericBusinessObject';
		$this->dataRequest->userName = $userName;
		$this->dataRequest->tipoDocumento = $dataRequest->documentType;
		$this->dataRequest->cedula = $dataRequest->documentId;
		$this->dataRequest->email = $dataRequest->email;
		$this->dataRequest->opcion = 'generarOTP';
		$this->dataRequest->subOpciones = [
			[
				'subOpcion' => 'validarDatosRecuperar',
      	'orden' => '1'
			]
		];
		$map = 0;

		$this->isResponseRc = ACTIVE_RECAPTCHA ? $this->callWs_ValidateCaptcha_User($dataRequest) : 0;

		if ($this->isResponseRc === 0) {
			$response = $this->sendToService('callWs_AccessRecover');
		}

		switch($this->isResponseRc) {
			case 200:
				$this->session->set_flashdata('authToken', $response->bean->TokenTO->authToken);
				$this->session->set_flashdata('userName', $response->logAccesoObject->userName);
				$this->response->code = 0;
				$this->response->msg = lang('GEN_OTP');
				$this->response->icon = lang('CONF_ICON_SUCCESS');
				$this->response->modalBtn['btn1']['action'] = 'none';
			break;
			case -100:
			case -101:
			case -102:
			case -103:
				$map = 1;
				$this->response->msg = lang('GEN_INVALID_DATA');
			break;
		}

		if($this->isResponseRc != 0 && $map == 1) {
			$this->response->title = lang('GEN_RECOVER_PASS_TITLE');
			$this->response->icon = lang('CONF_ICON_INFO');
			$this->response->modalBtn['btn1']['action'] = 'destroy';
		}

		return $this->responseToTheView('callWs_RecoverAccess');
	}
		/**
	 * @info Método para recuperar acceso con OTP
	 * @author Jhonnatan Vega
	 * @date July 14th, 2020
	 */
	public function callWs_ValidateOtp_User($dataRequest)
	{
		log_message('INFO', 'NOVO User Model: ValidateOtp Method Initialized');

		$this->dataAccessLog->modulo = 'Usuario';
		$this->dataAccessLog->function = 'Recuperar Acceso';
		$this->dataAccessLog->operation = 'Validar código OTP';
		$this->dataAccessLog->userName = $this->session->flashdata('userName');

		$this->dataRequest->idOperation = 'genericBusiness';
		$this->dataRequest->className = 'com.novo.objects.MO.GenericBusinessObject';
		$this->dataRequest->userName = $this->session->flashdata('userName');
		$this->dataRequest->opcion = 'validarOTP';
		$this->dataRequest->TokenTO = [
			'access_token' => $this->session->flashdata('authToken'),
      'token' => $dataRequest->optCode,
		];
		$this->dataRequest->subOpciones = [
			[
				'subOpcion' => 'envioEmailProdubancoRecuperacion',
      	'orden' => '1'
			]
		];
		$maskMail = maskString($dataRequest->email, 4, $end = 6, '@');
		$map = 0;

		$this->isResponseRc = ACTIVE_RECAPTCHA ? $this->callWs_ValidateCaptcha_User($dataRequest) : 0;

		if ($this->isResponseRc === 0){
			if ($this->session->flashdata('authToken') != NULL) {
				$response = $this->sendToService('callWs_ValidateOtp');
			} else {
				$this->isResponseRc = 998;
			}
		}

		switch($this->isResponseRc) {
			case 0:
				$this->response->msg = novoLang(lang('GEN_SEND_ACCESS'), [$maskMail]);
				$this->response->icon = lang('CONF_ICON_SUCCESS');
				$this->response->modalBtn['btn1']['link'] = 'inicio';
			break;
			case -286:
				$map = 1;
				$this->response->msg = lang('GEN_SO_CREATE_INCORRECT');
			break;
			case -287:
			case -288:
				$map = 1;
				$this->response->msg = lang('GEN_SO_CREATE_EXPIRED');
			break;
			case 998:
				$map = 1;
				$this->response->code = 4;
				$this->response->msg = lang('USER_EXPIRE_TIME');
			break;
		}

		if($this->isResponseRc != 0 && $map == 1) {
			$this->response->title = lang('GEN_RECOVER_PASS_TITLE');
			$this->response->icon = lang('CONF_ICON_INFO');
			$this->response->modalBtn['btn1']['action'] = 'destroy';
		}

		return $this->responseToTheView('callWs_ValidateOtp');
	}
	/**
	 * @info Método para el cambio de Contraseña
	 * @author J. Enrique Peñaloza Piñero
	 * @date April 29th, 2019
	 * @modified Diego Acosta García
	 * @date April 29th, 2020
	 */
	public function CallWs_ChangePassword_User($dataRequest)
	{
		log_message('INFO', 'NOVO User Model: ChangePassword Method Initialized');

		$this->dataAccessLog->modulo = 'Usuario';
		$this->dataAccessLog->function = 'Clave';
		$this->dataAccessLog->operation = 'Cambiar Clave';

		$current = $this->cryptography->decryptOnlyOneData($dataRequest->currentPass);
		$new = $this->cryptography->decryptOnlyOneData($dataRequest->newPass);

		$this->dataRequest->idOperation = 'cambioClave';
		$this->dataRequest->className = 'com.novo.objects.TOs.UsuarioTO';
		$this->dataRequest->userName = $this->userName;
		$this->dataRequest->passwordOld = md5($current);
		$this->dataRequest->password = md5($new);
		$changePassType = $this->session->flashdata('changePassword');
		$this->sendToService('CallWs_ChangePassword');
		$code = 0;

		switch($this->isResponseRc) {
			case 0:
				if(!$this->session->has_userdata('logged')) {
					$this->callWs_FinishSession_User();
				}
				$this->response->code = 4;
				$goLogin = $this->session->has_userdata('logged') ? '' : lang('RESP_PASSWORD_LOGIN');
				$this->response->msg = novoLang(lang('RESP_PASSWORD_CHANGED'), $goLogin);
				$this->response->icon = lang('CONF_ICON_SUCCESS');
				$this->response->modalBtn['btn1']['text'] = lang('GEN_BTN_CONTINUE');
				$this->response->modalBtn['btn1']['link']  = 'inicio';
				$this->response->modalBtn['btn1']['action'] = $this->session->has_userdata('logged') ? 'destroy' :  'redirect';
			break;
			case -4:
				$code = 1;
				$this->response->msg = lang('RESP_PASSWORD_USED');
			break;
			case -1:
			case -22:
				$code = 1;
				$this->response->msg = lang('RESP_PASSWORD_INCORRECT');
			break;
		}

		if($this->isResponseRc != 0 && $code == 1) {
			$this->session->set_flashdata('changePassword', $changePassType);
			$this->session->set_flashdata('userType', $this->session->flashdata('userType'));

			$this->response->title = lang('GEN_PASSWORD_CHANGE_TITLE');
			$this->response->icon = lang('CONF_ICON_WARNING');
			$this->response->modalBtn['btn1']['action'] = 'destroy';
		}

		return $this->responseToTheView('CallWs_ChangePassword');
	}
	/**
	 * @info Método para el cierre de sesión
	 * @author J. Enrique Peñaloza Piñero
	 * @date May 1st, 2019
	 */
	public function callWs_KeepSession_User($dataRequest = FALSE)
	{
		log_message('INFO', 'NOVO User Model: KeepSession Method Initialized');
		$response = new stdClass();
		$response->rc =  0;
		$this->makeAnswer($response, 'callWs_GetBranchOffices');
		$this->response->code = 0;

		return $this->responseToTheView('callWs_KeepSession');
	}
	/**
	 * @info Método para el cierre de sesión
	 * @author J. Enrique Peñaloza Piñero
	 * @date May 1st, 2019
	 */
	public function callWs_FinishSession_User($dataRequest = FALSE)
	{
		log_message('INFO', 'NOVO User Model: FinishSession Method Initialized');

		$userName = $dataRequest ? mb_strtoupper($dataRequest->userName) : $this->userName;

		$this->dataAccessLog->userName = $userName;
		$this->dataAccessLog->modulo = 'Usuario';
		$this->dataAccessLog->function = 'Salir del sistema';
		$this->dataAccessLog->operation = 'Cerrar sesion';

		$this->dataRequest->idOperation = 'desconectarUsuario';
		$this->dataRequest->className = 'com.novo.objects.TOs.UsuarioTO';
		$this->dataRequest->idUsuario = $userName;
		$this->dataRequest->codigoGrupo = $this->session->codigoGrupo;

		$response = $this->sendToService('callWs_FinishSession');

		$this->response->code = 0;
		$this->response->msg = lang('GEN_BTN_ACCEPT');
		$this->response->data = FALSE;

		if (!$this->input->is_ajax_request()) {
			$this->session->sess_destroy();
		}

		clearSessionsVars();

		return $this->responseToTheView('callWs_FinishSession');
	}
	/**
	 * @info Método validación recaptcha
	 * @author Yelsyns Lopez
	 * @date May 16th, 2019
	 * @modified J. Enrique Peñaloza Piñero
	 * @date October 21st, 2019
	 * @modified Luis Molina
	 * @date January 18th, 2021
	 */
	public function callWs_ValidateCaptcha_User($dataRequest)
	{
		log_message('INFO', 'NOVO User Model: validateCaptcha Method Initialized');

		$this->load->library('recaptcha');

		$userName = $dataRequest->userName ?? ($dataRequest->user ?? '');

		$result = $this->recaptcha->verifyResponse($dataRequest->token);
		$logMessage = 'NOVO ['.$userName.'] RESPONSE: recaptcha País: "' .$this->config->item('country');
		$logMessage.= '", Score: "' . $result["score"] .'", Hostname: "'. $result["hostname"].'"';

		log_message('DEBUG', $logMessage);

		$resultRecaptcha = $result["score"] <= lang('CONF_SCORE_CAPTCHA')[ENVIRONMENT] ? 9999 : 0;

		if ($resultRecaptcha == 9999) {
			$this->response->code = 4;
			$this->response->title = lang('GEN_SYSTEM_NAME');
			$this->response->icon = lang('CONF_ICON_DANGER');
			$this->response->msg = lang('RESP_RECAPTCHA_VALIDATION_FAILED');
			$this->response->modalBtn['btn1']['link'] = 'inicio';
			$this->response->modalBtn['btn1']['action'] = 'redirect';
		}

		return $resultRecaptcha;
	}

		/**
	 * @info Método para consulta de administración de usuarios.
	 * @author Diego Acosta García
	 * @date Oct 2st, 2020
	 */
	public function callWs_usersManagement_User($dataRequest = FALSE)
	{
		log_message('INFO', 'NOVO User Model: usersManagement Method Initialized');

		$this->dataAccessLog->modulo = 'Usuario';
		$this->dataAccessLog->function = 'Obtener usuarios banorte';
		$this->dataAccessLog->operation = 'obtener usuarios banorte';
		$this->dataRequest->idOperation = 'integracionBnt';
		$this->dataRequestclassName = 'com.novo.objects.TOs.GestionUsuariosTO';
		$this->dataRequest->opcion = 'getUsers';
		$this->dataRequest->idEmpresa = $this->session->enterpriseInf->idFiscal;

		$response = $this->sendToService('callWs_usersManagement');

		switch ($this->isResponseRc)  {
			case 0:
				$this->response->code = 0;
				$data = $response->bean->users;

				for ($i=0; $i < count($data); $i++) {
					if ($data[$i]->tranTipoUsuario == 0) {
						$data[$i]->tranTipoUsuario = 'Administrador';
					}	else {
						$data[$i]->tranTipoUsuario = 'Operador';
					}
					$data[$i]->idEnterprise = $data[$i]->tranIdEmpresa;
					$data[$i]->idUser = $data[$i]->tranIdUsuario;
					$data[$i]->name = $data[$i]->tranNombreUsuario;
					$data[$i]->mail = $data[$i]->tranCorreo;
					$data[$i]->type = $data[$i]->tranTipoUsuario;
					$data[$i]->registered = $data[$i]->registed;
					unset($data[$i]->tranIdEmpresa);
					unset($data[$i]->tranIdUsuario);
					unset($data[$i]->tranNombreUsuario);
					unset($data[$i]->tranCorreo);
					unset($data[$i]->tranTipoUsuario);
					unset($data[$i]->registed);
					unset($data[$i]->tranIdUsuarioOperativo);
				}

				$this->response->data = $data	;
				break;
			case -150:
				$this->response->code = 0;
			break;
		}

		return $this->responseToTheView('callWs_usersManagement');
	}
		/**
	 * @info Método para consulta de permisos de usuarios.
	 * @author Diego Acosta García
	 * @date Oct 2st, 2020
	 */
	public function callWs_userPermissions_User($dataRequest)
	{
		log_message('INFO', 'NOVO User Model: userPermissions Method Initialized');

		$this->dataAccessLog->modulo = 'Usuario';
		$this->dataAccessLog->function = 'Obtener usuarios banorte';
		$this->dataAccessLog->operation = 'obtener usuarios banorte';

		$this->dataRequest->idOperation = 'gestionUsuarios';
		$this->dataRequest->opcion = 'obtenerFuncionesUsuario';
		$this->dataRequest->userName = $dataRequest->idUser;
		$this->session->set_flashdata('userDataPermissions', $dataRequest);

		$response = $this->sendToService('callWs_userPermissions');

		switch ($this->isResponseRc)  {
			case 0:

				$this->response->code = 0;
				$data = $response->bean->perfiles;


				foreach ($data as $key => $val) {
					$titles[$key] = $data[$key]->descripcion;
					$arrayList[$titles[$key]] = $data[$key]->modulos;
				}

				foreach ($titles as $key => $value) {
					foreach ($arrayList[$titles[$key]]  as $key1 => $value1) {
						$arrayList[$titles[$key]][$key1] = $arrayList[$titles[$key]][$key1]->funciones;
						foreach($arrayList[$titles[$key]][$key1] AS $key2 => $val2){
							if ($arrayList[$titles[$key]][$key1][$key2]->status == "A") {
								$arrayList[$titles[$key]][$key1][$key2]->status = "on";
							}else{
								$arrayList[$titles[$key]][$key1][$key2]->status = "off";
							}
						}
					}
				}

				$this->response->data = $arrayList;
				break;
		}

		return $this->responseToTheView('callWs_userPermissions');
	}

	/**
	 * @info Método para actualizar permisos de usuarios.
	 * @author Diego Acosta García
	 * @date Oct 5st, 2020
	 */
	public function callWs_updatePermissions_User($dataRequest)
	{
		log_message('INFO', 'NOVO User Model: updatePermissions Method Initialized');

		$this->dataAccessLog->modulo = 'Usuario';
		$this->dataAccessLog->function = 'Actualizar funciones usuario';
		$this->dataAccessLog->operation = 'Actualizar funciones usuario';

		$this->dataRequest->idOperation = 'gestionUsuarios';
		$this->dataRequest->opcion = 'actualizarFuncionesUsuario';
		$this->dataRequest->className = 'com.novo.objects.TOs.GestionUsuariosTO';
		$this->dataRequest->userName =$dataRequest->idUser;

		$userDataList =[];
		$userData['idUser'] = $dataRequest->idUser;
		$userData['nameUser'] = $dataRequest->fullName;
		$userData['mailUser'] = $dataRequest->email;
		$userData['typeUser'] = $dataRequest->typeUser;
		$userDataList = (object) $userData;
		$user = $dataRequest->idUser;
		$this->session->set_flashdata('userDataPermissions', $userDataList);

		unset($dataRequest->idUser);
		unset($dataRequest->fullName);
		unset($dataRequest->email);
		unset($dataRequest->typeUser);

		$i=0;
		$j=0;
		$functionsArray =[];

		foreach ($dataRequest as $key => $value) {
			if ($value == "off") {
				$objet[$i] = ['accodfuncion' => $key, 'status'=> 'I'];
			} else {
				$objet[$i] = ['accodfuncion' => $key, 'status'=> 'A'];
			}
			$i++;
			unset($objet[$key]);
		}

		foreach ($objet as $key => $value) {
			$functionsArray[$j] = $value;
			$j++;
		};

		$this->dataRequest->perfiles = [['idPerfil' => 'TODOS', 'modulos' => [      ['idModulo' => 'TODOS', 'funciones' => $functionsArray]]]];

		$response = $this->sendToService('callWs_updatePermissions');

		switch ($this->isResponseRc)   {
			case 0:
				$this->response->title = lang('GEN_MENU_USERS_MANAGEMENT');
				$this->response->icon =  lang('CONF_ICON_SUCCESS');
				$this->response->msg = novoLang(lang('RESP_SUCCESSFULL_UPDATE_PERMISSIONS'), $user);

				if ($this->userName == $user) {
					$this->response->modalBtn['btn1']['action'] = 'redirect';
				} else {
					$this->response->modalBtn['btn1']['link'] = 'permisos-usuario';
				}
				break;
		}
		return $this->responseToTheView('callWs_updatePermissions');
	}

	/**
	 * @info Método para habilitar usuario.
	 * @author Diego Acosta García
	 * @date Oct 5st, 2020
	 */
	public function callWs_enableUser_User($dataRequest)
	{
		log_message('INFO', 'NOVO User Model: enableUser Method Initialized');

		$this->dataAccessLog->modulo = 'Usuario';
		$this->dataAccessLog->function = 'Obtener usuarios banorte';
		$this->dataAccessLog->operation = 'obtener usuarios banorte';

		$this->dataRequest->idOperation = 'gestionUsuarios';
		$this->dataRequest->opcion = 'crearUsuario';
		$this->dataRequest->className = 'com.novo.objects.TOs.GestionUsuariosTO';
		$this->dataRequest->userName = $dataRequest->user;
		$this->dataRequest->idUsuario = $dataRequest->user;
		$name= explode(" ", $dataRequest->name);
		$this->dataRequest->nombre1 = $name[0];
		$this->dataRequest->nombre2 = '';
		$this->dataRequest->apellido1 = $name[1];
		if( $name[1] == NULL){
			$this->dataRequest->apellido1 = "";
		}
		$this->dataRequest->apellido2 = '';
		$this->dataRequest->clonarPermisos = 'true';
		$this->dataRequest->mail = $dataRequest->mail;;
		$this->dataRequest->empresa = $this->session->enterpriseInf->idFiscal;
		$this->dataRequest->usuarioPlantilla = $this->session->userName;

		$response = $this->sendToService('callWs_enableUser');

		switch ($this->isResponseRc)   {
			case 0:
				$this->response->code = 4;
				$this->response->title = lang('GEN_MENU_USERS_MANAGEMENT');
				$this->response->icon =  lang('CONF_ICON_SUCCESS');
				$this->response->msg = novoLang(lang('RESP_SUCCESSFULL_ENABLE_USER'), $dataRequest->user);
				$this->response->modalBtn['btn1']['action'] = 'destroy';
				break;
		}

		return $this->responseToTheView('callWs_enableUser');
	}
}

