<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @info Controlador para las peticiones asíncronas de la aplicación
 * @author J. Enrique Peñaloza Piñero
 * @date April 20th, 2019
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
	/**
	 * @info Método que valida y maneja las peticiones asincornas de la aplicación
	 * @author J. Enrique Peñaloza Piñero
	 * @date April 20th, 2019
	 */
	public function index()
	{
		log_message('INFO', 'NOVO CallModels: index Method Initialized');

		if (!empty($this->dataRequest->data)){
			foreach($this->dataRequest->data AS $item => $value) {
				$_POST[$item] = $value;
			}
		}

		$this->appUserName = isset($_POST['user']) ? mb_strtoupper($_POST['user']) : $this->session->userName;

		log_message('DEBUG', 'NOVO ['.$this->appUserName.'] REQUEST FROM THE VIEW '.json_encode($this->dataRequest ,JSON_UNESCAPED_UNICODE));

		unset($this->dataRequest);
		$valid = $this->verify_access->validateForm($this->rule, $this->countryUri, $this->appUserName);

		if($_FILES && $valid) {
			$valid = $this->manageFile();
		} else if($_FILES) {
			unset($_FILES);
		}

		if($valid) {
			$this->request = $this->verify_access->createRequest($this->rule, $this->appUserName);
			$this->dataResponse = $this->loadModel($this->request);
		} else {
			$this->dataResponse = $this->verify_access->ResponseByDefect($this->appUserName);
		}

		$data = $this->dataResponse->data;
		$this->dataResponse->data = $this->verify_access->validateRedirect($data, $this->countryUri, $this->appUserName);
		$dataResponse = $this->cryptography->encrypt($this->dataResponse);
		$this->output->set_content_type('application/json')->set_output(json_encode($dataResponse, JSON_UNESCAPED_UNICODE));
	}
	/**
	 * @info Método que maneja los archivos enviados al servidor
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 11th, 2019
	 */
	private function manageFile()
	{
		log_message('INFO', 'NOVO CallModels: manageFile Method Initialized');

		$config['upload_path'] = $this->config->item('UPLOAD_BULK');
		$config['allowed_types'] = 'txt|xls|xlsx';
		$ext =  explode('.', $_FILES['file']['name']);
		$ext = end($ext);
		$pattern = [];
		$replace = [];
		$pattern[0] = '/\s/';
		$pattern[1] = '/\(/';
		$replace[0] = '';
		$replace[1] = '/_/';
		$filename = '_'.substr(preg_replace($pattern, $replace, $_POST['typeFileText']), 0, 15);
		$filename = $filename.'_'.time();
		$filename = mb_strtolower($this->countryUri.$filename.'.'.$ext);
		$config['file_name'] = $filename;
		$this->load->library('upload', $config);

		if(!$this->upload->do_upload('file')) {
			$errors = $this->upload->display_errors();
			log_message('DEBUG', 'NOVO  ['.$this->appUserName.'] VALIDATION '.$rule.' ERRORS: '.json_encode($errors, JSON_UNESCAPED_UNICODE));
		} else {
			$data = array('upload_data' => $this->upload->data());
			$nombreArchivo = $data["upload_data"]["raw_name"];//NOMBRE ARCHIVO SIN EXTENSION
			$rutaArchivo = $data["upload_data"]["file_path"];
			$extensionArchivo = $data["upload_data"]["file_ext"];
			$fileInfo = $this->upload->data();
		}
	}
}
