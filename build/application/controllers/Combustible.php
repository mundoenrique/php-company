<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Clase Combustible
 *
 * Clase para las operaciónes relacionadas al módulo Combustible.
 *
 * @package     controllers
 * @author      J. Enrique Peñaloza <mundoenrique@gmail.com>
 * @author      Andrés Rivas <andreselias2303@gmail.com>
 * @author      Hector Corredor <hector.corredor.g@gmail.com>
 */
class Combustible extends CI_Controller
{
  //Atributos de la clase
  protected $urlCountry;
  protected $logged_in;
  protected $idProductoS;
  protected $pais;
  protected $menu;
  protected $moduloAct;
  protected $addCss = [];
  protected $addJs = [];
  protected $titlePage;
  protected $contentView;
  protected $loadHelpers = [];
  protected $action;
  protected $dataResponse;

  //Método constructor
  public function __construct()
  {
    parent::__construct();
    //Attribute initialization
    $this->addCss = [
      'combustible/combustibleMaster.css'
    ];
    $this->addJs = [
      'jquery-3.6.0.min.js',
      'jquery-ui-1.13.1.min.js',
      'jquery-md5.js',
      'jquery.balloon.min.js',
      'jquery.iframe-transport.js',
      'header.js',
      'dashboard/widget-empresa.js',
      'combustible/jquery.dataTables.js',
      'combustible/dataTables.buttons.min.js',
      'routes.js',
      'combustible/routes.js'
    ];
    //Add languages
    $this->lang->load('dashboard');
    $this->lang->load('combustible');
    $this->lang->load('users');
    $this->lang->load('erroreseol');
    //Add libraries
    $this->load->library('parser');
    //Optener login, producto and pais
    $this->logged_in = $this->session->userdata('logged_in');
    $this->idProductoS = $this->session->userdata('idProductoS');
    $this->pais = $this->session->userdata('pais');
    //obtener el pais de la url
    $this->urlCountry = $this->uri->segment(1, 0);
    //Optener las opciones del menú
    $this->menu = $this->session->userdata('menuArrayPorProducto');
    $this->userCheck();
  }
  /*---Fin método constructor-------------------------------------------------------------------*/

  /**
   * @Method: userCheck
   * @access private
   * @params: strting $urlCountry
   * @info: Método verificar los persmisos de acceso del usuario
   * @autor: Enrique Peñaloza
   * @date:  21/03/2017
   */
  private function userCheck()
  {
    //Verificar país
    np_hoplite_countryCheck($this->urlCountry);
    if ($this->pais != $this->urlCountry || !$this->logged_in || !$this->idProductoS) {
      $this->withoutAccess();
    }
  }


  //Método que proporciona las opciones de trabajo para combustible
  public function home($urlCountry)
  {
    //Agregar estilos
    array_push(
      $this->addCss,
      'combustible/home.css'
    );
    //Agregar título de la página
    $this->titlePage = lang('BREADCRUMB_COMBUSTIBLE');
    //Agregar archivo de vista
    $this->contentView = 'combustible';
    //Cargar vista
    $this->loadView();
  }
  /*---Fin método home----------------------------------------------------------------------------------------------*/

  //Método para obtener el listado de conductores
  public function drivers($urlCountry)
  {
    //Verificar las opciones del menú
    $this->moduloAct = np_hoplite_existeLink($this->menu, "CMBCON");
    $this->moduloAct == false ? $this->withoutAccess() : '';
    //Agregar estilos
    array_push(
      $this->addCss,
      'combustible/drivers.css'
    );
    //Agregar javascript
    array_push(
      $this->addJs,
      'combustible/drivers.js'
    );
    //Agregar título de la página
    $this->titlePage = lang('BREADCRUMB_DRIVERS');
    //Agregar archivo de vista
    $this->contentView = 'combus-drivers';
    //titulo de la vista
    $this->action = [
      'title' => lang('DRIVER_ADMIN')
    ];
    //Cargar vista
    $this->loadView();
  }

  //Método para registrar o editar conductor
  public function driversAddEdit($urlCountry)
  {
    //Verificar las opciones del menú
    $this->moduloAct = np_hoplite_existeLink($this->menu, "CMBCON");
    $this->moduloAct == false ? $this->withoutAccess() : '';
    //Agregar javascript
    array_push(
      $this->addJs,
      'combustible/jquery.validate.min.js',
      'combustible/additional-methods.min.js',
      'combustible/driversAdd.js'
    );
    //Agregar título de la página
    $this->titlePage = lang('BREADCRUMB_PROFILE_DRIVERS');
    //Agregar archivo de vista
    $this->contentView = 'combus-driversAddEdit';
    //Optiene username del conductor y la función para registrar o actualizar perfil
    $dataRequest = $this->input->post('data-id');
    $action = $this->input->post('function');
    //titulo de la vista
    $this->action = [
      'title' => lang('DRIVER_INCLUDE'),
      'action' => lang('TAG_SEND'),
      'function' => $action
    ];
    //Evalua el username del conductor
    switch ($action) {
      case 'update':
        //Optener el modelo
        $model = $this->input->post('modelo');
        //Cargar el modelo
        $this->load->model($model . '_Model', 'combustible');
        //Obtener respuesta del API
        $response = $this->combustible->callAPIdrivers($urlCountry, $dataRequest);
        $this->dataResponse = json_encode($response);
        //action
        $status = '';
        if (isset($response['msg']['estatusConductor'])) {
          $status = $response['msg']['estatusConductor'] == 1 ? lang('MENU_DISABLED_DRIVER') : lang('MENU_AVAILABLE_DRIVER');
        }
        $this->action = [
          'title' => lang('DRIVER_EDIT'),
          'action' => lang('TAG_WITHOUT_CHANGES'),
          'changes' => lang('TAG_SAVE_CHANGES'),
          'status' => $status,
          'function' => $action
        ];
        break;
      case 'register':
        $response = [
          'code' => 0,
          'msg' => [
            'id_ext_per' => $dataRequest
          ]
        ];
        $this->dataResponse = json_encode($response);
        break;
      default:
        $response = [
          'code' => 1,
          'title' => lang('BREADCRUMB_COMBUSTIBLE'),
          'msg' => lang('ERROR_NON_ACCESS')
        ];
        $this->dataResponse = json_encode($response);
    }
    //Cargar vista
    $this->loadView();
  }
  /*---Fin métodos para conductores---------------------------------------------------------------------------------*/

  // método para listar los grupos de vehículos
  public function vehicleGroups($urlCountry)
  {

    //Verificar las opciones del menú
    $this->moduloAct = np_hoplite_existeLink($this->menu, "CMBVHI");
    $this->moduloAct == false ? $this->withoutAccess() : '';
    //agregar hoja de estilos
    array_push(
      $this->addCss,
      'combustible/vehicleGroups.css'
    );
    //Agregar javascript
    array_push(
      $this->addJs,
      'combustible/vehicleGroups.js',
      'combustible/vehicleGruopsFunctions.js',
      'combustible/notiSystem.js',
      'combustible/jquery.validate.min.js',
      'combustible/additional-methods.min.js'
    );
    //Agregar título de la página
    $this->titlePage = lang('BREADCRUMB_GROUP');
    //Agregar archivo de vista
    $this->contentView = 'combus-vehicleGroups';
    //titulo de la vista
    $this->action = [
      'title' => lang('TITLE_GROUP_ADMIN')
    ];
    //Cargar vista
    $this->loadView();
  }
  /*---Fin método para Grupos de vehículos----------------------------------*/

  /*---Métodos para vehículos-----------------------------------------------*/
  public function vehicles($urlCountry)
  {
    //Verificar las opciones del menú
    $this->moduloAct = np_hoplite_existeLink($this->menu, "CMBVHI");
    $this->moduloAct == false ? $this->withoutAccess() : '';
    //agregar hoja de estilos
    array_push(
      $this->addCss,
      'combustible/vehicles.css'
    );
    //agregar javascript
    array_push(
      $this->addJs,
      'combustible/vehicles.js',
      'combustible/vehiclesFunctions.js',
      'combustible/notiSystem.js',
      'combustible/jquery.validate.min.js',
      'combustible/additional-methods.min.js'
    );
    //Agregar título de la página
    $this->titlePage = lang('BREADCRUMB_VEHICLES');;
    //Agregar archivo de vista
    $this->contentView = 'combus-vehicles';
    //Obtiene id del grupo de vehículos
    $groupID = $this->input->post('data-id');
    //Obtiene el nombre del grupo de vehículos
    $groupName = $this->input->post('data-name');
    if ($groupID > 0) {
      //Cargar modelo
      $this->load->model('vehicles_model', 'vehicles');
      //Obtener respuesta del API
      $response = $this->vehicles->callAPIstatusVehicle($urlCountry);
      $this->dataResponse = json_encode($response);
      $this->action = [
        'title' => lang('TITLE_VEHI_ADMIN'),
        'groupID' => $groupID,
        'groupName' => $groupName
      ];

      //Cargar vista
      $this->loadView();
    } else {
      redirect(base_url($this->urlCountry . '/trayectos/gruposVehiculos'), 'location');
      exit();
    }
  }
  /*---Fin métodos para vehículos-------------------------------------------*/

  // métodos para cuentas
  public function accounts($urlCountry)
  {
    //Verificar las opciones del menú
    $this->moduloAct = np_hoplite_existeLink($this->menu, "CMBCTA");
    $this->moduloAct == false ? $this->withoutAccess() : '';
    //Agregar estilos
    array_push(
      $this->addCss,
      'combustible/account.css'
    );

    //Agregar javascript
    array_push(
      $this->addJs,
      'combustible/accounts.js'
    );
    //Agregar título de la página
    $this->titlePage = lang('BREADCRUMB_ACCOUNTS');

    //Cargar modelo
    $this->load->model('account_model', 'account');

    $this->action = [
      'title' => lang('TITLE_ACCOUNT_ADMIN'),
      //            'groupID' => $groupID,
      //            'groupName' => $groupName
    ];

    //Agregar archivo de vista
    $this->contentView = 'combus-accounts';
    //Cargar vista
    $this->loadView();
  }

  // métodos para cuentas
  public function accountsDetails($urlCountry)
  {
    //Verificar las opciones del menú
    $this->moduloAct = np_hoplite_existeLink($this->menu, "CMBCTA");
    $this->moduloAct == false ? $this->withoutAccess() : '';

    //Agregar estilos
    array_push(
      $this->addCss,
      'combustible/accountDetails.css'
    );

    //Agregar javascript
    array_push(
      $this->addJs,
      'combustible/accountsDetails.js'
    );
    //Agregar título de la página
    $this->titlePage = lang('BREADCRUMB_ACCOUNTS_DETAILS');

    //Optiene Numero de cuenta
    $dataCard = $this->input->post('data-id');

    if ($dataCard != '') {
      //Cargar el modelo
      $this->load->model('account_model', 'account');
      //Obtener respuesta del API
      $response = $this->account->callAPIaccounts($urlCountry, $dataCard);
      $this->dataResponse = json_encode($response);
      //Agregar archivo de vista
      $this->contentView = 'combus-accountsDetails';
      //Cargar vista
      $this->loadView();
    } else {
      redirect(base_url($urlCountry . "/combustible/cuentas"));
    }
  }
  /*---Fin métodos para cuentas-----------------------------------------------------------------------------------*/

  /*---Métodos para viajes-----------------------------------------------*/
  public function travels($urlCountry)
  {
    //Verificar las opciones del menú
    $this->moduloAct = np_hoplite_existeLink($this->menu, "CMBVJE");
    $this->moduloAct == false ? $this->withoutAccess() : '';
    //Agregar estilos
    array_push(
      $this->addCss,
      'combustible/travels.css'
    );
    //Agregar javascript
    array_push(
      $this->addJs,
      'combustible/jquery.validate.min.js',
      'combustible/additional-methods.min.js',
      'combustible/travels.js',
      'combustible/travelsFunctions.js',
      'combustible/travelsHelpers.js'
    );
    //Agregar título de la página
    $this->titlePage = lang('BREADCRUMB_TRAVELS');
    //Agregar archivo de vista
    $this->contentView = 'combus-travels';
    //titulo de la vista
    $this->action = [
      'title' => lang('TITLE_TRAVEL_ADMIN')
    ];
    //Cargar vista
    $this->loadView();
  }

  public function travelAddEdit($urlCountry)
  {
    //Verificar las opciones del menú
    $this->moduloAct = np_hoplite_existeLink($this->menu, "CMBVJE");
    $this->moduloAct == false ? $this->withoutAccess() : '';
    //Agregar estilos
    array_push(
      $this->addCss,
      'combustible/travelsAddEdit.css'
    );
    //Agregar javascript
    array_push(
      $this->addJs,
      'combustible/travelsAddEdit.js',
      'combustible/travelsAddEditFunctions.js',
      'combustible/travelsHelpers.js',
      'combustible/getMap.js',
      'combustible/jquery.validate.min.js',
      'combustible/additional-methods.min.js'
    );
    //Agregar título de la página
    $this->titlePage = lang('BREADCRUMB_TRAVELS');
    //Agregar archivo de vista
    $this->contentView = 'combus-travelsAdd';
    //Obtiene la función para registrar o actualizar un viaje y el id del viaje
    $action = $this->input->post('function');
    $travelID = $this->input->post('data-id');

    //Evalua el si registra o edita el viaje
    if ($action === 'register') {
      //titulo de la vista
      $this->action = [
        'title' => lang('TRAVELS_ADD'),
        'action' => lang('TAG_FOLLOW'),
        'function' => $action,
        'travelID' => $travelID,
        'activity' => lang('TRAVELS_CREATE_ROUTE'),
        'info' => lang('TAG_CLEAR_FORM'),
        'map' => 0
      ];
    } elseif ($action === 'update') {
      //titulo de la vista
      $this->action = [
        'title' => lang('TRAVELS_EDIT'),
        'action' => '',
        'function' => $action,
        'travelID' => $travelID,
        'activity' => '',
        'info' => '',
        'map' => 1,
      ];
    } else {
      redirect(base_url($this->urlCountry . '/combustible/viajes'), 'location');
      exit();
    }
    //Cargar vista
    $this->loadView();
  }
  /*---Fin de métodos para vehículos----------------------------------------*/

  //Método para visualización de la vista
  private function loadView()
  {
    //Cargar helpers
    if (count($this->loadHelpers > 0)) {
      foreach ($this->loadHelpers as $item) {
        $item = trim($item);
        $this->load->helper($item);
      }
    }
    //Optener el nombre del usuario
    $user = $this->session->userdata('nombreCompleto');
    //Nombre del producto
    $programa = $this->session->userdata('nombreProductoS') . ' / ' . $this->session->userdata('marcaProductoS');
    //Menú del header
    $menuHeader = $this->parser->parse('widgets/widget-menuHeader', array(), true);
    //Agregar el header
    $header = $this->parser->parse('layouts/layout-header', array(
      'bodyclass' => '',
      'menuHeaderActive' => TRUE,
      'menuHeaderMainActive' => TRUE,
      'menuHeader' => $menuHeader,
      'FooterCustomJSActive' => FALSE,
      'titlePage' => $this->titlePage,
      'css' => $this->addCss
    ), TRUE);
    //Agregar contenido
    $content = $this->parser->parse('combustible/' . 'content-' . $this->contentView, array(
      'programa' => $programa,
      'pais' => $this->urlCountry,
      'user' => $user,
      'dataResponse' => $this->dataResponse,
      'action' => $this->action
    ), TRUE);
    //Agregar widget-empresa
    $sidebarLotes = $this->parser->parse('dashboard/widget-empresa', array('sidebarActive' => TRUE), TRUE);
    //Menú del footer
    $menuFooter = $this->parser->parse('widgets/widget-menuFooter', array(), true);
    //Agregar footer
    $footer = $this->parser->parse('layouts/layout-footer', array(
      'menuFooterActive' => TRUE,
      'menuFooter' => $menuFooter,
      'FooterCustomInsertJSActive' => TRUE,
      'FooterCustomInsertJS' => $this->addJs,
    ), TRUE);
    //Agregar datos para la página
    $datos = array(
      'header' => $header,
      'content' => $content,
      'footer' => $footer,
      'sidebar' => $sidebarLotes,
      'pais' => $this->urlCountry
    );
    //Cargar pagina
    $this->parser->parse('layouts/layout-b', $datos);
  }
  /*---Fin método para visuazación de la vista----------------------------------------------------------------------*/

  //llamado del modelo
  public function callAPImodel($urlCountry)
  {
    //Optener el modelo
    $model = $this->input->post('modelo');
    //Optener el Método
    $method = 'callAPI' . $this->input->post('way');
    //Cargar el modelo
    $this->load->model($model . '_Model', 'combustible');
    //Optener datos para el request
    $dataRequest = $this->input->post('data');
    //Obtener respuesta del API
    $dataResponse = $this->combustible->$method($urlCountry, $dataRequest);
    //Enviar respuesta
    $this->output->set_content_type('application/json')->set_output(json_encode($dataResponse));
  }
  /*---Fin método para llamado al modelo----------------------------------------------------------------------------*/

  //Método para intento de ingreso no autorizado
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
