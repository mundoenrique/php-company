<?php defined('BASEPATH') or exit('No direct script access allowed');
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
		$this->views = ['information/benefits'];
		$this->render->titlePage = 'Beneficios';
		$this->loadView('benefits');
	}

	public function terms()
	{
		log_message('INFO', 'NOVO Information: terms Method Initialized');
		$newUser = FALSE;
		if($this->session->flashdata('changePassword')) {
			$newUser = TRUE;
			$this->session->set_flashdata('changePassword', 'newUser');
		}
		$this->views = ['information/terms'];
		$this->render->titlePage = 'Condiciones';
		$this->render->referer = $this->input->server('HTTP_REFERER');
		$baseReferer = substr($this->render->referer, 0, strlen(base_url()));
		$this->render->newUser = $newUser;
		$this->render->goBack = ($baseReferer === base_url()) && !$newUser;
		$this->lang->load('users');
		$this->loadView('terms');
	}

	public function rates()
	{
		log_message('INFO', 'NOVO Information: rates Method Initialized');
		$this->views = ['information/rates'];
		$this->render->titlePage = 'Condiciones';
		$this->render->referer = $this->input->server('HTTP_REFERER');
		$baseReferer = substr($this->render->referer, 0, strlen(base_url()));
		$this->render->goBack = $baseReferer === base_url();
		$this->lang->load('users');
		$this->loadView('rates');
	}
}
