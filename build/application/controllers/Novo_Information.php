<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Controlador para la vistas de información general alusuario
 * @author J. Enrique Peñaloza Piñero.
 */
class Novo_Information extends NOVO_Controller {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO Information Class Initialized');
	}
	/**
	 * @info Método que renderiza la vista de beneficios de la aplicación
	 * @author Jennifer Cadiz
	 * @date September 3rd, 2021
	 */
	public function benefitsInf()
	{
		$view = 'benefits_info';

		array_push(
			$this->includeAssets->jsFiles,
			"user/benefitsInf",
		);

		log_message('INFO', 'NOVO Information: benefitsInf Method Initialized');
		$this->render->titlePage =lang('GEN_FOTTER_BENEFITS');
		$this->render->activeHeader = TRUE;
		$this->views = ['information/'.$view];
		$this->loadView($view);
	}
	/**
	 * @info Método que renderiza la vista terminos y condiciones
	 * @author J. Enrique Peñaloza Piñero.
	 * @date April 16th, 2019
	 */
	public function termsInf()
	{
		log_message('INFO', 'NOVO Information: termsInf Method Initialized');
		$newUser = FALSE;
		$view = 'terms';

		if($this->session->flashdata('changePassword')) {
			array_push(
				$this->includeAssets->jsFiles,
				"user/terms"
			);

			$newUser = TRUE;
			$this->session->keep_flashdata('changePassword');
			$this->session->keep_flashdata('userType');
			$this->render->message = lang('TERMS_MESSAGE');
		}

		$this->render->titlePage =lang('GEN_FOTTER_TERMS');
		$this->render->activeHeader = TRUE;
		$this->render->referer = $this->input->server('HTTP_REFERER');
		$baseReferer = substr($this->render->referer, 0, strlen(base_url()));
		$this->render->newUser = $newUser;
		$this->render->goBack = ($baseReferer === base_url()) && !$newUser;
		$this->views = ['information/'.$view];
		$this->loadView($view);
	}
	/**
	 * @info Método que renderiza la vista de tarifas de la aplicación
	 * @author Jennifer Cadiz
	 * @date September 3rd, 2021
	 */
	public function ratesInf()
	{
		$view = 'rates_info';
		array_push(
			$this->includeAssets->cssFiles,
			"third_party/dataTables-1.10.20"
		);
		array_push(
			$this->includeAssets->jsFiles,
			"third_party/dataTables-1.10.20",
			"user/ratesInf"
		);

		log_message('INFO', 'NOVO Information: rates Method Initialized');
		$this->render->titlePage =lang('GEN_FOTTER_RATES');
		$this->render->referer = $this->input->server('HTTP_REFERER');
		$baseReferer = substr($this->render->referer, 0, strlen(base_url()));
		$this->render->goBack = $baseReferer === base_url();
		$this->views = ['information/'.$view];
		$this->loadView($view);
	}
	/*
	|--------------------------------------------------------------------------
	| TEMPORAL METHODS
	|--------------------------------------------------------------------------
	*/
	/**
	 * @info Método que renderiza la vista de beneficios de la aplicación
	 * @author J. Enrique Peñaloza Piñero.
	 * @date April 16th, 2019
	 */
	public function benefits()
	{
		$view = 'benefits';

		log_message('INFO', 'NOVO Information: benefits Method Initialized');
		$this->render->titlePage =lang('GEN_FOTTER_BENEFITS');
		$this->views = ['information/'.$view];
		$this->loadView($view);
	}
	/**
	 * @info Método que renderiza la vista terminos y condiciones
	 * @author J. Enrique Peñaloza Piñero.
	 * @date April 16th, 2019
	 */
	public function terms()
	{
		log_message('INFO', 'NOVO Information: terms Method Initialized');
		$newUser = FALSE;
		$view = 'terms';

		if($this->session->flashdata('changePassword')) {
			array_push(
				$this->includeAssets->jsFiles,
				"user/terms"
			);

			$newUser = TRUE;
			$this->session->set_flashdata('changePassword', $this->session->flashdata('changePassword'));
			$this->session->set_flashdata('userType', $this->session->flashdata('userType'));
			$this->render->message = lang('TERMS_MESSAGE');
		}
		$this->render->titlePage =lang('GEN_FOTTER_TERMS');
		$this->render->activeHeader = TRUE;
		$this->render->referer = $this->input->server('HTTP_REFERER');
		$baseReferer = substr($this->render->referer, 0, strlen(base_url()));
		$this->render->newUser = $newUser;
		$this->render->goBack = ($baseReferer === base_url()) && !$newUser;
		$this->views = ['information/'.$view];
		$this->loadView($view);
	}
}
