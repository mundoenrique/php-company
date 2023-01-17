<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Módelo para uso de funcionalidades en configuracion
 * @author Luis Molina
 * @date Jan 16th, 2023
 */
class Novo_Company_Model extends NOVO_Model {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO Enterprice Model Class Initialized');
	}

	/**
	 * @info Método para buscar contactos de la empresa
	 * @author Luis Molina
	 * @date Dec 06th, 2022
	 */
	public function CallWs_getContacts_Company($dataRequest)
	{
		log_message('INFO', 'NOVO Enterprice Model: getContacts Method Initialized');

		$this->dataAccessLog->modulo = 'Buscar contactos empresa';
		$this->dataAccessLog->function = 'Buscar contacto';
		$this->dataAccessLog->operation = 'Buscar';
		$this->dataRequest->idOperation = 'getContactosPorEmpresa';
		$this->dataRequest->className = 'com.novo.objects.MO.ListadoContactosMO';
		$this->dataRequest->lista = [["acrif" => $dataRequest->idEnterpriseList]];
		$this->dataRequest->paginar = false;
		$this->dataRequest->paginaActual = 0;
		$this->dataRequest->tamanoPagina = 1;

		$response = $this->sendToService('CallWs_getContacts');
		$contactsList = [];

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				foreach ($response->lista AS $key =>$contacts) {
					$record = new stdClass();
					$record->id = $key;
					$record->acrif = $contacts->acrif;
					$record->idExtPer = $contacts->idExtPer;
					$record->contactNames = $contacts->nombres;
					$record->contactLastNames = $contacts->apellido;
					$record->contactPosition = $contacts->cargo;
					$record->contactEmail = $contacts->email;
					$record->contactStatus = $contacts->estatus;
					$record->typeContactValue = $contacts->tipoContacto;
					foreach(lang('PRUE_ENTERPRICE_TYPE_CONTACT') as $key => $value){
						if($contacts->tipoContacto == $key){
							$record->typeContact = $value;
						}
					}
					array_push(
						$contactsList,
						$record
					);
				}
			break;
			case -150:
				$this->response->code = 1;
			break;
		}

		$this->response->data = $contactsList;
		return $this->responseToTheView('CallWs_getContacts');
	}

	/**
	 * @info Método para agregar contacto a la empresa
	 * @author Diego Acosta García
	 * @date April 29th, 2020
	 * @modified Luis Molina
	 * @date Jan 05th, 2023
	 */
	public function CallWs_addContact_Company($dataRequest)
	{
		log_message('INFO', 'NOVO Enterprice Model: AddContact Method Initialized');

		$this->dataAccessLog->modulo = 'Agregar contacto empresa';
		$this->dataAccessLog->function = 'Agregar contacto';
		$this->dataAccessLog->operation = 'Agregar';
		$this->dataRequest->idOperation = 'insertarContactoEmpresa';
		$this->dataRequest->className = 'com.novo.objects.TOs.ContactoTO';

		$this->dataRequest->acrif = $dataRequest->idFiscal;
		$this->dataRequest->idExtPer = $dataRequest->idExtPer;
		$this->dataRequest->nombres = $dataRequest->contactNames;
		$this->dataRequest->apellido = $dataRequest->contactLastNames;
		$this->dataRequest->cargo = $dataRequest->contactPosition;
		$this->dataRequest->email = $dataRequest->contactEmail;
		$this->dataRequest->tipoContacto = $dataRequest->contactType;

		$password = $this->cryptography->decryptOnlyOneData($dataRequest->pass);

		$this->dataRequest->usuario = [
			"userName" => $this->userName,
			"password" => md5($password)
		];

		$response = $this->sendToService('CallWs_addContact');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->msg = lang('GEN_ADD_CONTACT_SUCCESS');
				$this->response->icon = lang('CONF_ICON_SUCCESS');
				$this->response->modalBtn['btn1']['action'] = 'none';
			break;
			case -1:
				$this->response->msg = lang('GEN_PASSWORD_NO_VALID');
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->modalBtn['btn1']['action'] = 'destroy';
			break;
			case -163:
				$this->response->msg = lang('GEN_EXIST_CONTACT');
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->modalBtn['btn1']['action'] = 'destroy';
			break;
		}

		return $this->responseToTheView('CallWs_addContact');
	}

	/**
	 * @info Método para actualizar contacto existente
	 * @author Diego Acosta García
	 * @date April 20th, 2021
	 * @modified Luis Molina
	 * @date Dec 07th, 2022
	 */
	public function CallWs_updateContact_Company($dataRequest)
	{
		log_message('INFO', 'NOVO Enterprice Model: updateContact Method Initialized');

		$this->dataAccessLog->modulo = 'Modificar contacto empresa';
		$this->dataAccessLog->function = 'Modificar contacto';
		$this->dataAccessLog->operation = 'Modificar';
		$this->dataRequest->idOperation = 'updateContactoEmpresa';
		$this->dataRequest->className = 'com.novo.objects.TOs.ContactoTO';

		$this->dataRequest->acrif = $dataRequest->idFiscal;
		$this->dataRequest->idExtPer = $dataRequest->idExtPer;
		$this->dataRequest->nombres = $dataRequest->contactNames;
		$this->dataRequest->apellido = $dataRequest->contactLastNames;
		$this->dataRequest->cargo = $dataRequest->contactPosition;
		$this->dataRequest->email = $dataRequest->contactEmail;
		$this->dataRequest->tipoContacto = $dataRequest->contactType;

		$password = $this->cryptography->decryptOnlyOneData($dataRequest->pass);

		$this->dataRequest->usuario = [
			"userName" => $this->userName,
			"password" => md5($password)
		];

		$response = $this->sendToService('CallWs_updateContact');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->msg = lang('GEN_UPDATE_CONTACT_SUCCESS');
				$this->response->icon = lang('CONF_ICON_SUCCESS');
				$this->response->modalBtn['btn1']['action'] = 'none';
			break;
			case -1:
				$this->response->msg = lang('GEN_PASSWORD_NO_VALID');
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->modalBtn['btn1']['action'] = 'destroy';
			break;
		}

		return $this->responseToTheView('CallWs_updateContact');
	}

	/**
	 * @info Método para eliminar contacto
	 * @author Diego Acosta García
	 * @date April 20th, 2021
	 * @modified Luis Molina
	 * @date Dec 08th, 2022
	 */
	public function CallWs_deleteContact_Company($dataRequest)
	{
		log_message('INFO', 'NOVO Enterprice Model: deleteContact Method Initialized');

		$this->dataAccessLog->modulo = 'Eliminar contacto empresa';
		$this->dataAccessLog->function = 'Eliminar contacto';
		$this->dataAccessLog->operation = 'Eliminar';
		$this->dataRequest->idOperation = 'eliminarContactoEmpresa';
		$this->dataRequest->className = 'com.novo.objects.TOs.ContactoTO';

		$this->dataRequest->acrif = $dataRequest->idFiscal;
		$this->dataRequest->idExtPer = $dataRequest->idExtPer;

		$password = $this->cryptography->decryptOnlyOneData($dataRequest->pass);

		$this->dataRequest->usuario = [
			"userName" => $this->userName,
			"password" => md5($password)
		];

		$response = $this->sendToService('CallWs_deleteContact');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;
				$this->response->msg = lang('GEN_DELETE_CONTACT_SUCCESS');
				$this->response->icon = lang('CONF_ICON_SUCCESS');
				$this->response->modalBtn['btn1']['action'] = 'none';
			break;
			case -1:
				$this->response->msg = lang('GEN_PASSWORD_NO_VALID');
				$this->response->icon = lang('CONF_ICON_WARNING');
				$this->response->modalBtn['btn1']['action'] = 'destroy';
			break;
		}

		return $this->responseToTheView('CallWs_deleteContact');
	}

}
