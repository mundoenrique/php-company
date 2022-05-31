<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Clase Footer
 *
 * Esta clase realiza las operaciónes relacionadas al usuario como:
 * login, logout, cambio de clave y todo el módulo de configuración.
 *
 * @package    controllers
 * @author     Wilmer Rojas <rojaswilmer@gmail.com>
 * @author     Carla García <neiryerit@gmail.com>
*/
class Footer extends CI_Controller {

	/**
	 * Pantalla donde se visualizan los beneficios que ofrece el portal Conexión Empresas
	 * @param  string] $urlCountry
	 */
	public function pantallaBeneficios($urlCountry){
		np_hoplite_countryCheck($urlCountry);

		$this->lang->load('users');
		$this->lang->load('dashboard');

		$this->load->library('parser');
		$logged_in = $this->session->userdata('logged_in');

		$menu;

		if($logged_in){
			$menu=true;
		}else{
			$menu=false;
		}

       	$username = $this->session->userdata('userName');
       	$token = $this->session->userdata('token');

		$FooterCustomInsertJS=["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js","jquery.balloon.min.js","header.js","routes.js"];
	  $FooterCustomJS="";
	  $titlePage="Conexión Empresas Online - Beneficios";

		$menuHeader = $this->parser->parse('widgets/widget-menuHeader',array(),TRUE);
		$menuFooter = $this->parser->parse('widgets/widget-menuFooter',array(),TRUE);
		$header = $this->parser->parse('layouts/layout-header',array('bodyclass'=>'','menuHeaderActive'=>$menu,'menuHeaderMainActive'=>$menu,'menuHeader'=>$menuHeader,'titlePage'=>$titlePage),TRUE);
		$footer = $this->parser->parse('layouts/layout-footer',array('menuFooterActive'=>TRUE,'menuFooter'=>$menuFooter,'FooterCustomInsertJSActive'=>$menu,'FooterCustomInsertJS'=>$FooterCustomInsertJS,'FooterCustomJSActive'=>TRUE,'FooterCustomJS'=>$FooterCustomJS),TRUE);

		$content = $this->parser->parse('footerpages/beneficios',array(
			'breadcrum'=>''
			),TRUE);
			$sidebarLotes= $this->parser->parse('dashboard/widget-empresa',array('sidebarActive'=>false),TRUE);

		$datos = array(
			'header'=>$header,
			'content'=>$content,
			'footer'=>$footer,
		 			'sidebar'=>$sidebarLotes,
		 			'titleHeading' => 'TITULO ACA',
					'login' => 'LOGIN USUARIO',
					'password' => 'CONTRASEÑA',
					'loginBtn' => 'ENTRAR',
					'pais' => $urlCountry
			         );

		$this->parser->parse('layouts/layout-b', $datos);

	}


	/**
	 * Pantalla donde se visualiza el texto con los términos, condiciones de uso y privacidad del portal.
	 * @param  string] $urlCountry
	 */
	public function pantallaCondiciones($urlCountry){
		np_hoplite_countryCheck($urlCountry);

		$this->lang->load('users');
		$this->lang->load('dashboard');

		$this->load->library('parser');
		$logged_in = $this->session->userdata('logged_in');

		$menu;

		if($logged_in){
			$menu=true;
		}else{
			$menu=false;
		}

       	$username = $this->session->userdata('userName');
       	$token = $this->session->userdata('token');


			$FooterCustomInsertJS=["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js","jquery.balloon.min.js","header.js","routes.js"];
	    $FooterCustomJS="";
	    $titlePage="Conexión Empresas Online - Condiciones";

		$menuHeader = $this->parser->parse('widgets/widget-menuHeader',array(),TRUE);
		$menuFooter = $this->parser->parse('widgets/widget-menuFooter',array(),TRUE);
		$header = $this->parser->parse('layouts/layout-header',array('bodyclass'=>'','menuHeaderActive'=>$menu,'menuHeaderMainActive'=>$menu,'menuHeader'=>$menuHeader,'titlePage'=>$titlePage),TRUE);
		$footer = $this->parser->parse('layouts/layout-footer',array('menuFooterActive'=>TRUE,'menuFooter'=>$menuFooter,'FooterCustomInsertJSActive'=>$menu,'FooterCustomInsertJS'=>$FooterCustomInsertJS,'FooterCustomJSActive'=>TRUE,'FooterCustomJS'=>$FooterCustomJS),TRUE);

		$content = $this->parser->parse('footerpages/condiciones',array(
			'breadcrum'=>''
			),TRUE);
			$sidebarLotes= $this->parser->parse('dashboard/widget-empresa',array('sidebarActive'=>false),TRUE);

		$datos = array(
		 			'header'=>$header,
		 			'content'=>$content,
		 			'footer'=>$footer,
		 			'sidebar'=>$sidebarLotes,
		 			'titleHeading' => 'TITULO ACA',
		            'login' => 'LOGIN USUARIO',
				    'password' => 'CONTRASEÑA',
				    'loginBtn' => 'ENTRAR',
				    'pais' => $urlCountry
			         );

		$this->parser->parse('layouts/layout-b', $datos);

	}

	public function pantallaTarifas($urlCountry){
		np_hoplite_countryCheck($urlCountry);

		$this->lang->load('users');
		$this->lang->load('dashboard');

		$this->load->library('parser');
		$logged_in = $this->session->userdata('logged_in');

		$menu;

		if($logged_in){
			$menu=true;
		}else{
			$menu=false;
		}

       	$username = $this->session->userdata('userName');
       	$token = $this->session->userdata('token');
				 $cssTarifas=["tarifas.css"];
		$FooterCustomInsertJS=["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js","jquery.balloon.min.js","header.js","routes.js"];
	    $FooterCustomJS="";
			$titlePage="Conexión Empresas Online - Tarifas";

		$menuHeader = $this->parser->parse('widgets/widget-menuHeader',array(),TRUE);
		$menuFooter = $this->parser->parse('widgets/widget-menuFooter',array(),TRUE);
		$header = $this->parser->parse('layouts/layout-header',array('bodyclass'=>'','menuHeaderActive'=>$menu,'menuHeaderMainActive'=>$menu,'menuHeader'=>$menuHeader,'titlePage'=>$titlePage,'css'=>$cssTarifas),TRUE);
		$footer = $this->parser->parse('layouts/layout-footer',array('menuFooterActive'=>TRUE,'menuFooter'=>$menuFooter,'FooterCustomInsertJSActive'=>$menu,'FooterCustomInsertJS'=>$FooterCustomInsertJS,'FooterCustomJSActive'=>TRUE,'FooterCustomJS'=>$FooterCustomJS),TRUE);

		$content = $this->parser->parse('footerpages/tarifas',array(
			'breadcrum'=>''
			),TRUE);
			$sidebarLotes= $this->parser->parse('dashboard/widget-empresa',array('sidebarActive'=>false),TRUE);

		$datos = array(
		 			'header'=>$header,
		 			'content'=>$content,
		 			'footer'=>$footer,
		 			'sidebar'=>$sidebarLotes,
		 			'titleHeading' => 'TITULO ACA',
		            'login' => 'LOGIN USUARIO',
				    'password' => 'CONTRASEÑA',
				    'loginBtn' => 'ENTRAR',
				    'pais' => $urlCountry
			         );

		$this->parser->parse('layouts/layout-b', $datos);

	}
}
