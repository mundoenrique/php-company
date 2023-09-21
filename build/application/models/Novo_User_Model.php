<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @info Módelo para la información del usuario
 * @author J. Enrique Peñaloza Piñero
 * @date May 14th, 2019
 */
class Novo_User_Model extends NOVO_Model
{

	public function __construct()
	{
		parent::__construct();
		writeLog('INFO', 'User Model Class Initialized');
	}
	/**
	 * @info Método para el inicio de sesión
	 * @author J. Enrique Peñaloza Piñero
	 * @date May 14th, 2019
	 */
	public function callWs_SignIn_User($dataRequest)
	{
		writeLog('INFO', 'User Model: SignIn Method Initialized');

		$userName = manageString($dataRequest->userName, 'upper', 'none');
		$password = decryptData($dataRequest->userPass);
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

		if (IP_VERIFY) {
			$this->dataRequest->codigoOtp = [
				'tokenCliente' => $dataRequest->otpCode ?? '',
				'authToken' => $authToken
			];

			if (isset($dataRequest->saveIP)) {
				$this->dataRequest->guardaIp = $dataRequest->saveIP;
			}
		}

		if (lang('SETT_MAINTENANCE') === 'ON') {
			$this->isResponseRc = lang('SETT_MAINTENANCE_RC');
		} elseif (isset($dataRequest->otpCode) && $authToken === '') {
			$this->isResponseRc = 9998;
		} else {
			$this->isResponseRc = ACTIVE_RECAPTCHA ? $this->callWs_ValidateCaptcha_User($dataRequest) : 0;

			if ($this->isResponseRc === 0) {
				$response = $this->sendToWebServices('callWs_SignIn');
			}
		}

		if (lang('SETT_PASS_EXPIRED') === 'OFF' && ($this->isResponseRc === -2 || $this->isResponseRc === -185)) {
			$this->isResponseRc = 0;
		}

		$time = (object) [
			'customerTime' => (int) $dataRequest->currentTime,
			'serverTime' => (int) date("H")
		];

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$fullName = $response->usuario->primerNombre . ' ' . $response->usuario->primerApellido;
				$formatDate = $this->config->item('format_date');
				$formatTime = $this->config->item('format_time');
				$lastSession = date(
					"$formatDate $formatTime",
					strtotime(
						str_replace('/', '-', $response->usuario->fechaUltimaConexion)
					)
				);
				$userData = [
					'sessionId' => $response->logAccesoObject->sessionId,
					'logged' => TRUE,
					'userId' => $response->usuario->idUsuario,
					'userName' => $response->usuario->userName,
					'passWord' => lang('SETT_REMOTE_AUTH') === 'ON' ? $this->dataRequest->password : FALSE,
					'fullName' => manageString($fullName, 'lower', 'word'),
					'userType' => $response->usuario->ctipo,
					'groupCode' => $response->usuario->codigoGrupo,
					'lastSession' => $lastSession,
					'token' => $response->token,
					'time' => $time,
					'customerSess' => $this->config->item('customer'),
					'customerUri' => $this->config->item('customer_uri'),
					'clientAgent' => $this->agent->agent_string(),
					'autoLogin' => 'false',
					// Eliminar al finalizar la migración
					'idUsuario' => $response->usuario->idUsuario,
					'codigoGrupo' => $response->usuario->codigoGrupo,
					'pais' => $this->config->item('customer'),
					'nombreCompleto' => $fullName,
					'cl_addr' => $this->encrypt_decrypt->encryptWebServices($this->input->ip_address()),
					'logged_in' => TRUE
					// -----------------------------------
				];
				$this->session->set_userdata($userData);
				$this->response->data->link = base_url(lang('SETT_LINK_ENTERPRISES'));
				$this->response->keepModal = TRUE;
				break;
			case -2:
			case -185:
				$this->response->code = 0;
				$fullName = $response->usuario->primerNombre . ' ' . $response->usuario->primerApellido;
				$userData = [
					'sessionId' => $response->logAccesoObject->sessionId,
					'userId' => $response->usuario->idUsuario,
					'userName' => $response->usuario->userName,
					'fullName' => manageString($fullName, 'lower', 'word'),
					'groupCode' => $response->usuario->codigoGrupo,
					'token' => $response->token,
					'time' => $time,
					'customerSess' => $this->config->item('customer'),
					'customerUri' => $this->config->item('customer_uri'),
					'clientAgent' => $this->agent->agent_string(),
					// Eliminar al finalizar la migración
					'cl_addr' => $this->encrypt_decrypt->encryptWebServices($this->input->ip_address()),
					'codigoGrupo' => $response->usuario->codigoGrupo,
					// ----------------------------------
				];
				$this->session->set_userdata($userData);
				$nextWayFirstLogIn = lang('SETT_READ_TERMS') === 'ON' ? lang('SETT_LINK_TERMS') : lang('SETT_LINK_CHANGE_PASS');
				$this->response->data->link = base_url($nextWayFirstLogIn);
				$this->session->set_flashdata('changePassword', 'newUser');
				$this->session->set_flashdata('userType', $response->usuario->ctipo);

				if ($this->isResponseRc === -185) {
					$this->response->data->link = base_url(lang('SETT_LINK_CHANGE_PASS'));
					$this->session->set_flashdata('changePassword', 'expiredPass');
				}
				break;
			case -1:
				$this->response->code = 1;
				$this->response->msg = lang('USER_SIGNIN_INVALID_USER');
				$this->response->data->className = lang('SETT_VALID_INVALID_USER');
				$this->response->data->position = lang('SETT_VALID_POSITION');
				break;
			case -263:
				$this->response->code = 1;
				$this->response->msg = lang('USER_SIGNIN_WILL_BLOKED');
				$this->response->data->className = lang('SETT_VALID_INVALID_USER');
				$this->response->data->position = lang('SETT_VALID_POSITION');
				break;
			case -8:
				$this->response->code = 1;
				$this->response->msg = lang('USER_SIGNIN_SUSPENDED');
				$this->response->data->className = lang('SETT_VALID_INACTIVE_USER');
				$this->response->data->position = lang('SETT_VALID_POSITION');
				break;
			case -35:
				$this->response->code = 1;
				$this->response->msg = lang('USER_SIGNIN_BLOCKED');
				$this->response->data->className = lang('SETT_VALID_INACTIVE_USER');
				$this->response->data->position = lang('SETT_VALID_POSITION');
				break;
			case -424:
				$this->response->code = 2;
				$this->response->msg = novoLang(lang('GEN_LOGIN_IP_MSG'), $response->usuario->emailEnc);
				$this->response->labelInput = lang('GEN_LOGIN_IP_LABEL_INPUT');
				$this->response->assert = lang('GEN_LOGIN_IP_ASSERT');
				$this->response->modalBtn['btn1']['action'] = 'request';
				$this->response->modalBtn['btn2']['text'] = lang('GEN_BTN_CANCEL');
				$this->response->modalBtn['btn2']['action'] = 'destroy';
				$this->session->set_flashdata('authToken', json_decode($response->usuario->codigoOtp->access_token));
				break;
			case 9996:
				$this->response->code = 3;
				$this->response->icon = '';
				$this->response->title = lang('GEN_SYSTEM_NAME');
				$img = $this->asset->insertFile('nueva-expresion-monetaria.png', 'images', $this->customerFiles);
				// $this->response->msg = novolang(lang('GEN_MSG_MAINT_NOTIF'), $img);
				$this->response->msg = lang('GEN_MAINTENANCE_MSG');
				$this->response->data->img = $img;
				$this->response->modalBtn['btn1']['action'] = 'destroy';
				$this->response->modalBtn['btn1']['text'] = lang('GEN_BTN_ACCEPT');
				break;
			case -28:
				$this->response->msg = lang('GEN_INCORRECTLY_CLOSED');
				$this->response->data->action = 'session-close';
				$this->response->modalBtn['btn1']['action'] = 'request';
				$this->response->modalBtn['btn2']['action'] = 'destroy';
				break;
			case -229:
				$this->response->icon = lang('SETT_ICON_INFO');
				$this->response->msg = lang('USER_SIGNIN_OLD_APP');
				$this->response->modalBtn['btn1']['action'] = 'destroy';
				break;
			case -262:
				$this->response->icon = lang('SETT_ICON_INFO');
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

				$this->response->icon = lang('SETT_ICON_INFO');
				$this->response->title = lang('GEN_SYSTEM_NAME');
				$this->response->msg = lang('GEN_MAINTENANCE_MSG');
				$this->response->modalBtn['btn1']['action'] = 'destroy';
				$this->response->modalBtn['btn1']['text'] = lang('GEN_BTN_ACCEPT');
				break;
			case 9998:

				$this->response->icon = lang('SETT_ICON_INFO');
				$this->response->title = lang('GEN_SYSTEM_NAME');
				$this->response->msg = lang('USER_EXPIRE_TIME');
				$this->response->modalBtn['btn1']['action'] = 'destroy';
				break;
		}

		return $this->responseToTheView('callWs_SignIn');
	}
	/**
	 * @info Método para el inicio de sesión único
	 * @author J. Enrique Peñaloza Piñero
	 * @date May 14th, 2019
	 */
	public function callWs_SingleSignon_User($dataRequest)
	{
		writeLog('INFO', 'User Model: SingleSignon Method Initialized');

		$this->dataAccessLog->modulo = 'Usuario';
		$this->dataAccessLog->function = 'Ingreso al sistema';
		$this->dataAccessLog->operation = 'Inicio de sesión único';
		$this->dataAccessLog->userName = $this->customer;

		$this->dataRequest->idOperation = lang('SETT_SINGLE_SIGN_ON');
		$this->dataRequest->className = 'com.novo.objects.TOs.RequestTO';

		switch ($this->customer) {
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

		$response = $this->sendToWebServices('callWs_SingleSignon');

		if (lang('SETT_PASS_EXPIRED') === 'OFF' && ($this->isResponseRc === -2 || $this->isResponseRc === -185)) {
			$this->isResponseRc = 0;
		}

		$this->response->code = 0;

		switch ($this->isResponseRc) {
			case 0:
				$fullName = $response->usuario->primerNombre . ' ' . $response->usuario->primerApellido;
				$formatDate = $this->config->item('format_date');
				$formatTime = $this->config->item('format_time');
				$lastSession = date(
					"$formatDate $formatTime",
					strtotime(
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
					'fullName' => manageString($fullName, 'lower', 'word'),
					'codigoGrupo' => $response->usuario->codigoGrupo,
					'lastSession' => $lastSession,
					'token' => $response->token,
					'time' => $time,
					'cl_addr' => $this->encrypt_decrypt->encryptWebServices($this->input->ip_address()),
					'customerSess' => $this->config->item('customer'),
					'customerUri' => $this->config->item('customer_uri'),
					'clientAgent' => $this->agent->agent_string(),
					'autoLogin' => 'true',
					'enterpriseInf' => [
						'thirdApp' => $dataRequest->Canal ?? ''
					]
				];
				$this->session->set_userdata($userData);
				$this->response->code = 0;
				$this->response->data = base_url(lang('SETT_LINK_ENTERPRISES'));
				break;
			case -28:
				$userData = [
					'userId' => $response->usuario->idUsuario,
					'userName' => $response->usuario->userName,
					'codigoGrupo' => $response->usuario->codigoGrupo,
					'customerSess' => $this->config->item('customer'),
					'clientAgent' => $this->agent->agent_string()
				];
				$this->session->set_userdata($userData);
				$this->session->set_flashdata('unauthorized', lang('GEN_SESSION_DUPLICATE'));
				$this->response->data = base_url(lang('SETT_LINK_SIGNOUT') . lang('SETT_LINK_SIGNOUT_END'));
				break;
			default:
				$this->response->data = base_url('ingresar/' . lang('SETT_LINK_SIGNOUT_END'));
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
		writeLog('INFO', 'User Model: RecoverPass Method Initialized');

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
			$response = $this->sendToWebServices('callWs_RecoverPass');
		}

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->msg = novoLang(lang('GEN_TEMP_PASS'), [$this->dataRequest->userName, $maskMail]);
				$this->response->icon = lang('SETT_ICON_SUCCESS');
				$this->response->modalBtn['btn1']['text'] = lang('GEN_BTN_CONTINUE');
				$this->response->modalBtn['btn1']['link'] = lang('SETT_LINK_SIGNIN');
				$this->response->modalBtn['btn1']['action'] = 'redirect';
				break;
			case -6:
				$this->response->code = 1;
				$this->response->msg = novoLang(lang('GEN_COMPANNY_NOT_ASSIGNED'), $this->dataRequest->userName);
				break;
			case -150:
				$this->response->code = 1;
				$this->response->msg = novoLang(lang('GEN_FISCAL_REGISTRY_NO_FOUND'), [lang('GEN_FISCAL_REGISTRY_OF'), lang('GEN_FISCAL_REGISTRY'), lang('GEN_FISCAL_REGISTRY_OF_ENTERPRISE')]);
				break;
			case -159:
				$this->response->code = 1;
				$this->response->msg = novoLang(lang('GEN_EMAIL_NO_FOUND'), $maskMail);
				break;
			case -173:
				$this->response->code = 1;
				$this->response->msg = lang('GEN_EMAIL_NO_SENT');
				break;
			case -205:
				$this->response->code = 1;
				$this->response->msg = lang('GEN_UNREGISTERED_USER');
				$this->response->msg .= novoLang(lang('GEN_SUPPORT'), [lang('GEN_SUPPORT_MAIL'), lang('GEN_SUPPORT_TELF')]);
				break;
		}

		if ($this->isResponseRc != 0 && $this->response->code == 1) {
			$this->response->title = lang('GEN_RECOVER_PASS_TITLE');
			$this->response->icon = lang('SETT_ICON_INFO');
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
		writeLog('INFO', 'User Model: RecoverAccess Method Initialized');

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
			$response = $this->sendToWebServices('callWs_AccessRecover');
		}

		switch ($this->isResponseRc) {
			case 200:
				$this->session->set_flashdata('authToken', $response->bean->TokenTO->authToken);
				$this->session->set_flashdata('userName', $response->logAccesoObject->userName);
				$this->response->code = 0;
				$this->response->msg = lang('GEN_OTP');
				$this->response->icon = lang('SETT_ICON_SUCCESS');
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

		if ($this->isResponseRc != 0 && $map == 1) {
			$this->response->title = lang('GEN_RECOVER_PASS_TITLE');
			$this->response->icon = lang('SETT_ICON_INFO');
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
		writeLog('INFO', 'User Model: ValidateOtp Method Initialized');

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

		if ($this->isResponseRc === 0) {
			if ($this->session->flashdata('authToken') != NULL) {
				$response = $this->sendToWebServices('callWs_ValidateOtp');
			} else {
				$this->isResponseRc = 998;
			}
		}

		switch ($this->isResponseRc) {
			case 0:
				$this->response->msg = novoLang(lang('GEN_SEND_ACCESS'), [$maskMail]);
				$this->response->icon = lang('SETT_ICON_SUCCESS');
				$this->response->modalBtn['btn1']['link'] = lang('SETT_LINK_SIGNIN');
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

		if ($this->isResponseRc != 0 && $map == 1) {
			$this->response->title = lang('GEN_RECOVER_PASS_TITLE');
			$this->response->icon = lang('SETT_ICON_INFO');
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
		writeLog('INFO', 'User Model: ChangePassword Method Initialized');

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
		$this->sendToWebServices('CallWs_ChangePassword');
		$code = 0;

		switch ($this->isResponseRc) {
			case 0:
				if (!$this->session->has_userdata('logged')) {
					$this->callWs_FinishSession_User();
				}
				$this->response->code = 4;
				$goLogin = $this->session->has_userdata('logged') ? '' : lang('GEN_PASSWORD_LOGIN');
				$this->response->msg = novoLang(lang('GEN_PASSWORD_CHANGED'), $goLogin);
				$this->response->icon = lang('SETT_ICON_SUCCESS');
				$this->response->modalBtn['btn1']['text'] = lang('GEN_BTN_CONTINUE');
				$this->response->modalBtn['btn1']['link']  = lang('SETT_LINK_SIGNIN');
				$this->response->modalBtn['btn1']['action'] = $this->session->has_userdata('logged') ? 'destroy' :  'redirect';
				break;
			case -4:
				$code = 1;
				$this->response->msg = lang('GEN_PASSWORD_USED');
				break;
			case -1:
			case -22:
				$code = 1;
				$this->response->msg = lang('GEN_PASSWORD_INCORRECT');
				break;
		}

		if ($this->isResponseRc != 0 && $code == 1) {
			$this->session->set_flashdata('changePassword', $changePassType);
			$this->session->set_flashdata('userType', $this->session->flashdata('userType'));

			$this->response->title = lang('GEN_PASSWORD_CHANGE_TITLE');
			$this->response->icon = lang('SETT_ICON_WARNING');
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
		writeLog('INFO', 'User Model: KeepSession Method Initialized');
		$response = new stdClass();
		$response->rc =  0;
		$this->makeAnswer($response, 'callWs_GetBranchOffices');
		$this->response->code = 0;

		return $this->responseToTheView('callWs_KeepSession');
	}
	/**
	 * @info Método para el cambiar el idioma de la aplicaición
	 * @author J. Enrique Peñaloza Piñero
	 * @date May 16th, 2021
	 */
	public function callWs_ChangeLanguage_User($dataRequest)
	{
		writeLog('INFO', 'User Model: ChangeLanguage Method Initialized');

		$search = [$this->customerUri . '/', '/' . lang('GEN_BEFORE_COD_LANG')];
		$replace = ['/', '/' . lang('GEN_AFTER_COD_LANG')];
		$path = str_replace($search, $replace, $dataRequest->path);
		$response = new stdClass();
		$response->responseCode =  0;
		$this->makeAnswer($response, 'callWs_ChangeLanguage');

		languageCookie($dataRequest->lang);

		$this->response->code = 0;
		$this->response->msg = 'Idioma cambiado a ' . lang('GEN_AFTER_LANG');
		$this->response->data->link = base_url($path);

		return $this->responseToTheView('ChangeLanguage');
	}
	/**
	 * @info Método para el cierre de sesión
	 * @author J. Enrique Peñaloza Piñero
	 * @date May 1st, 2019
	 */
	public function callWs_FinishSession_User($dataRequest = FALSE)
	{
		writeLog('INFO', 'User Model: FinishSession Method Initialized');

		$userName = $dataRequest ? mb_strtoupper($dataRequest->userName) : $this->userName;

		$this->dataAccessLog->userName = $userName;
		$this->dataAccessLog->modulo = 'Usuario';
		$this->dataAccessLog->function = 'Salir del sistema';
		$this->dataAccessLog->operation = 'Cerrar sesion';

		$this->dataRequest->idOperation = 'desconectarUsuario';
		$this->dataRequest->className = 'com.novo.objects.TOs.UsuarioTO';
		$this->dataRequest->idUsuario = $userName;
		$this->dataRequest->codigoGrupo = $this->session->groupCode;

		$this->sendToWebServices('callWs_FinishSession');

		$this->response->code = 0;
		$this->response->msg = lang('GEN_BTN_ACCEPT');
		clearSessionsVars();

		return $this->responseToTheView('callWs_FinishSession');
	}
	/**
	 * @info Método para consulta de administración de usuarios.
	 * @author Diego Acosta García
	 * @date Oct 2st, 2020
	 */
	public function callWs_usersManagement_User($dataRequest = FALSE)
	{
		writeLog('INFO', 'User Model: usersManagement Method Initialized');

		$this->dataAccessLog->modulo = 'Usuario';
		$this->dataAccessLog->function = 'Obtener usuarios banorte';
		$this->dataAccessLog->operation = 'obtener usuarios banorte';
		$this->dataRequest->idOperation = 'integracionBnt';
		$this->dataRequest->className = 'com.novo.objects.TOs.GestionUsuariosTO';
		$this->dataRequest->opcion = 'getUsers';
		$this->dataRequest->idEmpresa = $this->session->enterpriseInf->idFiscal;

		$response = $this->sendToWebServices('callWs_usersManagement');

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$data = $response->bean->users;

				for ($i = 0; $i < count($data); $i++) {
					if ($data[$i]->tranTipoUsuario == 0) {
						$data[$i]->tranTipoUsuario = 'Administrador';
					} else {
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

				$this->response->data = $data;
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
		writeLog('INFO', 'User Model: userPermissions Method Initialized');

		$this->dataAccessLog->modulo = 'Usuario';
		$this->dataAccessLog->function = 'Obtener usuarios banorte';
		$this->dataAccessLog->operation = 'obtener usuarios banorte';

		$this->dataRequest->idOperation = 'gestionUsuarios';
		$this->dataRequest->opcion = 'obtenerFuncionesUsuario';
		$this->dataRequest->userName = $dataRequest->idUser;
		$this->session->set_flashdata('userDataPermissions', $dataRequest);

		$response = $this->sendToWebServices('callWs_userPermissions');

		switch ($this->isResponseRc) {
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
						foreach ($arrayList[$titles[$key]][$key1] as $key2 => $val2) {
							if ($arrayList[$titles[$key]][$key1][$key2]->status == "A") {
								$arrayList[$titles[$key]][$key1][$key2]->status = "on";
							} else {
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
		writeLog('INFO', 'User Model: updatePermissions Method Initialized');

		$this->dataAccessLog->modulo = 'Usuario';
		$this->dataAccessLog->function = 'Actualizar funciones usuario';
		$this->dataAccessLog->operation = 'Actualizar funciones usuario';

		$this->dataRequest->idOperation = 'gestionUsuarios';
		$this->dataRequest->opcion = 'actualizarFuncionesUsuario';
		$this->dataRequest->className = 'com.novo.objects.TOs.GestionUsuariosTO';
		$this->dataRequest->userName = $dataRequest->idUser;

		$userDataList = [];
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

		$i = 0;
		$j = 0;
		$functionsArray = [];

		foreach ($dataRequest as $key => $value) {
			if ($value == "off") {
				$objet[$i] = ['accodfuncion' => $key, 'status' => 'I'];
			} else {
				$objet[$i] = ['accodfuncion' => $key, 'status' => 'A'];
			}
			$i++;
			unset($objet[$key]);
		}

		foreach ($objet as $key => $value) {
			$functionsArray[$j] = $value;
			$j++;
		};

		$this->dataRequest->perfiles = [['idPerfil' => 'TODOS', 'modulos' => [['idModulo' => 'TODOS', 'funciones' => $functionsArray]]]];

		$response = $this->sendToWebServices('callWs_updatePermissions');

		switch ($this->isResponseRc) {
			case 0:
				$this->response->title = lang('GEN_MENU_USERS_MANAGEMENT');
				$this->response->icon =  lang('SETT_ICON_SUCCESS');
				$this->response->msg = novoLang(lang('GEN_SUCCESSFULL_UPDATE_PERMISSIONS'), $user);

				if ($this->userName == $user) {
					$this->response->modalBtn['btn1']['action'] = 'redirect';
				} else {
					$this->response->modalBtn['btn1']['link'] = lang('SETT_LINK_USERS_PERMISSIONS');
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
		writeLog('INFO', 'User Model: enableUser Method Initialized');

		$this->dataAccessLog->modulo = 'Usuario';
		$this->dataAccessLog->function = 'Obtener usuarios banorte';
		$this->dataAccessLog->operation = 'obtener usuarios banorte';

		$this->dataRequest->idOperation = 'gestionUsuarios';
		$this->dataRequest->opcion = 'crearUsuario';
		$this->dataRequest->className = 'com.novo.objects.TOs.GestionUsuariosTO';
		$this->dataRequest->userName = $dataRequest->user;
		$this->dataRequest->idUsuario = $dataRequest->user;
		$name = explode(" ", $dataRequest->name);
		$this->dataRequest->nombre1 = $name[0];
		$this->dataRequest->nombre2 = '';
		$this->dataRequest->apellido1 = $name[1];
		if ($name[1] == NULL) {
			$this->dataRequest->apellido1 = "";
		}
		$this->dataRequest->apellido2 = '';
		$this->dataRequest->clonarPermisos = 'true';
		$this->dataRequest->mail = $dataRequest->mail;;
		$this->dataRequest->empresa = $this->session->enterpriseInf->idFiscal;
		$this->dataRequest->usuarioPlantilla = $this->session->userName;

		$response = $this->sendToWebServices('callWs_enableUser');

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 4;
				$this->response->title = lang('GEN_MENU_USERS_MANAGEMENT');
				$this->response->icon =  lang('SETT_ICON_SUCCESS');
				$this->response->msg = novoLang(lang('GEN_SUCCESSFULL_ENABLE_USER'), $dataRequest->user);
				$this->response->modalBtn['btn1']['action'] = 'destroy';
				break;
		}

		return $this->responseToTheView('callWs_enableUser');
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
		writeLog('INFO', 'User Model: validateCaptcha Method Initialized');

		$this->load->library('recaptcha');
		$result = $this->recaptcha->verifyResponse($dataRequest->token);

		writeLog('DEBUG', 'RECAPTCH RESPONSE: ' . json_encode($result, JSON_UNESCAPED_UNICODE));

		$resultRecaptcha = $result->score < lang('SETT_SCORE_CAPTCHA')[ENVIRONMENT] ? 9999 : 0;

		if ($resultRecaptcha == 9999) {
			$this->response->code = 4;
			$this->response->title = lang('GEN_SYSTEM_NAME');
			$this->response->icon = lang('SETT_ICON_DANGER');
			$this->response->msg = lang('GEN_RECAPTCHA_VALIDATION_FAILED');
			$this->response->modalBtn['btn1']['link'] = lang('SETT_LINK_SIGNIN');
			$this->response->modalBtn['btn1']['action'] = 'redirect';
		}

		return $resultRecaptcha;
	}
}
