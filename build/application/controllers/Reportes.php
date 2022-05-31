<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
* Clase Reportes
*
* Esta clase realiza las operaciónes concernientes a todos los reportes en CEO.
*
* @package    controllers
* @author     Wilmer Rojas <rojaswilmer@gmail.com>
* @author     Carla García <neiryerit@gmail.com>
*/
class Reportes extends CI_Controller {

	public function __construct()
	{
		parent:: __construct();
		$this->load->helper('security');
	}
	/**
	 * Pantalla para el reporte de cuenta concentradora.
	 *
	 * @param  string $urlCountry
	 */
	public function CuentaConcentradora($urlCountry)
	{
			//VALIDATE COUNTRY
			np_hoplite_countryCheck($urlCountry);

			$this->lang->load('reportes');
			$this->lang->load('dashboard');
			$this->lang->load('users');
			$this->load->library('parser');
			$this->lang->load('erroreseol');
			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');

			$menuP =$this->session->userdata('menuArrayPorProducto');

			$moduloAct = np_hoplite_existeLink($menuP,"REPCON");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){

					$nombreCompleto = $this->session->userdata('nombreCompleto');
					$lastSessionD = $this->session->userdata('lastSession');
					$jsRte = '../../../js/';
					$thirdsJsRte = '../../../js/third_party/';
					$FooterCustomInsertJS=["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js","aes.min.js","aes-json-format.min.js","reportes/cuentaconcentradora.js","jquery.paginate.js","header.js","jquery.balloon.min.js","highcharts.js","exporting.js","routes.js",$thirdsJsRte."jquery.validate.min.js",$jsRte."validate-forms.js",$thirdsJsRte."additional-methods.min.js"];
					$FooterCustomJS="";
					$titlePage="Conexión Empresas Online - Reportes";

					$menuHeader = $this->parser->parse('widgets/widget-menuHeader',array(),TRUE);

					$menuFooter = $this->parser->parse('widgets/widget-menuFooter',array(),TRUE);
					$header = $this->parser->parse('layouts/layout-header',array('bodyclass'=>'','menuHeaderActive'=>TRUE,'menuHeaderMainActive'=>TRUE,'menuHeader'=>$menuHeader,'titlePage'=>$titlePage),TRUE);
					$footer = $this->parser->parse('layouts/layout-footer',array('menuFooterActive'=>TRUE,'menuFooter'=>$menuFooter,'FooterCustomInsertJSActive'=>TRUE,'FooterCustomInsertJS'=>$FooterCustomInsertJS,'FooterCustomJSActive'=>TRUE,'FooterCustomJS'=>$FooterCustomJS),TRUE);
					$content = $this->parser->parse('reportes/content-cuenta-concentradora',array(
							'titulo'=>$nombreCompleto,
							'breadcrum'=>'',
							'lastSession'=>$lastSessionD,
							),TRUE);
					$sidebarLotes= ($urlCountry == 'Ec-bp') ? '' :  $this->parser->parse('widgets/widget-publi-4',array('sidebarActive'=>TRUE),TRUE);

					$datos = array(
							'header'       =>$header,
							'content'      =>$content,
							'footer'       =>$footer,
							'sidebar'      =>$sidebarLotes
							);

					$this->parser->parse('layouts/layout-b', $datos);

			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}
	}

	/**
	 * Método para buscar el reporte de cuenta concentradora para ciertos parámetros de búsqueda.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	public function getCuentaConcentradora($urlCountry){

			np_hoplite_countryCheck($urlCountry);

			$logged_in = $this->session->userdata('logged_in');
			$paisS = $this->session->userdata('pais');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPCON");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){
					//Validate Request For Ajax
					if($this->input->is_ajax_request()){
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

							$_POST['paginaActual'] = $dataRequest->paginaActual;
							$_POST['empresa'] = $dataRequest->empresa;
							$_POST['fechaInicial'] = $dataRequest->fechaInicial;
							$_POST['fechaFin'] = $dataRequest->fechaFin;

							$this->form_validation->set_rules('empresa', 'Empresa', 'trim|xss_clean|required');
							$this->form_validation->set_rules('fechaInicial', 'Fecha Inicio', 'trim|xss_clean|regex_match[/^[0-9\/]+$/]');
							$this->form_validation->set_rules('fechaFin', 'Fecha Fin', 'trim|xss_clean|regex_match[/^[0-9\/]+$/]');
							$this->form_validation->set_rules('paginaActual', 'paginaActual', 'trim|xss_clean|required');

							$this->form_validation->set_error_delimiters('', '---');
							if ($this->form_validation->run() == FALSE)
							{
								$responseError = 'La combinacion de caracteres es invalido';
								$responseError = $this->cryptography->encrypt($responseError);
								$this->output->set_content_type('application/json')->set_output(json_encode($responseError));
								return $responseError;
								log_message('DEBUG', 'NOVO VALIDATION ERRORS: '.json_encode(validation_errors()));
								//echo "FORM NO VALIDO";
							}
							else
							{
								$paginaActual= $dataRequest->paginaActual;
								$empresa= $dataRequest->empresa;
								$fechaInicial= $dataRequest->fechaInicial;
								$fechaFin = $dataRequest->fechaFin;
								$filtroFecha = $dataRequest->filtroFecha;
								$tipoNota= $dataRequest->tipoNota;
								$username = $this->session->userdata('userName');
								$token = $this->session->userdata('token');
								unset($_POST['paginaActual'], $_POST['empresa'], $_POST['fechaInicial'], $_POST['fechaFin']);
								$pruebaTabla = $this->callWSCuentaConcentradora($urlCountry,$token,$username,$empresa,$fechaInicial,$fechaFin,$paginaActual,$filtroFecha,$tipoNota);
								$pruebaTabla = $this->cryptography->encrypt($pruebaTabla);
								$this->output->set_content_type('application/json')->set_output(json_encode($pruebaTabla));
							}
					}
			}else{
					$this->session->sess_destroy();
					$responseError = ['ERROR' => lang('ERROR_(-29)'), "rc"=> "-29"];
					$responseError = $this->cryptography->encrypt($responseError);
					$this->output->set_content_type('application/json')->set_output(json_encode($responseError));
			}
	}

		/**
	 * Método que realiza petición al WS para obtener la información solicitada en cuenta concentradora.
	 *
	 * @param  string $urlCountry
	 * @param  string $token,$username,$empresa,$fechaInicial,$fechaFin,$paginaActual,$filtroFecha,$tipoNota
	 * @return JSON
	 */
	private function callWSCuentaConcentradora($urlCountry,$token,$username,$empresa,$fechaInicial,$fechaFin,$paginaActual,$filtroFecha,$tipoNota){

			$this->lang->load('erroreseol');

			$canal = "ceo";
			$modulo    ="reportes";
			$function  ="buscarDepositoGarantia";
			$operation ="buscarDepositoGarantia";
			$className ="com.novo.objects.MO.DepositosGarantiaMO";
			$timeLog   = date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

			$data = array(
					"pais"=>$urlCountry,
					"idOperation" => $operation,
					"className" => $className,
					"idExtEmp"=> $empresa,
					"fechaIni" => $fechaInicial,
					"fechaFin" => $fechaFin,
					"tipoNota" => $tipoNota,
					"filtroFecha" => $filtroFecha,
					"tamanoPagina"=> "10",
					"paginaActual"=> $paginaActual,
					"logAccesoObject"=>$logAcceso,
					"token"=>$token
					);

			$data = json_encode($data,JSON_UNESCAPED_UNICODE);

			$dataEncry = np_Hoplite_Encryption($data, 'callWSCuentaConcentradora');
			$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
			$data = json_encode($data);
			$response = np_Hoplite_GetWS($data);
			$jsonResponse = np_Hoplite_Decrypt($response, 'callWSCuentaConcentradora');

			$response =  json_decode($jsonResponse);

			if($response){
					log_message('info','CuentaConcentradora '.$response->rc."/".$response->msg);
					if($response->rc==0){
							return $response;
					}else{

							if($response->rc==-61 || $response->rc==-29){
									$codigoError = array('mensaje' => lang('ERROR_(-29)'), "rc"=> "-29");
									$this->session->sess_destroy();
									return $codigoError;

							}else{
									$codigoError = lang('ERROR_('.$response->rc.')');
									if(strpos($codigoError, 'Error')!==false){
											$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
									}else{
											$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
									}

									return $codigoError;

							}

					}

			}else{
					log_message('info','CuentaConcentradora NO WS');
					return $codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'));
			}
	}

		/**
	 * Método para exportar en formato Excel los datos visualizados en el reporte de cuenta concentradora.
	 *
	 * @param  string $urlCountry
	 * @return bytes
	 */
	public function expCuentaConcentradoraXLS($urlCountry){

			np_hoplite_countryCheck($urlCountry);

			$this->lang->load('erroreseol');//HOJA DE ERRORES;

			$canal = "ceo";
			$modulo    ="reportes";
			$function  ="generarDepositoGarantia";
			$operation ="generarDepositoGarantia";
			$className ="com.novo.objects.MO.DepositosGarantiaMO";
			$timeLog   = date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$username = $this->session->userdata('userName');
			$token = $this->session->userdata('token');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPCON");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){
					$paginaActual=$this->input->post('paginaActual');
					$empresa = $this->input->post('empresa');
					$fechaInicial = $this->input->post('fechaInicial');
					$fechaFin = $this->input->post('fechaFin');
					$filtroFecha = $this->input->post('filtroFecha');

					$nomEmpresa = $this->input->post('nomEmpresa');
					$descProd = $this->input->post('descProd');

					$data = array(
							"pais"=>$urlCountry,
							"idOperation" => $operation,
							"className" => $className,
							"idExtEmp"=> $empresa,
							"fechaIni" => $fechaInicial,
							"fechaFin" => $fechaFin,
							"filtroFecha" => $filtroFecha,
							"nombreEmpresa" => $nomEmpresa,
							"producto" => $this->session->userdata('idProductoS'),
							"tamanoPagina"=> "10",
							"paginaActual"=> "1",
							"logAccesoObject"=>$logAcceso,
							"token"=>$token
							);

					$data = json_encode($data,JSON_UNESCAPED_UNICODE);

					$dataEncry = np_Hoplite_Encryption($data, 'expCuentaConcentradoraXLS');
					$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
					$data = json_encode($data);
					$response = np_Hoplite_GetWS($data);
					$jsonResponse = np_Hoplite_Decrypt($response, 'expCuentaConcentradoraXLS');

					$response =  json_decode($jsonResponse);

					if($response){
							log_message('info','depositosdegarantias XLS '.$response->rc."/".$response->msg);

							if($response->rc==0){
									np_hoplite_byteArrayToFile($response->archivo,"xls","CuentaConcentradora");

							}else{

									if($response->rc==-61 || $response->rc==-29){
											$this->session->sess_destroy();
											echo "<script>alert('usuario actualmente desconectado');
											window.history.back(-1);</script>";

									}else{
											$codigoError = lang('ERROR_('.$response->rc.')');
											if(strpos($codigoError, 'Error')!==false){
													$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
											}else{
													$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
											}
											$ceo_name = $this->security->get_csrf_token_name();
											$ceo_cook = $this->security->get_csrf_hash();
												echo "<form id='formu' method='post'>
															<input type='hidden'>
															<input type='hidden' name='$ceo_name' value='$ceo_cook'>
															</form>
													<script> alert('La descarga del archivo presenta inconvenientes en éstos momentos.');
													document.getElementById('formu').action='".$this->config->item('base_url')."$urlCountry/reportes/cuenta-concentradora';
															document.getElementById('formu').submit();
													</script>";
											// return $codigoError;
									}
							}

					}else{
					log_message('info','depositosdegarantias XLS NO WS');
					$ceo_name = $this->security->get_csrf_token_name();
					$ceo_cook = $this->security->get_csrf_hash();
					echo "<form id='formu' method='post' >
									<input type='hidden'>
									<input type='hidden' name='$ceo_name' value='$ceo_cook'>
								</form>
								<script> alert('La descarga del archivo presenta inconvenientes en éstos momentos.');          document.getElementById('formu').action='".$this->config->item('base_url')."$urlCountry/reportes/cuenta-concentradora';
										document.getElementById('formu').submit();
								</script>";
					}

			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}


	}

		/**
	 * Método para exportar en formato PDF los datos visualizados en el reporte de cuenta concentradora.
	 *
	 * @param  string $urlCountry
	 * @return bytes
	 */
	public function expCuentaConcentradoraPDF($urlCountry){
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo    ="reportes";
			$function  ="generarDepositoGarantiaPdf";
			$operation ="generarDepositoGarantiaPdf";
			$className ="com.novo.objects.MO.DepositosGarantiaMO";
			$timeLog   = date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$username = $this->session->userdata('userName');
			$token = $this->session->userdata('token');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPCON");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){
					$paginaActual=$this->input->post('paginaActual');
					$empresa = $this->input->post('empresa');
					$fechaInicial = $this->input->post('fechaInicial');
					$fechaFin = $this->input->post('fechaFin');
					$filtroFecha = $this->input->post('filtroFecha');

					$nomEmpresa = $this->input->post('nomEmpresa');


					$data = array(
							"pais"=>$urlCountry,
							"idOperation" => $operation,
							"className" => $className,
							"idExtEmp"=> $empresa,
							"fechaIni" => $fechaInicial,
							"fechaFin" => $fechaFin,
							"filtroFecha" => $filtroFecha,
							"nombreEmpresa" => $nomEmpresa,
							"producto" => $this->session->userdata('idProductoS'),
							"tamanoPagina"=> "10",
							"paginaActual"=> "1",
							"logAccesoObject"=>$logAcceso,
							"token"=>$token
							);

					$data = json_encode($data,JSON_UNESCAPED_UNICODE);

					$dataEncry = np_Hoplite_Encryption($data, 'expCuentaConcentradoraPDF');
					$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
					$data = json_encode($data);
					$response = np_Hoplite_GetWS($data);
					$jsonResponse = np_Hoplite_Decrypt($response, 'expCuentaConcentradoraPDF');

					$response =  json_decode($jsonResponse);

					if($response){
							log_message('info','depositosdegarantias PDF '.$response->rc."/".$response->msg);
							if($response->rc==0){
									np_hoplite_byteArrayToFile($response->archivo,"pdf","CuentaConcentradora");
							}else{

									if($response->rc==-61 || $response->rc==-29){
											$this->session->sess_destroy();
											echo "<script>alert('Usuario actualmente desconectado');
											window.history.back(-1);</script>";

									}else{
											$codigoError = lang('ERROR_('.$response->rc.')');
											if(strpos($codigoError, 'Error')!==false){
													$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
											}else{
													$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
											}
											$ceo_name = $this->security->get_csrf_token_name();
											$ceo_cook = $this->security->get_csrf_hash();
											echo "<form id='formu' method='post' >
														<input type='hidden'>
														<input type='hidden' name='$ceo_name' value='$ceo_cook'>
														</form>
									<script>
									alert('".$codigoError["mensaje"]."');
									document.getElementById('formu').action='".$this->config->item('base_url')."$urlCountry/reportes/cuenta-concentradora';
									document.getElementById('formu').submit();
									</script>";
											return $codigoError;
									}
							}

					}else{
							log_message('info','depositosdegarantias PDF NO WS ');
							$ceo_name = $this->security->get_csrf_token_name();
							$ceo_cook = $this->security->get_csrf_hash();
							echo "<form id='formu' method='post' >
														<input type='hidden'>
														<input type='hidden' name='$ceo_name' value='$ceo_cook'>
														</form>
									<script>
									alert('".$codigoError["mensaje"]."');
									document.getElementById('formu').action='".$this->config->item('base_url')."$urlCountry/reportes/cuenta-concentradora';
									document.getElementById('formu').submit();
									</script>";
											return $codigoError;
					}

			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}


	}

		/**
	 * Método para obtener la información de gráfico del reporte cuenta concentradora.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	public function graficoCuentaConcentradora($urlCountry){
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo    ="reportes";
			$function  ="generarDepositoGarantiaGrafico";
			$operation ="generarDepositoGarantiaGrafico";
			$className ="com.novo.objects.MO.DepositosGarantiaMO";
			$timeLog   = date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$username = $this->session->userdata('userName');
			$token = $this->session->userdata('token');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPCON");

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
			$paginaActual = $dataRequest->paginaActual;
			$empresa = $dataRequest->empresa;
			$fechaInicial = $dataRequest->fechaInicial;
			$fechaFin = $dataRequest->fechaFin;
			$filtroFecha = $dataRequest->filtroFecha;
			$nomEmpresa = isset($dataRequest->nomEmpresa)?$dataRequest->nomEmpresa :'';

					$data = array(
							"pais"=>$urlCountry,
							"idOperation" => $operation,
							"className" => $className,
							"idExtEmp"=> $empresa,
							"fechaIni" => $fechaInicial,
							"fechaFin" => $fechaFin,
							"filtroFecha" => $filtroFecha,
							"tamanoPagina"=> "10",
							"paginaActual"=> "1",
							"logAccesoObject"=>$logAcceso,
							"token"=>$token
							);

					$data = json_encode($data,JSON_UNESCAPED_UNICODE);

					$dataEncry = np_Hoplite_Encryption($data, 'graficoCuentaConcentradora');
					$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
					$data = json_encode($data);
					$response = np_Hoplite_GetWS($data);
					$jsonResponse = np_Hoplite_Decrypt($response, 'graficoCuentaConcentradora');
					$response = json_decode($jsonResponse);
					if($response){
							log_message('info','CuentaConcentradora GRAFICO '.$response->rc."/".$response->msg);
							if($response->rc==0){
								$response = $this->cryptography->encrypt($response);
								$this->output->set_content_type('application/json')->set_output(json_encode($response));
							}else{

									if($response->rc==-61  || $response->rc==-29){
											$responseError = ['mensaje' => lang('ERROR_(-29)'), "rc"=> "-29"];
											$responseError = $this->cryptography->encrypt($responseError);
											$this->output->set_content_type('application/json')->set_output(json_encode($responseError));
											$this->session->sess_destroy();

									}else{
											$codigoError = lang('ERROR_('.$response->rc.')');
											if(strpos($codigoError, 'Error')!==false){

													$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
											}else{
													$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
											}
									}
									$this->output->set_content_type('application/json')->set_output(json_encode($codigoError));
							}

					}else{
							log_message('info','CuentaConcentradora GRAFICO NO WS');
							$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'));
							$this->output->set_content_type('application/json')->set_output(json_encode($codigoError));
					}

			}else{
					$this->session->sess_destroy();
					$responseError = ['mensaje' => lang('ERROR_(-29)'), "rc"=> "-29"];
											$responseError = $this->cryptography->encrypt($responseError);
					$this->output->set_content_type('application/json')->set_output(json_encode( array('ERROR' => lang('ERROR_(-29)'), "rc"=> "-29")));
			}


	}

		/**
	 * Método para exportar en formato Excel el reporte consolidado en cuenta concentradora.
	 *
	 * @param  string $urlCountry
	 * @return bytes
	 */
	public function expCuentaConcentradoraConsolidadoXLS($urlCountry){
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo    ="reportes";
			$function  ="generaArchivoXlsConcil";
			$operation ="generaArchivoXlsConcil";
			$className ="com.novo.objects.MO.DepositosGarantiaMO";
			$timeLog   = date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$username = $this->session->userdata('userName');
			$token = $this->session->userdata('token');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPCON");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){
					$paginaActual=$this->input->post('paginaActual');
					$empresa = $this->input->post('empresa');
					$fechaInicial = $this->input->post('fechaInicial');
					$fechaFin = $this->input->post('fechaFin');
					$filtroFecha = $this->input->post('filtroFecha');
					$nomEmpresa = $this->input->post('nomEmpresa');
					$anio=$this->input->post('anio');

					$data = array(
							"pais"=>$urlCountry,
							"idOperation" => $operation,
							"className" => $className,
							"idExtEmp"=> $empresa,
							"fechaIni" => "",
							"fechaFin" => "",
							"tipoNota"=> "",
							"filtroFecha" => "0",
							"nombreEmpresa" => $nomEmpresa,
							"producto" => $this->session->userdata('idProductoS'),
							"anio"=> $anio,
							"tamanoPagina"=> "10",
							"paginaActual"=> "1",
							"logAccesoObject"=>$logAcceso,
							"token"=>$token
							);

					$data = json_encode($data,JSON_UNESCAPED_UNICODE);

					$dataEncry = np_Hoplite_Encryption($data, 'expCuentaConcentradoraConsolidadoXLS');
					$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
					$data = json_encode($data);
					$response = np_Hoplite_GetWS($data);
					$jsonResponse = np_Hoplite_Decrypt($response, 'expCuentaConcentradoraConsolidadoXLS');

					$response =  json_decode($jsonResponse);

					if($response){
							log_message('info','depositosdegarantias CONSOLIDADO XLS '.$response->rc."/".$response->msg);
							if($response->rc==0){
									np_hoplite_byteArrayToFile($response->archivo,"xls","CuentaConcentradora_Consolidado");
							}else{

									if($response->rc==-61 || $response->rc==-29){
											$this->session->sess_destroy();
											echo "<script>alert('Usuario actualmente desconectado');
											window.history.back(-1);</script>";

									}else{
											$codigoError = lang('ERROR_('.$response->rc.')');
											if(strpos($codigoError, 'Error')!==false){
													$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
											}else{
													$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
											}
											echo '<script languaje=\"javascript\">alert("'.$codigoError["mensaje"].'"); history.back();</script>';
											return $codigoError;

									}

							}

					}else{
							log_message('info','depositosdegarantias CONSOLIDADO XLS NO WS');
							echo "
							<script>
							alert('".lang('ERROR_GENERICO_USER')."');
							window.history.back(-1);
							</script>";
					}

			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}


	}

		/**
	 * Método para exportar en formato PSF el reporte consolidado en cuenta concentradora.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	public function expCuentaConcentradoraConsolidadoPDF($urlCountry){
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo    ="reportes";
			$function  ="generaArchivoConcilPdf";
			$operation ="generaArchivoConcilPdf";
			$className ="com.novo.objects.MO.DepositosGarantiaMO";
			$timeLog   = date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$username = $this->session->userdata('userName');
			$token = $this->session->userdata('token');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPCON");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){
					$paginaActual=$this->input->post('paginaActual');
					$empresa = $this->input->post('empresa');
					$fechaInicial = $this->input->post('fechaInicial');
					$fechaFin = $this->input->post('fechaFin');
					$filtroFecha = $this->input->post('filtroFecha');
					$nomEmpresa = $this->input->post('nomEmpresa');
					$anio=$this->input->post('anio');

					$data = array(
						"pais"=>$urlCountry,
						"idOperation" => $operation,
						"className" => $className,
						"idExtEmp"=> $empresa,
						"fechaIni" => "",
						"fechaFin" => "",
						"tipoNota"=> "",
						"filtroFecha" => "0",
						"nombreEmpresa" => $nomEmpresa,
						"producto" => $this->session->userdata('idProductoS'),
						"anio"=> $anio,
						"tamanoPagina"=> "10",
						"paginaActual"=> "1",
						"logAccesoObject"=>$logAcceso,
						"token"=>$token
						);

					$data = json_encode($data,JSON_UNESCAPED_UNICODE);

					$dataEncry = np_Hoplite_Encryption($data, 'expCuentaConcentradoraConsolidadoPDF');
					$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
					$data = json_encode($data);
					$response = np_Hoplite_GetWS($data);
					$jsonResponse = np_Hoplite_Decrypt($response, 'expCuentaConcentradoraConsolidadoPDF');

					$response =  json_decode($jsonResponse);

					if($response){
							log_message('info','depositosdegarantias CONSOLIDADO PDF '.$response->rc."/".$response->msg);
							if($response->rc==0){
									np_hoplite_byteArrayToFile($response->archivo,"pdf","CuentaConcentradora_Consolidado");
							}else{

									if($response->rc==-61 || $response->rc==-29){
											$this->session->sess_destroy();
											echo "<script>alert('Usuario actualmente desconectado');
											window.history.back(-1);</script>";

									}else{
											$codigoError = lang('ERROR_('.$response->rc.')');
											if(strpos($codigoError, 'Error')!==false){
													$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
											}else{
													$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
											}
											echo '<script languaje=\"javascript\">alert("'.$codigoError["mensaje"].'"); history.back();</script>';
											return $codigoError;

									}

							}

					}else{
							log_message('info','depositosdegarantias CONSOLIDADO PDF ');
							echo "
							<script>
							alert('".lang('ERROR_GENERICO_USER')."');
							window.history.back(-1);
							</script>";
					}

			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}


	}



	//-------------------------------------- Tarjetas Emitidas -----------------------------------------

		/**
	 * Pantalla para el reporte de tarjetas emitidas.
	 *
	 * @param  string $urlCountry
	 */
	public function tarjetasemitidas($urlCountry)
	{
			//VALIDATE COUNTRY
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('reportes');
			$this->lang->load('dashboard');
			$this->lang->load('users');
			$this->load->library('parser');
			$this->lang->load('erroreseol');

			$logged_in = $this->session->userdata('logged_in');
			$paisS = $this->session->userdata('pais');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPTAR");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){
					$nombreCompleto = $this->session->userdata('nombreCompleto');
					$lastSessionD = $this->session->userdata('lastSession');
					$jsRte = '../../../js/';
					$thirdsJsRte = '../../../js/third_party/';
					$FooterCustomInsertJS=["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js","jquery.dataTables.min.js","aes.min.js","aes-json-format.min.js","jquery.mtz.monthpicker.js","reportes/tarjetasemitidas.js","kendo.dataviz.min.js","header.js","jquery.balloon.min.js","routes.js",$thirdsJsRte."jquery.validate.min.js",$jsRte."validate-forms.js",$thirdsJsRte."additional-methods.min.js"];
					$FooterCustomJS="";
					$titlePage="Conexión Empresas Online - Reportes";
					$menuHeader = $this->parser->parse('widgets/widget-menuHeader',array(),TRUE);
					$menuFooter = $this->parser->parse('widgets/widget-menuFooter',array(),TRUE);
					$header = $this->parser->parse('layouts/layout-header',array('bodyclass'=>'','menuHeaderActive'=>TRUE,'menuHeaderMainActive'=>TRUE,'menuHeader'=>$menuHeader,'titlePage'=>$titlePage),TRUE);
					$footer = $this->parser->parse('layouts/layout-footer',array('menuFooterActive'=>TRUE,'menuFooter'=>$menuFooter,'FooterCustomInsertJSActive'=>TRUE,'FooterCustomInsertJS'=>$FooterCustomInsertJS,'FooterCustomJSActive'=>TRUE,'FooterCustomJS'=>$FooterCustomJS),TRUE);
					$content = $this->parser->parse('reportes/content-tarjetas-emitidas',array(
							'titulo'=>$nombreCompleto,
							'breadcrum'=>'',
							'lastSession'=>$lastSessionD,
							),TRUE);
					$sidebarLotes= ($urlCountry == 'Ec-bp') ? '' : $this->parser->parse('widgets/widget-publi-4',array('sidebarActive'=>TRUE),TRUE);

					$datos = array(
							'header'=>$header,
							'content'=>$content,
							'footer'=>$footer,
							'sidebar'=>$sidebarLotes,
							);

					$this->parser->parse('layouts/layout-b', $datos);
			}elseif($paisS!=$urlCountry){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}
	}


		/**
	 * Método para obtener la información de un reporte en tarjetas emitidas.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	public function getTarjetasEmitidas($urlCountry){
			np_hoplite_countryCheck($urlCountry);

			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPTAR");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){

					//Validate Request For Ajax
					if($this->input->is_ajax_request()){
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
						$_POST['empresa'] = $dataRequest->empresa;
						$_POST['fechaMes'] = (isset($dataRequest->fechaMes))?$dataRequest->fechaMes:'';
						$_POST['fechaInicial'] = (isset($dataRequest->fechaInicial))?$dataRequest->fechaInicial:"";
						$_POST['fechaFin']  = (isset($dataRequest->fechaFin))?$dataRequest->fechaFin:"";

						$this->form_validation->set_rules('empresa', 'Empresa', 'trim|xss_clean|required');
						$this->form_validation->set_rules('fechaMes', 'Fecha Mes', 'trim|xss_clean|regex_match[/^[0-9\/]+$/]');
						$this->form_validation->set_rules('fechaInicial', 'Fecha Inicio', 'trim|xss_clean|regex_match[/^[0-9\/]+$/]');
						$this->form_validation->set_rules('fechaFin', 'Fecha Fin', 'trim|xss_clean|regex_match[/^[0-9\/]+$/]');
						$this->form_validation->set_error_delimiters('', '---');
						}
							if ($this->form_validation->run() == FALSE)
							{
								log_message('DEBUG', 'NOVO VALIDATION ERRORS: '.json_encode(validation_errors()));
								$responseError = 'La combinación de caracteres es inválida';
								$responseError = $this->cryptography->encrypt($responseError);
								$this->output->set_content_type('application/json')->set_output(json_encode($responseError));
								return $responseError;
							}
							else
							{
								$empresa = $dataRequest->empresa;
								$fechaMes = (isset($dataRequest->fechaMes))?$dataRequest->fechaMes:"";
								$fechaInicial = (isset($dataRequest->fechaInicial))?$dataRequest->fechaInicial: "";
								$fechaFin = (isset($dataRequest->fechaFin))?$dataRequest->fechaFin: "";
								$tipoConsulta = $dataRequest->radioGeneral;
								$username = $this->session->userdata('userName');
								$token = $this->session->userdata('token');
								unset($_POST['empresa'], $_POST['fechaMes'], $_POST['fechaInicial'], $_POST['fechaFin']);
								$pruebaTabla = $this->callWSTarjetasEmitidas($urlCountry,$token,$username,$empresa,$fechaMes,$fechaInicial,$fechaFin,$tipoConsulta);
								$pruebaTabla = $this->cryptography->encrypt($pruebaTabla);
								$this->output->set_content_type('application/json')->set_output(json_encode($pruebaTabla));
							}

			}else{
					$this->session->sess_destroy();
					$responseError = ['ERROR' => lang('ERROR_(-29)'), "rc"=> "-29"];
					$responseError = $this->cryptography->encrypt($responseError);
					$this->output->set_content_type('application/json')->set_output(json_encode($responseError));
			}

	}

		/**
	 * Método que realiza petición al WS para obtener datos del reporte tarjetas emitidas.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	private function callWSTarjetasEmitidas($urlCountry,$token,$username,$empresa,$fechaMes,$fechaInicial,$fechaFin, $tipoConsulta){
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo="reportes";
			$function="buscarTarjetasEmitidas";
			$operation="buscarTarjetasEmitidas";
			$className="com.novo.objects.MO.ListadoEmisionesMO";
			$timeLog= date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);
			$data = array(
					"pais" => $urlCountry,
					"idOperation"=>$operation,
					"className"=> $className,
					"accodcia"=> $empresa,
					"fechaMes"=> $fechaMes,
					"fechaIni"=> $fechaInicial,
					"fechaFin"=> $fechaFin,
					"tipoConsulta"=> $tipoConsulta,
					"logAccesoObject" => $logAcceso,
					"token"=> $token,
					);


			$data = json_encode($data,JSON_UNESCAPED_UNICODE);
			$dataEncry = np_Hoplite_Encryption($data, 'callWSTarjetasEmitidas');
			$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
			$data = json_encode($data);
			$response = np_Hoplite_GetWS($data);
			$jsonResponse = np_Hoplite_Decrypt($response, 'callWSTarjetasEmitidas');
			$response =  json_decode($jsonResponse);

			if($response){
					log_message('info','tarjetasemitidas '.$response->rc."/".$response->msg);
					if($response->rc==0){
							return $response;
					}else{

							if($response->rc==-61 || $response->rc==-29){
									$codigoError = array('mensaje' => lang('ERROR_(-29)'), "rc"=> "-29");
									$this->session->sess_destroy();
									return $codigoError;

							}else{
									$codigoError = lang('ERROR_('.$response->rc.')');
									if(strpos($codigoError, 'Error')!==false){
											$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
									}else{
											$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
									}

									return $codigoError;

							}

					}

			}else{
					log_message('info','tarjetasemitidas no ws');
					return $codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'));
			}
	}

		/**
	 * Método para exportar en formato Excel el reporte completo de tarjetas emitidas.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	public function expTarjetasEmitidasXLS($urlCountry){
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo    ="reportes";
			$function  ="buscarTarjetasEmitidasExcel";
			$operation ="buscarTarjetasEmitidasExcel";
			$className ="com.novo.objects.MO.SaldosAmanecidosMO";
			$timeLog   = date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$username = $this->session->userdata('userName');
			$token = $this->session->userdata('token');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');
			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPTAR");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){

					$idEmpresa = $this->input->post('idEmpresa');
					$nomEmpresa = $this->input->post('nomEmpresa');
					$empresa = $this->input->post('empresa');
					$fechaMes = $this->input->post('fechaMes');
					$fechaInicial = $this->input->post('fechaInicial');
					$fechaFin = $this->input->post('fechaFin');
					$tipoConsulta = $this->input->post('radioGeneral');
					$tipoDetalle = $this->input->post('tipoDetalle');
					$posicionDetalle = $this->input->post('posicionDetalle');


					$data = array(
							"pais" => $urlCountry,
							"idOperation"=>$operation,
							"className"=> $className,
							"idExtEmp"=> $idEmpresa,
							"nombreEmpresa"=> $nomEmpresa,
							"accodcia"=> $empresa,
							"fechaMes"=> $fechaMes,
							"fechaIni"=> $fechaInicial,
							"fechaFin"=> $fechaFin,
							"tipoConsulta"=> $tipoConsulta,
							"tipoDetalle" => $tipoDetalle,
							"posicionDetalle" => $posicionDetalle,
							"opcion" => "CARD_EMI",
							"logAccesoObject" => $logAcceso,
							"token"=> $token,
							);
					$data = json_encode($data,JSON_UNESCAPED_UNICODE);

					$dataEncry = np_Hoplite_Encryption($data, 'expTarjetasEmitidasXLS');
					$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
					$data = json_encode($data);
					$response = np_Hoplite_GetWS($data);
					$jsonResponse = np_Hoplite_Decrypt($response, 'expTarjetasEmitidasXLS');

					$response =  json_decode($jsonResponse);

					if($response){
							log_message('info','tarjetasemitidas xls '.$response->rc."/".$response->msg);
							if($response->rc==0){
									np_hoplite_byteArrayToFile($response->archivo,"xls","TarjetasEmitidas");
							}else{

									if($response->rc==-61 || $response->rc==-29){
											$this->session->sess_destroy();
											echo "<script>alert('Usuario actualmente desconectado');
											window.history.back(-1);</script>";

									}else{
											$codigoError = lang('ERROR_('.$response->rc.')');
											if(strpos($codigoError, 'Error')!==false){
													$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
											}else{
													$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
											}
											echo '<script languaje=\"javascript\">alert("'.$codigoError["mensaje"].'"); history.back();</script>';
											return $codigoError;
									}
							}

					}else{
							log_message('info','tarjetasemitidas xls no ws');
							echo "
							<script>
							alert('".lang('ERROR_GENERICO_USER')."');
							window.history.back(-1);
							</script>";
					}
			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}

	}

		/**
	 * Método para exportar en formato PDF el reporte de tarjetas emitidas.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	public function expTarjetasEmitidasPDF($urlCountry){
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo    ="reportes";
			$function  ="buscarTarjetasEmitidasPDF";
			$operation ="buscarTarjetasEmitidasPDF";
			$className ="com.novo.objects.MO.SaldosAmanecidosMO";
			$timeLog   = date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$username = $this->session->userdata('userName');
			$token = $this->session->userdata('token');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');
			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPTAR");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){

					$idEmpresa = $this->input->post('idEmpresa');
					$nomEmpresa = $this->input->post('nomEmpresa');
					$empresa = $this->input->post('empresa');
					$fechaInicial = $this->input->post('fechaInicial');
					$fechaFin = $this->input->post('fechaFin');
					$tipoConsulta = $this->input->post('radioGeneral');

					$data = array(
							"pais" => $urlCountry,
							"idOperation"=>$operation,
							"className"=> $className,
							"idExtEmp"=> $idEmpresa,
							"nombreEmpresa"=> $nomEmpresa,
							"accodcia"=> $empresa,
							"fechaIni"=> $fechaInicial,
							"fechaFin"=> $fechaFin,
							"tipoConsulta"=> $tipoConsulta,
							"logAccesoObject" => $logAcceso,
							"token"=> $token,
							);

					$data = json_encode($data,JSON_UNESCAPED_UNICODE);
					$dataEncry = np_Hoplite_Encryption($data, 'expTarjetasEmitidasPDF');
					$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
					$data = json_encode($data);
					$response = np_Hoplite_GetWS($data);
					$jsonResponse = np_Hoplite_Decrypt($response, 'expTarjetasEmitidasPDF');

					$response =  json_decode($jsonResponse);

					if($response){

							log_message('info','tarjetasemitidas pdf '.$response->rc."/".$response->msg);

							if($response->rc==0){
									np_hoplite_byteArrayToFile($response->archivo,"pdf","TarjetasEmitidas");

							}else{

									if($response->rc==-61 || $response->rc==-29){
											$this->session->sess_destroy();
											echo "<script>alert('usuario actualmente desconectado');
											window.history.back(-1);</script>";

									}else{
											$codigoError = lang('ERROR_('.$response->rc.')');
											if(strpos($codigoError, 'Error')!==false){
													$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
											}else{
													$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
											}
											echo '<script languaje=\"javascript\">alert("'.$codigoError["mensaje"].'"); history.back();</script>';
											return $codigoError;
									}
							}

					}else{
							log_message('info','tarjetasemitidas pdf no ws');
							echo "
							<script>
							alert('".lang('ERROR_GENERICO_USER')."');
							window.history.back(-1);
							</script>";
					}
			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}

	}

	//------------------------------- Saldos Amanecidos (saldos al cierre) -------------------------------------------

		/**
	 * Pantalla para el reporte de saldos al cierre.
	 *
	 * @param  string $urlCountry
	 */
	public function saldosamanecidos($urlCountry)
	{
					//VALIDATE COUNTRY
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('reportes');
			$this->lang->load('dashboard');
			$this->lang->load('users');
			$this->load->library('parser');
			$this->lang->load('erroreseol');
			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPSAL");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){
					$nombreCompleto = $this->session->userdata('nombreCompleto');
					$lastSessionD = $this->session->userdata('lastSession');
					$jsRte = '../../../js/';
					$thirdsJsRte = '../../../js/third_party/';
					$FooterCustomInsertJS=["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js","jquery.paginate.js","aes.min.js","aes-json-format.min.js","reportes/saldosalcierre.js","header.js","jquery.balloon.min.js","routes.js",$thirdsJsRte."jquery.validate.min.js",$jsRte."validate-forms.js",$thirdsJsRte."additional-methods.min.js"];
					$FooterCustomJS="";
					$titlePage="Conexión Empresas Online - Reportes";

					$menuHeader = $this->parser->parse('widgets/widget-menuHeader',array(),TRUE);
					$menuFooter = $this->parser->parse('widgets/widget-menuFooter',array(),TRUE);
					$header = $this->parser->parse('layouts/layout-header',array('bodyclass'=>'','menuHeaderActive'=>TRUE,'menuHeaderMainActive'=>TRUE,'menuHeader'=>$menuHeader,'titlePage'=>$titlePage),TRUE);
					$footer = $this->parser->parse('layouts/layout-footer',array('menuFooterActive'=>TRUE,'menuFooter'=>$menuFooter,'FooterCustomInsertJSActive'=>TRUE,'FooterCustomInsertJS'=>$FooterCustomInsertJS,'FooterCustomJSActive'=>TRUE,'FooterCustomJS'=>$FooterCustomJS),TRUE);
					$content = $this->parser->parse('reportes/content-saldos-al-cierre',array(
							'titulo'=>$nombreCompleto,
							'breadcrum'=>'',
							'lastSession'=>$lastSessionD,
							),TRUE);
					$sidebarLotes= $this->parser->parse('widgets/widget-publi-4',array('sidebarActive'=>TRUE),TRUE);

					$datos = array(
							'header'=>$header,
							'content'=>$content,
							'footer'=>$footer,
							'sidebar'=>$sidebarLotes,
							);

					$this->parser->parse('layouts/layout-b', $datos);
			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}
	}

		/**
	 * Método para obtener la información de un reporte en saldos al cierre.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	public function getSaldosAmanecidos($urlCountry) {
		np_hoplite_countryCheck($urlCountry);

		$logged_in = $this->session->userdata('logged_in');

		$paisS = $this->session->userdata('pais');

		$menuP =$this->session->userdata('menuArrayPorProducto');
		$moduloAct = np_hoplite_existeLink($menuP,"REPSAL");

		if($paisS==$urlCountry && $logged_in && $moduloAct!==false) {
			if($this->input->is_ajax_request()){
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
				$_POST['empresa'] = $dataRequest->empresa;
				$_POST['cedula'] = $dataRequest->cedula;
				$_POST['producto'] = $dataRequest->producto;
				$_POST['paginaActual'] = $dataRequest->paginaActual;
				$_POST['tamPg'] = $dataRequest->tamPg;
				$this->form_validation->set_rules('empresa', 'empresa', 'trim|regex_match[/^([\w-]+[\s]*)+$/i]|required');
				$this->form_validation->set_rules('cedula', 'cedula',  'trim|regex_match[/^[0-9]+$/]');
				$this->form_validation->set_rules('producto', 'producto',  'trim|regex_match[/^([\w-]+[\s]*)+$/i]required');
				$this->form_validation->set_rules('paginaActual', 'paginaActual', 'trim|numeric|required');
				$this->form_validation->set_rules('tamPg', 'tamPg', 'trim|numeric|required');

				$this->form_validation->set_error_delimiters('', '---');

				if ($this->form_validation->run() == FALSE)	{
					log_message('DEBUG', 'NOVO VALIDATION ERRORS: '.json_encode(validation_errors()));
					$responseError = 'Combinacion de caracteres no válida';
					$responseError = $this->cryptography->encrypt($responseError);
					$this->output->set_content_type('application/json')->set_output(json_encode($responseError));
					return $responseError;

				}	else {

					$paginaActual = $dataRequest->paginaActual;
					$empresa = $dataRequest->empresa;
					$cedula = $dataRequest->cedula;
					$producto = $dataRequest->producto;
					$paginar = TRUE;
					$tamPg = $dataRequest->tamPg;
					$username = $this->session->userdata('userName');
					$token = $this->session->userdata('token');
					unset($_POST['empresa'], $_POST['cedula'], $_POST['producto']);
					$pruebaTabla = $this->callWSSaldosAmanecidos($urlCountry,$token,$username,$empresa,$cedula,$producto,$paginaActual,$paginar,$tamPg);
					$response = $this->cryptography->encrypt($pruebaTabla);
					$this->output->set_content_type('application/json')->set_output(json_encode($response));
				}
			}
		} else {
			$this->session->sess_destroy();
			$responseError = ['ERROR' => lang('ERROR_(-29)'), "rc"=> "-29"];
			$responseError = $this->cryptography->encrypt($responseError);
			$this->output->set_content_type('application/json')->set_output(json_encode($responseError));
		}

	}

	/**
	 * Método que realiza petición al WS para obtener la información de un reporte en saldos al cierre.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	private function callWSSaldosAmanecidos($urlCountry,$token,$username,$empresa,$cedula,$producto,$paginaActual,$paginar,$tamPg){
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo="reportes";
			$function="saldosAmanecidos";
			$operation="saldosAmanecidos";
			$className="com.novo.objects.MO.SaldosAmanecidosMO";
			$timeLog= date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

			$data = array(
					"idOperation" => $operation,
					"pais"=>$urlCountry,
					"className" => $className,
					"idExtPer"=> $cedula,
					"producto"=> $producto,
					"idExtEmp" =>$empresa,
					"tamanoPagina" => $tamPg,
					"paginar" => $paginar,
					"paginaActual" => $paginaActual,
					"logAccesoObject"=>$logAcceso,
					"token"=>$token
					);

			$data = json_encode($data,JSON_UNESCAPED_UNICODE);
			$dataEncry = np_Hoplite_Encryption($data, 'callWSSaldosAmanecidos');
			$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
			$data = json_encode($data);
			$response = np_Hoplite_GetWS($data);
			$jsonResponse = np_Hoplite_Decrypt($response, 'callWSSaldosAmanecidos');

			log_message('info', 'RESPONSE callWSSaldosAmanecidos'.$jsonResponse);

			$response =  json_decode($jsonResponse);

			if($response){
					log_message('info','saldosamanecidos '.$response->rc);

					if($response->rc==0){
							return $response;
					}else{

							if($response->rc==-61 || $response->rc==-29){
									$codigoError = array('mensaje' => lang('ERROR_(-29)'), "rc"=> "-29");
									$this->session->sess_destroy();
									return $codigoError;

							}else{
									$codigoError = lang('ERROR_('.$response->rc.')');
									if(strpos($codigoError, 'Error')!==false){
											$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
									}else{
											$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
									}

									return $codigoError;
							}
					}
			}else{
					log_message('info','saldosamanecidos NO WS');
					return $codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'));
			}
	}

	/**
	 * Método para exportar en formato Excel el reporte de saldos al cierre.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	public function expSaldosAmanecidosXLS($urlCountry){
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo    ="reportes";
			$function  ="generaArchivoXls";
			$operation ="generaArchivoXls";
			$className ="com.novo.objects.MO.SaldosAmanecidosMO";
			$timeLog   = date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$username = $this->session->userdata('userName');
			$token = $this->session->userdata('token');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPSAL");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){
					$paginaActual=$this->input->post('paginaActual');
					$empresa = $this->input->post('empresa');
					$cedula = $this->input->post('cedula');
					$producto = $this->input->post('producto');
					$nomEmpresa = $this->input->post('nomEmpresa');
					$descProd = $this->input->post('descProd');

					$data = array(
							"idOperation" => $operation,
							"pais"=>$urlCountry,
							"className" => $className,
							"idExtPer"=> $cedula,
							"producto"=> $producto,
							"idExtEmp" =>$empresa,
							"tamanoPagina" => "10",
							"paginar" => "true",
							"paginaActual" => $paginaActual,
							"nombreEmpresa"=> $nomEmpresa,
							"descProd"=> $descProd,
							"logAccesoObject"=>$logAcceso,
							"token"=>$token
							);

					$data = json_encode($data,JSON_UNESCAPED_UNICODE);
					$dataEncry = np_Hoplite_Encryption($data, 'expSaldosAmanecidosXLS');
					$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
					$data = json_encode($data);
					$response = np_Hoplite_GetWS($data);
					$jsonResponse = np_Hoplite_Decrypt($response, 'expSaldosAmanecidosXLS');

					$response =  json_decode($jsonResponse);

					if($response){
							log_message('info','saldosamanecidos XLS '.$response->rc);
							if($response->rc==0){
									np_hoplite_byteArrayToFile($response->archivo,"xls","SaldosAlCierre");
							}else{

									if($response->rc==-61 || $response->rc==-29){
											$this->session->sess_destroy();
											echo "<script>alert('Usuario actualmente desconectado');
											window.history.back(-1);</script>";
									}else{
											$codigoError = lang('ERROR_('.$response->rc.')');
											if(strpos($codigoError, 'Error')!==false){
													$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
											}else{
													$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
											}
											echo '<script languaje=\"javascript\">alert("'.$codigoError["mensaje"].'"); history.back();</script>';
											return $codigoError;
									}

							}

					}else{
							log_message('info','saldosamanecidos XLS NO WS');
							echo "
							<script>
							alert('".lang('ERROR_GENERICO_USER')."');
							window.history.back(-1);
							</script>";
					}
			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}

	}

	//------------------------------- Estatus de Lotes  -------------------------------------------

	/**
	 * Pantalla para el reporte de estatus de lotes.
	 *
	 * @param  string $urlCountry
	 */
	public function estatuslotes($urlCountry)
	{
			//VALIDATE COUNTRY
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('reportes');
			$this->lang->load('dashboard');
			$this->lang->load('users');
			$this->load->library('parser');
			$this->lang->load('erroreseol');
			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPLOT");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){
					$nombreCompleto = $this->session->userdata('nombreCompleto');
					$lastSessionD = $this->session->userdata('lastSession');
					$jsRte = '../../../js/';
					$thirdsJsRte = '../../../js/third_party/';
					$FooterCustomInsertJS=["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js","aes.min.js","aes-json-format.min.js","header.js","jquery.balloon.min.js","jquery.dataTables.min.js","reportes/estatusdelotes.js","routes.js",$thirdsJsRte."jquery.validate.min.js",$jsRte."validate-forms.js",$thirdsJsRte."additional-methods.min.js"];
					$FooterCustomJS="";
					$titlePage="Conexión Empresas Online - Reportes";

					$menuHeader = $this->parser->parse('widgets/widget-menuHeader',array(),TRUE);
					$menuFooter = $this->parser->parse('widgets/widget-menuFooter',array(),TRUE);
					$header = $this->parser->parse('layouts/layout-header',array('bodyclass'=>'','menuHeaderActive'=>TRUE,'menuHeaderMainActive'=>TRUE,'menuHeader'=>$menuHeader,'titlePage'=>$titlePage),TRUE);
					$footer = $this->parser->parse('layouts/layout-footer',array('menuFooterActive'=>TRUE,'menuFooter'=>$menuFooter,'FooterCustomInsertJSActive'=>TRUE,'FooterCustomInsertJS'=>$FooterCustomInsertJS,'FooterCustomJSActive'=>TRUE,'FooterCustomJS'=>$FooterCustomJS),TRUE);
					$content = $this->parser->parse('reportes/content-estatus-lotes',array(
							'titulo'=>$nombreCompleto,
							'breadcrum'=>'',
							'lastSession'=>$lastSessionD,
							),TRUE);
					$sidebarLotes= ($urlCountry == 'Ec-bp') ? '' : $this->parser->parse('widgets/widget-publi-4',array('sidebarActive'=>TRUE),TRUE);
					$datos = array(
							'header'=>$header,
							'content'=>$content,
							'footer'=>$footer,
							'sidebar'=>$sidebarLotes,
							);
					$this->parser->parse('layouts/layout-b', $datos);
			}else{
					redirect($urlCountry.'/login/');
			}
	}

	/**
	 * Método para obtener los datos para el reporte de los tarjetas Habientes
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	public function getEstatusTarjetasHabientes($urlCountry){
			np_hoplite_countryCheck($urlCountry);

			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"TEBTHA");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){

					//Validate Request For Ajax
					if($this->input->is_ajax_request()){
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
						if($dataRequest !== null){
							$_POST['nombreEmpresa'] = $dataRequest->nombreEmpresa;
              $_POST['lotes_producto'] = $dataRequest->lotes_producto;
							$this->form_validation->set_rules('nombreEmpresa', 'nombreEmpresa',  'trim|xss_clean|required');
							$this->form_validation->set_rules('lotes_producto', 'tarjeta',  'trim|xss_clean|required');
								if ($this->form_validation->run() == FALSE)
								{
									log_message('DEBUG', 'NOVO VALIDATION ERRORS: '.json_encode(validation_errors()));
									$responseError = 'La combinación de caracteres es inválida';
									$responseError = $this->cryptography->encrypt($responseError);
									$this->output->set_content_type('application/json')->set_output(json_encode($responseError));
									return $responseError;
								}else{
								  $paginaActual = $dataRequest->paginaActual;
								  $loteproducto = $dataRequest->lotes_producto;
								  $acrif = $dataRequest->acrif;
								  $username = $this->session->userdata('userName');
								  $token = $this->session->userdata('token');
                  unset($_POST['paginaActual'], $_POST['lotes_producto']);
								  $pruebaTabla = $this->callWSEstatusTarjetasHabientes($urlCountry,$token,$username,$acrif, $loteproducto, $paginaActual );
								  $pruebaTabla = $this->cryptography->encrypt($pruebaTabla);
								  $this->output->set_content_type('application/json')->set_output(json_encode($pruebaTabla));
								}
						}
					}
			}else{
					$this->session->sess_destroy();
					$responseError = ['ERROR' => lang('ERROR_(-29)'), "rc"=> "-29"];
					$responseError = $this->cryptography->encrypt($responseError);
					$this->output->set_content_type('application/json')->set_output(json_encode($responseError));
			}

	}

	/**
	 * Método que realiza petición al WS para obtener los datos para el reporte tarjetas habientes.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	private function callWSEstatusTarjetasHabientes($urlCountry,$token,$username,$acrif, $loteproducto, $paginaActual){

			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo="reportes";
			$function="Reportes Tarjetahabiente";
			$operation="getConsultarTarjetaHabientes";
			$operacion="Listar Tarjetahabientes";
			$className="com.novo.objects.MO.TarjetaHabientesMO";
			$timeLog= date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operacion,0,$ip,$timeLog);

			$data = array(
					"idOperation" => $operation,
					"className" => $className,
					"paginaActual" => $paginaActual,
					"tamanoPagina"=>10,
					"paginar" => true,
					"rifEmpresa"=> $acrif,
					"idProducto" => $loteproducto,
					"logAccesoObject"=>$logAcceso,
					"token"=>$token,
					"pais"=>$urlCountry
			);

			$data = json_encode($data,JSON_UNESCAPED_UNICODE);
			$dataEncry = np_Hoplite_Encryption($data, 'callWSEstatusTarjetasHabientes');
			$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
			$data = json_encode($data);
			$response = np_Hoplite_GetWS($data);
			$jsonResponse = np_Hoplite_Decrypt($response, 'callWSEstatusTarjetasHabientes');
			$response =  json_decode($jsonResponse);
			$data1 = json_encode($response);
			log_message('info','SALIDA desencriptada callWSEstatusTarjetasHabientes '.$data1);
			if($response){
					log_message('info','Estatus Lotes '.$response->rc."/".$response->msg);
					if($response->rc==0){
							return $response;
					}else{
						if($response->rc==-61 || $response->rc==-29){
							$codigoError = array('mensaje' => lang('ERROR_(-29)'), "rc"=> "-29");
							$this->session->sess_destroy();
							return $codigoError;
						}else{
              $codigoError = lang('ERROR_('.$response->rc.')');
            if($response->rc==-20){
              $codigoError = array('ERROR' => lang('ERROR_GENERAL'), "rc"=> $response->rc);
						}else if(strpos($codigoError, 'Error')!==false){
							$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
						}else{
							$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
						}
							return $codigoError;
						}
					}

			}else{
					log_message('info','Estatus Lotes NO WS ');
					return $codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'));
			}
	}

	/**
	 * Método para obtener los datos para el reporte estatus de lotes.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	public function getEstatusLotes($urlCountry){
			np_hoplite_countryCheck($urlCountry);
			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPLOT");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){

					//Validate Request For Ajax
					if($this->input->is_ajax_request()){
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
							$_POST['empresa'] = $dataRequest->empresa;
							$_POST['fechaInicial'] = $dataRequest->fechaInicial;
							$_POST['fechaFin'] = $dataRequest->fechaFin;
							$_POST['lotes_producto'] = $dataRequest->lotes_producto;
							$this->form_validation->set_rules('empresa', 'empresa',  'trim|xss_clean|required');
							$this->form_validation->set_rules('fechaInicial', 'Fecha Inicio',  'trim|xss_clean|regex_match[/^[0-9\/]+$/]|required');
							$this->form_validation->set_rules('fechaFin', 'Fecha Fin',  'trim|xss_clean|regex_match[/^[0-9\/]+$/]|required');
							$this->form_validation->set_rules('lotes_producto', 'Tarjeta',  'trim|xss_clean|required');

							if ($this->form_validation->run() == FALSE)
							{
								$responseError = 'La combinacion de caracteres es invalido';
								$responseError = $this->cryptography->encrypt($responseError);
								$this->output->set_content_type('application/json')->set_output(json_encode($responseError));
								return $responseError;
							}
							else
							{

							$paginaActual = $dataRequest->paginaActual;
							$empresa = $dataRequest->empresa;
							$fechaInicial = $dataRequest->fechaInicial;
							$fechaFin = $dataRequest->fechaFin;
							$loteproducto = $dataRequest->lotes_producto;
							$username = $this->session->userdata('userName');
							$token = $this->session->userdata('token');
							unset($_POST['lotes_producto'], $_POST['empresa'], $_POST['fechaInicial'], $_POST['fechaFin']);
							$pruebaTabla = $this->callWSEstatusLotes($urlCountry,$token,$username,$empresa,$fechaInicial,$fechaFin,$loteproducto);
							$pruebaTabla = $this->cryptography->encrypt($pruebaTabla);
							$this->output->set_content_type('application/json')->set_output(json_encode($pruebaTabla));
							}
					}
			}else{
					$this->session->sess_destroy();
					$responseError = ['ERROR' => lang('ERROR_(-29)'), "rc"=> "-29"];
					$responseError = $this->cryptography->encrypt($responseError);
					$this->output->set_content_type('application/json')->set_output(json_encode($responseError));
			}

	}

	/**
	 * Método que realiza petición al WS para obtener los datos para el reporte estatus de lotes.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	private function callWSEstatusLotes($urlCountry,$token,$username,$empresa,$fechaInicial,$fechaFin,$loteproducto){

			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo="reportes";
			$function="buscarEstatusLotes";
			$operation="buscarEstatusLotes";
			$className="com.novo.objects.MO.ListadoLotesMO";
			$timeLog= date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

			$data = array(
					"idOperation" => $operation,
					"pais"=>$urlCountry,
					"className" => $className,
					"acCodCia" => $empresa,
					"idProducto" => $loteproducto,
					"dtfechorcargaIni" => $fechaInicial,
					"dtfechorcargaFin" => $fechaFin,
					"logAccesoObject"=>$logAcceso,
					"token"=>$token
					);

			$data = json_encode($data,JSON_UNESCAPED_UNICODE);
			$dataEncry = np_Hoplite_Encryption($data, 'callWSEstatusLotes');
			$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
			$data = json_encode($data);
			$response = np_Hoplite_GetWS($data);
			$jsonResponse = np_Hoplite_Decrypt($response, 'callWSEstatusLotes');

			$response =  json_decode($jsonResponse);

			if($response){
					log_message('info','Estatus Lotes '.$response->rc."/".$response->msg);
					if($response->rc==0){
							return $response;
					}else{

							if($response->rc==-61 || $response->rc==-29){
									$codigoError = array('mensaje' => lang('ERROR_(-29)'), "rc"=> "-29");
									$this->session->sess_destroy();
									return $codigoError;

							}else{
									$codigoError = lang('ERROR_('.$response->rc.')');
									if(strpos($codigoError, 'Error')!==false){
											$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
									}else{
											$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
									}

									return $codigoError;
							}
					}

			}else{
					log_message('info','Estatus Lotes NO WS ');
					return $codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'));
			}
	}

	/**
	 * Método para exportar en formato PDF el reporte de estatus de lotes.
	 *
	 * @param  string $urlCountry
	 * @return bytes
	 */
	public function expEstatusLotesPDF($urlCountry){
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo    ="reportes";
			$function  ="generarPdfEstatusLotes";
			$operation ="generarPdfEstatusLotes";
			$className ="com.novo.objects.MO.ListadoLotesMO";
			$timeLog   = date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$username = $this->session->userdata('userName');
			$token = $this->session->userdata('token');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPLOT");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){

					$empresa = $this->input->post('empresa');
					$fechaInicial = $this->input->post('fechaInicial');
					$fechaFin = $this->input->post('fechaFin');
					$loteproducto = $this->input->post('lotes_producto');
					$data = array(
							"pais"=>$urlCountry,
							"idOperation" => $operation,
							"className" => $className,
							"acCodCia"=> $empresa,
							"idProducto"=> $loteproducto,
							"dtfechorcargaIni" => $fechaInicial,
							"dtfechorcargaFin" => $fechaFin,
							"logAccesoObject"=>$logAcceso,
							"token"=>$token
							);

					$data = json_encode($data,JSON_UNESCAPED_UNICODE);

					$dataEncry = np_Hoplite_Encryption($data, 'expEstatusLotesPDF');
					$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
					$data = json_encode($data);
					$response = np_Hoplite_GetWS($data);
					$jsonResponse = np_Hoplite_Decrypt($response, 'expEstatusLotesPDF');

					$response =  json_decode($jsonResponse);

					if($response){
							log_message('info','Estatus Lotes PDF '.$response->rc."/".$response->msg);
							if($response->rc==0){
									np_hoplite_byteArrayToFile($response->archivo,"pdf","EstatusDeLotes");
							}else{

									if($response->rc==-61 || $response->rc==-29){
											$this->session->sess_destroy();
											echo "<script>alert('Usuario actualmente desconectado');
											window.history.back(-1);</script>";

									}else{
											$codigoError = lang('ERROR_('.$response->rc.')');
											if(strpos($codigoError, 'Error')!==false){
													$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
											}else{
													$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
											}
											echo '<script languaje=\"javascript\">alert("'.$codigoError["mensaje"].'"); history.back();</script>';
											return $codigoError;
									}
							}

					}else{
							log_message('info','Estatus Lotes PDF NO WS ');
							echo "
							<script>
							alert('".lang('ERROR_GENERICO_USER')."');
							window.history.back(-1);
							</script>";
					}
			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}

	}

	/**
	 * Método para exportar en formato Excel el reporte de estatus de lotes.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	public function expEstatusLotesXLS($urlCountry){
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo    ="reportes";
			$function  ="generarArchivoXlsEstatusLotes";
			$operation ="generarArchivoXlsEstatusLotes";
			$className ="com.novo.objects.MO.ListadoLotesMO";
			$timeLog   = date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$username = $this->session->userdata('userName');
			$token = $this->session->userdata('token');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPLOT");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){

					$empresa = $this->input->post('empresa');
					$fechaInicial = $this->input->post('fechaInicial');
					$fechaFin = $this->input->post('fechaFin');
					$loteproducto = $this->input->post('lotes_producto');
					$data = array(
							"pais"=>$urlCountry,
							"idOperation" => $operation,
							"className" => $className,
							"acCodCia"=> $empresa,
							"idProducto"=> $loteproducto,
							"dtfechorcargaIni" => $fechaInicial,
							"dtfechorcargaFin" => $fechaFin,
							"logAccesoObject"=>$logAcceso,
							"token"=>$token
							);

					$data = json_encode($data,JSON_UNESCAPED_UNICODE);
					$dataEncry = np_Hoplite_Encryption($data, 'expEstatusLotesXLS');
					$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
					$data = json_encode($data);
					$response = np_Hoplite_GetWS($data);
					$jsonResponse = np_Hoplite_Decrypt($response, 'expEstatusLotesXLS');

					$response =  json_decode($jsonResponse);

					if($response){
							log_message('info', 'estatusdelotes xls '.$response->rc);

							if($response->rc==0){
									np_hoplite_byteArrayToFile($response->archivo,"xls","EstatusDeLotes");
							}else{

									if($response->rc==-61 || $response->rc==-29){
											$this->session->sess_destroy();
											echo "<script>alert('Usuario actualmente desconectado');
											window.history.back(-1);</script>";

									}else{
											$codigoError = lang('ERROR_('.$response->rc.')');
											if(strpos($codigoError, 'Error')!==false){
													$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
											}else{
													$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
											}
											echo '<script languaje=\"javascript\">alert("'.$codigoError["mensaje"].'"); history.back();</script>';
											return $codigoError;
									}
							}

					}else{
							log_message('info', 'estatusdelotes xls NO WS');
							echo "
							<script>
							alert('".lang('ERROR_GENERICO_USER')."');
							window.history.back(-1);
							</script>";
					}

			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}

	}


	/**
	 * Método para exportar en formato Excel el reporte de tarjetasHabientes.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	public function expEstatusTarjetasHabientesXLS($urlCountry){
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo    ="Reportes";
			$function  ="Reportes Tarjetahabiente";
			$operation ="consultarTarjetaHabientesExcel";
			$className ="com.novo.objects.MO.TarjetaHabientesMO ";
			$operacion="Descargar Excel";
			$timeLog   = date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$username = $this->session->userdata('userName');
			$token = $this->session->userdata('token');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operacion,0,$ip,$timeLog);

			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"TEBTHA");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){

					$acrif = $this->input->post('acrif');
					$nomEmpresa =$this->input->post('nombreEmpresa');
					$nombreProducto =$this->input->post('nombreProducto');
					$loteproducto = $this->input->post('lotes_producto');

					$data = array(
							"idOperation" => $operation,
							"className" => $className,
							"paginaActual"=> 1,
							"tamanoPagina"=> 10,
							"paginar"=> false,
							"rifEmpresa"=> $acrif,
							"nombreEmpresa"=>$nomEmpresa,
							"idProducto"=>$loteproducto,
							"nombreProducto"=>$nombreProducto,
							"logAccesoObject"=>$logAcceso,
							"token"=>$token,
							"pais"=>$urlCountry
							);

					$data = json_encode($data,JSON_UNESCAPED_UNICODE);
					$dataEncry = np_Hoplite_Encryption($data, 'expEstatusTarjetasHabientesXLS');
					$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
					$data = json_encode($data);
					$response = np_Hoplite_GetWS($data);
					$jsonResponse = np_Hoplite_Decrypt($response, 'expEstatusTarjetasHabientesXLS');

					$response =  json_decode($jsonResponse);

					if($response){
							log_message('info', 'tarjetasHabientes xls '.$response->rc);

							if($response->rc==0){
									np_hoplite_byteArrayToFile($response->archivo,"xls","tarjetasHabientes");
							}else{

									if($response->rc==-61 || $response->rc==-29){
											$this->session->sess_destroy();
											echo "<script>alert('Usuario actualmente desconectado');
											window.history.back(-1);</script>";

									}else{
											$codigoError = lang('ERROR_('.$response->rc.')');
											if(strpos($codigoError, 'Error')!==false){
													$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
											}else{
													$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
											}
											echo '<script languaje=\"javascript\">alert("'.$codigoError["mensaje"].'"); history.back();</script>';
											return $codigoError;
									}
							}

					}else{
							log_message('info', 'tarjetasHabientes xls NO WS');
							echo "
							<script>
							alert('".lang('ERROR_GENERICO_USER')."');
							window.history.back(-1);
							</script>";
					}

			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}

	}

		/**
	 * Método para exportar en formato PDF el reporte de tarjetashabientes.
	 *
	 * @param  string $urlCountry
	 * @return bytes
	 */
	public function expEstatusTarjetasHabientesPDF($urlCountry){
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo    ="Reportes";
			$function  ="Reportes Tarjetahabiente";
			$operation ="consultarTarjetaHabientesPDF";
			$operacion ="Descargar PDF";
			$className ="com.novo.objects.MO.TarjetaHabientesMO";
			$timeLog   = date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$username = $this->session->userdata('userName');
			$token = $this->session->userdata('token');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operacion,0,$ip,$timeLog);

			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"TEBTHA");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){

					$acrif = $this->input->post('acrif');
					$nomEmpresa =$this->input->post('nombreEmpresa');
					$nombreProducto =$this->input->post('nombreProducto');
					$loteproducto = $this->input->post('lotes_producto');

					$data = array(
							"idOperation" => $operation,
							"className" => $className,
							"paginaActual"=> 1,
							"tamanoPagina"=> 10,
							"paginar"=> false,
							"rifEmpresa"=> $acrif,
							"nombreEmpresa"=>$nomEmpresa,
							"idProducto"=>$loteproducto,
							"nombreProducto"=>$nombreProducto,
							"logAccesoObject"=>$logAcceso,
							"token"=>$token,
							"pais"=>$urlCountry
							);

					$data = json_encode($data,JSON_UNESCAPED_UNICODE);
					log_message('info', 'tarjetasHabientes PDF'.$data);
					$dataEncry = np_Hoplite_Encryption($data, 'expEstatusTarjetasHabientesPDF');
					$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
					$data = json_encode($data);
					$response = np_Hoplite_GetWS($data);
					$jsonResponse = np_Hoplite_Decrypt($response, 'expEstatusTarjetasHabientesPDF');

					$response =  json_decode($jsonResponse);

					if($response){
							log_message('info', 'tarjetasHabientes PDF '.$response->rc);

							if($response->rc==0){
									np_hoplite_byteArrayToFile($response->archivo,"pdf","tarjetasHabientes");
							}else{

									if($response->rc==-61 || $response->rc==-29){
											$this->session->sess_destroy();
											echo "<script>alert('Usuario actualmente desconectado');
											window.history.back(-1);</script>";

									}else{
											$codigoError = lang('ERROR_('.$response->rc.')');
											if(strpos($codigoError, 'Error')!==false){
													$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
											}else{
													$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
											}
											echo '<script languaje=\"javascript\">alert("'.$codigoError["mensaje"].'"); history.back();</script>';
											return $codigoError;
									}
							}

					}else{
							log_message('info', 'tarjetasHabientes PDF NO WS');
							echo "
							<script>
							alert('".lang('ERROR_GENERICO_USER')."');
							window.history.back(-1);
							</script>";
					}

			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}

	}


	//-------------------------------------- Reposiciones de Tarjetas y Claves--------------------------------

	/**
	 * Pantalla del reporte de reposiciones.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	public function reposiciones($urlCountry)
	{
			//VALIDATE COUNTRY
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('reportes');
			$this->lang->load('dashboard');
			$this->lang->load('users');
			$this->load->library('parser');
			$this->lang->load('erroreseol');
			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');
			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPREP");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){
					$nombreCompleto = $this->session->userdata('nombreCompleto');
					$lastSessionD = $this->session->userdata('lastSession');
					$FooterCustomInsertJS=["jquery-3.6.0.min.js","jquery-ui-1.13.1.min.js","aes.min.js","aes-json-format.min.js","reportes/reposiciones.js","header.js","jquery.balloon.min.js","jquery.paginate.js","routes.js"];
					$FooterCustomJS="";
					$titlePage="Conexión Empresas Online - Reportes";

					$menuHeader = $this->parser->parse('widgets/widget-menuHeader',array(),TRUE);
					$menuFooter = $this->parser->parse('widgets/widget-menuFooter',array(),TRUE);
					$header = $this->parser->parse('layouts/layout-header',array('bodyclass'=>'','menuHeaderActive'=>TRUE,'menuHeaderMainActive'=>TRUE,'menuHeader'=>$menuHeader,'titlePage'=>$titlePage),TRUE);
					$footer = $this->parser->parse('layouts/layout-footer',array('menuFooterActive'=>TRUE,'menuFooter'=>$menuFooter,'FooterCustomInsertJSActive'=>TRUE,'FooterCustomInsertJS'=>$FooterCustomInsertJS,'FooterCustomJSActive'=>TRUE,'FooterCustomJS'=>$FooterCustomJS),TRUE);
					$content = $this->parser->parse('reportes/content-reposiciones',array(
							'titulo'=>$nombreCompleto,
							'breadcrum'=>'',
							'lastSession'=>$lastSessionD,
							),TRUE);
					$sidebarLotes= $this->parser->parse('widgets/widget-publi-4',array('sidebarActive'=>TRUE),TRUE);

					$datos = array(
							'header'=>$header,
							'content'=>$content,
							'footer'=>$footer,
							'sidebar'=>$sidebarLotes,
							);

					$this->parser->parse('layouts/layout-b', $datos);
			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}
	}

	/**
	 * Método para obtener los datos para el reporte reposiciones.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	public function getReposiciones($urlCountry){
			np_hoplite_countryCheck($urlCountry);

			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPREP");

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
				$paginaActual = $dataRequest->paginaActual;
				$empresa = $dataRequest->empresa;
				$fechaInicial = $dataRequest->fechaInicial;
				$fechaFin = $dataRequest->fechaFin;
				$idTarjetaHabiente = $dataRequest->idTarjetaHabiente;
				$tipoReposicion = $dataRequest->tipoReposicion;
				$producto = $dataRequest->producto;
				$tamPg = $dataRequest->tamPg;
				$paginar = $dataRequest->paginar;

					$pruebaTabla = $this->callWSReposiciones($urlCountry, $empresa, $producto, $fechaInicial, $fechaFin, $paginaActual, $tipoReposicion, $idTarjetaHabiente, $tamPg, $paginar);
					$pruebaTabla = $this->cryptography->encrypt($pruebaTabla);
					$this->output->set_content_type('application/json')->set_output(json_encode($pruebaTabla));

			}else{
					$this->session->sess_destroy();
					$responseError = ['ERROR' => lang('ERROR_(-29)'), "rc"=> "-29"];
					$responseError = $this->cryptography->encrypt($responseError);
					$this->output->set_content_type('application/json')->set_output(json_encode($responseError));
			}

	}

	/**
	 * Método que realiza petición al WS para obtener los datos para el reporte reposiciones.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	private function callWSReposiciones($urlCountry, $empresa, $producto,$fechaInicial, $fechaFin, $paginaActual, $tipoReposicion, $idTarjetaHabiente, $tamPg, $paginar){
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo="buscarReposicionesDetalle";
			$function="buscarReposicionesDetalle";
			$operation="buscarReposicionesDetalle";
			$className="com.novo.objects.MO.ReposicionesMO";
			$timeLog= date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$username = $this->session->userdata('userName');
			$token = $this->session->userdata('token');
			$sessionId = $this->session->userdata('sessionId');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

			$data = array(
					"pais"=>$urlCountry,
					"idOperation" => $operation,
					"className" => $className,
					"idExtPer" => $idTarjetaHabiente,
					"idExtEmp"=> $empresa,
					"producto" => $producto,
					"paginaActual" => $paginaActual,
					"tamanoPagina" => $tamPg,
					"paginar" => $paginar,
					"fechaIni" => $fechaInicial,
					"fechaFin"=> $fechaFin,
					"tipoRep"=> $tipoReposicion,
					"logAccesoObject"=>$logAcceso,
					"token"=>$token
					);

			$data = json_encode($data,JSON_UNESCAPED_UNICODE);

			$dataEncry = np_Hoplite_Encryption($data, 'callWSReposiciones');
			$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
			$data = json_encode($data);
			$response = np_Hoplite_GetWS($data);
			$jsonResponse = np_Hoplite_Decrypt($response, 'callWSReposiciones');

			$response =  json_decode($jsonResponse);

			if($response){
					log_message('info','Reposiciones '.$response->rc."/".$response->msg);
					if($response->rc==0){
							return $response;
					}else{

							if($response->rc==-61 || $response->rc==-29){
									$codigoError = array('mensaje' => lang('ERROR_(-29)'), "rc"=> "-29");
									$this->session->sess_destroy();
									return $codigoError;

							}else{
									$codigoError = lang('ERROR_('.$response->rc.')');
									if(strpos($codigoError, 'Error')!==false){
											$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
									}else{
											$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
									}
									return $codigoError;
							}
					}

			}else{
					log_message('info','Reposiciones NO WS ');
					return $codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'));
			}
	}

	/**
	 * Método exportar en formato Excel el reporte de reposiciones.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	public function reposicionesExpXLS($urlCountry){
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo    ="reposicionesGeneraArchivo";
			$function  ="reposicionesGeneraArchivo";
			$operation ="reposicionesGeneraArchivo";
			$className ="com.novo.objects.MO.ReposicionesMO";
			$timeLog   = date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$username = $this->session->userdata('userName');
			$token = $this->session->userdata('token');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

			$logged_in = $this->session->userdata('logged_in');

			$paginaActual=$this->input->post('paginaActual');
			$empresa = $this->input->post('empresa');
			$fechaInicial = $this->input->post('fechaInicial');
			$fechaFin = $this->input->post('fechaFin');
			$idTarjetaHabiente = $this->input->post('idTarjetaHabiente');
			$tipoReposicion = $this->input->post('tipoReposicion');
			$producto =$this->input->post('producto');
			$nomEmpresa =$this->input->post('nomEmpresa');
			$nomProducto =$this->input->post('nomProducto');

			$paisS = $this->session->userdata('pais');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPREP");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){

					$data = array(
					"pais"=>$urlCountry,
					"idOperation" => $operation,
					"className" => $className,
					"idExtPer" => $idTarjetaHabiente,
					"idExtEmp"=> $empresa,
					"producto" => $producto,
					"paginaActual" => $paginaActual,
					"tamanoPagina" => "10",
					"paginar" => "false",
					"fechaIni" => $fechaInicial,
					"fechaFin"=> $fechaFin,
					"tipoRep"=> $tipoReposicion,
					"nombreEmpresa"=> $nomEmpresa,
					"descProducto"=> $nomProducto,
					"logAccesoObject"=>$logAcceso,
					"token"=>$token
					);

					$data = json_encode($data,JSON_UNESCAPED_UNICODE);

					$dataEncry = np_Hoplite_Encryption($data, 'reposicionesExpXLS');
					$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
					$data = json_encode($data);
					$response = np_Hoplite_GetWS($data);
					$jsonResponse = np_Hoplite_Decrypt($response, 'reposicionesExpXLS');

					$response =  json_decode($jsonResponse);

					if($response){
							log_message('info','Reposiciones XLS '.$response->rc."/".$response->msg);
							if($response->rc==0){
									np_hoplite_byteArrayToFile($response->archivo,"xls","Reposiciones");
							}else{

									if($response->rc==-61 || $response->rc==-29){
											$this->session->sess_destroy();
											echo "<script>alert('usuario actualmente desconectado');
											window.history.back(-1);</script>";

									}else{
											$codigoError = lang('ERROR_('.$response->rc.')');
											if(strpos($codigoError, 'Error')!==false){
													$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
											}else{
													$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
											}
											echo '<script languaje=\"javascript\">alert("'.$codigoError["mensaje"].'"); history.back();</script>';
											return $codigoError;
									}
							}

					}else{
							log_message('info','Reposiciones XLS NO WS ');
							echo "
							<script>
							alert('".lang('ERROR_GENERICO_USER')."');
							window.history.back(-1);
							</script>";
					}
			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}
	}

	//-------------------------------------- Recargas Realizadas -----------------------------------------

	/**
	 * Pantalla para el reporte de recargas realizas.
	 *
	 * @param  string $urlCountry
	 */
	public function recargasrealizadas($urlCountry)
	{
			//VALIDATE COUNTRY
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('reportes');
			$this->lang->load('dashboard');
			$this->lang->load('users');
			$this->load->library('parser');
			$this->lang->load('erroreseol');
			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPPRO");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){
					$nombreCompleto = $this->session->userdata('nombreCompleto');
					$lastSessionD = $this->session->userdata('lastSession');
					$jsRte = '../../../js/';
					$thirdsJsRte = '../../../js/third_party/';
					$FooterCustomInsertJS=["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js","aes.min.js","aes-json-format.min.js","reportes/recargasrealizadas.js","kendo.dataviz.min.js","header.js","highcharts.js","exporting.js","jquery.balloon.min.js","routes.js",$thirdsJsRte."jquery.validate.min.js",$jsRte."validate-forms.js",$thirdsJsRte."additional-methods.min.js"];
					$FooterCustomJS="";
					$titlePage="Conexión Empresas Online - Reportes";

					$menuHeader = $this->parser->parse('widgets/widget-menuHeader',array(),TRUE);
					$menuFooter = $this->parser->parse('widgets/widget-menuFooter',array(),TRUE);

					$header = $this->parser->parse('layouts/layout-header',array('bodyclass'=>'','menuHeaderActive'=>TRUE,'menuHeaderMainActive'=>TRUE,'menuHeader'=>$menuHeader,'titlePage'=>$titlePage),TRUE);
					$footer = $this->parser->parse('layouts/layout-footer',array('menuFooterActive'=>TRUE,'menuFooter'=>$menuFooter,'FooterCustomInsertJSActive'=>TRUE,'FooterCustomInsertJS'=>$FooterCustomInsertJS,'FooterCustomJSActive'=>TRUE,'FooterCustomJS'=>$FooterCustomJS),TRUE);
					$content = $this->parser->parse('reportes/content-recargas-realizadas',array(
							'titulo'=>$nombreCompleto,
							'breadcrum'=>'',
							'lastSession'=>$lastSessionD,
							),TRUE);
					$sidebarLotes= ($urlCountry == 'Ec-bp')? '': $this->parser->parse('widgets/widget-publi-4',array('sidebarActive'=>TRUE),TRUE);

					$datos = array(
							'header'=>$header,
							'content'=>$content,
							'footer'=>$footer,
							'sidebar'=>$sidebarLotes,
							);

					$this->parser->parse('layouts/layout-b', $datos);
			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}
	}

	/**
	 * Método para obtener los datos del reporte de recargas realizadas.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	public function getRecargasRealizadas($urlCountry){
			np_hoplite_countryCheck($urlCountry);

			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');
			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPPRO");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){

							//Validate Request For Ajax
					if($this->input->is_ajax_request()){
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
						$_POST['paginaActual'] = $dataRequest->paginaActual;
						$_POST['empresa'] = $dataRequest->empresa;
						$_POST['anio'] = $dataRequest->anio;
						$_POST['mes'] = $dataRequest->mes;
						$this->form_validation->set_rules('paginaActual', 'paginaActual',  'trim|xss_clean|required');
						$this->form_validation->set_rules('empresa', 'Empresa',  'trim|xss_clean|required');
						$this->form_validation->set_rules('anio', 'anio',  'trim|xss_clean|regex_match[/^[0-9\/]+$/]|required');
						$this->form_validation->set_rules('mes', 'mes',  'trim|xss_clean|regex_match[/^[0-9\/]+$/]|required');
						$this->form_validation->set_error_delimiters('', '---');
							if ($this->form_validation->run() == FALSE)
							{
								log_message('DEBUG', 'NOVO VALIDATION ERRORS: '.json_encode(validation_errors()));
								$responseError = 'La combinación de caracteres es inválida';
								$responseError = $this->cryptography->encrypt($responseError);
								$this->output->set_content_type('application/json')->set_output(json_encode($responseError));
								return $responseError;
							}
							else
							{

									$paginaActual = $dataRequest->paginaActual;
									$empresa = $dataRequest->empresa;
									$anio = $dataRequest->anio;
									$mes = $dataRequest->mes;
									$username = $this->session->userdata('userName');
									$token = $this->session->userdata('token');
									unset($_POST['paginaActual'], $_POST['empresa'], $_POST['anio'], $_POST['mes']);
									$pruebaTabla = $this->callWSRecargasRealizadas($urlCountry,$token,$username,$empresa,$mes,$anio,$paginaActual);
									$pruebaTabla = $this->cryptography->encrypt($pruebaTabla);
									$this->output->set_content_type('application/json')->set_output(json_encode($pruebaTabla));
							}
					}
			}else{
					$this->session->sess_destroy();
					$responseError = ['ERROR' => lang('ERROR_(-29)'), "rc"=> "-29"];
					$responseError = $this->cryptography->encrypt($responseError);
					$this->output->set_content_type('application/json')->set_output(json_encode($responseError));
			}
	}

		/**
	 * Método que realiza petición al WS para obtener los datos del reporte recargas relizadas.
	 *
	 * @param  string $urlCountry
	 * @param  string $token,$username,$empresa,$mes,$anio,$paginaActual
	 * @return JSON
	 */
	private function callWSRecargasRealizadas($urlCountry,$token,$username,$empresa,$mes,$anio,$paginaActual){
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo="reportes";
			$function="recargasRealizadas";
			$operation="recargasRealizadas";
			$className="com.novo.objects.TOs.RecargasRealizadasTO";
			$timeLog= date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

			$data = array(
					"idOperation" => $operation,
					"className" => $className,
					"pais"=>$urlCountry,
					"fecha" => "",
					"fecha1" => "",
					"fecha2" => "",
					"tamanoPagina" => 10,
					"accodcia"=> $empresa,
					"mesSeleccionado"=> $mes,
					"anoSeleccionado"=> $anio,
					"paginaActual" => $paginaActual,
					"logAccesoObject"=>$logAcceso,
					"token"=>$token
					);

			$data = json_encode($data,JSON_UNESCAPED_UNICODE);
			$dataEncry = np_Hoplite_Encryption($data, 'callWSRecargasRealizadas');
			$data = array('bean' => $dataEncry, 'pais' =>$urlCountry);
			$data = json_encode($data);
			$response = np_Hoplite_GetWS($data);
			$jsonResponse = np_Hoplite_Decrypt($response, 'callWSRecargasRealizadas');

			$response =  json_decode($jsonResponse);

			if($response){
					log_message('info','RecargasRealizadas '.$response->rc."/".$response->msg);
					if($response->rc==0){
							return $response;
					}else{

							if($response->rc==-61 || $response->rc==-29){
									$codigoError = array('mensaje' => lang('ERROR_(-29)'), "rc"=> "-29");
									$this->session->sess_destroy();
									return $codigoError;

							}else{
									$codigoError = lang('ERROR_('.$response->rc.')');
									if(strpos($codigoError, 'Error')!==false){
											$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
									}else{
											$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
									}

									return $codigoError;
							}
					}

			}else{
					log_message('info','RecargasRealizadas NO WS');
					return $codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'));
			}
	}

	/**
	 * Método exportar en formato Excel el reporte de recargas realizadas.
	 *
	 * @param  string $urlCountry
	 * @return bytes
	 */
	public function expRecargasrealizadasXLS($urlCountry){
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo    ="generarExcelRecargasRealizadas";
			$function  ="generarExcelRecargasRealizadas";
			$operation ="generarExcelRecargasRealizadas";
			$className ="com.novo.objects.TOs.RecargasRealizadasTO";
			$timeLog   = date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$username = $this->session->userdata('userName');
			$token = $this->session->userdata('token');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

			$logged_in = $this->session->userdata('logged_in');

			$empresa = $this->input->post('empresa');
			$anio = $this->input->post('anio');
			$mes = $this->input->post('mes');

			$paisS = $this->session->userdata('pais');
			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPPRO");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){

					$data = array(
							"idOperation" => $operation,
							"className" => $className,
							"pais"=>$urlCountry,
							"fecha" => "",
							"fecha1" => "",
							"fecha2" => "",
							"tamanoPagina" => 10,
							"accodcia"=> $empresa,
							"mesSeleccionado"=> $mes,
							"anoSeleccionado"=> $anio,
							"paginaActual" => 1,
							"logAccesoObject"=>$logAcceso,
							"token"=>$token
							);

					$data = json_encode($data,JSON_UNESCAPED_UNICODE);

					$dataEncry = np_Hoplite_Encryption($data, 'expRecargasrealizadasXLS');
					$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
					$data = json_encode($data);
					$response = np_Hoplite_GetWS($data);
					$jsonResponse = np_Hoplite_Decrypt($response, 'expRecargasrealizadasXLS');

					$response =  json_decode($jsonResponse);

					if($response){
							log_message('info','RecargasRealizadas XLS '.$response->rc."/".$response->msg);
							if($response->rc==0){
									np_hoplite_byteArrayToFile($response->archivo,"xls","RecargasRealizadas");
							}else{

									if($response->rc==-61 || $response->rc==-29){
											$this->session->sess_destroy();
											echo "<script>alert('Usuario actualmente desconectado');
											window.history.back(-1);</script>";

									}else{
											$codigoError = lang('ERROR_('.$response->rc.')');
											if(strpos($codigoError, 'Error')!==false){
													$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
											}else{
													$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
											}
											echo '<script languaje=\"javascript\">alert("'.$codigoError["mensaje"].'"); history.back();</script>';
											return $codigoError;
									}
							}
					}else{
							log_message('info','RecargasRealizadas XLS NO WS');
							echo "
							<script>
							alert('".lang('ERROR_GENERICO_USER')."');
							window.history.back(-1);
							</script>";
					}
			}elseif($paisS!=$urlCountry){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}
	}

	/**
	 * Método exportar en formato PDF el reporte de recargas realizadas.
	 *
	 * @param  string $urlCountry
	 * @return bytes
	 */
	public function expRecargasRealizadasPDF($urlCountry){
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo    ="generarPdfRecargasRealizadas";
			$function  ="generarPdfRecargasRealizadas";
			$operation ="generarPdfRecargasRealizadas";
			$className ="com.novo.objects.TOs.RecargasRealizadasTO";
			$timeLog   = date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$username = $this->session->userdata('userName');
			$token = $this->session->userdata('token');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

			$logged_in = $this->session->userdata('logged_in');

			$empresa = $this->input->post('empresa');
			$anio = $this->input->post('anio');
			$mes = $this->input->post('mes');

			$paisS = $this->session->userdata('pais');
			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPPRO");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){

					$data = array(
							"idOperation" => $operation,
							"className" => $className,
							"pais"=>$urlCountry,
							"fecha" => "",
							"fecha1" => "",
							"fecha2" => "",
							"tamanoPagina" => 10,
							"accodcia"=> $empresa,
							"mesSeleccionado"=> $mes,
							"anoSeleccionado"=> $anio,
							"paginaActual" => 1,
							"logAccesoObject"=>$logAcceso,
							"token"=>$token
							);

					$data = json_encode($data,JSON_UNESCAPED_UNICODE);

					$dataEncry = np_Hoplite_Encryption($data, 'expRecargasRealizadasPDF');
					$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
					$data = json_encode($data);
					$response = np_Hoplite_GetWS($data);
					$jsonResponse = np_Hoplite_Decrypt($response, 'expRecargasRealizadasPDF');

					$response =  json_decode($jsonResponse);

					if($response){
							log_message('info','RecargasRealizadas PDF '.$response->rc."/".$response->msg);
							if($response->rc==0){
									np_hoplite_byteArrayToFile($response->archivo,"pdf","RecargasRealizadas");
							}else{

									if($response->rc==-61 || $response->rc==-29){
											$this->session->sess_destroy();
											echo "<script>alert('Usuario actualmente desconectado');
											window.history.back(-1);</script>";

									}else{
											$codigoError = lang('ERROR_('.$response->rc.')');
											if(strpos($codigoError, 'Error')!==false){
													$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
											}else{
													$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
											}
											echo '<script languaje=\"javascript\">alert("'.$codigoError["mensaje"].'"); history.back();</script>';
											return $codigoError;
									}
							}
					}else{
							log_message('info','RecargasRealizadas PDF NO WS');
							echo "
							<script>
							alert('".lang('ERROR_GENERICO_USER')."');
							window.history.back(-1);
							</script>";
					}
			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}
	}

	//-------------------------------------- Actividad por Usuario -----------------------------------------

	/**
	 * Pantalla para visualizar el reporte de actividad por usuario.
	 *
	 * @param  string $urlCountry
	 */
	public function actividadporusuario($urlCountry)
	{
			//VALIDATE COUNTRY
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('reportes');
			$this->lang->load('dashboard');
			$this->lang->load('users');
			$this->load->library('parser');
			$this->lang->load('erroreseol');
			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');
			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPUSU");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){
					$nombreCompleto = $this->session->userdata('nombreCompleto');
					$lastSessionD = $this->session->userdata('lastSession');
					$jsRte = '../../../js/';
					$thirdsJsRte = '../../../js/third_party/';
					$FooterCustomInsertJS=["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js","aes.min.js","aes-json-format.min.js","reportes/actividadporusuario.js","kendo.dataviz.min.js","header.js","jquery.balloon.min.js","jquery.dataTables.min.js","routes.js",$thirdsJsRte."jquery.validate.min.js",$jsRte."validate-forms.js",$thirdsJsRte."additional-methods.min.js"];
					$FooterCustomJS="";
					$titlePage="Conexión Empresas Online - Reportes";

					$menuHeader = $this->parser->parse('widgets/widget-menuHeader',array(),TRUE);
					$menuFooter = $this->parser->parse('widgets/widget-menuFooter',array(),TRUE);

					$header = $this->parser->parse('layouts/layout-header',array('bodyclass'=>'','menuHeaderActive'=>TRUE,'menuHeaderMainActive'=>TRUE,'menuHeader'=>$menuHeader,'titlePage'=>$titlePage),TRUE);
					$footer = $this->parser->parse('layouts/layout-footer',array('menuFooterActive'=>TRUE,'menuFooter'=>$menuFooter,'FooterCustomInsertJSActive'=>TRUE,'FooterCustomInsertJS'=>$FooterCustomInsertJS,'FooterCustomJSActive'=>TRUE,'FooterCustomJS'=>$FooterCustomJS),TRUE);
					$content = $this->parser->parse('reportes/content-actividad-por-usuario',array(
							'titulo'=>$nombreCompleto,
							'breadcrum'=>'',
							'lastSession'=>$lastSessionD,
							),TRUE);
					$sidebarLotes= $this->parser->parse('widgets/widget-publi-4',array('sidebarActive'=>TRUE),TRUE);

					$datos = array(
							'header'=>$header,
							'content'=>$content,
							'footer'=>$footer,
							'sidebar'=>$sidebarLotes,
							);

					$this->parser->parse('layouts/layout-b', $datos);
			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}
	}

		/**
	 * Método para obtener los datos del reporte actividad por usuario.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	public function getactividadporusuario($urlCountry){
			np_hoplite_countryCheck($urlCountry);

			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');
			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPUSU");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){
									//Validate Request For Ajax
					if($this->input->is_ajax_request()){
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
						$_POST['fech_ini'] = $dataRequest->data_fechaIni;
						$_POST['fech_fin'] = $dataRequest->data_fechaFin;
						$this->form_validation->set_rules('fech_ini', 'Desde',  'trim|xss_clean|regex_match[/^[0-9\/]+$/]');
						$this->form_validation->set_rules('fech_fin', 'Hasta',  'trim|xss_clean|regex_match[/^[0-9\/]+$/]');

							if ($this->form_validation->run() == FALSE)
							{
								$responseError = 'La combinacion de caracteres es invalido';
								$responseError = $this->cryptography->encrypt($responseError);
								$this->output->set_content_type('application/json')->set_output(json_encode($responseError));
								return $responseError;
							}
							else
							{

									$fechaIni = $dataRequest->data_fechaIni;
									$fechaFin = $dataRequest->data_fechaFin;
									$acodcia = $dataRequest->data_acodcia;
									unset($_POST['fech_ini'], $_POST['fech_fin']);
									$response = $this->callWSActividadPorUsuario($urlCountry, $fechaIni, $fechaFin, $acodcia);
									$response = $this->cryptography->encrypt($response);
									$this->output->set_content_type('application/json')->set_output(json_encode($response));
							}
					}
			}else{
					$this->session->sess_destroy();
					$response = $this->cryptography->encrypt( array('ERROR' => lang('ERROR_(-29)'), "rc"=> "-29"));
					$this->output->set_content_type('application/json')->set_output(json_encode($response));
			}

	}

		/**
	 * Método que realiza petición al WS para obtener los datos del reporte actividad por usuario.
	 *
	 * @param  string $urlCountry
	 * @param  string $fechaIni
	 * @param  string $fechaFin
	 * @param  string $acodcia
	 * @return JSON
	 */
	private function callWSActividadPorUsuario($urlCountry, $fechaIni, $fechaFin, $acodcia){
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo="ReportesGenerales";
			$function="ActividadesPorUsuario";
			$operation="buscarActividadesXUsuario";
			$className="com.novo.objects.MO.ListadoEmpresasMO";

			$timeLog= date("m/d/Y H:i");
			$ip= $this->input->ip_address();

			$sessionId = $this->session->userdata('sessionId');
			$username = $this->session->userdata('userName');
			$token = $this->session->userdata('token');

			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

			$data = array(
					"pais" => $urlCountry,
					"idOperation" => $operation,
					"className" => $className,
					"fechaIni" => $fechaIni,
					"fechaFin" => $fechaFin,
					"acCodCia" => $acodcia,
					"logAccesoObject" => $logAcceso,
					"token"=>$token
					);

			$data = json_encode($data,JSON_UNESCAPED_UNICODE);

			$dataEncry = np_Hoplite_Encryption($data, 'callWSActividadPorUsuario');
			$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
			$data = json_encode($data);
			$response = np_Hoplite_GetWS($data);
			$jsonResponse = np_Hoplite_Decrypt($response, 'callWSActividadPorUsuario');

			$response =  json_decode($jsonResponse);

			if($response){
					log_message('info','actividadporusuario '.$response->rc."/".$response->msg);
					if($response->rc==0){

							return $response;
					}else{

							if($response->rc==-61 || $response->rc==-29){
									$codigoError = array('ERROR' => lang('ERROR_(-29)'), "rc"=> "-29");
									$this->session->sess_destroy();
									return $codigoError;

							}else{
									$codigoError = lang('ERROR_('.$response->rc.')');
									if(strpos($codigoError, 'Error')!==false){
											$codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
									}else{
											$codigoError = array('ERROR' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
									}

									return $codigoError;
							}

					}

			}else{
					log_message('info','actividadporusuario NO WS');
					return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
			}
	}

		/**
	 * Método para exportar en formato PDF el reporte actividad por usuario.
	 *
	 * @param  string $urlCountry
	 * @return bytes
	 */
	public function downPDFactividadUsuario($urlCountry){

			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');//HOJA DE ERRORES;

			$logged_in = $this->session->userdata('logged_in');
			$paisS = $this->session->userdata('pais');
			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPUSU");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){

					$canal = "ceo";
					$modulo="ReportesGenerales";
					$function="ActividadesPorUsuario";
					$operation="generarPdfActividadesXUsuario";
					$className="com.novo.objects.MO.ListadoEmpresasMO";

					$timeLog= date("m/d/Y H:i");
					$ip= $this->input->ip_address();

					$sessionId = $this->session->userdata('sessionId');
					$username = $this->session->userdata('userName');
					$token = $this->session->userdata('token');

					$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

					$fechIni = $this->input->post('data-fechaIni');
					$fechFin = $this->input->post('data-fechaFin');
					$acodcia = $this->input->post('data-acodcia');
					$acrif = $this->input->post('data-acrif');

					$data = array(
							"pais" => $urlCountry,
							"idOperation" => $operation,
							"className" => $className,
							"fechaIni" => $fechIni,
							"fechaFin" => $fechFin,
							"acCodCia" => $acodcia,
							"rifEmpresa" => $acrif,
							"logAccesoObject" => $logAcceso,
							"token"=>$token
							);

					$data = json_encode($data,JSON_UNESCAPED_UNICODE);

					$dataEncry = np_Hoplite_Encryption($data, 'downPDFactividadUsuario');
					$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
					$data = json_encode($data);
					$response = np_Hoplite_GetWS($data);
					$jsonResponse = np_Hoplite_Decrypt($response, 'downPDFactividadUsuario');

					$response =  json_decode($jsonResponse);

					if($response){
							log_message("INFO",'PDF actividadporusuario '.$response->rc.'/'.$response->msg);

							if($response->rc==0){
									np_hoplite_byteArrayToFile($response->archivo,"pdf","ActividadPorUsuario".date("d/m/Y H:i"));

							}else{

									if($response->rc==-61 || $response->rc==-29){
											$this->session->sess_destroy();
											echo "<script>alert('Usuario actualmente desconectado');
											window.history.back(-1);</script>";
									}else{
											$codigoError = lang('ERROR_('.$response->rc.')');
											if(strpos($codigoError, 'Error')!==false){
													$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
											}else{
													$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
											}
											echo '<script languaje=\"javascript\">alert("'.$codigoError["mensaje"].'"); history.back();</script>';
											return $codigoError;
									}
							}
					}else{
							echo "
							<script>
							alert('".lang('ERROR_GENERICO_USER')."');
							window.history.back(-1);
							</script>";
					}

			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}
	}

		/**
	 * Método para exportar en formato Excel el reporte actividad por usuario.
	 *
	 * @param  string $urlCountry
	 * @return bytes
	 */
	public function downXLSactividadUsuario($urlCountry){

			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');//HOJA DE ERRORES;

			$logged_in = $this->session->userdata('logged_in');
			$paisS = $this->session->userdata('pais');
			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPUSU");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){

					$canal = "ceo";
					$modulo="ReportesGenerales";
					$function="ActividadesPorUsuario";
					$operation="generarArchivoXlsActividadesXUsuario";
					$className="com.novo.objects.MO.ListadoEmpresasMO";

					$timeLog= date("m/d/Y H:i");
					$ip= $this->input->ip_address();

					$sessionId = $this->session->userdata('sessionId');
					$username = $this->session->userdata('userName');
					$token = $this->session->userdata('token');

					$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

					$fechIni = $this->input->post('data-fechaIni');
					$fechFin = $this->input->post('data-fechaFin');
					$acodcia = $this->input->post('data-acodcia');
					$acrif = $this->input->post('data-acrif');

					$data = array(
							"pais" => $urlCountry,
							"idOperation" => $operation,
							"className" => $className,
							"fechaIni" => $fechIni,
							"fechaFin" => $fechFin,
							"acCodCia" => $acodcia,
							"rifEmpresa" => $acrif,
							"logAccesoObject" => $logAcceso,
							"token"=>$token
							);

					$data = json_encode($data,JSON_UNESCAPED_UNICODE);

					$dataEncry = np_Hoplite_Encryption($data, 'downXLSactividadUsuario');
					$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
					$data = json_encode($data);
					$response = np_Hoplite_GetWS($data);
					$jsonResponse = np_Hoplite_Decrypt($response, 'downXLSactividadUsuario');

					$response =  json_decode($jsonResponse);

					if($response){
							log_message("INFO",'XLS actividadporusuario '.$response->rc.'/'.$response->msg);

							if($response->rc==0){
									np_hoplite_byteArrayToFile($response->archivo,"xls","ActividadPorUsuario".date("d/m/Y H:i"));

							}else{

									if($response->rc==-61 || $response->rc==-29){
											$this->session->sess_destroy();
											echo "<script>alert('Usuario actualmente desconectado');
											window.history.back(-1);</script>";
									}

									else{
											$codigoError = lang('ERROR_('.$response->rc.')');
											if(strpos($codigoError, 'Error')!==false){
													$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
											}else{
													$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
											}
											echo '<script languaje=\"javascript\">alert("'.$codigoError["mensaje"].'"); history.back();</script>';
											return $codigoError;
									}
							}
					}else{
							echo "
							<script>
							alert('".lang('ERROR_GENERICO_USER')."');
							window.history.back(-1);
							</script>";
					}

			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}
	}



	//-------------------------------------- Gastos Por Categoria -----------------------------------------

		/**
	 * Pantalla para visualizar el reporte de gastos por categoría.
	 *
	 * @param  string $urlCountry
	 * @return bytes
	 */
	public function gastosporcategorias($urlCountry)
	{
			//VALIDATE COUNTRY
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('reportes');
			$this->lang->load('dashboard');
			$this->lang->load('users');
			$this->load->library('parser');
			$this->lang->load('erroreseol');
			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');
			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPCAT");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){
					$nombreCompleto = $this->session->userdata('nombreCompleto');
					$lastSessionD = $this->session->userdata('lastSession');
					$FooterCustomInsertJS=["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js","aes.min.js","aes-json-format.min.js","reportes/gastosporcategorias.js","kendo.dataviz.min.js","jquery.paginate.js","header.js","jquery.balloon.min.js","routes.js"];
					$FooterCustomJS="";
					$titlePage="Conexión Empresas Online - Reportes";

					$menuHeader = $this->parser->parse('widgets/widget-menuHeader',array(),TRUE);
					$menuFooter = $this->parser->parse('widgets/widget-menuFooter',array(),TRUE);
					$header = $this->parser->parse('layouts/layout-header',array('bodyclass'=>'','menuHeaderActive'=>TRUE,'menuHeaderMainActive'=>TRUE,'menuHeader'=>$menuHeader,'titlePage'=>$titlePage),TRUE);
					$footer = $this->parser->parse('layouts/layout-footer',array('menuFooterActive'=>TRUE,'menuFooter'=>$menuFooter,'FooterCustomInsertJSActive'=>TRUE,'FooterCustomInsertJS'=>$FooterCustomInsertJS,'FooterCustomJSActive'=>TRUE,'FooterCustomJS'=>$FooterCustomJS),TRUE);
					$content = $this->parser->parse('reportes/content-gastos-por-categoria',
							array(
									'titulo'=>$nombreCompleto,
									'breadcrum'=>'',
									'lastSession'=>$lastSessionD,
									),TRUE);
					$sidebarLotes= $this->parser->parse('widgets/widget-publi-4',array('sidebarActive'=>TRUE),TRUE);

					$datos = array(
							'header'=>$header,
							'content'=>$content,
							'footer'=>$footer,
							'sidebar'=>$sidebarLotes,
							);

					$this->parser->parse('layouts/layout-b', $datos);
			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}
	}

		/**
	 * Método para obtener los datos para visualizar el reporte gastos por categoría.
	 *
	 * @param  string $urlCountry
	 * @return bytes
	 */
	public function getgastosporcategorias($urlCountry){
			np_hoplite_countryCheck($urlCountry);


			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');
			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPCAT");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){
					//Validate Request For Ajax
					if($this->input->is_ajax_request()){
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
						$_POST['producto'] = $dataRequest->producto;
						$_POST['empresa'] =  $dataRequest->empresa;
						$_POST['fechaIni'] = $dataRequest->fechaInicial;
						$_POST['fechaFin'] =$dataRequest->fechaFin;
						$_POST['tipoConsulta'] =  $dataRequest->tipoConsulta;
						$_POST['cedula'] = $dataRequest->cedula;
						$_POST['tarjeta'] = $dataRequest->tarjeta;
						$this->form_validation->set_rules('producto', 'producto',  'trim|xss_clean|required');
						$this->form_validation->set_rules('empresa', 'empresa',  'trim|xss_clean|required');
						$this->form_validation->set_rules('fechaIni', 'fechaIni',  'trim|xss_clean|required|regex_match[/^[0-9\/]+$/]');
						$this->form_validation->set_rules('fechaFin', 'fechaFin',  'trim|xss_clean|required|regex_match[/^[0-9\/]+$/]');
						$this->form_validation->set_rules('tipoConsulta', 'tipoConsulta',  'trim|xss_clean|required');
						$this->form_validation->set_rules('cedula', 'cedula',  'trim|xss_clean|required');
						$this->form_validation->set_rules('tarjeta', 'tarjeta',  'trim|xss_clean|required|regex_match[/^[0-9]+$/]');

							if ($this->form_validation->run() == FALSE)
							{
								$responseError = 'La combinación de caracteres es inválida';
								$responseError = $this->cryptography->encrypt($responseError);
								$this->output->set_content_type('application/json')->set_output(json_encode($responseError));
								return $responseError;
							}
							else
							{

									$producto = (isset($dataRequest->producto))? $dataRequest->producto : "";

									$empresa = (isset($dataRequest->empresa))? $dataRequest->empresa : "";

									$fechaIni = (isset($dataRequest->fechaInicial))? $dataRequest->fechaInicial : "";

									$fechaFin = (isset($dataRequest->fechaFin))? $dataRequest->fechaFin : "";

									$tipoConsulta = (isset($dataRequest->tipoConsulta))? $dataRequest->tipoConsulta : "";

									$cedula = (isset($dataRequest->cedula))? $dataRequest->cedula : "";

									$tarjeta = (isset($dataRequest->tarjeta))? $dataRequest->tarjeta : "";
									$username = $this->session->userdata('userName');
									$token = $this->session->userdata('token');
									unset($_POST['producto'], $_POST['empresa'], $_POST['fechaIni'], $_POST['fechaFin'],$_POST['tipoConsulta'], $_POST['cedula'], $_POST['tarjeta']);
									$pruebaTabla = $this->callWSGastosPorCategorias($urlCountry,$token,$username,$empresa,$tarjeta,$cedula,$fechaIni,$fechaFin,$producto,$tipoConsulta);
									$response = $this->cryptography->encrypt($pruebaTabla);
									$this->output->set_content_type('application/json')->set_output(json_encode($response,JSON_UNESCAPED_UNICODE));

							}
					}
			}else{
					$this->session->sess_destroy();
					$responseError = ['ERROR' => lang('ERROR_(-29)'), "rc"=> "-29"];
					$responseError = $this->cryptography->encrypt($responseError);
					$this->output->set_content_type('application/json')->set_output(json_encode($responseError));
			}

	}

		/**
	 * Método que realiza petición al WS para obtener los datos para visualizar el reporte gastos por categoría.
	 *
	 * @param  string $urlCountry
	 * @param  string $token,$username,$empresa,$tarjeta,$cedula,$empresa,$fechaIni,$fechaFin,$producto,$tipoConsulta
	 * @return JSON
	 */
	private function callWSGastosPorCategorias($urlCountry,$token,$username,$empresa,$tarjeta,$cedula,$fechaIni,$fechaFin,$producto,$tipoConsulta){
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo="reportes";
			$function="buscarListadoGastosRepresentacion";
			$operation="buscarListadoGastosRepresentacion";
			$className="com.novo.objects.MO.GastosRepresentacionMO";
			$timeLog= date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

			$data = array(
					"idOperation" => $operation,
					"className" => $className,
					"pais"=>$urlCountry,
					"idExtEmp"=> $empresa,
					"idPersona"=> $cedula,
					"nroTarjeta"=> $tarjeta,
					"fechaIni"=> $fechaIni,
					"fechaFin"=> $fechaFin,
					"producto"=> $producto,
					"tipoConsulta"=> $tipoConsulta,
					"logAccesoObject"=>$logAcceso,
					"token"=>$token
					);

			$data = json_encode($data,JSON_UNESCAPED_UNICODE);
			//print_r($data);
			$dataEncry = np_Hoplite_Encryption($data, 'callWSGastosPorCategorias');
			$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
			$data = json_encode($data);
			$response = np_Hoplite_GetWS($data);
			$jsonResponse = np_Hoplite_Decrypt($response, 'callWSGastosPorCategorias');
			$response =  json_decode($jsonResponse);

			if($response){
					log_message('info','GastosPorCategorias '.$response->rc."/".$response->msg);
					if($response->rc==0){
							return $response;
					}else{

							if($response->rc==-61 || $response->rc==-29){
									$codigoError = array('mensaje' => lang('ERROR_(-29)'), "rc"=> "-29");
									$this->session->sess_destroy();
									return $codigoError;

							}else{
									$codigoError = lang('ERROR_('.$response->rc.')');
									if(strpos($codigoError, 'Error')!==false){
											$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
									}else{
											$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
									}

									return $codigoError;

							}

					}

			}else{
					log_message('info','GastosPorCategorias NO WS ');
					return $codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'));
			}
	}

		/**
	 * Método para exportar el reporte gastos por categoría en formato PDF.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	public function expGastosporCategoriasPDF($urlCountry){
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo    ="reportes";
			$function  ="generarArchivoPDFGastosRepresentacion";
			$operation ="generarArchivoPDFGastosRepresentacion";
			$className ="com.novo.objects.MO.GastosRepresentacionMO";
			$timeLog   = date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$username = $this->session->userdata('userName');
			$token = $this->session->userdata('token');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

			$logged_in = $this->session->userdata('logged_in');
			$paisS = $this->session->userdata('pais');
			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPCAT");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){

					$producto = $this->input->post('producto');
					$empresa = $this->input->post('empresa');
					$fechaIni = $this->input->post('fechaIni');
					$fechaFin = $this->input->post('fechaFin');
					$tipoConsulta = $this->input->post('tipoConsulta');
					$cedula = $this->input->post('cedula');
					$tarjeta= $this->input->post('tarjeta');

					$data = array(
							"idOperation" => $operation,
							"className" => $className,
							"pais"=>$urlCountry,
							"idExtEmp"=> $empresa,
							"idPersona"=> $cedula,
							"nroTarjeta"=> $tarjeta,
							"fechaIni"=> $fechaIni,
							"fechaFin"=> $fechaFin,
							"producto"=> $producto,
							"tipoConsulta"=> $tipoConsulta,
							"logAccesoObject"=>$logAcceso,
							"token"=>$token
							);

					$data = json_encode($data,JSON_UNESCAPED_UNICODE);

					$dataEncry = np_Hoplite_Encryption($data, 'expGastosporCategoriasPDF');
					$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
					$data = json_encode($data);
					$response = np_Hoplite_GetWS($data);
					$jsonResponse = np_Hoplite_Decrypt($response, 'expGastosporCategoriasPDF');
					$response =  json_decode($jsonResponse);

					if($response){
							log_message('info','GastosPorCategorias PDF '.$response->rc."/".$response->msg);
							if($response->rc==0){
									np_hoplite_byteArrayToFile($response->archivo,"pdf","GastosPorCategorias");
							}else{

									if($response->rc==-61 || $response->rc==-29){
											$this->session->sess_destroy();
											echo "<script>alert('Usuario actualmente desconectado');
											window.history.back(-1);</script>";

									}else{
											$codigoError = lang('ERROR_('.$response->rc.')');
											if(strpos($codigoError, 'Error')!==false){
													$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
											}else{
													$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
											}
											echo '<script languaje=\"javascript\">alert("'.$codigoError["mensaje"].'"); history.back();</script>';
											return $codigoError;
									}
							}
					}else{
							log_message('info','GastosPorCategorias PDF NO WS ');
							echo "
							<script>
							alert('".lang('ERROR_GENERICO_USER')."');
							window.history.back(-1);
							</script>";
					}
			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}
	}

		/**
	 * Método para exportar en formato Excel el reporte de gastos por categoría.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	public function expGastosporCategoriasXLS($urlCountry){
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo    ="reportes";
			$function  ="generarArchivoXlsGastosRepresentacion";
			$operation ="generarArchivoXlsGastosRepresentacion";
			$className ="com.novo.objects.MO.GastosRepresentacionMO";
			$timeLog   = date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$username = $this->session->userdata('userName');
			$token = $this->session->userdata('token');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');
			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPCAT");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){

					$producto = $this->input->post('producto');
					$empresa = $this->input->post('empresa');
					$fechaIni = $this->input->post('fechaIni');
					$fechaFin = $this->input->post('fechaFin');
					$tipoConsulta = $this->input->post('tipoConsulta');
					$cedula = $this->input->post('cedula');
					$tarjeta= $this->input->post('tarjeta');

					$data = array(
							"idOperation" => $operation,
							"className" => $className,
							"pais"=>$urlCountry,
							"idExtEmp"=> $empresa,
							"idPersona"=> $cedula,
							"nroTarjeta"=> $tarjeta,
							"fechaIni"=> $fechaIni,
							"fechaFin"=> $fechaFin,
							"producto"=> $producto,
							"tipoConsulta"=> $tipoConsulta,
							"logAccesoObject"=>$logAcceso,
							"token"=>$token
							);

					$data = json_encode($data,JSON_UNESCAPED_UNICODE);

					$dataEncry = np_Hoplite_Encryption($data, 'expGastosporCategoriasXLS');
					$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
					$data = json_encode($data);
					$response = np_Hoplite_GetWS($data);
					$jsonResponse = np_Hoplite_Decrypt($response, 'expGastosporCategoriasXLS');

					$response =  json_decode($jsonResponse);

					if($response){
							log_message('info','GastosPorCategorias XLS '.$response->rc."/".$response->msg);
							if($response->rc==0){
									np_hoplite_byteArrayToFile($response->archivo,"xls","GastosPorCategorias");
							}else{

									if($response->rc==-61 || $response->rc==-29){
											$this->session->sess_destroy();
											echo "<script>alert('Usuario actualmente desconectado');
											window.history.back(-1);</script>";

									}else{
											$codigoError = lang('ERROR_('.$response->rc.')');
											if(strpos($codigoError, 'Error')!==false){
													$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
											}else{
													$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
											}
											echo '<script languaje=\"javascript\">alert("'.$codigoError["mensaje"].'"); history.back();</script>';
											return $codigoError;
									}
							}

					}else{
							log_message('info','GastosPorCategorias XLS NO WS');
							echo "
							<script>
							alert('".lang('ERROR_GENERICO_USER')."');
							window.history.back(-1);
							</script>";
					}
			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}
	}



	//-------------------------------------- Estados de Cuenta -----------------------------------------

		/**
	 * Pantalla para visualizar el reporte de estados de cuenta.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	public function estadosdecuenta($urlCountry)
	{
			//VALIDATE COUNTRY
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('reportes');
			$this->lang->load('dashboard');
			$this->lang->load('users');
			$this->load->library('parser');
			$this->lang->load('erroreseol');
			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');
			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPEDO");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){
					$nombreCompleto = $this->session->userdata('nombreCompleto');
					$lastSessionD = $this->session->userdata('lastSession');
					$jsRte = '../../../js/';
					$thirdsJsRte = '../../../js/third_party/';
					$FooterCustomInsertJS=["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js","aes.min.js","aes-json-format.min.js","reportes/estadosdecuenta.js","kendo.dataviz.min.js","jquery.paginate.js","header.js","jquery.balloon.min.js","jquery.dataTables.min.js","routes.js",$thirdsJsRte."jquery.validate.min.js",$jsRte."validate-forms.js",$thirdsJsRte."additional-methods.min.js"];
					$FooterCustomJS="";
					$titlePage="Conexión Empresas Online - Reportes";

					$menuHeader = $this->parser->parse('widgets/widget-menuHeader',array(),TRUE);
					$menuFooter = $this->parser->parse('widgets/widget-menuFooter',array(),TRUE);

					$header = $this->parser->parse('layouts/layout-header',array('bodyclass'=>'','menuHeaderActive'=>TRUE,'menuHeaderMainActive'=>TRUE,'menuHeader'=>$menuHeader,'titlePage'=>$titlePage),TRUE);
					$footer = $this->parser->parse('layouts/layout-footer',array('menuFooterActive'=>TRUE,'menuFooter'=>$menuFooter,'FooterCustomInsertJSActive'=>TRUE,'FooterCustomInsertJS'=>$FooterCustomInsertJS,'FooterCustomJSActive'=>TRUE,'FooterCustomJS'=>$FooterCustomJS),TRUE);
					$content = $this->parser->parse('reportes/content-estados-de-cuenta',array(
							'titulo'=>$nombreCompleto,
							'breadcrum'=>'',
							'lastSession'=>$lastSessionD,
							),TRUE);
					$sidebarLotes= ($urlCountry =='Ec-bp') ? '' : $this->parser->parse('widgets/widget-publi-4',array('sidebarActive'=>TRUE),TRUE);

					$datos = array(
						'header'=>$header,
						'content'=>$content,
						'footer'=>$footer,
						'sidebar'=>$sidebarLotes,
						);
				$this->parser->parse('layouts/layout-b', $datos);
		}elseif($paisS!=$urlCountry && $paisS!=""){
				$this->session->sess_destroy();
				redirect($urlCountry.'/login');
		}else{
				redirect($urlCountry.'/login');
		}
	}

		/**
	 * Método para obtener los datos del reporte de estados de cuenta.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	public function getestadosdecuenta($urlCountry){
			np_hoplite_countryCheck($urlCountry);

			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');
			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPEDO");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){


					//Validate Request For Ajax
					if($this->input->is_ajax_request()){
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
						$_POST['paginaActual'] = $dataRequest->paginaActual;
						$_POST['empresa'] = $dataRequest->empresa;
						$_POST['cedula'] = $dataRequest->cedula;
						$_POST['fechaIni'] = $dataRequest->fechaInicial;
						$_POST['fechaFin'] =$dataRequest->fechaFin;
						$this->form_validation->set_rules('paginaActual', 'paginaActual',  'trim|xss_clean|required');
						$this->form_validation->set_rules('empresa', 'Empresa',  'trim|xss_clean|required');
						$this->form_validation->set_rules('cedula', 'cedula',  'trim|xss_clean|regex_match[/^[0-9]+$/]');
						$this->form_validation->set_rules('fechaIni', 'fechaIni', 'trim|xss_clean|required|regex_match[/^[0-9\/]+$/]');
						$this->form_validation->set_rules('fechaFin', 'fechaFin', 'trim|xss_clean|required|regex_match[/^[0-9\/]+$/]');

						$this->form_validation->set_error_delimiters('', '---');
							if ($this->form_validation->run() == FALSE)
							{
								log_message('DEBUG', 'NOVO VALIDATION ERRORS: '.json_encode(validation_errors()));
								$responseError = 'La combinación de caracteres es inválida';
								$responseError = $this->cryptography->encrypt($responseError);
								$this->output->set_content_type('application/json')->set_output(json_encode($responseError));
								return $responseError;
							}
							else
							{

									$empresa = $dataRequest->empresa;
									$fechaIni = $dataRequest->fechaInicial;
									$fechaFin = $dataRequest->fechaFin;
									$cedula = $dataRequest->cedula;
									$producto = $dataRequest->producto;
									$tipoConsulta = $dataRequest->tipoConsulta;
									$acrif = $dataRequest->acrif;
									$acnomcia = $dataRequest->acnomcia;
									$productoDesc = $dataRequest->productoDesc;
									$paginaActual = $dataRequest->paginaActual;

									$username = $this->session->userdata('userName');
									$token = $this->session->userdata('token');
									unset($_POST['paginaActual'], $_POST['empresa']);
									$pruebaTabla = $this->callWSEstadosDeCuenta($urlCountry,$token,$username,$empresa,$fechaIni,$fechaFin,$paginaActual,$producto,$cedula,$tipoConsulta);
									$pruebaTabla = $this->cryptography->encrypt($pruebaTabla);
									$this->output->set_content_type('application/json')->set_output(json_encode($pruebaTabla));
							}
					}
			}else{
					$this->session->sess_destroy();
					$responseError = ['ERROR' => lang('ERROR_(-29)'), "rc"=> "-29"];
					$responseError = $this->cryptography->encrypt($responseError);
					$this->output->set_content_type('application/json')->set_output(json_encode($responseError));
					//$this->output->set_content_type('application/json')->set_output(json_encode();
			}

	}

		/**
	 * Método que realiza petición al WS para obtener los datos del reporte de estados de cuenta.
	 *
	 * @param  string $urlCountry
	 * @param  string $token,$username,$empresa,$fechaIni,$fechaFin,$paginaActual,$producto,$cedula,$tipoConsulta
	 * @return JSON
	 */
	private function callWSEstadosDeCuenta($urlCountry,$token,$username,$empresa,$fechaIni,$fechaFin,$paginaActual,$producto,$cedula,$tipoConsulta){
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo="reportes";
			$function="movimientoEstadoCuentaDetalle";
			$operation="movimientoEstadoCuentaDetalle";
			$className="com.novo.objects.MO.EstadoCuentaMO";
			$timeLog= date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

			$data = array(
					"pais"=>$urlCountry,
					"idOperation" => $operation,
					"className" => $className,
					"idExtEmp" => $empresa,
					"idExtPer" => $cedula,
					"fechaIni" => $fechaIni,
					"fechaFin"=> $fechaFin,
					"tamanoPagina"=> "10",
					"tipoConsulta"=> $tipoConsulta,
					"pagActual" => $paginaActual,
					"prefix"=> $producto,
					"paginar" => "true",
					"logAccesoObject"=>$logAcceso,
					"token"=>$token
					);

			$data = json_encode($data,JSON_UNESCAPED_UNICODE);

			$dataEncry = np_Hoplite_Encryption($data, 'callWSEstadosDeCuenta');
			$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
			$data = json_encode($data);
			$response = np_Hoplite_GetWS($data);
			$jsonResponse = np_Hoplite_Decrypt($response, 'callWSEstadosDeCuenta');

			$response =  json_decode($jsonResponse);

			if($response){
					log_message('info','EstadosDeCuenta '.$response->rc."/".$response->msg);
					if($response->rc==0){
							return $response;
					}else{

							if($response->rc==-61 || $response->rc==-29){
									$codigoError = array('mensaje' => lang('ERROR_(-29)'), "rc"=> "-29");
									$this->session->sess_destroy();
									return $codigoError;

							}else{
									$codigoError = lang('ERROR_('.$response->rc.')');
									if(strpos($codigoError, 'Error')!==false){
											$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
									}else{
											$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
									}
									return $codigoError;
							}
					}

			}else{
					log_message('info','EstadosDeCuenta NO WS ');
					return $codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'));
			}
	}


		/**
	 * Método para exportar en formato PDF el reporte de actividad por usuario.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	public function expEstadosdeCuentaPDF($urlCountry){
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo    ="reportes";
			$function  ="generaArchivoPDF";
			$operation ="generaArchivoPDF";
			$className ="com.novo.objects.MO.EstadoCuentaMO";
			$timeLog   = date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$username = $this->session->userdata('userName');
			$token = $this->session->userdata('token');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');
			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPEDO");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){

					$empresa = $this->input->post('empresa');
					$fechaInicial = $this->input->post('fechaInicial');
					$fechaFin = $this->input->post('fechaFin');
					$producto = $this->input->post('producto');
					$cedula = $this->input->post('cedula');
					$paginaActual = $this->input->post('paginaActual');
					$tipoConsulta =$this->input->post('tipoConsulta');
					$nomEmpresa =$this->input->post('nomEmpresa');
					$descProducto = $this->input->post('descProducto');

					$data= array(
							"pais"=> $urlCountry,
							"idOperation"=> $operation,
							"className"=> $className,
							"idExtEmp"=> $empresa,
							"idExtPer"=> $cedula,
							"fechaIni"=> $fechaInicial,
							"fechaFin"=> $fechaFin,
							"tamanoPagina"=> "5",
							"pagActual"=> $paginaActual,
							"prefix"=> $producto,
							"tipoConsulta"=> $tipoConsulta,
							"paginar"=> "false",
							"nombreEmpresa"=>$nomEmpresa,
							"descProducto" => $descProducto,
							"logAccesoObject"=>$logAcceso,
							"token"=>$token
							);

					$data = json_encode($data,JSON_UNESCAPED_UNICODE);

					$dataEncry = np_Hoplite_Encryption($data, 'expEstadosdeCuentaPDF');
					$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
					$data = json_encode($data);
					$response = np_Hoplite_GetWS($data);
					$jsonResponse = np_Hoplite_Decrypt($response, 'expEstadosdeCuentaPDF');
					$response =  json_decode($jsonResponse);

					if($response){
							log_message('info','EstadosDeCuenta PDF '.$response->rc."/".$response->msg);
							if($response->rc==0){

									np_hoplite_byteArrayToFile($response->archivo,"pdf","EstadosDeCuenta");
							}else{

									if($response->rc==-61 || $response->rc==-29){
											$this->session->sess_destroy();

											echo "<script>alert('Usuario actualmente desconectado');
											window.history.back(-1);</script>";

									}else{
											$codigoError = lang('ERROR_('.$response->rc.')');
											if(strpos($codigoError, 'Error')!==false){
													$codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
											}else{
													$codigoError = array('ERROR' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
											}
											echo '<script languaje=\"javascript\">alert("'.$codigoError["ERROR"].'"); history.back();</script>';
											return $codigoError;
									}
							}
					}else{
							log_message('info','EstadosDeCuenta PDF NO WS ');
							echo "
							<script>
							alert('".lang('ERROR_GENERICO_USER')."');
							window.history.back(-1);
							</script>";
					}
			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}

	}

		/**
	 * Método para exportar en formato Excel el reporte de estados de cuenta.
	 *
	 * @param  string $urlCountry
	 * @param  string $email
	 * @return JSON
	 */
	public function expEstadosdeCuentaXLS($urlCountry){
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo    ="reportes";
			$function  ="generaArchivoXlsEdoCta";
			$operation ="generaArchivoXlsEdoCta";
			$className ="com.novo.objects.MO.EstadoCuentaMO";
			$timeLog   = date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$username = $this->session->userdata('userName');
			$token = $this->session->userdata('token');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');
			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPEDO");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){

					$empresa = $this->input->post('empresa');
					$fechaInicial = $this->input->post('fechaInicial');
					$fechaFin = $this->input->post('fechaFin');
					$producto = $this->input->post('producto');
					$cedula = $this->input->post('cedula');
					$paginaActual = $this->input->post('paginaActual');
					$tipoConsulta =$this->input->post('tipoConsulta');
					$nomEmpresa =$this->input->post('nomEmpresa');
					$descProducto = $this->input->post('descProducto');

					$data= array(
							"pais"=> $urlCountry,
							"idOperation"=> $operation,
							"className"=> $className,
							"idExtEmp"=> $empresa,
							"idExtPer"=> $cedula,
							"fechaIni"=> $fechaInicial,
							"fechaFin"=> $fechaFin,
							"tamanoPagina"=> "5",
							"pagActual"=> $paginaActual,
							"prefix"=> $producto,
							"tipoConsulta"=> $tipoConsulta,
							"paginar"=> "false",
							"nombreEmpresa"=>$nomEmpresa,
							"descProducto" => $descProducto,
							"logAccesoObject"=>$logAcceso,
							"token"=>$token
							);
					$data = json_encode($data,JSON_UNESCAPED_UNICODE);
					$dataEncry = np_Hoplite_Encryption($data, 'expEstadosdeCuentaXLS');
					$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
					$data = json_encode($data);
					$response = np_Hoplite_GetWS($data);
					$jsonResponse = np_Hoplite_Decrypt($response, 'expEstadosdeCuentaXLS');
					$response =  json_decode($jsonResponse);

					if($response){
							log_message('DEBUG','EstadosDeCuenta XLS '.$response->rc."/".$response->msg);
							if($response->rc==0){
									np_hoplite_byteArrayToFile($response->archivo,"xls","EstadosDeCuenta");
							}else{

									if($response->rc==-61 || $response->rc==-29){
											$this->session->sess_destroy();

											echo "<script>alert('Usuario actualmente desconectado');
											window.history.back(-1);</script>";

									}else{
											$codigoError = lang('ERROR_('.$response->rc.')');
											if(strpos($codigoError, 'Error')!==false){
													$codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
											}else{
													$codigoError = array('ERROR' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
											}
											echo '<script languaje=\"javascript\">alert("'.$codigoError["ERROR"].'"); history.back();</script>';
											return $codigoError;
									}
							}
					}else{
							log_message('info','EstadosDeCuenta XLS NO WS ');
							echo "
							<script>
							alert('".lang('ERROR_GENERICO_USER')."');
							window.history.back(-1);
							</script>";
					}
			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}
	}

		/**
	 * Método para emitir y exportar en formato PDF el comprobante
	 * de abono individual en el reporte de cuenta concentradora.
	 *
	 * @param  string $urlCountry
	 * @return bytes
	 */
	public function EstadosdeCuentaComprobante($urlCountry){
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo    ="reportes";
			$function  ="generarComprobante";
			$operation ="generarComprobante";
			$className ="com.novo.objects.MO.EstadoCuentaMO";
			$timeLog   = date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$username = $this->session->userdata('userName');
			$token = $this->session->userdata('token');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');
			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPEDO");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){

					$empresa = $this->input->get('empresa');
					$fechaInicial = $this->input->get('fechaInicial');
					$fechaFin = $this->input->get('fechaFin');
					$producto = $this->input->get('producto');
					$cedula = $this->input->get('cedula');
					$paginaActual = $this->input->get('paginaActual');
					$tipoConsulta =$this->input->get('tipoConsulta');
					$tarjeta = $this->input->get('tarjeta');
					$fecha = $this->input->get('fecha');
					$referencia = $this->input->get('referencia');
					$descripcion =$this->input->get('descripcion');
					$monto = $this->input->get('monto');
					$nomEmpresa =$this->input->get('nomEmpresa');
					$cliente = $this->input->get('cliente');

					$data= array(
							"pais"=> $urlCountry,
							"idOperation"=> $operation,
							"className"=> $className,
							"idExtPer"=> $cedula,
							"fechaIni"=> $fechaInicial,
							"fechaFin"=> $fechaFin,
							"tamanoPagina"=> "5",
							"pagActual"=> $paginaActual,
							"prefix"=> $producto,
							"tipoConsulta"=> $tipoConsulta,
							"paginar"=> "false",
							"listadoEstadosCuentas" =>array(array(
									"listaMovimientos"=>array(array(
											"tarjeta" => $tarjeta,
											"fecha"=>$fecha,
											"referencia"=>$referencia,
											"descripcion"=>$descripcion,
											"monto"=>$monto,
											"idExtEmp" =>$empresa,
											"nomEmpresa" => $nomEmpresa,
											"cliente" => $cliente
											))
									)),
							"logAccesoObject"=>$logAcceso,
							"token"=>$token
							);

					$data = json_encode($data,JSON_UNESCAPED_UNICODE);

					$dataEncry = np_Hoplite_Encryption($data, 'EstadosdeCuentaComprobante');
					$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
					$data = json_encode($data);
					$response = np_Hoplite_GetWS($data);
					$jsonResponse = np_Hoplite_Decrypt($response, 'EstadosdeCuentaComprobante');

					$response =  json_decode($jsonResponse);

					if($response){
							log_message('info','EstadosdeCuentaComprobante '.$response->rc."/".$response->msg);
							if($response->rc==0){
									np_hoplite_byteArrayToFile($response->archivo,"pdf","EstadosDeCuentaComprobante");
							}else{

									if($response->rc==-61 || $response->rc==-29){
											$this->session->sess_destroy();

											echo "<script>alert('Usuario actualmente desconectado');
											window.history.back(-1);</script>";

									}else{
											$codigoError = lang('ERROR_('.$response->rc.')');
											if(strpos($codigoError, 'Error')!==false){
													$codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
											}else{
													$codigoError = array('ERROR' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
											}
											echo '<script languaje=\"javascript\">alert("'.$codigoError["ERROR"].'"); history.back();</script>';
											return $codigoError;
									}
							}

					}else{
							log_message('info','EstadosdeCuentaComprobante NO WS');
							echo "
							<script>
							alert('".lang('ERROR_GENERICO_USER')."');
							window.history.back(-1);
							</script>";
					}

			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}
	}

		/**
	 * Método para emitir y exportar en formato PDF el comprobante
	 * de abono individual en el reporte de cuenta concentradora.
	 *
	 * @param  string $urlCountry
	 * @return bytes
	 */
	public function expEstadosdeCuentaComprobanteMasivo($urlCountry){
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo    ="reportes";
			$function  ="generarComprobante";
			$operation ="generarComprobante";
			$className ="com.novo.objects.MO.EstadoCuentaMO";
			$timeLog   = date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$username = $this->session->userdata('userName');
			$token = $this->session->userdata('token');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);

			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');
			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPEDO");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){

					$empresa = $this->input->post('empresa');
					$fechaInicial = $this->input->post('fechaInicial');
					$fechaFin = $this->input->post('fechaFin');
					$producto = $this->input->post('producto');
					$cedula = $this->input->post('cedula');
					$paginaActual = $this->input->post('paginaActual');
					$tipoConsulta =$this->input->post('tipoConsulta');
					$nomEmpresa =$this->input->post('nomEmpresa');
					$descProducto = $this->input->post('descProducto');

					$data= array(
							"pais"=> $urlCountry,
							"idOperation"=> $operation,
							"className"=> $className,
							"idExtEmp"=> $empresa,
							"idExtPer"=> $cedula,
							"fechaIni"=> $fechaInicial,
							"fechaFin"=> $fechaFin,
							"tamanoPagina"=> "5",
							"pagActual"=> $paginaActual,
							"prefix"=> $producto,
							"tipoConsulta"=> $tipoConsulta,
							"paginar"=> "false",
							"nombreEmpresa"=>$nomEmpresa,
							"descProducto" => $descProducto,
							"logAccesoObject"=>$logAcceso,
							"token"=>$token
							);
					$data = json_encode($data,JSON_UNESCAPED_UNICODE);

					$dataEncry = np_Hoplite_Encryption($data, 'expEstadosdeCuentaComprobanteMasivo');
					$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
					$data = json_encode($data);
					$response = np_Hoplite_GetWS($data);
					$jsonResponse = np_Hoplite_Decrypt($response, 'expEstadosdeCuentaComprobanteMasivo');

					$response =  json_decode($jsonResponse);

					if($response){
							log_message('info','EstadosdeCuentaComprobante MASIVO '.$response->rc."/".$response->msg);
							if($response->rc==0){

									np_hoplite_byteArrayToFile($response->archivo, "pdf", "EstadosdeCuentaComprobanteMasivo");
							}else{

									if($response->rc==-61 || $response->rc==-29){
										$this->session->sess_destroy();
										echo "<script>alert('Usuario actualmente desconectado');
										window.history.back(-1);</script>";

										}else{
											$codigoError = lang('ERROR_('.$response->rc.')');
											if(strpos($codigoError, 'Error')!==false){
													$codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
											}else{
													$codigoError = array('ERROR' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
											}
											echo '<script languaje=\"javascript\">alert("'.$codigoError["ERROR"].'"); history.back();</script>';
											return $codigoError;
									}
							}

					}else{
							log_message('info','EstadosdeCuentaComprobante MASIVO NO WS');
							echo "
							<script>
							alert('".lang('ERROR_GENERICO_USER')."');
							window.history.back(-1);
							</script>";
					}
			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}

	}

		/**
	 * Método para obtener los datos para mostrar el gráfico de estados de cuenta.
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	public function GraficoEstadosdeCuenta($urlCountry){
			np_hoplite_countryCheck($urlCountry);

			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');
			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPEDO");

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

				$empresa = $dataRequest->empresa;
				$fechaInicial = $dataRequest->fechaInicial;
				$fechaFin = $dataRequest->fechaFin;
				$producto = $dataRequest->producto;
				$cedula = $dataRequest->cedula;
				$paginaActual = $dataRequest->paginaActual;
				$tipoConsulta = $dataRequest->tipoConsulta;

				$response = $this->callWSGraficoEstadosdeCuenta($urlCountry,$empresa,$fechaInicial,$fechaFin,$producto,$cedula,$paginaActual,$tipoConsulta);
				$response = $this->cryptography->encrypt($response);
				$this->output->set_content_type("application/json")->set_output(json_encode($response));

			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}

	}

		/**
	 * Método que realiza petición al WS para obtener los datos para mostrar el gráfico de estados de cuenta
	 *
	 * @param  string $urlCountry
	 * @param  string $empresa,$fechaInicial,$fechaFin,$producto,$cedula,$paginaActual,$tipoConsulta
	 * @return JSON
	 */
	private function callWSGraficoEstadosdeCuenta($urlCountry,$empresa,$fechaInicial,$fechaFin,$producto,$cedula,$paginaActual,$tipoConsulta){
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo    ="reportes";
			$function  ="generarGraficoEstadoCuenta";
			$operation ="generarGraficoEstadoCuenta";
			$className ="com.novo.objects.MO.EstadoCuentaMO";
			$timeLog   = date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$username = $this->session->userdata('userName');
			$token = $this->session->userdata('token');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,$modulo,$function,$operation,0,$ip,$timeLog);


			$data= array(
					"pais"=> $urlCountry,
					"idOperation"=> $operation,
					"className"=> $className,
					"idExtEmp"=> $empresa,
					"idExtPer"=> $cedula,
					"fechaIni"=> $fechaInicial,
					"fechaFin"=> $fechaFin,
					"tamanoPagina"=> "5",
					"pagActual"=> $paginaActual,
					"prefix"=> $producto,
					"tipoConsulta"=> $tipoConsulta,
					"paginar"=> "false",
					"logAccesoObject"=>$logAcceso,
					"token"=>$token
					);

			$data = json_encode($data,JSON_UNESCAPED_UNICODE);
			$dataEncry = np_Hoplite_Encryption($data, 'callWSGraficoEstadosdeCuenta');
			$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
			$data = json_encode($data);
			$response = np_Hoplite_GetWS($data);
			$jsonResponse = np_Hoplite_Decrypt($response, 'callWSGraficoEstadosdeCuenta');
			$response = json_decode($jsonResponse);

			if($response){
					log_message('info','GraficoEstadosdeCuenta '.$response->rc."/".$response->msg);
					if($response->rc==0){
							return $response;
					}else{

							if($response->rc==-61 || $response->rc==-29){
									$codigoError = array('mensaje' => lang('ERROR_(-29)'), "rc"=> "-29");
									$this->session->sess_destroy();
							}else{
									$codigoError = lang('ERROR_('.$response->rc.')');
									if(strpos($codigoError, 'Error')!==false){
											$codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc);
									}else{
											$codigoError = array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
									}
							}
							return $codigoError;
					}

			}else{
					log_message('info','GraficoEstadosdeCuenta NO WS ');
					return $codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'));
			}


	}

	//------------------------------- Tarjetas Hambientes  -------------------------------------------

	/**
	 * Pantalla para la consulta de Tarjetas Hambientes.
	 *
	 * @param  string $urlCountry
	 */
	public function tarjetahabientes($urlCountry)
	{
			//VALIDATE COUNTRY
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('reportes');
			$this->lang->load('dashboard');
			$this->lang->load('users');
			$this->load->library('parser');
			$this->lang->load('erroreseol');
			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"TEBTHA");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){
					$nombreCompleto = $this->session->userdata('nombreCompleto');
					$lastSessionD = $this->session->userdata('lastSession');
					$FooterCustomInsertJS=["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js","aes.min.js","aes-json-format.min.js","header.js","jquery.balloon.min.js","jquery.dataTables.min.js","reportes/tarjetasHabientes.js","routes.js"];
					$FooterCustomJS="";
					$titlePage="Conexión Empresas Online - Reportes";

					$menuHeader = $this->parser->parse('widgets/widget-menuHeader',array(),TRUE);
					$menuFooter = $this->parser->parse('widgets/widget-menuFooter',array(),TRUE);

					$header = $this->parser->parse('layouts/layout-header',array('bodyclass'=>'','menuHeaderActive'=>TRUE,'menuHeaderMainActive'=>TRUE,'menuHeader'=>$menuHeader,'titlePage'=>$titlePage),TRUE);
					$footer = $this->parser->parse('layouts/layout-footer',array('menuFooterActive'=>TRUE,'menuFooter'=>$menuFooter,'FooterCustomInsertJSActive'=>TRUE,'FooterCustomInsertJS'=>$FooterCustomInsertJS,'FooterCustomJSActive'=>TRUE,'FooterCustomJS'=>$FooterCustomJS),TRUE);
					$content = $this->parser->parse('reportes/content-tarjetas-habientes',array(
							'titulo'=>$nombreCompleto,
							'breadcrum'=>'',
							'lastSession'=>$lastSessionD,
					),TRUE);
					$sidebarLotes= ($urlCountry == 'Ec-bp') ? '' :  $this->parser->parse('widgets/widget-publi-4',array('sidebarActive'=>TRUE),TRUE);
					$datos = array(
							'header'=>$header,
							'content'=>$content,
							'footer'=>$footer,
							'sidebar'=>$sidebarLotes,
					);

					$this->parser->parse('layouts/layout-b', $datos);
			}else{
					redirect($urlCountry.'/login/');
			}
	}

	//------------------------------- Reporte Guarderia  -------------------------------------------

	/**
	 * Pantalla para la consulta de Guardería Electrónica.
	 *
	 * @param  string $urlCountry
	 */
	public function guarderia($urlCountry)
	{

			//VALIDATE COUNTRY
			np_hoplite_countryCheck($urlCountry);

			$this->lang->load('reportes');
			$this->lang->load('dashboard');
			$this->lang->load('users');
			$this->load->library('parser');
			$this->lang->load('erroreseol');

			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPPGE");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){

					$nombreCompleto = $this->session->userdata('nombreCompleto');
					$lastSessionD = $this->session->userdata('lastSession');

					$FooterCustomInsertJS=["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js",
						"header.js","jquery.balloon.min.js","jquery.dataTables.min.js","aes.min.js","aes-json-format.min.js",
						"reportes/guarderia.js", "routes.js"];

					$FooterCustomJS="";
					$titlePage="Conexión Empresas Online - Reportes";

					$menuHeader = $this->parser->parse('widgets/widget-menuHeader',array(),TRUE);
					$menuFooter = $this->parser->parse('widgets/widget-menuFooter',array(),TRUE);

					$header = $this->parser->parse('layouts/layout-header',
						array('bodyclass'=>'','menuHeaderActive'=>TRUE,'menuHeaderMainActive'=>TRUE,
						'menuHeader'=>$menuHeader,'titlePage'=>$titlePage),TRUE);

					$footer = $this->parser->parse('layouts/layout-footer',
						array('menuFooterActive'=>TRUE,'menuFooter'=>$menuFooter,
								'FooterCustomInsertJSActive'=>TRUE,'FooterCustomInsertJS'=>$FooterCustomInsertJS,
								'FooterCustomJSActive'=>TRUE,'FooterCustomJS'=>$FooterCustomJS),TRUE);

					$content = $this->parser->parse('reportes/content-guarderia',array(
							'titulo'=>$nombreCompleto,
							'breadcrum'=>'',
							'lastSession'=>$lastSessionD,
							'riffGuarderia' =>$this->session->userdata('acrifS')
					),TRUE);

					$sidebarLotes= $this->parser->parse('widgets/widget-publi-4',
																array('sidebarActive'=>TRUE),TRUE);

					$datos = array(
							'header'=>$header,
							'content'=>$content,
							'footer'=>$footer,
							'sidebar'=>$sidebarLotes,
					);

					$this->parser->parse('layouts/layout-b', $datos);
			}else{
					redirect($urlCountry.'/login/');
			}
	}

	/**
	 * Método para obtener los datos para el reporte de las guarderias
	 *
	 * @param  string $urlCountry
	 * @return JSON
	 */
	public function getGuarderiaResult($urlCountry){

		$urlCountry= $this->input->post('pais');

			np_hoplite_countryCheck($urlCountry);
			$logged_in = $this->session->userdata('logged_in');
			$paisS = $this->session->userdata('pais');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPPGE");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){

					if($this->input->is_ajax_request()){

								$Fechaini = $this->input->post('Fechaini');
								$Fechafin = $this->input->post('Fechafin');
								$acrif = $this->session->userdata('acrifS');
								$username = $this->session->userdata('userName');
								$token = $this->session->userdata('token');
								$pruebaTabla = $this->callWSGuarderia( $urlCountry, $token, $username, $acrif, $Fechaini, $Fechafin );

								$this->output->set_content_type('application/json')->set_output(json_encode($pruebaTabla));
					}
			}else{
					$this->session->sess_destroy();

					$this->output->set_content_type('application/json')->set_output(json_encode( ));
			}

	}
	function callWSGuarderia( $urlCountry, $token, $username, $acrif, $Fechaini, $Fechafin ){

			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo="Reporte por Producto";
			$function="Consultar Guarderia";
			$operation="reporteGuarderiaElectronica";

			$className="com.novo.objects.TOs.RegistrosLoteGuarderiaTO";
			$timeLog= date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,
			$modulo,$function,$operation,0,$ip,$timeLog);

			$data = array(
					"idOperation" => $operation,
					"className" => $className,
					"id_ext_emp"=> $acrif,
					"fechaini" => $Fechaini,
					"fechafin" => $Fechafin,
					"logAccesoObject"=>$logAcceso,
					"token"=>$token,
					"pais"=>$urlCountry
			);

			$data = json_encode( $data, JSON_UNESCAPED_UNICODE);
			$dataEncry = np_Hoplite_Encryption( $data, 'callWSGuarderia');
			$data = array( 'bean' => $dataEncry, 'pais' =>$urlCountry );
			$data = json_encode($data);
			$response = np_Hoplite_GetWS($data);
			$jsonResponse = np_Hoplite_Decrypt($response, 'callWSGuarderia');
			$response =  json_decode($jsonResponse);
			$data1 = json_encode($response);

			if($response){
					log_message('info','Estatus Lotes '.$response->rc."/".$response->msg);
					if($response->rc==0){
							return $response;
					}else{
									if($response->rc==-61 || $response->rc==-29){

											$codigoError = array('mensaje' => lang('ERROR_(-29)'), "rc"=> "-29");
											$this->session->sess_destroy();
											return $codigoError;

									}else{

											$codigoError = lang('ERROR_('.$response->rc.')');

											$codigoError = (strpos($codigoError, 'Error')!==false)?
												array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc):
													array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);

											return $codigoError;
									}
					}

			}else{
					log_message('info','Estatus Lotes NO WS ');
					return $codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'));
			}
	}

	/**
	* Método para exportar en formato Excel el reporte completo de Guarderia.
	*
	* @param  string $urlCountry
	* @return JSON
	*/
	public function guarderiaExpXLS($urlCountry){
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo    ="reportes";
			$function  ="Consultar Guarderia";
			$operation ="generarArchivoXlsGuarderiaElectronica";
			$className ="com.novo.objects.TOs.RegistrosLoteGuarderiaTO";
			$timeLog   = date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$username = $this->session->userdata('userName');
			$token = $this->session->userdata('token');
			$logAcceso = np_hoplite_log($sessionId,$username,$canal,
												$modulo,$function,$operation,0,$ip,$timeLog);

			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');
			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPPGE");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){

					$Fechaini = $this->input->post('fechaini');
					$Fechafin = $this->input->post('fechafin');
					$acrif = $this->session->userdata('acrifS');
					$nombreEmpresa = $this->input->post('nombreEmpresa');

					$data = array(
							"pais" => $urlCountry,
							"idOperation"=>$operation,
							"className"=> $className,
							"id_ext_emp"=> $acrif,
							"fechaini"=> $Fechaini,
							"fechafin"=> $Fechafin,
							"nombreEmpresa"=> $nombreEmpresa,
							"logAccesoObject" => $logAcceso,
							"token"=> $token,
							);
					$data = json_encode($data,JSON_UNESCAPED_UNICODE);
					$dataEncry = np_Hoplite_Encryption($data, 'guarderiaExpXLS');
					$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
					$data = json_encode($data);
					$response = np_Hoplite_GetWS($data);
					$jsonResponse = np_Hoplite_Decrypt($response, 'guarderiaExpXLS');

					$response =  json_decode($jsonResponse);

					if($response){
							log_message('info','guarderia xls '.$response->rc."/".$response->msg);
							if($response->rc==0){
									np_hoplite_byteArrayToFile($response->archivo,"xls","XlsGuarderiaElectronica");
							}else{

									if($response->rc==-61 || $response->rc==-29){
											$this->session->sess_destroy();
											echo "<script>alert('Usuario actualmente desconectado');
											window.history.back(-1);</script>";

									}else{
											$codigoError = lang('ERROR_('.$response->rc.')');

											$codigoError = (strpos($codigoError, 'Error')!==false)?
											array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc):
												array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);

											echo '<script languaje=\"javascript\">'.
												'alert("'.$codigoError["mensaje"].'"); history.back();</script>';
											return $codigoError;
									}
							}

					}else{
						log_message('info','Guarderia xls no ws');
						echo "
						<script>
						alert('".lang('ERROR_GENERICO_USER')."');
						window.history.back(-1);
						</script>";
				}
		}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}

	}

	/**
	* Método para exportar en formato PDF el reporte de Guardería.
	*
	* @param  string $urlCountry
	* @return JSON
	*/
	public function guarderiaExpPDF($urlCountry){
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');//HOJA DE ERRORES;
			$canal = "ceo";
			$modulo    ="reportes";
			$function  ="Consultar Guarderia";
			$operation ="generarArchivoPDFGuarderiaElectronica";
			$className ="com.novo.objects.MOs.PlantillaGuarderiaMO";
			$timeLog   = date("m/d/Y H:i");
			$ip= $this->input->ip_address();
			$sessionId = $this->session->userdata('sessionId');
			$username = $this->session->userdata('userName');
			$token = $this->session->userdata('token');
			$logAcceso = np_hoplite_log($sessionId,$username,
								$canal,$modulo,$function,$operation,0,$ip,$timeLog);

			$logged_in = $this->session->userdata('logged_in');

			$paisS = $this->session->userdata('pais');
			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"REPPGE");

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){

				$Fechaini = $this->input->post('fechaini');
				$Fechafin = $this->input->post('fechafin');
				$acrif = $this->session->userdata('acrifS');
			$nombreEmpresa = $this->input->post('nombreEmpresa');

				$data = array(
						"pais" => $urlCountry,
						"idOperation"=>$operation,
						"className"=> $className,
						"id_ext_emp"=> $acrif,
						"fechaini"=> $Fechaini,
						"fechafin"=> $Fechafin,
							"nombreEmpresa"=> $nombreEmpresa,
						"logAccesoObject" => $logAcceso,
						"token"=> $token,
						);

					$data = json_encode($data,JSON_UNESCAPED_UNICODE);
					$dataEncry = np_Hoplite_Encryption($data, 'guarderiaExpPDF');
					$data = array('bean' => $dataEncry, 'pais' =>$urlCountry );
					$data = json_encode($data);
					$response = np_Hoplite_GetWS($data);
					$jsonResponse = np_Hoplite_Decrypt($response, 'guarderiaExpPDF');

					$response =  json_decode($jsonResponse);

					if($response){

						if($response->rc==0){
									np_hoplite_byteArrayToFile($response->archivo,"pdf","PDFGuarderiaElectronica");
							}else{

									if($response->rc==-61 || $response->rc==-29){
											$this->session->sess_destroy();
											echo "<script>alert('Usuario actualmente desconectado');
											window.history.back(-1);</script>";
									}else{
											$codigoError = lang('ERROR_('.$response->rc.')');
											$codigoError = (strpos($codigoError, 'Error')!==false)?
												array('mensaje' => lang('ERROR_GENERICO_USER'), "rc"=> $response->rc):
													array('mensaje' => lang('ERROR_('.$response->rc.')'), "rc"=> $response->rc);
												echo '<script languaje=\"javascript\">'.
													'alert("'.$codigoError["mensaje"].'"); history.back();</script>';
											return $codigoError;
									}
							}
					}else{
						log_message('info','guarderia pdf no ws');
						echo "
						<script>
						alert('".lang('ERROR_GENERICO_USER')."');
						window.history.back(-1);
						</script>";
					}
			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					redirect($urlCountry.'/login');
			}
	}
}
