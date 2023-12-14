<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @info Controlador para la vista principal de la aplicación
 * @author J. Enrique Peñaloza Piñero
 */
class Novo_User extends NOVO_Controller
{
  public function __construct()
  {
    parent::__construct();
    writeLog('INFO', 'User Controller Class Initialized');
  }
  /**
   * @info Método que renderiza la vista de inicio de sesión
   * @author J. Enrique Peñaloza Piñero.
   * @date October 24th, 2020
   */
  public function signIn()
  {
    writeLog('INFO', 'User: signIn Method Initialized');

    clearSessionsVars();
    SetSignSessionType(lang('SETT_COOKIE_SINGN_IN'));
    $view = 'signIn';

    array_push(
      $this->includeAssets->jsFiles,
      "third_party/jquery.balloon",
      "third_party/jquery.validate-1.19.5",
      "third_party/additional-methods-1.19.5",
      "validation/messages_validation",
      "user/signIn"
    );

    if ($this->customerUri === 'bp' && ENVIRONMENT === 'production') {
      array_push(
        $this->includeAssets->jsFiles,
        "third_party/borders"
      );
    }

    $this->render->skipProductInf = TRUE;
    $this->render->titlePage = lang('GEN_SYSTEM_NAME');
    $this->views = ['user/signin'];
    $this->loadView($view);
  }
  /**
   * @info Método para el cierre de sesión
   * @author J. Enrique Peñaloza Piñero.
   */
  public function singleSignOn($sessionId = NULL)
  {
    writeLog('INFO', 'User: singleSignOn Method Initialized');

    clearSessionsVars();
    $view = 'singleSignOn';
    $this->render->submit = FALSE;

    if ($sessionId) {
      $this->render->form['sessionId'] = $sessionId;
      $this->render->submit = TRUE;
    } else {
      $this->render->form = $this->request;
    }

    array_push(
      $this->includeAssets->jsFiles,
      'user/singleSignOn'
    );

    SetSignSessionType(lang('SETT_COOKIE_SINGN_ON'));

    $this->render->titlePage = lang('GEN_SYSTEM_NAME');
    $this->render->skipmenu = TRUE;
    $this->render->skipProductInf = TRUE;
    $this->views = ['user/' . $view];
    $this->loadView($view);
  }
  /**
   * @info Método que renderiza la vista para recuperar la contraseña
   * @author J. Enrique Peñaloza Piñero.
   */
  public function recoverPass()
  {
    writeLog('INFO', 'User: passwordRecovery Method Initialized');

    clearSessionsVars();
    $view = 'recoverPass';

    array_push(
      $this->includeAssets->jsFiles,
      "user/recoverPass",
      "third_party/jquery.validate",
      "form_validation",
      "third_party/additional-methods"
    );

    $this->render->titlePage = lang('GEN_RECOVER_PASS_TITLE');
    $this->render->activeHeader = TRUE;
    $this->render->skipProductInf = TRUE;
    $this->views = ['user/' . $view];
    $this->loadView($view);
  }
  /**
   * @info Método que renderiza la vista para recuperar los datos de acceso
   * @author Jhonnatan Vega.
   */
  public function recoverAccess()
  {
    writeLog('INFO', 'User: recoverAccess Method Initialized');

    clearSessionsVars();
    $view = 'recoverAccess';

    array_push(
      $this->includeAssets->jsFiles,
      "user/recoverAccess",
      "third_party/jquery.validate",
      "form_validation",
      "third_party/additional-methods"
    );
    $this->render->titlePage = lang('GEN_RECOVER_PASS_TITLE');
    $this->render->activeHeader = TRUE;
    $this->render->skipProductInf = TRUE;
    $this->views = ['user/' . $view];
    $this->loadView($view);
  }
  /**
   * @info Método que renderiza la vista para cambiar la contraseña
   * @author J. Enrique Peñaloza Piñero.
   */
  public function changePassword()
  {
    writeLog('INFO', 'User: changePassword Method Initialized');

    $view = 'changePassword';

    if (!$this->session->flashdata('changePassword')) {
      redirect(base_url(lang('SETT_LINK_SIGNIN')), 'Location', 302);
      exit;
    }

    array_push(
      $this->includeAssets->jsFiles,
      "user/changePassword-core",
      "user/passValidate",
      "third_party/jquery.balloon",
      "third_party/jquery.validate",
      "form_validation",
      "third_party/additional-methods"
    );

    switch ($this->session->flashdata('changePassword')) {
      case 'newUser':
        $this->render->message = novoLang(lang("PASSWORD_NEWUSER"), lang('GEN_SYSTEM_NAME'));
        break;
      case 'expiredPass':
        $this->render->message = novoLang(lang("PASSWORD_EXPIRED"), lang('GEN_SYSTEM_NAME'));
        break;
    }

    $this->render->userType = $this->session->flashdata('userType');
    $this->session->set_flashdata('changePassword', $this->session->flashdata('changePassword'));
    $this->session->set_flashdata('userType', $this->session->flashdata('userType'));
    $this->render->titlePage = lang('GEN_PASSWORD_CHANGE_TITLE');
    $this->render->activeHeader = TRUE;
    $this->views = ['user/' . $view];
    $this->loadView($view);
  }
  /**
   * @info Método para el cierre de sesión
   * @author J. Enrique Peñaloza Piñero.
   */
  public function finishSession($redirect)
  {
    writeLog('INFO', 'User: finishSession Method Initialized');

    $view = 'finish';
    $thirdPartySession = getSignSessionType() === lang('SETT_COOKIE_SINGN_ON');

    if ($this->session->has_userdata('userId')) {
      $this->load->model('Novo_User_Model', 'finishSession');
      $this->finishSession->callWs_FinishSession_User();
    }

    if ($redirect == lang('SETT_LINK_SIGNOUT_END') || $thirdPartySession) {
      $pos = array_search('sessionControl', $this->includeAssets->jsFiles);
      $this->render->action = base_url(lang('SETT_LINK_SIGNIN'));
      $this->render->showBtn = !$thirdPartySession;
      $this->render->sessionEnd = novoLang(lang('GEN_EXPIRED_SESSION'), lang('GEN_SYSTEM_NAME'));

      if ($this->session->flashdata('unauthorized') !== NULL) {
        $this->render->sessionEnd = $this->session->flashdata('unauthorized');
      }

      if ($redirect == lang('SETT_LINK_SIGNOUT_START')) {
        $this->render->sessionEnd = novoLang(lang('GEN_FINISHED_SESSION'), lang('GEN_SYSTEM_NAME'));
      }

      unset($this->includeAssets->jsFiles[$pos]);
      $this->render->activeHeader = TRUE;
      $this->render->skipProductInf = TRUE;
      $this->render->titlePage = lang('GEN_FINISH_TITLE');
      $this->views = ['user/' . $view];
      $this->loadView($view);
    } else {
      redirect(base_url(lang('SETT_LINK_SIGNIN')), 'Location', 302);
      exit;
    }
  }
  /**
   * @info Método que renderiza la vista de segerencias de navegador
   * @author J. Enrique Peñaloza Piñero.
   * @date November 25th, 2020
   */
  public function suggestion()
  {
    writeLog('INFO', 'User: suggestion Method Initialized');

    $view = 'suggestion';

    if (!$this->session->flashdata('messageBrowser')) {
      redirect(base_url(lang('SETT_LINK_SIGNIN')), 'Location', 302);
      exit;
    }

    $views = ['staticpages/content-browser'];

    $this->includeAssets->cssFiles = [
      "$this->customerStyle/$this->customerStyle-browser",
      "$this->customerStyle/$this->customerStyle-root",
      "general-root",
      "reboot",
      "$this->customerStyle/" . "$this->customerStyle-base"
    ];

    $messageBrowser = $this->session->flashdata('messageBrowser');
    $this->render->activeHeader = TRUE;
    $this->render->platform = $messageBrowser->platform;
    $this->render->title = $messageBrowser->title;
    $this->render->msg1 = $messageBrowser->msg1;
    $this->render->msg2 = $messageBrowser->msg2;
    $this->render->titlePage = lang('GEN_SYSTEM_NAME');
    $this->views = $views;
    $this->loadView($view);
  }
  /**
   * @info Método que renderiza la vista de administración de usuarios
   * @author Hector D. Corredor.
   *
   */
  public function usersManagement()
  {
    writeLog('INFO', 'User: usersManagement Method Initialized');

    $view = 'usersManagement';
    array_push(
      $this->includeAssets->cssFiles,
      "third_party/dataTables-1.10.20"
    );
    array_push(
      $this->includeAssets->jsFiles,
      "third_party/dataTables-1.10.20",
      "third_party/jquery.validate",
      "form_validation",
      "third_party/additional-methods",
      "user/usersManagement"
    );

    $responseList = $this->loadModel();
    $data = $responseList->data;
    $code = $responseList->code;

    if (($code) == 4) {
      $this->render->userList = [];
      $this->render->userRegistered = '';
    } else {
      $this->render->userList = $data;
      $registeredUser = 'OFF';
      $countRegisteredUser = 0;
      foreach ($data as $key => $value) {
        if ($data[$key]->registered == "false") {
          $countRegisteredUser++;
        }
      }

      if ($countRegisteredUser > 0) {
        $registeredUser = 'ON';
      }
      $this->render->userRegistered = $registeredUser;
    }

    $this->responseAttr($responseList);
    $this->render->titlePage = lang('GEN_MENU_USERS_MANAGEMENT');
    $this->views = ['user/' . $view];
    $this->loadView($view);
  }

  /**
   * @info Método que renderiza la vista de permisos de usuario
   * @author Jennifer C. Cádiz.
   */
  public function userPermissions()

  {
    writeLog('INFO', 'User: userPermissions Method Initialized');

    $view = 'userPermissions';
    array_push(
      $this->includeAssets->jsFiles,
      "third_party/jquery.validate",
      "form_validation",
      "third_party/additional-methods",
      "user/userPermissions"
    );

    if ($this->session->flashdata('userDataPermissions') != NULL) {
      $userDataList = $this->session->flashdata('userDataPermissions');
      $this->request = $userDataList;
      $this->session->set_flashdata('userDataPermissions', $userDataList);
    }
    $this->render->username = $this->session->userName;
    $this->render->user = $this->request->idUser;
    $this->render->name = $this->request->nameUser;
    $this->render->email = $this->request->mailUser;
    $this->render->type = $this->request->typeUser;
    $responseList = $this->loadModel($this->request);

    $arrayDelete = $this->session->enterpriseInf->operatingModel === 'BRAND-2023' ? [] : lang('PERMISSIONS_EXCLUDED');
    $arrayList = $responseList->data;

    foreach ($arrayList as $key => $value) {
      // Recorre subarreglo de una dimensión
      foreach ($value as $index => $subArray) {
        // Recorre subarreglo de dos dimensiones
        foreach ($subArray as $subIndex => $subValue) {
          // Si encuentra el objeto que desea eliminar
          if (in_array($subValue->accodfuncion, $arrayDelete)) {
            // Elimina el objeto utilizando unset
            unset($arrayList[$key][$index][$subIndex]);
          }
        }
      }
    }

    $this->render->modules = $arrayList;

    $this->responseAttr($responseList);
    $this->render->titlePage = lang('GEN_USER_PERMISSION_TITLE');
    $this->views = ['user/' . $view];
    $this->loadView($view);
  }
  /**
   * @info Método que renderiza la vista de cuentas de usuario
   * @author Jennifer C. Cádiz.
   */
  public function userAccounts()

  {
    writeLog('INFO', 'User: userAccounts Method Initialized');

    $view = 'userAccounts';
    array_push(
      $this->includeAssets->jsFiles,
      "third_party/jquery.validate",
      "form_validation",
      "third_party/additional-methods",
      "user/userAccounts"
    );

    if ($this->session->flashdata('userDataAccounts') != NULL) {
      $userDataList = $this->session->flashdata('userDataAccounts');
      $this->request = $userDataList;
      $this->session->set_flashdata('userDataAccounts', $userDataList);
    }

    $this->render->username = $this->session->userName;
    $this->render->user = $this->request->idUser;
    $this->render->name = $this->request->nameUser;
    $this->render->email = $this->request->mailUser;
    $this->render->type = $this->request->typeUser;
    $responseList = $this->loadModel($this->request);

    $arrayDelete = $this->session->enterpriseInf->operatingModel === 'BRAND-2023' ? [] : lang('PERMISSIONS_EXCLUDED');
    $arrayList = $responseList->data;

    $this->render->modules = $arrayList;

    $this->responseAttr($responseList);
    $this->render->titlePage = lang('GEN_ADMIN_ACCOUNTS_TITLE');
    $this->views = ['user/' . $view];
    $this->loadView($view);
  }
  /*
	|--------------------------------------------------------------------------
	| TEMPORAL METHODS
	|--------------------------------------------------------------------------
	*/
  /**
   * @info Método que renderiza la vista de login
   * @author J. Enrique Peñaloza Piñero.
   */
  public function login()
  {
    writeLog('INFO', 'User: index Method Initialized');
    clearSessionsVars();

    $view = 'login';
    $views = ['user/login', 'user/signin'];

    if ($this->customerUri == 'bpi') {
      $views = ['user/signin'];
    }

    array_push(
      $this->includeAssets->jsFiles,
      "third_party/jquery.balloon",
      "third_party/jquery.validate-1.19.5",
      "third_party/additional-methods-1.19.5",
      "validation/messages_validation",
      "user/signIn"
    );

    if ($this->customerUri !== 'bpi') {
      array_push(
        $this->includeAssets->jsFiles,
        "third_party/jquery.kwicks",
        "user/kwicks"
      );
    }

    if ($this->customerUri === 'bpi' && ENVIRONMENT === 'production') {
      array_push(
        $this->includeAssets->jsFiles,
        "third_party/borders"
      );
    }

    $this->render->skipProductInf = TRUE;
    $this->render->titlePage = lang('GEN_SYSTEM_NAME');
    $this->views = $views;
    $this->loadView($view);
  }
  /**
   * @info Método que renderiza la vista para recuperar la contraseña
   * @author J. Enrique Peñaloza Piñero.
   */
  public function passwordRecovery()
  {
    writeLog('INFO', 'User: passwordRecovery Method Initialized');

    $view = 'recoverPass';

    array_push(
      $this->includeAssets->jsFiles,
      "user/recoverPass",
      "third_party/jquery.validate",
      "validate-forms",
      "third_party/additional-methods"
    );

    $this->render->titlePage = lang('GEN_RECOVER_PASS_TITLE');
    $this->render->activeHeader = TRUE;
    $this->render->skipProductInf = TRUE;
    $this->views = ['user/' . $view];
    $this->loadView($view);
  }
  /**
   * @info Método que renderiza la vista para cambiar la contraseña
   * @author J. Enrique Peñaloza Piñero.
   */
  public function changePass()
  {
    writeLog('INFO', 'User: changePass Method Initialized');

    $view = 'changePassword';

    if (!$this->session->flashdata('changePassword')) {
      redirect(base_url(lang('SETT_LINK_SIGNIN')), 'Location', 302);
      exit;
    }

    array_push(
      $this->includeAssets->jsFiles,
      "user/changePassword",
      "user/passValidate",
      "third_party/jquery.balloon",
      "third_party/jquery.validate",
      "validate-forms",
      "third_party/additional-methods"
    );

    switch ($this->session->flashdata('changePassword')) {
      case 'newUser':
        $this->render->message = novoLang(lang("PASSWORD_NEWUSER"), lang('GEN_SYSTEM_NAME'));
        break;
      case 'expiredPass':
        $this->render->message = novoLang(lang("PASSWORD_EXPIRED"), lang('GEN_SYSTEM_NAME'));
        break;
    }

    $this->render->userType = $this->session->flashdata('userType');
    $this->session->set_flashdata('changePassword', $this->session->flashdata('changePassword'));
    $this->session->set_flashdata('userType', $this->session->flashdata('userType'));
    $this->render->titlePage = lang('GEN_PASSWORD_CHANGE_TITLE');
    $this->render->activeHeader = TRUE;
    $this->views = ['user/' . $view];
    $this->loadView($view);
  }
  /**
   * @info Método que renderiza la vista de segerencias de navegador
   * @author J. Enrique Peñaloza Piñero.
   * @date November 25th, 2020
   */
  public function browsers()
  {
    writeLog('INFO', 'User: browsers Method Initialized');

    $view = 'browsers';

    if (!$this->session->flashdata('messageBrowser')) {
      redirect(base_url(lang('SETT_LINK_SIGNIN')), 'Location', 302);
      exit;
    }

    $views = ['staticpages/content-browser'];

    $messageBrowser = $this->session->flashdata('messageBrowser');
    $this->render->activeHeader = TRUE;
    $this->render->platform = $messageBrowser->platform;
    $this->render->title = $messageBrowser->title;
    $this->render->msg1 = $messageBrowser->msg1;
    $this->render->msg2 = $messageBrowser->msg2;
    $this->render->titlePage = lang('GEN_SYSTEM_NAME');
    $this->views = $views;
    $this->loadView($view);
  }
}