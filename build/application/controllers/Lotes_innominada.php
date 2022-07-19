<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Clase Lotes
 *
 * Esta clase realiza todas las operaciones relacionadas con la gestión de lotes.
 * tales como: carga, confirmación, autorización, calculo y reproceso
 *
 * @package    controllers
 * @author     Wilmer Rojas <rojaswilmer@gmail.com>
 * @author     Carla García <neiryerit@gmail.com>
 */
class Lotes_innominada extends CI_Controller {

	public function pantallaInno($urlCountry)
	{
			//VALIDATE COUNTRY
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('dashboard');
			$this->lang->load('lotes');
			$this->lang->load('users');
			$this->load->library('parser');
			$this->lang->load('erroreseol');
			$this->load->model('users_model');
			$this->load->model('innominadas_model');

			$logged_in = $this->session->userdata('logged_in');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"TICARG");

			$paisS = $this->session->userdata('pais');

			if($paisS==$urlCountry && $logged_in && $moduloAct!==false){

					$nombreCompleto = $this->session->userdata('nombreCompleto');
					$lastSessionD = $this->session->userdata('lastSession');
					$jsRte = '../../../js/';
					$thirdsJsRte = '../../../js/third_party/';
					$FooterCustomInsertJS=["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js","jquery.balloon.min.js","aes.min.js","aes-json-format.min.js","dashboard/widget-empresa.js","header.js","jquery.dataTables.min.js","lotes/lotes-innominada.js","jquery-md5.js","routes.js",$thirdsJsRte."jquery.validate.min.js",$jsRte."validate-forms.js",$thirdsJsRte."additional-methods.min.js"];
					$FooterCustomJS="";
					$titlePage="Conexión Empresas Online - Solicitud Innominadas";
					$programa = $this->session->userdata('nombreProductoS').' / '. $this->session->userdata('marcaProductoS') ;
					$acidlote=$this->input->post('data-idTicket');
					$acodcia = $this->session->userdata('accodciaS');

					$cestatus="3";
					$exoboolean="S";
					$acnumlote="";
					$dtfechorcargaIni="";
					$dtfechorcargaFin="";

					/*$listaCuentasInno = $this->innominadas_model->callWSListaInnominadasEnProc($cestatus, $exoboolean, $acnumlote, $dtfechorcargaIni, $dtfechorcargaFin);
					$listaCuentasInno = serialize($listaCuentasInno);*/

					$menuHeader = $this->parser->parse('widgets/widget-menuHeader',array(),TRUE);
					$menuFooter = $this->parser->parse('widgets/widget-menuFooter',array(),TRUE);
					$header = $this->parser->parse('layouts/layout-header',array('bodyclass'=>'','menuHeaderActive'=>TRUE,'menuHeaderMainActive'=>TRUE,'menuHeader'=>$menuHeader,'titlePage'=>$titlePage),TRUE);
					$footer = $this->parser->parse('layouts/layout-footer',array('menuFooterActive'=>TRUE,'menuFooter'=>$menuFooter,'FooterCustomInsertJSActive'=>TRUE,'FooterCustomInsertJS'=>$FooterCustomInsertJS,'FooterCustomJSActive'=>TRUE,'FooterCustomJS'=>$FooterCustomJS),TRUE);
					$content = $this->parser->parse('lotes/content-innolotes',array(
							'acidlote'=>$acidlote,
							'acodcia'=>$acodcia,
							'titulo'=>$nombreCompleto,
							'lastSession'=>$lastSessionD,
							'programa'=>$programa,
							'pais' => $urlCountry,
							'mesesVencimiento' => $this->session->userdata('mesesVencimiento'),
							'maxTarjetas' => $this->session->userdata('maxTarjetas')
							//'data' => $rtest
					),TRUE);
					$sidebarLotes= $this->parser->parse('dashboard/widget-empresa',array('sidebarActive'=>TRUE),TRUE);

					$datos = array(
							'header'=>$header,
							'content'=>$content,
							'footer'=>$footer,
							'sidebar'=>$sidebarLotes,
							'aviso' =>TRUE,
							'titleHeading' => 'TITULO ACA',
							'login' => 'LOGIN USUARIO',
							'password' => 'CONTRASEÑA',
							'loginBtn' => 'ENTRAR',
							'pais' => $urlCountry
					);

					$this->parser->parse('layouts/layout-b', $datos);
			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					echo "
		<script>alert('Enlace no permitido'); location.href = '".$this->config->item('base_url')."$urlCountry/login';</script>
		";
			}

	}

	public function pantallaInnoInven($urlCountry)
	{
			//VALIDATE COUNTRY
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('dashboard');
			$this->lang->load('consultas');
			$this->lang->load('lotes');
			$this->lang->load('users');
			$this->load->library('parser');
			$this->lang->load('erroreseol');
			$this->load->model('innominadas_model');

			$logged_in = $this->session->userdata('logged_in');
			$idProductoS = $this->session->userdata('idProductoS');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"TIINVN");

			$paisS = $this->session->userdata('pais');

			if($paisS==$urlCountry && $logged_in && isset($idProductoS) && $moduloAct!==false){

					$nombreCompleto = $this->session->userdata('nombreCompleto');
					$lastSessionD = $this->session->userdata('lastSession');
					$FooterCustomInsertJS=["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js","jquery.balloon.min.js","dashboard/widget-empresa.js","header.js","jquery-md5.js","jquery.dataTables.min.js","aes.min.js","aes-json-format.min.js","lotes/lotes-innominada_inventario.js","routes.js"];
					$FooterCustomJS="";
					$titlePage="Conexión Empresas Online - Lotes";
					$idProductoS = $this->session->userdata('idProductoS');
					$idEmpresa = $this->session->userdata('acrifS');
					$programa = $this->session->userdata('nombreProductoS').' / '. $this->session->userdata('marcaProductoS') ;

					$acidlote=$this->input->post('data-idTicket');
					//$rtest[] = $this->callWSverDetalleBandeja($urlCountry,$acidlote);
					//
					$cestatus="4";
					$exoboolean="";
					$acnumlote=$this->input->post('data-numlote');
					$dtfechorcargaIni=$this->input->post('data-fecha_inicial');
					$dtfechorcargaFin=$this->input->post('data-fecha_final');

					$listaCuentasInno = $this->innominadas_model->callWSListaInnominadasEnProc($urlCountry, $cestatus, $exoboolean, $acnumlote, $dtfechorcargaIni, $dtfechorcargaFin);
					$listaCuentasInno = serialize($listaCuentasInno);
					//$listaCuentasInno = "";

					$menuHeader = $this->parser->parse('widgets/widget-menuHeader',array(),TRUE);
					$menuFooter = $this->parser->parse('widgets/widget-menuFooter',array(),TRUE);
					$header = $this->parser->parse('layouts/layout-header',array('bodyclass'=>'','menuHeaderActive'=>TRUE,'menuHeaderMainActive'=>TRUE,'menuHeader'=>$menuHeader,'titlePage'=>$titlePage),TRUE);
					$footer = $this->parser->parse('layouts/layout-footer',array('menuFooterActive'=>TRUE,'menuFooter'=>$menuFooter,'FooterCustomInsertJSActive'=>TRUE,'FooterCustomInsertJS'=>$FooterCustomInsertJS,'FooterCustomJSActive'=>TRUE,'FooterCustomJS'=>$FooterCustomJS),TRUE);
					$content = $this->parser->parse('lotes/content-inventarioinnolotes',array(
							'titulo'=>$nombreCompleto,
							'lastSession'=>$lastSessionD,
							'programa'=>$programa,
							'data1' => $listaCuentasInno
					),TRUE);
					$sidebarLotes= $this->parser->parse('dashboard/widget-empresa',array('sidebarActive'=>TRUE),TRUE);

					$datos = array(
							'header'=>$header,
							'content'=>$content,
							'footer'=>$footer,
							'sidebar'=>$sidebarLotes,
							'titleHeading' => 'TITULO ACA',
							'login' => 'LOGIN USUARIO',
							'password' => 'CONTRASEÑA',
							'loginBtn' => 'ENTRAR',
					);

					$this->parser->parse('layouts/layout-b', $datos);
			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					echo "
		<script>alert('Enlace no permitido'); location.href = '".$this->config->item('base_url')."$urlCountry/login';</script>
		";
			}

	}

	public function pantallaDetalleInnoLote($urlCountry)
	{
			//VALIDATE COUNTRY
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('dashboard');
			$this->lang->load('lotes');
			$this->lang->load('users');
			$this->load->library('parser');
			$this->lang->load('erroreseol');
			$this->load->model('innominadas_model');

			$logged_in = $this->session->userdata('logged_in');
			$idProductoS = $this->session->userdata('idProductoS');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$moduloAct = np_hoplite_existeLink($menuP,"TEBCAR");

			$paisS = $this->session->userdata('pais');

			if($paisS==$urlCountry && $logged_in && isset($idProductoS)){ //&& $moduloAct!==false

					$nombreCompleto = $this->session->userdata('nombreCompleto');
					$lastSessionD = $this->session->userdata('lastSession');
					$FooterCustomInsertJS=["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js","jquery.balloon.min.js","dashboard/widget-empresa.js","header.js","jquery-md5.js","jquery.dataTables.min.js","aes.min.js","aes-json-format.min.js","lotes/lotes-innominada_detalle.js","routes.js"];
					$FooterCustomJS="";
					$titlePage="Conexión Empresas Online - Lotes";
					$idProductoS = $this->session->userdata('idProductoS');
					$idEmpresa = $this->session->userdata('acrifS');
					$programa = $this->session->userdata('nombreProductoS').' / '. $this->session->userdata('marcaProductoS') ;

					$acidlote=$this->input->post('data-idTicket');
					//$rtest[] = $this->callWSverDetalleBandeja($urlCountry,$acidlote);
					//
					$cestatus="4";
					$exoboolean="";
					$numLote=$this->input->post('data-numlote');

					$acrif=$this->input->post('data-acrif');
					$acnomcia=$this->input->post('data-acnomcia');
					$dtfechorcarga=$this->input->post('data-dtfechorcarga');
					$nmonto=$this->input->post('data-nmonto');

					$listaTarjetasInnominadas = $this->innominadas_model->callWSListaTarjetasInnominadas($urlCountry, $numLote);
					if($listaTarjetasInnominadas != ""){
							$listaTarjetasInnominadas = serialize($listaTarjetasInnominadas);
					} else {
							$listaTarjetasInnominadas = "";
					}

					$menuHeader = $this->parser->parse('widgets/widget-menuHeader',array(),TRUE);
					$menuFooter = $this->parser->parse('widgets/widget-menuFooter',array(),TRUE);
					$header = $this->parser->parse('layouts/layout-header',array('bodyclass'=>'','menuHeaderActive'=>TRUE,'menuHeaderMainActive'=>TRUE,'menuHeader'=>$menuHeader,'titlePage'=>$titlePage),TRUE);
					$footer = $this->parser->parse('layouts/layout-footer',array('menuFooterActive'=>TRUE,'menuFooter'=>$menuFooter,'FooterCustomInsertJSActive'=>TRUE,'FooterCustomInsertJS'=>$FooterCustomInsertJS,'FooterCustomJSActive'=>TRUE,'FooterCustomJS'=>$FooterCustomJS),TRUE);
					$content = $this->parser->parse('lotes/content-detalleinnolotes',array(
							'titulo'=>$nombreCompleto,
							'lastSession'=>$lastSessionD,
							'programa'=>$programa,
							'data1'=>$listaTarjetasInnominadas,
							'numLote'=>$numLote,
							'acrif'=>$acrif,
							'acnomcia'=>$acnomcia,
							'dtfechorcarga'=>$dtfechorcarga,
							'nmonto'=>number_format((float)$nmonto, 2, '.', '')
					),TRUE);
					$sidebarLotes= $this->parser->parse('dashboard/widget-empresa',array('sidebarActive'=>TRUE),TRUE);

					$datos = array(
							'header'=>$header,
							'content'=>$content,
							'footer'=>$footer,
							'sidebar'=>$sidebarLotes,
							'titleHeading' => 'TITULO ACA',
							'login' => 'LOGIN USUARIO',
							'password' => 'CONTRASEÑA',
							'loginBtn' => 'ENTRAR',
					);

					$this->parser->parse('layouts/layout-b', $datos);
			}elseif($paisS!=$urlCountry && $paisS!=""){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}else{
					echo "
		<script>alert('Enlace no permitido'); location.href = '".$this->config->item('base_url')."$urlCountry/login';</script>
		";
			}

	}

	public function createCuentasInnominadas($urlCountry)
	{
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');
			$this->load->library('parser');
			$this->load->model('innominadas_model');

			$menuP =$this->session->userdata('menuArrayPorProducto');

			$moduloAct = np_hoplite_existeLink($menuP,"TEBGUR");

			$logged_in = $this->session->userdata('logged_in');
			$paisS = $this->session->userdata('pais');

			if($paisS==$urlCountry && $logged_in){
					//if ( $moduloAct!==false) {

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
					$cantReg = $dataRequest->data_cant;
					//$idEmpresa = $dataRequest->data_empresa;
					$idEmpresa = (isset($dataRequest->data_empresa))? $dataRequest->data_empresa :"";
					//$monto = $dataRequest->data_monto;
					$monto = (isset($dataRequest->data_monto))? $dataRequest->data_monto : "";
					$lembozo1 = $dataRequest->data_lembozo1;
					$lembozo2 = (isset($dataRequest->data_lembozo2))? $dataRequest->data_lembozo2 : "";
					$codSucursal = $dataRequest->data_codsucursal;
					$password = (isset($dataRequest->data_password))? $dataRequest->data_password : "";
					$fechaExp = $dataRequest->data_fechaexp;
					$password = (isset($dataRequest->data_password))? $dataRequest->data_password : "";
					$response = $this->innominadas_model->callWSCreateInnominadas($urlCountry, $cantReg, $monto, $lembozo1, $lembozo2, $codSucursal, $password, $fechaExp);
					/*}else{
							$response = array("ERROR"=>lang('SIN_FUNCION'));
					}*/
					$response = $this->cryptography->encrypt($response);
					$this->output->set_content_type('application/json')->set_output(json_encode($response,JSON_UNESCAPED_UNICODE));

			}elseif($paisS!=$urlCountry&& $paisS!=''){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}elseif($this->input->is_ajax_request()){
					$response = $this->cryptography->encrypt(array('ERROR' => '-29' ));
					$this->output->set_content_type('application/json')->set_output(json_encode($response));
			}else{
					redirect($urlCountry.'/login');
			}
	}

	public function listaSucursalesInnominadas($urlCountry)
	{

			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');
			$this->load->library('parser');
			$this->load->model('users_model');

			$menuP =$this->session->userdata('menuArrayPorProducto');
			$idEmpresa = $this->session->userdata('acrifS');

			$moduloAct = np_hoplite_existeLink($menuP,"TEBGUR");

			$logged_in = $this->session->userdata('logged_in');
			$paisS = $this->session->userdata('pais');

			if($paisS==$urlCountry && $logged_in){

					$response = $this->users_model->callWSConsultarSucursales($urlCountry, $idEmpresa, '1', '10', true);
					$response = $this->cryptography->encrypt($response);
					$this->output->set_content_type('application/json')->set_output(json_encode($response,JSON_UNESCAPED_UNICODE));

			}elseif($paisS!=$urlCountry&& $paisS!=''){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}elseif($this->input->is_ajax_request()){
					$this->output->set_content_type('application/json')->set_output(json_encode( array('ERROR' => '-29' )));
			}else{
					redirect($urlCountry.'/login');
			}
	}

	public function listaCuentasInnominadas($urlCountry)
	{
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');
			$this->load->library('parser');
			$this->load->model('innominadas_model');

			$menuP =$this->session->userdata('menuArrayPorProducto');

			$moduloAct = np_hoplite_existeLink($menuP,"TEBGUR");

			$logged_in = $this->session->userdata('logged_in');
			$paisS = $this->session->userdata('pais');
			$cestatus=$this->input->post('data-cestatus');
			$exoboolean=$this->input->post('data-exoboolean');
			$acnumlote=$this->input->post('data-numlote');
			$dtfechorcargaIni=$this->input->post('data-fecha_inicial');
			$dtfechorcargaFin=$this->input->post('data-fecha_final');

			if($paisS==$urlCountry && $logged_in){
					//if ( $moduloAct!==false) {
					$response = $this->innominadas_model->callWSListaInnominadasEnProc($urlCountry, $cestatus, $exoboolean, $acnumlote, $dtfechorcargaIni, $dtfechorcargaFin);
					//}else{
					//$response = array("ERROR"=>lang('SIN_FUNCION'));
					//}
					$this->output->set_content_type('application/json')->set_output(json_encode($response,JSON_UNESCAPED_UNICODE));

			}elseif($paisS!=$urlCountry&& $paisS!=''){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}elseif($this->input->is_ajax_request()){
					$this->output->set_content_type('application/json')->set_output(json_encode( array('ERROR' => '-29' )));
			}else{
					redirect($urlCountry.'/login');
			}
	}

	public function listaTarjetasInnominadas($urlCountry){
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');
			$this->load->library('parser');
			$this->load->model('innominadas_model');

			$menuP =$this->session->userdata('menuArrayPorProducto');

			$moduloAct = np_hoplite_existeLink($menuP,"TEBGUR");

			$logged_in = $this->session->userdata('logged_in');
			$paisS = $this->session->userdata('pais');
			$numLote = $this->input->post('data-numlote');
			$idExtEmp = $this->input->post('data-idextemp');

			if($paisS==$urlCountry && $logged_in){
					//if ( $moduloAct!==false) {
					$response = $this->innominadas_model->callWSListaTarjetasInnominadas($urlCountry, $numLote);
					//}else{
					//$response = array("ERROR"=>lang('SIN_FUNCION'));
					//}
					$this->output->set_content_type('application/json')->set_output(json_encode($response,JSON_UNESCAPED_UNICODE));

			}elseif($paisS!=$urlCountry&& $paisS!=''){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}elseif($this->input->is_ajax_request()){
					$this->output->set_content_type('application/json')->set_output(json_encode( array('ERROR' => '-29' )));
			}else{
					redirect($urlCountry.'/login');
			}
	}

	public function generarReporteTarjetasInnominadas($urlCountry){
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');
			$this->load->library('parser');
			$this->load->model('innominadas_model');

			$menuP =$this->session->userdata('menuArrayPorProducto');

			$moduloAct = np_hoplite_existeLink($menuP,"TEBGUR");

			$logged_in = $this->session->userdata('logged_in');
			$paisS = $this->session->userdata('pais');

			if($paisS==$urlCountry && $logged_in){
					//if ( $moduloAct!==false) {
					$numlote=$this->input->post('data-numlote');

					$response = $this->innominadas_model->callWSReporteInnominadas($urlCountry, $numlote);
					log_message('info','inno_report_xls antes de enviar a la vista ======> '.json_encode($response, JSON_UNESCAPED_UNICODE));
					if (isset($response->nombre)) {
							np_hoplite_byteArrayToFile($response->archivo,"xls",$response->nombre);
					} else {
							echo "<script>alert('No fue posible generar el archivo, intenta más tarde'); window.history.go(-2);</script>";
					}

			} elseif($paisS!=$urlCountry&& $paisS!=''){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			} elseif($this->input->is_ajax_request()){
					$this->output->set_content_type('application/json')->set_output(json_encode( array('ERROR' => '-29' )));
			} else{
					redirect($urlCountry.'/login');
			}
	}

	public function eliminarLotesInnominadas($urlCountry){
			np_hoplite_countryCheck($urlCountry);
			$this->lang->load('erroreseol');
			$this->load->library('parser');
			$this->load->model('innominadas_model');

			$menuP =$this->session->userdata('menuArrayPorProducto');

			$moduloAct = np_hoplite_existeLink($menuP,"TEBGUR");

			$logged_in = $this->session->userdata('logged_in');
			$paisS = $this->session->userdata('pais');

			$pass=$this->input->post('data-pass');
			$idlote=$this->input->post('data-idlote');
			$numlote=$this->input->post('data-numlote');

			if($paisS==$urlCountry && $logged_in){
					//if ( $moduloAct!==false) {
					$response = $this->innominadas_model->callWSEliminarInnominadas($urlCountry, $pass, $idlote,$numlote);
					$this->output->set_content_type('application/json')->set_output(json_encode($response,JSON_UNESCAPED_UNICODE));
					//}else{
					//$response = array("ERROR"=>lang('SIN_FUNCION'));
					//}

			}elseif($paisS!=$urlCountry&& $paisS!=''){
					$this->session->sess_destroy();
					redirect($urlCountry.'/login');
			}elseif($this->input->is_ajax_request()){
					$this->output->set_content_type('application/json')->set_output(json_encode( array('ERROR' => '-29' )));
			}else{
					redirect($urlCountry.'/login');
			}
	}
}
