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

if(!function_exists('createMenu')) {
	function createMenu($userAccess, $seralize = FALSE) {
		$menuData = $seralize ? unserialize($userAccess) : $userAccess;
		$levelOneOpts = [];
		if($menuData==NULL||!isset($menuData))
			return $levelOneOpts;
		foreach($menuData as $function) {
			$levelTwoOpts = [];
			$levelThreeOpts = [];
			$seeLotFact = FALSE;
			foreach($function->modulos as $module) {
				if($module->idModulo==='TEBAUT')
					$seeLotFact = TRUE;
				if($module->idModulo==='LOTFAC'&&!$seeLotFact)
					continue;
				$moduleOpt = [
					'route' => menuRoute($module->idModulo, $seeLotFact),
					'text' => lang($module->idModulo)
				];
				if($module->idModulo==='TICARG'||$module->idModulo==='TIINVN')
					$levelThreeOpts[] = $moduleOpt;
				else
					$levelTwoOpts[] = $moduleOpt;
			}
			if(!empty($levelThreeOpts))
				$levelTwoOpts[] = [
					'route' => '#',
					'text' => 'Cuentas innominadas',
					'suboptions' => $levelThreeOpts
				];
			$levelOneOpts[] = [
				'icon' => menuIcon($function->idPerfil),
				'text' => lang($function->idPerfil),
				'suboptions' => $levelTwoOpts
			];
		}
		return $levelOneOpts;
	}
}

if(!function_exists('menuIcon')) {
	function menuIcon($functionId) {
		switch ($functionId) {
			case 'CONSUL': return "&#xe072;";
			case 'GESLOT': return "&#xe03c;";
			case 'SERVIC': return "&#xe019;";
			case 'GESREP': return "&#xe021;";
			case 'COMBUS': return "&#xe08e;";
		}
		return '';
	}
}

if(!function_exists('menuRoute')) {
	function menuRoute($functionId, $seeLotFact) {
		$CI = &get_instance();
		$country = $CI->config->item('country');
		$countryUri = $CI->config->item('countryUri');
		switch ($functionId) {
			case 'TEBCAR': return base_url($country."/lotes/carga");
			case 'TEBAUT': return base_url($country."/lotes/autorizacion");
			case 'TEBGUR': return base_url($country."/lotes/reproceso");
			case 'TICARG': return base_url($country."/lotes/innominada");
			case 'TIINVN': return base_url($country."/lotes/innominada/afiliacion");
			case 'TEBTHA': return base_url($country."/reportes/tarjetahabientes");
			case 'TEBORS': return base_url($country."/consulta/ordenes-de-servicio");
			case 'TRAMAE': return base_url($country."/servicios/transferencia-maestra");
			case 'CONVIS': return base_url($country."/controles/visa");
			case 'PAGPRO': return base_url($country."/pagos");
			case 'TEBPOL': return base_url($country."/servicios/actualizar-datos");
			case 'CMBCON': return base_url($country."/trayectos/conductores");
			case 'CMBVHI': return base_url($country."/trayectos/gruposVehiculos");
			case 'CMBCTA': return base_url($country."/trayectos/cuentas");
			case 'CMBVJE': return base_url($country."/trayectos/viajes");
			case 'REPTAR': return base_url($country."/reportes/tarjetas-emitidas");
			case 'REPPRO': return base_url($country."/reportes/recargas-realizadas");
			case 'REPLOT': return base_url($country."/reportes/estatus-lotes");
			case 'REPUSU': return base_url($country."/reportes/actividad-por-usuario");
			case 'REPCON': return base_url($country."/reportes/cuenta-concentradora");
			case 'REPSAL': return base_url($country."/reportes/saldos-al-cierre");
			case 'REPREP': return base_url($country."/reportes/reposiciones");
			case 'REPCAT': return base_url($country."/reportes/gastos-por-categorias");
			case 'REPEDO': return base_url($country."/reportes/estados-de-cuenta");
			case 'REPPGE': return base_url($country."/reportes/guarderia");
			case 'REPRTH': return base_url($country."/reportes/comisiones");
			case 'LOTFAC': if ($seeLotFact) return base_url($country."/consulta/lotes-por-facturar");
		}
		return '#';
	}
}

if(!function_exists('languajeLoad')) {
	function languageLoad($client = 'default_lang', $langFiles = FALSE) {
		$CI = &get_instance();
		$class = $CI->router->fetch_class();
		$langFiles = $langFiles ?: $CI->router->fetch_method();
		$languages = [];
		$lanGeneral = ['bdb', 'bp', 'co', 've'];
		$loadlanguages = FALSE;
		$client = !$client ? 'default_lang' : $client;
		log_message('INFO', 'NOVO HELPER languajeLoad Initialized for controller '.$class. ' and method '.$langFiles);

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
					'login' => ['login']
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
				];
		}

		if(array_key_exists($langFiles, $languages)) {
			$languages = $languages[$langFiles];
			$loadlanguages = TRUE;
		}
		if(in_array($client, $lanGeneral)) {
			array_unshift($languages, 'general');
			$loadlanguages = TRUE;
		}

		if($loadlanguages) {
			$CI->lang->load($languages);
		}

	}
}
