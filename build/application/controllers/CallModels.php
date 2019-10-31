<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @info Controlador para las peticiones asíncronas de la aplicación
 * @author J. Enrique Peñaloza P
*/
class CallModels extends Novo_Controller {
	protected $rule;

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO CallModels Controller Class Initialized');
		if($this->input->is_ajax_request()) {
			$this->model = 'Novo_'.$this->dataRequest->who.'_Model';
			$this->rule = lcfirst($this->dataRequest->where);
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

		$valid = $this->verify_access->validateForm($this->rule, $this->countryUri);

		if($valid) {
			$this->request = $this->verify_access->createRequest();
			$this->dataResponse = $this->loadModel($this->request);
		} else {
			$this->dataResponse = $this->verify_access->ResponseByDefect();
		}

		$this->dataResponse->data = $this->verify_access->validateRedirect($this->dataResponse->data, $this->countryUri);
		$dataResponse = $this->cryptography->encrypt($this->dataResponse);
		$this->output->set_content_type('application/json')->set_output(json_encode($dataResponse));
	}
}
