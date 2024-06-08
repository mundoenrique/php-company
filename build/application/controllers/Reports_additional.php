<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @info		Controlador para obtener reportes adicionales
 * @date		2018/05/15
 * @author	J. Enrique Peñaloza P.
 */
class Reports_additional extends CI_Controller
{
  //Atributos de la clase
  protected $urlCountry;
  protected $logged_in;
  protected $idProductoS;
  protected $companyCod;
  protected $pais;
  protected $menu;
  protected $addCss = [];
  protected $addJs = [];
  protected $moduloAct;
  protected $titlePage;
  protected $contentView;
  protected $loadHelpers = [];
  protected $pageInfo;
  protected $dataResponse;
  protected $user;
  protected $program;

  public function __construct()
  {
    parent::__construct();
    log_message('INFO', 'reports_additional Controller initialized');
    //Inicializa los atributos
    $this->urlCountry = $this->uri->segment(1, 0);
    $this->logged_in = $this->session->userdata('logged_in');
    $this->idProductoS = $this->session->userdata('idProductoS');
    $this->companyCod = $this->session->userdata('accodciaS');
    $this->pais = $this->session->userdata('pais');
    $this->menu = $this->session->userdata('menuArrayPorProducto');
    $this->addCss = [
      'combustible/combustibleMaster.css',
      'reports/reports-master.css'
    ];
    $this->addJs = [
      'jquery-3.6.0.min.js',
      'jquery-ui-1.13.1.min.js',
      'jquery-md5.js',
      'jquery.balloon.min.js',
      'jquery.iframe-transport.js',
      'aes.min.js',
      'aes-json-format.min.js',
      'header.js',
      'dashboard/widget-empresa.js',
      'combustible/jquery.dataTables.js',
      'routes.js',
      'reportes/comision-recargas.js'
    ];
    $this->user = $this->session->userdata('nombreCompleto');
    $this->program = $this->session->userdata('nombreProductoS') . ' / ' . $this->session->userdata('marcaProductoS');
    $this->userCheck();
  }
  /**
   * @info		Método para verificar el acceso del usuario
   * @date 		2018/05/11
   * @author 	J. Enrique Peñaloza P.
   * @param	 	string $urlCountry
   * @param	 	string $pais
   * @param	 	boolean $logged_in
   * @param	 	string $idProductoS
   * @return	void
   */
  private function userCheck()
  {
    //Verificar país
    np_hoplite_countryCheck($this->urlCountry);
    //Llamado de los lenguajes que serán utilizados
    $this->lang->load('users');
    $this->lang->load('dashboard');
    $this->lang->load('erroreseol');
    $this->lang->load('reportes');
    if ($this->pais != $this->urlCountry || !$this->logged_in || !$this->idProductoS) {
      $this->withoutAccess();
    }
  }
  /**
   * @info		método para obtener los insumos de la vista de reportes con comisión
   * @date 		2018/05/11
   * @author	J. Enrique Peñaloza
   * @param		array @moduloAct[]
   * @return	void
   */
  public function ReportRecharWithComm()
  {
    $this->moduloAct = np_hoplite_existeLink($this->menu, "REPRTH");
    $this->moduloAct === FALSE ? $this->withoutAccess() : '';
    //Obtiene la lista de las empresas
    $this->load->model('lists_and_requirements_model', 'lists');
    $paginar = FALSE;
    $tamanoPagina = NULL;
    $paginaActual = NULL;
    $filtroEmpresas = NULL;

    $responseList = $this->lists->callWSListaEmpresasPaginar($paginar, $tamanoPagina, $paginaActual, $filtroEmpresas);
    $code = $responseList['code'];
    $title = $responseList['title'];
    $msg = $responseList['msg'];
    $companyList = [];
    $selectCompanies = 'disabled';
    $optionCompanies = lang('NO_EMPRESAS_LIST');
    if ($responseList['code'] === 0) {
      $companyList = $responseList['data'];
      $selectCompanies = '';
      $optionCompanies = lang('REPORTES_SELECCIONE_EMPRESA');
    }

    //Obtiene la lista de las recargas
    $this->load->model('reports_additional_model', 'recargas');
    $recargasList = $this->recargas->callWSReportRecharWithComm();
    if ($code === 0) {
      $code = $recargasList['code'];
      $title = $recargasList['title'];
      $msg = $recargasList['msg'];
    }

    $this->dataResponse = $recargasList['data'];


    $this->titlePage = 'Reportes';
    $this->pageInfo = [
      'title' => lang('BREADCRUMB_REPORTES_COMISION'),
      'code' => $code,
      'title-modal' => $title,
      'msg' => $msg,
      'css' => $recargasList['css'],
      'date' => $recargasList['date'],
      'select-companies' => $selectCompanies,
      'option-companies' => $optionCompanies,
      'current-company' => $this->companyCod,
      'companies' => $companyList
    ];

    $this->contentView = 'recargas-comision';

    $this->renderView();
  }
  /**
   * @info		método para renderizar la vista solicitada por el usuario
   * @date 		2018/05/11
   * @author	J. Enrique Peñaloza
   * @param		string $titlePAge, $program, $pais, $user,
   * @param		array $addCss[], $dataResponse[], $pageInfo[], $addJs[]
   * @return	void
   */
  private function renderView()
  {
    //Menú del header
    $menuHeader = $this->parser->parse('widgets/widget-menuHeader', [], true);
    //Agregar el header
    $header = $this->parser->parse('layouts/layout-header', [
      'bodyclass' => '',
      'menuHeaderActive' => TRUE,
      'menuHeaderMainActive' => TRUE,
      'menuHeader' => $menuHeader,
      'FooterCustomJSActive' => FALSE,
      'titlePage' => $this->titlePage,
      'css' => $this->addCss
    ], TRUE);
    //Agregar contenido
    $content = $this->parser->parse('reportes/' . 'content-' . $this->contentView, [
      'programa' => $this->program,
      'pais' => $this->pais,
      'user' => $this->user,
      'recharges' => $this->dataResponse,
      'pageInfo' => $this->pageInfo
    ], TRUE);
    //Agregar widget-empresa
    $sidebarLotes = $this->parser->parse('widgets/widget-publi-4', [
      'sidebarActive' => TRUE
    ], TRUE);
    //Menú del footer
    $menuFooter = $this->parser->parse('widgets/widget-menuFooter', [], true);
    //Agregar footer
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
      'sidebar' => $sidebarLotes,
      'pais' => $this->pais
    ];
    //Cargar pagina
    $this->parser->parse('layouts/layout-b', $datos);
  }
  /**
   * @info		método para el llamado generoico de los métodos del modelo reportes adicionales
   * @date		2018/05/11
   * @author	J. Enrique Peñaloza
   * @param		string $urlCountry, $model, $method, $dataRequest
   * @return	object $dataResponse{}
   */
  public function callSystem($urlCountry)
  {
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
    //Optener el modelo
    $model = $dataRequest->mod;
    //Cargar el modelo
    $this->load->model($model . '_model', 'modelo');
    //Optener el Método
    $method = 'callWS' . $dataRequest->way;
    //Optener datos para el request
    $data = $dataRequest->request;
    //Obtener respuesta del servicio
    $dataResponse = $this->modelo->$method($data);
    //Enviar respuesta a la vista
    $response = $this->cryptography->encrypt($dataResponse);
    $this->output->set_content_type('application/json')->set_output(json_encode($response));
  }
  /**
   * @info		método para eliminar archivos de reportes descargados por el usuario
   * @date		2018/08/14
   * @author	J. Enrique Peñaloza
   */
  public function deleteReport($urlCountry)
  {
    $file = $this->input->post('way');
    $path = $this->config->item('CDN');
    unlink($path . 'downloads/reports/' . $file);
    $this->output->set_content_type('application/json')->set_output(json_encode('ok'));
  }
  /**
   * @info		método para el acceso no autorizado de los usuarios
   * @date		2018/05/11
   * @author	J. Enrique Peñaloza
   * @param		string $logged_in, $idProductos, $moduloAct
   * @return	void (Alert javascript notificando el motivo del rechazo)
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
