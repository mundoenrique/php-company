<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @info Controlador para el manejo de errores
 * @author J. Enrique Peñaloza Piñero
 * @date July 13th, 2021
 */
class Novo_Errors extends NOVO_Controller
{

	public function __construct()
	{
		parent::__construct();
		writeLog('INFO', 'Novo_Errors Controller Class Initialized');

		$this->config->set_item('language', BASE_LANGUAGE . '-base');
		$this->lang->load('errors');
		$customerLang = BASE_LANGUAGE . '-' . $this->config->item('customer_uri');
		$this->config->set_item('language', $customerLang);
		$pathLang = APPPATH . 'language' . DIRECTORY_SEPARATOR . $customerLang . DIRECTORY_SEPARATOR;

		if (file_exists($pathLang . 'errors_lang.php')) {
			$this->lang->load('errors');
		}
	}
	/**
	 * @info Método para la pagina no encontrda
	 * @author J. Enrique Peñaloza Piñero.
	 * @date July 13th, 2021
	 */
	public function pageNoFound()
	{
		writeLog('INFO', 'errors: pageNoFound Method Initialized');

		if ($this->input->is_ajax_request()) {
			$this->output->set_content_type('application/json')->set_status_header(404);
		}

		$view = 'html/error_404';
		$this->render->activeHeader = TRUE;
		$this->render->titlePage = lang('ERROR_PAGE_404');
		$this->views = ['errors/' . $view];
		$this->loadView($view);
	}
}
