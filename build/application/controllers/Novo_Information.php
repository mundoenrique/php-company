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

	public function benefits()
	{
		$view = 'benefits';

		log_message('INFO', 'NOVO Information: benefits Method Initialized');
		$this->render->titlePage =lang('GEN_FOTTER_BENEFITS');
		$this->views = ['information/'.$view];
		$this->loadView($view);
	}

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

	public function rates()
	{
		log_message('INFO', 'NOVO Information: rates Method Initialized');

		$this->render->titlePage =lang('GEN_FOTTER_RATES');
		$this->render->referer = $this->input->server('HTTP_REFERER');
		$baseReferer = substr($this->render->referer, 0, strlen(base_url()));
		$this->render->goBack = $baseReferer === base_url();
		$this->views = ['information/rates'];
		$this->loadView('rates');
	}

	public function benefitsInf()
	{
		$view = 'benefits_info';

		array_push(
			$this->includeAssets->jsFiles,
			"user/benefitsInf",
		);

		log_message('INFO', 'NOVO Information: benefits Method Initialized');
		$this->render->titlePage =lang('GEN_FOTTER_BENEFITS');
		$this->render->activeHeader = TRUE;
		$this->views = ['information/'.$view];
		$this->loadView($view);
	}

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
}
