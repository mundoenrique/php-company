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
		writeLog('INFO', 'Plantilla Model Class Initialized');
	}
	/**
	 * @info Método para
	 * @author
	 */
	public function callWs_Plantilla_Plantilla($dataRequest)
	{
		writeLog('INFO', 'Plantilla Model: Plantilla Method Initialized');

		$this->dataAccessLog->modulo = '';
		$this->dataAccessLog->function = '';
		$this->dataAccessLog->operation = '';
		//usar de ser necesario
		$this->dataAccessLog->userName = $dataRequest->userName;

		$this->dataRequest->idOperation = 'id-optation';
		$this->dataRequest->className = 'class-name';

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
				$this->response->icon = lang('SETT_ICON_SUCCESS');
				$this->response->title = lang('SOME_LANGUAGE_VARIBLE');
				$this->response->msg = lang('SOME_LANGUAGE_VARIBLE');
				$this->response->data = 'data from service';
				$this->response->modalBtn['btn1']['text'] = lang('SOME_LANGUAGE_VARIBLE');
				$this->response->modalBtn['btn1']['link']  = 'link';
				$this->response->modalBtn['btn1']['action'] = 'none|destroy|redirect';
				$this->response->modalBtn['btn2']['text'] = lang('SOME_LANGUAGE_VARIBLE');
				$this->response->modalBtn['btn2']['link']  = 'link';
				$this->response->modalBtn['btn2']['action'] = 'none|destroy|redirect';

				break;
		}

		return $this->responseToTheView('callWs_Plantilla');
	}
}
