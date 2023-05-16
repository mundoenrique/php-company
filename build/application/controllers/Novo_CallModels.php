<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @info Controlador para las peticiones asíncronas de la aplicación
 * @author J. Enrique Peñaloza Piñero
 * @date April 20th, 2019
*/
class Novo_CallModels extends Novo_Controller {

	public function __construct()
	{
		parent:: __construct();
		writeLog('INFO', 'CallModels Controller Class Initialized');

		if($this->input->is_ajax_request()) {
			$this->fileLanguage = lcfirst($this->dataRequest->who);
			$this->validationMethod = lcfirst($this->dataRequest->where);
			$this->modelClass = 'Novo_' . ucfirst($this->dataRequest->who) . '_Model';
			$this->modelMethod = 'callWs_' . ucfirst($this->dataRequest->where) . '_' . $this->dataRequest->who;
		} else {
			redirect('page-no-found', 'Location', 301);
		}
	}
	/**
	 * @info Método que valida y maneja las peticiones asincornas de la aplicación
	 * @author J. Enrique Peñaloza Piñero
	 * @date April 20th, 2019
	 */
	public function index()
	{
		writeLog('INFO', 'CallModels: index Method Initialized');

		if (!empty($this->dataRequest->data)) {
			foreach($this->dataRequest->data AS $item => $value) {
				$_POST[$item] = $value;
			}
		}

		unset($this->dataRequest);
		$valid = $this->verify_access->accessAuthorization($this->validationMethod);;

		if(!empty($_FILES) && $valid) {
			$valid = $this->manageFile();
		}

		if($valid) {
			$this->request = $this->verify_access->createRequest($this->modelClass, $this->modelMethod);
			$valid = $this->verify_access->validateForm($this->validationMethod);
		}

		LoadLangFile('generic', $this->fileLanguage, $this->customerLang);
		$this->config->set_item('language', BASE_LANGUAGE . '-' . $this->customerLang);
		LoadLangFile('specific', $this->fileLanguage, $this->customerLang);

		if ($valid) {
			$this->dataResponse = $this->loadModel($this->request);
		} else {
			$this->dataResponse = $this->verify_access->responseByDefect();
		}

		$modalBtn = $this->dataResponse->modalBtn;
		$this->dataResponse->modalBtn = $this->verify_access->validateRedirect($modalBtn, $this->customerUri);

		$dataResponse = lang('CONF_CYPHER_DATA') == 'ON' ?  $this->cryptography->encrypt($this->dataResponse) : $this->dataResponse;
		$this->output->set_content_type('application/json')->set_output(json_encode($dataResponse, JSON_UNESCAPED_UNICODE));
	}
	/**
	 * @info Método que maneja los archivos enviados al servidor
	 * @author J. Enrique Peñaloza Piñero
	 * @date December 11th, 2019
	 */
	private function manageFile()
	{
		writeLog('INFO', 'CallModels: manageFile Method Initialized');
		writeLog('DEBUG', 'UPLOAD FILE MIMETYPE: ' . $_FILES['file']['type']);

		$ext =  explode('.', $_FILES['file']['name']);
		$ext = end($ext);
		$pattern = [
			'/\s/', '/\(/', '/\)/',
			'/á/', '/à/', '/ä/', '/â/', '/ª/', '/Á/', '/À/', '/Â/', '/Ä/',
			'/é/', '/è/', '/ë/', '/ê/', '/É/', '/È/', '/Ê/', '/Ë/',
			'/í/', '/ì/', '/ï/', '/î/', '/Í/', '/Ì/', '/Ï/', '/Î/',
			'/ó/', '/ò/', '/ö/', '/ô/', '/Ó/', '/Ò/', '/Ö/', '/Ô/',
			'/ú/', '/ù/', '/ü/', '/û/', '/Ú/', '/Ù/', '/Û/', '/Ü/',
			'/ñ/', '/Ñ/', '/ç/', '/Ç/'
		];
		$replace = [
			'_', '_', '',
			'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
			'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
			'i', 'i', 'i', 'i', 'i', 'i', 'i', 'i',
			'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
			'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
			'n', 'N', 'c', 'C'
		];
		$filename = '_'.substr(preg_replace($pattern, $replace, $_POST['typeBulkText']), 0, 19);
		$filenameT = time().'_'.date('s').$this->customerUri.$filename;
		$filenameT = mb_strtolower($filenameT.'.'.$ext);
		$config['file_name'] = $filenameT;
		$config['upload_path'] = UPLOAD_PATH;
		$config['allowed_types'] = lang('CONF_FILES_EXTENSION');
		$config['max_size'] = lang('CONF_MAX_FILE_SIZE');
		$this->load->library('upload', $config);

		if(!$this->upload->do_upload('file')) {
			$errors = $this->upload->display_errors();

			writeLog('DEBUG', 'VALIDATION FILEUPLOAD ERRORS: ' . json_encode($errors, JSON_UNESCAPED_UNICODE));

			$valid = FALSE;
		} else {
			$uploadData = (object) $this->upload->data();
			$_POST['fileName'] = $uploadData->file_name;
			$_POST['filePath'] = $uploadData->file_path;
			$_POST['rawName'] = mb_strtolower($this->customerUri.$filename);
			$_POST['fileExt'] = substr($uploadData->file_ext, 1);
			unset($_POST['typeBulkText'], $_POST['file']);

			$valid = TRUE;
		}

		return $valid;
	}
}
