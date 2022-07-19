<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Clase Consultas
 *
 * Esta clase realiza las operaciónes relacionadas al módulo de consulta, tal como: ordenes de servicio.
 *
 * @package    controllers
 * @author     Wilmer Rojas <rojaswilmer@gmail.com>
 * @author     Carla García <neiryerit@gmail.com>
 */
class Consultas extends CI_Controller {

    /**
     * Pantalla que muestra el módulo de ordenes de servicio.
     *
     * @param  string $urlCountry
     */
    public function ordenesServicio($urlCountry){
			//VALIDATE COUNTRY
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('dashboard');
			$this->load->library('parser');
			$this->lang->load('users');
			$this->lang->load('consultas');
			$this->lang->load('erroreseol');

			$logged_in = $this->session->userdata('logged_in');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"TEBORS");
			$moduloActAuth = np_hoplite_existeLink($menuP,"TEBAUT");

			$paisS = $this->session->userdata('pais');

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){

					$osConfirm;
					if($this->input->post("data-status")){
							$fechaIni = $this->input->post("data-fechIn");
							$fechaFin = $this->input->post("data-fechFin");
							$status = $this->input->post("data-status");
							$osConfirm = $this->callWSbuscarOS($urlCountry, $fechaIni, $fechaFin, $status);
							if( array_key_exists("ERROR", $osConfirm) ) {

									if($osConfirm['ERROR']=='-29'){
											echo "<script>alert('Usuario actualmente desconectado'); window.location.href='".$urlCountry."/login"."';</script>";
									}
							}
							$osConfirm = serialize($osConfirm);
					}else if($this->input->post("data-OS")){
							$osConfirm = $this->input->post("data-OS");
					}else{
							$osConfirm = $this->input->post("data-confirm");
					}

					$username = $this->session->userdata('userName');
					$token = $this->session->userdata('token');
					$nombreCompleto = $this->session->userdata('nombreCompleto');
					$lastSessionD = $this->session->userdata('lastSession');
					$jsRte = '../../../js/';
					$thirdsJsRte = '../../../js/third_party/';
					$FooterCustomInsertJS=["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js","jquery.balloon.min.js","aes.min.js","aes-json-format.min.js","consultas/ordenes-servicio.js","dashboard/widget-empresa.js","header.js","jquery.dataTables.min.js","jquery-md5.js","routes.js",$thirdsJsRte."jquery.validate.min.js",$jsRte."validate-forms.js",$thirdsJsRte."additional-methods.min.js"];

					$FooterCustomJS="";
					$titlePage="Conexión Empresas Online - Consultas";
					$idProductoS = $this->session->userdata('idProductoS');
					$idEmpresa = $this->session->userdata('acrifS');

					if($this->session->userdata('marcaProductoS') === 'Cheque'){
						 $programa = $this->session->userdata('nombreProductoS');
						}else{
							$programa = $this->session->userdata('nombreProductoS').' / '.ucwords( $this->session->userdata('marcaProductoS'));
						}
					$tipoStatusOS[] = $this->callWStatusLotesOS($urlCountry);
					$menuP =$this->session->userdata('menuArrayPorProducto');
					$funciones = np_hoplite_modFunciones($menuP);

					//INSTANCIA MENU HEADER
					$menuHeader = $this->parser->parse('widgets/widget-menuHeader',array(),TRUE);
					//INSTANCIA MENU FOOTER
					$menuFooter = $this->parser->parse('widgets/widget-menuFooter',array(),TRUE);
					$header = $this->parser->parse('layouts/layout-header',array('bodyclass'=>'','menuHeaderActive'=>TRUE,'menuHeaderMainActive'=>TRUE,'menuHeader'=>$menuHeader,'titlePage'=>$titlePage),TRUE);
					$footer = $this->parser->parse('layouts/layout-footer',array('menuFooterActive'=>TRUE,'menuFooter'=>$menuFooter,'FooterCustomInsertJSActive'=>TRUE,'FooterCustomInsertJS'=>$FooterCustomInsertJS,'FooterCustomJSActive'=>TRUE,'FooterCustomJS'=>$FooterCustomJS),TRUE);
					$content = $this->parser->parse('consultas/content-ordenesServicio',array(
							'titulo'=>$nombreCompleto,
							'lastSession'=>$lastSessionD,
							'programa'=>$programa,
							'osConfirmV'=>$osConfirm,
							'tipoStatus' => $tipoStatusOS,
							'funciones' => $funciones
					),TRUE);
					$sidebarLotes= $this->parser->parse('dashboard/widget-empresa',array('sidebarActive'=>TRUE),TRUE);

					$datos = array(
							'header'=>$header,
							'content'=>$content,
							'footer'=>$footer,
							'sidebar'=>$sidebarLotes
					);

					$this->parser->parse('layouts/layout-b', $datos);

			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}elseif ($moduloActAuth!=false) {
					redirect($urlCountry.'/lotes/autorizacion');
			}else{
					redirect($urlCountry.'/login');
			}

	}

	/**
	 * Método que obtiene el listado de tipos de estatus de lote, para ser
	 * utilizado como parámetro de búsqueda en el módulo de orden de servicio.
	 *
	 * @param  string $urlCountry
	 * @param  string $tipoStatus [tipo_B: relevantes, tipo_A: todos]
	 * @return array
	 */
	private function callWStatusLotesOS($urlCountry, $tipoStatus='TIPO_B'){

			$this->lang->load('erroreseol');
			$idOperation = "estatusLotes";
			$className = "com.novo.objects.MO.EstatusLotesMO";

			$userName = $this->session->userdata('userName');
			$sessionId = $this->session->userdata('sessionId');
			$timeLog   = date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$canal = "ceo";
			$modulo = "statusLotesOS";
			$function = "tipoStatusLotesOS";

			$logAcceso = np_hoplite_log($sessionId,$userName,$canal,$modulo,$function,$idOperation,0,$ip,$timeLog);

			$token = $this->session->userdata('token');

			$data = array(
					'pais' => $urlCountry,
					'idOperation' => $idOperation,
					'className' => $className,
					'tipoEstatus' => $tipoStatus,
					'logAccesoObject' => $logAcceso,
					'token' => $token
			);

			$data = json_encode($data,JSON_UNESCAPED_UNICODE);

			$dataEncry = np_Hoplite_Encryption($data, 'callWStatusLotesOS');
			$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
			$data = json_encode($data);
			$response = np_Hoplite_GetWS($data);
			$jsonResponse = np_Hoplite_Decrypt($response, 'callWStatusLotesOS');

			$response = json_decode($jsonResponse);

			if($response){
					log_message('info', 'tipoStatusOS '.$response->rc);
					if($response->rc==0){
							return $response;
					}else{
							if($response->rc==-61 || $response->rc==-29){
									$this->session->sess_destroy();
									$codigoError = array('ERROR' => '-29' );
							}
							else{
									$codigoError = lang('ERROR_('.$response->rc.')');
									if(strpos($codigoError, 'Error')!==false){
											$codigoError = array('ERROR' => lang('ERROR_GENERICO_USER') );
									}else{
											$codigoError = array('ERROR' => lang('ERROR_('.$response->rc.')') );
									}
							}
							return $codigoError;

					}
			}else{
					return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER') );
			}
	}

	/**
	 * Método que busca información de ordenes de servicio para los criterios de búsqueda solicitados
	 *
	 * @param  string $urlCountry
	 * @param  string $fechaIni
	 * @param  string $fechaFin
	 * @param  string $status
	 * @return array
	 */
	private function callWSbuscarOS($urlCountry,$fechaIni, $fechaFin, $status){
			$this->lang->load('erroreseol');
			$idOperation = "buscarOrdenServicio";
			$className = "com.novo.objects.MO.ListadoOrdenServicioMO";

			$rifEmpresa = $this->session->userdata('acrifS');
			$accodciaS = $this->session->userdata('accodciaS');
			$idProducto = $this->session->userdata('idProductoS');

			$userName = $this->session->userdata('userName');
			$sessionId = $this->session->userdata('sessionId');
			$timeLog   = date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$canal = "ceo";
			$modulo = "buscarOS";
			$function = "buscarOS";

			$logAcceso = np_hoplite_log($sessionId,$userName,$canal,$modulo,$function,$idOperation,0,$ip,$timeLog);

			$token = $this->session->userdata('token');

			$data = array(
					'pais' => $urlCountry,
					'idOperation' => $idOperation,
					'className' => $className,
					'rifEmpresa' => $rifEmpresa,
					'acCodCia' => $accodciaS,
					'idProducto' => $idProducto,
					'fechaIni' => $fechaIni,
					'fechaFin' => $fechaFin,
					'status' => $status,
					'logAccesoObject' => $logAcceso,
					'token' => $token
			);

			$data = json_encode($data,JSON_UNESCAPED_UNICODE);

			$dataEncry = np_Hoplite_Encryption($data, 'callWSbuscarOS');
			$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
			$data = json_encode($data);
			$response = np_Hoplite_GetWS($data);
			$jsonResponse = np_Hoplite_Decrypt($response, 'callWSbuscarOS');

			$response = json_decode($jsonResponse);
			$prueba = json_encode($response);

			if($response){
					log_message('info', 'response BOS '.$response->rc);
					if($response->rc==0){
							return $response;
					}else{
							if($response->rc==-61 || $response->rc==-29){
									$this->session->sess_destroy();
									$codigoError = array('ERROR' => '-29' );
							}
							else{
									$codigoError = lang('ERROR_('.$response->rc.')');
									if(strpos($codigoError, 'Error')!==false){
											$codigoError = array('ERROR' => lang('ERROR_GENERICO_USER') );
									}else{
											$codigoError = array('ERROR' => lang('ERROR_('.$response->rc.')') );
									}
							}
							return $codigoError;
					}
			}else{
					log_message('info', 'response BOS NO WS');
					return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER') );
			}
	}
	/**
	 * Método para cambio de estatus de lote por tarjetas
	 * @param  string $urlCountry
	 *
	 */
	public function embozado($urlCountry){
		np_hoplite_countryCheck($urlCountry);
		$nlote = $this->input->post('nlote',true);
		$this->load->model('embozados_model');
		$dataResponse = $this->embozados_model->cambioStatus($nlote);
		$this->output->set_content_type('application/json')->set_output(json_encode($dataResponse));
	}
	/**
	 * Método para descargar en formato PDF la orden de servicio seleccionada
	 * @param  string $urlCountry
	 * @return bytes
	 */
	public function downloadOS($urlCountry){

			np_hoplite_countryCheck($urlCountry);

			$logged_in = $this->session->userdata('logged_in');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"TEBORS");

			$paisS = $this->session->userdata('pais');

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){
					$idOS = $this->input->post("data-idOS");
					$OS = $this->input->post("data-OS");

					$result = $this->callWSdownloadOS($urlCountry, $idOS, $OS);

			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}
	}

	/**
	 * Método para descargar en formato PDF la Facturacion de la orden de servicio seleccionada
	 * @param  string $urlCountry
	 * @return bytes
	 */

	public function downloadFacturacionOS($urlCountry){

			np_hoplite_countryCheck($urlCountry);

			$logged_in = $this->session->userdata('logged_in');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"TEBORS");

			$paisS = $this->session->userdata('pais');

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){
					$idOS = $this->input->post("data-idOS");
					$OS = $this->input->post("data-OS");

					$result = $this->callWSdownloadFacturaOS($urlCountry, $idOS, $OS);

			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}
	}


	/**
	 * Método que realiza petición al WS para descargar en formato PDF la orden de servicio seleccionada
	 * @param  string $urlCountry
	 * @param  string $idOS
	 * @return type
	 */
	private function callWSdownloadOS($urlCountry, $idOS, $OS){
			$this->lang->load('erroreseol');
			$token = $this->session->userdata('token');
			$username = $this->session->userdata('userName');
			$acrifS = $this->session->userdata('acrifS');
			$accodciaS = $this->session->userdata('accodciaS');
			$idProductoS = $this->session->userdata('idProductoS');

			$usuario = array( 'userName' => $username );

			$this->lang->load('erroreseol');
			$operacion = "visualizarOS";
			$classname = "com.novo.objects.TOs.OrdenServicioTO";
			$timeLog= date("m/d/Y H:i");
			$ip= $this->input->ip_address();

			$sessionId = $this->session->userdata('sessionId');
			$canal = "ceo";
			$modulo = 'descargarPDFOS';
			$funcion = 'descargarPDFOS';
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$funcion,$operacion,0,$ip,$timeLog);

			$data = array(
					"pais" => $urlCountry,
					"idOperation" => $operacion,
					"className"=> $classname,
					"rifEmpresa"=>$acrifS,
					"acCodCia" => $accodciaS,
					"acprefix" => $idProductoS,
					"idOrden"=>$idOS,
					"usuario"=> $usuario,
					"logAccesoObject"=> $logAcceso,
					"token"=> $token
			);

			$data = json_encode($data,JSON_UNESCAPED_UNICODE);
			$dataEncry = np_Hoplite_Encryption($data, 'callWSdownloadOS');
			$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
			$data = json_encode($data);
			$response = np_Hoplite_GetWS($data);
			$jsonResponse = np_Hoplite_Decrypt($response, 'callWSdownloadOS');

			$response = json_decode($jsonResponse);

			log_message("INFO",'Respuesta: '.json_encode($response));
			if($response){
					log_message("INFO",'PDF OS '.$response->rc.'/'.$response->msg);

					if($response->rc==0){
							np_hoplite_byteArrayToFile($response->archivo,"pdf",
										str_replace(' ', '_', 'OrdenServicio'.date("d/m/Y H:i")));
					}else{

							if($response->rc==-61 || $response->rc==-29){
									$this->session->sess_destroy();
									echo "<script>alert('usuario actualmente desconectado');
									location.href='".$this->config->item('base_url')."$urlCountry/login';</script>";
							}else{
									$codigoError = lang('ERROR_('.$response->rc.')');
									if(strpos($codigoError, 'Error')!==false){
											$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
									}else{
											$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
									}
									$ceo_name = $this->security->get_csrf_token_name();
									$ceo_cook = $this->security->get_csrf_hash();
									echo "<form id='formu' method='post'><input type='hidden' name='data-OS' value='$OS'>
																												<input type='hidden' name='$ceo_name' value='$ceo_cook'></form>

									<script>
									alert('".$codigoError["mensaje"]."');
									document.getElementById('formu').action='".$this->config->item('base_url')."$urlCountry/consulta/ordenes-de-servicio';
									document.getElementById('formu').submit();
									</script>";
							}
					}
			}else{
					$ceo_name = $this->security->get_csrf_token_name();
					$ceo_cook = $this->security->get_csrf_hash();
					echo "<form id='formu' method='post'><input type='hidden' name='data-OS' value='$OS'>
																							 <input type='hidden' name='$ceo_name' value='$ceo_cook'></form>
					<script>
					alert('Error al descargar archivo.');
					document.getElementById('formu').action='".$this->config->item('base_url')."$urlCountry/consulta/ordenes-de-servicio';
					document.getElementById('formu').submit();
					</script>";
			}
	}

	/**
	 * Método que realiza petición al WS para descargar en formato PDF la factura de la orden de servicio seleccionada
	 * @param  string $urlCountry
	 * @param  string $idOS
	 * @return type
	 */
	private function callWSdownloadFacturaOS($urlCountry, $idOS, $OS){
			$this->lang->load('erroreseol');

			$username = $this->session->userdata('userName');

			$usuario = array( 'userName' => $username );

			$this->lang->load('erroreseol');
			$operacion = "Buscar Plantilla Empresas";

			$timeLog= date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$token = $this->session->userdata('token');
			$sessionId = $this->session->userdata('sessionId');
			$canal = "ceo";
			$modulo = 'Reprocesar Lotes';
			$funcion = 'Reprocesar Guarderia';
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$funcion,$operacion,0,$ip,$timeLog);

			$data = array(
					"idOperation" => "descargarFactura",
					"className"=> "com.novo.objects.TOs.FacturaTO",
					"idOrdenS"=>$idOS,
					"dFecha" => $timeLog,
					"logAccesoObject"=> $logAcceso,
					"token"=> $token,
					"pais"=> $urlCountry
			);
			$data = json_encode($data,JSON_UNESCAPED_UNICODE);
			//log_message('info', 'request callWSdownloadFacturaOS '.$data);
			$dataEncry = np_Hoplite_Encryption($data, 'callWSdownloadFacturaOS');
			$data = json_encode(array('bean' => $dataEncry, 'pais' =>$urlCountry ));
			$response = np_Hoplite_GetWS($data);
			$data1 = json_encode($response);
			//log_message("INFO",'Respuesta del response callWSdownloadFacturaOS===>>>>> '.$data1);
			$jsonResponse = np_Hoplite_Decrypt($response, 'callWSdownloadFacturaOS');
			$response = json_decode($jsonResponse);

			//log_message("INFO",'Respuesta del response ===>>>>>>>>>>>  '.$jsonResponse);

			if($response){

					log_message("INFO",'PDF OS===============>>>>>>>> '.$response->rc.'/'.$response->msg);
					if($response->rc==0){

							$nombre = explode(".", $response->nombre);
							$nombre = $nombre[0];

							np_hoplite_byteArrayToFile($response->archivo,"pdf",
								str_replace(' ', '_', $nombre.date("d/m/Y H:i")));

					} elseif ($response->rc==-109) {
							$ceo_name = $this->security->get_csrf_token_name();
							$ceo_cook = $this->security->get_csrf_hash();
							echo "<form id='formu' method='post'><input type='hidden' name='data-OS' value='$OS'>
																									 <input type='hidden' name='$ceo_name' value='$ceo_cook'></form>
							<script>
									alert('La factura aún no está digitalizada.');
									document.getElementById('formu').action='".$this->config->item('base_url')."$urlCountry/consulta/ordenes-de-servicio';
									document.getElementById('formu').submit();
							</script>";
					} else{
							$ceo_name = $this->security->get_csrf_token_name();
							$ceo_cook = $this->security->get_csrf_hash();
							echo "<form id='formu' method='post'><input type='hidden' name='data-OS' value='$OS'>
							<input type='hidden' name='$ceo_name' value='$ceo_cook'></form>
							<script>
									alert('En éstos momentos no podemos atender tu solicitud por favor intenta más tarde.');
									document.getElementById('formu').action='".$this->config->item('base_url')."$urlCountry/consulta/ordenes-de-servicio';
									document.getElementById('formu').submit();
							</script>";
					}
			}else{
					$ceo_name = $this->security->get_csrf_token_name();
					$ceo_cook = $this->security->get_csrf_hash();
					echo "<form id='formu' method='post'><input type='hidden' name='data-OS' value='$OS'>
					<input type='hidden' name='$ceo_name' value='$ceo_cook'></form>
					<script>
					alert('En éstos momentos no podemos atender tu solicitud por favor intenta más tarde.');
					document.getElementById('formu').action='".$this->config->item('base_url')."$urlCountry/consulta/ordenes-de-servicio';
					document.getElementById('formu').submit();
					</script>";
			}
	}


	/**
	 * Método para anular una orden de servicio determinada.
	 * @param  string $urlCountry
	 * @return json
	 */
	public function anularOS($urlCountry){
			np_hoplite_countryCheck($urlCountry);

			$logged_in = $this->session->userdata('logged_in');
			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"TEBORS");

			$paisS = $this->session->userdata('pais');

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){
					$dataRequest = json_decode(
						$this->security->xss_clean(
							strip_tags(
								$this->cryptography->decrypt(
									base64_decode($this->input->get_post('plot')),
									utf8_encode($this->input->get_post('request'))
								)
							)
						)
					);
					$idOS = $dataRequest->data_idOS;
					$pass = $dataRequest->data_pass;

					$result = $this->callWSanularOS($urlCountry, $idOS, $pass);
					$response = $this->cryptography->encrypt($result);
					$this->output->set_content_type('application/json')->set_output(json_encode($response));

			}else{
					redirect($urlCountry.'/login');
			}
	}

	/**
	 * Método que realiza petición al WS para anular una orden de servicio determinada.
	 * @param  string $urlCountry
	 * @return array
	 */
	private function callWSanularOS($urlCountry, $idOS, $pass){
			$this->lang->load('erroreseol');
			$idOperation = "desconciliarOS";
			$className = "com.novo.objects.TOs.OrdenServicioTO";

			$rifEmpresa = $this->session->userdata('acrifS');

			$userName = $this->session->userdata('userName');
			$sessionId = $this->session->userdata('sessionId');
			$timeLog   = date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$canal = "ceo";
			$modulo = "anularOS";
			$function = "anularOS";

			$logAcceso = np_hoplite_log($sessionId,$userName,$canal,$modulo,$function,$idOperation,0,$ip,$timeLog);

			$token = $this->session->userdata('token');

			$user = array(
					"userName"=> $userName,
					"password"=> $pass
			);

			$data = array(
					'pais' => $urlCountry,
					'idOperation' => $idOperation,
					'className' => $className,
					'idOrden' => $idOS,
					"rifEmpresa" => $rifEmpresa,
					'usuario' => $user,
					'logAccesoObject' => $logAcceso,
					'token' => $token
			);

			$data = json_encode($data,JSON_UNESCAPED_UNICODE);

			$dataEncry = np_Hoplite_Encryption($data, 'callWSanularOS');
			$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
			$data = json_encode($data);
			$response = np_Hoplite_GetWS($data);
			$jsonResponse = np_Hoplite_Decrypt($response, 'callWSanularOS');

			$response = json_decode($jsonResponse);

			if($response){
					log_message('info', 'response anularOS '.$response->rc.'/'.$response->msg);
					if($response->rc==0){
							return $response;
					}else{
							if($response->rc==-61 || $response->rc==-29){
									$this->session->sess_destroy();
									$codigoError = array('ERROR' => '-29');
							}else{
									$codigoError = lang('ERROR_('.$response->rc.')');
									if(strpos($codigoError, 'Error')!==false){
											$codigoError = array('ERROR' => lang('ERROR_GENERICO_USER') );
									}else{
											$codigoError = array('ERROR' => lang('ERROR_('.$response->rc.')') );
									}
							}
							return $codigoError;
					}
			}else{
					log_message('info', 'response anularOS NO WS');
					return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER') );
			}

	}

	/**
	 * Método para generar la factura de la orden de servicio seleccionada
	 * @param  [string] $urlCountry [description]
	 */
	public function facturar($urlCountry){

			np_hoplite_countryCheck($urlCountry);

			$logged_in = $this->session->userdata('logged_in');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"TEBORS");

			$paisS = $this->session->userdata('pais');

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){
					$idOS = $this->input->post("data-idOS");
					$OS = $this->input->post("data-OS");
					$result = $this->callWSfacturar($urlCountry, $idOS, $OS);

			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}
	}

	/**
	 * Método que realiza petición al WS para generar la factura de la orden de servicio seleccionada
	 * @param  string $urlCountry
	 * @param  string $idOS
	 * @return type
	 */
	private function callWSfacturar($urlCountry, $idOS, $OS){
			$this->lang->load('erroreseol');
			$token = $this->session->userdata('token');
			$username = $this->session->userdata('userName');

			$this->lang->load('erroreseol');
			$operacion = "descargarFactura";
			$classname = "com.novo.objects.TOs.FacturaTO";
			$timeLog= date("m/d/Y H:i");
			$ip= $this->input->ip_address();

			$sessionId = $this->session->userdata('sessionId');
			$canal = "ceo";
			$modulo = 'descargarPDFOS';
			$funcion = 'descargarPDFOS';
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$funcion,$operacion,0,$ip,$timeLog);

			$data = array(
					"idOperation" => $operacion,
					"className"=> $classname,
					"idOrdenS"=>$idOS,
					"dFecha" => $timeLog,
					"logAccesoObject"=> $logAcceso,
					"token"=> $token,
					"pais" => $urlCountry
			);

			$data = json_encode($data,JSON_UNESCAPED_UNICODE);

			$dataEncry = np_Hoplite_Encryption($data, 'callWSfacturar');
			$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
			$data = json_encode($data);
			$response = np_Hoplite_GetWS($data);
			$jsonResponse = np_Hoplite_Decrypt($response, 'callWSfacturar');

			$response = json_decode($jsonResponse);

			if($response){
					log_message("INFO",'FACTURA OS '.$response->rc/*.'/'.$response->msg*/);

					if($response->rc==0){

							np_hoplite_byteArrayToFile($response->archivo,"pdf",
								str_replace(' ', '_', "FacturaOS".date("d/m/Y H:i")));

					}else{

							if($response->rc==-61 || $response->rc==-29){
									$this->session->sess_destroy();
									echo "<script>alert('usuario actualmente desconectado');
									location.href='".$this->config->item('base_url')."$urlCountry/login';</script>";
							}else{
									$codigoError = lang('ERROR_('.$response->rc.')');
									if(strpos($codigoError, 'Error')!==false){
											$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
									}else{
											$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
									}
									$ceo_name = $this->security->get_csrf_token_name();
									$ceo_cook = $this->security->get_csrf_hash();
									echo "<form id='formu' method='post' ><input type='hidden' name='data-OS' value='$OS'>
									<input type='hidden' name='$ceo_name' value='$ceo_cook'></form>
									<script>
									alert('".$codigoError["mensaje"]."');
									document.getElementById('formu').action='".$this->config->item('base_url')."$urlCountry/consulta/ordenes-de-servicio';
									document.getElementById('formu').submit();
									</script>";
							}
					}
			}else{
					$ceo_name = $this->security->get_csrf_token_name();
					$ceo_cook = $this->security->get_csrf_hash();
					echo "<form id='formu' method='post'><input type='hidden' name='data-OS' value='$OS'>
					<input type='hidden' name='$ceo_name' value='$ceo_cook'></form>
					<script>
					alert('Error al descargar archivo.');
					document.getElementById('formu').action='".$this->config->item('base_url')."$urlCountry/consulta/ordenes-de-servicio';
					document.getElementById('formu').submit();
					</script>";
			}

	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//solicitud de envío de token de seguridad
	public function PagoOS($urlCountry){

			np_hoplite_countryCheck($urlCountry);

			$this->lang->load('erroreseol');

			$logged_in = $this->session->userdata('logged_in');
			$paisS = $this->session->userdata('pais');

			if($paisS==$urlCountry && $logged_in){
					$menuP =$this->session->userdata('menuArrayPorProducto');
					$funcAct = in_array("tebpgo", np_hoplite_modFunciones($menuP));

					if($funcAct){
							$result = $this->callWsPagoOS($urlCountry);
					}else{
							$result = ["ERROR"=>lang('SIN_FUNCION')];
					}
					$response = $this->cryptography->encrypt($result);
					$this->output->set_content_type('application/json')->set_output(json_encode($response));

			}elseif($paisS!=$urlCountry && $paisS!=''){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}elseif($this->input->is_ajax_request()){
					$response = $this->cryptography->encrypt(array('ERROR' => '-29' ));
					$this->output->set_content_type('application/json')->set_output(json_encode($response));
			}else{
					redirect($urlCountry.'/login');
			}
	}

	//solicitud de envío de token de seguridad
	private function callWsPagoOS($urlCountry){

			$this->lang->load('erroreseol');
			$this->lang->load('consultas');
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
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$logOperation,$RC,$ip,$timeLog);

			$data = array(
					"idOperation" => $operation,
					"token"=>$token,
					"className" => $className,
					"logAccesoObject"=>$logAcceso,
					"pais" => $urlCountry
			);

			$data = json_encode($data, JSON_UNESCAPED_UNICODE);
			log_message("info","DATA array before encrypt  " . $data );
			$dataEncry = np_Hoplite_Encryption($data, 'callWsPagoOS');
			$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );

			$data = json_encode($data);
			$response = np_Hoplite_GetWS($data);
			$jsonResponse = np_Hoplite_Decrypt($response, 'callWsPagoOS');
			$response =  json_decode($jsonResponse);

			//simula respuesta de WS
			/*sleep(2);
			$data = '{"rc":0,"msg":" ", "bean":""}';
			$response = json_decode($data);*/

			if ($response) {
					$rc = $response->rc;
					switch ($rc) {
							case 0:
									$bean = array(
											'bean' => $response->bean
									);
									$this->session->set_userdata($bean);
									$response = [
											'code' => 0,
											'title' => lang('PAG_OS_TITLE'),
											'msg' => lang('PAG_OS_ENV_OK')
									];
									break;
							case -61:
							case -29:
									$response = [
											'code' => 2,
											'title' => lang('PAG_OS_TITLE'),
											'msg' => lang('ERROR_(-29)')
									];
									break;
							default:
									$response = [
											'code' => 1,
											'title' => lang('PAG_OS_TITLE'),
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



	public function PagoOSProcede($urlCountry){

			np_hoplite_countryCheck($urlCountry);

			$dataRequest = json_decode(
				$this->security->xss_clean(
					strip_tags(
						$this->cryptography->decrypt(
							base64_decode($this->input->get_post('plot')),
							utf8_encode($this->input->get_post('request'))
						)
					)
				)
			);
			$idOS = $dataRequest->idOS;
			$codeToken = $dataRequest->codeToken;
			$totalamount = $dataRequest->totalamount;
			$factura = $dataRequest->factura;

			$this->lang->load('erroreseol');

			$logged_in = $this->session->userdata('logged_in');
			$paisS = $this->session->userdata('pais');

			if($paisS==$urlCountry && $logged_in){
					$menuP =$this->session->userdata('menuArrayPorProducto');
					$funcAct = in_array("tebpgo", np_hoplite_modFunciones($menuP));

					if($funcAct){
							$result = $this->callWsPagoOSProcede($urlCountry, $idOS, $codeToken, $totalamount, $factura);
					}else{
							$result = array("ERROR"=>lang('SIN_FUNCION'));
					}
					$response = $this->cryptography->encrypt($result);
					$this->output->set_content_type('application/json')->set_output(json_encode($response));

			}elseif($paisS!=$urlCountry && $paisS!=''){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}elseif($this->input->is_ajax_request()){
					$response = $this->cryptography->encrypt(array('ERROR' => '-29' ));
					$this->output->set_content_type('application/json')->set_output(json_encode($response));
			}else{
					redirect($urlCountry.'/login');
			}
	}


	private function callWsPagoOSProcede($urlCountry, $idOS, $codeToken, $totalamount, $factura){  /// recarga transferencia maestra

			$this->lang->load('erroreseol');
			$this->lang->load('consultas');
			$paisS = $this->session->userdata('pais');
			$canal = "ceo";
			$modulo="Pagos";
			$function="Pagar Orden de servicio";
			$operation="pagarOS";
			$logOperation="Realizar Pago";
			$RC=0;
			$className="com.novo.objects.TOs.OrdenServicioTO";
			$timeLog= date("m/d/Y H:i");
			$ip= $this->input->ip_address();

			$idEmpresa = $this->session->userdata('acrifS');
			$sessionId = $this->session->userdata('sessionId');
			$username = $this->session->userdata('userName');
			$token = $this->session->userdata('token');
			$logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $logOperation, $RC, $ip, $timeLog);

			$idProductoS = $this->session->userdata('idProductoS');
			$bean = $this->session->userdata('bean');

			$data = array(
					"pais" => $paisS,
					"idOperation" => $operation,
					"className" => $className,
					"rifEmpresa"=> $idEmpresa,
					"idOrden"=> $idOS,
					"idProducto"=> $idProductoS,
					"acUsuario"=> $username,
					"nofactura"=> $factura,
					"montoTotal"=> $totalamount,
					"tokenCliente"=> $codeToken,
					"authToken"=> $bean,
					"logAccesoObject"=> $logAcceso,
					"token"=> $token
			);

			$data = json_encode($data,JSON_UNESCAPED_UNICODE);
			log_message("info","Request botón de pago  " . $data );
			$dataEncry = np_Hoplite_Encryption($data, 'callWsPagoOSProcede');
			$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
			//log_message("info","DATA array after encrypt  " . $data );
			$data = json_encode($data);
			$response = np_Hoplite_GetWS($data);
			$jsonResponse = np_Hoplite_Decrypt($response, 'callWsPagoOSProcede');
			$response = json_decode($jsonResponse);

			log_message("info","RESPONSE Pagar Orden de servicio ------------------->>>>      " . json_encode($response));

			//simula respuesta de WS
			/*sleep(2);
			$data = '{"rc":0,"msg":"Mensaje Banco", "bean":"KJHGB"}';
			log_message("info","RESPONSE Pagar Orden de servicio ------------------->>>>      " . $data);
			$response = json_decode($data);*/

			if ($response) {
					$rc = $response->rc;
					$codeError =[-21, -155, -241, -281, -285, -286, -287, -296, -297, -298, -299,];
					$errorMsg = (in_array($rc, $codeError)) ?  lang('ERROR_('.$response->rc.')') : lang('ERROR_(-230)');
					$errorMsg = ($rc == -300) ? $response->msg : $errorMsg;

					switch ($rc) {
							case 0:
									$response = [
											'code' => 0,
											'title' => lang('PAG_OS_TITLE'),
											'msg' => lang('PAG_OS_OK')
									];
									break;
							case -61:
							case -29:
									$response = [
											'code' => 2,
											'title' => lang('PAG_OS_TITLE'),
											'msg' => lang('ERROR_(-29)')
									];
									break;
							default:
									$errorReg = ($rc == -296) ? 1 : 0;
									$response = [
											'code' => 1,
											'title' => lang('PAG_OS_TITLE'),
											'msg' => $errorMsg,
											'errorReg' => $errorReg
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
