<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/**
	 * CodeIgniter XML Helpers
	 *
	 * @package		CodeIgniter
	 * @subpackage	Helpers
	 * @category	Helpers
	 * @author		ExpressionEngine Dev Team
	 * @link		http://codeigniter.com/user_guide/helpers/xml_helper.html
	 */

	// ------------------------------------------------------------------------
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
					$CI->config->load('ve-config');
					break;
				case 'Co':
					$CI->config->load('co-config');
					break;
				case 'Pe':
					$CI->config->load('pe-config');
					break;
				case 'Usd':
					$CI->config->load('usd-config');
					break;
				default:
					redirect('/Pe/login');
					break;
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
			log_message("DEBUG" ,json_encode($menuP));

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
													<a href='#'>Innominadas</a>
													SUBMENU-INNO
												</li>";
								$menuBoolean = true;
							}
							$menuInno.= "<li>
											<a href='".$ruta."'>". lang($submenu->idModulo)."</a>
										</li>";
						} else {
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
