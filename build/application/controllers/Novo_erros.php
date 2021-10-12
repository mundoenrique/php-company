<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Controlador para el manejo de errores
 * @author J. Enrique Peñaloza Piñero
 * @date July 13th, 2021
*/
class Novo_erros extends NOVO_Controller {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO error404 Controller Class Initialized');
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
		$this->render->titlePage = lang('GEN_MENU_NOTIFICATIONS');
		$this->views = ['errors/'.$view];
		$this->loadView($view);
	}
}
