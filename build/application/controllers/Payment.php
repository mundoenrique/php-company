<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @Class:  Visa
 * @package controllers
 * @Info:   Clase para los controles Visa
 * @author: J Enrique Peñaloza P
 * Date: 29/08/2017
 * Time: 10:00 am
 */
class Payment extends CI_Controller
{
  //Atributos de la clase
  protected $urlCountry;
  protected $logged_in;
  protected $idProductoS;
  protected $pais;
  protected $menu;
  protected $funciones;
  protected $moduloAct;
  protected $addCss = [
    'combustible/combustibleMaster.css'
  ];
  protected $addJs = [
    "jquery-3.6.0.min.js",
    "jquery-ui-1.10.3.custom.min.js",
    "jquery-md5.js",
    "jquery.balloon.min.js",
    "jquery.iframe-transport.js",
    "header.js",
    "dashboard/widget-empresa.js",
    'combustible/jquery.dataTables.js',
    "routes.js",
    'combustible/jquery.validate.min.js',
    'combustible/additional-methods.min.js',
    'visa/validate-form.js',
    'visa/visa-helpers.js'
  ];
  protected $titlePage;
  protected $contentView;
  protected $action;
  protected $dataResponse;

  /**
   * Constructor de clase
   */
  public function __construct()
  {
    parent::__construct();
    //Cargar librarias
    $this->load->library('parser');
    //Optener login, producto y pais
    $this->logged_in = $this->session->userdata('logged_in');
    $this->idProductoS = $this->session->userdata('idProductoS');
    $this->pais = $this->session->userdata('pais');
    //Optener las opciones del menú
    $this->menu = $this->session->userdata('menuArrayPorProducto');
    $this->funciones = np_hoplite_modFunciones($this->menu);
    $this->countrycheck();
  }
  //----------------------------------------------------------------------------------------------

  /**
   * @Method: countrycheck
   * @access public
   * @params: void
   * @info: Método verificar el país de la url y permisos de acceso al módulo
   * @autor: Enrique Peñaloza
   * @date:  04/10/2017
   */
  private function countrycheck()
  {
    //obtener el pais de la url
    $this->urlCountry = $this->uri->segment(1, 0);
    //Verificar país
    np_hoplite_countryCheck($this->urlCountry);
    //Agregar archivos de lenguaje
    $this->lang->load('servicios');
    $this->lang->load('dashboard');
    $this->lang->load('users');
    $this->lang->load('erroreseol');
    $this->lang->load('visa');
    //Verificar las permisos del usuario
    $this->moduloAct = np_hoplite_existeLink($this->menu, "PAGPRO");
    if (
      $this->pais != $this->urlCountry || !$this->logged_in || !$this->idProductoS ||
      $this->moduloAct == FALSE
    ) {
      $this->withoutAccess();
    }
  }
  //----------------------------------------------------------------------------------------------

  /**
   * @Method: payments
   * @access public
   * @params: void
   * @info: Método obtener y modificar los controles de las tarjetas visa
   * @autor: Enrique Peñaloza
   * @date:  31/09/2017
   */
  public function payments()
  {
    //Agregar archivos css
    array_push(
      $this->addCss,
      'visa/card-list.css'
    );
    //Agregar archivos js
    array_push(
      $this->addJs,
      'visa/visa-payments.js'
    );
    //cargar modelo
    $this->load->model('payments_model', 'payments');
    //Agregar título de la página
    $this->titlePage = lang('TITLE_VISA_PAYMENTS');
    //Agregar archivo de vista
    $this->contentView = 'payments-visa';
    //titulo de la vista
    $this->action = lang('TITLE_VISA_PAYMENTS');
    $this->dataResponse = $this->payments->callWsConsultaSaldo($this->urlCountry);
    //Cargar vista
    $this->loadView();
  }
  //----------------------------------------------------------------------------------------------

  /**
   * @Method: loadView
   * @access private
   * @params: varios
   * @info: renderizar la vista
   * @autor: Enrique Peñaloza
   * @date:  31/09/2017
   */
  private function loadView()
  {
    //Optener el nombre del usuario
    $user = $this->session->userdata('nombreCompleto');
    //Nombre del producto
    $programa = $this->session->userdata('nombreProductoS') . ' / ' .
      $this->session->userdata('marcaProductoS');
    //Menú del header
    $menuHeader = $this->parser->parse('widgets/widget-menuHeader', [], true);
    //Agregar el header
    $header = $this->parser->parse(
      'layouts/layout-header',
      [
        'bodyclass' => '',
        'menuHeaderActive' => TRUE,
        'menuHeaderMainActive' => TRUE,
        'menuHeader' => $menuHeader,
        'FooterCustomJSActive' => FALSE,
        'titlePage' => $this->titlePage,
        'css' => $this->addCss
      ],
      TRUE
    );
    //Agregar contenido
    $content = $this->parser->parse(
      'visa/content-' . $this->contentView,
      [
        'programa' => $programa,
        'funciones' => $this->funciones,
        'pais' => $this->urlCountry,
        'user' => $user,
        'dataResponse' => $this->dataResponse,
        'action' => $this->action
      ],
      TRUE
    );

    //Agregar widget-empresa
    $sidebarLotes = $this->parser->parse(
      'dashboard/widget-empresa',
      [
        'sidebarActive' => TRUE
      ],
      TRUE
    );
    //Menú del footer
    $menuFooter = $this->parser->parse('widgets/widget-menuFooter', [], TRUE);
    //Agregar footer
    $footer = $this->parser->parse(
      'layouts/layout-footer',
      [
        'menuFooterActive' => TRUE,
        'menuFooter' => $menuFooter,
        'FooterCustomInsertJSActive' => TRUE,
        'FooterCustomInsertJS' => $this->addJs,
      ],
      TRUE
    );
    //Agregar datos para la página
    $datos = [
      'header' => $header,
      'content' => $content,
      'footer' => $footer,
      'sidebar' => $sidebarLotes,
      'pais' => $this->urlCountry
    ];
    //Cargar pagina
    $this->parser->parse('layouts/layout-b', $datos);
  }
  //----------------------------------------------------------------------------------------------

  /**
   * @Method: callVisaModel
   * @access public
   * @params: varios
   * @info: Método generico para los llamados AJAX
   * @autor: Enrique Peñaloza
   * @date:  31/09/2017
   */
  public function callAPImodel()
  {
    //Optener el Modelo
    $model = $this->input->post('model');
    //Optener el Método
    $method = 'callCeoApi' . $this->input->post('method');
    //Cargar el modelo
    $this->load->model($model . '_Model', 'model');
    //Optener datos para el request
    $dataRequest = $this->input->post('dataRequest');
    //Obtener respuesta del servicio
    $dataResponse = $this->model->$method($this->urlCountry, $dataRequest);
    //Enviar respuesta
    $this->output->set_content_type('application/json')->set_output(
      $dataResponse
    );
  }
  //----------------------------------------------------------------------------------------------

  /**
   * @Method: withoutAccess
   * @access public
   * @params: void
   * @info: Método el intento de ingreso no autorizado
   * @autor: Enrique Peñaloza
   * @date:  31/09/2017
   */
  private function withoutAccess()
  {
    if ($this->logged_in && !$this->idProductoS) {
      echo "
			<script>
                alert('Selecciona un producto');
                location.href = '" . $this->config->item('base_url') . "$this->urlCountry/dashboard/';
            </script>
			";
    } elseif ($this->logged_in && $this->idProductoS && $this->moduloAct == FALSE) {
      echo "
			<script>
                alert('" . lang('SIN_FUNCION') . "');
                location.href = '" . $this->config->item('base_url') . "$this->urlCountry/dashboard/';
            </script>
			";
    } else {
      $this->session->sess_destroy();
      redirect($this->urlCountry . '/login');
    }
  }
}
