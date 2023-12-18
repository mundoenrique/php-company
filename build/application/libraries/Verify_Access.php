<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @info Librería para validar el acceso del usuario a las funciones
 * @author J. Enrique Peñaloza Piñero
 * @date October 31th, 2019
 */
class Verify_Access
{
  private $CI;

  public function __construct()
  {
    writeLog('INFO', 'Verify_Access Library Class Initialized');

    $this->CI = &get_instance();
  }
  /**
   * @info método que valida los datos de los formularios enviados
   * @author J. Enrique Peñaloza Piñero
   * @date October 31th, 2019
   */
  public function validateForm($validationMethod)
  {
    writeLog('INFO', 'Verify_Access: validateForm method initialized');

    $this->CI->load->library('form_validation');
    $this->CI->form_validation->set_error_delimiters('', '---');
    $this->CI->config->set_item('language', 'global');
    $result = $this->CI->form_validation->run($validationMethod);

    writeLog('DEBUG', 'VALIDATION FORM ' . $validationMethod . ': ' . json_encode($result, JSON_UNESCAPED_UNICODE));

    if (!$result) {
      writeLog('ERROR', 'VALIDATION ' . $validationMethod . ' ERRORS: ' . json_encode(validation_errors(), JSON_UNESCAPED_UNICODE));
    }

    unset($_POST);

    return $result;
  }
  /**
   * @info método para crear el request al modelo
   * @author J. Enrique Peñaloza Piñero
   * @date October 31th, 2019
   */
  public function createRequest($class, $method)
  {
    writeLog('INFO', 'Verify_Access: createRequest method initialized');

    $requestServ = new stdClass();

    foreach ($_POST as $key => $value) {
      switch ($key) {
        case 'request':
        case 'plot':
        case 'ceo_name':
          break;
        case 'screenSize':
          $this->CI->session->set_userdata('screenSize', $value);
          break;
        default:
          $requestServ->$key = $value;
      }
    }

    writeLog('DEBUG', 'REQUEST CREATED FOR CLASS ' . $class . ' AND METHOD ' . $method . ': ' .
      json_encode($requestServ, JSON_UNESCAPED_UNICODE));

    return $requestServ;
  }
  /**
   * @info método para crear el request al modelo
   * @author J. Enrique Peñaloza Piñero
   * @date October 31th, 2019
   */
  public function ResponseByDefect()
  {
    writeLog('INFO', 'Verify_Access: ResponseByDefect method initialized');

    $redirectLink = uriRedirect();

    $responseDefect = new stdClass();
    $responseDefect->code = lang('SETT_DEFAULT_CODE');
    $responseDefect->title = lang('GEN_SYSTEM_NAME');
    $responseDefect->icon = lang('SETT_ICON_DANGER');
    $responseDefect->msg = lang('GEN_VALIDATION_INPUT');
    $responseDefect->data = new stdClass();
    $responseDefect->modalBtn = [
      'btn1' => [
        'text' => lang('GEN_BTN_ACCEPT'),
        'link' => $redirectLink,
        'action' => 'redirect'
      ]
    ];

    if ($this->CI->session->has_userdata('logged')) {
      $responseDefect->msg = lang('GEN_VALIDATION_INPUT_LOGGED');
      $this->CI->load->model('Novo_User_Model', 'finishSession');
      $this->CI->finishSession->callWs_FinishSession_User();
    }

    writeLog('DEBUG', 'ResponseByDefect: ' . json_encode($responseDefect, JSON_UNESCAPED_UNICODE));

    return $responseDefect;
  }
  /**
   * @info método que valida la autorización de acceso del usuario a las vistas
   * @author J. Enrique Peñaloza Piñero
   * @date October 31th, 2019
   */
  public function accessAuthorization($validationMethod)
  {
    writeLog('INFO', 'Verify_Access: accessAuthorization method initialized');

    $isLogged = $this->CI->session->has_userdata('logged');
    $isUserId = $this->CI->session->has_userdata('userId');
    $enterpriseInf = $this->CI->session->has_userdata('enterpriseInf');
    $productInf = $this->CI->session->has_userdata('productInf');
    $referrer = $this->CI->agent->referrer();
    $ajaxRequest = $this->CI->input->is_ajax_request();

    if ($isUserId && $this->CI->session->clientAgent !== $this->CI->agent->agent_string()) {
      clearSessionsVars();
    }

    switch ($validationMethod) {
      case 'signIn':
        $auth = TRUE;
        $uriSegmwnts = $this->CI->uri->segment(2) . '/' . $this->CI->uri->segment(3);

        if (SINGLE_SIGN_ON && $uriSegmwnts !== 'internal/novopayment' && ENVIRONMENT === 'production' && !$ajaxRequest) {
          redirect('page-no-found', 'Location', 301);
          // show_404();
          exit();
        } elseif ($uriSegmwnts === 'internal/novopayment' && ENVIRONMENT !== 'production' && !$ajaxRequest) {
          redirect('page-no-found', 'Location', 301);
          exit();
        }
        break;
      case 'recoverPass':
      case 'passwordRecovery':
        $auth = lang('SETT_RECOV_PASS') === 'ON';
        break;
      case 'recoverAccess':
      case 'validateOtp':
        $auth = lang('SETT_RECOV_ACCESS') === 'ON';
        break;
      case 'changeEmail':
      case 'changeTelephones':
      case 'changeDataEnterprice':
      case 'addContact':
      case 'addBranches':
      case 'deleteContact':
      case 'getEnterprises':
      case 'getEnterprise':
      case 'getUser':
      case 'obtenerIdEmpresa':
      case 'keepSession':
      case 'options':
      case 'getFileIni':
      case 'getBranches':
      case 'getContacts':
      case 'uploadFileBranches':
      case 'updateBranches':
      case 'updateContact':
      case 'deleteFile':
      case 'getProducts':
        $auth = $isLogged;
        break;
      case 'changePassword':
      case 'changePass':
        $auth = $isLogged || $this->CI->session->flashdata('changePassword') !== NULL;
        break;
      case 'benefits':
      case 'benefitsInf':
        $auth = lang('SETT_BENEFITS') === 'ON';
        break;
      case 'ratesInf':
        $auth = ($isLogged && lang('SETT_FOOTER_RATES') === 'ON');
        break;
      case 'getProductDetail':
        $auth = ($isLogged && $enterpriseInf);
        break;
      case 'authorizationKey':
        $auth = ($isLogged && $productInf);
        break;
      case 'getPendingBulk':
      case 'loadBulk':
      case 'getDetailBulk':
        $auth = ($productInf && $this->verifyAuthorization('TEBCAR'));
        break;
      case 'unnamedRequest':
        $auth = ($productInf && $this->verifyAuthorization('TICARG'));
        break;
      case 'unnamedAffiliate':
      case 'unnmamedDetail':
        $auth = ($productInf && $this->verifyAuthorization('TIINVN'));
        break;
      case 'confirmBulk':
        $auth = ($productInf && $this->verifyAuthorization('TEBCAR', 'TEBCON'));
        break;
      case 'deleteNoConfirmBulk':
        $auth = ($productInf && $this->verifyAuthorization('TEBCAR', 'TEBELC'));
        break;
      case 'signBulkList':
      case 'authorizeBulk':
      case 'authorizeBulkList':
      case 'calculateServiceOrder':
        $auth = ($productInf && $this->verifyAuthorization('TEBAUT'));
        break;
      case 'bulkDetail':
        $auth = ($productInf && ($this->verifyAuthorization('TEBAUT') || $this->verifyAuthorization('TEBORS')));
        break;
      case 'deleteConfirmBulk':
      case 'disassConfirmBulk':
        $auth = ($productInf && $this->verifyAuthorization('TEBAUT', 'TEBELI'));
        break;
      case 'serviceOrder':
      case 'cancelServiceOrder':
        $auth = ($productInf && $this->verifyAuthorization('TEBAUT'));
        break;
      case 'exportFiles':
      case 'serviceOrders':
      case 'getServiceOrders':
        $auth = ($productInf && $this->verifyAuthorization('TEBORS'));
        break;
      case 'clearServiceOrders':
        $auth = ($productInf && $this->verifyAuthorization('TEBORS', 'TEBANU'));
        break;
      case 'transfMasterAccount':
      case 'actionMasterAccount':
        $auth = ($productInf && $this->verifyAuthorization('TRAMAE'));
        break;
      case 'masterAccountTransfer':
      case 'rechargeAuthorization':
        $auth = ($productInf && $this->verifyAuthorization('TRAMAE', 'TRAPGO'));
        break;
      case 'cardsInquiry':
      case 'inquiriesActions':
        $auth = ($productInf && $this->verifyAuthorization('COPELO'));
        break;
      case 'transactionalLimits':
        $auth = ($productInf && $this->verifyAuthorization('LIMTRX'));
        break;
      case 'updateTransactionalLimits':
        $auth = ($productInf && $this->verifyAuthorization('LIMTRX', 'ACTLIM'));
        break;
      case 'commercialTwirls':
        $auth = ($productInf && $this->verifyAuthorization('GIRCOM'));
        break;
      case 'updateCommercialTwirls':
        $auth = ($productInf && $this->verifyAuthorization('GIRCOM', 'ACTGIR'));
        break;
      case 'getReportsList':
        $auth = ($productInf && $this->verifyAuthorization('REPALL'));
        break;
      case 'getReport':
        $auth = ($productInf && $this->verifyAuthorization('REPALL', 'REPALL'));
        break;
      case 'userActivity':
      case 'exportReportUserActivity':
      case 'exportToActivityUser':
        $auth = ($productInf && $this->verifyAuthorization('REPUSU') && lang('SETT_USER_ACTIVITY') === 'ON');
        break;
      case 'usersActivity':
      case 'exportExcelUsersActivity':
        $auth = ($productInf && $this->verifyAuthorization('REPUSU') && lang('SETT_USERS_ACTIVITY') === 'ON');
        break;
      case 'statusAccountExcelFile':
      case 'statusAccountPdfFile':
      case 'searchStatusAccount':
      case 'accountStatus':
        $auth = ($productInf && $this->verifyAuthorization('REPEDO'));
        break;
      case 'extendedAccountStatus':
      case 'searchExtendedAccountStatus':
      case 'exportToExcelExtendedAccountStatus':
      case 'exportToTxtExtendedAccountStatus':
      case 'exportToPdfExtendedAccountStatus':
        $auth = ($productInf && $this->verifyAuthorization('REPEDC'));
        break;
      case 'statusMasterAccount':
        $auth = ($productInf && $this->verifyAuthorization('REPECT'));
        break;
      case 'replacement':
        $auth = ($productInf && $this->verifyAuthorization('REPREP'));
        break;
      case 'closingBalance':
      case 'exportToExcel':
      case 'exportToClosingBalance':
      case 'closingBudgets':
        $auth = ($productInf && $this->verifyAuthorization('REPSAL'));
        break;
      case 'rechargeMade':
        $auth = ($productInf && $this->verifyAuthorization('REPPRO'));
        break;
      case 'issuedCards':
        $auth = ($productInf && $this->verifyAuthorization('REPTAR'));
        break;
      case 'categoryExpense':
        $auth = ($productInf && $this->verifyAuthorization('REPCAT'));
        break;
      case 'statusBulk':
      case 'exportToStatusBulk':
        $auth = ($productInf && $this->verifyAuthorization('REPLOT'));
        break;
      case 'exportToExcelMasterAccount':
      case 'exportToPDFMasterAccount':
      case 'exportToExcelMasterAccountConsolid':
      case 'exportToPDFMasterAccountConsolid':
      case 'masterAccount':
        $auth = ($productInf && $this->verifyAuthorization('REPCON'));
        break;
      case 'extendedMasterAccount':
      case 'exportToExcelExtendedMasterAccount':
      case 'exportToTxtExtendedMasterAccount':
      case 'extendedDownloadMasterAccountCon':
        $auth = ($productInf && $this->verifyAuthorization('REPCMT'));
        break;
      case 'cardHolders':
      case 'exportReportCardHolders':
        $auth = ($productInf && $this->verifyAuthorization('TEBTHA'));
        break;
      case 'usersManagement':
        $auth = ($productInf && $this->verifyAuthorization('USEREM', 'CONUSU'));
        break;
      case 'enableUser':
        $auth = ($productInf && $this->verifyAuthorization('USEREM', 'CREUSU'));
        break;
      case 'userAccounts':
      case 'userPermissions':
      case 'updatePermissions':
      case 'updateAccounts':
        $auth = ($productInf && $this->verifyAuthorization('USEREM', 'ASGPER', 'COACUE'));
        break;
      case 'pagoOs':
      case 'pagarOS':
        $auth = ($productInf && $this->verifyAuthorization('TEBORS', 'TEBPGO'));
        break;
      default:
        $freeAccess = [
          'login', 'suggestion', 'browsers', 'finishSession', 'singleSignOn', 'changeLanguage', 'terms', 'termsInf', 'pageNoFound'
        ];
        $auth = in_array($validationMethod, $freeAccess);
    }

    writeLog('DEBUG', 'accessAuthorization ' . $validationMethod . ': ' . json_encode($auth, JSON_UNESCAPED_UNICODE));

    if (!$auth) {
      $auth = !(preg_match('/Novo_/', $this->CI->router->fetch_class()) === 1);
    }

    return $auth;
  }

  /**
   * @info método que valida la autorización de acceso del usuario a las funcionalidades
   * @author J. Enrique Peñaloza Piñero
   * @date October 31th, 2019
   */
  public function verifyAuthorization($moduleLink, $function = FALSE)
  {
    writeLog('INFO', 'Verify_Access: verifyAuthorization method initialized');

    $userAccess = $this->CI->session->user_access;
    $items = [];
    $auth = FALSE;

    if ($userAccess) {
      foreach ($userAccess as $item) {
        foreach ($item->modulos as $module) {
          if (!$function) {
            $items[] = $module->idModulo;
          } else {
            foreach ($module->funciones as $functions) {
              if ($module->idModulo != $moduleLink) {
                continue;
              }

              $items[] = $functions->accodfuncion;
            }
          }
        }
      }

      $access = $function ? $function : $moduleLink;
      $prompter = $function ? '->' . $function : '';
      $auth = in_array($access, $items);

      writeLog('INFO', 'verifyAuthorization ' . $moduleLink . $prompter . ': ' . json_encode($auth, JSON_UNESCAPED_UNICODE));
    }


    return $auth;
  }
  /**
   * @info método que valida la redirección del core correcto
   * @author J. Enrique Peñaloza Piñero
   * @date October 31th, 2019
   */
  public function validateRedirect($redirectUrl, $customerUri)
  {
    writeLog('INFO', 'Verify_Access: validateRedirect method initialized');

    $dataLink = isset($redirectUrl['btn1']['link']) ? $redirectUrl['btn1']['link'] : FALSE;

    if (!is_array($redirectUrl) && strpos($redirectUrl, 'dashboard') !== FALSE) {
      $redirectUrl = str_replace($customerUri . '/', $this->CI->config->item('customer') . '/', $redirectUrl);
    } elseif ($dataLink && !is_array($dataLink) && strpos($dataLink, 'dashboard') !== FALSE) {
      $dataLink = str_replace($customerUri . '/', $this->CI->config->item('customer') . '/', $dataLink);
      $redirectUrl['btn1']['link'] =  $dataLink;
    }

    return $redirectUrl;
  }
}
