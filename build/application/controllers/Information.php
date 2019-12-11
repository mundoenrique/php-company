<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Controlador para la vistas de información general alusuario
 * @author J. Enrique Peñaloza Piñero.
 */
class Information extends NOVO_Controller {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO Information class Initialized');
	}

	public function benefits()
	{
		$view = 'benefits';

		log_message('INFO', 'NOVO Information: benefits Method Initialized');
		$this->render->titlePage = 'Beneficios';
		$this->views = ['information/'.$view];
		$this->loadView($view);
	}

	public function terms()
	{
		log_message('INFO', 'NOVO Information: terms Method Initialized');
		$newUser = FALSE;
		$view = 'terms';

		if($this->session->flashdata('changePassword')) {
			if($this->config->item('country') !== ($this->session->countrySess)) {
				$urlRedirect = urlReplace($this->countryUri, $this->session->countrySess, base_url('inicio'));
				$this->load->model('Novo_User_Model', 'finishSession');
				$this->finishSession->callWs_FinishSession_User();
				redirect($urlRedirect, 'location');
				exit();
			}
			array_push(
				$this->includeAssets->jsFiles,
				"user/".$view
			);
			$newUser = TRUE;
			$this->session->set_flashdata('changePassword', $this->session->flashdata('changePassword'));
			$this->session->set_flashdata('userType', $this->session->flashdata('userType'));
			$this->render->message = lang('TERM_MESSAGE');
		}
		$this->render->titlePage = 'Condiciones';
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
		$this->render->titlePage = 'Tarifas';
		$this->render->referer = $this->input->server('HTTP_REFERER');
		$baseReferer = substr($this->render->referer, 0, strlen(base_url()));
		$this->render->goBack = $baseReferer === base_url();
		$this->views = ['information/rates'];
		$this->loadView('rates');
	}
}
