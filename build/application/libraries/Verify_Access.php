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
	public function validateForm($rule, $countryUri, $user)
	{
		log_message('INFO', 'NOVO Verify_Access: validateForm method initialized');

		$result = $this->CI->form_validation->run($rule);

		log_message('DEBUG', 'NOVO ['.$user.'] VALIDATION FORM '.$rule.': '.json_encode($result));

		if(!$result) {
			log_message('DEBUG', 'NOVO  ['.$user.'] VALIDATION '.$rule.' ERRORS: '.json_encode(validation_errors()));
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
	public function createRequest($rule, $user)
	{
		log_message('INFO', 'NOVO Verify_Access: createRequest method initialized');
		foreach ($_POST AS $key => $value) {
			switch($key) {
				case 'request':
				case 'plot':
				case 'ceo_name':
					continue;
				case 'currenTime':
					$time = strtotime($value.' UTC');
					$dateInLocal = date("H", $time);
					$this->CI->session->set_userdata('greeting', $dateInLocal);
					continue;
				case 'screenSize':
					$this->CI->session->set_userdata('screenSize', $value);
					continue;
				default:
				$this->requestServ->$key = $value;
			}
		}
		unset($_POST);
		log_message('INFO', 'NOVO ['.$user.'] '.$rule.' REQUEST CREATED '.json_encode($this->requestServ));
		return $this->requestServ;
	}
	/**
	 * @info método para crear el request al modelo
	 * @author J. Enrique Peñaloza Piñero
	 * @date October 31th, 2019
	 */
	public function ResponseByDefect($user)
	{
		log_message('INFO', 'NOVO Verify_Access: ResponseByDefect method initialized');

		$this->responseDefect->code = lang('RESP_DEFAULT_CODE');
		$this->responseDefect->title = lang('GEN_SYSTEM_NAME');
		$this->responseDefect->msg = lang('RESP_VALIDATION_INPUT');
		$this->responseDefect->data = base_url('inicio');
		$this->responseDefect->icon = lang('GEN_ICON_WARNING');
		$this->responseDefect->data = [
			'btn1'=> [
				'text'=> lang('GEN_BTN_ACCEPT'),
				'link'=> base_url('inicio'),
				'action'=> 'redirect'
			]
		];
		$this->CI->session->sess_destroy();

		log_message('DEBUG', 'NOVO  ['.$user.'] ResponseByDefect: '.json_encode($this->responseDefect));

		return $this->responseDefect;
	}
	/**
	 * @info método que valida la autorización de acceso del usuario a las funcionalidades
	 * @author J. Enrique Peñaloza Piñero
	 * @date October 31th, 2019
	 */
	public function accessAuthorization($module, $countryUri, $user)
	{
		log_message('INFO', 'NOVO Verify_Access: accessAuthorization method initialized');

		$auth = FALSE;
		switch($module) {
			case 'login':
			case 'benefits':
			case 'terms':
			case 'recoverPass':
			case 'finishSession':
				$auth = TRUE;
				break;
			case 'changePassword':
				$auth = ($this->CI->session->flashdata('changePassword') != NULL);
				break;
			case 'getEnterprises':
			case 'getProducts':
			case 'getProductDetail':
				$auth = ($this->CI->session->logged != NULL && $countryUri === 'bdb');
				break;
			case 'rates':
				$auth = ($this->CI->session->logged != NULL && $countryUri === 've');
				break;
		}

		log_message('INFO', 'NOVO ['.$user.'] accessAuthorization '.$module.': '.json_encode($auth));

		return $auth;
	}
	/**
	 * @info método que valida la autorización de acceso del usuario a las funcionalidades
	 * @author J. Enrique Peñaloza Piñero
	 * @date October 31th, 2019
	 */
	public function validateRedirect($redirectUrl, $countryUri)
	{
		log_message('INFO', 'NOVO Verify_Access: validateRedirect method initialized');

		$dataLink = isset($redirectUrl['btn1']['link']) ? $redirectUrl['btn1']['link'] : FALSE;

		if(!is_array($redirectUrl) && strpos($redirectUrl, 'dashboard') !== FALSE) {
			$redirectUrl = str_replace($countryUri.'/', $this->CI->config->item('country').'/', $redirectUrl);
		} elseif($dataLink && !is_array($dataLink) && strpos($dataLink, 'dashboard') !== FALSE) {
			$dataLink = str_replace($countryUri.'/', $this->CI->config->item('country').'/', $dataLink);
			$redirectUrl['btn1']['link'] =  $dataLink;
		}

		return $redirectUrl;
	}
}
