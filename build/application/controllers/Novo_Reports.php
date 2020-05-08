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
			"validate".$this->render->newViews."-forms",
			"third_party/additional-methods",
			"reports/reports"
		);
		$responseReports = $this->loadModel($this->request);
		$this->responseAttr($responseReports);

		foreach($responseReports->data AS $index => $render) {
			if($index !== 'resp') {
				$this->render->$index = $render;
			}
		}

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
			"validate-core-forms",
			"third_party/additional-methods",
			"reports/account_status"
		);
		$this->responseAttr();
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
			"validate-core-forms",
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
			"validate-core-forms",
			"third_party/additional-methods",
			"reports/closing_balance"
		);
		$this->responseAttr();
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
			"validate-core-forms",
			"third_party/additional-methods",
			"reports/user_activity"
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
			"validate-core-forms",
			"third_party/additional-methods",
			"reports/recharge_made"
		);
		$this->responseAttr();
		$this->render->titlePage = lang('GEN_MENU_REP_RECHARGE_MADE');
		$this->views = ['reports/'.$view];
		$this->loadView($view);
	}
}
