<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Controlador para manejar los reportes
 * @author J. Enrique Peñaloza Piñero
 * @date December 7th, 2019
*/
class Novo_Reports extends NOVO_Controller {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'Novo_Reports Controller Class Initialized');
	}
	/**
	 * @info Método para renderizar la lista de reportes
	 * @author J. Enrique Peñaloza Piñero
	 * @date February 6th, 2020
	 */
	public function getReportsList()
	{
		log_message('INFO', 'Novo_Reports: getReportsList Method Initialized');

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

		$responseReports = $this->loadModel($this->request);

		foreach($responseReports->data AS $index => $render) {
			$this->render->$index = $render;
		}

		$this->responseAttr($responseReports);
		$this->render->titlePage =lang('GEN_MENU_REPORTS');
		$this->views = ['reports/'.$view];
		$this->loadView($view);
	}
	/**
	 * @info Método para accder al repor de estado de cuenta
	 * @author J. Enrique Peñaloza Piñero
	 * @date May 7th, 2020
	 */
	public function accountStatus()
	{
		log_message('INFO', 'Novo_Reports: accountStatus Method Initialized');

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
		$this->request->select = TRUE;
		$logo = $this->session->enterpriseInf;
		$this->request->idFiscal = $logo->idFiscal;
		$this->request->enterpriseCode = $logo->enterpriseCode;
		$this->load->model('Novo_Business_Model', 'getProducts');
		$response = $this->getProducts->callWs_GetProducts_Business($this->request);
		$this->render->selectProducts = $response->code === 0 ? lang('GEN_SELECT_PRODUCT') : lang('GEN_TRY_AGAIN');
		$this->render->productsSelect = $response->code !== 0 ? FALSE : $response->data;
		$this->render->currentProd = $this->session->productInf->productPrefix;
		$this->responseAttr($response);
		$this->render->titlePage = lang('GEN_MENU_REP_ACCAOUNT_STATUS');
		$this->views = ['reports/'.$view];
		$this->loadView($view);
	}
	/**
	 * @info Método para accder al repor de reposiciones
	 * @author J. Enrique Peñaloza Piñero
	 * @date May 7th, 2020
	 */
	public function replacement()
	{
		log_message('INFO', 'Novo_Reports: replacement Method Initialized');

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
			"reports/replacement"
		);
		$this->responseAttr();
		$this->render->titlePage = lang('GEN_MENU_REP_CARD_REPLACE');
		$this->views = ['reports/'.$view];
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
		log_message('INFO', 'Novo_Reports: closingBalance Method Initialized');

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

		$this->request->idFiscal = $this->session->enterpriseInf->idFiscal;
		$this->request->enterpriseCode = $this->session->enterpriseInf->enterpriseCode;
		if($this->session->has_userdata('idReportsBusiness') != null){
			$this->load->model('Novo_Reports_Model', 'obtenerIdEmpresa');
			$acrif = $this->obtenerIdEmpresa->callWs_obtenerIdEmpresa_Reports($this->session->idReportsBusiness->acrif);
			$this->request->idFiscal = $acrif->data[0];
		}
		if($this->session->has_userdata('enterpriseInf') != null){
			$this->request->select = TRUE;
			$this->load->model('Novo_Business_Model', 'getProducts');
			$response = $this->getProducts->callWs_GetProducts_Business($this->request);
			$this->render->selectProducts = $response->code === 0 ? lang('GEN_SELECT_PRODUCT') : lang('GEN_TRY_AGAIN');
			$this->responseAttr($response);
			$this->render->productsSelect = $response->code !== 0 ? FALSE : $response->data;
			$this->render->prod = lang('GEN_NO_PRODUCT');
			$this->render->tamP = 1000000;
			$this->render->currentProd = $this->session->productInf->productPrefix;
		}
		$this->render->titlePage = lang('GEN_MENU_REP_CLOSING_BAKANCE');
		$this->views = ['reports/'.$view];
		$this->loadView($view);

	}
	/**
	 * @info Método para accder al reporte de actividad por usuario
	 * @author J. Enrique Peñaloza Piñero
	 * @date May 7th, 2020
	 */
	public function userActivity()
	{
		log_message('INFO', 'Novo_Reports: userActivity Method Initialized');

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
		$this->views = ['reports/'.$view];
		$this->loadView($view);
	}
	/**
	 * @info Método para acceder al reporte de actividad por usuarios (Produbanco)
	 * @author Jhonnatan Vega
	 * @date October 6th, 2020
	 */
	public function usersActivity()
	{
		log_message('INFO', 'Novo_Reports: usersActivity Method Initialized');

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
		$this->views = ['reports/'.$view];
		$this->loadView($view);
	}
	/**
	 * @info Método para accder al reporte de recargas realizadas
	 * @author J. Enrique Peñaloza Piñero
	 * @date May 7th, 2020
	 */
	public function rechargeMade()
	{
		log_message('INFO', 'Novo_Reports: rechargeMade Method Initialized');

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

		$this->request->select = TRUE;
		$this->request->idFiscal = $this->session->enterpriseInf->idFiscal;
		$this->request->enterpriseCode = $this->session->enterpriseInf->enterpriseCode;
		$this->load->model('Novo_Business_Model', 'getProducts');
		$response = $this->getProducts->callWs_GetProducts_Business($this->request);
		$this->render->selectProducts = $response->code === 0 ? lang('GEN_SELECT_PRODUCT') : lang('GEN_TRY_AGAIN');
		$this->render->productsSelect = $response->code !== 0 ? FALSE : $response->data;

		if ($this->session->flashdata('download')) {
			$response = $this->session->flashdata('download');
		}
		$this->responseAttr($response);
		$this->render->currentProd = $this->session->productInf->productPrefix;
		$this->render->titlePage = lang('GEN_MENU_REP_RECHARGE_MADE');
		$this->views = ['reports/'.$view];
		$this->loadView($view);
	}
	/**
	 * @info Método para accder al reporte de recargas realizadas
	 * @author J. Enrique Peñaloza Piñero
	 * @date May 7th, 2020
	 */
	public function issuedCards()
	{
		log_message('INFO', 'Novo_Reports: issuedCards Method Initialized');

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

		$this->request->idFiscal = $this->session->enterpriseInf->idFiscal;
		$this->request->enterpriseCode = $this->session->enterpriseInf->enterpriseCode;

		if ($this->session->flashdata('download')) {
			$response = $this->session->flashdata('download');
		}

		$this->responseAttr();
		$this->render->currentProd = $this->session->productInf->productPrefix;
		$this->render->titlePage = lang('GEN_MENU_REP_ISSUED_CARDS');
		$this->views = ['reports/'.$view];
		$this->loadView($view);
	}
	/**
	 * @info Método para accder al reporte de gastos por categoria
	 * @author J. Enrique Peñaloza Piñero
	 * @date May 7th, 2020
	 */
	public function categoryExpense()
	{
		log_message('INFO', 'Novo_Reports: categoryExpense Method Initialized');

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
			"reports/category_expense"
		);
		$this->responseAttr();
		$this->render->titlePage = lang('GEN_MENU_REP_CATEGORY_EXPENSE');
		$this->views = ['reports/'.$view];
		$this->loadView($view);
	}
	/**
	 * @info Método para accder al reporte de gastos por categoria
	 * @author J. Enrique Peñaloza Piñero
	 * @date May 7th, 2020
	 */
	public function masterAccount()
	{
		log_message('INFO', 'Novo_Reports: masterAccount Method Initialized');

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
		$this->render->tamP = 1000000;
		$this->responseAttr();
		$this->render->titlePage = lang('GEN_MENU_REP_MASTER_ACCOUNT');
		$this->views = ['reports/'.$view];
		$this->loadView($view);
	}
	/**
	 * @info Método para accder al reporte de gastos por categoria
	 * @author Luis Molina
	 * @date Mar 29th, 2022
	*/
	public function extendedMasterAccount()
	{
		log_message('INFO', 'Novo_Reports: extendedMasterAccount Method Initialized');

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
		$this->render->tamP = 1000000;
		$this->responseAttr();
		$this->render->titlePage = lang('GEN_MENU_REP_MASTER_ACCOUNT');
		$this->views = ['reports/'.$view];
		$this->loadView($view);
	}
	/**
	 * @info Método para accder al reporte de Estado de Lote
	 * @author J. Enrique Peñaloza Piñero
	 * @date May 7th, 2020
	 */
	public function statusBulk()
	{
		log_message('INFO', 'Novo_Reports: statusBulk Method Initialized');
		$view = 'statusBulk';
		$statusBulkList = FALSE;
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
		$this->request->select = TRUE;
		$this->request->idFiscal = $this->session->enterpriseInf->idFiscal;
		$this->request->enterpriseCode = $this->session->enterpriseInf->enterpriseCode;
		$this->load->model('Novo_Business_Model', 'getProducts');
		$response = $this->getProducts->callWs_GetProducts_Business($this->request);
		$this->render->selectProducts = $response->code === 0 ? lang('GEN_SELECT_PRODUCT') : lang('GEN_TRY_AGAIN');
		$this->render->productsSelect = $response->code !== 0 ? FALSE : $response->data;

		if ($this->session->flashdata('download')) {
			$response = $this->session->flashdata('download');
		}
		$this->responseAttr($response);
		$this->render->currentProd = $this->session->productInf->productPrefix;
		$this->render->titlePage = lang('GEN_MENU_REP_STATUS_BULK');
		$this->views = ['reports/'.$view];
		$this->loadView($view);
	}

	/**
	 * @info Método para accder al reporte de tarjetahbientes
	 * @author J. Enrique Peñaloza Piñero
	 * @date May 7th, 2020
	 */
	public function cardHolders()
	{
		log_message('INFO', 'Novo_Reports: cardHolders Method Initialized');
		$view = 'cardHolders';
		$cardHoldersList = FALSE;
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

		$this->request->select = TRUE;
		$this->request->idFiscal = $this->session->enterpriseInf->idFiscal;
		$this->request->enterpriseCode = $this->session->enterpriseInf->enterpriseCode;
		$this->load->model('Novo_Business_Model', 'getProducts');
		$response = $this->getProducts->callWs_GetProducts_Business($this->request);
		$this->render->selectProducts = $response->code === 0 ? lang('GEN_SELECT_PRODUCT') : lang('GEN_TRY_AGAIN');
		$this->render->productsSelect = $response->code !== 0 ? FALSE : $response->data;

		if ($this->session->flashdata('download')) {
			$response = $this->session->flashdata('download');
		}
		$this->responseAttr($response);
		$this->render->currentProd = $this->session->productInf->productPrefix;
		$this->render->titlePage = lang('GEN_MENU_REP_CARDHOLDERS');
		$this->views = ['reports/'.$view];
		$this->loadView($view);
	}
	/**
	 * @info Método para accder al repor de estado de cuenta maestra
	 * @author Luis Molina
	 * @date August 30, 2021
	 */
	public function statusMasterAccount()
	{
		log_message('INFO', 'Novo_Reports: statusMasterAccount Method Initialized');

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
		$this->request->select = TRUE;
		$logo = $this->session->enterpriseInf;
		$this->request->idFiscal = $logo->idFiscal;
		$this->request->enterpriseCode = $logo->enterpriseCode;
		$this->load->model('Novo_Business_Model', 'getProducts');
		$response = $this->getProducts->callWs_GetProducts_Business($this->request);
		$this->responseAttr($response);
		$this->render->titlePage = lang('GEN_MENU_REP_STATUS_MASTER_ACCOUNT');
		$this->views = ['reports/'.$view];
		$this->loadView($view);
	}
}

