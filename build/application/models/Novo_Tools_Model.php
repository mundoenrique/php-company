<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @info Módelo para uso de funcionalidades en configuracion
 * @author Diego Acosta García
 * @date May 12th, 2020
 */
class Novo_Tools_Model extends NOVO_Model
{

  public function __construct()
  {
    parent::__construct();
    writeLog('INFO', 'Tools Model Class Initialized');
  }

  /**
   * @info Método para Obtener los datos del usuario
   * @author Diego Acosta García
   * @date April 29th, 2020
   * @modify J. Enrique Peñaloza Piñero
   * @date July 28th, 2020
   */
  public function CallWs_GetUser_Tools()
  {
    writeLog('INFO', 'Tools Model: getUser Method Initialized');

    $this->dataAccessLog->modulo = 'Configuracion';
    $this->dataAccessLog->function = 'usuario';
    $this->dataAccessLog->operation = 'Obtener datos del usuario';

    $this->dataRequest->idOperation = 'getPerfilUsuario';
    $this->dataRequest->className = 'com.novo.objects.TOs.UsuarioTO';
    $this->dataRequest->idUsuario = $this->userName;

    $response = $this->sendToWebServices('CallWs_GetUser');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $dataUser = new stdClass();
        $dataUser->userName = manageString($response->idUsuario, 'upper', 'none');
        $dataUser->firstName = manageString($response->primerNombre, 'upper', 'none');
        $dataUser->lastName = manageString($response->primerApellido, 'upper', 'none');
        $dataUser->position = manageString($response->cargo, 'upper', 'none');
        $dataUser->area = manageString($response->area, 'upper', 'none');
        $dataUser->email = manageString($response->email, 'upper', 'none');

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

    if ($this->isResponseRc != 0 && $this->response->code == 1) {
      $this->response->title = lang('GEN_USER_TITLE');
      $this->response->icon = lang('SETT_ICON_WARNING');
      $this->response->modalBtn['btn1']['action'] = 'destroy';
    }

    return $this->responseToTheView('CallWs_GetUser');
  }
  /**
   * @info Método para el cambio de Email
   * @author Diego Acosta García
   * @date April 29th, 2020
   */
  public function CallWs_changeEmail_Tools($dataRequest)
  {
    writeLog('INFO', 'Tools Model: ChangeEmail Method Initialized');

    $this->dataAccessLog->modulo = 'configuracion';
    $this->dataAccessLog->function = 'actualizar-usuario';
    $this->dataAccessLog->operation = 'getActualizarUsuario';

    $this->dataRequest->idOperation = 'getActualizarUsuario';
    $this->dataRequest->className = 'com.novo.objects.TOs.UsuarioTO';
    $this->dataRequest->idUsuario = $this->session->userdata('userName');
    $this->dataRequest->email = $dataRequest->email;

    if (!$dataRequest) {
      $access = [
        'user_access',
      ];
      $this->session->unset_userdata($access);
    }

    $this->sendToWebServices('CallWs_ChangeEmail');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 4;
        $this->response->msg = lang('GEN_EMAIL_CHANGED');
        $this->response->icon = lang('SETT_ICON_SUCCESS');
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
  public function callWS_ListaEmpresas_Tools()
  {
    writeLog('INFO', 'Tools Model: ListaEmpresas Method Initialized');

    $this->dataAccessLog->modulo = 'configuracion';
    $this->dataAccessLog->function = 'obtener empresa';
    $this->dataAccessLog->operation = 'getInfoEmpresaConfig';

    $this->dataRequest->idOperation = 'getEmpresaXUsuario';
    $this->dataRequest->className = 'com.novo.objects.MO.ListadoEmpresasMO';
    $this->dataRequest->accodusuario = $this->userName;

    $response = $this->sendToWebServices('callWS_ListaEmpresas');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $this->response->msg = lang('GEN_EMAIL_CHANGED');
        $this->response->icon = lang('SETT_ICON_SUCCESS');
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

    if ($this->isResponseRc != 0 && $this->response->code == 1) {
      $this->response->title = lang('GEN_USER_TITLE');
      $this->response->icon = lang('SETT_ICON_WARNING');
      $this->response->modalBtn['btn1']['action'] = 'destroy';
    }

    return $this->responseToTheView('callWS_ListaEmpresas');
  }
  /**
   * @info Método para el cambio de telefonos
   * @author Diego Acosta García
   * @date April 29th, 2020
   */
  public function CallWs_ChangeTelephones_Tools($dataRequest)
  {
    writeLog('INFO', 'Tools Model: ChangeTelephones Method Initialized');

    $this->dataAccessLog->modulo = 'Configuracion';
    $this->dataAccessLog->function = 'Datos de la empresa';
    $this->dataAccessLog->operation = 'Actulizar telefonos';

    $this->dataRequest->idOperation = 'getActualizarTLFEmpresa';
    $this->dataRequest->className = 'com.novo.objects.TOs.EmpresaTO';
    $this->dataRequest->acrif  = $dataRequest->idFiscal;
    $this->dataRequest->actel  = $dataRequest->phone1 ?? '';
    $this->dataRequest->actel2 = $dataRequest->phone2 ?? '';
    $this->dataRequest->actel3 = $dataRequest->phone3 ?? '';

    $response = $this->sendToWebServices('CallWs_ChangeTelephones');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 4;
        $this->response->msg = lang('GEN_PHONE_CHANGED');
        $this->response->icon = lang('SETT_ICON_SUCCESS');
        $this->response->modalBtn['btn1']['text'] = lang('GEN_BTN_CONTINUE');
        $this->response->modalBtn['btn1']['link']  = lang('SETT_LINK_ENTERPRISES');
        break;
    }

    return $this->responseToTheView('CallWs_ChangeTelephones');
  }

  /**
   * @info Método para el cambio de dirección de las empresas
   * @author Luis Molina
   * @date sept 23, 2021
   */
  public function CallWs_ChangeDataEnterprice_Tools($dataRequest)
  {
    writeLog('INFO', 'Tools Model: ChangeDataEnterprice Method Initialized');

    $this->dataAccessLog->modulo = 'reportes';
    $this->dataAccessLog->function = 'updateDataEmpresaPBO';
    $this->dataAccessLog->operation = 'updateDataEmpresaPBO';

    $this->dataRequest->opcion = 'updateDataEmpresa';
    $this->dataRequest->idOperation = 'genericBusiness';
    $this->dataRequest->className = 'com.novo.business.parametros.bos.Opciones';
    $this->dataRequest->ruc  = $dataRequest->idFiscal;
    $this->dataRequest->direccion  = $dataRequest->address ?? '';
    $this->dataRequest->direccionFact = $dataRequest->billingAddress ?? '';

    $response = $this->sendToWebServices('CallWs_ChangeDataEnterprice');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 4;
        $this->response->msg = lang('GEN_ADDRESS_ENTERPRICE_CHANGED');
        $this->response->icon = lang('SETT_ICON_SUCCESS');
        $this->response->modalBtn['btn1']['text'] = lang('GEN_BTN_CONTINUE');
        $this->response->modalBtn['btn1']['link']  = lang('SETT_LINK_ENTERPRISES');
        break;
    }

    return $this->responseToTheView('CallWs_ChangeTelephones');
  }

  /**
   * @info Método para obtener archivo de configuración .ini
   * @author Luis Molina
   * @date Jun 07Sun, 2020
   */
  public function CallWs_GetFileIni_Tools($dataRequest)
  {
    writeLog('INFO', 'Setting Model: CallWs_GetFileIni Method Initialized');

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

    $response = $this->sendToWebServices('CallWs_GetFileIni');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $file = $response->archivo;
        $name = $response->nombre;
        $ext =  mb_strtolower($response->formatoArchivo);
        $this->response->data->file = $file;
        $this->response->data->name = $name . '.' . $ext;
        $this->response->data->ext = $ext;
        break;
      default:
        $this->response->code = 4;
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('GEN_WARNING_DOWNLOAD_FILE');
        $this->response->modalBtn['btn1']['link']  = lang('SETT_LINK_SETTING');
        break;
    }

    return $this->responseToTheView('CallWs_GetFileIni: ' . $this->dataRequest->idOperation);
  }


  /**
   * @info Método para buscar contactos de la empresa
   * @author Luis Molina
   * @date Dec 06th, 2022
   */
  public function CallWs_getContacts_Tools($dataRequest)
  {
    writeLog('INFO', 'Tools Model: getContacts Method Initialized');

    $this->dataAccessLog->modulo = 'Buscar contactos empresa';
    $this->dataAccessLog->function = 'Buscar contacto';
    $this->dataAccessLog->operation = 'Buscar';
    $this->dataRequest->idOperation = 'getContactosPorEmpresa';
    $this->dataRequest->className = 'com.novo.objects.MO.ListadoContactosMO';
    $this->dataRequest->lista = [["acrif" => $dataRequest->idEnterpriseList]];
    $this->dataRequest->paginar = false;
    $this->dataRequest->paginaActual = 0;
    $this->dataRequest->tamanoPagina = 1;

    $response = $this->sendToWebServices('CallWs_getContacts');
    $contactsList = [];
    //$this->isResponseRc = -150;

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        foreach ($response->lista as $key => $contacts) {
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
          if ($contacts->tipoContacto != '') {
            foreach (lang('PRUE_ENTERPRICE_TYPE_CONTACT') as $key => $value) {
              if ($contacts->tipoContacto == $key) {
                $record->typeContact = $value;
              }
            }
          } else {
            $record->typeContact = '';
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
  public function CallWs_addContact_Tools($dataRequest)
  {
    writeLog('INFO', 'Tools Model: AddContact Method Initialized');

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

    $response = $this->sendToWebServices('CallWs_addContact');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $this->response->msg = lang('GEN_ADD_CONTACT_SUCCESS');
        $this->response->icon = lang('SETT_ICON_SUCCESS');
        $this->response->modalBtn['btn1']['action'] = 'none';
        break;
      case -1:
        $this->response->msg = lang('GEN_PASSWORD_NO_VALID');
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -163:
        $this->response->msg = lang('GEN_EXIST_CONTACT');
        $this->response->icon = lang('SETT_ICON_WARNING');
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
  public function CallWs_updateContact_Tools($dataRequest)
  {
    writeLog('INFO', 'Tools Model: updateContact Method Initialized');

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

    $response = $this->sendToWebServices('CallWs_updateContact');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $this->response->msg = lang('GEN_UPDATE_CONTACT_SUCCESS');
        $this->response->icon = lang('SETT_ICON_SUCCESS');
        $this->response->modalBtn['btn1']['action'] = 'none';
        break;
      case -1:
        $this->response->msg = lang('GEN_PASSWORD_NO_VALID');
        $this->response->icon = lang('SETT_ICON_WARNING');
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
  public function CallWs_deleteContact_Tools($dataRequest)
  {
    writeLog('INFO', 'Tools Model: deleteContact Method Initialized');

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

    $response = $this->sendToWebServices('CallWs_deleteContact');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $this->response->msg = lang('GEN_DELETE_CONTACT_SUCCESS');
        $this->response->icon = lang('SETT_ICON_SUCCESS');
        $this->response->modalBtn['btn1']['action'] = 'none';
        break;
      case -1:
        $this->response->msg = lang('GEN_PASSWORD_NO_VALID');
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    return $this->responseToTheView('CallWs_deleteContact');
  }

  /**
   * @info Método para búsqueda de sucursales refactorizado
   * @author Luis Molina
   * @date Jun 01th, 2022
   */
  public function CallWs_getBranches_Tools($dataRequest)
  {

    writeLog('INFO', 'Tools Model: getBranches Method Initialized');

    $this->dataAccessLog->modulo = 'Sucursales';
    $this->dataAccessLog->function = 'Búsqueda de sucursales';
    $this->dataAccessLog->operation = 'Buscar sucursales';

    $this->dataRequest->idOperation = 'getConsultarSucursales';
    $this->dataRequest->className = 'com.novo.objects.MO.ListadoSucursalesMO';
    $this->dataRequest->paginaActual = 1;
    $this->dataRequest->tamanoPagina = 10;
    $this->dataRequest->paginar = false;
    $this->dataRequest->lista = [
      [
        "rif" => $dataRequest->idFiscalList
      ]
    ];

    $response = $this->sendToWebServices('CallWs_getBranches');
    $branchesList = [];

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;

        foreach ($response->lista as $key => $detailBranches) {
          $record = [];
          $record['codeUpdate'] = $detailBranches->cod;
          $record['person'] = $detailBranches->persona;
          $record['userName'] = $detailBranches->usuario;
          $record['branchName'] = $detailBranches->nomb_cia;
          $record['branchCode'] = $detailBranches->codigo;
          $record['contact'] = $detailBranches->persona;
          $record['phone'] = $detailBranches->telefono;
          $record['zoneName'] = $detailBranches->zona;
          $record['address1'] = $detailBranches->direccion_1;
          $record['address2'] = $detailBranches->direccion_2;
          $record['address3'] = $detailBranches->direccion_3;
          $record['areaCode'] = $detailBranches->cod_area;
          $record['countryCod'] = $detailBranches->codPais;
          $record['stateCod'] = $detailBranches->estado;
          $record['cityCod'] = $detailBranches->ciudad;
          $record['branchRow'] = $key;

          array_push(
            $branchesList,
            $record
          );
        };
        break;
      case -150:
        $this->response->code = 1;
        break;
    };

    $this->response->data->branchesList = $branchesList;
    $this->response->data->regionsList = isset($response->paisTo) ? $response->paisTo : '';

    return $this->responseToTheView('CallWs_getBranches');
  }

  /**
   * @info Método para guardar sucursal refactorizado
   * @author Luis Molina
   * @date JUn 06th, 2022
   */
  public function CallWs_addBranche_Tools($dataRequest)
  {
    writeLog('INFO', 'Tools Model: addBranche Method Initialized');

    $this->dataAccessLog->modulo = 'Sucursales';
    $this->dataAccessLog->function = 'Agregar Sucursales';
    $this->dataAccessLog->operation = 'Agregar';

    $password = isset($dataRequest->pass) ? $this->cryptography->decryptOnlyOneData($dataRequest->pass) : $this->session->passWord;

    if (getSignSessionType() === lang('SETT_COOKIE_SINGN_IN')) {
      $password = $this->session->passWord ?: md5($password);
    }

    $this->dataRequest->idOperation = 'getAgregarSucursales';
    $this->dataRequest->className = 'com.novo.objects.TOs.SucursalTO';
    $this->dataRequest->rif = $dataRequest->idFiscal;
    $this->dataRequest->codigo = $dataRequest->branchCode;
    $this->dataRequest->nomb_cia = $dataRequest->branchName;
    $this->dataRequest->direccion_1 = $dataRequest->address1 ?? '';
    $this->dataRequest->direccion_2 = $dataRequest->address2 ?? '';
    $this->dataRequest->direccion_3 = $dataRequest->address3 ?? '';
    $this->dataRequest->zona = $dataRequest->zoneName ?? '';
    $this->dataRequest->codPais = $dataRequest->countryCod;
    $this->dataRequest->estado = $dataRequest->stateCodBranch;
    $this->dataRequest->ciudad = $dataRequest->cityCodBranch;
    $this->dataRequest->persona = $dataRequest->person;
    $this->dataRequest->cod_area = $dataRequest->areaCode;
    $this->dataRequest->telefono = $dataRequest->phone;
    $this->dataRequest->costoDistribucion = '0';
    $this->dataRequest->costoUnitDistribucion = '0';
    $this->dataRequest->costoMinimo = '0';
    $this->dataRequest->costoDistribRep = '0';
    $this->dataRequest->usuario = $this->userName;
    $this->dataRequest->password = $password;

    $response = $this->sendToWebServices('CallWs_addBranche');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $this->response->icon =  lang('SETT_ICON_SUCCESS');
        $this->response->title = lang('GEN_BRANC_OFFICES');
        $this->response->msg = lang('TOOLS_BRANCH_ADD');
        $this->response->modalBtn['btn1']['action'] = 'none';
        break;
      case -1:
        $this->response->code = 4;
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('GEN_PASSWORD_NO_VALID');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
      case -168:
        $this->response->code = 4;
        $this->response->icon = lang('SETT_ICON_INFO');
        $this->response->msg = lang('TOOLS_BRANCH_EXISTS');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    return $this->responseToTheView('CallWs_addBranche');
  }

  /**
   * @info Método para actualizar sucursal
   * @author Diego Acosta García
   * @date May 29th, 2021
   * @info Actualizado por Luis Molina
   * @date Oct 17th, 2022
   */
  public function CallWs_updateBranche_Tools($dataRequest)
  {
    writeLog('INFO', 'Tools Model: updateBranche Method Initialized');

    $this->dataAccessLog->modulo = 'Sucursales';
    $this->dataAccessLog->function = 'Actualizar Sucursales';
    $this->dataAccessLog->operation = 'Actualizar';

    $password = isset($dataRequest->pass) ? $this->cryptography->decryptOnlyOneData($dataRequest->pass) : $this->session->passWord;

    if (getSignSessionType() === lang('SETT_COOKIE_SINGN_IN')) {
      $password = $this->session->passWord ?: md5($password);
    }

    $this->dataRequest->idOperation = 'getActualizarSucursal';
    $this->dataRequest->className = 'com.novo.objects.TOs.SucursalTO';
    $this->dataRequest->rif = $dataRequest->idFiscal;
    $this->dataRequest->cod = $dataRequest->codeUpdate;
    $this->dataRequest->nomb_cia = $dataRequest->branchName;
    $this->dataRequest->direccion_1 = $dataRequest->address1 ?? '';
    $this->dataRequest->direccion_2 = $dataRequest->address2 ?? '';
    $this->dataRequest->direccion_3 = $dataRequest->address3 ?? '';
    $this->dataRequest->zona = $dataRequest->zoneName ?? '';
    $this->dataRequest->codPais = $dataRequest->countryCod;
    $this->dataRequest->estado = $dataRequest->stateCodBranch;
    $this->dataRequest->ciudad = $dataRequest->cityCodBranch;
    $this->dataRequest->persona = $dataRequest->person;
    $this->dataRequest->cod_area = $dataRequest->areaCode;
    $this->dataRequest->telefono = $dataRequest->phone;
    $this->dataRequest->usuario = $this->userName;
    $this->dataRequest->password = $password;

    $this->sendToWebServices('CallWs_updateBranche');

    switch ($this->isResponseRc) {
      case 0:
        $this->response->code = 0;
        $this->response->icon =  lang('SETT_ICON_SUCCESS');
        $this->response->title = lang('GEN_BRANC_OFFICES');
        $this->response->msg = lang('TOOLS_BRANCH_UPDATE');
        $this->response->modalBtn['btn1']['action'] = 'none';
        break;
      case -1:
        $this->response->code = 4;
        $this->response->icon = lang('SETT_ICON_WARNING');
        $this->response->msg = lang('GEN_PASSWORD_NO_VALID');
        $this->response->modalBtn['btn1']['action'] = 'destroy';
        break;
    }

    return $this->responseToTheView('CallWs_updateBranche');
  }

  /**
   * @info Método para subir archivo de sucursales
   * @author Luis Molina
   * @date Oct 26th, 2022
   */
  public function CallWs_UploadFileBranches_Tools($dataRequest)
  {
    writeLog('INFO', 'UploadFileBranches Model: UploadFileBranches Method Initialized');

    $this->sendFile($dataRequest->fileName, 'UploadFileBranches');

    if ($this->isResponseRc === 0) {
      $this->dataAccessLog->modulo = 'Sucursales';
      $this->dataAccessLog->function = 'Carga masiva';
      $this->dataAccessLog->operation = 'Registro masivo';
      $this->dataRequest->idOperation = 'getSucursalTxt';
      $this->dataRequest->className = 'com.novo.objects.TOs.SucursalTO';

      $this->dataRequest->data = [
        "pais" => $this->session->customerSess,
        "idOperation" => $this->dataRequest->idOperation,
        "className" => $this->dataRequest->className,
        "rif" => $dataRequest->idFiscal,
        "url" => $dataRequest->fileName,
        "idTipoLote" => "7",
        "usuario" => $this->session->userdata('userName'),
        "logAccesoObject" => $this->dataAccessLog,
        "token" => $this->session->userdata('token'),
      ];

      $response = $this->sendToWebServices('CallWs_UploadFileBranch');

      switch ($this->isResponseRc) {
        case 0:
          $this->response->code = 0;
          $this->response->icon =  lang('SETT_ICON_SUCCESS');
          $this->response->msg = lang('TOOLS_BRANCH_UPLOAD_FILE');
          $this->response->modalBtn['btn1']['action'] = 'none';
          break;
        case -166:
        case -167:
          $this->response->icon =  lang('SETT_ICON_WARNING');
          $this->response->msg = lang('TOOLS_BRANCH_NO_LOAD');
          $this->response->modalBtn['btn1']['action'] = 'destroy';
          break;
      }
    } else {
      $this->response->icon = lang('SETT_ICON_WARNING');
      $this->response->msg = lang('TOOLS_BRANCH_FILE_NO_MOVE');
      $this->response->modalBtn['btn1']['action'] = 'destroy';
    }

    return $this->responseToTheView('UploadFileBranches');
  }
}
