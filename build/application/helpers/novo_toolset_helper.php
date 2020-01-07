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

// ------------------------------------------------------------------------
if(!function_exists('assetPath')) {
	function assetPath($route = '') {
		return get_instance()->config->item('asset_path').$route;
	}
}

if(!function_exists('assetUrl')) {
	function assetUrl($route = '') {
		return get_instance()->config->item('asset_url').$route;
	}
}

if(!function_exists('countryCheck')) {
	function countryCheck($country) {
		$CI = &get_instance();

		$CI->config->load('config-'.$country);

		/*
		switch ($country) {
			case 'bdb':
				$CI->config->load('config-bdb');
				break;
			case 'bp':
				$CI->config->load('config-bp');
				break;
			case 'co':
				$CI->config->load('config-co');
				break;
			case 'pe':
				$CI->config->load('config-pe');
				break;
			case 'us':
				$CI->config->load('config-us');
				break;
			case 've':
				$CI->config->load('config-ve');
				break;
			default:
				redirect('/pe/inicio');
		}
		*/
	}
}

if(!function_exists('getFaviconLoader')) {
	function getFaviconLoader($countryUri) {
		$CI = &get_instance();
		$favicon = $CI->config->item('favicon');
		$loader = 'loading-';
		switch($countryUri) {
			case 'bp':
				$ext = 'ico';
				$loader.= 'bp.gif';
				break;
			case 'bdb':
				$ext = 'ico';
				$loader.= 'bdb.gif';
				break;
			default:
				$ext = 'png';
				$loader.= 'novo.gif';
		}

		$faviconLoader = new stdClass();
		$faviconLoader->favicon = $favicon;
		$faviconLoader->ext = $ext;
		$faviconLoader->loader = $loader;

		return $faviconLoader;
	}
}

if(!function_exists('accessLog')) {
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

if(!function_exists('urlReplace')) {
	function urlReplace($countryUri, $countrySess, $url) {
		$CI = &get_instance();
		switch($countrySess) {
			case 'Ec-bp':
				$country = 'bp';
				break;
			case 'Co':
				$country = 'co';
				break;
			case 'Pe':
				$country = 'pe';
				break;
			case 'Usd':
				$country = 'us';
				break;
			case 'Ve':
				$country = 've';
				break;
		}
		return str_replace($countryUri.'/', $country.'/', $url);
	}
}

if(!function_exists('maskString')) {
	function maskString($string, $start = 1, $end = 1, $type = NULL) {
		$type = $type ? $type : '';
		$length = strlen($string);
		return substr($string, 0, $start).str_repeat('*', 3).$type.str_repeat('*', 3).substr($string, $length - $end, $end);
	}
}

if(!function_exists('languajeLoad')) {
	function languageLoad($call, $client = 'default_lang', $langFiles = FALSE) {
		$CI = &get_instance();
		$class = $CI->router->fetch_class();
		$langFiles = $langFiles ?: $CI->router->fetch_method();
		$languagesFile = [];
		$lanGeneral = ['bdb', 'bp', 'co', 've'];
		$lanValidate = ['bdb'];
		$loadLanguages = FALSE;
		$client = !$client ? 'default_lang' : $client;
		log_message('INFO', 'NOVO Language '.$call.', HELPER: languajeLoad Initialized for controller: '.$class. ' and method: '.$langFiles);

		switch($client) {
			case 'bp':
				$languages = [
					'login' => ['login'],
					'validatecaptcha' => ['login'],
					'recoverPass'	=> ['password-recover'],
					'terms'	=> ['terms'],
				];
				break;
			case 'bdb':
				$languages = [
					'login' => ['login'],
					'validatecaptcha' => ['login'],
					'getPendingBulk'	=> ['bulk'],
					'loadBulk'	=> ['bulk'],
					'deleteNoConfirmBulk'	=> ['bulk'],
					'confirmBulk'	=> ['bulk'],
					'getDetailBulk'	=> ['bulk'],
					'authorizeBulkList'	=> ['bulk'],
					'authorizeBulk'	=> ['bulk'],
					'deleteConfirmBulk'	=> ['bulk'],
				];
				break;
			case 'co':
				$languages = [
					'login' => ['login'],
					'validatecaptcha' => ['login'],
					'recoverPass'	=> ['password-recover'],
					'terms'	=> ['terms'],
				];
				break;
			case 'pe':
				$languages = [
					'login' => ['login'],
					'validatecaptcha' => ['login'],
				];
				break;
			case 'us':
				$languages = [
					'login' => ['login'],
					'validatecaptcha' => ['login'],
				];
				break;
			case 've':
				$languages = [
					'login' => ['login'],
					'validatecaptcha' => ['login'],
					'recoverPass'	=> ['password-recover'],
					'terms'	=> ['terms'],
				];
				break;
			default:
				$languages = [
					'login' => ['login'],
					'validatecaptcha' => ['login'],
					'recoverPass'	=> ['password-recover'],
					'changePassword'	=> ['password-change'],
					'benefits'	=> ['benefits'],
					'terms'	=> ['terms'],
					'rates'	=> ['rates'],
					'getEnterprises'	=> ['enterprise'],
					'getProducts'	=> ['products'],
					'getProductDetail'	=> ['products'],
					'getPendingBulk'	=> ['bulk'],
					'loadBulk'	=> ['bulk'],
					'deleteNoConfirmBulk'	=> ['bulk'],
					'confirmBulk'	=> ['bulk'],
					'getDetailBulk'	=> ['bulk'],
					'authorizeBulkList'	=> ['bulk'],
					'authorizeBulk'	=> ['bulk'],
					'deleteConfirmBulk'	=> ['bulk'],
				];
		}

		if(array_key_exists($langFiles, $languages)) {
			$languagesFile = $languages[$langFiles];
			$loadLanguages = TRUE;
		}

		if(in_array($client, $lanGeneral)) {
			array_unshift($languagesFile, 'general');
			$loadLanguages = TRUE;
		}

		if(in_array($client, $lanValidate)) {
			array_unshift($languagesFile, 'validate', 'response');
			$loadLanguages = TRUE;
		}

		if($loadLanguages) {
			$CI->lang->load($languagesFile);
		}

	}
}

if(!function_exists('setCurrentPage')) {
	function setCurrentPage($currentClass, $menu) {
		$cssClass = '';
		switch ($currentClass) {
			case 'business':
				if($menu == lang('GEN_MENU_ENTERPRISE')) {
					$cssClass = 'page-current';
				}
				break;
			case 'bulk':
				if($menu == lang('GEN_MENU_LOTS')) {
					$cssClass = 'page-current';
				}
				break;
		}
		return $cssClass;
	}
}
