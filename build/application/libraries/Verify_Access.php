<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Librería para validar el acceso del usuario a las funciones
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
	private $user;

	public function __construct()
	{
		log_message('INFO', 'NOVO Verify_Access Library Class Initialized');

		$this->CI = &get_instance();
		$this->requestServ = new stdClass();
		$this->user = $this->CI->session->userName;
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

		log_message('DEBUG', 'NOVO ['.$user.'] VALIDATION FORM '.$rule.': '.json_encode($result, JSON_UNESCAPED_UNICODE));

		if(!$result) {
			log_message('DEBUG', 'NOVO  ['.$user.'] VALIDATION '.$rule.' ERRORS: '.json_encode(validation_errors(), JSON_UNESCAPED_UNICODE));
		}

		languageLoad('generic', NULL, $rule);
		$this->CI->config->set_item('language', 'spanish-'.$countryUri);
		$newViews = $this->CI->config->item('new-views');

		if($newViews != '') {
			$this->CI->lang->load('config'.$newViews);
		}

		languageLoad('specific', $countryUri, $rule);

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
				case 'screenSize':
					$this->CI->session->set_userdata('screenSize', $value);
					continue;
				default:
				$this->requestServ->$key = $value;
			}
		}

		unset($_POST);
		log_message('INFO', 'NOVO ['.$user.'] '.$rule.' REQUEST CREATED '.json_encode($this->requestServ, JSON_UNESCAPED_UNICODE));

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

		$this->responseDefect = new stdClass();
		$this->responseDefect->code = lang('RESP_DEFAULT_CODE');
		$this->responseDefect->title = lang('GEN_SYSTEM_NAME');
		$this->responseDefect->msg = lang('RESP_VALIDATION_INPUT');
		$this->responseDefect->data = base_url('inicio');
		$this->responseDefect->icon = lang('GEN_ICON_WARNING');
		$this->responseDefect->data = [
			'btn1'=> [
				'text'=> lang('GEN_BTN_ACCEPT'),
				'link'=> 'inicio',
				'action'=> 'redirect'
			]
		];

		if($this->CI->session->has_userdata('logged')) {
			$this->CI->load->model('Novo_User_Model', 'finishSession');
			$this->CI->finishSession->callWs_FinishSession_User();
		}

		log_message('DEBUG', 'NOVO  ['.$user.'] ResponseByDefect: '.json_encode($this->responseDefect, JSON_UNESCAPED_UNICODE));

		return $this->responseDefect;
	}
	/**
	 * @info método que valida la autorización de acceso del usuario a las vistas
	 * @author J. Enrique Peñaloza Piñero
	 * @date October 31th, 2019
	 */
	public function accessAuthorization($module, $countryUri, $user = FALSE)
	{
		log_message('INFO', 'NOVO Verify_Access: accessAuthorization method initialized');

		$auth = FALSE;
		$user = $user ?: $this->user;
		$freeAccess = ['login', 'suggestion', 'validateCaptcha', 'finishSession', 'terms', 'singleSignon'];
		$auth = in_array($module, $freeAccess);

		if(!$auth) {
			switch($module) {
				case 'recoverPass':
				case 'benefits':
					$clients = ['novo', 'pichincha', 'banorte', 'produbanco'];
					$auth = in_array($this->CI->config->item('client'), $clients);
					break;
				case 'terms':
					$clients = ['pichincha', 'banco-bog'];
					if(in_array($this->CI->config->item('client'), $clients)) {
						$auth = $this->CI->session->flashdata('changePassword');
					}
					break;
				case 'changePassword':
					$auth = ($this->CI->session->flashdata('changePassword') != NULL);
					break;
				case 'rates':
					$auth = ($this->CI->session->has_userdata('logged') && $countryUri === 've');
					break;
				case 'getEnterprises':
				case 'getProducts':
				case 'keepSession':
				case 'options':
					$auth = ($this->CI->session->has_userdata('logged'));
					break;
				case 'getProductDetail':
					$auth = ($this->CI->session->has_userdata('enterpriseInf'));
					break;
				case 'getPendingBulk':
				case 'loadBulk':
				case 'getDetailBulk':
					$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('TEBCAR'));
					break;
				case 'unnamedRequest':
					$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('TICARG'));
					break;
				case 'unnamedAffiliate':
				case 'unnmamedDetail':
					$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('TIINVN'));
					break;
				case 'confirmBulk':
					$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('TEBCAR', 'TEBCON'));
					break;
				case 'deleteNoConfirmBulk':
					$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('TEBCAR', 'TEBELC'));
					break;
				case 'authorizeBulkList':
				case 'signBulkList':
				case 'authorizeBulk':
				case 'bulkDetail':
				case 'calculateServiceOrder':
					$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('TEBAUT'));
					break;
				case 'deleteConfirmBulk':
				case 'disassConfirmBulk':
					$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('TEBAUT', 'TEBELI'));
					break;
				case 'serviceOrder':
				case 'cancelServiceOrder':
				case 'exportFiles':
					$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('TEBAUT') && $this->verifyAuthorization('TEBORS'));
					break;
				case 'serviceOrders':
				case 'getServiceOrders':
					$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('TEBORS'));
					break;
				case 'clearServiceOrders':
					$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('TEBORS', 'TEBANU'));
					break;
				case 'transfMasterAccount':
					$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('TRAMAE'));
					break;
				case 'getReportsList':
					$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('REPALL'));
					break;
				case 'getReport':
				case 'deleteFile':
					$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('REPALL', 'REPALL'));
					break;
				case 'accountStatus':
					$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('REPEDO'));
					break;
				case 'replacement':
					$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('REPREP'));
					break;
				case 'closingBalance':
					$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('REPSAL'));
					break;
				case 'userActivity':
					$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('REPUSU'));
					break;
				case 'rechargeMade':
					$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('REPPRO'));
					break;
				case 'issuedCards':
					$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('REPTAR'));
					break;
				case 'categoryExpense':
					$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('REPCAT'));
					break;
				case 'masterAccount':
					$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('REPCON'));
					break;
				case 'cardHolders':
					$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('TEBTHA'));
					break;
			}
		}

		log_message('INFO', 'NOVO ['.$user.'] accessAuthorization '. $module.': '.json_encode($auth, JSON_UNESCAPED_UNICODE));

		return $auth;
	}

	/**
	 * @info método que valida la autorización de acceso del usuario a las funcionalidades
	 * @author J. Enrique Peñaloza Piñero
	 * @date October 31th, 2019
	 */
	public function verifyAuthorization($moduleLink, $function = FALSE)
	{
		log_message('INFO', 'NOVO Verify_Access: verifyAuthorization method initialized');

		$userAccess = $this->CI->session->user_access;
		$items = [];
		$auth = FALSE;

		if($userAccess) {
			foreach($userAccess AS $item) {
				foreach($item->modulos AS $module) {
					if(!$function) {
						$items[] = $module->idModulo;
					} else {
						foreach($module->funciones AS $functions) {
							if($module->idModulo != $moduleLink) {
								continue;
							}
							$items[] = $functions->accodfuncion;
						}
					}
				}
			}

			$access = $function ? $function : $moduleLink;
			$prompter = $function ? '->'.$function : '';
			$auth = in_array($access, $items);
			log_message('INFO', 'NOVO ['.$this->user.'] verifyAuthorization '.$moduleLink.$prompter.': '.json_encode($auth, JSON_UNESCAPED_UNICODE));
		}


		return $auth;
	}
	/**
	 * @info método que valida la redirección del core correcto
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
