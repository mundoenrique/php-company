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
		$valid = TRUE;
		$deleteFile = FALSE;

		if($_FILES) {
			$deleteFile = TRUE;
			$valid = $this->manageFile();
		}

		if($deleteFile) {
			unlink($_FILES);
		}

		if($valid) {
			$valid = $this->verify_access->validateForm($this->rule, $this->countryUri, $this->appUserName);
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

		$config['upload_path'] = $this->config->item('upload_bulk').'/'.'bulk/';
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

			log_message('DEBUG', 'NOVO  ['.$this->appUserName.'] VALIDATION FILEUPLOAD ERRORS: '.json_encode($errors, JSON_UNESCAPED_UNICODE));

			return FALSE;
		} else {
			$uploadData = (object) $this->upload->data();
			$_POST['file_name'] = $uploadData->file_name;
			$_POST['file_path'] = $uploadData->file_path;
			$_POST['raw_name'] = $uploadData->raw_name;
			$_POST['file_ext'] = $uploadData->file_ext;
			unset($_POST['typeFileText']);

			log_message('DEBUG', 'NOVO  ['.$this->appUserName.'] result fileupload: '.json_encode($uploadData, JSON_UNESCAPED_UNICODE));

			return TRUE;
		}
	}
}
