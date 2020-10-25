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
	 * @modify J. Enrique Peñaloza Piñero
	 * @date July 28th, 2020
	 */
	public function CallWs_GetUser_Settings()
	{
		log_message('INFO', 'NOVO Settings Model: getUser Method Initialized');

		$this->dataAccessLog->modulo = 'Configuracion';
		$this->dataAccessLog->function = 'usuario';
		$this->dataAccessLog->operation = 'Obtener datos del usuario';

		$this->dataRequest->idOperation = 'getPerfilUsuario';
		$this->dataRequest->className = 'com.novo.objects.TOs.UsuarioTO';
		$this->dataRequest->idUsuario = $this->userName;

		$response = $this->sendToService('CallWs_GetUser');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$dataUser = new stdClass();
				$dataUser->userName = mb_strtoupper(trim($response->idUsuario));
				$dataUser->firstName = mb_strtoupper(trim($response->primerNombre));
				$dataUser->lastName = mb_strtoupper(trim($response->primerApellido));
				$dataUser->position = mb_strtoupper(trim($response->cargo));
				$dataUser->area = mb_strtoupper(trim($response->area));
				$dataUser->email = mb_strtoupper(trim($response->email));

				$this->response->data->dataUser = $dataUser;
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
			$this->response->icon = lang('CONF_ICON_WARNING');
			$this->response->modalBtn['btn1']['action'] = 'destroy';
		}

		return $this->responseToTheView('CallWs_GetUser');
	}
	/**
	 * @info Método para el cambio de Email
	 * @author Diego Acosta García
	 * @date April 29th, 2020
	 */
	public function CallWs_changeEmail_Settings($dataRequest)
	{
		log_message('INFO', 'NOVO Settings Model: ChangeEmail Method Initialized');

		$this->dataAccessLog->modulo = 'configuracion';
		$this->dataAccessLog->function = 'actualizar-usuario';
		$this->dataAccessLog->operation = 'getActualizarUsuario';

		$this->dataRequest->idOperation = 'getActualizarUsuario';
		$this->dataRequest->className = 'com.novo.objects.TOs.UsuarioTO';
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
				$this->response->icon = lang('CONF_ICON_SUCCESS');
				$this->response->modalBtn['btn1']['text'] = lang('GEN_BTN_CONTINUE');
				$this->response->modalBtn['btn1']['action'] = 'destroy';
			break;
		}

		if ($this->isResponseRc != 0) {
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

		$this->dataAccessLog->modulo = 'configuracion';
		$this->dataAccessLog->function = 'obtener empresa';
		$this->dataAccessLog->operation = 'getInfoEmpresaConfig';

		$this->dataRequest->idOperation = 'getEmpresaXUsuario';
		$this->dataRequest->className = 'com.novo.objects.MO.ListadoEmpresasMO';
		$this->dataRequest->accodusuario = $this->userName;

		$response = $this->sendToService('callWS_ListaEmpresas');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->msg = lang('RESP_EMAIL_CHANGED');
				$this->response->icon = lang('CONF_ICON_SUCCESS');
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
			$this->response->icon = lang('CONF_ICON_WARNING');
			$this->response->modalBtn['btn1']['action'] = 'destroy';
		}

		return $this->responseToTheView('callWS_ListaEmpresas');
	}
	/**
	 * @info Método para el cambio de telefonos
	 * @author Diego Acosta García
	 * @date April 29th, 2020
	 */
	public function CallWs_ChangeTelephones_Settings($dataRequest)
	{
		log_message('INFO', 'NOVO Settings Model: ChangeTelephones Method Initialized');


		$this->dataAccessLog->modulo = 'configuracion';
		$this->dataAccessLog->function = 'cambiar-telefono';
		$this->dataAccessLog->operation = 'getActualizarTLFEmpresa';

		$this->dataRequest->idOperation = 'getActualizarTLFEmpresa';
		$this->dataRequest->className = 'com.novo.objects.TOs.EmpresaTO';
		$this->dataRequest->acrif = $dataRequest->acrif;
		$this->dataRequest->actel = $dataRequest->tlf1;
		$this->dataRequest->actel2 = $dataRequest->tlf2;
		$this->dataRequest->actel3 = $dataRequest->tlf3;

		$this->sendToService('CallWs_ChangeTelephones');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->msg = lang('RESP_EMAIL_CHANGED');
				$this->response->icon = lang('CONF_ICON_SUCCESS');
				$this->response->modalBtn['btn1']['text'] = lang('GEN_BTN_CONTINUE');
				$this->response->modalBtn['btn1']['link']  = 'empresas';
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
			$this->response->icon = lang('CONF_ICON_WARNING');
			$this->response->modalBtn['btn1']['action'] = 'destroy';
		}

		return $this->responseToTheView('CallWs_ChangeTelephones');
	}
	/**
	 * @info Método para agregar contacto
	 * @author Diego Acosta García
	 * @date April 29th, 2020
	 */
	public function CallWs_AddContact_Settings($dataRequest)
	{
		log_message('INFO', 'NOVO Settings Model: AddContact Method Initialized');

		$this->dataAccessLog->modulo = 'configuracion';
		$this->dataAccessLog->function = 'agregar-contacto';
		$this->dataAccessLog->operation = 'insertarContactoEmpresa';

		$this->dataRequest->idOperation = 'insertarContactoEmpresa';
		$this->dataRequest->className = 'com.novo.objects.TOs.ContactoTO';
		$this->dataRequest->acrif = $dataRequest->acrif;
		$this->dataRequest->idExtPer = $dataRequest->idExtPer;
		$this->dataRequest->nombres = $dataRequest->nombres;
		$this->dataRequest->apellido = $dataRequest->apellido;
		$this->dataRequest->cargo = $dataRequest->cargo;
		$this->dataRequest->email = $dataRequest->email;
		$this->dataRequest->tipoContacto = $dataRequest->tipoContacto;
		$this->dataRequest->usuario = $dataRequest->usuario;

		$this->sendToService('CallWs_AddContact');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->msg = lang('RESP_EMAIL_CHANGED');
				$this->response->icon = lang('CONF_ICON_SUCCESS');
				$this->response->modalBtn['btn1']['text'] = lang('GEN_BTN_CONTINUE');
				$this->response->modalBtn['btn1']['action'] = 'destroy';
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
			$this->response->icon = lang('CONF_ICON_WARNING');
			$this->response->modalBtn['btn1']['action'] = 'destroy';
		}

		return $this->responseToTheView('CallWs_AddContact');
	}

	/**
	 * @info Método para obtener archivo de configuración .ini
	 * @author Luis Molina
	 * @date Jun 07Sun, 2020
	 */
	public function CallWs_GetFileIni_Settings($dataRequest)
	{
		log_message('INFO', 'NOVO Setting Model: CallWs_GetFileIni Method Initialized');

		$this->dataAccessLog->function = 'Listado de tarjetas';
		$this->dataAccessLog->operation = 'Descargar archivo';
		$this->dataAccessLog->modulo = 'Reportes';

		$this->dataRequest->idOperation = '216';
		$this->dataRequest->className = 'ReporteCEOTO.class';
		$idFiscal = $this->session->enterpriseSelect->list[0]->acrif;
		$enterpriseCode = $this->session->enterpriseSelect->list[0]->accodcia;

		if ($this->session->has_userdata('enterpriseInf')) {
			$idFiscal = $this->session->enterpriseInf->idFiscal;
			$enterpriseCode = $this->session->enterpriseInf->enterpriseCode;
		}

		$this->dataRequest->empresaCliente = [
			'rif' => $idFiscal,
			'accodcia' => $enterpriseCode
		];

		$response = $this->sendToService('CallWs_GetFileIni');

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
			default:
				$this->response->code = 4;
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->msg = lang('GEN_WARNING_DOWNLOAD_FILE');
				$this->response->modalBtn['btn1']['link']  = 'configuracion';
			break;
		}

		return $this->responseToTheView('CallWs_GetFileIni: '.$this->dataRequest->idOperation);
	}
}
