<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Controlador para la vistas de información general alusuario
 * @author J. Enrique Peñaloza P.
 */
class Information extends NOVO_Controller {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO Information class Initialized');
	}

	public function benefits()
	{
		log_message('INFO', 'NOVO Information: benefits Method Initialized');
		$this->render->titlePage = 'Beneficios';
		$this->views = ['information/benefits'];
		$this->loadView('benefits');
	}

	public function terms()
	{
		log_message('INFO', 'NOVO Information: terms Method Initialized');
		$newUser = FALSE;
		if($this->session->flashdata('changePassword')) {
			if($this->config->item('country') !== ($this->session->userdata('countrySess'))) {
				$urlRedirect = urlReplace($this->countryUri, $this->session->userdata('countrySess'), base_url('inicio'));
				$this->load->model('Novo_User_Model', 'finishSession');
				$this->finishSession->callWs_FinishSession_User();
				redirect($urlRedirect, 'location');
				exit();
			}
			array_push(
				$this->includeAssets->jsFiles,
				"user/terms"
			);
			$newUser = TRUE;
			$this->session->set_flashdata('changePassword', $this->session->flashdata('changePassword'));
			$this->session->set_flashdata('userType', $this->session->flashdata('userType'));
			$this->render->message = "Estimado usuario debes leer y aceptar los términos de uso y confidencialidad para "; $this->render->message.= "comenzar a usar nuestra plataforma.";
		}
		$this->render->titlePage = 'Condiciones';
		$this->render->referer = $this->input->server('HTTP_REFERER');
		$baseReferer = substr($this->render->referer, 0, strlen(base_url()));
		$this->render->newUser = $newUser;
		$this->render->goBack = ($baseReferer === base_url()) && !$newUser;
		$this->lang->load('users');
		$this->views = ['information/terms'];
		$this->loadView('terms');
	}

	public function rates()
	{
		log_message('INFO', 'NOVO Information: rates Method Initialized');
		$this->render->titlePage = 'Condiciones';
		$this->render->referer = $this->input->server('HTTP_REFERER');
		$baseReferer = substr($this->render->referer, 0, strlen(base_url()));
		$this->render->goBack = $baseReferer === base_url();
		$this->lang->load('users');
		$this->views = ['information/rates'];
		$this->loadView('rates');
	}
}
