<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info clase para obtener información relacionada con los lotes
 * @author
 *
 */
class Novo_Plantilla_Model extends NOVO_Model {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO Plantilla Model Class Initialized');
	}
	/**
	 * @info Método para
	 * @author
	 */
	public function callWs_Plantilla_User($dataRequest)
	{
		log_message('INFO', 'NOVO Plantilla Model: Plantilla Method Initialized');
		$this->className = '';

		$this->dataAccessLog->modulo = '';
		$this->dataAccessLog->function = '';
		$this->dataAccessLog->operation = '';
		//usar de ser necesario
		$this->dataAccessLog->userName = $dataRequest->user;

		$this->dataRequest->idOperation = 'id-optation';

		$response = $this->sendToService('callWs_Plantilla');

		switch($this->isResponseRc) {
			case 0:
				$this->response->code = 0;


				break;
			case -5000:
				$this->response->code = 1;

				break;
			case -6000:
				$this->response->code = 2;

				break;
			case -7000:
				$this->response->code = 3;
				$this->response->msg = lang('REEMPLAZAR POR TRADUCCION DESDE RESPONSE-LANG');
				$this->response->icon = lang('CONF_ICON_SUCCESS');
				$this->response->data = [
					'btn1'=> [
						'text'=> lang('GEN_BTN_ACCEPT'),
						'link'=> 'empresas',
						'action'=> 'redirect'
					],
					'btn2'=> [
						'text'=> lang('GEN_BTN_CANCEL'),
						'link'=> FALSE,
						'action'=> 'close'
					]
				];
				break;
		}

		return $this->responseToTheView('callWs_Plantilla');
	}
}
