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
		$this->dataRequest->ctipo = '';

		$response = $this->sendToService('Login');

		switch($this->isResponseRc) {
			case 0:
				$nameUser = mb_strtolower($response->usuario->primerNombre).' ';
				$nameUser+= mb_strtolower($response->usuario->primerApellido);
				$formatDate = $this->config->item('format_date');
				$formatTime = $this->config->item('format_time');
				$lastSession = date("$formatDate $formatTime", strtotime(str_replace('/', '-', $response->usuario->fechaUltimaConexion)));
				$userData = [
					'sessionId' => $response->logAccesoObject->sessionId,
					'logged' => TRUE,
					'idUsuario' => $response->usuario->idUsuario,
					'userName' => $response->usuario->userName,
					'nombreCompleto' => $nameUser,
					'codigoGrupo' => $response->usuario->codigoGrupo,
					'lastSession' => $lastSession,
					'token' => $response->token,
					'cl_addr' => $this->encrypt_connect->encode($_SERVER['REMOTE_ADDR']),
					'countrySess' => $this->session->userdata('countryConf'),
					'pais' => $this->session->userdata('countryConf'),
					'logged_in' => TRUE
				];

				$this->session->set_userdata($userData);
				$this->response->code = 200;
				$this->response->msg = 'Ingreso exitoso';
				break;
			case -2:
				$this->response->code = 301;
				$this->response->title = 'Aceptar términos de uso';
				$this->response->msg = 'Usuario nuevo';
				$this->response->data = base_url('inf-condiciones');
				$this->session->set_flashdata('newUser', TRUE);
				break;
		}

		return $this->response;
	}
}
