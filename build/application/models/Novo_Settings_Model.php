<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Módelo para uso de funcionalidades en configuracion
 * @author Diego Acosta García
 * @date May 12th, 2020
 */
class Novo_Settings_Model extends NOVO_Model {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO Settings Model Class Initialized');
	}

		/**
	 * @info Método para Obtener los datos del usuario
	 * @author Diego Acosta García
	 * @date April 29th, 2020
	 */
	public function CallWs_GetUser_Settings()
	{
		log_message('INFO', 'NOVO Settings Model: getUser Method Initialized');

		$this->className = 'com.novo.objects.TOs.UsuarioTO';
		$this->dataAccessLog->modulo = 'configuracion';
		$this->dataAccessLog->function = 'obtener-usuario';
		$this->dataAccessLog->operation = 'getPerfilUsuario';
		$this->dataRequest->idOperation = 'getPerfilUsuario';
		$this->dataRequest->idUsuario = $this->userName;
		$this->dataRequest->userName = $this->userName;

		$response = $this->sendToService(' CallWs_GetUser');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->msg = lang('RESP_EMAIL_CHANGED');
				$this->response->icon = lang('GEN_ICON_SUCCESS');
				$user = $response;
				$this->response->data = $user;
				break;
			case -4:
				$this->response->code = 1;
				$this->response->msg = lang('RESP_USER_INCORRECT');
				break;
			case -22:
				$this->response->code = 1;
				$this->response->msg = lang('RESP_USER_INCORRECT');
				break;
		}

		if($this->isResponseRc != 0 && $this->response->code == 1) {
			$this->response->title = lang('GEN_USER_TITLE');
			$this->response->icon = lang('GEN_ICON_WARNING');
			$this->response->data = [
				'btn1'=> [
					'action'=> 'close'
				]
			];
		}

		return $this->responseToTheView(' CallWs_GetUser');
	}

	/**
	 * @info Método para el cambio de Email
	 * @author Diego Acosta García
	 * @date April 29th, 2020
	 */
	public function CallWs_changeEmail_Settings($dataRequest)
	{

		log_message('INFO', 'NOVO Settings Model: ChangeEmail Method Initialized');

		$this->className = 'com.novo.objects.TOs.UsuarioTO';
		$this->dataAccessLog->modulo = 'configuracion';
		$this->dataAccessLog->function = 'actualizar-usuario';
		$this->dataAccessLog->operation = 'getActualizarUsuario';
		$this->dataRequest->idOperation = 'getActualizarUsuario';
		$this->dataRequest->idUsuario = $this->session->userdata('userName');
		$this->dataRequest->email = $dataRequest->email;
		if(!$dataRequest) {
			$access = [
				'user_access',
			];
			$this->session->unset_userdata($access);
		}
		$this->sendToService('CallWs_ChangeEmail');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->msg = lang('RESP_EMAIL_CHANGED');
				$this->response->icon = lang('GEN_ICON_SUCCESS');
				$this->response->data = [
					'btn1'=> [
						'text'=> lang('GEN_BTN_CONTINUE'),
						'link'=> 'inicio',
						'action'=> 'close'
					]
				];
				break;
			}
		if($this->isResponseRc != 0) {
					$this->response->code = 1;
					$branchOffice[] = (object) [
						'key' => '',
						'text' => lang('RESP_TRY_AGAIN')
					];
				}


		return $this->responseToTheView('CallWs_ChangeEmail');
	}


		/**
	 * @info Método para Obtener los datos de empresa resumido
	 * @author Diego Acosta García
	 * @date May 2nd, 2020
	 */
	public function callWS_ListaEmpresas_Settings()
	{
		log_message('INFO', 'NOVO Business Model: getEnterprise Method Initialized');

		$this->className = 'com.novo.objects.MO.ListadoEmpresasMO';
		$this->dataAccessLog->modulo = 'configuracion';
		$this->dataAccessLog->function = 'obtener empresa';
		$this->dataAccessLog->operation = 'getInfoEmpresaConfig';
		$this->dataRequest->idOperation = 'getEmpresaXUsuario';

		$this->dataRequest->accodusuario = $this->userName;
		$response = $this->sendToService(' ListaEmpresas');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->msg = lang('RESP_EMAIL_CHANGED');
				$this->response->icon = lang('GEN_ICON_SUCCESS');
				$enter = $response;
				$this->response->data = $enter;
				break;
			case -4:
				$this->response->code = 1;
				$this->response->msg = lang('RESP_USER_INCORRECT');
				break;
			case -22:
				$this->response->code = 1;
				$this->response->msg = lang('RESP_USER_INCORRECT');
				break;
		}

		if($this->isResponseRc != 0 && $this->response->code == 1) {
			$this->response->title = lang('GEN_USER_TITLE');
			$this->response->icon = lang('GEN_ICON_WARNING');
			$this->response->data = [
				'btn1'=> [
					'action'=> 'close'
				]
			];
		}

		return $this->responseToTheView(' ListaEmpresas');
	}

			/**
	 * @info Método para Obtener la posicion de la empresa
	 * @author Diego Acosta García
	 * @date May 2nd, 2020
	 */
	public function callWS_obtainNumPosition_Settings($dataRequest)
	{
		log_message('INFO', 'NOVO Settings Model: obtainNumPosition Method Initialized');
		$this->className = 'com.novo.objects.MO.ListadoEmpresasMO';
		$this->dataAccessLog->modulo = 'configuracion';
		$this->dataAccessLog->function = 'obtener-posicion';
		$this->dataAccessLog->operation = 'getInfoSelectConfig';
		$this->dataRequest->idOperation = 'getSelectXUsuario';

		$response = (array)$dataRequest;
		$this->response->code = 0;
		$user = $response;
		$this->response->data = $user;

		return $this->responseToTheView(' obtainNumPosition');
	}

		/**
	 * @info Método para el cambio de telefonos
	 * @author Diego Acosta García
	 * @date April 29th, 2020
	 */
	public function CallWs_ChangeTelephones_Settings($dataRequest)
	{

		log_message('INFO', 'NOVO Settings Model: ChangeTelephones Method Initialized');

		$this->className = 'com.novo.objects.TOs.EmpresaTO';
		$this->dataAccessLog->modulo = 'configuracion';
		$this->dataAccessLog->function = 'cambiar-telefono';
		$this->dataAccessLog->operation = 'getActualizarTLFEmpresa';

		$this->dataRequest->idOperation = 'getActualizarTLFEmpresa';
		$this->dataRequest->acrif = $dataRequest->acrif;
		$this->dataRequest->actel = $dataRequest->tlf1;
		$this->dataRequest->actel2 = $dataRequest->tlf2;
		$this->dataRequest->actel3 = $dataRequest->tlf3;

		$this->sendToService('ChangeTelephones');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->msg = lang('RESP_EMAIL_CHANGED');
				$this->response->icon = lang('GEN_ICON_SUCCESS');
				$this->response->data = [
					'btn1'=> [
						'text'=> lang('GEN_BTN_CONTINUE'),
						'link'=> 'inicio',
						'action'=> 'close'
					]
				];
				break;
			case -4:
				$this->response->code = 1;
				$this->response->msg = lang('RESP_EMAIL_USED');
				break;
			case -22:
				$this->response->code = 1;
				$this->response->msg = lang('RESP_EMAIL_INCORRECT');
				break;
		}

		if($this->isResponseRc != 0 && $this->response->code == 1) {
			$this->response->title = lang('GEN_EMAIL_CHANGE_TITLE');
			$this->response->icon = lang('GEN_ICON_WARNING');
			$this->response->data = [
				'btn1'=> [
					'action'=> 'close'
				]
			];
		}

		return $this->responseToTheView('ChangeTelephones');
	}

	/**
	 * @info Método para agregar contacto
	 * @author Diego Acosta García
	 * @date April 29th, 2020
	 */
	public function CallWs_AddContact_Settings($dataRequest)
	{

		log_message('INFO', 'NOVO Settings Model: AddContact Method Initialized');

		$this->className = 'com.novo.objects.TOs.ContactoTO';
		$this->dataAccessLog->modulo = 'configuracion';
		$this->dataAccessLog->function = 'agregar-contacto';
		$this->dataAccessLog->operation = 'insertarContactoEmpresa';

		$this->dataRequest->idOperation = 'insertarContactoEmpresa';
		$this->dataRequest->acrif = $dataRequest->acrif;
		$this->dataRequest->idExtPer = $dataRequest->idExtPer;
		$this->dataRequest->nombres = $dataRequest->nombres;
		$this->dataRequest->apellido = $dataRequest->apellido;
		$this->dataRequest->cargo = $dataRequest->cargo;
		$this->dataRequest->email = $dataRequest->email;
		$this->dataRequest->tipoContacto = $dataRequest->tipoContacto;
		$this->dataRequest->usuario = $dataRequest->usuario;

		$this->sendToService('AddContact');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->msg = lang('RESP_EMAIL_CHANGED');
				$this->response->icon = lang('GEN_ICON_SUCCESS');
				$this->response->data = [
					'btn1'=> [
						'text'=> lang('GEN_BTN_CONTINUE'),
						'link'=> 'inicio',
						'action'=> 'close'
					]
				];
				break;
			case -4:
				$this->response->code = 1;
				$this->response->msg = lang('RESP_EMAIL_USED');
				break;
			case -22:
				$this->response->code = 1;
				$this->response->msg = lang('RESP_EMAIL_INCORRECT');
				break;
		}

		if($this->isResponseRc != 0 && $this->response->code == 1) {
			$this->response->title = lang('GEN_EMAIL_CHANGE_TITLE');
			$this->response->icon = lang('GEN_ICON_WARNING');
			$this->response->data = [
				'btn1'=> [
					'action'=> 'close'
				]
			];
		}

		return $this->responseToTheView('AddContact');
	}

	/**
	 * @info Método para obtener archivo de configuración .ini
	 * @author Luis Molina
	 * @date Jun 07Sun, 2020
	 */
	public function CallWs_GetFileIni_Settings($dataRequest)
	{
		log_message('INFO', 'NOVO Setting Model: CallWs_GetFileIni Method Initialized');

		$this->className = 'ReporteCEOTO.class';
		$this->dataAccessLog->function = 'Listado de tarjetas';
		$this->dataAccessLog->operation = 'Descargar archivo';
		$this->dataAccessLog->modulo = 'Reportes';

		$this->dataRequest->idOperation = 216;
		$this->dataRequest->rutaArchivo = DOWNLOAD_ROUTE;

		$rif = count($this->session->userdata('enterpriseSelect')->list) > 1 ? $this->session->userdata('enterpriseInf')->idFiscal : $this->session->userdata('enterpriseSelect')->list[0]->acrif;
		$accodcia = count($this->session->userdata('enterpriseSelect')->list) > 1 ? $this->session->userdata('enterpriseInf')->enterpriseCode : $this->session->userdata('enterpriseSelect')->list[0]->accodcia;
		
		$this->dataRequest->empresaCliente = [
			'rif' => $rif,
			'accodcia' => $accodcia
		];

		$response = $this->sendToService('CallWs_GetFileIni: '.$this->dataRequest->idOperation);

		switch($this->isResponseRc) {
			case 0:
					$this->response->code = 0;
					$file = $response->archivo;
				    $name = $response->nombre;
				    $ext =  mb_strtolower($response->formatoArchivo);
					$this->response->data['file'] = $file;
					$this->response->data['name'] = $name.'.'.$ext;
					$this->response->data['ext'] = $ext;
			break;
		}

		return $this->responseToTheView('CallWs_GetFileIni: '.$this->dataRequest->idOperation);
	}
}
