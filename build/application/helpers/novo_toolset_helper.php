<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * NOVOPAYMENT toolset Helpers
 *
 * @subpackage	Helpers
 * @category		Helpers
 * @author			J. Enrique Peñaloza P
 * @date				Novembre 23th, 2019
 */
if (!function_exists('assetPath')) {
	function assetPath($route = '') {
		return get_instance()->config->item('asset_path').$route;
	}
}

if (!function_exists('assetUrl')) {
	function assetUrl($route = '') {
		return get_instance()->config->item('asset_url').$route;
	}
}

if (!function_exists('clientUrlValidate')) {
	function clientUrlValidate($customer) {
		$CI = &get_instance();
		$accessUrl = explode(',', ACCESS_URL);
		array_walk($accessUrl, 'arrayTrim');
		reset($accessUrl);

		if (!in_array($customer, $accessUrl)) {
			$customer = current($accessUrl);
			redirect(base_url($customer.'/inicio'), 'Location', 302);
			exit;
		}

		$CI->config->load('config-'.$customer);
	}
}

if (!function_exists('arrayTrim')) {
	function arrayTrim(&$value) {
		$value = trim($value);

		return $value;
	}
}

if(!function_exists('dbSearch')) {
	function dbSearch($uri) {
		$CI = &get_instance();

		log_message('INFO', 'DATABASE URI '.$uri);

		if (!isset($CI->config->item('client_db')[$uri])) {
			$dbName = 'alpha';
		} else {
			$dbName = $CI->config->item('client_db')[$uri];
		}

		log_message('INFO', 'DATABASE SELECT '.$dbName);

		return 'ceo_' . $dbName;
	}
}

if (!function_exists('clearSessionVars')) {
	function clearSessionsVars() {
		$CI = &get_instance();

		foreach ($CI->session->all_userdata() AS $pos => $sessionVar) {
			if ($pos == '__ci_last_regenerate') {
				continue;
			}

			$CI->session->unset_userdata($pos);
		}
	}
}

if (!function_exists('accessLog')) {
	function accessLog($dataAccessLog) {
		$CI = &get_instance();

		return $accessLog = [
			"sessionId"=> $CI->session->userdata('sessionId') ?: '',
			"userName" => $CI->session->userdata('userName') ?: $dataAccessLog->userName,
			"canal" => $CI->config->item('channel'),
			"modulo"=> $dataAccessLog->modulo,
			"function"=> $dataAccessLog->function,
			"operacion"=> $dataAccessLog->operation,
			"RC"=> 0,
			"IP"=> $CI->input->ip_address(),
			"dttimesstamp"=> date('m/d/Y H:i'),
			"lenguaje"=> strtoupper(LANGUAGE)
		];
	}
}

if (!function_exists('maskString')) {
	function maskString($string, $start = 1, $end = 1, $type = NULL) {
		$type = $type ? $type : '';
		$length = strlen($string);
		return substr($string, 0, $start).str_repeat('*', 3).$type.str_repeat('*', 3).substr($string, $length - $end, $end);
	}
}

if (!function_exists('languageLoad')) {
	function languageLoad($call, $class) {
		$CI = &get_instance();
		$languagesFile = [];
		$loadLanguages = FALSE;
		$configLanguage = $CI->config->item('language');
		$pathLang = APPPATH.'language'.DIRECTORY_SEPARATOR.$configLanguage.DIRECTORY_SEPARATOR;
		$customerUri = $call == 'specific' ? $CI->config->item('customer_lang') : '';
		$class = lcfirst(str_replace('Novo_', '', $class));
		$CI->config->set_item('language', 'global');

		log_message('INFO', 'NOVO Language '.$call.', HELPER: Language Load Initialized for class: '.$class);

		switch ($call) {
			case 'generic':
				$CI->lang->load(['config-core', 'images']);
			break;
			case 'specific':
				$globalLan = APPPATH.'language'.DIRECTORY_SEPARATOR.'global'.DIRECTORY_SEPARATOR;

				if(file_exists($globalLan.'config-core-'.$customerUri.'_lang.php')) {
					$CI->lang->load('config-core-'.$customerUri,);
				}

				if(file_exists($globalLan.'images_'.$customerUri.'_lang.php')) {
					$CI->lang->load('images_'.$customerUri);
				}
			break;
		}

		$CI->config->set_item('language', $configLanguage);

		if ($call == 'specific') {
			if (file_exists($pathLang.'general_lang.php')) {
				array_push($languagesFile, 'general');
				$loadLanguages = TRUE;
			}

			if (file_exists($pathLang.'validate_lang.php')) {
				array_push($languagesFile, 'validate');
				$loadLanguages = TRUE;
			}

			//eliminar despues de la certificación
			if (file_exists($pathLang.'config_lang.php')) {
				array_push($languagesFile, 'config');
				$loadLanguages = TRUE;
			}
		}

		if (file_exists($pathLang.$class.'_lang.php')) {
			array_push($languagesFile, $class);
			$loadLanguages = TRUE;
		}

		if ($loadLanguages) {
			$CI->lang->load($languagesFile);
		}
	}
}

if (!function_exists('setCurrentPage')) {
	function setCurrentPage($currentClass, $menu) {
		$cssClass = '';

		switch ($currentClass) {
			case 'Novo_Business':
				if ($menu == lang('GEN_MENU_ENTERPRISE')) {
					$cssClass = 'page-current';
				}
				break;
			case 'Novo_Bulk':
				if ($menu == lang('GEN_MENU_LOTS')) {
					$cssClass = 'page-current';
				}
				break;
			case 'Novo_Inquiries':
				if ($menu == lang('GEN_MENU_CONSULTATIONS')) {
					$cssClass = 'page-current';
				}
				break;
			case 'Novo_Services':
				if ($menu == lang('GEN_MENU_SERVICES')) {
					$cssClass = 'page-current';
				}
				break;
			case 'Novo_Reports':
				if ($menu == lang('GEN_MENU_REPORTS')) {
					$cssClass = 'page-current';
				}
				break;
			case 'Novo_User':
				if ($menu == lang('GEN_MENU_USERS')) {
					$cssClass = 'page-current';
				}
				break;
		}

		return $cssClass;
	}
}

if (!function_exists('exportFile')) {
	function exportFile($file, $typeFile, $filename, $bytes = TRUE) {
		switch ($typeFile) {
			case 'pdf':
				header('Content-type: application/pdf');
				header('Content-Disposition: attachment; filename='.$filename.'.pdf');
				header('Pragma: no-cache');
				header('Expires: 0');
			break;
			case 'xls':
				header('Content-type: application/vnd.ms-excel');
				header('Content-Disposition: attachment; filename='.$filename.'.xls');
				header('Pragma: no-cache');
				header('Expires: 0');
			break;
			case 'xlsx':
				header('Content-type: application/vnd.ms-excel');
				header('Content-Disposition: attachment; filename='.$filename.'.xlsx');
				header('Pragma: no-cache');
				header('Expires: 0');
			break;
		}

		if ($bytes) {
			foreach ($file as $chr) {
				echo chr($chr);
			}
		} else {
			echo $file;
		}
	}
}

if (!function_exists('convertDate')) {
	function convertDate($date) {
		$date = explode('/', $date);
		$date = $date[2].'-'.$date[1].'-'.$date[0];

		return $date;
	}
}

if (!function_exists('convertDateMDY')) {
	function convertDateMDY($date) {
		$date = explode('/', $date);
		$date = $date[1].'/'.$date[0].'/'.$date[2];

		return $date;
	}
}

if (!function_exists('uriRedirect')) {
	function uriRedirect() {
		$CI = &get_instance();
		$linkredirect = $CI->session->has_userdata('productInf') ? lang('CONF_LINK_PRODUCT_DETAIL') : lang('CONF_LINK_ENTERPRISES');
		$linkredirect = !$CI->session->has_userdata('logged') ? lang('CONF_LINK_SIGNIN') : $linkredirect;
		$linkredirect = SINGLE_SIGN_ON ? 'ingresar/'.lang('CONF_LINK_SIGNOUT_END') : $linkredirect;

		return $linkredirect;
	}
}

if (! function_exists('currencyFormat')) {
	function currencyFormat($amount){
		$CI =& get_instance();
		$client = $CI->session->userdata('customerSess');
		$decimalPoint = ['Ve', 'Co', 'Bdb'];
		$amount = (float)$amount;

		if (in_array($client, $decimalPoint)) {
			$amount = number_format($amount, 2, ',', '.');
		} else {
			$amount = number_format($amount, 2);
		}

		return $amount;
	}
}

if (! function_exists('languageCookie')) {
	function languageCookie($language) {
		$baseLanguage = [
			'name' => 'baseLanguage',
			'value' => $language,
			'expire' => 0,
			'httponly' => TRUE
		];

		set_cookie($baseLanguage);

	}
}
//eliminar despues de la certificación
if (! function_exists('checkTemporalTenant')) {
	function checkTemporalTenant($customer) {
		$pattern = ['/bog/', '/col/', '/per/', '/usd/', '/ven/'];
		$replace = ['bdb', 'co', 'pe', 'us', 've'];
		$customer = preg_replace($pattern, $replace, $customer);

		return $customer;
	}
}

if (!function_exists('normalizeName')) {
	function normalizeName($name) {
		$pattern = [
			'/\s/',
			'/á/', '/à/', '/ä/', '/â/', '/ª/', '/Á/', '/À/', '/Â/', '/Ä/',
			'/é/', '/è/', '/ë/', '/ê/', '/É/', '/È/', '/Ê/', '/Ë/',
			'/í/', '/ì/', '/ï/', '/î/', '/Í/', '/Ì/', '/Ï/', '/Î/',
			'/ó/', '/ò/', '/ö/', '/ô/', '/Ó/', '/Ò/', '/Ö/', '/Ô/',
			'/ú/', '/ù/', '/ü/', '/û/', '/Ú/', '/Ù/', '/Û/', '/Ü/',
			'/ñ/', '/Ñ/', '/ç/', '/Ç/'
		];
		$replace = [
			'_',
			'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
			'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
			'i', 'i', 'i', 'i', 'i', 'i', 'i', 'i',
			'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
			'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
			'n', 'N', 'c', 'C'
		];

		return preg_replace($pattern, $replace, mb_strtolower(trim($name)));
	}
}
