<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @info Controlador para las peticiones asíncronas de la aplicación
 * @author J. Enrique Peñaloza P
*/
class CallModels extends Novo_Controller {
	private $model;
	private $method;
	private $request;

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO CallModels Controller Class Initialized');
		if($this->input->is_ajax_request()) {
			$this->model = 'Novo_'.$this->dataRequest->who.'_Model';
			$this->method = 'callWs_'.$this->dataRequest->where.'_User';

		} else {
			show_404();
		}
	}

	public function index()
	{
		log_message('INFO', 'NOVO CallModels: index Method Initialized');
		$this->load->model($this->model, 'modelLoad');
		$method = $this->method;
		$dataResponse = $this->modelLoad->$method($this->dataRequest->data);
		if($dataResponse->code !== 303 && $dataResponse->code !== 3) {
			unset($dataResponse->icon);
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($dataResponse));
	}
}
