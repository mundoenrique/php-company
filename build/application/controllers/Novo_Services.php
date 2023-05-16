<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info clase para controlar las peticiones de la cuneta maestra
 * @author J. Enrique Peñaloza Piñero
 * @date May 06th, 2020
 */
Class Novo_Services extends Novo_Controller {

	public function __construct()
	{
		parent:: __construct();
		writeLog('INFO', 'Services Controller Class Initialized');
	}
	/**
	 * @info Método para las opciones de la cuenta maestra
	 * @author J. Enrique Peñaloza Piñero
	 * @date February 6th, 2020
	 */
	public function transfMasterAccount()
	{
		writeLog('INFO', 'Services: transfMasterAccount Method Initialized');

		$view = 'transfMasterAccount';
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
			"services/transfMasterAccount",
			'third_party/jquery.mask-1.14.16',
		);
		$responseAttr = 0;
		$showRechargeAccount = FALSE;

		if ($this->verify_access->verifyAuthorization('TRAMAE', 'TRAPGO')) {
			$showRechargeAccount = TRUE;
			array_push(
				$this->includeAssets->jsFiles,
				'services/transfMasterRecharge'
			);

			$this->method = 'CallWs_MasterAccountBalance_Services';
			$responseAttr = $this->loadModel();

			foreach ($responseAttr->data->info AS $index => $render) {
				$this->render->$index = $render;
			}

			$responseAttr->data->params['showRechargeAccount'] = $showRechargeAccount;
		}

		$this->responseAttr($responseAttr);
		$this->render->titlePage = lang('GEN_MENU_SERV_MASTER_ACCOUNT');
		$this->render->skipInputPass = lang('SETT_INPUT_PASS') == 'OFF' ? 'ml-auto' : '';
		$this->render->showRechargeAccount = $showRechargeAccount;
		$this->views = ['services/'.$view];
		$this->loadView($view);
	}
	/**
	 * @info Método para la consulta de tarjetas
	 * @author J. Enrique Peñaloza Piñero
	 * @date February 6th, 2020
	 */
	public function cardsInquiry()
	{
		writeLog('INFO', 'Services: cardsInquiry Method Initialized');

		$view = 'cardsInquiry';
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
			'services/cardsInquiry'
		);
		$this->responseAttr(0, FALSE);
		$this->render->titlePage = lang('GEN_MENU_SERV_CARD_INQUIRY');
		$this->views = ['services/'.$view];
		$this->loadView($view);
	}
		/**
	 * @info Método para limites transaccionales
	 * @author Hector D. Corredor
	 * @date July 3th, 2020
	 */
	public function transactionalLimits()
	{
		writeLog('INFO', 'Services: transactionalLimits Method Initialized');

		$view = 'transactionalLimits';
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
			'services/transactionalLimits'
		);
		$this->responseAttr();
		$this->render->titlePage = lang('GEN_MENU_SERV_TRANS_LIMITS');
		$this->views = ['services/'.$view];
		$this->loadView($view);
	}

	/**
	 * @info Método para la consulta de tarjetas
	 * @author G. Jennifer Carolina Cádiz
	 * @date July 3th, 2020
	 */

	public function commercialTwirls()
	{
		writeLog('INFO', 'Services: commercialTwirls Method Initialized');

		$view = 'commercialTwirls';
		array_push(
			$this->includeAssets->jsFiles,
			"third_party/jquery.validate",
			"form_validation",
			"third_party/additional-methods",
			'services/commercialTwirls'
		);
		$this->responseAttr();
		$this->render->titlePage = lang('GEN_MENU_SERV_COMM_MONEY_ORDERS');
		$this->views = ['services/'.$view];
		$this->loadView($view);
	}
}
