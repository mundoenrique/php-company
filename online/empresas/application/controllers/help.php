<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Clase Help
 *
 * Clase para las operaciónes relacionadas al módulo Help
 *
 * @package     controllers
 * @author
 */

class Help extends CI_Controller
{
    //Atrinutos de la clase

		//Método constructor
		public function __construct()
    {
        parent:: __construct();
				//Add model
				$this->load->model('help_model', 'help');
				//Add languages
        $this->lang->load('users');
        $this->lang->load('erroreseol');
        //Add libraries
        $this->load->library('parser');
    }

    //Método que obtiene las categorias y sus funcionalidades
    public function functionalities()
    {
        //LLAMADA AL MODELO, IMPORTANTE
				$message1 = '1.- Controller ';
				$info = $this->help->get_functionalities($message1);
				$view = 'content';

				$this->loadView($info, $view);
    }
    /*---Fin método home----------------------------------------------------------------------------------------------*/

    //Método para obtener el detalle de una funcionalidad especifica
    public function functionality_detail()
    {
			$info = 'Controller functionality_detail() Procedure';
			$view = 'detail';

			$this->loadView($info, $view);
    }
    /*---Fin método home----------------------------------------------------------------------------------------------*/

    //Método para visualización de la vista
    private function loadView($info, $view)
    {
				np_hoplite_countryCheck('Ve');
        $titlePage= "Conexión Empresas Online - Guias";
        $FooterCustomJS="";
        $FooterCustomInsertJS=[];
				//INSTANCIA MENU HEADER
        $menuHeader = $this->parser->parse('widgets/widget-menuHeader',array(),TRUE);
        //INSTANCIA MENU FOOTER
				$menuFooter = $this->parser->parse('widgets/widget-menuFooter',array(),TRUE);
				$header = $this->parser->parse('layouts/layout-header',array('bodyclass'=>'full-width','menuHeaderActive'=>TRUE,'menuHeaderMainActive'=>TRUE,'menuHeader'=>$menuHeader,'titlePage'=>$titlePage),TRUE);
				$FooterCustomInsertJS=["jquery-1.10.2.min.js","jquery-ui-1.10.3.custom.min.js","jquery.balloon.min.js","jquery.paginate.js","jquery.isotope.min.js","header.js"];
				$footer = $this->parser->parse('layouts/layout-footer',array('menuFooterActive'=>TRUE,'menuFooter'=>$menuFooter,'FooterCustomInsertJSActive'=>TRUE,'FooterCustomInsertJS'=>$FooterCustomInsertJS,'FooterCustomJSActive'=>TRUE,'FooterCustomJS'=>$FooterCustomJS),TRUE);
				if ($view == 'content') {
					$content = $this->parser->parse('help/content-help',array('info'=>$info),TRUE);
				} else {
					$content = $this->parser->parse('help/help-detail',array('info'=>$info),TRUE);
				}

				$datos = array(
					'header'=>$header,
					'content'=>$content,
					'footer'=>$footer,
					'sidebarActive'=>FALSE,
					'titleHeading' => 'TITULO ACA',
					'login' => 'LOGIN USUARIO',
					'password' => 'CONTRASEÑA',
					'loginBtn' => 'ENTRAR',
				);

		$this->parser->parse('layouts/layout-a', $datos);
    }
    /*---Fin método para visuazación de la vista----------------------------------------------------------------------*/
}
