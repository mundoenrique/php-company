<?php defined('BASEPATH') or exit('No direct script access allowed');

class Novo_User_Model extends NOVO_Model {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO User Model Class Initialized');
	}

	public function callWs_Login_User($dataRequest)
	{
		log_message('INFO', 'NOVO User Model: Login method Initialized');
		$this->className = 'com.novo.objects.TOs.UsuarioTO';

		$this->dataAccessLog->modulo = 'login';
		$this->dataAccessLog->function = 'login';
		$this->dataAccessLog->operation = 'loginFull';
		$this->dataAccessLog->userName = $dataRequest->user;

		$this->dataRequest->userName = $dataRequest->user;
		$this->dataRequest->password = $dataRequest->pass;
		$this->dataRequest->ctipo = $dataRequest->active;

		$response = $this->sendToService('Login');
		if($this->isResponseRc !== FALSE) {
			switch($this->isResponseRc) {
				case 0:
					log_message('DEBUG', 'NOVO ['.$dataRequest->user.'] RESPONSE: Login: ' . json_encode($response->usuario));
					$nameUser = mb_strtolower($response->usuario->primerNombre).' ';
					$nameUser.= mb_strtolower($response->usuario->primerApellido);
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
						'nombreCompleto' => $nameUser,
						'codigoGrupo' => $response->usuario->codigoGrupo,
						'lastSession' => $lastSession,
						'token' => $response->token,
						'cl_addr' => $this->encrypt_connect->encode($_SERVER['REMOTE_ADDR'], $dataRequest->user, 'REMOTE_ADDR'),
						'countrySess' => $this->config->item('country'),
						'pais' => $this->config->item('country'),
						'logged_in' => TRUE
					];

					$this->session->set_userdata($userData);
					$this->response->code = 0;
					$this->response->msg = 'Ingreso exitoso';
					$this->response->data = base_url('dashboard');
					break;
				case -2:
				case -185:
					$nameUser = mb_strtolower($response->usuario->primerNombre).' ';
					$nameUser.= mb_strtolower($response->usuario->primerApellido);
					$userData = [
						'sessionId' => $response->logAccesoObject->sessionId,
						'idUsuario' => $response->usuario->idUsuario,
						'userName' => $response->usuario->userName,
						'nombreCompleto' => $nameUser,
						'token' => $response->token,
						'cl_addr' => $this->encrypt_connect->encode($_SERVER['REMOTE_ADDR'], $dataRequest->user, 'REMOTE_ADDR'),
						'countrySess' => $this->config->item('country'),
					];

					$this->session->set_userdata($userData);

					$this->response->code = 0;
					$this->response->title = 'Usuario nuevo';
					$this->response->msg = 'Debe aceptar los términos de uso';
					$this->response->data = base_url('inf-condiciones');
					$this->session->set_flashdata('changePassword', 'newUser');

					if($this->isResponseRc === -185) {
						$this->response->code = 0;
						$this->response->title = 'Clave vencida';
						$this->response->msg = 'Debe cambiar la clave';
						$this->response->data = base_url('cambiar-clave');
						$this->session->set_flashdata('changePassword', 'expiredPass');
						break;
					}
					break;
				case -1:
					$this->response->code = 1;
					$this->response->title = 'Usuario incorrecto';
					$this->response->className = 'error-login-2';
					$this->response->msg = lang('ERROR_(-1)');
					break;
				case -263:
					$this->response->code = 1;
					$this->response->title = 'El usuario será suspendido';
					$this->response->className = 'login-inactive';
					$this->response->msg = lang('ERROR_(-263)');
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
					$this->response->msg = 'Estimado usuario usted no tiene permisos para la aplicación, por favor comuníquese ';
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
								'where'=> 'finishSession'
							],
							'action'=> 'logout'
						]
					];
					break;
			}
		}

		return $this->response;
	}

	public function callWs_finishSession_User($dataRequest = FALSE)
	{
		log_message('INFO', 'NOVO User Model: Login method Initialized');
		$user = $dataRequest ? $dataRequest->user : $this->session->userdata('userName');
		$this->className = 'com.novo.objects.TOs.UsuarioTO';

		$this->dataAccessLog->userName = $user;
		$this->dataAccessLog->modulo = 'logout';
		$this->dataAccessLog->function = 'logout';
		$this->dataAccessLog->operation = 'desconectarUsuario';

		$this->dataRequest->idUsuario = $user;
		$this->dataRequest->codigoGrupo = $this->session->userdata('codigoGrupo');

		$response = $this->sendToService('finishSession');

		if($this->isResponseRc !== FALSE) {
			switch($this->isResponseRc) {
				case 0:
					$this->response->code = 0;
					$this->response->msg = 'Sessión finalizada exitosamente';
					$this->response->data = 'finishSession';
					$this->session->sess_destroy();
					break;
			}
		}

		return $this->response;
	}
}
