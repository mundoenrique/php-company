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
	function clientUrlValidate($country) {
		$CI = &get_instance();
		$accessUrl = $CI->config->item('access_url');
		array_walk($accessUrl, 'arrayTrim');
		reset($accessUrl);

		if (!in_array($country, $accessUrl)) {
			$country = current($accessUrl);
			redirect(base_url($country.'/inicio'), 'location', 301);
		}

		$CI->config->load('config-'.$country);
	}
}

if (!function_exists('arrayTrim')) {
	function arrayTrim(&$value) {
		$value = trim($value);

		return $value;
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
		$pathLang = APPPATH.'language'.DIRECTORY_SEPARATOR.$CI->config->item('language').DIRECTORY_SEPARATOR;
		$class = lcfirst(str_replace('Novo_', '', $class));
		log_message('INFO', 'NOVO Language '.$call.', HELPER: Language Load Initialized for class: '.$class);

		if ($call == 'specific') {
			if (file_exists($pathLang.'general_lang.php')) {
				array_push($languagesFile, 'general');
				$loadLanguages = TRUE;
			}

			if (file_exists($pathLang.'validate_lang.php')) {
				array_push($languagesFile, 'validate');
				$loadLanguages = TRUE;
			}

			if (file_exists($pathLang.'config-core_lang.php')) {
				array_push($languagesFile, 'config-core');
				$loadLanguages = TRUE;
			}

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
	function uriRedirect($singleSignOn) {
		$CI = &get_instance();
		$linkredirect = $CI->session->has_userdata('productInf') ? 'detalle-producto' : 'empresas';
		$linkredirect = !$CI->session->has_userdata('logged') ? 'inicio' : $linkredirect;
		$linkredirect = $singleSignOn ? 'ingresar/fin' : $linkredirect;

		return $linkredirect;
	}
}

if (! function_exists('currencyFormat')) {
	function currencyFormat($amount){
		$CI =& get_instance();
		$client = $CI->session->userdata('countrySess');
		$decimalPoint = ['Ve', 'Co', 'Bdb'];
		$amount = (Float)$amount;

		if (in_array($client, $decimalPoint)) {
			$amount =  number_format($amount, 2, ',', '.');
		} else {
			$amount = number_format($amount, 2);
		}

		return $amount;
	}
}
