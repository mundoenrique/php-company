<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Librerí para validar el acceso del usuario a las funcionalidades
 * @author J. Enrique Peñaloza Piñero
 * @date October 31th, 2019
 */
class Verify_Access {
	private $CI;
	private $class;
	private $method;
	private $operation;
	private $requestServ;
	private $responseDefect;

	public function __construct()
	{
		log_message('INFO', 'NOVO Verify_Access Library Class Initialized');
		$this->CI = &get_instance();
		$this->requestServ = new stdClass();
		$this->responseDefect = new stdClass();
	}
	/**
	 * @info método que valida los datos de los formularios enviados
	 * @author J. Enrique Peñaloza Piñero
	 * @date October 31th, 2019
	 */
	public function validateForm($rule, $countryUri)
	{
		log_message('INFO', 'NOVO Verify_Access: validateForm method initialized');

		$result = $this->CI->form_validation->run($rule);

		log_message('DEBUG', 'NOVO VALIDATION FORM '.$rule.': '.json_encode($result));

		if(!$result) {
			log_message('DEBUG', 'NOVO VALIDATION ERRORS: '.json_encode(validation_errors()));
		}

		languageLoad(NULL, $rule);
		$this->CI->config->set_item('language', 'spanish-'.$countryUri);
		languageLoad($countryUri, $rule);

		return $result;
	}
	/**
	 * @info método para crear el request al modelo
	 * @author J. Enrique Peñaloza Piñero
	 * @date October 31th, 2019
	 */
	public function createRequest()
	{
		log_message('INFO', 'NOVO Verify_Access: createRequest method initialized');

		foreach ($_POST AS $key => $value) {
			switch($key) {
				case 'request':
				case 'plot':
					continue;
				default:
				$this->requestServ->$key = $value;
			}
		}
		unset($_POST);

		return $this->requestServ;
	}
	/**
	 * @info método para crear el request al modelo
	 * @author J. Enrique Peñaloza Piñero
	 * @date October 31th, 2019
	 */
	public function ResponseByDefect()
	{
		log_message('INFO', 'NOVO Verify_Access: ResponseByDefect method initialized');

		$this->responseDefect->code = lang('RESP_DEFAULT_CODE');;
		$this->responseDefect->title = lang('GEN_SYSTEM_NAME');
		$this->responseDefect->msg = lang('RESP_VALIDATION_INPUT');
		$this->responseDefect->data = base_url('inicio');
		$this->responseDefect->icon = 'ui-icon-alert';
		$this->responseDefect->data = [
			'btn1'=> [
				'text'=> lang('GEN_BTN_ACCEPT'),
				'link'=> base_url('inicio'),
				'action'=> 'redirect'
			]
		];
		$this->CI->session->sess_destroy();

		return $this->responseDefect;
	}
	/**
	 * @info método que valida la autorización de acceso del usuario a las funcionalidades
	 * @author J. Enrique Peñaloza Piñero
	 * @date October 31th, 2019
	 */
	public function validateRedirect($redirectUrl, $countryUri)
	{
		log_message('INFO', 'NOVO Verify_Access: validateRedirect method initialized');

		$data = $redirectUrl;
		$dataLink = isset($data['btn1']['link']) ? $data['btn1']['link'] : FALSE;

		if(!is_array($data) && strpos($data, 'dashboard') !== FALSE) {
			$data = str_replace($countryUri.'/', $this->CI->config->item('country').'/', $data);
		} elseif($dataLink && !is_array($dataLink) && strpos($dataLink, 'dashboard') !== FALSE) {
			$dataLink = str_replace($countryUri.'/', $this->CI->config->item('country').'/', $dataLink);
			$data['btn1']['link'] =  $dataLink;
		}

		$redirectUrl = $data;

		return $redirectUrl;

	}
	/**
	 * @info método que valida la autorización de acceso del usuario a las funcionalidades
	 * @author J. Enrique Peñaloza Piñero
	 * @date October 31th, 2019
	 */
	public function accessAuthorization($validate)
	{
		log_message('INFO', 'NOVO Verify_Access: accessAuthorization method initialized');

		$auth = FALSE;
		switch($module) {
			case 'login':
			case 'benefits':
			case 'terms':
			case 'pass-recovery':
			case 'rates':
				$auth = TRUE;
				break;
			case 'change-password':
				$auth = ($this->session->flashdata('changePassword'));
				break;
			case 'products':
			case 'enterprise':
				$auth = ($this->render->logged);
				break;
			case 'rates':
				$auth = ($this->render->logged && $this->countryUri === 've');
				break;
		}

		return $auth;
	}
}
