<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NOVO_Model extends CI_Model {
	public $dataAccessLog;
	public $className;
	public $accessLog;
	public $token;
	public $country;
	public $countryUri;
	public $dataRequest;
	public $isResponseRc;
	public $response;
	public $userName;

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO Model Class Initialized');

		$this->dataAccessLog = new stdClass();
		$this->dataRequest = new stdClass();
		$this->response = new stdClass();
		$this->country = $this->session->has_userdata('countrySess') ? $this->session->countrySess : $this->config->item('country');
		$this->countryUri = $this->session->countryUri;
		$this->token = $this->session->token ?: '';
		$this->userName = $this->session->userName;
	}
	/**
	 * @info Método para comunicación con el servicio
	 * @author J. Enrique Peñaloza Piñero.
	 * @date April 20th, 2019
	 */
	public function sendToService($model)
	{
		log_message('INFO', 'NOVO Model: sendToService Method Initialized');

		$this->accessLog = accessLog($this->dataAccessLog);
		$this->userName = $this->userName ?: mb_strtoupper($this->dataAccessLog->userName);

		$this->dataRequest->className = $this->className;
		$this->dataRequest->logAccesoObject = $this->accessLog;
		$this->dataRequest->token = $this->token;
		$this->dataRequest->pais = $this->country;
		$encryptData = $this->encrypt_connect->encode($this->dataRequest, $this->userName, $model);
		$request = ['bean'=> $encryptData, 'pais'=> $this->country];
		$response = $this->encrypt_connect->connectWs($request, $this->userName, $model);

		if(isset($response->rc)) {
			$responseDecrypt = $response;
		} else {
			$responseDecrypt = $this->encrypt_connect->decode($response, $this->userName, $model);
		}

		return $this->makeAnswer($responseDecrypt);
	}
	/**
	 * @info Método para comunicación con el servicio
	 * @author J. Enrique Peñaloza Piñero.
	 * @date April 20th, 2019
	 */
	public function sendFile($file, $model)
	{
		log_message('INFO', 'NOVO Model: sendFile Method Initialized');

		$responseUpload = $this->encrypt_connect->moveFile($file, $this->userName, $model);

		return $this->makeAnswer($responseUpload);
	}
	/**
	 * @info Método armar la respuesta a los modelos
	 * @author J. Enrique Peñaloza Piñero.
	 * @date December 11th, 2019
	 */
	protected function makeAnswer($responseModel)
	{
		log_message('INFO', 'NOVO Model: makeAnswer Method Initialized');

		$this->isResponseRc = (int) $responseModel->rc;
		$this->response->code = lang('RESP_DEFAULT_CODE');
		$this->response->title = lang('GEN_SYSTEM_NAME');
		$this->response->msg = '';
		$this->response->icon = lang('GEN_ICON_WARNING');
		$linkredirect = $this->session->has_userdata('productInf') ? 'detalle-producto' : lang('GEN_ENTERPRISE_LIST');
		$arrayResponse = [
			'btn1'=> [
				'text'=> FALSE,
				'link'=> base_url($linkredirect),
				'action'=> 'redirect'
			]
		];
		$this->response->data = $arrayResponse;

		if(!$this->input->is_ajax_request()) {
			$this->response->data = new stdClass();
			$this->response->data->resp = $arrayResponse;;
		}

		switch($this->isResponseRc) {
			case -29:
			case -61:
				$this->response->msg = lang('RESP_DUPLICATED_SESSION');
				if($this->session->has_userdata('logged') || $this->session->has_userdata('userId')) {
					$this->session->sess_destroy();
				}
				break;
			default:
				$this->response->msg = lang('RESP_MESSAGE_SYSTEM');
				$this->response->icon = lang('GEN_ICON_DANGER');
				break;
		}

		$this->response->msg = $this->isResponseRc == 0 ? lang('RESP_RC_0') : $this->response->msg;

		return $responseModel;
	}
	/**
	 * @info Método enviar el resultado de la consulta a la vista
	 * @author J. Enrique Peñaloza Piñero.
	 * @date November 21st, 2019
	 */
	public function responseToTheView($model)
	{
		log_message('INFO', 'NOVO Model: responseToView Method Initialized');
		log_message('DEBUG', 'NOVO ['.$this->userName.'] RESULT '.$model.' SENT TO THE VIEW '.json_encode($this->response));

		return $this->response;
	}
}
