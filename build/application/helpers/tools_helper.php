<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * CodeIgniter XML Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/xml_helper.html
 */
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

		switch ($country) {
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
	}
}

if(!function_exists('getFaviconLoader')) {
	function getFaviconLoader() {
		$CI = &get_instance();
		$favicon = $CI->config->item('favicon');
		$loader = 'loading-';
		switch($CI->config->item('country')) {
			case 'Ec-bp':
				$ext = 'ico';
				$loader.= 'bp.gif';
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
		$sessionId = $CI->session->userdata('sessionId') ? $CI->session->userdata('sessionId') : '';
		$userName = $CI->session->userdata('userName') ? $CI->session->userdata('userName') : $dataAccessLog->userName;
		return $accessLog = [
			"sessionId"=> $sessionId,
			"userName" => $userName,
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
		return str_replace($countryUri, $country, $url);
	}
}

if(!function_exists('maskString')) {
	function maskString($string, $start = 1, $end = 1) {
		$length = strlen($string);
		return substr($string, 0, $start).str_repeat('*', 3).'@'.str_repeat('*', 3).substr($string, $length - $end, $end);
	}
}

if(!function_exists('createMenu')) {
	function createMenu($menuP) {
		$menuData = unserialize($menuP);
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

	if ( ! function_exists('np_hoplite_log')) {
		/**
		 * Helper que lanza la descarga de un documento que arma el objeto logAccesoObject y lo retorna
		 *
		 * @param  string $username
		 * @param  string $canal
		 * @param  string $modulo
		 * @param  string $function
		 * @param  string $operacion
		 * @param  int $rc
		 * @param  string $ip
		 * @param  date $timeLog
		 * @return array
		 */
		function np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operacion,$rc,$ip,$timeLog)
		{
			$logAcceso = array(
				"sessionId"=> $sessionId,
				"userName" => $username,
				"canal" => $canal,
				"modulo"=>$modulo,
				"function"=>$function,
				"operacion"=>$operacion,
				"RC"=>$rc,
				"IP"=>$ip,
				"dttimesstamp"=>$timeLog,
				"lenguaje"=>"ES"
			);
			return $logAcceso;
		}
	}

	if ( ! function_exists('np_hoplite_countryCheck')) {
		/**
		 * Helper que lanza la descarga de un documento para emplear el archivo de configuración adecuado, dependiendo del país.
		 * El archivo de configuración indica el lenguaje, los paths y URLs a emplear
		 *
		 * @param  string $countryISO
		 */
		function np_hoplite_countryCheck($countryISO)
		{
			$CI =& get_instance();

			switch ($countryISO) {
				case 'Ve':
				case 've':
					$CI->config->load('ve-config');
					break;
				case 'Co':
				case 'co':
					$CI->config->load('co-config');
					break;
				case 'Pe':
				case 'pe':
					$CI->config->load('pe-config');
					break;
				case 'Usd':
				case 'us':
					$CI->config->load('usd-config');
					break;
				case 'Ec-bp':
				case 'bp':
					$CI->config->load('ec-bp-config');
					break;
				default:
					redirect('/Pe/login');
			}
		}
	}

	if ( ! function_exists('np_hoplite_byteArrayToFile')) {
		/**
		 * Helper que lanza al navegador la descarga de un documento.
		 * Recibe como parametros los bytes del documento, el nombre y tipo de archivo.
		 *
		 * @param  byte $bytes
		 * @return document
		 */
		function np_hoplite_byteArrayToFile($file, $typeFile, $filename, $bytes = TRUE)
		{
			$CI =& get_instance();

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
				default:
					break;
			}

			if($bytes) {
				foreach ($file as $chr) {
					echo chr($chr);
				}
			} else {
				echo $file;
			}
		}
	}

	if ( ! function_exists('np_hoplite_jsontoiconsector')) {
		/**
		 * Helper para obtener el nombre del icono que representa el sector económico de la empresa.
		 * <actualmente no se hace uso de este helper>
		 *
		 * @param  string $nroIcon
		 * @return string
		 */
		function np_hoplite_jsontoiconsector($nroIcon)
		{
			$string = file_get_contents("/opt/httpd-2.4.4/vhost/online/application/uploads/sector.json");
			$json_a=json_decode($string);
			$icon = $json_a->pe->{$nroIcon};
			return $icon;
		}
	}

	if ( ! function_exists('np_hoplite_crearMenu')) {
		/**
		 * Helper que carga el menú con las funciones del usuario.
		 *
		 * @param  string $menuP     menú enviado por el WS
		 * @param  string $pais 	 país de conexión del usuario
		 * @param  string $urlBaseA  URL base del sistema ej: "https://online.novopayment.dev/empresas/"
		 * @return hmtl
		 */
		function np_hoplite_crearMenu($menuP,$pais,$urlBaseA)
		{
			$urlBase = $urlBaseA.$pais;

			$menuP = unserialize($menuP);
			//log_message("INFO", "<<<<<==FUNCIONES Y PERMISOS DEL USUARIO==>>>>>: ".json_encode($menuP));
			$seeLotFact = FALSE;

			$menuH="";
			$menuInno="";
			$menuBoolean=false;
			if($menuP!=NULL && isset($menuP)){
				foreach ($menuP as $opcionPrincipal) {
					switch ($opcionPrincipal->idPerfil) {
						case 'CONSUL':
							$icon="&#xe072;";
							break;
						case 'GESLOT':
							$icon="&#xe03c;";
							break;
						case 'SERVIC':
							$icon="&#xe019;";
							break;
						case 'GESREP':
							$icon="&#xe021;";
							break;
						case 'COMBUS':
							$icon="&#xe08e;";
							break;
					}
					$opMenuSubmenu="<ul>
										<div id='scrollup' style='display:none'>
											<span class='ui-icon ui-icon-triangle-1-n'></span>
										</div>";
					foreach ($opcionPrincipal->modulos as $submenu) {

						switch ($submenu->idModulo) {

							case 'TEBCAR':
								$ruta=$urlBase."/lotes/carga";
								break;
							case 'TEBAUT':
								$ruta=$urlBase."/lotes/autorizacion";
								$seeLotFact = TRUE;
								break;
							case 'TEBGUR':
								$ruta=$urlBase."/lotes/reproceso";
								break;
							case 'TICARG':
								$ruta=$urlBase."/lotes/innominada";
								break;
							case 'TIINVN':
								$ruta=$urlBase."/lotes/innominada/afiliacion";
								break;
							case 'TEBTHA':
								$ruta=$urlBase."/reportes/tarjetahabientes";
								break;
							case 'TEBORS':
								$ruta=$urlBase."/consulta/ordenes-de-servicio";
								break;
							case 'LOTFAC':
								if($seeLotFact) {
									$ruta=$urlBase."/consulta/lotes-por-facturar";
								}
								break;
							case 'TRAMAE':
								$ruta=$urlBase."/servicios/transferencia-maestra";
								break;
							case 'CONVIS':
								$ruta=$urlBase."/controles/visa";
								break;
							case 'PAGPRO':
								$ruta=$urlBase."/pagos";
								break;
							case 'TEBPOL':
								$ruta=$urlBase."/servicios/actualizar-datos";
								break;
							case 'CMBCON':
								$ruta=$urlBase."/trayectos/conductores";
								break;
							case 'CMBVHI':
								$ruta=$urlBase."/trayectos/gruposVehiculos";
								break;
							case 'CMBCTA':
								$ruta=$urlBase."/trayectos/cuentas";
								break;
							case 'CMBVJE':
								$ruta=$urlBase."/trayectos/viajes";
								break;
							case 'REPTAR':
								$ruta=$urlBase."/reportes/tarjetas-emitidas";
								break;
							case 'REPPRO':
								$ruta=$urlBase."/reportes/recargas-realizadas";
								break;
							case 'REPLOT':
								$ruta=$urlBase."/reportes/estatus-lotes";
								break;
							case 'REPUSU':
								$ruta=$urlBase."/reportes/actividad-por-usuario";
								break;
							case 'REPCON':
								$ruta=$urlBase."/reportes/cuenta-concentradora";
								break;
							case 'REPSAL':
								$ruta=$urlBase."/reportes/saldos-al-cierre";
								break;
							case 'REPREP':
								$ruta=$urlBase."/reportes/reposiciones";
								break;
							case 'REPCAT':
								$ruta=$urlBase."/reportes/gastos-por-categorias";
								break;
							case 'REPEDO':
								$ruta=$urlBase."/reportes/estados-de-cuenta";
								break;
							case 'REPPGE':
								$ruta=$urlBase."/reportes/guarderia";
								break;
							case 'REPRTH':
								$ruta= $urlBase . "/reportes/comisiones";
								break;

						}
						if($submenu->idModulo == "TICARG"||$submenu->idModulo == "TIINVN"){
							if($menuBoolean == false) {
								$opMenuSubmenu.= "<li>
													<a href='#'>Cuentas innominadas</a>
													SUBMENU-INNO
												</li>";
								$menuBoolean = true;
							}
							$menuInno.= "<li>
											<a href='".$ruta."'>". lang($submenu->idModulo)."</a>
										</li>";
						} else {
							if($submenu->idModulo == 'LOTFAC' && !$seeLotFact) continue;
							$opMenuSubmenu.= "<li>
												<a href='".$ruta."'>". lang($submenu->idModulo)."</a>
											</li>";
						}
					}
					$opMenuSubmenu.= "<div id='scrolldown' style='display:none'>
										<span class='ui-icon ui-icon-triangle-1-s'></span>
									</div>
								</ul>";
					$opMenu= "<li>
								<a rel='section'>
									<span aria-hidden='true' class='icon' data-icon='".$icon."'></span>
									".lang($opcionPrincipal->idPerfil)."
								</a>
								".$opMenuSubmenu."
							</li>";
					if($menuBoolean == true){
						$opMenu = str_replace("SUBMENU-INNO", "<ul>" . $menuInno . "</ul>", $opMenu);
					}
					$menuH.=$opMenu;
				}
			}
			$menu='<nav id="nav2">
					<ul style="margin:0">
						<li>
							<a href="'.$urlBase.'/dashboard" rel="start" >
								<span aria-hidden="true" class="icon" data-icon="&#xe097;"></span>
								'.lang('MENU_INICIO').'
							</a>
						</li>
						'.$menuH.'
						<li>
							<a href="'.$urlBase.'/logout" rel="subsection">
								<span aria-hidden="true" class="icon" data-icon="&#xe03e;"></span>
								'.lang("SUBMENU_LOGOUT").'
							</a>
						</li>
					</ul>
				</nav>';
			echo $menu;
		}
	}

	if ( ! function_exists('np_hoplite_existeLink')) {
		/**
		 * Helper empleado para saber si determinado módulo se encuentra habilitado para el usuario desde el menú.
		 * retorna un entero positivo en caso de que exista el módulo y false en caso contrario.
		 *
		 * @param  string $menuP  menú enviado por el WS
		 * @param  módulo $link   módulo a buscar
		 * @return int, boolean   int si lo encuentra, false si no
		 */
		function np_hoplite_existeLink($menuP, $link)
		{
			$arrayMenu = unserialize($menuP);
			$modulos = "";

			if($arrayMenu!=""){

				foreach ($arrayMenu as $value) {
					foreach ($value->modulos as $modulo) {
						$modulos.= strtolower($modulo->idModulo).",";
					}
				}

				return strrpos($modulos, strtolower($link));

			}else{
				return false;
			}

		}
	}

	if ( ! function_exists('np_hoplite_modFunciones')) {
		/**
		 * Helper que retorna un arreglo con las todas las funciones a las que está autorizado un usuario.
		 *
		 * @param  string $menuP    menú enviado por el ws
		 * @return array            arreglo con las funciones
		 */
		function np_hoplite_modFunciones($menuP)
		{

			$arrayMenu = unserialize($menuP);
			$funciones = "";

			if($arrayMenu!=""){

				foreach ($arrayMenu as $value) {
					foreach ($value->modulos as $modulo) {
						foreach ($modulo->funciones as $func) {
							$funciones.= strtolower($func->accodfuncion).",";
						}
					}
				}

				return explode(',', strtolower($funciones));

			}else{
				return false;
			}

		}
	}

	if (! function_exists('amount_format')) {

		function amount_format($amount){
			$CI =& get_instance();
			$country = $CI->session->userdata('pais');
			if ($country == 'Ve' || $country == 'Co' ) {

				return number_format($amount,2,",",".");
				# code...
				// return $country;
			}
			else {

				return number_format($amount,2);
			}

			// return $CI->session->userdata('pais');
		}
	}

	if(!function_exists('mask_account')) {
		function mask_account($account, $start = 1, $end = 1){
			$CI = &get_instance();
			$len = strlen($account);
			return substr($account, 0, $start).str_repeat('*', $len - ($start + $end)).substr($account, $len - $end, $end);
		}
	}
