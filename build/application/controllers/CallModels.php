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

		$result = $this->form_validation->run($this->rule);
		log_message('DEBUG', 'NOVO VALIDATION FORM '.$this->rule.': '.json_encode($result));
		if($result) {
			foreach ($_POST AS $key => $value) {
				switch($key) {
					case 'request':
					case 'plot':
						continue;
					default:
					$this->request->$key = $value;
				}
			}
			unset($_POST);
			languageLoad(NULL, $this->rule);
			$this->config->set_item('language', 'spanish-'.$this->countryUri);
			languageLoad($this->countryUri, $this->rule);
			$this->load->model($this->model, 'modelLoad');
			$method = $this->method;
			$this->dataResponse = $this->modelLoad->$method($this->request);

		} else {
			log_message('DEBUG', 'NOVO VALIDATION ERRORS: '.json_encode(validation_errors()));
			$this->dataResponse->code = lang('RES_DEFAULT_CODE');;
			$this->dataResponse->title = lang('SYSTEM_NAME');
			$this->dataResponse->msg = lang('CALLMODELS_INDEX_MSG');
			$this->dataResponse->data = base_url('inicio');
			$this->dataResponse->icon = 'ui-icon-alert';
			$this->dataResponse->data = [
				'btn1'=> [
					'text'=> lang('GEN_BTN_ACCEPT'),
					'link'=> base_url('inicio'),
					'action'=> 'redirect'
				]
			];
			$this->session->sess_destroy();
		}
		$data = $this->dataResponse->data;
		$dataLink = isset($data['btn1']['link']) ? $data['btn1']['link'] : FALSE;
		if(!is_array($data) && strpos($data, 'dashboard') !== FALSE) {
			$data = str_replace($this->countryUri.'/', $this->config->item('country').'/', $data);
		} elseif($dataLink && !is_array($dataLink) && strpos($dataLink, 'dashboard') !== FALSE) {
			$dataLink = str_replace($this->countryUri.'/', $this->config->item('country').'/', $dataLink);
			$data['btn1']['link'] =  $dataLink;
		}
		$this->dataResponse->data = $data;
		$dataResponse = $this->cryptography->encrypt($this->dataResponse);
		$this->output->set_content_type('application/json')->set_output(json_encode($dataResponse));
	}
}
