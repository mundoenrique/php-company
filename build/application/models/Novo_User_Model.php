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
	public function callWs_Login_User($dataRequest)
	{
		log_message('INFO', 'NOVO User Model: Login Method Initialized');

		$this->dataAccessLog->modulo = 'Usuario';
		$this->dataAccessLog->function = 'Ingreso al sistema';
		$this->dataAccessLog->operation = 'Iniciar sesion';

		$userName = mb_strtoupper($dataRequest->user);
		$this->dataAccessLog->userName = $userName;

		$password = json_decode(base64_decode($dataRequest->pass));
		$password = $this->cryptography->decrypt(
			base64_decode($password->plot),
			utf8_encode($password->password)
		);
		$authToken = $this->session->flashdata('authToken') ? $this->session->flashdata('authToken') : '';
		$authToken_str=str_replace('"','', $authToken);

		$this->dataRequest->idOperation = 'loginFull';
		$this->dataRequest->className = 'com.novo.objects.TOs.UsuarioTO';
		$this->dataRequest->userName = $userName;
		$this->dataRequest->password = md5($password);
		$this->dataRequest->ctipo = $dataRequest->active;

		if (IP_VERIFY == 'ON') {
			$this->dataRequest->codigoOtp =[
				'tokenCliente' => $dataRequest->codeOTP != '' ? $dataRequest->codeOTP : '',
				'authToken' => $authToken_str
			];
			$this->dataRequest->guardaIp = $dataRequest->saveIP !='' ? true : false;
		}

		if($dataRequest->codeOTP != '' && $authToken == '') {
			$this->isResponseRc = 998;
		} else {
			if(ACTIVE_RECAPTCHA) {
				$this->isResponseRc = $this->callWs_ValidateCaptcha_User($dataRequest);

				if ($this->isResponseRc === 0) {
					$response = $this->sendToService('callWs_Login');
				}
			} else {
				$response = $this->sendToService('callWs_Login');
			}

			if(lang('CONFIG_PASS_EXPIRED') == 'OFF' && ($this->isResponseRc == -2 || $this->isResponseRc == -185)) {
				$this->isResponseRc = 0;
			}

			$time = (object) [
				'customerTime' => (int) $dataRequest->currentTime,
				'serverTime' => (int) date("H")
			];
		}

		switch($this->isResponseRc) {
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
				$userData = [
					'sessionId' => $response->logAccesoObject->sessionId,
					'logged' => TRUE,
					'userId' => $response->usuario->idUsuario,
					'userName' => $response->usuario->userName,
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
				$this->response->code = 0;
				$this->response->data = base_url(lang('GEN_ENTERPRISE_LIST'));
				$this->response->modal = TRUE;
			break;
			case -2:
			case -185:
				$fullName = mb_strtolower($response->usuario->primerNombre.' '.$response->usuario->primerApellido);
				$userData = [
					'sessionId' => $response->logAccesoObject->sessionId,
					'userId' => $response->usuario->idUsuario,
					'userName' => $response->usuario->userName,
					'fullName' => ucwords(mb_strtolower($fullName)),
					'codigoGrupo' => $response->usuario->codigoGrupo,
					'token' => $response->token,
					'time' => $time,
					'cl_addr' => $this->encrypt_connect->encode($this->input->ip_address(), $dataRequest->user, 'REMOTE_ADDR'),
					'countrySess' => $this->config->item('country'),
					'countryUri' => $this->config->item('country-uri'),
					'clientAgent' => $this->agent->agent_string()
				];
				$this->session->set_userdata($userData);
				$this->response->code = 0;
				$this->response->data = base_url('inf-condiciones');
				$this->session->set_flashdata('changePassword', 'newUser');
				$this->session->set_flashdata('userType', $response->usuario->ctipo);

				if($this->isResponseRc === -185) {
					$this->response->data = base_url('cambiar-clave');
					$this->session->set_flashdata('changePassword', 'expiredPass');
				}
			break;
			case -1:
			case -263:
				$this->response->code = 1;
				$this->response->msg = lang('RESP_INVALID_USER');
				$this->response->className = lang('CONF_VALID_INVALID_USER');
				$this->response->position = lang('CONF_VALID_POSITION');
			break;
			case -8:
			case -35:
				$this->response->code = 1;
				$this->response->msg = lang('RESP_SUSPENDED_USER');
				$this->response->className = lang('CONF_VALID_INACTIVE_USER');
				$this->response->position = lang('CONF_VALID_POSITION');
			break;
			case -229:
				$this->response->code = 3;
				$this->response->msg = lang('RESP_OLD_USER');
			break;
			case -262:
				$this->response->code = 3;
				$this->response->msg = lang('RESP_NO_PERMISSIONS');
				$this->response->icon = lang('CONF_ICON_INFO');
				$this->response->data = [
					'btn1'=> [
						'action'=> 'close'
					]
				];
			break;
			case -28:
				$this->response->code = 3;
				$this->response->msg = lang('RESP_INCORRECTLY_CLOSED');
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->data = [
					'btn1'=> [
						'link'=> [
							'who'=> 'User',
							'where'=> 'FinishSession'
						],
						'action'=> 'logout'
					]
				];
			break;
			case -424:
				$this->response->code = 2;
				$this->response->ipInvalid = TRUE;
				$this->response->assert = lang('GEN_LOGIN_IP_ASSERT');
				$this->response->labelInput = lang('GEN_LOGIN_IP_LABEL_INPUT');
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->email = $response->usuario->emailEnc;
				$this->response->msg = novoLang(lang('GEN_LOGIN_IP_MSG'), $this->response->email);
				$this->response->data = [
					'btn1'=> [
						'text'=> lang('GEN_BTN_ACCEPT'),
						'link'=> false,
						'action'=> 'none'
					],
					'btn2'=> [
						'text'=> lang('GEN_BTN_CANCEL'),
						'link'=> false,
						'action'=> 'close'
					]
				];
				$this->session->set_flashdata('authToken',$response->usuario->codigoOtp->access_token);
			break;
			case -286:
					$this->response->code = 4;
					$this->response->msg = lang('GEN_RESP_CODE_INVALID');
					$this->response->icon = lang('CONF_ICON_WARNING');
					$this->response->data['btn1'] = [
						'text' => lang('GEN_BTN_ACCEPT'),
						'action' => 'close'
					];
			break;
			case -287:
			case -288:
				$this->response->code = 4;
				$this->response->msg = lang('GEN_RESP_CODE_OTP_INVALID');
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->data['btn1'] = [
					'text' => lang('GEN_BTN_ACCEPT'),
					'action' => 'close'
				];
			break;
			case 998:
				$this->response->code = 4;
				$this->response->msg = lang('SESSION_EXPIRE_TIME');
				$this->response->title = lang('GEN_RECOVER_PASS_TITLE');
				$this->response->icon = lang('CONF_ICON_INFO');
				$this->response->data = [
					'btn1'=> [
						'action'=> 'close'
					]
				];
			break;
			case 9999:
				$this->response->code = 3;
				$this->response->title = lang('GEN_SYSTEM_NAME');
				$this->response->icon = lang('CONF_ICON_DANGER');
				$this->response->msg = lang('RESP_RECAPTCHA_VALIDATION_FAILED');
				$this->response->data = [
					'btn1'=> [
						'text'=> lang('GEN_BTN_ACCEPT'),
						'link'=> 'inicio',
						'action'=> 'redirect'
					]
				];
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
		$this->token = $dataRequest->sessionId ?? $dataRequest->clave;

		switch ($this->country) {
			case 'Bdb':
				$this->token = $dataRequest->sessionId;
			break;
			case 'Mx-Bn':
				$this->dataRequest->userName = '';
				$this->dataRequest->password = '';
				$this->dataRequest->ctipo = $dataRequest->canal;
				$this->dataRequest->codigoOtp = [
					'tokenCliente' => $dataRequest->ip ?? $this->input->ip_address(),
					'authToken' => $dataRequest->IdServicio,
				];
				$this->dataRequest->guardaIp = FALSE;
				$this->token = $dataRequest->clave;
			break;
		}

		$response = $this->sendToService('callWs_SingleSignon');
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
					'thirdPartyChannel' => $dataRequest->canal ?? ''
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
		$response = $this->sendToService('callWs_RecoverPass');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->msg = novoLang(lang('RESP_TEMP_PASS'), [$this->dataRequest->userName, $maskMail]);
				$this->response->icon = lang('CONF_ICON_SUCCESS');
				$this->response->data = [
					'btn1'=> [
						'text'=> lang('GEN_BTN_CONTINUE'),
						'link'=> 'inicio',
						'action'=> 'redirect'
					]
				];
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
			$this->response->data = [
				'btn1'=> [
					'action'=> 'close'
				]
			];
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

		$response = $this->sendToService('callWs_RecoverAccess');

		switch($this->isResponseRc) {
			case 200:
				$this->session->set_flashdata('authToken', $response->bean->TokenTO->authToken);
				$this->session->set_flashdata('userName', $response->logAccesoObject->userName);
				$this->response->code = 0;
				$this->response->msg = lang('GEN_OTP');
				$this->response->icon = lang('CONF_ICON_SUCCESS');
				$this->response->data = [
					'btn1'=> [
						'text'=> lang('GEN_BTN_ACCEPT'),
						'action'=> 'none'
					]
				];
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
			$this->response->data = [
				'btn1'=> [
					'action'=> 'close'
				]
			];
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

		if ($this->session->flashdata('authToken') != NULL) {
			$response = $this->sendToService('callWs_ValidateOtp');
		} else {
			$this->isResponseRc = 998;
		}

		switch($this->isResponseRc) {
			case 0:
				$this->response->msg = novoLang(lang('GEN_SEND_ACCESS'), [$maskMail]);
				$this->response->icon = lang('CONF_ICON_SUCCESS');
				$this->response->data = [
					'btn1'=> [
						'text'=> lang('GEN_BTN_ACCEPT'),
						'link'=> 'inicio',
						'action'=> 'redirect'
					]
				];
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
				$this->response->msg = lang('SESSION_EXPIRE_TIME');
			break;
		}

		if($this->isResponseRc != 0 && $map == 1) {
			$this->response->title = lang('GEN_RECOVER_PASS_TITLE');
			$this->response->icon = lang('CONF_ICON_INFO');
			$this->response->data = [
				'btn1'=> [
					'action'=> 'close'
				]
			];
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

		$current = json_decode(base64_decode($dataRequest->currentPass));
		$current = $this->cryptography->decrypt(
			base64_decode($current->plot),
			utf8_encode($current->password)
		);
		$new = json_decode(base64_decode($dataRequest->newPass));
		$new = $this->cryptography->decrypt(
			base64_decode($new->plot),
			utf8_encode($new->password)
		);

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
				$this->response->data = [
					'btn1'=> [
						'text'=> lang('GEN_BTN_CONTINUE'),
						'link'=> 'inicio',
						'action'=> $this->session->has_userdata('logged') ? 'close' :  'redirect'
					]
				];
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
			$this->response->data['btn1']['action'] = 'close';
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

		$userName = $dataRequest ? mb_strtoupper($dataRequest->user) : $this->userName;

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
	 */
	public function callWs_ValidateCaptcha_User($dataRequest)
	{
		log_message('INFO', 'NOVO User Model: validateCaptcha Method Initialized');

		$this->load->library('recaptcha');

		$result = $this->recaptcha->verifyResponse($dataRequest->token);
		$logMessage = 'NOVO ['.$dataRequest->user.'] RESPONSE: recaptcha País: "' .$this->config->item('country');
		$logMessage.= '", Score: "' . $result["score"] .'", Hostname: "'. $result["hostname"].'"';

		log_message('DEBUG', $logMessage);

		return $result["score"] <= lang('CONF_SCORE_CAPTCHA')[ENVIRONMENT] ? 9999 : 0;
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
		// $this->dataRequest->idEmpresa = '1511440';

		$response = $this->sendToService('callWs_usersManagement');

		//CABLE FOR SIMULATE THE SERVICE RESPONSE
		// $response = '{"rc":0,"msg":"Proceso OK","bean":"{\"users\":[{\"tranIdEmpresa\":1511440,\"tranIdUsuario\":\"DGONZALEZ1\",\"tranNumeroCliente\":52174189,\"tranIdUsuarioOperativo\":\"\",\"tranNombreUsuario\":\"Deiby Gonzalez\",\"tranCorreo\":\"pruebas@hotmail.com\",\"tranTipoUsuario\":0,\"registed\":false}, {\"tranIdEmpresa\":2622551,\"tranIdUsuario\":4238588,\"tranNumeroCliente\":63285298,\"tranIdUsuarioOperativo\":\"\",\"tranNombreUsuario\":\"Alberto López\",\"tranCorreo\":\"pruebas1@hotmail.com\",\"tranTipoUsuario\":1,\"registed\":true}]}"}';
		// $this->isResponseRc = 0;

		switch($this->isResponseRc)  {
			case 0:
				$this->response->code = 0;
				$data = $response->bean->users;
				// $data = (json_decode(json_decode($response)->bean)->users);
				$this->response->data = $data;
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
		$this->dataRequest->userName = $dataRequest;
		// $this->dataRequest->userName = 'DGONZALEZ1';

		$response = $this->sendToService('callWs_userPermissions');

		//CABLE FOR SIMULATE THE SERVICE RESPONSE
		// $cableArray = '{"rc":0,"msg":"Proceso OK","bean":"{\"perfiles\":[{\"idPerfil\":\"CONSUL\",\"descripcion\":\"CONSULTAS\",\"status\":\"A\",\"modulos\":[{\"idModulo\":\"TEBORS\",\"status\":\"A\",\"funciones\":[{\"accodfuncion\":\"TEBANU\",\"acnomfuncion\":\"ANULAR ORDEN DE SERVICIO\",\"status\":\"A\"},{\"accodfuncion\":\"TEBCOS\",\"acnomfuncion\":\"CONSULTAR ORDEN DE SERVICIO\",\"status\":\"A\"},{\"accodfuncion\":\"TEBCOS\",\"acnomfuncion\":\"CONSULTAR ORDEN DE SERVICIO\",\"status\":\"A\"},{\"accodfuncion\":\"TEBPGO\",\"acnomfuncion\":\"PAGAR ORDEN DE SERVICIO\",\"status\":\"I\"}],\"rc\":0}]},{\"idPerfil\":\"GESLOT\",\"descripcion\":\"LOTES\",\"status\":\"A\",\"modulos\":[{\"idModulo\":\"TEBAUT\",\"status\":\"A\",\"funciones\":[{\"accodfuncion\":\"TEBELI\",\"acnomfuncion\":\"ELIMINACION DE LOTE\",\"status\":\"A\"},{\"accodfuncion\":\"TEBELI\",\"acnomfuncion\":\"ELIMINACION DE LOTE\",\"status\":\"A\"}],\"rc\":0},{\"idModulo\":\"TEBCAR\",\"status\":\"A\",\"funciones\":[{\"accodfuncion\":\"TEBCON\",\"acnomfuncion\":\"CONFIRMACION DE LOTE\",\"status\":\"A\"},{\"accodfuncion\":\"TEBCON\",\"acnomfuncion\":\"CONFIRMACION DE LOTE\",\"status\":\"A\"},{\"accodfuncion\":\"TEBELC\",\"acnomfuncion\":\"ELIMINACION DE LOTE POR CONFIRMAR\",\"status\":\"A\"},{\"accodfuncion\":\"TEBELC\",\"acnomfuncion\":\"ELIMINACION DE LOTE POR CONFIRMAR\",\"status\":\"A\"}],\"rc\":0},{\"idModulo\":\"TICARG\",\"status\":\"A\",\"funciones\":[{\"accodfuncion\":\"TICREA\",\"acnomfuncion\":\"GENERACIÓN DE LOTE\",\"status\":\"A\"},{\"accodfuncion\":\"TICREA\",\"acnomfuncion\":\"GENERACIÓN DE LOTE\",\"status\":\"A\"}],\"rc\":0},{\"idModulo\":\"TIINVN\",\"status\":\"A\",\"funciones\":[{\"accodfuncion\":\"TIREPO\",\"acnomfuncion\":\"REPORTE DE INNOMINADAS\",\"status\":\"A\"},{\"accodfuncion\":\"TIREPO\",\"acnomfuncion\":\"REPORTE DE INNOMINADAS\",\"status\":\"A\"}],\"rc\":0}]},{\"idPerfil\":\"GESREP\",\"descripcion\":\"REPORTES\",\"status\":\"A\",\"modulos\":[{\"idModulo\":\"REPCAT\",\"status\":\"A\",\"funciones\":[{\"accodfuncion\":\"REPCAT\",\"acnomfuncion\":\"REPORTES GASTOS POR CATEGORIAS\",\"status\":\"I\"}],\"rc\":0},{\"idModulo\":\"REPCON\",\"status\":\"A\",\"funciones\":[{\"accodfuncion\":\"REPCON\",\"acnomfuncion\":\"REPORTE CUENTA CONCENTRADORA\",\"status\":\"A\"},{\"accodfuncion\":\"REPCON\",\"acnomfuncion\":\"REPORTE CUENTA CONCENTRADORA\",\"status\":\"A\"},{\"accodfuncion\":\"TEBCOD\",\"acnomfuncion\":\"GENERAR CONSOLIDADO\",\"status\":\"I\"}],\"rc\":0},{\"idModulo\":\"REPEDO\",\"status\":\"A\",\"funciones\":[{\"accodfuncion\":\"REPEDO\",\"acnomfuncion\":\"REPORTE DE ESTADOS DE CUENTA\",\"status\":\"A\"},{\"accodfuncion\":\"REPEDO\",\"acnomfuncion\":\"REPORTE DE ESTADOS DE CUENTA\",\"status\":\"A\"}],\"rc\":0},{\"idModulo\":\"REPLOT\",\"status\":\"A\",\"funciones\":[{\"accodfuncion\":\"REPLOT\",\"acnomfuncion\":\"REPORTE DE ESTATUS DE LOTES\",\"status\":\"A\"},{\"accodfuncion\":\"REPLOT\",\"acnomfuncion\":\"REPORTE DE ESTATUS DE LOTES\",\"status\":\"A\"}],\"rc\":0},{\"idModulo\":\"REPPRO\",\"status\":\"A\",\"funciones\":[{\"accodfuncion\":\"REPPRO\",\"acnomfuncion\":\"REPORTE RECARGAS REALIZADAS\",\"status\":\"A\"},{\"accodfuncion\":\"REPPRO\",\"acnomfuncion\":\"REPORTE RECARGAS REALIZADAS\",\"status\":\"A\"}],\"rc\":0},{\"idModulo\":\"REPREP\",\"status\":\"A\",\"funciones\":[{\"accodfuncion\":\"REPREP\",\"acnomfuncion\":\"REPORTE REPOSICIONES\",\"status\":\"I\"}],\"rc\":0},{\"idModulo\":\"REPRTH\",\"status\":\"A\",\"funciones\":[{\"accodfuncion\":\"REPRTH\",\"acnomfuncion\":\"REPORTE RECARGA CON COMISIONES\",\"status\":\"I\"}],\"rc\":0},{\"idModulo\":\"REPSAL\",\"status\":\"A\",\"funciones\":[{\"accodfuncion\":\"REPSAL\",\"acnomfuncion\":\"REPORTE SALDOS AL CIERRE\",\"status\":\"I\"}],\"rc\":0},{\"idModulo\":\"REPTAR\",\"status\":\"A\",\"funciones\":[{\"accodfuncion\":\"REPTAR\",\"acnomfuncion\":\"REPORTE TARJETAS EMITIDAS\",\"status\":\"A\"},{\"accodfuncion\":\"REPTAR\",\"acnomfuncion\":\"REPORTE TARJETAS EMITIDAS\",\"status\":\"A\"}],\"rc\":0},{\"idModulo\":\"REPUSU\",\"status\":\"A\",\"funciones\":[{\"accodfuncion\":\"REPUSU\",\"acnomfuncion\":\"REPORTE ACTIVIDAD POR USUARIO\",\"status\":\"A\"},{\"accodfuncion\":\"REPUSU\",\"acnomfuncion\":\"REPORTE DE USUARIOS EMPRESA\",\"status\":\"I\"}],\"rc\":0},{\"idModulo\":\"SALDAM\",\"status\":\"A\",\"funciones\":[{\"accodfuncion\":\"SALDAM\",\"acnomfuncion\":\"SALDOS AMANECIDOS\",\"status\":\"I\"}],\"rc\":0},{\"idModulo\":\"TEBTHA\",\"status\":\"A\",\"funciones\":[{\"accodfuncion\":\"TEBTHA\",\"acnomfuncion\":\"REPORTE DE TARJETAHABIENTE\",\"status\":\"A\"}],\"rc\":0}]},{\"idPerfil\":\"GESUSR\",\"descripcion\":\"USUARIOS\",\"status\":\"A\",\"modulos\":[{\"idModulo\":\"USEREM\",\"status\":\"A\",\"funciones\":[{\"accodfuncion\":\"ACTUSU\",\"acnomfuncion\":\"ACTUALIZACION DE USUARIOS\",\"status\":\"I\"},{\"accodfuncion\":\"ASGPER\",\"acnomfuncion\":\"ASIGNACION DE PERMISOS\",\"status\":\"A\"},{\"accodfuncion\":\"CONPER\",\"acnomfuncion\":\"CONSULTA DE PERMISOS\",\"status\":\"I\"},{\"accodfuncion\":\"CONUSU\",\"acnomfuncion\":\"CONSULTA DE USUARIOS\",\"status\":\"A\"},{\"accodfuncion\":\"CREUSU\",\"acnomfuncion\":\"CREACION DE USUARIOS\",\"status\":\"A\"},{\"accodfuncion\":\"ELMPER\",\"acnomfuncion\":\"ELIMINACION DE PERMISOS\",\"status\":\"I\"}],\"rc\":0}]},{\"idPerfil\":\"SERVIC\",\"descripcion\":\"SERVICIOS\",\"status\":\"A\",\"modulos\":[{\"idModulo\":\"COPELO\",\"status\":\"A\",\"funciones\":[{\"accodfuncion\":\"OPCONL\",\"acnomfuncion\":\"CONSULTA DE ESTADO/OPERACION TARJETAS\",\"status\":\"A\"}],\"rc\":0},{\"idModulo\":\"GIRCOM\",\"status\":\"A\",\"funciones\":[{\"accodfuncion\":\"ACTGIR\",\"acnomfuncion\":\"ACTUALIZACION DE TARJETA\",\"status\":\"A\"},{\"accodfuncion\":\"CONGIR\",\"acnomfuncion\":\"CONSULTA A TARJETAS\",\"status\":\"A\"}],\"rc\":0},{\"idModulo\":\"LIMTRX\",\"status\":\"A\",\"funciones\":[{\"accodfuncion\":\"ACTLIM\",\"acnomfuncion\":\"ACTUALIZACION DE TARJETA\",\"status\":\"A\"},{\"accodfuncion\":\"CONLIM\",\"acnomfuncion\":\"CONSULTA A TARJETAS\",\"status\":\"A\"}],\"rc\":0},{\"idModulo\":\"TEBPOL\",\"status\":\"A\",\"funciones\":[{\"accodfuncion\":\"TEBPOL\",\"acnomfuncion\":\"EMISION DE POLIZA\",\"status\":\"I\"}],\"rc\":0},{\"idModulo\":\"TRAMAE\",\"status\":\"A\",\"funciones\":[{\"accodfuncion\":\"TRAABO\",\"acnomfuncion\":\"ABONOS A TARJETAS\",\"status\":\"A\"},{\"accodfuncion\":\"TRAASG\",\"acnomfuncion\":\"REASIGNACION DE TARJETA\",\"status\":\"A\"},{\"accodfuncion\":\"TRABLQ\",\"acnomfuncion\":\"BLOQUEO A TARJETAS\",\"status\":\"A\"},{\"accodfuncion\":\"TRACAR\",\"acnomfuncion\":\"CARGOS A TARJETAS\",\"status\":\"A\"},{\"accodfuncion\":\"TRADBL\",\"acnomfuncion\":\"DESBLOQUEO A TARJETA\",\"status\":\"A\"},{\"accodfuncion\":\"TRAPGO\",\"acnomfuncion\":\"ABONAR CUENTA CONCENTRADORA\",\"status\":\"A\"},{\"accodfuncion\":\"TRASAL\",\"acnomfuncion\":\"CONSULTA A TARJETAS\",\"status\":\"A\"}],\"rc\":0}]}]}"}';
		// $this->isResponseRc = 0;

		switch($this->isResponseRc)  {
			case 0:
				$this->response->code = 0;
				$data = $response->bean->perfiles;
				//$data = (json_decode(json_decode($response)->bean)->perfiles);
				$this->response->data = $data;
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
		$this->dataRequest->userName = $dataRequest->user;
		// $this->dataRequest->userName = 'DGONZALEZ1';

		foreach ($dataRequest as $key => $value) {
			$objeto[lang('PERMITS_UPDATE_ENGLISH_CHANGE')[$key]] = $value;
			unset($objeto[$key]);
		};

		$i=0;
		$j=0;
		$functionsArray =[];

		foreach ($objeto as $key => $value) {
			if($value == "off"){
				$objeto[$i] = ['accodfuncion' => $key,
													'status'=> 'I'];
			}else{
				$objeto[$i] = ['accodfuncion' => $key,
				'status'=> 'A'];
			}
			$i++;
			unset($objeto[$key]);
		}

		foreach ($objeto as $key => $value) {
			$functionsArray[$j] = $value;
			$j++;
		};

		$this->dataRequest->perfiles = [['idPerfil' => 'TODOS',
		'modulos' => [['idModulo' => 'TODOS',
		'funciones' => $functionsArray]]]];

		$response = $this->sendToService('callWs_updatePermissions');

		// $this->isResponseRc = 0;

		switch($this->isResponseRc)   {
			case 0:
				$this->response->code = 0;
				$this->response->data = (array)$response;
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
		$this->dataRequest->userName = 'DGONZALEZ1';
		$this->dataRequest->idUsuario = '16903005';
		$this->dataRequest->nombre1 = 'DEIBY';
		$this->dataRequest->nombre2 = 'GABRIEL';
		$this->dataRequest->apellido1 = 'GONZALEZ';
		$this->dataRequest->apellido2 = 'HERNANDEZ';
		$this->dataRequest->clonarPermisos = 'true';
		$this->dataRequest->mail = 'dehernandez@novopayment.onmicrosoft.com';
		$this->dataRequest->empresa = '1234567890';
		$this->dataRequest->usuarioPlantilla = 'DGONZALEZ1';

		$response = $this->sendToService('callWs_enableUser');

		switch($this->isResponseRc)   {
			case 0:
				$this->response->code = 0;
				$this->response->data = (array)$response;
				break;
		}

		return $this->responseToTheView('callWs_enableUser');
	}
}

