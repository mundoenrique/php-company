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
		log_message('INFO', 'NOVO Services Controller Class Initialized');
	}
	/**
	 * @info Método para las opciones de la cuenta maestra
	 * @author J. Enrique Peñaloza Piñero
	 * @date February 6th, 2020
	 */
	public function transfMasterAccount()
	{
		log_message('INFO', 'Novo_Services: transfMasterAccount Method Initialized');

		$view = 'transfMasterAccount';
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
			'services/transfMasterAccount',
			'services/transfMasterRecharge',
		);

		$responseAttr = 0;

		if (lang('CONF_PAY_ACCOUNT') == 'ON' && $this->verify_access->verifyAuthorization('TRAMAE', 'TRASAL')) {
			$this->method = 'CallWs_MasterAccountBalance_Services';
			$responseAttr = $this->loadModel();
		}

		$this->responseAttr($responseAttr);
		$this->render->titlePage = lang('GEN_MENU_SERV_MASTER_ACCOUNT');
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
		log_message('INFO', 'Novo_Services: cardsInquiry Method Initialized');

		$view = 'cardsInquiry';
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
		log_message('INFO', 'Novo_Services: transactionalLimits Method Initialized');

		$view = 'transactionalLimits';
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
		log_message('INFO', 'Novo_Services: commercialTwirls Method Initialized');

		$view = 'commercialTwirls';
		array_push(
			$this->includeAssets->jsFiles,
			"third_party/jquery.validate",
			"validate-core-forms",
			"third_party/additional-methods",
			'services/commercialTwirls'
		);
		$this->responseAttr();
		$this->render->titlePage = lang('GEN_MENU_SERV_COMM_MONEY_ORDERS');
		$this->views = ['services/'.$view];
		$this->loadView($view);
	}
}
