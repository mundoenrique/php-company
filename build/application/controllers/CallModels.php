<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @info Controlador para las peticiones asíncronas de la aplicación
 * @author J. Enrique Peñaloza Piñero
*/
class CallModels extends Novo_Controller {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO CallModels Controller Class Initialized');
		if($this->input->is_ajax_request()) {
			$this->rule = lcfirst($this->dataRequest->where);
			$this->model = 'Novo_'.$this->dataRequest->who.'_Model';
			$this->method = 'callWs_'.$this->dataRequest->where.'_'.$this->dataRequest->who;

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

			unset($this->dataRequest);
		}

		$this->appUserName = isset($_POST['user']) ? mb_strtoupper($_POST['user']) : $this->session->userdata('userName');
		$valid = $this->verify_access->validateForm($this->rule, $this->countryUri, $this->appUserName);

		if($valid) {
			$this->request = $this->verify_access->createRequest($this->appUserName);
			$time = strtotime($this->request->currentime.' UTC');
			$dateInLocal = date("H", $time);
			$this->session->set_userdata('greeting', $dateInLocal);
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
