<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Controlador para el manejo de errores
 * @author J. Enrique Peñaloza Piñero
 * @date July 13th, 2021
*/
class Novo_Errors extends NOVO_Controller {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO Novo_Errors Controller Class Initialized');

		$this->config->set_item('language', BASE_LANGUAGE . '-base');
		$this->lang->load('errors');
		$customerLang = BASE_LANGUAGE . '-' . $this->config->item('customer_uri');
		$this->config->set_item('language', $customerLang);
		$pathLang = APPPATH . 'language' . DIRECTORY_SEPARATOR . $customerLang . DIRECTORY_SEPARATOR;

		if(file_exists($pathLang . 'errors_lang.php')) {
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
		log_message('INFO', 'NOVO errors: pageNoFound Method Initialized');

		$view = 'html/error_404';
		$this->render->activeHeader = TRUE;
		$this->render->titlePage = lang('ERROR_PAGE_404');
		$this->views = ['errors/'.$view];
		$this->loadView($view);
	}
}
