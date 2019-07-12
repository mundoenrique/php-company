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
		if($this->isResponseRc !== FALSE) {
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
					$this->response->msg = 'Ingreso exitoso';
					$this->response->data = base_url('dashboard');
					break;
				case -2:
				case -185:
					$fullName = mb_strtolower($response->usuario->primerNombre).' ';
					$fullName.= mb_strtolower($response->usuario->primerApellido);
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
					$this->response->title = 'Usuario nuevo';
					$this->response->msg = 'Debes aceptar los términos de uso';
					$this->response->data = base_url('inf-condiciones');
					$this->session->set_flashdata('changePassword', 'newUser');
					$this->session->set_flashdata('userType', $response->usuario->ctipo);

					if($this->isResponseRc === -185) {
						$this->response->code = 0;
						$this->response->title = 'Clave vencida';
						$this->response->msg = 'Debes cambiar la clave';
						$this->response->data = base_url('cambiar-clave');
						$this->session->set_flashdata('changePassword', 'expiredPass');
						break;
					}
					break;
				case -1:
				case -263:
					$this->response->code = 1;
					$this->response->title = 'Usuario incorrecto';
					$this->response->className = 'error-login-2';
					$this->response->msg = lang('ERROR_(-1)');
					break;
				case -8:
				case -35:
					$this->response->code = 1;
					$this->response->title = 'Usuario suspendido';
					$this->response->className = 'login-inactive';
					$this->response->msg = lang('ERROR_(-8)');
					break;
				case -229:
					$this->response->code = 2;
					$this->response->title = 'Usuario aplicación anterior';
					break;
				case -262:
					$this->response->code = 3;
					$this->response->msg = 'Estimado usuario no tienes permisos para la aplicación, por favor comunícate ';
					$this->response->msg.= 'con el administrador';
					$this->response->icon = 'ui-icon-info';
					$this->response->data = [
						'btn1'=> [
							'text'=> 'Aceptar',
							'link'=> FALSE,
							'action'=> 'close'
						]
					];
					break;
				case -28:
					$this->response->code = 3;
					$this->response->msg = lang('ERROR_(-28)');
					$this->response->icon = 'ui-icon-alert';
					$this->response->data = [
						'btn1'=> [
							'text'=> 'Aceptar',
							'link'=> [
								'who'=> 'User',
								'where'=> 'FinishSession'
							],
							'action'=> 'logout'
						]
					];
					break;
			}
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

		if($this->isResponseRc !== FALSE) {
			$this->response->title = 'Restablecer contraseña';
			switch($this->isResponseRc) {
				case 0:
					$maskMail = maskString($dataRequest->email, 4, $end = 6, '@');
					$this->response->code = 0;
					$this->response->msg = 'Proceso exitoso, se ha enviado un correo a '.$maskMail.' con la contraseña temporal.';
					$this->response->icon = 'ui-icon-circle-check';
					$this->response->data = [
						'btn1'=> [
							'text'=> 'Continuar',
							'link'=> base_url('inicio'),
							'action'=> 'redirect'
						]
					];
					break;
				case -6:
					$msg = 'El usuario indicado no posee empresa asignada.';
					break;
				case -150:
					$msg = lang('ERROR_RIF');
					break;
				case -159:
					$msg = lang('ERROR_MAIL');
					break;
				case -173:
					$msg = 'No fue posible enviar el correo.<br>Verifícalo e intenta nuevamente.';
					break;
				case -205:
					$msg = lang('ERROR_USER');
					if($this->countryUri == 've') {
						$msg.= '<br>'.lang('ERROR_SUPPORT');
					}
					break;
			}

			if($this->isResponseRc != 0) {
				$this->response->code = 1;
				$this->response->msg = $msg;
				$this->response->icon = 'ui-icon-info';
				$this->response->data = [
					'btn1'=> [
						'text'=> 'Aceptar',
						'link'=> FALSE,
						'action'=> 'close'
					]
				];
			}
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

		if($this->isResponseRc !== FALSE) {
			switch($this->isResponseRc) {
				case 0:
					$this->callWs_FinishSession_User();
					$this->response->code = 0;
					$this->response->msg = 'La contraseña fue cambiada exitosamente.<br>Por motivos de seguridad es necesario que inicies sesión nuevamente.';
					$this->response->icon = 'ui-icon-circle-check';
					$this->response->data = [
						'btn1'=> [
							'text'=> 'Continuar',
							'link'=> base_url('inicio'),
							'action'=> 'redirect'
						]
					];
					break;
				case -4:
				case -22:
					$this->response->code = 1;
					$this->response->icon = 'ui-icon-alert';
					$this->response->msg = lang('ERROR_('.$this->isResponseRc.')');
					$this->response->data = [
						'btn1'=> [
							'text'=> 'Aceptar',
							'link'=> FALSE,
							'action'=> 'close'
						]
					];
					$this->session->set_flashdata('changePassword', $changePassType);
					$this->session->set_flashdata('userType', $this->session->flashdata('userType'));
					break;
			}
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

		if($this->isResponseRc !== FALSE) {
			switch($this->isResponseRc) {
				case 0:
					$this->response->code = 0;
					$this->response->msg = 'Sessión finalizada exitosamente';
					$this->response->data = 'finishSession';
					break;
				}
			}

		$this->session->sess_destroy();
		return $this->response;
	}

	public function callWs_validateCaptcha_User($dataRequest)
	{

		$this->load->library('recaptcha');
		$result = $this->recaptcha->verifyResponse($dataRequest->token);

		$logMessage = 'NOVO ['.$dataRequest->user.'] RESPONSE: recaptcha: País: "' .$this->config->item('country');
		$logMessage.= '", Score: "' . $result["score"] .'", Hostname: "'. $result["hostname"].'"';
		log_message('DEBUG', $logMessage);

		$this->response->title = lang('SYSTEM_NAME');
		if($result["score"] <= 0) {

			$this->response->code = 1;
			$this->response->icon = 'ui-icon-closethick';
			$this->response->msg = 'El sistema ha detectado una actividad no autorizada, por favor intenta nuevamente';
			$this->response->data = [
				'btn1'=> [
					'text'=> 'Aceptar',
					'link'=> base_url('inicio'),
					'action'=> 'close'
				]
			];
		} else {
			$this->response->code = 0;
			$this->response->data = 'Ok';
		}
		return $this->response;

	}
}
