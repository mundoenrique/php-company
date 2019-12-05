<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @info Controlador para las peticiones asíncronas de la aplicación
 * @author J. Enrique Peñaloza Piñero
*/
class CallModels extends Novo_Controller {

	public function __construct()
	{
		log_message('INFO', 'NOVO CallModels Controller Class Initialized');

		parent:: __construct();

		if($this->input->is_ajax_request()) {
			$this->rule = lcfirst($this->dataRequest->where);
			$this->model = 'Novo_'.ucfirst($this->dataRequest->who).'_Model';
			$this->method = 'callWs_'.ucfirst($this->dataRequest->where).'_'.$this->dataRequest->who;
		} else {
			show_404();
		}
	}

	public function index()
	{
		log_message('INFO', 'NOVO CallModels: index Method Initialized');

		if (!empty($this->dataRequest->data)){
			foreach($this->dataRequest->data AS $item => $value) {
				$_POST[$item] = $value;
			}

		}

		$this->appUserName = isset($_POST['user']) ? mb_strtoupper($_POST['user']) : $this->session->userdata('userName');

		log_message('DEBUG', 'NOVO ['.$this->appUserName.'] REQUEST FROM THE VIEW '.json_encode($this->dataRequest));

		unset($this->dataRequest);
		$valid = $this->verify_access->validateForm($this->rule, $this->countryUri, $this->appUserName);

		if($valid) {
			$this->request = $this->verify_access->createRequest($this->rule, $this->appUserName);
			$this->dataResponse = $this->loadModel($this->request);
		} else {
			$this->dataResponse = $this->verify_access->ResponseByDefect($this->appUserName);
		}

		$data = $this->dataResponse->data;
		$this->dataResponse->data = $this->verify_access->validateRedirect($data, $this->countryUri, $this->appUserName);
		$dataResponse = $this->cryptography->encrypt($this->dataResponse);
		$this->output->set_content_type('application/json')->set_output(json_encode($dataResponse));
	}
}
