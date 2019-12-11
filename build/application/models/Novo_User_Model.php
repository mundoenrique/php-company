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
		log_message('INFO', 'NOVO User Model: Login method Initialized');

		$this->className = 'com.novo.objects.TOs.UsuarioTO';
		$userName = mb_strtoupper($dataRequest->user);

		$this->dataAccessLog->modulo = 'Usuario';
		$this->dataAccessLog->function = 'Ingreso al sistema';
		$this->dataAccessLog->operation = 'Iniciar sesion';
		$this->dataAccessLog->userName = $userName;
		$passWord = json_decode(base64_decode($dataRequest->pass));
		$passWord = $this->cryptography->decrypt(
			base64_decode($passWord->plot),
			utf8_encode($passWord->passWord)
		);
		$this->dataRequest->idOperation = 'loginFull';
		$this->dataRequest->userName = $userName;
		$this->dataRequest->password = md5($passWord);
		$this->dataRequest->ctipo = $dataRequest->active;

		$response = $this->sendToService(lang('GEN_LOGIN'));

		switch($this->isResponseRc) {
			case 0:
				log_message('DEBUG', 'NOVO ['.$userName.'] RESPONSE Login: ' . json_encode($response->usuario));

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
					'codigoGrupo' => $response->usuario->codigoGrupo,
					'lastSession' => $lastSession,
					'token' => $response->token,
					'cl_addr' => $this->encrypt_connect->encode($_SERVER['REMOTE_ADDR'], $userName, 'REMOTE_ADDR'),
					'countrySess' => $this->config->item('country'),
					'idUsuario' => $response->usuario->idUsuario,
					'pais' => $this->config->item('country'),
					'nombreCompleto' => $fullName,
					'logged_in' => TRUE
				];
				$this->session->set_userdata($userData);

				$this->response->code = 0;
				$this->response->data = base_url(lang('GEN_ENTERPRISE_LIST'));
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
					'cl_addr' => $this->encrypt_connect->encode($_SERVER['REMOTE_ADDR'], $dataRequest->user, 'REMOTE_ADDR'),
					'countrySess' => $this->config->item('country')
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
				$this->response->className = lang('VALIDATE_INVALID_USER');
				break;
			case -8:
			case -35:
				$this->response->code = 1;
				$this->response->msg = lang('RESP_SUSPENDED_USER');
				$this->response->className = lang('VALIDATE_INACTIVE_USER');
				break;
			case -229:
				$this->response->code = 2;
				$this->response->msg = lang('RESP_OLD_USER');
				break;
			case -262:
				$this->response->code = 3;
				$this->response->msg = lang('RESP_NO_PERMISSIONS');
				$this->response->icon = lang('GEN_ICON_INFO');
				$this->response->data = [
					'btn1'=> [
						'action'=> 'close'
					]
				];
				break;
			case -28:
				$this->response->code = 3;
				$this->response->msg = lang('RESP_INCORRECTLY_CLOSED');
				$this->response->icon = lang('GEN_ICON_WARNING');
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
		}

		return $this->responseToTheView(lang('GEN_LOGIN'));
	}
	/**
	 * @info Método para recuperar contraseña
	 * @author J. Enrique Peñaloza Piñero
	 * @date April 29th, 2019
	 */
	public function callWs_RecoverPass_User($dataRequest)
	{
		log_message('INFO', 'NOVO User Model: RecoverPass method Initialized');

		$this->className = 'com.novo.objects.TO.UsuarioTO';

		$userName = mb_strtoupper($dataRequest->user);

		$this->dataAccessLog->modulo = 'Usuario';
		$this->dataAccessLog->function = 'Recuperar Clave';
		$this->dataAccessLog->operation = 'Enviar Clave';
		$this->dataAccessLog->userName = $userName;

		$this->dataRequest->idOperation = 'olvidoClave';
		$this->dataRequest->userName = $userName;
		$this->dataRequest->idEmpresa = $dataRequest->idEmpresa;
		$this->dataRequest->email = $dataRequest->email;

		$maskMail = maskString($dataRequest->email, 4, $end = 6, '@');
		$response = $this->sendToService(lang('GEN_RECOVER_PASS'));

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->msg = novoLang(lang('RESP_TEMP_PASS'), [$this->dataRequest->userName, $maskMail]);
				$this->response->icon = lang('GEN_ICON_SUCCESS');
				$this->response->data = [
					'btn1'=> [
						'text'=> lang('GEN_BTN_CONTINUE'),
						'link'=> base_url('inicio'),
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
				$this->response->msg = novoLang(lang('RESP_FISCAL_REGISTRY_NO_FOUND'), lang('GEN_FISCAL_REGISTRY'));
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
			$this->response->icon = lang('GEN_ICON_INFO');
			$this->response->data = [
				'btn1'=> [
					'action'=> 'close'
				]
			];
		}

		return $this->responseToTheView(lang('GEN_RECOVER_PASS'));
	}
	/**
	 * @info Método para el cambio de Contraseña
	 * @author J. Enrique Peñaloza Piñero
	 * @date April 29th, 2019
	 */
	public function CallWs_ChangePassword_User($dataRequest)
	{
		log_message('INFO', 'NOVO User Model: ChangePassword Method Initialized');

		$this->className = 'com.novo.objects.TOs.UsuarioTO';

		$this->dataAccessLog->modulo = 'Usuario';
		$this->dataAccessLog->function = 'Clave';
		$this->dataAccessLog->operation = 'Cambiar Clave';

		$this->dataRequest->idOperation = 'cambioClave';
		$this->dataRequest->userName = $this->userName;
		$this->dataRequest->passwordOld = $dataRequest->currentPass;
		$this->dataRequest->password = $dataRequest->newPass;

		$changePassType = $this->session->flashdata('changePassword');
		$response = $this->sendToService(lang('GEN_CHANGE_PASS'));

		switch($this->isResponseRc) {
			case 0:
				$this->callWs_FinishSession_User();
				$this->response->code = 0;
				$this->response->msg = lang('RESP_PASSWORD_CHANGED');
				$this->response->icon = lang('GEN_ICON_SUCCESS');
				$this->response->data = [
					'btn1'=> [
						'text'=> lang('GEN_BTN_CONTINUE'),
						'link'=> base_url('inicio'),
						'action'=> 'redirect'
					]
				];
				break;
			case -4:
				$this->response->code = 1;
				$this->response->msg = lang('RESP_PASSWORD_USED');
				break;
			case -22:
				$this->response->code = 1;
				$this->response->msg = lang('RESP_PASSWORD_INCORRECT');
				break;
		}

		if($this->isResponseRc != 0 && $this->response->code == 1) {
			$this->session->set_flashdata('changePassword', $changePassType);
			$this->session->set_flashdata('userType', $this->session->flashdata('userType'));
			$this->response->title = lang('GEN_PASSWORD_CHANGE_TITLE');
			$this->response->icon = lan('GEN_ICON_WARNING');
			$this->response->data = [
				'btn1'=> [
					'action'=> 'close'
				]
			];
		}

		return $this->responseToTheView(lang('GEN_CHANGE_PASS'));
	}
	/**
	 * @info Método para el cierre de sesión
	 * @author J. Enrique Peñaloza Piñero
	 * @date May 1st, 2019
	 */
	public function callWs_FinishSession_User($dataRequest = FALSE)
	{
		log_message('INFO', 'NOVO User Model: FinishSession method Initialized');

		$this->className = 'com.novo.objects.TOs.UsuarioTO';

		$userName = $dataRequest ? mb_strtoupper($dataRequest->user) : $this->userName;

		$this->dataAccessLog->userName = $userName;
		$this->dataAccessLog->modulo = 'Usuario';
		$this->dataAccessLog->function = 'Salir del sistema';
		$this->dataAccessLog->operation = 'Cerrar sesion';

		$this->dataRequest->idOperation = 'desconectarUsuario';
		$this->dataRequest->idUsuario = $userName;
		$this->dataRequest->codigoGrupo = $this->session->codigoGrupo;

		$response = $this->sendToService(lang('GEN_FINISH_SESSION'));

		$this->response->code = 0;
		$this->response->msg = lang('GEN_BTN_ACCEPT');
		$this->response->data = FALSE;

		$this->session->sess_destroy();

		return $this->responseToTheView(lang('GEN_FINISH_SESSION'));
	}
	/**
	 * @info Método validación recaptcha
	 * @author Yelsyns Lopez
	 * @date May 16th, 2019
	 * @modified J. Enrique Peñaloza Piñero
	 * @date October 21st, 2019
	 */
	public function callWs_validateCaptcha_User($dataRequest)
	{
		log_message('INFO', 'NOVO User Model: validateCaptcha method Initialized');

		$this->load->library('recaptcha');

		$result = $this->recaptcha->verifyResponse($dataRequest->token);

		$logMessage = 'NOVO ['.$dataRequest->user.'] RESPONSE: recaptcha País: "' .$this->config->item('country');
		$logMessage.= '", Score: "' . $result["score"] .'", Hostname: "'. $result["hostname"].'"';
		log_message('DEBUG', $logMessage);

		$this->response->title = lang('GEN_SYSTEM_NAME');

		if($result["score"] <= 0) {
			$this->response->code = 3;
			$this->response->icon = lang('GEN_ICON_DANGER');
			$this->response->msg = lang('RESP_RECAPTCHA_VALIDATION_FAILED');
			$this->response->data = [
				'btn1'=> [
					'text'=> lang('GEN_BTN_ACCEPT'),
					'link'=> base_url('inicio'),
					'action'=> 'redirect'
				]
			];
		} else {
			$this->callWs_Login_User($dataRequest);
		}

		return $this->response;
	}
}
