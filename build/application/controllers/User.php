<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @info Controlador para la vista principal de la aplicación
 * @author J. Enrique Peñaloza P
*/
class User extends NOVO_Controller {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'NOVO User Controller class Initialized');
		$this->lang->load('users');
	}
	/**
	 * @info Método que renderiza la vista de login
	 * @author J. Enrique Peñaloza P.
	 */
	public function home()
	{
		log_message('INFO', 'NOVO User: index Method Initialized');
		if($this->session->userdata('logged')) {
			$urlRedirect = str_replace($this->countryUri, $this->config->item('country'), base_url('dashboard'));
			redirect($urlRedirect, 'location');
			exit();
		}
		$userData = [
			'sessionId' => NULL,
			'idUsuario' => NULL,
			'userName' => NULL,
			'fullName' => NULL,
			'codigoGrupo' => NULL,
			'lastSession' => NULL,
			'token' => NULL,
			'cl_addr' => NULL,
			'countrySess' => NULL,
			'pais' => NULL,
			'nombreCompleto' => NULL
		];
		$this->session->unset_userdata($userData);

		$this->load->library('user_agent');

		$browser = strtolower($this->agent->browser());
		$version = (float) $this->agent->version();
		$noBrowser = "internet explorer";
		$sliderbar = true;
		$contentpage = 'users/content-login';


		$views = ['user/login', 'user/signin'];
		if($browser == $noBrowser && $version < 8.0){
			$sliderbar = false;
			$views = ['staticpages/content-browser'];
		}
		array_push(
			$this->includeAssets->cssFiles,
			"$this->countryUri/default"
		);
		array_push(
			$this->includeAssets->jsFiles,
			"third_party/jquery.md5",
			"third_party/jquery.balloon",
			"user/login",
			"$this->countryUri/clave"
		);
		if($this->countryUri !== 'bp') {
			array_push(
				$this->includeAssets->jsFiles,
				"third_party/jquery.kwicks",
				"user/kwicks"
			);
		}
		$this->views = $views;
		$this->render->titlePage = lang('SYSTEM_NAME');
		$this->loadView('login');
	}

	public function passwordRecovery()
	{
		log_message('INFO', 'NOVO User: passwordRecovery Method Initialized');
		array_push(
			$this->includeAssets->jsFiles,
			"user/pass-recovery",
			"third_party/jquery.validate",
			"validate-forms",
			"third_party/additional-methods"
		);
		$this->views = ['user/pass-recovery'];
		$this->render->titlePage = "Recuperar contraseña";
		$this->loadView('pass-recovery');
	}

	public function changePassword()
	{
		log_message('INFO', 'NOVO User: changePassword Method Initialized');
		if(!$this->session->flashdata('changePassword')) {
			redirect(base_url('inicio'), 'location');
			exit();
		}
		array_push(
			$this->includeAssets->jsFiles,
			"user/change-pass",
			"third_party/jquery.md5",
			"third_party/jquery.balloon",
			"third_party/jquery.validate",
			"validate-forms",
			"third_party/additional-methods"
		);
		switch($this->session->flashdata('changePassword')) {
			case 'newUser':
			$this->render->message = lang("MSG_NEW_PASS_USER");
			break;
			case 'expiredPass':
			$this->render->message = lang("MSG_NEW_PASS_CADU");
			break;
		}

		$this->render->fullName = $this->session->userdata('fullName');
		$this->render->userType = $this->session->flashdata('userType');
		$this->views = ['user/change-password'];
		$this->render->titlePage = "Recuperar contraseña";

		$this->session->set_flashdata('changePassword', $this->session->flashdata('changePassword'));
		$this->session->set_flashdata('userType', $this->session->flashdata('userType'));

		$this->loadView('pass-recovery');
	}
}
