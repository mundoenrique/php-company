<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Clase Guides
 *
 * Clase para las operaciónes relacionadas al módulo de Ayuda
 *
 * @package     controllers
 * @author
 */
class Guides extends CI_Controller
{
  //Método constructor
  public function __construct()
  {
    parent::__construct();
    //Add country
    np_hoplite_countryCheck('Ve');
    //Add model
    $this->load->model('guides_model', 'guides');
    //Add languages
    $this->lang->load('users');
    $this->lang->load('dashboard');
    $this->lang->load('erroreseol');
    //Add libraries
    $this->load->library('parser');
    //Get session and country
    $this->logged_in = $this->session->userdata('logged_in');
    $this->pais = $this->session->userdata('pais');
    //Get URL country
    $this->urlCountry = $this->uri->segment(1, 0);
    //add css
    $this->addCss = [
      "guides/style.css"
    ];

    $this->userCheck();
  }

  private function userCheck()
  {
    //Verificar país
    np_hoplite_countryCheck($this->urlCountry);
    if ($this->pais != $this->urlCountry || !$this->logged_in) {
      $this->withoutAccess();
    }
  }

  //get all categories and all titles
  public function all_guides()
  {
    $info = new stdClass();
    $info->categories = $this->guides->get_categories();
    $info->guides = $this->guides->get_guides();

    $view = 'content';

    $this->loadView($info, $view);
  }
  /*---Fin método home----------------------------------------------------------------------------*/

  //Método que obtiene las categorias y sus funcionalidades
  public function category_guides($urlBase, $category)
  {
    $info = new stdClass();
    $info->categories = $this->guides->get_categories();
    $info->guides = $this->guides->get_category_guides($category);

    $view = 'content';

    $this->loadView($info, $view);
  }
  /*---Fin método home-----------------------------------------------------------------------------*/

  //Método para obtener el detalle de una funcionalidad especifica
  public function guides_detail($url, $title_id)
  {
    $info = new stdClass();
    $info->categories = $this->guides->get_categories();
    $info->title_info = $this->guides->get_title_info($title_id);
    $info->category_guides = $this->guides->get_category_guides($info->title_info[0]->category);

    $view = 'detail';

    $this->loadView($info, $view);
  }
  /*---Fin método home------------------------------------------------------------------------------*/

  //Método para visualización de la vista
  private function loadView($info, $view)
  {
    $titlePage = "Conexión Empresas Online - Guias";
    $FooterCustomJS = "";
    //INSTANCIA MENU HEADER
    $menuHeader = $this->parser->parse('widgets/widget-menuHeader', array(), TRUE);
    //INSTANCIA MENU FOOTER
    $menuFooter = $this->parser->parse('widgets/widget-menuFooter', array(), TRUE);
    $header = $this->parser->parse('layouts/layout-header', array(
      'bodyclass' => 'full-width',
      'menuHeaderActive' => TRUE,
      'menuHeaderMainActive' => TRUE,
      'menuHeader' => $menuHeader,
      'titlePage' => $titlePage,
      'css' => $this->addCss
    ), TRUE);
    if ($view == 'content') {
      //add JS
      $addJs = [
        "jquery-3.6.0.min.js",
        "jquery-ui-1.10.3.custom.min.js",
        "jquery.balloon.min.js",
        "jquery.paginate.js",
        "jquery.isotope.min.js",
        "header.js",
        "routes.js"
      ];
      $footer = $this->parser->parse('layouts/layout-footer', array(
        'menuFooterActive' => TRUE,
        'menuFooter' => $menuFooter,
        'FooterCustomInsertJSActive' => TRUE,
        'FooterCustomInsertJS' => $addJs,
        'FooterCustomJSActive' => TRUE,
        'FooterCustomJS' => $FooterCustomJS
      ), TRUE);
      $categories = $info->categories;
      $guides = $info->guides;
      $content = $this->parser->parse('guides/content-guides', array(
        'categories' => $categories,
        'guides' => $guides
      ), TRUE);
    }
    if ($view == 'detail') {
      //add JS
      $addJs = [
        "jquery-3.6.0.min.js",
        "jquery-ui-1.10.3.custom.min.js",
        "jquery.balloon.min.js",
        "jquery.paginate.js",
        "jquery.isotope.min.js",
        "header.js",
        "guides/guides.js",
        "routes.js"
      ];
      $footer = $this->parser->parse('layouts/layout-footer', array(
        'menuFooterActive' => TRUE,
        'menuFooter' => $menuFooter,
        'FooterCustomInsertJSActive' => TRUE,
        'FooterCustomInsertJS' => $addJs,
        'FooterCustomJSActive' => TRUE,
        'FooterCustomJS' => $FooterCustomJS
      ), TRUE);
      $categories = $info->categories;
      $category_guides = $info->category_guides;
      $title_info = $info->title_info;
      if (sizeof($category_guides) <= 4) {
        $content = $this->parser->parse('guides/guides-detail', array(
          'categories' => $categories,
          'category_guides' => $category_guides,
          'title_info' => $title_info
        ), TRUE);
      } else {
        $content = $this->parser->parse('guides/guides-detail-extend', array(
          'categories' => $categories,
          'category_guides' => $category_guides,
          'title_info' => $title_info
        ), TRUE);
      }
    }

    $datos = array(
      'header' => $header,
      'content' => $content,
      'footer' => $footer,
      'sidebarActive' => FALSE,
      'titleHeading' => 'TITULO ACA',
      'login' => 'LOGIN USUARIO',
      'password' => 'CONTRASEÑA',
      'loginBtn' => 'ENTRAR',
    );

    $this->parser->parse('layouts/layout-a', $datos);
  }
  /*---Fin método para visuazación de la vista---------------------------------------------------*/

  //Método para intento de ingreso no autorizado
  private function withoutAccess()
  {
    if (!$this->logged_in) {
      $this->session->sess_destroy();
      redirect($this->urlCountry . '/login');
    }
  }
}
