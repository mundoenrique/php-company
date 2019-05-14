<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Clase para todas las operaciones a realizar dentro del módulo de servicios
 * incluyendo transferencia maestra y actualización de datos (sólo Ve)
 * .
 * @package    controllers
 * @author     Wilmer Rojas <rojaswilmer@gmail.com>
 * @author     Carla García <neiryerit@gmail.com>
 */
class Servicios extends CI_Controller {
	/**
	 * Pantalla para transferencia maestra.
	 *
	 * @param  string $urlCountry
	 */
	public function transferenciaMaestra($urlCountry)
	{
		np_hoplite_countryCheck($urlCountry);

		$this->lang->load('servicios');
		$this->lang->load('dashboard');
		$this->lang->load('users');
		$this->lang->load('erroreseol');

		$logged_in = $this->session->userdata('logged_in');
		$menuP =$this->session->userdata('menuArrayPorProducto');
		$funciones = np_hoplite_modFunciones($menuP);
		$moduloAct = np_hoplite_existeLink($menuP, "TRAMAE");
		$paisS = $this->session->userdata('pais');

		if($paisS==$urlCountry && $logged_in && $moduloAct!==false) {
			$FooterCustomInsertJS = ["jquery-1.10.2.min.js","jquery-ui-1.10.3.custom.min.js","jquery.balloon.min.js",
			"jquery-md5.js","jquery.paginate.js","header.js","dashboard/widget-empresa.js",
			"servicios/transferencia-maestra.js","routes.js"];
			$FooterCustomJS = "";
			$titlePage = "Transferencia maestra";
			$programa = $this->session->userdata('nombreProductoS').' / '. $this->session->userdata('marcaProductoS');
			$menuHeader = $this->parser->parse('widgets/widget-menuHeader', [], TRUE);
			$menuFooter = $this->parser->parse('widgets/widget-menuFooter', [], TRUE);
			$header = $this->parser->parse('layouts/layout-header', array('bodyclass'=>'','menuHeaderActive'=>TRUE,
			'menuHeaderMainActive'=>TRUE,'menuHeader'=>$menuHeader, 'titlePage'=>$titlePage), TRUE);
			$footer = $this->parser->parse('layouts/layout-footer', array('menuFooterActive'=>TRUE,
			'menuFooter'=>$menuFooter, 'FooterCustomInsertJSActive'=>TRUE, 'FooterCustomInsertJS'=>$FooterCustomInsertJS,
			'FooterCustomJSActive'=>TRUE, 'FooterCustomJS'=> $FooterCustomJS), TRUE);
			$ctas = '';
			if($urlCountry == 'Ec-bp') {
				$this->load->model('services_model', 'servicios');
				$ctas = $this->servicios->getBanckAccountlist();
			}
			$content = $this->parser->parse('servicios/content-transferencia-maestra', array(
				'programa'=>$programa,
				'funciones' => $funciones,
				'dataCtas'=> $ctas
			),TRUE);
			$sidebarLotes= $this->parser->parse('dashboard/widget-empresa', array('sidebarActive'=>TRUE), TRUE);
			$datos = array(
				'header'       =>$header,
				'content'      =>$content,
				'footer'       =>$footer,
				'sidebar'      =>$sidebarLotes
			);

			$this->parser->parse('layouts/layout-b', $datos);

		} elseif($paisS!=$urlCountry && $paisS!="") {
			$this->session->sess_destroy();
			redirect($urlCountry.'/login');
		} else {
			redirect($urlCountry.'/login');
		}
	}

	/**
	 * Método para solicitar las tarjetas en Transferencia maestra
	 *
	 * @param  string $urlCountry
	 * @return json
	 */
	public function buscarTM($urlCountry)
{
		np_hoplite_countryCheck($urlCountry);

		$logged_in = $this->session->userdata('logged_in');
		$paisS = $this->session->userdata('pais');
		$menuP =$this->session->userdata('menuArrayPorProducto');
		$moduloAct = np_hoplite_existeLink($menuP, "TRAMAE");

		if($paisS==$urlCountry && $logged_in && $moduloAct!==false) {
				$result = $this->callWSbuscarTransferenciaM($urlCountry);
				$menuP =$this->session->userdata('menuArrayPorProducto');
				$funciones = np_hoplite_modFunciones($menuP);
				$r["result"] = $result;
				$r["funciones"] = $funciones;

				$this->output->set_content_type('application/json')->set_output(json_encode($r));

		} elseif($paisS != $urlCountry && $paisS != '') {
			$this->session->sess_destroy();
			redirect($urlCountry.'/login');

		} elseif ($this->input->is_ajax_request()) {
			$this->output->set_content_type('application/json')->set_output(json_encode( array('ERROR' => '-29' )));

		}else{
			redirect($urlCountry.'/login');

		}
	}

	/**
	 * Método que llama al WS para realizar la busqueda de tarjetas en transferencia maestra
	 *
	 * @param  string $urlCountry
	 * @return json
	 */
	private function callWSbuscarTransferenciaM($urlCountry)
	{

		$this->lang->load('erroreseol');

		$username = $this->session->userdata('userName');
		$token = $this->session->userdata('token');
		$idEmpresa = $this->session->userdata('acrifS');
		$idProductoS = $this->session->userdata('idProductoS');
		$tarjeta = $this->input->post('data-tjta');
		$dni = $this->input->post('data-dni');
		$pg = $this->input->post('data-pg');
		$paginas = $this->input->post('data-paginas');
		$paginar = $this->input->post('data-paginar');
		$acodcia = $this->session->userdata('accodciaS');
		$acgrupo = $this->session->userdata('accodgrupoeS');
		$sessionId = $this->session->userdata('sessionId');
		$canal = "ceo";
		$modulo="TM";
		$function="buscarTransferenciaM";
		$operation="buscarTransferenciaM";
		$ip = $this->input->ip_address();
		$timeLog= date("m/d/Y H:i");
		$className="com.novo.objects.MO.TransferenciaMO";

		$logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $operation, 0, $ip, $timeLog);

		$listaTarjetas = [
			"paginaActual" => $pg,
			"tamanoPagina" => $paginas,
			"paginar" => $paginar
		];
		$listaTarjetas = [$listaTarjetas];
		$Ausuario = ["userName" =>$username];
		$listadoT = [
			"noTarjeta" =>$tarjeta,
			"id_ext_per" =>$dni
		];
		$listadoT = ['lista'=> [$listadoT]];
		$data = [
			"idOperation" => $operation,
			"className" => $className,
			"rifEmpresa" => $idEmpresa,
			"listaTarjetas" => $listaTarjetas,
			"usuario" => $Ausuario,
			"idProducto" => $idProductoS,
			"listadoTarjetas" => $listadoT,
			"logAccesoObject"=>$logAcceso,
			"token"=>$token,
			"pais" =>$urlCountry
		];

		$data = json_encode($data, JSON_UNESCAPED_UNICODE);

		$dataEncry = np_Hoplite_Encryption($data, 'callWSbuscarTransferenciaM');
		$data = ['bean' => $dataEncry, 'pais' =>$urlCountry];
		$data = json_encode($data);
		$response = np_Hoplite_GetWS('eolwebInterfaceWS',$data);
		$jsonResponse = np_Hoplite_Decrypt($response, 'callWSbuscarTransferenciaM');
		$response = json_decode($jsonResponse);

		if($response) {
			if($response->rc == 0) {
				unset(
					$response->rc, $response->msg, $response->className, $response->token, $response->idOperation,
					$response->logAccesoObject, $response->usuario
				);
				log_message('DEBUG', 'RESULTS: ' . json_encode($response));
					return $response;
			} else {
				if($response->rc == -61 || $response->rc == -29){
					$this->session->sess_destroy();
					$codigoError = ['ERROR'=> '-29'];
				} else{
					$codigoError = lang('ERROR_('.$response->rc.')');
					if(strpos($codigoError, 'Error') !== false) {
						$codigoError = ['ERROR'=> $response->msg];
					} else {
						if(gettype($codigoError) == 'boolean') {
							$codigoError = ['ERROR'=> $response->msg];
						} else {
							$codigoError = ['ERROR'=> lang('ERROR_('.$response->rc.')')];
						}
					}
				}
				return $codigoError;
			}
		} else {
			return $codigoError = ['ERROR'=> lang('ERROR_GENERICO_USER')];
		}
	}

	/**
	 * Método destinado a realizar la operación de consulta de
	 * saldo de una o varias tarjetas en transferencia maestra
	 *
	 * @param  string $urlCountry
	 * @return json
	 */
	public function consultar($urlCountry)
{
		np_hoplite_countryCheck($urlCountry);

		$this->lang->load('erroreseol');

		$logged_in = $this->session->userdata('logged_in');
		$paisS = $this->session->userdata('pais');

		if($paisS==$urlCountry && $logged_in) {
			$menuP =$this->session->userdata('menuArrayPorProducto');
			$funcAct = in_array("trasal", np_hoplite_modFunciones($menuP));

			if($funcAct) {
				$result = $this->callWSconsultarTM($urlCountry);
			} else {
				$result = array("ERROR"=>lang('SIN_FUNCION'));
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));

		} elseif($paisS!=$urlCountry && $paisS!='') {
			$this->session->sess_destroy();
			redirect($urlCountry.'/login');

		} elseif($this->input->is_ajax_request()) {
			$this->output->set_content_type('application/json')->set_output(json_encode( array('ERROR' => '-29' )));
		} else {
			redirect($urlCountry.'/login');
		}
	}

	/**
	 * Método que realiza petición al WS para consultar el saldo de las tarjetas en T.M.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	private function callWSconsultarTM($urlCountry)
	{
		$this->lang->load('erroreseol');

		$canal = "ceo";
		$modulo="TM";
		$function="consultaTransferenciaM";
		$operation = "saldoTM";
		$ip= $this->input->ip_address();
		$timeLog= date("m/d/Y H:i");
		$className="com.novo.objects.MO.TransferenciaMO";
		$username = $this->session->userdata('userName');
		$token = $this->session->userdata('token');
		$idEmpresa = $this->session->userdata('acrifS');
		$idProductoS = $this->session->userdata('idProductoS');
		$pg = $this->input->post('data-pg');
		$paginas = $this->input->post('data-paginas');
		$paginar = $this->input->post('data-paginar');

		$listaTarjetas = [
			"paginaActual" => $pg,
			"tamanoPagina" => $paginas,
			"paginar" => $paginar
		];

		$listaTarjetas = array($listaTarjetas);

		$tarjetas = $this->input->post('data-tarjeta');
		$dnis = $this->input->post('data-id_ext_per');
		$pass = $this->input->post('data-pass');
		$lista;

		foreach ($tarjetas as $key => $value) {
			$tjs = ["noTarjeta" => $value, "id_ext_per" => $dnis[$key]];
			$lista[$key] = $tjs;
		}

		$listadoT = ['lista'=> $lista];
		$Ausuario = ["userName" =>$username, "password" =>$pass];

		$sessionId = $this->session->userdata('sessionId');
		$logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $operation, 0, $ip, $timeLog);

		$data = [
			"pais" => $urlCountry,
			"idOperation" => $operation,
			"className" => $className,
			"rifEmpresa" => $idEmpresa,
			"listaTarjetas" => $listaTarjetas,
			"listadoTarjetas" => $listadoT,
			"usuario" => $Ausuario,
			"idProducto" => $idProductoS,
			"logAccesoObject" => $logAcceso,
			"token" => $token
		];

		$data = json_encode($data, JSON_UNESCAPED_UNICODE);
		$dataEncry = np_Hoplite_Encryption($data, 'callWSconsultarTM');
		$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
		$data = json_encode($data);
		$response = np_Hoplite_GetWS('eolwebInterfaceWS',$data);
		$jsonResponse = np_Hoplite_Decrypt($response, 'callWSconsultarTM');
		$response = json_decode($jsonResponse);

		if($response) {
			if($response->rc == 0) {
				unset(
					$response->rc, $response->msg, $response->className, $response->token, $response->idOperation,
					$response->logAccesoObject, $response->usuario
				);
				log_message('DEBUG', 'RESULTS: ' . json_encode($response));
				return $response;
			} else {
				if($response->rc ==- 61 || $response->rc ==- 29) {
					$this->session->sess_destroy();
					$codigoError = ['ERROR'=> '-29'];
				} else {
					$codigoError = lang('ERROR_('.$response->rc.')');
					if(strpos($codigoError, 'Error') !== false){
							$codigoError = ['ERROR'=> $response->msg];
					} else {
						if(gettype($codigoError) == 'boolean'){
							$codigoError = ['ERROR'=> $response->msg];
						} else {
							$codigoError = ['ERROR'=> lang('ERROR_('.$response->rc.')')];
						}
					}
				}
				return $codigoError;
			}
		} else {
			return $codigoError = ['ERROR'=> lang('ERROR_GENERICO_USER')];
		}
	}

	/**
	 * Método destinado a realizar la operación de abono
	 * para una o varias tarjetas en transferencia maestra.
	 *
	 * @param  string $urlCountry
	 * @return json
	 */
	public function abonarAtarjeta($urlCountry)
	{
		np_hoplite_countryCheck($urlCountry);
		$this->lang->load('erroreseol');

		$logged_in = $this->session->userdata('logged_in');
		$paisS = $this->session->userdata('pais');

		if($paisS == $urlCountry && $logged_in){
			$menuP = $this->session->userdata('menuArrayPorProducto');
			$funcAct = in_array("traabo", np_hoplite_modFunciones($menuP));

			if ($funcAct) {
				$result = $this->callWSabonarTM($urlCountry);
			}else{
				$result = ["ERROR"=>lang('SIN_FUNCION')];
			}

			$this->output->set_content_type('application/json')->set_output(json_encode($result));

		} elseif ($paisS!=$urlCountry && $paisS!=''){
			$this->session->sess_destroy();
			redirect($urlCountry.'/login');

		} elseif ($this->input->is_ajax_request()){
			$this->output->set_content_type('application/json')->set_output(json_encode( array('ERROR' => '-29' )));

		} else {
			redirect($urlCountry.'/login');

		}
	}

	/**
	 * Método que realiza petición al WS para realizar abono a tarjetas en T.M.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	private function callWSabonarTM($urlCountry)
	{
		$this->lang->load('erroreseol');

		$canal = "ceo";
		$modulo="TM";
		$function="abonaTransferenciaM";
		$operation = "abonarTM";
		$ip= $this->input->ip_address();
		$timeLog= date("m/d/Y H:i");
		$className="com.novo.objects.MO.TransferenciaMO";
		$username = $this->session->userdata('userName');
		$token = $this->session->userdata('token');
		$idEmpresa = $this->session->userdata('acrifS');
		$idProductoS = $this->session->userdata('idProductoS');
		$pg = $this->input->post('data-pg');
		$paginas = $this->input->post('data-paginas');
		$paginar = $this->input->post('data-paginar');

		$listaTarjetas = [
			"paginaActual" => $pg,
			"tamanoPagina" => $paginas,
			"paginar" => $paginar
		];
		$listaTarjetas = [$listaTarjetas];

		$tarjetas = $this->input->post('data-tarjeta');
		$dnis = $this->input->post('data-id_ext_per');
		$montoTrans = $this->input->post('data-monto');
		$pass = $this->input->post('data-pass');
		$lista;

		foreach ($tarjetas as $key => $value) {
			$tjs = [
				"noTarjeta" => $value,
				"id_ext_per" => $dnis[$key],
				"montoTransaccion" => $montoTrans[$key]
			];
			$lista[$key] = $tjs;
		}

		$listadoT = ['lista'=> $lista];
		$Ausuario = ["userName" =>$username, "password" =>$pass];
		$sessionId = $this->session->userdata('sessionId');
		$logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $operation, 0, $ip, $timeLog);

		$data = [
			"pais" => $urlCountry,
			"idOperation" => $operation,
			"className" => $className,
			"rifEmpresa" => $idEmpresa,
			"listaTarjetas" => $listaTarjetas,
			"listadoTarjetas" => $listadoT,
			"usuario" => $Ausuario,
			"idProducto" => $idProductoS,
			"logAccesoObject" => $logAcceso,
			"token" => $token
		];

		$data = json_encode($data, JSON_UNESCAPED_UNICODE);
		$dataEncry = np_Hoplite_Encryption($data, 'callWSabonarTM');
		$data = ['bean' => $dataEncry, 'pais' =>$urlCountry ];
		$data = json_encode($data);
		$response = np_Hoplite_GetWS('eolwebInterfaceWS',$data);
		$jsonResponse = np_Hoplite_Decrypt($response, 'callWSabonarTM');
		$response = json_decode($jsonResponse);

		if($response) {
			if($response->rc == 0) {
				unset(
					$response->rc, $response->msg, $response->className, $response->token, $response->idOperation,
					$response->logAccesoObject, $response->usuario
				);
				log_message('DEBUG', 'RESULTS: ' . json_encode($response));
				return $response;

			} else {
				if($response->rc == -61 || $response->rc ==- 29){
					$this->session->sess_destroy();
					$codigoError = ['ERROR' => '-29'];

				} else {
					$codigoError = lang('ERROR_('.$response->rc.')');
					if(strpos($codigoError, 'Error') !== false) {
							$codigoError = ['ERROR'=> $response->msg];

					} else if(!$codigoError) {
							$codigoError = ['ERROR'=> $response->msg];

					} else {
						if(gettype($codigoError) == 'boolean'){
							$codigoError = ['ERROR'=> $response->msg];

						} else {
							if(gettype($codigoError) == 'boolean'){
								$codigoError = ['ERROR'=> $response->msg];

							} else {
								$codigoError = ['ERROR'=> lang('ERROR_('.$response->rc.')')];

							}
						}
					}
				}
				return $codigoError;

			}
		} else {
			return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER') );

		}
	}

	/**
	 * Método destinado a realizar la operación de cargar en la cuenta en TM.
	 *
	 * @param  string $urlCountry
	 * @return json
	 */

	public function cargarAtarjeta($urlCountry){

		np_hoplite_countryCheck($urlCountry);

		$this->lang->load('erroreseol');

		$logged_in = $this->session->userdata('logged_in');
		$paisS = $this->session->userdata('pais');

		if($paisS==$urlCountry && $logged_in) {
			$menuP =$this->session->userdata('menuArrayPorProducto');
			$funcAct = in_array("tracar", np_hoplite_modFunciones($menuP));
			if($funcAct) {
				$result = $this->callWScargarTM($urlCountry);

			} else {
				$result = array("ERROR"=>lang('SIN_FUNCION'));

			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));

		} elseif ($paisS!=$urlCountry && $paisS!='') {
			$this->session->sess_destroy();
			redirect($urlCountry.'/login');

		} elseif ($this->input->is_ajax_request()) {
			$this->output->set_content_type('application/json')->set_output(json_encode( array('ERROR' => '-29' )));

		} else {
				redirect($urlCountry.'/login');

		}
	}

	/**
	 * Método que realiza petición al WS para realizar la operación de cargar en la cuenta en TM.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	private function callWScargarTM($urlCountry)
	{
		$this->lang->load('erroreseol');

		$canal = "ceo";
		$modulo = "TM";
		$function = "cargoTransferenciaM";
		$operation = "cargoTM";
		$ip = $this->input->ip_address();
		$timeLog = date("m/d/Y H:i");
		$className ="com.novo.objects.MO.TransferenciaMO";
		$username = $this->session->userdata('userName');
		$token = $this->session->userdata('token');
		$idEmpresa = $this->session->userdata('acrifS');
		$idProductoS = $this->session->userdata('idProductoS');
		$pg = $this->input->post('data-pg');
		$paginas = $this->input->post('data-paginas');
		$paginar = $this->input->post('data-paginar');

		$listaTarjetas = [
			"paginaActual" => $pg,
			"tamanoPagina" => $paginas,
			"paginar" => $paginar
		];
		$listaTarjetas = [$listaTarjetas];
		$tarjetas = $this->input->post('data-tarjeta');
		$dnis = $this->input->post('data-id_ext_per');
		$montoTrans = $this->input->post('data-monto');
		$pass = $this->input->post('data-pass');
		$lista;

		foreach ($tarjetas as $key => $value) {
			$tjs = [
				"noTarjeta" => $value,
				"id_ext_per" => $dnis[$key],
				"montoTransaccion" => $montoTrans[$key]
			];
			$lista[$key] = $tjs;
		}
		$listadoT = ['lista'=> $lista];
		$Ausuario = ["userName" =>$username, "password" =>$pass];
		$sessionId = $this->session->userdata('sessionId');
		$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

		$data = [
			"pais" => $urlCountry,
			"idOperation" => $operation,
			"className" => $className,
			"rifEmpresa" => $idEmpresa,
			"listaTarjetas" => $listaTarjetas,
			"listadoTarjetas" => $listadoT,
			"usuario" => $Ausuario,
			"idProducto" => $idProductoS,
			"logAccesoObject" => $logAcceso,
			"token" => $token
		];

		$data = json_encode($data, JSON_UNESCAPED_UNICODE);
		$dataEncry = np_Hoplite_Encryption($data, 'callWScargarTM');
		$data = ['bean' => $dataEncry, 'pais' =>$urlCountry];
		$data = json_encode($data, JSON_UNESCAPED_UNICODE);
		$response = np_Hoplite_GetWS('eolwebInterfaceWS',$data);
		$jsonResponse = np_Hoplite_Decrypt($response, 'callWScargarTM');
		$response = json_decode($jsonResponse);

		if($response) {
			if($response->rc == 0) {
				unset(
					$response->rc, $response->msg, $response->className, $response->token, $response->idOperation,
					$response->logAccesoObject, $response->usuario
				);
				log_message('DEBUG', 'RESULTS: ' . json_encode($response));
				return $response;

			} else {
				if($response->rc == -61 || $response->rc == -29) {
					$this->session->sess_destroy();
					$codigoError = array('ERROR' => '-29' );

				} else {
					$codigoError = lang('ERROR_('.$response->rc.')');

					if(strpos($codigoError, 'Error') !== false) {
						$codigoError = array('ERROR'=> $response->msg );

					} else {
						if(gettype($codigoError) == 'boolean'){
							$codigoError = array('ERROR'=> $response->msg);
						} else {
							$codigoError = array('ERROR'=> lang('ERROR_('.$response->rc.')') );
						}

					}
				}
				return $codigoError;

			}
		} else {
			return $codigoError = ['ERROR' => lang('ERROR_GENERICO_USER')];

		}
	}

	/**
	 * Pantalla para el módulo de actualización de datos (poliza).
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */

	public function actualizarDatos($urlCountry)
	{
		np_hoplite_countryCheck($urlCountry);

		$this->lang->load('servicios');
		$this->lang->load('dashboard');
		$this->lang->load('users');
		$this->lang->load('erroreseol');

		$logged_in = $this->session->userdata('logged_in');
		$menuP =$this->session->userdata('menuArrayPorProducto');
		$moduloAct = np_hoplite_existeLink($menuP,"TEBPOL");
		$paisS = $this->session->userdata('pais');

		if($paisS == $urlCountry && $logged_in && $moduloAct !==false ) {
			$FooterCustomInsertJS = ["jquery-1.10.2.min.js", "jquery-ui-1.10.3.custom.min.js", "jquery.balloon.min.js",
			"jquery.dataTables.min.js", "header.js", "dashboard/widget-empresa.js", "jquery.fileupload.js", "jquery.iframe-transport.js", "servicios/actualizar-datos.js", "routes.js"];
			$FooterCustomJS = "";
			$titlePage = "Actualizar datos";
			$programa = $this->session->userdata('nombreProductoS').' / '. $this->session->userdata('marcaProductoS');
			$menuHeader = $this->parser->parse('widgets/widget-menuHeader', [], TRUE);
			$menuFooter = $this->parser->parse('widgets/widget-menuFooter', [], TRUE);
			$estatus = $this->callWsEstatusArchivo($urlCountry);

			if(!array_key_exists("ERROR", $estatus)) {
				$estatus = $estatus->lista;

			} else if($estatus["ERROR"]=='-29') {
				echo "<script>alert('usuario actualmente desconectado'); location.href = '".$this->config->item('base_url')."$urlCountry/login';</script>";

			}

			$header = $this->parser->parse('layouts/layout-header', array('bodyclass'=>'', 'menuHeaderActive'=>TRUE,
			'menuHeaderMainActive'=>TRUE, 'menuHeader'=>$menuHeader, 'titlePage'=>$titlePage), TRUE);
			$footer = $this->parser->parse('layouts/layout-footer', array('menuFooterActive'=> TRUE,'menuFooter'=>
			$menuFooter, 'FooterCustomInsertJSActive'=> TRUE,'FooterCustomInsertJS'=> $FooterCustomInsertJS,
			'FooterCustomJSActive'=> TRUE, 'FooterCustomJS'=> $FooterCustomJS), TRUE);
			$content = $this->parser->parse('servicios/content-actualizar-datos', array(
				"estatus"=> $estatus,
				"programa"=> $programa
			), TRUE);
			$sidebarLotes = $this->parser->parse('dashboard/widget-empresa', array('sidebarActive'=> TRUE), TRUE);
			$datos = array(
				'header'=> $header,
				'content'=> $content,
				'footer'=> $footer,
				'sidebar'=> $sidebarLotes
			);

			$this->parser->parse('layouts/layout-b', $datos);

		} elseif($paisS!=$urlCountry) {
			$this->session->sess_destroy();
			redirect($urlCountry.'/login');

		} else {
			redirect($urlCountry.'/login');
		}
	}

	/**
	 * Método que realiza petición al WS para obtener el listado
	 * de los estatus de archivo en actualizar los datos.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	private function callWsEstatusArchivo($urlCountry)
	{
		$this->lang->load("erroreseol");

		$operation = "buscarEstatusPolizas";
		$className = "com.novo.objects.MO.PolizaMO";
		$canal = "ceo";
		$modulo = "Polizas";
		$function = "Actualizacion de Polizas";
		$timeLog = date("m/d/Y H:i");
		$ip = $this->input->ip_address();
		$sessionId = $this->session->userdata('sessionId');
		$username = $this->session->userdata('userName');
		$token = $this->session->userdata('token');
		$logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $operation, 0, $ip, $timeLog);
		$data = array(
			"pais"=> $urlCountry,
			"idOperation"=> $operation,
			"className"=> $className,
			"logAccesoObject"=> $logAcceso,
			"token"=> $token
		);
		$data = json_encode($data, JSON_UNESCAPED_UNICODE);
		$dataEncry = np_Hoplite_Encryption($data, 'callWsEstatusArchivo');
		$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
		$data = json_encode($data);
		$response = np_Hoplite_GetWS('eolwebInterfaceWS',$data);
		$jsonResponse = np_Hoplite_Decrypt($response, 'callWsEstatusArchivo');
		$response = json_decode($jsonResponse);

		if($response) {
			if($response->rc==0 || $response->rc==-128){
				return $response;

			} else {
				if($response->rc==-61 || $response->rc==-29){
					$this->session->sess_destroy();
					return array('ERROR' => '-29' );

				}	else {
					$codigoError = lang('ERROR_('.$response->rc.')');
					if(strpos($codigoError, 'Error')!==false) {
						$codigoError = array('ERROR' => $response->msg );

					} else {
						if(gettype($codigoError)=='boolean'){
							$codigoError = array('ERROR' => $response->msg);

						} else {
							$codigoError = array('ERROR' => lang('ERROR_('.$response->rc.')') );

						}
					}
					return $codigoError;
				}
			}
		} else {
			log_message('info',"combo estatus NO WS");
			return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));

		}
	}

	/**
	 * Método para cargar archivo masivo de usuarios a actualizar.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	public function cargarArchivo($urlCountry)
	{
		np_hoplite_countryCheck($urlCountry);

		$logged_in = $this->session->userdata('logged_in');
		$paisS = $this->session->userdata('pais');
		$menuP = $this->session->userdata('menuArrayPorProducto');
		$moduloAct = np_hoplite_existeLink($menuP,"TEBPOL");

		if($paisS == $urlCountry && $logged_in && $moduloAct !== false){

			$this->lang->load('upload');
			$this->lang->load('erroreseol');

			$config['upload_path'] = $this->config->item('FOLDER_UPLOAD_LOTES');
			$config['allowed_types'] = 'xls|xlsx';

			$this->load->library('upload', $config);

			//VERIFICAR SI NO SUBIO ARCHIVO
			if (!$this->upload->do_upload()) {
				log_message('error', 'temp repos '.$config['upload_path']);

				$error = array('ERROR' => 'No se puede cargar el archivo. Verifiquelo e intente de nuevo');
				echo json_encode($error);

			} else {
				//VALIDO
				$data = array('upload_data' => $this->upload->data());
				$nombreArchivo = $data["upload_data"]["file_name"];//NOMBRE ARCHIVO CON EXTENSION
				$rutaArchivo = $data["upload_data"]["file_path"];
				$ch = curl_init();
				$localfile = $config['upload_path'].$nombreArchivo;
				$fp = fopen($localfile, 'r');

				$URL_TEMPLOTES = $this->config->item('URL_TEMPLOTES');
				$LOTES_USERPASS = $this->config->item('LOTES_USERPASS');

				curl_setopt($ch, CURLOPT_URL, $URL_TEMPLOTES.$nombreArchivo);
				curl_setopt($ch, CURLOPT_USERPWD, $LOTES_USERPASS);
				curl_setopt($ch, CURLOPT_UPLOAD, 1);
				curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_SFTP);
				curl_setopt($ch, CURLOPT_INFILE, $fp);
				curl_setopt($ch, CURLOPT_INFILESIZE, filesize($localfile));
				curl_exec ($ch);

				$error_no = curl_errno($ch); log_message('ERROR',"subiendo archivo lotes sftp ".$error_no."/".lang("SFTP(".$error_no.")"));
				curl_close ($ch);

				if($error_no == 0) {
					unlink("$localfile"); //BORRAR ARCHIVO
					$error = 'Archivo Movido.';
					//COLOCAR LLAMADO DE LA FUNCION CUANDO ESTE CORRECTO
					$username = $this->session->userdata('userName');
					$token = $this->session->userdata('token');
					$cargaLote = $this->callWScargarArchivo($urlCountry,$nombreArchivo);
					echo json_encode($cargaLote);

				} else {
					$error = array('ERROR' => 'Falla Al mover archivo.');
					echo json_encode($error);

				}
			}
		} elseif($paisS!=$urlCountry && $paisS!='') {
			$this->session->sess_destroy();
			redirect($urlCountry.'/login');

		}elseif($this->input->is_ajax_request()){
			$this->output->set_content_type('application/json')->set_output(json_encode( array('ERROR' => '-29' )));

		} else {
			redirect($urlCountry.'/login');
		}
	}

	/**
	 * Método que realiza petición al WS para cargar archivo de actualización de datos.
	 *
	 * @param  [string] $urlCountry   [description]
	 * @param  [string] $nombreOriginal   [description]
	 * @return [array]                [description]
	 */
	private function callWScargarArchivo($urlCountry,$nombreOriginal)
	{
		$this->lang->load('erroreseol');

		$canal = "ceo";
		$modulo = "Polizas";
		$function = "Actualizacion de Polizas";
		$operation = "actualizarPolizas";
		$className = "com.novo.objects.MO.PolizaMO";
		$timeLog = date("m/d/Y H:i");
		$ip = $this->input->ip_address();
		$sessionId = $this->session->userdata('sessionId');
		$username = $this->session->userdata('userName');
		$token = $this->session->userdata('token');
		$logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $operation, 0, $ip, $timeLog);
		$lista = array("nombreArchivo"=> $nombreOriginal);
		$data = array(
			"idOperation" => $operation,
			"className" => $className,
			"lista"=>[$lista],
			"logAccesoObject"=>$logAcceso,
			"token"=>$token,
			"pais" => $urlCountry
		);
		$data = json_encode($data,JSON_UNESCAPED_UNICODE);
		log_message('info',"carga actualizarDatos ".$data);
		$dataEncry = np_Hoplite_Encryption($data);
		$data = array('bean'=> $dataEncry, 'pais'=> $urlCountry );
		$data = json_encode($data);
		$response = np_Hoplite_GetWS('eolwebInterfaceWS',$data);
		$jsonResponse = np_Hoplite_Decrypt($response);
		$response = json_decode($jsonResponse);

		if($response) {
			log_message('info',"carga actualizarDatos ".$response->rc.'/'.$response->msg);
			if($response->rc == 0 || $response->rc == -128) {
				return $response;

			} else {
				if($response->rc==-61 || $response->rc==-29){
					$this->session->sess_destroy();
					return array('ERROR' => '-29' );

				} else {
					$codigoError = lang('ERROR_('.$response->rc.')');

					if(strpos($codigoError, 'Error')!==false) {
						$codigoError = array('ERROR' => $response->msg );

					} else {
						if(gettype($codigoError)=='boolean'){
							$codigoError = array('ERROR' => $response->msg);

						} else {
							$codigoError = array('ERROR' => lang('ERROR_('.$response->rc.')'));

						}
					}
					return $codigoError;

				}
			}
		} else {
			log_message('info',"carga actualizarDatos NO WS");
			return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));

		}
	}

	/**
	 * Método para buscar el listado de asegurados en actualización de datos
	 *
	 * @param  [string] $urlCountry   [description]
	 * @return [JSON]                [description]
	 */
	public function buscarDatos($urlCountry)
	{
		np_hoplite_countryCheck($urlCountry);
		$this->lang->load('erroreseol');

		$logged_in = $this->session->userdata('logged_in');
		$paisS = $this->session->userdata('pais');

		if($paisS==$urlCountry && $logged_in) {
			$nombre = $this->input->post("data-nombre");
			$status = $this->input->post("data-status");
			$result = $this->callWSbuscarDatos($urlCountry,$nombre,$status);

			$this->output->set_content_type('application/json')->set_output(json_encode($result));

		} elseif($paisS != $urlCountry && $paisS != '') {
			$this->session->sess_destroy();
			redirect($urlCountry.'/login');

		} elseif($this->input->is_ajax_request()) {
			$this->output->set_content_type('application/json')->set_output(json_encode( array('ERROR' => '-29' )));
		} else {
			redirect($urlCountry.'/login');
		}
	}

	/**
	 * Método que realiza petición al WS para buscar el listado de asegurados en actualización de datos.
	 *
	 * @param  string $urlCountry
	 * @param  string $nombre
	 * @param  string $status
	 * @return json
	 */
	private function callWSbuscarDatos($urlCountry,$nombre,$status)
	{
		$this->lang->load('erroreseol');

		$canal = "ceo";
		$modulo = "Polizas";
		$function = "Actualizacion de Polizas";
		$operation ="buscarPolizas";
		$className ="com.novo.objects.MO.PolizaMO";
		$timeLog = date("m/d/Y H:i");
		$ip = $this->input->ip_address();
		$sessionId = $this->session->userdata('sessionId');
		$username = $this->session->userdata('userName');
		$token = $this->session->userdata('token');
		$logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $operation, 0, $ip, $timeLog);
		$idProductoS = $this->session->userdata('idProductoS');
		$acodcia = $this->session->userdata('accodciaS');
		$usuario = array(
			"userName" => $username
		);
		$data = array(
			"idOperation" => $operation,
			"className" => $className,
			"idProducto"=>$idProductoS,
			"paginar"=>"false",
			"paginaActual"=>"1",
			"tamanoPagina"=>"10",
			"estatus"=>$status,
			"nombreArchivo" => $nombre,
			"acCodCia"=>$acodcia,
			"logAccesoObject"=>$logAcceso,
			"token"=>$token,
			"pais" => $urlCountry
		);
		$data = json_encode($data, JSON_UNESCAPED_UNICODE);
		$dataEncry = np_Hoplite_Encryption($data);
		$data = array('bean'=> $dataEncry, 'pais'=> $urlCountry );
		$data = json_encode($data);
		$response = np_Hoplite_GetWS('eolwebInterfaceWS',$data);
		$jsonResponse = np_Hoplite_Decrypt($response);
		$response = json_decode($jsonResponse);

		if($response) {
			log_message('info',"BUSCAR actualizarDatos ".$response->rc.'/'.$response->msg);

			if($response->rc==0 || $response->rc==-128){
					return $response;
			} else {
				if($response->rc==-61 || $response->rc==-29){
					$this->session->sess_destroy();
					return array('ERROR' => '-29' );

				} else {
					$codigoError = lang('ERROR_('.$response->rc.')');
					if(strpos($codigoError, 'Error') !== false){
						$codigoError = array('ERROR'=> $response->msg);

					} else {
						if(gettype($codigoError)=='boolean') {
							$codigoError = array('ERROR' => $response->msg);

						} else {
							$codigoError = array('ERROR' => lang('ERROR_('.$response->rc.')') );

						}
					}
					return $codigoError;

				}
			}
		} else {
			log_message('info',"buscar actualizarDatos NO WS");
			return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));

		}
	}

	/**
	 * Método para descargar documento Excel en actualización de datos.
	 *
	 * @param  string $urlCountry
	 * @return file
	 */
	public function downXLS_AD($urlCountry)
	{

		np_hoplite_countryCheck($urlCountry);

		$this->lang->load('erroreseol');//HOJA DE ERRORES;

		$logged_in = $this->session->userdata('logged_in');
		$paisS = $this->session->userdata('pais');
		$menuP =$this->session->userdata('menuArrayPorProducto');
		$moduloAct = np_hoplite_existeLink($menuP,"TEBPOL");

		if($paisS==$urlCountry && $logged_in && $moduloAct!==false) {

			$canal = "ceo";
			$modulo = "Polizas";
			$function = "Actualizacion de Polizas";
			$operation = "descargarPolizas";
			$className = "com.novo.objects.MO.PolizaMO";
			$timeLog = date("m/d/Y H:i");
			$ip = $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$username = $this->session->userdata('userName');
			$token = $this->session->userdata('token');
			$logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $operation, 0, $ip, $timeLog);
			$fecha = $this->input->post('data-fecha');
			$nombre = $this->input->post('data-nomb');
			$lista = array("fechaRegistro"=> $fecha,"nombreArchivo"=> $nombre);
			$data = array(
				"pais"=> $urlCountry,
				"idOperation"=> $operation,
				"className"=> $className,
				"lista"=> [$lista],
				"logAccesoObject"=> $logAcceso,
				"token"=> $token
			);
			$data = json_encode($data);
			$dataEncry = np_Hoplite_Encryption($data);
			$data = array('bean'=> $dataEncry, 'pais'=> $urlCountry);
			$data = json_encode($data);
			$response = np_Hoplite_GetWS('eolwebInterfaceWS', $data);
			$jsonResponse = np_Hoplite_Decrypt($response);
			$response =  json_decode($jsonResponse);

			if($response) {
				log_message("INFO",'descargar xls actializacion datos '.$response->rc.'/'.$response->msg);

				if($response->rc==0) {
						$nombreArchivo = explode(".", $response->lista[0]->nombreArchivo);
						$ext = end($nombreArchivo);
						array_pop($nombreArchivo);
						np_hoplite_byteArrayToFile($response->lista[0]->archivo,$ext,implode($nombreArchivo));
						unset($nombreArchivo);

				} else {

					if($response->rc==-61 || $response->rc==-29){
						$this->session->sess_destroy();
						echo "<script>alert('usuario actualmente desconectado');
						location.href = '".$this->config->item('base_url').$urlCountry."/servicios/actualizar-datos';</script>";

					} else {
						$codigoError = lang('ERROR_('.$response->rc.')');
						if(strpos($codigoError, 'Error')!==false){
							$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);

						} else {
							$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);

						}
						echo '<script languaje=\"javascript\">alert("'.$codigoError["mensaje"].'");  location.href = "'.$this->config->item('base_url').$urlCountry.'/servicios/actualizar-datos"; </script>';
						return $codigoError;

					}
				}

			} else {
				log_message("INFO",'descargar xls actializacion datos NO WS');

				echo "
				<script>
				alert('".lang('ERROR_GENERICO_USER')."');
				location.href = '".$this->config->item('base_url').$urlCountry."/servicios/actualizar-datos';
				</script>";

			}

		} elseif($paisS != $urlCountry && $paisS != "") {
			$this->session->sess_destroy();
			redirect($urlCountry.'/login');

		} else {
			redirect($urlCountry.'/login');
		}
	}
////////////////////////////////// pichincha ////////////////////////////////////////////////////
	public function consultarSaldo($urlCountry)
	{
		np_hoplite_countryCheck($urlCountry);

		$this->lang->load('erroreseol');

		$logged_in = $this->session->userdata('logged_in');
		$paisS = $this->session->userdata('pais');

		if($paisS==$urlCountry && $logged_in) {
			$menuP = $this->session->userdata('menuArrayPorProducto');
			$funcAct = in_array("trasal", np_hoplite_modFunciones($menuP));

			if($funcAct) {
				$result = $this->callWsConsultaSaldo($urlCountry);
			} else {
				$result = array("ERROR"=>lang('SIN_FUNCION'));
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));

		} elseif($paisS!=$urlCountry && $paisS!='') {
			$this->session->sess_destroy();
			redirect($urlCountry.'/login');

		} elseif($this->input->is_ajax_request()) {
			$this->output->set_content_type('application/json')->set_output(json_encode( array('ERROR' => '-29' )));
		} else {
			redirect($urlCountry.'/login');
		}
	}

	private function callWsConsultaSaldo($urlCountry)
	{
		$this->lang->load('erroreseol');
		$token = $this->session->userdata('token');
		$canal = "ceo";
		$modulo="TM";
		$function="buscarTransferenciaM";
		$operation="saldoCuentaMaestraTM";
		$logOperation="SaldoCuentaM";
		$RC=0;
		$className="com.novo.objects.MO.TransferenciaMO";
		$timeLog= date("m/d/Y H:i");
		$ip= $this->input->ip_address();
		$idEmpresa = $this->session->userdata('acrifS');
		$sessionId = $this->session->userdata('sessionId');
		$username = $this->session->userdata('userName');
		$token = $this->session->userdata('token');
		$logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $logOperation, $RC, $ip, $timeLog);
		$idProductoS = $this->session->userdata('idProductoS');
		$acodcia = $this->session->userdata('accodciaS');
		$usuario = [
			"userName" => $username
		];
		$data = [
			"idOperation" => $operation,
			"token"=>$token,
			"className" => $className,
			"rifEmpresa"=> $idEmpresa,
			"logAccesoObject"=>$logAcceso,
			"pais" => $urlCountry
		];
		$data = json_encode($data, JSON_UNESCAPED_UNICODE);
		$dataEncry = np_Hoplite_Encryption($data, 'callWsConsultaSaldo');
		$data = ['bean'=> $dataEncry, 'pais'=> $urlCountry];
		$request = json_encode($data);
		$encrypResponse = np_Hoplite_GetWS('eolwebInterfaceWS', $request);
		$jsonResponse = np_Hoplite_Decrypt($encrypResponse, 'callWsConsultaSaldo');
		$response = json_decode($jsonResponse);

		if($response->rc == 0) {
			log_message("DEBUG", "RESULTS : " . json_encode($response->maestroDeposito, JSON_UNESCAPED_UNICODE));

		}
		return $jsonResponse;
	}

	public function RegargaTM($urlCountry)
	{
		np_hoplite_countryCheck($urlCountry);

		$this->lang->load('erroreseol');

		$logged_in = $this->session->userdata('logged_in');
		$paisS = $this->session->userdata('pais');

		if($paisS==$urlCountry && $logged_in) {
			$menuP =$this->session->userdata('menuArrayPorProducto');
			$funcAct = in_array("trasal", np_hoplite_modFunciones($menuP));

			if($funcAct){
				$result = $this->callWsRecargaTM($urlCountry);

			}else{
				$result = array("ERROR"=>lang('SIN_FUNCION'));

			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));

		} elseif($paisS!=$urlCountry && $paisS!='') {
			$this->session->sess_destroy();
			redirect($urlCountry.'/login');

		} elseif($this->input->is_ajax_request()) {
			$this->output->set_content_type('application/json')->set_output(json_encode( array('ERROR' => '-29' )));
		} else {
			redirect($urlCountry.'/login');
		}
	}

	//solicitud de envío de token de seguridad
	private function callWsRecargaTM($urlCountry)
	{
		$this->lang->load('erroreseol');
		$this->lang->load('consultas');
		$this->lang->load('servicios');
		$canal = "ceo";
		$modulo="Pagos";
		$function="Doble Autenticacion";
		$operation="dobleAutenticacion";
		$logOperation="Generar Token";
		$RC=0;
		$className="com.novo.objects.TO.UsuarioTO";
		$timeLog= date("m/d/Y H:i");
		$ip= $this->input->ip_address();

		$sessionId = $this->session->userdata('sessionId');
		$username = $this->session->userdata('userName');
		$token = $this->session->userdata('token');
		$logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $logOperation, $RC, $ip, $timeLog);

		$data = [
			"idOperation" => $operation,
			"token"=>$token,
			"className" => $className,
			"logAccesoObject"=>$logAcceso,
			"pais" => $urlCountry
		];

		$request = json_encode($data, JSON_UNESCAPED_UNICODE);
		$dataEncry = np_Hoplite_Encryption($request, 'callWsRecargaTM solicitar TOKEN');
		$data = ['bean' => $dataEncry, 'pais' =>$urlCountry];

		$request = json_encode($data);
		$encrypResponse = np_Hoplite_GetWS('eolwebInterfaceWS', $request);
		$jsonResponse = np_Hoplite_Decrypt($encrypResponse, 'callWsRecargaTM solicitar TOKEN');
		$response =  json_decode($jsonResponse);

		//simula respuesta de WS
		/*sleep(2);
		$data = '{"rc":0,"msg":" ", "bean":"123hgf"}';
		$response = json_decode($data);*/
		if ($response) {
			switch ($response->rc) {
				case 0:
					log_message('DEBUG', 'BEAN: '.json_encode($response->bean));
					$bean = ['bean' => $response->bean];
					$this->session->set_userdata($bean);
					$response = [
						'code' => 0,
						'title' => lang('REG_CTA_CONCEN'),
						'msg' => lang('PAG_OS_ENV_OK')
					];
					break;
				case -61:
				case -29:
					$response = [
						'code' => 2,
						'title' => lang('TITULO_CEO'),
						'msg' => lang('ERROR_(-29)')
					];
					break;
				default:
					$response = [
						'code' => 1,
						'title' => lang('REG_CTA_CONCEN'),
						'msg' => lang('PAG_OS_E_CORREO')
					];
			}
		} else {
			$response = [
				'code' => 2,
				'title' => lang('TITULO_CEO'),
				'msg' => lang('ERROR_GENERICO_USER')
			];
		}

		return $response;
	}


	public function RegargaTMProcede($urlCountry)
	{
		np_hoplite_countryCheck($urlCountry);

		$amount = $this->input->post('amount');
		$descript = $this->input->post('descript');
		$account = $this->input->post('account');
		$type = $this->input->post('type');
		$codeToken = $this->input->post('codeToken');

		$this->lang->load('erroreseol');

		$logged_in = $this->session->userdata('logged_in');
		$paisS = $this->session->userdata('pais');

		if($paisS==$urlCountry && $logged_in){
			$menuP =$this->session->userdata('menuArrayPorProducto');
			$funcAct = in_array("trasal", np_hoplite_modFunciones($menuP));

			if($funcAct) {
				$result = $this->callWsRecargaTMProcede($urlCountry, $amount, $descript, $account, $type, $codeToken);
			}else{
				$result = array("ERROR"=>lang('SIN_FUNCION'));
			}
			$this->output->set_content_type('application/json')->set_output(json_encode($result));

		} elseif($paisS!=$urlCountry && $paisS!='') {
			$this->session->sess_destroy();
			redirect($urlCountry.'/login');
		} elseif($this->input->is_ajax_request()) {
			$this->output->set_content_type('application/json')->set_output(json_encode( array('ERROR' => '-29' )));
		} else {
			redirect($urlCountry.'/login');
		}
	}


	private function callWsRecargaTMProcede($urlCountry, $amount, $descript, $account, $type, $codeToken)
	{
		/// recarga transferencia maestra
		np_hoplite_countryCheck($urlCountry);
		$this->lang->load('erroreseol');
		$this->lang->load('consultas');
		$this->lang->load('servicios');

		$paisS = $this->session->userdata('pais');
		$canal = "ceo";
		$modulo="TM";
		$function="buscarTransferenciaM";
		$operation="cargoCuentaMaestraTM";
		$logOperation="AbonarCuentaM";
		$RC=0;
		$className="com.novo.objects.MO.TransferenciaMO";
		$timeLog= date("m/d/Y H:i");
		$ip= $this->input->ip_address();

		$idEmpresa = $this->session->userdata('acrifS');
		$sessionId = $this->session->userdata('sessionId');
		$username = $this->session->userdata('userName');
		$token = $this->session->userdata('token');
		$logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $logOperation, $RC, $ip, $timeLog);

		$bean = $this->session->userdata('bean');
		$idProducto = $this->session->userdata('idProductoS');

		$maestroDeposito = [
			"idExtEmp"=>$idEmpresa,
			"saldo"=> round($amount,2),
			"descrip"=> $descript,
			"cuentaCliente"=> $account,
			"type"=> $type,
			"tokenCliente"=> $codeToken,
			"authToken"=> $bean,
			"idProducto"=> $idProducto
		];

		$data = [
			"pais" => $paisS,
			"idOperation" => $operation,
			"className" => $className,
			"maestroDeposito" =>  $maestroDeposito,
			"logAccesoObject" => $logAcceso,
			"token" => $token
		];

		$data = json_encode($data, JSON_UNESCAPED_UNICODE);
		$dataEncry = np_Hoplite_Encryption($data, 'callWsRecargaTMProcede');
		$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );

		$data = json_encode($data);
		$response = np_Hoplite_GetWS('eolwebInterfaceWS', $data);
		$jsonResponse = np_Hoplite_Decrypt($response, 'callWsRecargaTMProcede');
		$response = json_decode($jsonResponse);

		//simula respuesta de WS
		// sleep(2);
		// $data = '{"rc":-288,"msg":"Mensaje Banco"}';
		// log_message("info","RESPONSE simulado recarga Cta Concentradora------------------->>>>      " . $data);
		// $response = json_decode($data);
		if ($response) {
			$rc = $response->rc;
			$codeError =[-21, -155, -233, -241, -281, -285, -286, -287, -288, -296, -297, -298, -299, -301];
			$errorMsg = (in_array($rc, $codeError)) ?  lang('ERROR_('.$response->rc.')') : lang('ERROR_(-230)');
			$errorMsg = ($rc == -300) ? $response->msg : $errorMsg;
			switch ($rc) {
				case 0:
					$response = [
						'code' => 0,
						'title' => lang('REG_CTA_CONCEN'),
						'msg' => lang('REG_CTA_OK')
					];
					break;
				case -61:
				case -29:
					$response = [
						'code' => 2,
						'title' => lang('REG_CTA_CONCEN'),
						'msg' => lang('ERROR_(-29)')
					];
					break;
				default:
					$response = [
						'code' => 1,
						'title' => lang('REG_CTA_CONCEN'),
						'msg' => $errorMsg
					];
			}
		} else {
			$response = [
				'code' => 2,
				'title' => lang('TITULO_CEO'),
				'msg' => lang('ERROR_GENERICO_USER')
			];
		}
		return $response;
	}
}
