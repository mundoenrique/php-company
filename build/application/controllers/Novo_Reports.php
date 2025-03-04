<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @info Controlador para manejar los reportes
 * @author J. Enrique Peñaloza Piñero
 * @date December 7th, 2019
 */
class Novo_Reports extends NOVO_Controller
{

  public function __construct()
  {
    parent::__construct();
    writeLog('INFO', 'Reports Controller Class Initialized');

    $getProducts = in_array(
      $this->controllerMethod,
      [
        'accountStatus',
        'cardHolders',
        'categoryExpense',
        'closingBalance',
        'extendedAccountStatus',
        'replacement',
        'statusBulk',
      ]
    );

    if ($getProducts) {
      $inf = $this->session->enterpriseInf;
      $this->request->select = TRUE;
      $this->request->idFiscal = $inf->idFiscal;
      $this->request->enterpriseCode = $inf->enterpriseCode;
      $this->load->model('Novo_Business_Model', 'getProducts');
      $productsList = $this->getProducts->callWs_GetProducts_Business($this->request);

      $this->render->selectProducts = $productsList->code === 0 ? lang('GEN_SELECT_PRODUCT') : lang('GEN_TRY_AGAIN');
      $this->render->productsSelect = $productsList->code !== 0 ? [] : $productsList->data;
      $this->render->currentProd = $this->session->productInf->productPrefix;
      $this->responseAttr($productsList);
    }
  }
  /**
   * @info Método para renderizar la lista de reportes
   * @author J. Enrique Peñaloza Piñero
   * @date February 6th, 2020
   */
  public function getReportsList()
  {
    writeLog('INFO', 'Reports: getReportsList Method Initialized');

    $view = 'reports';
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
      "reports/reports"
    );

    $responseReports = $this->loadModel();

    foreach ($responseReports->data as $index => $render) {
      $this->render->$index = $render;
    }

    $this->responseAttr($responseReports);
    $this->render->titlePage = lang('GEN_MENU_REPORTS');
    $this->views = ['reports/' . $view];
    $this->loadView($view);
  }
  /**
   * @info Método para accder al repor de estado de cuenta
   * @author J. Enrique Peñaloza Piñero
   * @date May 7th, 2020
   */
  public function accountStatus()
  {
    writeLog('INFO', 'Reports: accountStatus Method Initialized');

    $view = 'accountStatus';
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
      "reports/account_status",
      "reports/getproductsReports"
    );

    $this->render->titlePage = lang('GEN_MENU_REP_ACCOUNT_STATUS');
    $this->views = ['reports/' . $view];
    $this->loadView($view);
  }

  /**
   * @info Método para acceder al reporte de estado de cuenta extendido
   * @author Luis Molina / Jennifer Cádiz
   * @date May 16th, 2022
   */
  public function extendedAccountStatus()
  {
    writeLog('INFO', 'Reports: extendedAccountStatus Method Initialized');

    $view = 'extendedAccountStatus';
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
      "reports/extended_account_status",
      "reports/getproductsReports"
    );

    $this->render->titlePage = lang('GEN_MENU_REP_ACCOUNT_STATUS');
    $this->views = ['reports/' . $view];
    $this->loadView($view);
  }
  /**
   * @info Método para accder al repor de reposiciones
   * @author J. Enrique Peñaloza Piñero
   * @date May 7th, 2020
   */
  public function replacement()
  {
    writeLog('INFO', 'Reports: replacement Method Initialized');

    $view = 'replacement';
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
      "reports/replacement",
      "reports/getproductsReports"
    );

    $this->render->titlePage = lang('GEN_MENU_REP_CARD_REPLACE');
    $this->views = ['reports/' . $view];
    $this->loadView($view);
  }
  /**
   * @info Método para accder al reporte de saldo al cierre
   * @author J. Enrique Peñaloza Piñero
   * @date May 7th, 2020
   * @modified Diego Acosta García
   * @date May 22th, 2020
   */
  public function closingBalance()
  {
    writeLog('INFO', 'Reports: closingBalance Method Initialized');

    $view = 'closingBalance';
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
      "reports/closing_balance"
    );

    $this->render->prod = lang('GEN_NO_PRODUCT');
    $this->render->tamP = 1000000;
    $this->render->titlePage = lang('GEN_MENU_REP_CLOSING_BAKANCE');
    $this->views = ['reports/' . $view];
    $this->loadView($view);
  }
  /**
   * @info Método para accder al reporte de actividad por usuario
   * @author J. Enrique Peñaloza Piñero
   * @date May 7th, 2020
   */
  public function userActivity()
  {
    writeLog('INFO', 'Reports: userActivity Method Initialized');

    $view = 'userActivity';
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
      "reports/user_activity"
    );

    $this->responseAttr();
    $this->render->titlePage = lang('GEN_MENU_REP_USER_ACT');
    $this->views = ['reports/' . $view];
    $this->loadView($view);
  }
  /**
   * @info Método para acceder al reporte de actividad por usuarios (Produbanco)
   * @author Jhonnatan Vega
   * @date October 6th, 2020
   */
  public function usersActivity()
  {
    writeLog('INFO', 'Reports: usersActivity Method Initialized');

    $view = 'usersActivity';
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
      "reports/users_activity"
    );

    $this->responseAttr();
    $this->render->titlePage = lang('GEN_MENU_REP_USER_ACT');
    $this->views = ['reports/' . $view];
    $this->loadView($view);
  }
  /**
   * @info Método para accder al reporte de recargas realizadas
   * @author J. Enrique Peñaloza Piñero
   * @date May 7th, 2020
   */
  public function rechargeMade()
  {
    writeLog('INFO', 'Reports: rechargeMade Method Initialized');

    $view = 'rechargeMade';
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
      "reports/recharge_made"
    );

    if ($this->session->flashdata('download')) {
      $response = $this->session->flashdata('download');
      $this->responseAttr($response);
    } else {
      $this->responseAttr();
    }

    $this->render->titlePage = lang('GEN_MENU_REP_RECHARGE_MADE');
    $this->views = ['reports/' . $view];
    $this->loadView($view);
  }
  /**
   * @info Método para accder al reporte de recargas realizadas
   * @author J. Enrique Peñaloza Piñero
   * @date May 7th, 2020
   */
  public function issuedCards()
  {
    writeLog('INFO', 'Reports: issuedCards Method Initialized');

    $view = 'issuedCards';
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
      "reports/issued_cards"
    );

    if ($this->session->flashdata('download')) {
      $response = $this->session->flashdata('download');
      $this->responseAttr($response);
    } else {
      $this->responseAttr();
    }

    $this->render->titlePage = lang('GEN_MENU_REP_ISSUED_CARDS');
    $this->views = ['reports/' . $view];
    $this->loadView($view);
  }
  /**
   * @info Método para accder al reporte de gastos por categoria
   * @author J. Enrique Peñaloza Piñero
   * @date May 7th, 2020
   */
  public function categoryExpense()
  {
    writeLog('INFO', 'Reports: categoryExpense Method Initialized');

    $view = 'categoryExpense';
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
      "reports/categoryExpense",
      "reports/getproductsReports"
    );

    $this->render->titlePage = lang('GEN_MENU_REP_CATEGORY_EXPENSE');
    $this->views = ['reports/' . $view];
    $this->loadView($view);
  }
  /**
   * @info Método para accder al reporte de gastos por categoria
   * @author J. Enrique Peñaloza Piñero
   * @date May 7th, 2020
   */
  public function masterAccount()
  {
    writeLog('INFO', 'Reports: masterAccount Method Initialized');

    $view = 'masterAccount';
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
      "reports/master_account"
    );

    $this->responseAttr();
    $this->render->tamP = 1000000;
    $this->render->titlePage = lang('GEN_MENU_REP_MASTER_ACCOUNT');
    $this->views = ['reports/' . $view];
    $this->loadView($view);
  }
  /**
   * @info Método para accder al reporte de gastos por categoria
   * @author Luis Molina
   * @date Mar 29th, 2022
   */
  public function extendedMasterAccount()
  {
    writeLog('INFO', 'Reports: extendedMasterAccount Method Initialized');

    $view = 'extendedMasterAccount';
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
      "reports/extended_master_account"
    );

    $this->responseAttr();
    $this->render->tamP = 1000000;
    $this->render->titlePage = lang('GEN_MENU_REP_MASTER_ACCOUNT');
    $this->views = ['reports/' . $view];
    $this->loadView($view);
  }
  /**
   * @info Método para accder al reporte de Estado de Lote
   * @author J. Enrique Peñaloza Piñero
   * @date May 7th, 2020
   */
  public function statusBulk()
  {
    writeLog('INFO', 'Reports: statusBulk Method Initialized');

    $view = 'statusBulk';
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
      "reports/statusBulk",
      "reports/getproductsReports"
    );

    if ($this->session->flashdata('download')) {
      $response = $this->session->flashdata('download');
      $this->responseAttr($response);
    }

    $this->render->titlePage = lang('GEN_MENU_REP_STATUS_BULK');
    $this->views = ['reports/' . $view];
    $this->loadView($view);
  }

  /**
   * @info Método para accder al reporte de tarjetahbientes
   * @author J. Enrique Peñaloza Piñero
   * @date May 7th, 2020
   */
  public function cardHolders()
  {
    writeLog('INFO', 'Reports: cardHolders Method Initialized');

    $view = 'cardHolders';
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
      "reports/cardholders",
      "reports/getproductsReports"
    );

    if ($this->session->flashdata('download')) {
      $response = $this->session->flashdata('download');
      $this->responseAttr($response);
    }

    $this->render->titlePage = lang('GEN_MENU_REP_CARDHOLDERS');
    $this->views = ['reports/' . $view];
    $this->loadView($view);
  }
  /**
   * @info Método para accder al repor de estado de cuenta maestra
   * @author Luis Molina
   * @date August 30, 2021
   */
  public function statusMasterAccount()
  {
    writeLog('INFO', 'Reports: statusMasterAccount Method Initialized');

    $view = 'statusMasterAccount';
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
      "reports/status_master_account"
    );

    $this->responseAttr();
    $this->render->titlePage = lang('GEN_MENU_REP_STATUS_MASTER_ACCOUNT');
    $this->views = ['reports/' . $view];
    $this->loadView($view);
  }
}
