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
	public function validateForm($rule, $customerUri, $user, $class = FALSE)
	{

		log_message('INFO', 'NOVO Verify_Access: validateForm method initialized');

		$result = $this->CI->form_validation->run($rule);

		log_message('DEBUG', 'NOVO [' . $user . '] VALIDATION FORM ' . $rule . ': ' .
			json_encode($result, JSON_UNESCAPED_UNICODE));

		if(!$result) {
			log_message('DEBUG', 'NOVO  [' . $user . '] VALIDATION ' . $rule . ' ERRORS: ' .
				json_encode(validation_errors(), JSON_UNESCAPED_UNICODE));
		}

		if ($class) {
			$this->CI->config->set_item('language', BASE_LANGUAGE.'-base');
			languageLoad('generic', $class);
			$this->CI->config->set_item('language', BASE_LANGUAGE.'-'.$customerUri);
			languageLoad('specific', $class);
		}

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
				break;
				case 'screenSize':
					$this->CI->session->set_userdata('screenSize', $value);
				break;
				default:
					if (!$this->CI->input->is_ajax_request()) {
						$value = $this->CI->security->xss_clean(strip_tags($value));
					}

					$this->requestServ->$key = $value;
			}
		}

		unset($_POST);
		log_message('DEBUG', 'NOVO [' . $user . '] IP ' . $this->CI->input->ip_address() . ' ' . $rule .' REQUEST CREATED '.
			json_encode($this->requestServ, JSON_UNESCAPED_UNICODE));

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

		$singleSession = base64_decode(get_cookie('singleSession', TRUE));
		$linkredirect = $singleSession == 'SignThird' ? 'ingresar/'.lang('CONF_LINK_SIGNOUT_END')
			: lang('CONF_LINK_SIGNIN');
		$this->responseDefect = new stdClass();
		$this->responseDefect->code = lang('CONF_DEFAULT_CODE');
		$this->responseDefect->title = lang('GEN_SYSTEM_NAME');
		$this->responseDefect->msg = lang('GEN_VALIDATION_INPUT');
		$this->responseDefect->icon = lang('CONF_ICON_WARNING');
		$this->responseDefect->modalBtn = [
			'btn1'=> [
				'text'=> lang('GEN_BTN_ACCEPT'),
				'link'=> $linkredirect,
				'action'=> 'redirect'
			]
		];

		if($this->CI->session->has_userdata('logged')) {
			$this->responseDefect->msg = lang('GEN_VALIDATION_INPUT_LOGGED');
			$this->CI->load->model('Novo_User_Model', 'finishSession');
			$this->CI->finishSession->callWs_FinishSession_User();
		}

		log_message('DEBUG', 'NOVO  [' . $user . '] IP ' . $this->CI->input->ip_address() . ' ResponseByDefect: ' .
			json_encode($this->responseDefect, JSON_UNESCAPED_UNICODE));

		return $this->responseDefect;
	}
	/**
	 * @info método que valida la autorización de acceso del usuario a las vistas
	 * @author J. Enrique Peñaloza Piñero
	 * @date October 31th, 2019
	 */
	public function accessAuthorization($module, $customerUri, $user = FALSE)
	{
		log_message('INFO', 'NOVO Verify_Access: accessAuthorization method initialized');

		$user = $user ?? $this->user;

		if ($this->CI->session->has_userdata('userId') && $this->CI->session->clientAgent != $this->CI->agent->agent_string()) {
			clearSessionsVars();
		}

		switch($module) {
			case 'signIn':
				$auth = TRUE;
				$uriSegmwnts = $this->CI->uri->segment(2).'/'.$this->CI->uri->segment(3);
				$ajaxRequest = $this->CI->input->is_ajax_request();

				if (SINGLE_SIGN_ON && $uriSegmwnts !== 'internal/novopayment' && ENVIRONMENT === 'production' && !$ajaxRequest) {
					redirect('page-no-found', 'Location', 301);
					// show_404();
					exit();
				} elseif ($uriSegmwnts === 'internal/novopayment' && ENVIRONMENT !== 'production' && !$ajaxRequest) {
					redirect('page-no-found', 'Location', 301);
					exit();
				}
				break;
			case 'recoverPass':
			case 'passwordRecovery':
				$auth = lang('CONF_RECOV_PASS') == 'ON';
				break;
			case 'recoverAccess':
			case 'validateOtp':
				$auth = lang('CONF_RECOV_ACCESS') == 'ON';
				break;
			case 'changeEmail':
			case 'changeTelephones':
			case 'changeDataEnterprice':
			case 'addContact':
			case 'addBranches':
			case 'deleteContact':
			case 'getEnterprises':
			case 'getEnterprise':
			case 'getUser':
			case 'obtenerIdEmpresa':
			case 'keepSession':
			case 'options':
			case 'getFileIni':
			case 'getBranches':
			case 'getContacts':
			case 'uploadFileBranches':
			case 'updateBranches':
			case 'updateContact':
			case 'deleteFile':
			case 'getProducts':
				$auth = ($this->CI->session->has_userdata('logged'));
				break;
			case 'changePassword':
			case 'changePass':
				$auth = $this->CI->session->has_userdata('logged') || $this->CI->session->flashdata('changePassword') != NULL;
				break;
			case 'benefits':
			case 'benefitsInf':
				$auth = lang('CONF_BENEFITS') == 'ON';
				break;
			case 'ratesInf':
				$auth = ($this->CI->session->has_userdata('logged') && lang('CONF_FOOTER_RATES') == 'ON');
				break;
			case 'getProductDetail':
				$auth = ($this->CI->session->has_userdata('logged') && $this->CI->session->has_userdata('enterpriseInf'));
				break;
			case 'authorizationKey':
				$auth = ($this->CI->session->has_userdata('logged') && $this->CI->session->has_userdata('productInf'));
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
			case 'signBulkList':
			case 'authorizeBulk':
			case 'authorizeBulkList':
			case 'calculateServiceOrder':
				$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('TEBAUT'));
				break;
			case 'bulkDetail':
				$auth = ($this->CI->session->has_userdata('productInf') && ($this->verifyAuthorization('TEBAUT') || $this->verifyAuthorization('TEBORS')));
				break;
			case 'deleteConfirmBulk':
			case 'disassConfirmBulk':
				$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('TEBAUT', 'TEBELI'));
				break;
			case 'serviceOrder':
			case 'cancelServiceOrder':
				$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('TEBAUT'));
				break;
			case 'exportFiles':
			case 'serviceOrders':
			case 'getServiceOrders':
				$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('TEBORS'));
				break;
			case 'clearServiceOrders':
				$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('TEBORS', 'TEBANU'));
				break;
			case 'transfMasterAccount':
			case 'actionMasterAccount':
				$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('TRAMAE'));
				break;
			case 'masterAccountTransfer':
			case 'rechargeAuthorization':
				$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('TRAMAE', 'TRAPGO'));
				break;
			case 'cardsInquiry':
			case 'inquiriesActions':
				$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('COPELO'));
				break;
			case 'transactionalLimits':
				$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('LIMTRX'));
				break;
			case 'updateTransactionalLimits':
				$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('LIMTRX', 'ACTLIM'));
				break;
			case 'commercialTwirls':
				$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('GIRCOM'));
				break;
			case 'updateCommercialTwirls':
				$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('GIRCOM', 'ACTGIR'));
				break;
			case 'getReportsList':
				$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('REPALL'));
				break;
			case 'getReport':
				$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('REPALL', 'REPALL'));
				break;
			case 'userActivity':
			case 'exportReportUserActivity':
				$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('REPUSU') && lang('CONF_USER_ACTIVITY') == 'ON');
				break;
			case 'usersActivity':
			case 'exportExcelUsersActivity':
				$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('REPUSU') && lang('CONF_USERS_ACTIVITY') == 'ON');
				break;
			case 'statusAccountExcelFile':
			case 'statusAccountPdfFile':
			case 'searchStatusAccount':
			case 'accountStatus':
				$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('REPEDO'));
				break;
			case 'extendedAccountStatus':
			case 'searchExtendedAccountStatus':
			case 'exportToExcelExtendedAccountStatus':
						$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('REPEDC'));
				break;
			case 'statusMasterAccount':
				$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('REPECT'));
				break;
			case 'replacement':
				$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('REPREP'));
				break;
			case 'closingBalance':
			case 'exportToExcel':
			case 'closingBudgets':
				$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('REPSAL'));
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
			case 'statusBulk':
				$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('REPLOT'));
				break;
			case 'exportToExcelMasterAccount':
			case 'exportToPDFMasterAccount':
			case 'exportToExcelMasterAccountConsolid':
			case 'exportToPDFMasterAccountConsolid':
			case 'masterAccount':
				$auth = ($this->CI->session->has_userdata('productInf') && ($this->verifyAuthorization('REPCON') || $this->verifyAuthorization('REPCMT')));
				break;
			case 'extendedMasterAccount':
			case 'exportToExcelExtendedMasterAccount':
			case 'extendedDownloadMasterAccountCon':
				$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('REPCMT'));
				break;
			case 'cardHolders':
			case 'exportReportCardHolders':
				$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('TEBTHA'));
				break;
			case 'usersManagement':
				$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('USEREM', 'CONUSU'));
				break;
			case 'enableUser':
				$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('USEREM', 'CREUSU'));
				break;
			case 'userPermissions':
			case 'updatePermissions':
				$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('USEREM', 'ASGPER'));;
				break;
			case 'pagoOs':
			case 'pagarOS':
				$auth = ($this->CI->session->has_userdata('productInf') && $this->verifyAuthorization('TEBORS', 'TEBPGO'));
				break;
			default:
				$freeAccess = [
					'login', 'suggestion', 'browsers', 'finishSession', 'singleSignOn', 'changeLanguage', 'terms', 'termsInf'
				];
				$auth = in_array($module, $freeAccess);
		}

		log_message('INFO', 'NOVO ['.$user.'] accessAuthorization '. $module.': '.json_encode($auth, JSON_UNESCAPED_UNICODE));

		if (!$auth) {
			$auth = !(preg_match('/Novo_/', $this->CI->router->fetch_class()) === 1);
		}

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
	public function validateRedirect($redirectUrl, $customerUri)
	{
		log_message('INFO', 'NOVO Verify_Access: validateRedirect method initialized');

		$dataLink = isset($redirectUrl['btn1']['link']) ? $redirectUrl['btn1']['link'] : FALSE;

		if(!is_array($redirectUrl) && strpos($redirectUrl, 'dashboard') !== FALSE) {
			$redirectUrl = str_replace($customerUri.'/', $this->CI->config->item('customer').'/', $redirectUrl);
		} elseif($dataLink && !is_array($dataLink) && strpos($dataLink, 'dashboard') !== FALSE) {
			$dataLink = str_replace($customerUri.'/', $this->CI->config->item('customer').'/', $dataLink);
			$redirectUrl['btn1']['link'] =  $dataLink;
		}

		return $redirectUrl;
	}
}
