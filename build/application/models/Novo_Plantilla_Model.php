<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info clase para
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
		log_message('INFO', 'NOVO Plantilla Model: Plantilla method Initialized');
		$this->className = '';

		$this->dataAccessLog->modulo = '';
		$this->dataAccessLog->function = '';
		$this->dataAccessLog->operation = '';
		$this->dataAccessLog->userName = $dataRequest->user;

		$this->dataRequest->userName = mb_strtoupper($dataRequest->user);

		$response = $this->sendToService('Plantilla');
		if($this->isResponseRc !== FALSE) {
			switch($this->isResponseRc) {
				case 0:
					log_message('DEBUG', 'NOVO ['.$dataRequest->user.'] RESPONSE: Login: ' . json_encode($response->usuario));

					break;
				case -5000:
					$this->response->code = 1;
					$this->response->title = lang('REEMPLAZAR POR TRADUCCION DESDE RESPONSE-LANG');
					$this->response->className = 'error-login-2';
					$this->response->msg = lang('REEMPLAZAR POR TRADUCCION DESDE RESPONSE-LANG');
					break;
				case -6000:
					$this->response->code = 3;
					$this->response->msg = lang('REEMPLAZAR POR TRADUCCION DESDE RESPONSE-LANG');
					$this->response->icon = 'ui-icon-info';
					$this->response->data = [
						'btn1'=> [
							'text'=> lang('BUTTON-ACCEPT'),
							'link'=> base_url('inicio'),
							'action'=> 'redirect'
						],
						'btn2'=> [
							'text'=> lang('BUTTON-CANCEL'),
							'link'=> FALSE,
							'action'=> 'close'
						]
					];
					break;
			}
		}

		return $this->response;
	}
}
