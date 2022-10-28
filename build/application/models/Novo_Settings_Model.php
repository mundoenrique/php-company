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
				$this->response->msg = lang('GEN_USER_INCORRECT');
			break;
			case -22:
				$this->response->code = 1;
				$this->response->msg = lang('GEN_USER_INCORRECT');
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
				$this->response->msg = lang('GEN_EMAIL_CHANGED');
				$this->response->icon = lang('CONF_ICON_SUCCESS');
				$this->response->modalBtn['btn1']['text'] = lang('GEN_BTN_CONTINUE');
				$this->response->modalBtn['btn1']['action'] = 'destroy';
			break;
		}

		if ($this->isResponseRc != 0) {
			$this->response->code = 1;
			$branchOffice[] = (object) [
				'key' => '',
				'text' => lang('GEN_TRY_AGAIN')
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
				$this->response->msg = lang('GEN_EMAIL_CHANGED');
				$this->response->icon = lang('CONF_ICON_SUCCESS');
				$enter = $response;
				$this->response->data = $enter;
			break;
			case -4:
				$this->response->code = 1;
				$this->response->msg = lang('GEN_USER_INCORRECT');
			break;
			case -22:
				$this->response->code = 1;
				$this->response->msg = lang('GEN_USER_INCORRECT');
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
		$this->dataRequest->acrif  = $dataRequest->idFiscal;
		$this->dataRequest->actel  = $dataRequest->phone1 ?? '';
		$this->dataRequest->actel2 = $dataRequest->phone2 ?? '';
		$this->dataRequest->actel3 = $dataRequest->phone3 ?? '';

		$response = $this->sendToService('CallWs_ChangeTelephones');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 4;
				$this->response->msg = lang('GEN_PHONE_CHANGED');
				$this->response->icon = lang('CONF_ICON_SUCCESS');
				$this->response->modalBtn['btn1']['text'] = lang('GEN_BTN_CONTINUE');
				$this->response->modalBtn['btn1']['link']  = lang('CONF_LINK_ENTERPRISES');
			break;
		}

		return $this->responseToTheView('CallWs_ChangeTelephones');
	}

	/**
	 * @info Método para el cambio de dirección de las empresas
	 * @author Luis Molina
	 * @date sept 23, 2021
	 */
	public function CallWs_ChangeDataEnterprice_Settings($dataRequest)
	{
		log_message('INFO', 'NOVO Settings Model: ChangeDataEnterprice Method Initialized');

		$this->dataAccessLog->modulo = 'reportes';
		$this->dataAccessLog->function = 'updateDataEmpresaPBO';
		$this->dataAccessLog->operation = 'updateDataEmpresaPBO';

		$this->dataRequest->opcion = 'updateDataEmpresa';
		$this->dataRequest->idOperation = 'genericBusiness';
		$this->dataRequest->className = 'com.novo.business.parametros.bos.Opciones';
		$this->dataRequest->ruc  = $dataRequest->idFiscal;
		$this->dataRequest->direccion  = $dataRequest->address ?? '';
		$this->dataRequest->direccionFact = $dataRequest->billingAddress ?? '';

		$response = $this->sendToService('CallWs_ChangeDataEnterprice');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 4;
				$this->response->msg = lang('GEN_ADDRESS_ENTERPRICE_CHANGED');
				$this->response->icon = lang('CONF_ICON_SUCCESS');
				$this->response->modalBtn['btn1']['text'] = lang('GEN_BTN_CONTINUE');
				$this->response->modalBtn['btn1']['link']  = lang('CONF_LINK_ENTERPRISES');
			break;
		}

		return $this->responseToTheView('CallWs_ChangeTelephones');
	}
	/**
	 * @info Método para agregar contacto
	 * @author Diego Acosta García
	 * @date April 29th, 2020
	 */
	public function CallWs_addContact_Settings($dataRequest)
	{
		log_message('INFO', 'NOVO Settings Model: AddContact Method Initialized');

		$this->dataAccessLog->modulo = 'insertarContactoEmpresa';
		$this->dataAccessLog->function = 'insertarContactoEmpresa';
		$this->dataAccessLog->operation = 'insertarContactoEmpresa';

		$this->dataRequest->idOperation = 'insertarContactoEmpresa';
		$this->dataRequest->className = 'com.novo.objects.TOs.ContactoTO';
		$this->dataRequest->acrif = $dataRequest->acrif;
		$this->dataRequest->idExtPer = $dataRequest->nameNewContact;
		$this->dataRequest->nombres = $dataRequest->nameNewContact;
		$this->dataRequest->apellido = $dataRequest->surnameNewContact;
		$this->dataRequest->cargo = $dataRequest->positionNewContact;
		$this->dataRequest->email = $dataRequest->emailNewContact;
		$this->dataRequest->tipoContacto = $dataRequest->typeNewContact;

		$this->dataRequest->usuario = [
			[
				"userName" => $this->userName,
				"password" => $dataRequest->newContPass,
			]
		];

		$response = $this->sendToService('CallWs_addContact');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->msg = lang('GEN_EMAIL_CHANGED');
				$this->response->icon = lang('CONF_ICON_SUCCESS');
				$this->response->modalBtn['btn1']['text'] = lang('GEN_BTN_CONTINUE');
				$this->response->modalBtn['btn1']['action'] = 'destroy';
			break;
		}

		return $this->responseToTheView('CallWs_addContact');
	}

		/**
	 * @info Método para buscar contactos
	 * @author Diego Acosta García
	 * @date April 20th, 2021
	 */
	public function CallWs_getContacts_Settings($dataRequest)
	{
		log_message('INFO', 'NOVO Settings Model: getContacts Method Initialized');

		$this->dataAccessLog->modulo = 'getContactosPorEmpresa';
		$this->dataAccessLog->function = 'getContactosPorEmpresa';
		$this->dataAccessLog->operation = 'getContactosPorEmpresa';

		$this->dataRequest->idOperation = 'getContactosPorEmpresa';
		$this->dataRequest->className = 'com.novo.objects.MO.ListadoContactosMO';
		$this->dataRequest->lista = [["acrif" => $dataRequest->acrif]];
		$this->dataRequest->paginar = false;
		$this->dataRequest->paginaActual = 0;
		$this->dataRequest->tamanoPagina = 1;

		$response = $this->sendToService('CallWs_getContacts');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				foreach ($response->lista as $key => $value) {
				  ($response->lista[$key])->id = $key + 1;
				}
				$this->response->data = $response->lista;
				$this->response->diego = $this->session;
			break;
			default:
			$this->response->code = 1;
				break;
		}

		return $this->responseToTheView('CallWs_getContacts');
	}

			/**
	 * @info Método para eliminar contacto
	 * @author Diego Acosta García
	 * @date April 20th, 2021
	 */
	public function CallWs_deleteContact_Settings($dataRequest)
	{
		log_message('INFO', 'NOVO Settings Model: deleteContact Method Initialized');

		$this->dataAccessLog->modulo = 'eliminarContactoEmpresa';
		$this->dataAccessLog->function = 'eliminarContactoEmpresa';
		$this->dataAccessLog->operation = 'eliminarContactoEmpresa';

		$this->dataRequest->idOperation = 'eliminarContactoEmpresa';
		$this->dataRequest->className = 'com.novo.objects.TOs.ContactoTO';
		$this->dataRequest->usuario = [
			[
				"userName" => $this->userName,
				"password" => $dataRequest->pass,
			]
		];
		$this->dataRequest->acrif = $dataRequest->acrif;
		$this->dataRequest->idExtPer = $dataRequest->idExper;


		$response = $this->sendToService('CallWs_deleteContact');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->data = $response->lista;
			break;
			default:
			$this->response->code = 1;
				break;
		}

		return $this->responseToTheView('CallWs_deleteContact');
	}

			/**
	 * @info Método para actualizar contacto existente
	 * @author Diego Acosta García
	 * @date April 20th, 2021
	 */
	public function CallWs_updateContact_Settings($dataRequest)
	{
		log_message('INFO', 'NOVO Settings Model: updateContact Method Initialized');

		$this->dataAccessLog->modulo = 'updateContactoEmpresa';
		$this->dataAccessLog->function = 'updateContactoEmpresa';
		$this->dataAccessLog->operation = 'updateContactoEmpresa';

		$this->dataRequest->idOperation = 'updateContactoEmpresa';
		$this->dataRequest->className = 'com.novo.objects.TOs.ContactoTO';

		$this->dataRequest->usuario = [
			[
				"userName" => $this->userName,
				"password" => $dataRequest->modifyContactPass,
			]
		];

		$this->dataRequest->acrif = $dataRequest->acrif;
		$this->dataRequest->idExtPer = $dataRequest->dniModifyContact;
		$this->dataRequest->nombres = $dataRequest->nameModifyContact;
		$this->dataRequest->apellido = $dataRequest->surnameModifyContact;
		$this->dataRequest->cargo = $dataRequest->positionModifyContact;
		$this->dataRequest->email = $dataRequest->emailModifyContact;
		$this->dataRequest->tipoContacto = $dataRequest->typeModifyContact;


		$response = $this->sendToService('CallWs_updateContact');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->data = $response->lista;
			break;
			default:
			$this->response->code = 1;
				break;
		}

		return $this->responseToTheView('CallWs_updateContact');
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
				$this->response->modalBtn['btn1']['link']  = lang('CONF_LINK_SETTING');
			break;
		}

		return $this->responseToTheView('CallWs_GetFileIni: '.$this->dataRequest->idOperation);
	}

	/**
	 * @info Método para busqueda de sucursales
	 * @author Diego Acosta García
	 * @date May 20th, 2021
	 */
	/*public function CallWs_getBranches_Settings_old($dataRequest)
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
							$stateName = $response->paisTo->listaEstados[$key1]->estados;

								foreach ($response->paisTo->listaEstados[$key1]->listaCiudad as $key2 => $value2) {
									$cities = [$response->paisTo->listaEstados[$key1]->listaCiudad, $response->paisTo->listaEstados[$key1]->listaCiudad[$key2]->ciudad];
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
				$this->response->code = 7;
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
	}*/

	/**
	 * @info Método para búsqueda de sucursales refactorizado
	 * @author Luis Molina
	 * @date Jun 01th, 2022
	 */
	public function CallWs_getBranches_Settings($dataRequest){

		log_message('INFO', 'NOVO Settings Model: getBranches Method Initialized');

		$this->dataAccessLog->modulo = 'getConsultarSucursales';
		$this->dataAccessLog->function = 'getConsultarSucursales';
		$this->dataAccessLog->operation = 'getConsultarSucursales';

		$this->dataRequest->idOperation = 'getConsultarSucursales';
		$this->dataRequest->className = 'com.novo.objects.MO.ListadoSucursalesMO';
		$this->dataRequest->paginaActual = 1;
		$this->dataRequest->tamanoPagina = 10;
		$this->dataRequest->paginar = false;
		$this->dataRequest->lista = [
			[
				"rif" => $dataRequest->branchListBr
			]
		];
		$profile = 'S';
		$country = $this->customerUri;

		$response = $this->sendToService('CallWs_getBranches');
		$listBranches = [];

		switch ($this->isResponseRc) {
			case 0:
				$this->response->code = 0;

				foreach ($response->lista as $key =>$detailBranches) {
					$record = new stdClass();
					$record->rifB = $detailBranches->rif;
					$record->codB = $detailBranches->cod;
					$record->person = $detailBranches->persona;
					$record->userNameB = $detailBranches->usuario;
					$record->branchName = $detailBranches->nomb_cia;
					$record->branchCode = $detailBranches->codigo;
					$record->contact = $detailBranches->persona;
					$record->phone = $detailBranches->telefono;
					$record->zoneName = $detailBranches->zona;
					$record->address1 = $detailBranches->direccion_1;
					$record->address2 = $detailBranches->direccion_2;
					$record->address3 = $detailBranches->direccion_3;
					$record->areaCode = $detailBranches->cod_area;
					$record->countryCod = $detailBranches->codPais;
					$record->stateCod = $detailBranches->estado;
					$record->cityCod = $detailBranches->ciudad;
					$record->branchRow = $key;

					array_push(
						$listBranches,
						$record
					);
				};
				break;
				case -150:
					$this->response->code = 1;
				break;
		};

		$this->response->data = $listBranches;
		$this->response->paisTo = isset($response->paisTo) ? $response->paisTo : '';

		return $this->responseToTheView('CallWs_getBranches');
	}

	/**
	 * @info Método para guardar sucursal refactorizado
	 * @author Luis Molina
	 * @date JUn 06th, 2022
	 */
	public function CallWs_addBranch_Settings($dataRequest)
	{
		log_message('INFO', 'NOVO Settings Model: addBranch Method Initialized');

		$this->dataAccessLog->modulo = 'getAgregarSucursales';
		$this->dataAccessLog->function = 'getAgregarSucursales';
		$this->dataAccessLog->operation = 'getAgregarSucursales';
		$this->dataRequest->idOperation = 'getAgregarSucursales';
		$this->dataRequest->className = 'com.novo.objects.TOs.SucursalTO';

		$this->dataRequest->rif = $dataRequest->rif;
		$this->dataRequest->codigo = $dataRequest->branchCode;
		$this->dataRequest->nomb_cia = $dataRequest->branchName;
		$this->dataRequest->direccion_1 = $dataRequest->address1;
		$this->dataRequest->direccion_2 = $dataRequest->address2;
		$this->dataRequest->direccion_3 = $dataRequest->address3;
		$this->dataRequest->zona = $dataRequest->zoneName;
		$this->dataRequest->codPais = $dataRequest->countryCodBranch;
		$this->dataRequest->estado = $dataRequest->stateCodBranch;
		$this->dataRequest->ciudad = $dataRequest->cityCodBranch;
		$this->dataRequest->persona = $dataRequest->person;
		$this->dataRequest->cod_area = $dataRequest->areaCode;
		$this->dataRequest->telefono = $dataRequest->phone;
		$this->dataRequest->costoDistribucion = '0';
		$this->dataRequest->costoUnitDistribucion = '0';
		$this->dataRequest->costoMinimo = '0';
		$this->dataRequest->costoDistribRep= '0';

		$password = isset($dataRequest->pass) ? $this->cryptography->decryptOnlyOneData($dataRequest->pass) : $this->session->passWord;

		$this->dataRequest->password = $password;

		//$response = $this->sendToService('CallWs_addBranch');
		$this->isResponseRc=0;
		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->icon =  lang('CONF_ICON_SUCCESS');
				$this->response->msg = 'Sucursal cargada con exito';
				$this->response->modalBtn['btn1']['action'] = 'none';
			break;
		}

		return $this->responseToTheView('CallWs_addBranch');
	}

	/**
	 * @info Método para actualizar sucursal
	 * @author Diego Acosta García
	 * @date May 29th, 2021
	 * @info Actualizado por Luis Molina
	 * @date Oct 17th, 2022
	 */
	public function CallWs_updateBranch_Settings($dataRequest)
	{
		log_message('INFO', 'NOVO Settings Model: updateBranch Method Initialized');

		$this->dataAccessLog->modulo = 'getActualizarSucursal';
		$this->dataAccessLog->function = 'getActualizarSucursal';
		$this->dataAccessLog->operation = 'getActualizarSucursal';
		$this->dataRequest->idOperation = 'getActualizarSucursal';
		$this->dataRequest->className = 'com.novo.objects.TOs.SucursalTO';

		$this->dataRequest->rif = $dataRequest->rif;
		$this->dataRequest->cod = $dataRequest->codB;
		$this->dataRequest->nom_cia = $dataRequest->branchName;
		$this->dataRequest->direccion_1 = $dataRequest->address1;
		$this->dataRequest->direccion_2 = $dataRequest->address2;
		$this->dataRequest->direccion_3 = $dataRequest->address3;
		$this->dataRequest->zona = $dataRequest->branchCode;
		$this->dataRequest->codPais = $dataRequest->countryCodBranch;
		$this->dataRequest->estado = $dataRequest->stateCodBranch;
		$this->dataRequest->ciudad = $dataRequest->cityCodBranch;
		$this->dataRequest->persona = $dataRequest->person;
		$this->dataRequest->cod_area = $dataRequest->areaCode;
		$this->dataRequest->telefono = $dataRequest->phone;
		$this->dataRequest->usuario = $dataRequest->userNameB;

		$password = isset($dataRequest->pass) ? $this->cryptography->decryptOnlyOneData($dataRequest->pass) : $this->session->passWord;

		if (lang('CONF_HASH_PASS') == 'ON' && $this->singleSession == 'signIn') {
			$password = $this->session->pass ?: md5($password);
		}

		$this->dataRequest->password = $password;

		//$response = $this->sendToService('CallWs_updateBranch');
		$this->isResponseRc=0;
		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->icon =  lang('CONF_ICON_SUCCESS');
				$this->response->msg = 'Sucursal Actualizada con exito';
				$this->response->modalBtn['btn1']['action'] = 'none';
			break;
		}

		return $this->responseToTheView('CallWs_updateBranch');
	}

	/**
	 * @info Método para subir archivo de sucursales
	 * @author Luis Molina
	 * @date Oct 26th, 2022
	 */
	public function CallWs_UploadFileBranch_Settings($dataRequest)
	{
		log_message('INFO', 'NOVO UploadFileBranch Model: UploadFileBranch Method Initialized');

		//$this->sendFile($dataRequest->fileName, 'UploadFileBranches');

		$this->isResponseRc = 0;

		if ($this->isResponseRc === 0) {
			$this->dataAccessLog->modulo = 'getSucursalTxt';
			$this->dataAccessLog->function = 'getSucursalTxt';
			$this->dataAccessLog->operation = 'getSucursalTxt';

			$this->dataRequest->idOperation = 'getSucursalTxt';
			$this->dataRequest->className = 'com.novo.objects.TOs.SucursalTO';

			$this->dataRequest->data = [
				"pais" => $this->session->userdata('pais'),
				"idOperation" => $this->dataRequest->idOperation,
				"className" => $this->dataRequest->className,
				"rif"=> $dataRequest->rif,
				"url"=>$dataRequest->fileName,
				"idTipoLote"=>"7",
				"usuario"=> $this->session->userdata('userName'),
				"logAccesoObject" => $this->dataAccessLog,
				"token" => $this->session->userdata('token'),
			];

			$response = $this->sendToService('CallWs_UploadFileBranch');

			switch ($this->isResponseRc) {
				case 0:
					$this->response->code = 0;
					$this->response->icon =  lang('CONF_ICON_SUCCESS');
					$this->response->msg = 'Sucursales Cargadas con exito';
					$this->response->modalBtn['btn1']['action'] = 'destroy';
			}

		} else {
			$this->response->code = 2;
			$this->response->icon = lang('CONF_ICON_WARNING');
			$this->response->msg = lang('BULK_FILE_NO_MOVE');
			$this->response->modalBtn['btn1']['action'] = 'destroy';
		}

		return $this->responseToTheView('UploadFileBranches');
	}

}
