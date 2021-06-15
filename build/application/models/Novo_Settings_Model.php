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
				$this->response->code = 4;
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
				$this->response->modalBtn['btn1']['link']  = lang('CONF_LINK_ENTERPRISES');
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

		$response = $this->sendToService('CallWs_AddContact');

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
				$this->response->data->file = $file;
				$this->response->data->name = $name.'.'.$ext;
				$this->response->data->ext = $ext;
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

	/**
	 * @info Método para busqueda de sucursales
	 * @author Diego Acosta García
	 * @date May 20th, 2021
	 */
	public function CallWs_getBranches_Settings($dataRequest)
	{
		log_message('INFO', 'NOVO Settings Model: getBranches Method Initialized');

		$this->dataAccessLog->modulo = 'getConsultarSucursales';
		$this->dataAccessLog->function = 'getConsultarSucursales';
		$this->dataAccessLog->operation = 'getConsultarSucursales';
		$this->dataRequest->lista = [
			[
				"rif" => $dataRequest->branchListBr
			]
		];
		$this->dataRequest->paginaActual = 1;
		$this->dataRequest->tamanoPagina = 10;
		$this->dataRequest->paginar = false;
		$this->dataRequest->idOperation = 'getConsultarSucursales';
		$this->dataRequest->className = 'com.novo.objects.MO.ListadoSucursalesMO';
		$profile = 'S';
		$country = $this->customerUri;

		$response = $this->sendToService('CallWs_getBranches');

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$infoBranches = $response->lista;
				$tableBranches = [];

				foreach ($infoBranches as $key0 => $value0) {

					$geographic[$key0] = [
						"state" => $infoBranches[$key0]->estado,
						"city"=> $infoBranches[$key0]->ciudad
					];

					$tableBranches[$key0] = [
						"id" => $key0,
						"rifB" => $infoBranches[$key0]->rif,
						"codB" => $infoBranches[$key0]->cod,
						"person" => $infoBranches[$key0]->persona,
						"userNameB" => $infoBranches[$key0]->usuario,
						"branchName" =>$infoBranches[$key0]->nomb_cia,
						"branchCode" =>$infoBranches[$key0]->codigo,
						"contact" =>$infoBranches[$key0]->persona,
						"phone" =>$infoBranches[$key0]->telefono,
						"zoneName" =>$infoBranches[$key0]->zona,
						"address1" =>$infoBranches[$key0]->direccion_1,
						"address2" =>$infoBranches[$key0]->direccion_2,
						"address3" =>$infoBranches[$key0]->direccion_3,
						"areaCode" =>$infoBranches[$key0]->cod_area
					];

					if (array_key_exists("paisTo", $response)) {
						$country = $response->paisTo->pais;

						foreach ($response->paisTo->listaEstados as $key1 => $value1) {
							if( $response->paisTo->listaEstados[$key1]->codEstado == $infoBranches[$key0]->estado){
								$stateName = $response->paisTo->listaEstados[$key1]->estados;

								foreach ($response->paisTo->listaEstados[$key1]->listaCiudad as $key2 => $value2) {
									$cities = [$response->paisTo->listaEstados[$key1]->listaCiudad, $response->paisTo->listaEstados[$key1]->listaCiudad[$key2]->ciudad];
								};
							};
						};

						foreach ($cities[0] as $key => $value) {
							if (array_key_exists('listaDistrito', ($cities[0][$key]))) {
								$profile = 'L';
								$districts[$key] = $cities[0][$key]->ListaDistrito;
							};
						};
					};

					$this->response->country = [
						"countryCodeBranch" =>  $infoBranches[0]->codPais,
						"countryNameBranch" =>  $country,
						"statesList" => $response->paisTo->listaEstados
					];
				};

				$this->response->data = $tableBranches;
				$this->response->geoUserData = $geographic;
				$this->response->geoInfo = $response->paisTo;
				$this->response->longProfile = $profile;
				$this->response->infoBranches = $stateName;

				break;
			case -150:
				$this->response->code = 1;
				$this->response->geoInfo = $response->paisTo;
				$this->response->longProfile = $profile;
				$this->response->country = [
					"countryCodeBranch" =>  $response->paisTo->codPais,
					"countryNameBranch" =>  $country,
				];
				break;
			default:
				$this->response->code = 2;
				break;
		};

		return $this->responseToTheView('CallWs_getBranches');
	}

	/**
	 * @info Método para subir archivo de sucursales
	 * @author Diego Acosta García
	 * @date May 28th, 2021
	 */
	public function CallWs_uploadFileBranches_Settings($dataRequest)
	{
		log_message('INFO', 'NOVO Settings Model: uploadFileBranches Method Initialized');

		$this->dataAccessLog->modulo = 'getSucursalTxt';
		$this->dataAccessLog->function = 'getSucursalTxt';
		$this->dataAccessLog->operation = 'getSucursalTxt';
		$this->dataRequest->idOperation = 'getSucursalTxt';
		$this->dataRequest->className = 'com.novo.objects.MO.ListadoSucursalesMO';
		$this->dataRequest->file = $dataRequest->file;
		$this->isResponseRc = 0;

		switch($this->isResponseRc ) {
			case 0:
				$this->response->code = 0;
				$this->response->data = $dataRequest->file;
			break;
		}

		return $this->responseToTheView('CallWs_uploadFileBranches');
	}

	/**
	 * @info Método para actualizar sucursal
	 * @author Diego Acosta García
	 * @date May 29th, 2021
	 */
	public function CallWs_updateBranch_Settings($dataRequest)
	{
		log_message('INFO', 'NOVO Settings Model: updateBranch Method Initialized');

		$this->dataAccessLog->modulo = 'getActualizarSucursal';
		$this->dataAccessLog->function = 'getActualizarSucursal';
		$this->dataAccessLog->operation = 'getActualizarSucursal';
		$this->dataRequest->idOperation = 'getActualizarSucursal';
		$this->dataRequest->className = 'com.novo.objects.TOs.SucursalTO';

		$this->dataRequest->rif = $dataRequest->rifB;
		$this->dataRequest->cod = $dataRequest->codB;
		$this->dataRequest->nom_cia = $dataRequest->branchName;
		$this->dataRequest->direccion_1 = $dataRequest->address1;
		$this->dataRequest->direccion_2 = $dataRequest->address2;
		$this->dataRequest->direccion_3 = $dataRequest->address3;
		$this->dataRequest->zona = $dataRequest->branchCode;
		$this->dataRequest->codPais = $dataRequest->countryCodeBranch;
		$this->dataRequest->estado = $dataRequest->stateCodeBranch;
		$this->dataRequest->ciudad = $dataRequest->cityCodeBranch;
		$this->dataRequest->persona = $dataRequest->person;
		$this->dataRequest->cod_area = $dataRequest->areaCode;
		$this->dataRequest->telefono = $dataRequest->phone;
		$this->dataRequest->usuario = $dataRequest->userNameB;

		$password = isset($dataRequest->pass) ? $this->cryptography->decryptOnlyOneData($dataRequest->pass) : $this->session->passWord;

		if (lang('CONF_HASH_PASS') == 'ON' && $this->singleSession == 'signIn') {
			$password = $this->session->pass ?: md5($password);
		}

		$this->dataRequest->password = $password;

		$response = $this->sendToService('CallWs_updateBranch');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
			break;
		}

		return $this->responseToTheView('CallWs_updateBranch');
	}
}
