<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Módelo para la información del usuario
 * @author J. Enrique Peñaloza Piñero
 *
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
	 */
	public function callWs_Login_User($dataRequest)
	{
		log_message('INFO', 'NOVO User Model: Login method Initialized');
		$this->className = 'com.novo.objects.TOs.UsuarioTO';

		$this->dataAccessLog->modulo = 'login';
		$this->dataAccessLog->function = 'login';
		$this->dataAccessLog->operation = 'loginFull';
		$this->dataAccessLog->userName = $dataRequest->user;

		$this->dataRequest->userName = mb_strtoupper($dataRequest->user);
		$this->dataRequest->password = $dataRequest->pass;
		$this->dataRequest->ctipo = $dataRequest->active;

		$response = $this->sendToService('Login');
		//$this->isResponseRc = -262;
		switch($this->isResponseRc) {
			case 0:
				log_message('DEBUG', 'NOVO ['.$dataRequest->user.'] RESPONSE: Login: ' . json_encode($response->usuario));
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
					'idUsuario' => $response->usuario->idUsuario,
					'userName' => $response->usuario->userName,
					'fullName' => $fullName,
					'codigoGrupo' => $response->usuario->codigoGrupo,
					'lastSession' => $lastSession,
					'token' => $response->token,
					'cl_addr' => $this->encrypt_connect->encode($_SERVER['REMOTE_ADDR'], $dataRequest->user, 'REMOTE_ADDR'),
					'countrySess' => $this->config->item('country'),
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
					'idUsuario' => $response->usuario->idUsuario,
					'userName' => $response->usuario->userName,
					'fullName' => $fullName,
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
				$this->response->className = 'error-login-2';
				break;
			case -8:
			case -35:
				$this->response->code = 1;
				$this->response->msg = lang('RESP_SUSPENDED_USER');
				$this->response->className = 'login-inactive';
				break;
			case -229:
				$this->response->code = 2;
				$this->response->msg = lang('RESP_OLD_USER');
				break;
			case -262:
				$this->response->code = 3;
				$this->response->msg = lang('RESP_NO_PERMISSIONS');
				$this->response->icon = 'ui-icon-info';
				$this->response->data = [
					'btn1'=> [
						'text'=> FALSE,
						'link'=> FALSE,
						'action'=> 'close'
					]
				];
				break;
			case -28:
				$this->response->code = 3;
				$this->response->msg = lang('RESP_INCORRECTLY_CLOSED');
				$this->response->icon = 'ui-icon-alert';
				$this->response->data = [
					'btn1'=> [
						'text'=> FALSE,
						'link'=> [
							'who'=> 'User',
							'where'=> 'FinishSession'
						],
						'action'=> 'logout'
					]
				];
				break;
		}

		return $this->response;
	}
	/**
	 * @info Método para recuperar contraseña
	 * @author J. Enrique Peñaloza Piñero
	 */
	public function callWs_RecoveryPass_User($dataRequest)
	{
		log_message('INFO', 'NOVO User Model: RecoveryPass method Initialized');
		$this->className = 'com.novo.objects.TO.UsuarioTO';

		$this->dataAccessLog->modulo = 'clave';
		$this->dataAccessLog->function = 'recuperarClave';
		$this->dataAccessLog->operation = 'olvidoClave';
		$this->dataAccessLog->userName = $dataRequest->userName;

		$this->dataRequest->userName = mb_strtoupper($dataRequest->userName);
		$this->dataRequest->idEmpresa = $dataRequest->idEmpresa;
		$this->dataRequest->email = $dataRequest->email;

		$response = $this->sendToService('RecoveryPass');

		$this->response->title = lang('RECOVERYPASS_TITLE');
		switch($this->isResponseRc) {
			case 0:
				$maskMail = maskString($dataRequest->email, 4, $end = 6, '@');
				$this->response->code = 0;
				$this->response->msg = novoLang(lang('RESP_TEMP_PASS'), $maskMail);
				$this->response->icon = 'ui-icon-circle-check';
				$this->response->data = [
					'btn1'=> [
						'text'=> lang('GEN_BTN_CONTINUE'),
						'link'=> base_url('inicio'),
						'action'=> 'redirect'
					]
				];
				break;
			case -205:
				//soporteempresas@tebca.com
				$this->response->msg = lang('RES_UNREGISTERED_USER');
				$this->response->msg.= novoLang(lang('RES_SUPPORT'), [lang('RES_SUPPORT_MAIL'), lang('RES_SUPPORT_TELF')]);
				break;
		}

		if($this->isResponseRc != 0) {
			$this->response->code = 1;
			$this->response->icon = 'ui-icon-info';
			$this->response->data = [
				'btn1'=> [
					'text'=> FALSE,
					'link'=> FALSE,
					'action'=> 'close'
				]
			];
		}


		return $this->response;
	}
	/**
	 * @info Método para el cambio de Contraseña
	 * @author J. Enrique Peñaloza Piñero
	 */
	public function CallWs_ChangePassword_User($dataRequest)
	{
		log_message('INFO', 'NOVO User Model: ChangePassword Method Initialized');
		$this->className = 'com.novo.objects.TOs.UsuarioTO';

		$this->dataAccessLog->modulo = 'login';
		$this->dataAccessLog->function = 'login';
		$this->dataAccessLog->operation = 'cambioClave';

		$this->dataRequest->userName = $this->session->userdata('userName');
		$this->dataRequest->passwordOld = $dataRequest->currentPass;
		$this->dataRequest->password = $dataRequest->newPass;

		$changePassType = $this->session->flashdata('changePassword');

		$response = $this->sendToService('ChangePassword');

		switch($this->isResponseRc) {
			case 0:
				$this->callWs_FinishSession_User();
				$this->response->code = 0;
				$this->response->msg = lang('CHANGEPASSWORD_MSG-'.$this->isResponseRc);
				$this->response->icon = 'ui-icon-circle-check';
				$this->response->data = [
					'btn1'=> [
						'text'=> lang('GEN_BTN_CONTINUE'),
						'link'=> base_url('inicio'),
						'action'=> 'redirect'
					]
				];
				break;
			case -4:
			case -22:
				$this->response->code = 1;
				$this->response->icon = 'ui-icon-alert';
				$this->response->msg = lang('CHANGEPASSWORD_MSG-'.$this->isResponseRc);
				$this->response->data = [
					'btn1'=> [
						'text'=> FALSE,
						'link'=> FALSE,
						'action'=> 'close'
					]
				];
				$this->session->set_flashdata('changePassword', $changePassType);
				$this->session->set_flashdata('userType', $this->session->flashdata('userType'));
				break;
		}

		return $this->response;
	}
	/**
	 * @info Método para el cierre de sesión
	 * @author J. Enrique Peñaloza Piñero
	 */
	public function callWs_FinishSession_User($dataRequest = FALSE)
	{
		log_message('INFO', 'NOVO User Model: FinishSession method Initialized');
		$user = $dataRequest ? mb_strtoupper($dataRequest->user) : $this->session->userdata('userName');
		$this->className = 'com.novo.objects.TOs.UsuarioTO';

		$this->dataAccessLog->userName = $user;
		$this->dataAccessLog->modulo = 'logout';
		$this->dataAccessLog->function = 'logout';
		$this->dataAccessLog->operation = 'desconectarUsuario';

		$this->dataRequest->idUsuario = $user;
		$this->dataRequest->codigoGrupo = $this->session->userdata('codigoGrupo');

		$response = $this->sendToService('FinishSession');

		$this->response->code = 0;
		$this->response->msg = lang('GEN_BTN_ACCEPT');
		$this->response->data = FALSE;

		$this->session->sess_destroy();
		return $this->response;
	}

	public function callWs_validateCaptcha_User($dataRequest)
	{
		$this->load->library('recaptcha');
		$result = $this->recaptcha->verifyResponse($dataRequest->token);

		$logMessage = 'NOVO ['.$dataRequest->user.'] RESPONSE: recaptcha País: "' .$this->config->item('country');
		$logMessage.= '", Score: "' . $result["score"] .'", Hostname: "'. $result["hostname"].'"';
		log_message('DEBUG', $logMessage);
		$this->response->title = lang('SYSTEM_NAME');

		if($result["score"] <= 0) {
			$this->response->code = 3;
			$this->response->icon = 'ui-icon-closethick';
			$this->response->msg = lang('VALIDATECAPTCHA_MSG-0');
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
