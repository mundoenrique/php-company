<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @info Controlador para gestionar las conultas adicionales
 * @date 2018/08/02
 * @author J. Enrique Peñaloza P
 * @package controllers
 */
class Additional_inquiries extends CI_Controller
{

  //Atributos de clase
  protected $urlCountry;
  protected $logged_in;
  protected $userName;
  protected $idProductoS;
  protected $program;
  protected $sessionCountry;
  protected $menu;
  protected $moduloAct;
  protected $addCss = [];
  protected $addJs = [];
  protected $titlePage;
  protected $contentView;
  protected $action;
  protected $dataResponse;

  public function __construct()
  {
    parent::__construct();
    //Attribute initialization
    $this->addCss = [
      'combustible/combustibleMaster.css',
      'inquiries/inquiries-matser.css'
    ];
    $this->addJs = [
      'jquery-3.6.0.min.js',
      'jquery-ui-1.13.1.min.js',
      'jquery.balloon.min.js',
      'header.js',
      'dashboard/widget-empresa.js',
      'combustible/jquery.dataTables.js',
      'combustible/dataTables.buttons.min.js',
      'routes.js'
    ];
    //Add libraries
    $this->load->library('parser');
    //Optener login, producto and pais
    $this->logged_in = $this->session->userdata('logged_in');
    $this->userName = $this->session->userdata('nombreCompleto');
    $this->idProductoS = $this->session->userdata('idProductoS');
    $this->program = $this->session->userdata('nombreProductoS') . ' / ' . $this->session->userdata('marcaProductoS');
    $this->sessionCountry = $this->session->userdata('pais');
    //obtener el pais de la url
    $this->urlCountry = $this->uri->segment(1, 0);
    //Optener las opciones del menú
    $this->menu = $this->session->userdata('menuArrayPorProducto');
    $this->userCheck();
  }

  private function userCheck()
  {
    //Verificar país
    np_hoplite_countryCheck($this->urlCountry);
    //Add languages
    $this->lang->load('dashboard');
    $this->lang->load('users');
    $this->lang->load('consultas');
    $this->lang->load('erroreseol');
    if ($this->sessionCountry != $this->urlCountry || !$this->logged_in || !$this->idProductoS) {
      $this->withoutAccess();
    }
  }

  public function batchesByInvoice($urlCountry)
  {
    $this->moduloAct = np_hoplite_existeLink($this->menu, "LOTFAC");
    $this->moduloAct == false ? $this->withoutAccess() : '';
    array_push(
      $this->addJs,
      'consultas/batches-invoice.js'
    );
    $this->action = lang('TITULO_LOTES_POR_FACTURAR');
    $this->contentView = 'batches-invoice';

    $this->load->model('additional_inquiries_model', 'list');
    $responseList = $this->list->callWsGetBatchesByInvoice();

    $code = $responseList['code'];
    $title = $responseList['title'];
    $msg = $responseList['msg'];

    $this->dataResponse = $responseList['data'];


    $this->titlePage = 'Consultas';
    $this->pageInfo = [
      'title' => lang('BREADCRUMB_REPORTES_COMISION'),
      'code' => $code,
      'title-modal' => $title,
      'msg' => $msg,
    ];

    $this->loadView();
  }

  private function loadView()
  {
    //Agrega la cabecera
    $menuHeader = $this->parser->parse('widgets/widget-menuHeader', [], true);
    $header = $this->parser->parse('layouts/layout-header', [
      'bodyclass' => '',
      'menuHeaderActive' => TRUE,
      'menuHeaderMainActive' => TRUE,
      'menuHeader' => $menuHeader,
      'FooterCustomJSActive' => FALSE,
      'titlePage' => $this->titlePage,
      'css' => $this->addCss
    ], TRUE);
    //agrega el contenido
    $content = $this->parser->parse('consultas/' . 'content-' . $this->contentView, [
      'programa' => $this->program,
      'pais' => $this->urlCountry,
      'user' => $this->userName,
      'dataResponse' => $this->dataResponse,
      'action' => $this->action,
      'pageInfo' => $this->pageInfo
    ], TRUE);
    //Agregar sideBar-empresa
    $sidebar = $this->parser->parse('dashboard/widget-empresa', [
      'sidebarActive' => TRUE
    ], TRUE);
    //Agregar aviso
    $aviso = $this->parser->parse('widgets/widget-aviso', [
      'msg' => 'Los lotes acumulados, pendientes por facturar, serán fraccionados de acuerdo a su antigüedad y aparecerán reflejados en las Órdenes de servicio/Facturas de las recargas que ordene su empresa.'
    ], TRUE);
    //Agregar footer
    $menuFooter = $this->parser->parse('widgets/widget-menuFooter', [], true);
    $footer = $this->parser->parse('layouts/layout-footer', [
      'menuFooterActive' => TRUE,
      'menuFooter' => $menuFooter,
      'FooterCustomInsertJSActive' => TRUE,
      'FooterCustomInsertJS' => $this->addJs,
    ], TRUE);
    //Agregar datos para la página
    $datos = [
      'header' => $header,
      'content' => $content,
      'footer' => $footer,
      'sidebar' => $sidebar,
      'aviso' => $aviso,
      'pais' => $this->urlCountry
    ];
    //Cargar pagina
    $this->parser->parse('layouts/layout-b', $datos);
  }

  public function callWebService()
  {
    $method = 'callWs' . $this->input->get('way');
    $this->load->model('additional_inquiries_model', 'report');
    $response = $this->report->callWsGetBatchesByInvoice('report');
    $this->output->set_content_type('application/json')->set_output(json_encode($response));
  }

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
