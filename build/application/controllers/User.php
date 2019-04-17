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
	public function index()
	{
		log_message('INFO', 'NOVO User: index Method Initialized');
		array_push(
			$this->includeAssets->cssFiles,
			"$this->countryUri/default"
		);
		array_push(
			$this->includeAssets->jsFiles,
			"third_party/jquery.kwicks",
			"third_party/jquery.md5",
			"user/login",
			"$this->countryUri/clave"

		);
		$this->views = ['user/login', 'user/signin'];
		$this->render->titlePage = lang('SYSTEM_NAME');
		$this->loadView('login');
	}

	public function passwordRecovery()
	{
		log_message('INFO', 'NOVO User: passwordRecovery Method Initialized');
		array_push(
			$this->includeAssets->jsFiles,
			"user/pass-recovery"
		);
		$this->views = ['user/pass-recovery'];
		$this->render->titlePage = "Recuperar contraseña";
		$this->loadView('pass-recovery');
	}
}
