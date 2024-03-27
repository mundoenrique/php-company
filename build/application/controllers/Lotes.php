<?php
defined('BASEPATH') or exit('No direct script access allowed');
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
class Lotes extends CI_Controller
{
  /**
   * Patalla que muestra el módulo de carga de lotes y lotes por confirmar.
   *
   * @param  string $urlCountry
   */
  public function pantallaCarga($urlCountry)
  {
    //VALIDATE COUNTRY
    np_hoplite_countryCheck($urlCountry);

    $this->lang->load('dashboard');
    $this->lang->load('lotes');
    $this->lang->load('users');
    $this->lang->load('erroreseol');

    $this->load->library('parser');
    $logged_in = $this->session->userdata('logged_in');
    $idEmpresa = $this->session->userdata('acrifS');
    $idProductoS = $this->session->userdata('idProductoS');

    $menuP = $this->session->userdata('menuArrayPorProducto');
    $moduloAct = np_hoplite_existeLink($menuP, "TEBCAR");

    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in && $idProductoS && $moduloAct !== false) {
      $username = $this->session->userdata('userName');
      $token = $this->session->userdata('token');
      $nombreCompleto = $this->session->userdata('nombreCompleto');
      $lastSessionD = $this->session->userdata('lastSession');
      $jsRte = '../../../js/';
      $thirdsJsRte = '../../../js/third_party/';
      $FooterCustomInsertJS = ["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js", "jquery-md5.js", "jquery.balloon.min.js", "aes.min.js", "aes-json-format.min.js", "jquery.fileupload.js", "jquery.iframe-transport.js", "dashboard/widget-empresa.js", "lotes/lotes.js", "jquery.dataTables.min.js", "header.js", "routes.js", $thirdsJsRte . "jquery.validate.min.js", $jsRte . "validate-forms.js", $thirdsJsRte . "additional-methods.min.js"];
      $FooterCustomJS = "";
      $titlePage = "Conexión Empresas Online - Lotes";

      if ($this->session->userdata('marcaProductoS') === 'Cheque') {
        $programa = $this->session->userdata('nombreProductoS');
      } else {
        $programa = $this->session->userdata('nombreProductoS') . ' / ' . ucwords($this->session->userdata('marcaProductoS'));
      }

      $tiposLotesLista[] = $this->callWSconsultarTipoLote($urlCountry, $idProductoS);

      $menuHeader = $this->parser->parse('widgets/widget-menuHeader', array(), TRUE);
      $menuFooter = $this->parser->parse('widgets/widget-menuFooter', array(), TRUE);
      $header = $this->parser->parse('layouts/layout-header', array('bodyclass' => '', 'menuHeaderActive' => TRUE, 'menuHeaderMainActive' => TRUE, 'menuHeader' => $menuHeader, 'titlePage' => $titlePage), TRUE);
      $aviso = $this->parser->parse('widgets/widget-aviso', ['pais' => $urlCountry], TRUE);
      $footer = $this->parser->parse('layouts/layout-footer', array('menuFooterActive' => TRUE, 'menuFooter' => $menuFooter, 'FooterCustomInsertJSActive' => TRUE, 'FooterCustomInsertJS' => $FooterCustomInsertJS, 'FooterCustomJSActive' => TRUE, 'FooterCustomJS' => $FooterCustomJS), TRUE);
      $content = $this->parser->parse('lotes/content-cargarlotes', array(
        'titulo' => $nombreCompleto,
        'breadcrum' => '',
        'lastSession' => $lastSessionD,
        'selectTiposLotes' => $tiposLotesLista,
        'programa' => $programa
      ), TRUE);
      $sidebarLotes = $this->parser->parse('dashboard/widget-empresa', array('sidebarActive' => TRUE), TRUE);

      $datos = array(
        'header' => $header,
        'content' => $content,
        'footer' => $footer,
        'sidebar' => $sidebarLotes,
        'aviso' => $aviso,
        'titleHeading' => 'TITULO ACA',
        'login' => 'LOGIN USUARIO',
        'password' => 'CONTRASEÑA',
        'loginBtn' => 'ENTRAR',
        'pais' => $urlCountry
      );

      $this->parser->parse('layouts/layout-c', $datos);
    } elseif ($logged_in) {
      echo "
			<script>
			alert('Selecciona un producto');
			location.href = '" . $this->config->item('base_url') . "$urlCountry/dashboard/productos';
			</script>
			";
    } else if ($paisS != $urlCountry && $paisS != "") {
      $this->session->sess_destroy();
      echo "
			<script> location.href = '" . $this->config->item('base_url') . "$urlCountry/login';</script>
			";
    } else {
      echo "
			<script>alert('Enlace no permitido'); location.href = '" . $this->config->item('base_url') . "$urlCountry/login';</script>
			";
    }
  }

  /**
   * Pantalla para visualizar el detalle del lote con errores seleccionado
   *
   * @param  string $urlCountry
   *
   */
  public function pantallaDetalleLote($urlCountry)
  {
    //VALIDATE COUNTRY
    np_hoplite_countryCheck($urlCountry);
    $this->lang->load('dashboard');
    $this->lang->load('lotes');
    $this->lang->load('users');
    $this->load->library('parser');
    $this->lang->load('erroreseol');

    $logged_in = $this->session->userdata('logged_in');
    $idProductoS = $this->session->userdata('idProductoS');

    $menuP = $this->session->userdata('menuArrayPorProducto');
    $moduloAct = np_hoplite_existeLink($menuP, "TEBCAR");

    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in && isset($idProductoS) && $moduloAct !== false) {

      $nombreCompleto = $this->session->userdata('nombreCompleto');
      $lastSessionD = $this->session->userdata('lastSession');
      $FooterCustomInsertJS = ["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js", "jquery.balloon.min.js", "aes.min.js", "aes-json-format.min.js", "dashboard/widget-empresa.js", "header.js", "routes.js"];
      $FooterCustomJS = "";
      $titlePage = "Conexión Empresas Online - Lotes";
      $idProductoS = $this->session->userdata('idProductoS');
      $idEmpresa = $this->session->userdata('acrifS');
      $programa = $this->session->userdata('nombreProductoS') . ' / ' . $this->session->userdata('marcaProductoS');

      $acidlote = $this->input->post('data-idTicket');
      $rtest[] = $this->callWSverDetalleBandeja($urlCountry, $acidlote);

      $menuHeader = $this->parser->parse('widgets/widget-menuHeader', array(), TRUE);
      $menuFooter = $this->parser->parse('widgets/widget-menuFooter', array(), TRUE);
      $header = $this->parser->parse('layouts/layout-header', array('bodyclass' => '', 'menuHeaderActive' => TRUE, 'menuHeaderMainActive' => TRUE, 'menuHeader' => $menuHeader, 'titlePage' => $titlePage), TRUE);
      $footer = $this->parser->parse('layouts/layout-footer', array('menuFooterActive' => TRUE, 'menuFooter' => $menuFooter, 'FooterCustomInsertJSActive' => TRUE, 'FooterCustomInsertJS' => $FooterCustomInsertJS, 'FooterCustomJSActive' => TRUE, 'FooterCustomJS' => $FooterCustomJS), TRUE);
      $content = $this->parser->parse('lotes/content-detallelote', array(
        'titulo' => $nombreCompleto,
        'lastSession' => $lastSessionD,
        'data' => $rtest
      ), TRUE);
      $sidebarLotes = $this->parser->parse('dashboard/widget-empresa', array('sidebarActive' => TRUE), TRUE);

      $datos = array(
        'header' => $header,
        'content' => $content,
        'footer' => $footer,
        'sidebar' => $sidebarLotes,
        'titleHeading' => 'TITULO ACA',
        'login' => 'LOGIN USUARIO',
        'password' => 'CONTRASEÑA',
        'loginBtn' => 'ENTRAR',
      );

      $this->parser->parse('layouts/layout-b', $datos);
    } elseif ($paisS != $urlCountry && $paisS != "") {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } else {
      echo "
			<script>alert('Enlace no permitido'); location.href = '" . $this->config->item('base_url') . "$urlCountry/login';</script>
			";
    }
  }

  /**
   * Pantalla que muestra el detalle del lote validado pendiente por confirmar
   *
   * @param  [string] $urlCountry [description]
   * @return [view]               [description]
   */
  public function pantallaConfirmacion($urlCountry)
  {
    //VALIDATE COUNTRY
    np_hoplite_countryCheck($urlCountry);
    $this->lang->load('lotes');
    $this->lang->load('dashboard');
    $this->lang->load('users');
    $this->load->library('parser');
    $this->lang->load('erroreseol');
    $logged_in = $this->session->userdata('logged_in');
    $idProductoS = $this->session->userdata('idProductoS');

    $menuP = $this->session->userdata('menuArrayPorProducto');
    $funcAct = in_array("tebcon", np_hoplite_modFunciones($menuP));

    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in && isset($idProductoS) && $funcAct !== false) {
      $username = $this->session->userdata('userName');
      $token = $this->session->userdata('token');
      $nombreCompleto = $this->session->userdata('nombreCompleto');
      $lastSessionD = $this->session->userdata('lastSession');
      $jsRte = '../../../js/';
      $thirdsJsRte = '../../../js/third_party/';
      $FooterCustomInsertJS = ["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js", "jquery.balloon.min.js", "jquery-md5.js", "aes.min.js", "aes-json-format.min.js", "dashboard/widget-empresa.js", "lotes/lotes-confirmacion.js", "header.js", "routes.js", $thirdsJsRte . "jquery.validate.min.js", $jsRte . "validate-forms.js", $thirdsJsRte . "additional-methods.min.js"];
      $FooterCustomJS = "";
      $titlePage = "Conexión Empresas Online - Lotes";
      $idProductoS = $this->session->userdata('idProductoS');
      $idEmpresa = $this->session->userdata('acrifS');
      $programa = $this->session->userdata('nombreProductoS') . ' / ' . $this->session->userdata('marcaProductoS');

      $acidlote = $this->input->post('data-idTicket');
      $rtest[] = $this->callWSverDetalleBandeja($urlCountry, $acidlote);

      $menuHeader = $this->parser->parse('widgets/widget-menuHeader', array(), TRUE);
      $menuFooter = $this->parser->parse('widgets/widget-menuFooter', array(), TRUE);
      $header = $this->parser->parse('layouts/layout-header', array('bodyclass' => '', 'menuHeaderActive' => TRUE, 'menuHeaderMainActive' => TRUE, 'menuHeader' => $menuHeader, 'titlePage' => $titlePage), TRUE);
      $footer = $this->parser->parse('layouts/layout-footer', array('menuFooterActive' => TRUE, 'menuFooter' => $menuFooter, 'FooterCustomInsertJSActive' => TRUE, 'FooterCustomInsertJS' => $FooterCustomInsertJS, 'FooterCustomJSActive' => TRUE, 'FooterCustomJS' => $FooterCustomJS), TRUE);
      $content = $this->parser->parse('lotes/content-confirmlotes', array(
        'titulo' => $nombreCompleto,
        'lastSession' => $lastSessionD,
        'data' => $rtest
      ), TRUE);
      $sidebarLotes = $this->parser->parse('dashboard/widget-empresa', array('sidebarActive' => TRUE), TRUE);

      $datos = array(
        'header' => $header,
        'content' => $content,
        'footer' => $footer,
        'sidebar' => $sidebarLotes,
        'titleHeading' => 'TITULO ACA',
        'login' => 'LOGIN USUARIO',
        'password' => 'CONTRASEÑA',
        'loginBtn' => 'ENTRAR'
      );

      $this->parser->parse('layouts/layout-b', $datos);
    } elseif ($paisS != $urlCountry && $paisS != "") {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } else {
      echo "
			<script>alert('Enlace no permitido'); location.href = '" . $this->config->item('base_url') . "$urlCountry/login';</script>
			";
    }
  }

  /**
   * Pantalla que muestra los lotes por firmar/autorizar.
   *
   * @param  string $urlCountry
   */
  public function pantallaAutorizacion($urlCountry)
  {
    //VALIDATE COUNTRY
    np_hoplite_countryCheck($urlCountry);
    $this->lang->load('lotes');
    $this->lang->load('dashboard');
    $this->lang->load('users');
    $this->lang->load('erroreseol');
    $this->load->library('parser');
    $logged_in = $this->session->userdata('logged_in');
    $idProductoS = $this->session->userdata('idProductoS');

    $menuP = $this->session->userdata('menuArrayPorProducto');
    $linkCarg = np_hoplite_existeLink($menuP, "TEBCAR");

    $linkAut = np_hoplite_existeLink($menuP, "TEBAUT");

    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in && $idProductoS && $linkAut !== false) {
      $username = $this->session->userdata('userName');
      $token = $this->session->userdata('token');
      $nombreCompleto = $this->session->userdata('nombreCompleto');
      $lastSessionD = $this->session->userdata('lastSession');
      $jsRte = '../../../js/';
      $thirdsJsRte = '../../../js/third_party/';
      $FooterCustomInsertJS = ["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js", "jquery.balloon.min.js", "jquery-md5.js", "aes.min.js", "aes-json-format.min.js", "dashboard/widget-empresa.js", "lotes/lotes-autorizacion.js", "jquery.dataTables.min.js", "header.js", "routes.js", $thirdsJsRte . "jquery.validate.min.js", $jsRte . "validate-forms.js", $thirdsJsRte . "additional-methods.min.js"];
      $FooterCustomJS = "";
      $titlePage = "Conexión Empresas Online - Lotes";

      if ($this->session->userdata('marcaProductoS') === 'Cheque') {
        $programa = $this->session->userdata('nombreProductoS');
      } else {
        $programa = $this->session->userdata('nombreProductoS') . ' / ' . ucwords($this->session->userdata('marcaProductoS'));
      }
      $lista = $this->input->post('tempIdOrdenL');

      /*si está entrando a está página desde el módulo de calculo OS, solicitar eliminación de lotes temporales,
			  caso contrario, solicitar directamente los lotes por autorizar*/
      if ($lista) {
        $rTest[] = $this->callWScancelarCalculoOS($urlCountry, $lista);
      } else {
        $rTest[] = $this->callWSbuscarLotesAutorizar($urlCountry);
      }

      $menuP = $this->session->userdata('menuArrayPorProducto');
      $funciones = np_hoplite_modFunciones($menuP);

      $menuHeader = $this->parser->parse('widgets/widget-menuHeader', array(), TRUE);
      $menuFooter = $this->parser->parse('widgets/widget-menuFooter', array(), TRUE);
      $header = $this->parser->parse('layouts/layout-header', array('bodyclass' => '', 'menuHeaderActive' => TRUE, 'menuHeaderMainActive' => TRUE, 'menuHeader' => $menuHeader, 'titlePage' => $titlePage), TRUE);
      $aviso = $this->parser->parse('widgets/widget-aviso', ['pais' => $urlCountry], TRUE);
      $footer = $this->parser->parse('layouts/layout-footer', array('menuFooterActive' => TRUE, 'menuFooter' => $menuFooter, 'FooterCustomInsertJSActive' => TRUE, 'FooterCustomInsertJS' => $FooterCustomInsertJS, 'FooterCustomJSActive' => TRUE, 'FooterCustomJS' => $FooterCustomJS), TRUE);
      $content = $this->parser->parse('lotes/content-authlotes', array(
        'titulo' => $nombreCompleto,
        'breadcrum' => '',
        'lastSession' => $lastSessionD,
        'programa' => $programa,
        'data' => $rTest,
        'funciones' => $funciones
      ), TRUE);

      /*Prueba de hz para ver el json que se envía a la vista*/
      $prueba = json_encode($rTest, JSON_UNESCAPED_UNICODE);
      log_message('info', 'Descripción de lote --->>> ' . $prueba);

      $sidebarLotes = $this->parser->parse('dashboard/widget-empresa', array('sidebarActive' => TRUE), TRUE);

      $datos = array(
        'header' => $header,
        'content' => $content,
        'footer' => $footer,
        'sidebar' => $sidebarLotes,
        'titleHeading' => 'TITULO ACA',
        'login' => 'LOGIN USUARIO',
        'password' => 'CONTRASEÑA',
        'loginBtn' => 'ENTRAR',
        'aviso' => $aviso,
        'pais' => $urlCountry
      );

      $this->parser->parse('layouts/layout-c', $datos);
    } else if ($linkAut === false && $linkCarg !== false) {
      echo "
			<script>
			if(location.href.indexOf('confirmacion')==-1){
				alert('Enlace no permitido');
			}

			location.href = '" . $this->config->item('base_url') . "$urlCountry/lotes';
			</script>
			";
    } elseif ($paisS != $urlCountry && $paisS != "") {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } else {

      echo "
			<script>alert('Enlace no permitido'); location.href = '" . $this->config->item('base_url') . "$urlCountry/login';</script>
			";
    }
  }

  /**
   * Método que realiza petición al WS para obtener el listado de los lotes por firmar/autorizar.
   *
   * @param  string $urlCountry
   * @return json
   */
  private function callWSbuscarLotesAutorizar($urlCountry)
  {
    $this->lang->load('erroreseol');

    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');
    $idEmpresa = $this->session->userdata('acrifS');
    $idProductoS = $this->session->userdata('idProductoS');
    $acodcia = $this->session->userdata('accodciaS');
    $acgrupo = $this->session->userdata('accodgrupoeS');

    $canal = "ceo";
    $modulo = "lotes";
    $function = "cargarAutorizar";
    $operation = "cargarAutorizar";
    $ip = $this->input->ip_address();
    $timeLog = date("m/d/Y H:i");
    $className = "com.novo.objects.TOs.LoteTO";

    $sessionId = $this->session->userdata('sessionId');

    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, "cargarAutorizar", 0, $ip, $timeLog);

    $data = array(
      "idOperation" => $operation,
      "className" => $className,
      "accodcia" => $acodcia,
      "accodgrupo" => $acgrupo,
      "actipoproducto" => $idProductoS,
      "accodusuarioc" => $username,
      "acrif" => $idEmpresa,
      "logAccesoObject" => $logAcceso,
      "token" => $token,
      "pais" => $urlCountry
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSbuscarLotesAutorizar');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSbuscarLotesAutorizar');
    $response = json_decode($jsonResponse);

    if ($response) {
      log_message('info', 'lotes aut ' . $response->rc);
      if ($response->rc == 0) {
        return $response;
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          return array('ERROR' => '-29');
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')');
          if (strpos($codigoError, 'Error') !== false) {
            $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'));
          }

          return $codigoError;
        }
      }
    } else {
      log_message('info', 'lotes aut No WS');
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }


  /**
   * Método que realiza petición al WS para cancelar el cálculo de la orden de servicio (pre-autorización).
   *
   * @param  string $urlCountry
   * @param  string (serialized) $lista
   * @return json
   */
  private function callWScancelarCalculoOS($urlCountry, $lista)
  {
    $this->lang->load('erroreseol');

    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');
    $idEmpresa = $this->session->userdata('acrifS');
    $idProductoS = $this->session->userdata('idProductoS');
    $acodcia = $this->session->userdata('accodciaS');
    $acgrupo = $this->session->userdata('accodgrupoeS');

    $canal = "ceo";
    $modulo = "lotes";
    $function = "cancelar calculo OS";
    $operation = "cancelarOS";
    $ip = $this->input->ip_address();
    $timeLog = date("m/d/Y H:i");
    $className = "com.novo.objects.MO.ListadoOrdenServicioMO";

    $sessionId = $this->session->userdata('sessionId');

    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $operation, 0, $ip, $timeLog);

    $lista = unserialize($lista);
    $listaAux = [];
    if ($lista) {
      foreach ($lista as $key => $value) {
        $listaAux[$key] = array('idOrdenTemp' => $value);
      }
    } else {
      $listaAux = array();
    }

    $datosRef = array('accodcia' => $acodcia, 'accodgrupo' => $acgrupo, 'actipoproducto' => $idProductoS, 'accodusuarioc' => $username, 'acrif' => $idEmpresa);

    $data = array(
      "idOperation" => $operation,
      "className" => $className,
      "lista" => $listaAux,
      "lotesNF" => [$datosRef],
      "logAccesoObject" => $logAcceso,
      "token" => $token,
      "pais" => $urlCountry
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);
    log_message('info', 'lotes cancelarOS ' . $data);
    $dataEncry = np_Hoplite_Encryption($data, 'callWScancelarCalculoOS');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWScancelarCalculoOS');
    $response = json_decode($jsonResponse);

    if ($response) {
      log_message('info', 'lotes cancelarOS ' . $response->rc);
      if ($response->rc == 0) {
        return $response;
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          return array('ERROR' => '-29');
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')');
          if (strpos($codigoError, 'Error') !== false) {
            $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'));
          }

          return $codigoError;
        }
      }
    } else {
      log_message('info', 'lotes cancelarOS No WS');
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }


  /**
   * Método que realiza la operación de primera firma de uno o varios lotes
   *
   * @param  string $urlCountry
   * @return json
   */
  public function firmarLote($urlCountry)
  {

    np_hoplite_countryCheck($urlCountry);
    $this->lang->load('lotes');
    $logged_in = $this->session->userdata('logged_in');
    //VALIDAR QUE USUARIO ESTE LOGGEDIN
    $paisS = $this->session->userdata('pais');
    $ordenS = $this->session->userdata('ordenS');

    $menuP = $this->session->userdata('menuArrayPorProducto');
    $linkAut = np_hoplite_existeLink($menuP, "TEBAUT");

    if ($paisS == $urlCountry && $logged_in && ($ordenS == '' || $ordenS == '0' || $ordenS == '1') && $linkAut !== false) {

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

      $pass = $dataRequest->data_pass;
      $halp1 = var_export($dataRequest->data_lotes, true);
      $lotes = explode(',', $dataRequest->data_lotes);
      $halp2 = var_export($lotes, true);
      array_pop($lotes);

      $rTest = $this->callWSfirmarLote($urlCountry, $pass, $lotes);

      $response = $this->cryptography->encrypt($rTest);

      $this->output->set_content_type('application/json')->set_output(json_encode($response));
    } elseif ($paisS != $urlCountry && $paisS != "") {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($ordenS != '' || $ordenS != '0' || $ordenS != '1') {
      $codigoError = array('ERROR' => lang('MSJ_NO_FIRMA'));
      $this->output->set_content_type('application/json')->set_output(json_encode($codigoError));
    } elseif ($this->input->is_ajax_request() && $linkAut !== false) {
      $this->output->set_content_type('application/json')->set_output(json_encode(array('ERROR' => '-29')));
    } else {
      redirect($urlCountry . '/login');
    }
  }

  /**
   * Método que realiza petición al WS para realizar la operación de primera firma de uno o varios lotes
   *
   * @param  string $urlCountry
   * @param  string $pass
   * @param  string $lotes
   * @return array
   */

  private function callWSfirmarLote($urlCountry, $pass, $lotes)
  {

    $this->lang->load('erroreseol');

    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');
    $idEmpresa = $this->session->userdata('acrifS');
    $producto = $this->session->userdata('idProductoS');
    $acodcia = $this->session->userdata('accodciaS');
    $acgrupo = $this->session->userdata('accodgrupoeS');

    $canal = "ceo";
    $modulo = "lotes";
    $function = "firmalote";
    $operacion = "firmarLote";
    $className = "com.novo.objects.MO.ListadoLotesMO";
    $ip = $this->input->ip_address();
    $timeLog = date("m/d/Y H:i");
    $lista = [];

    foreach ($lotes as $key => $value) {
      $lote = array(
        'acidlote' => $value,
        'accodcia' => $acodcia,
        'accodgrupo' => $acgrupo,
        'actipoproducto' => $producto
      );

      $lista[$key] = $lote;
    }

    $usuario = array(
      'userName' => $username,
      'password' => $pass
    );

    $sessionId = $this->session->userdata('sessionId');
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $operacion, 0, $ip, $timeLog);

    $data = array(
      "idOperation" => $operacion,
      "className" => $className,
      "lista" => $lista,
      "usuario" => $usuario,
      "logAccesoObject" => $logAcceso,
      "token" => $token,
      "pais" => $urlCountry
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSfirmarLote');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSfirmarLote');
    $response = json_decode($jsonResponse);

    if ($response) {
      log_message('info', 'firma lote ' . $response->rc);
      if ($response->rc == 0) {
        return $response;
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          return array('ERROR' => '-29');
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')');
          if ($response->rc == -1) {
            $codigoError = array('ERROR' => lang('MSG_INVALID_PASS'), "rc" => $response->rc);
          } else if (strpos($codigoError, 'Error') !== false) {
            $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'));
          }

          return $codigoError;
        }
      }
    } else {
      log_message('info', 'response anularOS No WS');
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }

  /**
   * Método para revocar la firma de un lote ya firmado por el mismo usuario conectado
   * @param  string $urlCountry
   * @return json $response
   */
  public function desasociarFirma($urlCountry)
  {

    np_hoplite_countryCheck($urlCountry);
    $logged_in = $this->session->userdata('logged_in');

    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in) {

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

      $lotes = explode(',', $dataRequest->data_lotes);
      array_pop($lotes);

      $pass = $dataRequest->data_pass;
      $rTest = $this->callWSdesasociarFirma($urlCountry, $lotes, $pass);

      $response = $this->cryptography->encrypt($rTest);
      $this->output->set_content_type('application/json')->set_output(json_encode($response, JSON_UNESCAPED_UNICODE));
    } elseif ($paisS != $urlCountry && $paisS != '') {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      $response = $this->cryptography->encrypt(array('ERROR' => '-29'));
      $this->output->set_content_type('application/json')->set_output(json_encode($response, JSON_UNESCAPED_UNICODE));
    } else {
      redirect($urlCountry . '/login');
    }
  }

  /**
   * Método que realiza petición al WS para desasociar la firma de un lote en módulo de lotes por firmar/autorizar
   * @param  string $urlCountry
   * @param  string $lotes
   * @param  string $pass
   * @return array
   */
  private function callWSdesasociarFirma($urlCountry, $lotes, $pass)
  {

    $this->lang->load('erroreseol');

    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');
    $producto = $this->session->userdata('idProductoS');
    $acodcia = $this->session->userdata('accodciaS');
    $acgrupo = $this->session->userdata('accodgrupoeS');

    $canal = "ceo";
    $modulo = "lotes";
    $function = "desasociafirma";
    $operacion = "desasociarFirma";
    $classname = "com.novo.objects.MO.ListadoLotesMO";
    $ip = $this->input->ip_address();
    $timeLog = date("m/d/Y H:i");
    $lista = [];

    foreach ($lotes as $key => $value) {
      $lote = array(
        'acidlote' => $value,
        'accodcia' => $acodcia,
        'accodgrupo' => $acgrupo,
        'actipoproducto' => $producto
      );

      $lista[$key] = $lote;
    }

    $usuario = array(
      'userName' => $username,
      'password' => $pass
    );

    $sessionId = $this->session->userdata('sessionId');
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $operacion, 0, $ip, $timeLog);

    $data = array(
      'idOperation' => $operacion,
      'className' => $classname,
      'lista' => $lista,
      'usuario' => $usuario,
      'logAccesoObject' => $logAcceso,
      'token' => $token,
      'pais' => $urlCountry
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSdesasociarFirma');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSdesasociarFirma');
    $response = json_decode($jsonResponse);

    if ($response) {
      switch ($response->rc) {
        case 0:
          $response = $response;
          break;
        case -61:
        case -29:
          $this->session->sess_destroy();
          $response = array('ERROR' => '-29');
          break;
        case -1:
        case -22:
          $response = array('ERROR' => lang('MSG_INVALID_PASS'));
          break;
        default:
          $codigoError = lang('ERROR_(' . $response->rc . ')');
          if (strpos($codigoError, 'Error') !== false) {
            $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'));
          }
          $response = $codigoError;
      }
    } else {
      $response = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
    return $response;
  }

  /**
   * Método para eliminar los lotes pendientes por firmar/autorizar.
   * @param  string $urlCountry
   * @return json
   */
  public function eliminarLotesPorAutorizar($urlCountry)
  {
    np_hoplite_countryCheck($urlCountry);
    $this->lang->load('lotes');
    $logged_in = $this->session->userdata('logged_in');

    $paisS = $this->session->userdata('pais');
    $menuP = $this->session->userdata('menuArrayPorProducto');
    $funcActiv =  in_array("tebeli", np_hoplite_modFunciones($menuP));

    if ($paisS == $urlCountry && $logged_in && $funcActiv != false) {

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

      $lotes = explode(',', $dataRequest->data_lotes);
      $acnumlote = explode(',', $dataRequest->data_acnumlote);
      $actipolote = explode(',', $dataRequest->data_ctipolote);
      array_pop($lotes);
      array_pop($acnumlote);
      array_pop($actipolote);

      $pass = $dataRequest->data_pass;


      $rTest = $this->callWSeliminarLotesPorAutorizar($urlCountry, $lotes, $acnumlote, $actipolote, $pass);
      $response = $this->cryptography->encrypt($rTest);

      $this->output->set_content_type('application/json')->set_output(json_encode($response));
    } elseif ($paisS != $urlCountry && $paisS != "") {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($paisS == $urlCountry && $logged_in && $funcActiv == false) {
      $codigoError = array('ERROR' => lang('MSJ_NO_TEBELI'));
      $this->output->set_content_type('application/json')->set_output(json_encode($codigoError));
    } elseif ($this->input->is_ajax_request() && $funcActiv !== false) {
      $this->output->set_content_type('application/json')->set_output(json_encode(array('ERROR' => '-29')));
    } else {
      redirect($urlCountry . '/login');
    }
  }

  /**
   * Método que realiza petición al WS para eliminar los lotes pendientes por firmar/autorizar.
   * @param  string $urlCountry
   * @param  string $lotes
   * @param  string $acnumlote
   * @param  string $actipolote
   * @param  string $pass
   * @return array
   */
  private function callWSeliminarLotesPorAutorizar($urlCountry, $lotes, $acnumlote, $actipolote, $pass)
  {

    $this->lang->load('erroreseol');

    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');
    $idEmpresa = $this->session->userdata('acrifS');

    $canal = "ceo";
    $modulo = "lotes";
    $function = "eliminaLotePorAutorizar";
    $operacion = "eliminarLotesPorAutorizar";
    $classname = "com.novo.objects.MO.AutorizarLoteMO";
    $ip = $this->input->ip_address();
    $timeLog = date("m/d/Y H:i");

    $lista = [];

    foreach ($lotes as $key => $value) {
      $lote = array(
        'acrif' => $idEmpresa,
        'acidlote' => $value,
        'acnumlote' => $acnumlote[$key],
        'ctipolote' => $actipolote[$key]
      );
      $lista[$key] = $lote;
    }

    $listalotes = array('lista' => $lista);

    $usuario = array(
      'userName' => $username,
      'password' => $pass
    );

    $sessionId = $this->session->userdata('sessionId');
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $operacion, 0, $ip, $timeLog);

    $data = array(
      'idOperation' => $operacion,
      'className' => $classname,
      'listaLotes' => $listalotes,
      'usuario' => $usuario,
      'logAccesoObject' => $logAcceso,
      'token' => $token,
      'pais' => $urlCountry
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSeliminarLotesPorAutorizar');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSeliminarLotesPorAutorizar');
    $response = json_decode($jsonResponse);


    if ($response) {
      log_message('info', 'elminar lote auth ' . $response->rc . "/" . $response->msg);
      if ($response->rc == 0) {
        return $response;
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          return array('ERROR' => '-29');
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')');
          if (strpos($codigoError, 'Error') !== false) {
            $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'));
          }

          return $codigoError;
        }
      }
    } else {
      log_message('info', 'elminar lote auth NO WS');
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }

  /**
   * Pantalla para mostrar información detallada del lote pendiente por firmar/autorizar.
   * @param  string $urlCountry
   * @return view
   */
  public function detalleLoteAuth($urlCountry)
  {

    np_hoplite_countryCheck($urlCountry);
    $this->lang->load('dashboard');
    $this->lang->load('lotes');
    $this->lang->load('users');
    $this->load->library('parser');
    $this->lang->load('erroreseol');

    $logged_in = $this->session->userdata('logged_in');
    $idProductoS = $this->session->userdata('idProductoS');

    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in && $idProductoS) {

      $nombreCompleto = $this->session->userdata('nombreCompleto');
      $lastSessionD = $this->session->userdata('lastSession');
      $FooterCustomInsertJS = ["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js", "jquery.balloon.min.js", "aes.min.js", "aes-json-format.min.js", "dashboard/widget-empresa.js", "lotes/lotes-autorizacion.js", "jquery.dataTables.min.js", "header.js", "routes.js"];
      $FooterCustomJS = "";
      $titlePage = "Conexión Empresas Online - Lotes";
      $programa = $this->session->userdata('nombreProductoS') . ' / ' . $this->session->userdata('marcaProductoS');

      $acidlote = $this->input->post('data-lote');
      if ($acidlote) {
        $rtest[] = $this->callWSdetalleLoteAutorizar($urlCountry, $acidlote);
      } else {
        redirect($urlCountry . '/lotes/autorizacion');
      }

      $dataOS = $this->input->post('data-OS');
      $dataCOS = $this->input->post('data-COS');
      $dataLF = $this->input->post('data-LF');

      $menuHeader = $this->parser->parse('widgets/widget-menuHeader', array(), TRUE);
      $menuFooter = $this->parser->parse('widgets/widget-menuFooter', array(), TRUE);
      $header = $this->parser->parse('layouts/layout-header', array('bodyclass' => '', 'menuHeaderActive' => TRUE, 'menuHeaderMainActive' => TRUE, 'menuHeader' => $menuHeader, 'titlePage' => $titlePage), TRUE);
      $footer = $this->parser->parse('layouts/layout-footer', array('menuFooterActive' => TRUE, 'menuFooter' => $menuFooter, 'FooterCustomInsertJSActive' => TRUE, 'FooterCustomInsertJS' => $FooterCustomInsertJS, 'FooterCustomJSActive' => TRUE, 'FooterCustomJS' => $FooterCustomJS), TRUE);
      $content = $this->parser->parse('lotes/content-detalleauthlote', array(
        'acidlote' => $acidlote,
        'titulo' => $nombreCompleto,
        'lastSession' => $lastSessionD,
        'data' => $rtest,
        'dataOS' => $dataOS,
        'dataCOS' => $dataCOS,
        'dataLF' => $dataLF
      ), TRUE);
      $sidebarLotes = $this->parser->parse('dashboard/widget-empresa', array('sidebarActive' => TRUE), TRUE);

      $datos = array(
        'header' => $header,
        'content' => $content,
        'footer' => $footer,
        'sidebar' => $sidebarLotes,
        'titleHeading' => 'TITULO ACA',
        'login' => 'LOGIN USUARIO',
        'password' => 'CONTRASEÑA',
        'loginBtn' => 'ENTRAR',
      );

      $this->parser->parse('layouts/layout-b', $datos);
    } elseif ($paisS != $urlCountry && $paisS != "") {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } else {
      redirect($urlCountry . '/login');
    }
  }

  /**
   * Método que realiza petición al WS para obtener la información detallada del lote pendiente por firmar/autorizar
   * @param  string $urlCountry
   * @param  string $acidlote
   * @return array
   */
  private function callWSdetalleLoteAutorizar($urlCountry, $acidlote)
  {
    $this->lang->load('erroreseol');

    $canal = "ceo";
    $modulo = "lotes";
    $function = "verdetallelote";
    $operacion = "detalleLote";
    $classname = "com.novo.objects.TOs.LoteTO";
    $ip = $this->input->ip_address();
    $timeLog = date("m/d/Y H:i");

    $sessionId = $this->session->userdata('sessionId');
    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $operacion, 0, $ip, $timeLog);

    $data = array(
      'idOperation' => $operacion,
      'className' => $classname,
      'acidlote' => $acidlote,
      'logAccesoObject' => $logAcceso,
      'token' => $token,
      'pais' => $urlCountry
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    log_message('DEBUG', 'REQUEST DETALLE DEL LOTE: ' . $data);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSdetalleLoteAutorizar');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSdetalleLoteAutorizar');

    log_message('info', 'detalle loteAuth ' . $jsonResponse);

    $response = json_decode($jsonResponse);


    if ($response) {
      log_message('info', 'detalle loteAuth ' . $response->rc);
      if ($response->rc == 0) {
        return $response;
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          return array('ERROR' => '-29');
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')');
          if (strpos($codigoError, 'Error') !== false) {
            $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'));
          }
          return $codigoError;
        }
      }
    } else {
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }

  public function expdetalleLoteAuthXLS($urlCountry)
  {

    np_hoplite_countryCheck($urlCountry);

    $this->lang->load('erroreseol'); //HOJA DE ERRORES;

    $canal = "ceo";
    $modulo    = "lotes";
    $function  = "verdetallelote";
    $operation = "detalleLoteExcel";
    $className = "com.novo.objects.TOs.LoteTO";
    $timeLog   = date("m/d/Y H:i");
    $ip = $this->input->ip_address();
    $sessionId = $this->session->userdata('sessionId');
    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $operation, 0, $ip, $timeLog);

    $logged_in = $this->session->userdata('logged_in');

    $paisS = $this->session->userdata('pais');

    $menuP = $this->session->userdata('menuArrayPorProducto');
    $moduloAct = np_hoplite_existeLink($menuP, "TEBAUT");
    if ($urlCountry == 'Ec-bp') {
      $moduloActTebAut = np_hoplite_existeLink($menuP, "TEBAUT");
      $moduloActTebOrs = np_hoplite_existeLink($menuP, "TEBORS");
      $moduloAct = $moduloActTebAut !== FALSE || $moduloActTebOrs !== FALSE;
    }

    if ($paisS == $urlCountry && $logged_in && $moduloAct !== false) {

      $acidlote = $this->input->post('data-lote');

      $data = array(
        "pais" => $urlCountry,
        "idOperation" => $operation,
        "className" => $className,
        "acidlote" => $acidlote,
        "logAccesoObject" => $logAcceso,
        "token" => $token
      );

      $data = json_encode($data, JSON_UNESCAPED_UNICODE);

      $dataEncry = np_Hoplite_Encryption($data, 'expdetalleLoteAuthXLS');
      $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
      $data = json_encode($data);
      $response = np_Hoplite_GetWS($data);
      $jsonResponse = np_Hoplite_Decrypt($response, 'expdetalleLoteAuthXLS');

      $response =  json_decode($jsonResponse);

      if ($response) {
        log_message('info', 'detalleLoteAuth XLS ' . $response->rc . "/" . $response->msg);
        if ($response->rc == 0) {
          np_hoplite_byteArrayToFile($response->archivo, "xls", $response->nombre);
        } else {

          if ($response->rc == -61 || $response->rc == -29) {
            $this->session->sess_destroy();
            echo "<script>alert('usuario actualmente desconectado');
                        window.history.back(-1);</script>";
          } else {
            $codigoError = lang('ERROR_(' . $response->rc . ')');
            if (strpos($codigoError, 'Error') !== false) {
              $codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc" => $response->rc);
            } else {
              $codigoError = array('mensaje' => lang('ERROR_(' . $response->rc . ')'), "rc" => $response->rc);
            }
            echo '<script languaje=\"javascript\">alert("' . $codigoError["mensaje"] . '"); history.back();</script>';
            return $codigoError;
          }
        }
      } else {
        log_message('info', 'detalleLoteAuth XLS NO WS');
        echo "
                <script>
                alert('" . lang('ERROR_GENERICO_USER') . "');
                window.history.back(-1);
                </script>";
      }
    } elseif ($paisS != $urlCountry && $paisS != "") {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } else {
      redirect($urlCountry . '/login');
    }
  }

  /**
   * Método para exportar en formato PDF los datos visualizados en el reporte de cuenta concentradora.
   *
   * @param  string $urlCountry
   * @return bytes
   */
  public function expdetalleLoteAuthPDF($urlCountry)
  {

    np_hoplite_countryCheck($urlCountry);
    $this->lang->load('erroreseol'); //HOJA DE ERRORES;
    $canal = "ceo";
    $modulo    = "lotes";
    $function  = "verdetallelote";
    $operation = "detalleLotePDF";
    $className = "com.novo.objects.TOs.LoteTO";
    $timeLog   = date("m/d/Y H:i");
    $ip = $this->input->ip_address();
    $sessionId = $this->session->userdata('sessionId');
    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $operation, 0, $ip, $timeLog);

    $logged_in = $this->session->userdata('logged_in');

    $paisS = $this->session->userdata('pais');

    $menuP = $this->session->userdata('menuArrayPorProducto');
    $moduloAct = np_hoplite_existeLink($menuP, "TEBAUT");
    if ($urlCountry == 'Ec-bp') {
      $moduloActTebAut = np_hoplite_existeLink($menuP, "TEBAUT");
      $moduloActTebOrs = np_hoplite_existeLink($menuP, "TEBORS");
      $moduloAct = $moduloActTebAut !== FALSE || $moduloActTebOrs !== FALSE;
    }

    if ($paisS == $urlCountry && $logged_in && $moduloAct !== false) {

      $acidlote = $this->input->post('data-lote');

      $data = array(
        "pais" => $urlCountry,
        "idOperation" => $operation,
        "className" => $className,
        "acidlote" => $acidlote,
        "logAccesoObject" => $logAcceso,
        "token" => $token
      );

      $data = json_encode($data, JSON_UNESCAPED_UNICODE);

      $dataEncry = np_Hoplite_Encryption($data, 'expdetalleLoteAuthPDF');
      $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
      $data = json_encode($data);
      $response = np_Hoplite_GetWS($data);
      $jsonResponse = np_Hoplite_Decrypt($response, 'expdetalleLoteAuthPDF');

      $response =  json_decode($jsonResponse);

      if ($response) {
        log_message('info', 'detalleLoteAuth PDF ' . $response->rc . "/" . $response->msg);
        if ($response->rc == 0) {
          np_hoplite_byteArrayToFile($response->archivo, "pdf", $response->nombre);
        } else {

          if ($response->rc == -61 || $response->rc == -29) {
            $this->session->sess_destroy();
            echo "<script>alert('usuario actualmente desconectado');
                        window.history.back(-1);</script>";
          } else {
            $codigoError = lang('ERROR_(' . $response->rc . ')');
            if (strpos($codigoError, 'Error') !== false) {
              $codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'), "rc" => $response->rc);
            } else {
              $codigoError = array('mensaje' => lang('ERROR_(' . $response->rc . ')'), "rc" => $response->rc);
            }
            echo '<script languaje=\"javascript\">alert("' . $codigoError["mensaje"] . '"); history.back();</script>';
            return $codigoError;
          }
        }
      } else {
        log_message('info', 'depositosdegarantias PDF NO WS ');
        echo "
                <script>
                alert('" . lang('ERROR_GENERICO_USER') . "');
                window.history.back(-1);
                </script>";
      }
    } elseif ($paisS != $urlCountry && $paisS != "") {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } else {
      redirect($urlCountry . '/login');
    }
  }

  /**
   * Método que realiza petición al WS para solicitar los lotes pendientes por confirmar (bandeja)
   * @param  string $urlCountry
   * @param  string $idEmpresa
   * @param  string $codProducto
   * @return array
   */
  private function callWSbuscarLotesPorConfirmar($urlCountry, $idEmpresa, $codProducto)
  {
    $this->lang->load('erroreseol');
    $canal = "ceo";
    $modulo = "cargaLotes";
    $function = "cargaLotes";
    $operation = "buscarLotesPorConfirmar";
    $className = "com.novo.objects.MO.ConfirmarLoteMO";
    $timeLog = date("m/d/Y H:i");
    $ip = $this->input->ip_address();

    $sessionId = $this->session->userdata('sessionId');
    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, "buscarLotesPorConfirmar", 0, $ip, $timeLog);

    $lotesTO = array(
      "idEmpresa" => $idEmpresa,
      "codProducto" => $codProducto
    );

    $usuario = array(
      "userName" => $username
    );

    $data = array(
      "idOperation" => $operation,
      "className" => $className,
      "lotesTO" => $lotesTO,
      "usuario" => $usuario,
      "logAccesoObject" => $logAcceso,
      "token" => $token,
      "pais" => $urlCountry
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);
    $dataEncry = np_Hoplite_Encryption($data, 'callWSbuscarLotesPorConfirmar');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSbuscarLotesPorConfirmar');
    $response = json_decode($jsonResponse);

    if ($response) {
      log_message('info', 'bandeja ' . $response->rc . "/" . $response->msg);

      if ($response->rc == 0) {
        return $response;
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          return array('ERROR' => '-29');
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')');
          if (strpos($codigoError, 'Error') !== false) {
            $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'));
          }
        }
        return $codigoError;
      }
    } else {
      log_message('info', 'bandeja No WS');
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }

  /**
   * Método que realiza petición al WS para obtener el listado de tipos de lote a ser cargado.
   * @param   string $urlCountry
   * @param   string $codProducto
   * @return  array
   */
  private function callWSconsultarTipoLote($urlCountry, $codProducto)
  {
    $this->lang->load('erroreseol');
    $canal = "ceo";
    $modulo = "consultarTipoLote";
    $function = "consultarTipoLote";
    $operation = "consultarTipoLote";
    $className = "com.novo.objects.MO.ConfirmarLoteMO";
    $timeLog = date("m/d/Y H:i");
    $ip = $this->input->ip_address();

    $sessionId = $this->session->userdata('sessionId');
    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, "consultarTipoLote", 0, $ip, $timeLog);

    $lotesTO = array(
      "codProducto" => $codProducto
    );

    $usuario = array(
      "userName" => $username
    );

    $data = array(
      "idOperation" => $operation,
      "className" => $className,
      "lotesTO" => $lotesTO,
      "usuario" => $usuario,
      "logAccesoObject" => $logAcceso,
      "token" => $token,
      "pais" => $urlCountry
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    log_message('info', 'request consultarTipoLote ' . $data);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSconsultarTipoLote');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSconsultarTipoLote');
    log_message('DEBUG', 'NOVO [' . $username . '] RESPONSE: callWSconsultarTipoLote: ' . $jsonResponse);
    $response = json_decode($jsonResponse);

    if ($response) {

      if ($response->rc == 0) {
        return $response;
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          return array('ERROR' => '-29');
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')');
          if (strpos($codigoError, 'Error') !== false) {
            $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'));
          }

          return $codigoError;
        }
      }
    } else {
      log_message('info', 'consultarTipoLote NO WS');
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }

  /**
   * Método que realiza petición al WS para cargar archivo de lotes
   * @param  string $urlCountry
   * @param  string $codProducto
   * @param  string $formato
   * @param  string $nombreArchivo
   * @param  string $nombreOriginal
   * @param  string $idEmpresa
   * @param  string $tipoLote
   * @return array
   */
  private function callWScargarArchivo($urlCountry, $codProducto, $formato, $nombreOriginal, $nombreArchivo, $idEmpresa, $tipoLote, $formatolote)
  {
    $this->lang->load('erroreseol');
    $canal = "ceo";
    $modulo = "cargarArchivo";
    $function = "cargarArchivo";
    $operation = "cargarArchivo";
    $className = "com.novo.objects.MO.ConfirmarLoteMO";
    $timeLog = date("m/d/Y H:i");
    $ip = $this->input->ip_address();

    $sessionId = $this->session->userdata('sessionId');
    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, "cargarArchivo", 0, $ip, $timeLog);

    $acodcia = $this->session->userdata('accodciaS');

    $lotesTO = array(
      "codProducto" => $codProducto,
      "formato" => $formato,
      "nombre" => $nombreOriginal,
      "nombreArchivo" => $nombreArchivo,
      "idEmpresa" => $idEmpresa,
      "codCia" => $acodcia,
      "idTipoLote" => $tipoLote,
      "usuario" => $username,
      "formatoLote" => $formatolote
    );

    $usuario = array(
      "userName" => $username
    );

    $data = array(
      "idOperation" => $operation,
      "className" => $className,
      "lotesTO" => $lotesTO,
      "usuario" => $usuario,
      "logAccesoObject" => $logAcceso,
      "token" => $token,
      "pais" => $urlCountry
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWScargarArchivo');

    log_message('info', "cargaLote " . $data);

    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWScargarArchivo');
    $response = json_decode($jsonResponse);
    log_message('info', "cargaLote " . $jsonResponse);
    if ($response) {

      if ($response->rc == 0 || $response->rc == -128) {
        return $response;
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          return array('ERROR' => '-29');
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')');
          if (strpos($codigoError, 'Error') !== false) {
            $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'));
          }

          return $codigoError;
        }
      }
    } else {
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }

  /**
   * Método que realiza petición al WS para eliminar los lotes pendientes por confirmar (lotes pendiente, bandeja)
   * @param  string $urlCountry
   * @param  string $idTicket
   * @param  string $idLote
   * @param  string $username
   * @param  string $password
   * @param  string $token
   * @return array
   */
  private function callWSeliminarLoteNoConfirmado($urlCountry, $idTicket, $idLote, $username, $password, $token)
  {
    $this->lang->load('erroreseol');
    $canal = "ceo";
    $modulo = "Lotes";
    $function = "eliminarLoteNoConfirmado";
    $operation = "eliminarLoteNoConfirmado";
    $className = "com.novo.objects.MO.ConfirmarLoteMO";
    $timeLog = date("m/d/Y H:i");
    $ip = $this->input->ip_address();

    $sessionId = $this->session->userdata('sessionId');
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, "eliminarLoteNoConfirmado", 0, $ip, $timeLog);

    $lotesTO = array(
      "idTicket" => $idTicket,
      "idLote" => $idLote
    );

    $usuario = array(
      "userName" => $username,
      "password" => $password
    );

    $data = array(
      "idOperation" => $operation,
      "className" => $className,
      "lotesTO" => $lotesTO,
      "usuario" => $usuario,
      "logAccesoObject" => $logAcceso,
      "token" => $token,
      "pais" => $urlCountry
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);
    $dataEncry = np_Hoplite_Encryption($data, 'callWSeliminarLoteNoConfirmado');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSeliminarLoteNoConfirmado');
    $response = json_decode($jsonResponse);

    if ($response) {
      log_message('info', 'borrar lote sin conf ' . $response->rc . '/' . $response->msg);
      if ($response->rc == 0) {
        return $response;
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          return array('ERROR' => '-29');
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')');
          if ($response->rc == -1) {
            $codigoError = array('ERROR' => lang('MSG_INVALID_PASS'), "rc" => $response->rc);
          } else if (strpos($codigoError, 'Error') !== false) {
            $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'));
          }

          return $codigoError;
        }
      }
    } else {
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }

  /**
   * Método para obtener el listado de lotes pendientes por confimar (lotes pendiente, bandeja)
   * @param  string $urlCountry
   * @return json
   */
  public function getLotesPorConfirmarJSON($urlCountry)
  {
    np_hoplite_countryCheck($urlCountry);
    $logged_in = $this->session->userdata('logged_in');
    //VALIDAR QUE USUARIO ESTE LOGGEDIN
    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in) {
      $username = $this->session->userdata('userName');
      $token = $this->session->userdata('token');
      $idProductoS = $this->session->userdata('idProductoS');
      $idEmpresa = $this->session->userdata('acrifS');

      $rTest = $this->callWSbuscarLotesPorConfirmar($urlCountry, $idEmpresa, $idProductoS);
      $menuP = $this->session->userdata('menuArrayPorProducto');
      $funciones = np_hoplite_modFunciones($menuP);

      $r["result"] = $rTest;
      $r["funciones"] = $funciones;

      $r = $this->cryptography->encrypt($r);

      $this->output->set_content_type('application/json')->set_output(json_encode($r));
    } elseif ($paisS != $urlCountry && $paisS != '') {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      $this->output->set_content_type('application/json')->set_output(json_encode(array('ERROR' => '-29')));
    } else {
      redirect($urlCountry . '/login');
    }
  }


  /**
   * Método para cargar el archivo masivo de lotes en el servidor y enviar la petición al WS
   * @param string $urlCountry
   * @return string
   */
  public function cargarLotes($urlCountry)
  {
    np_hoplite_countryCheck($urlCountry);

    $logged_in = $this->session->userdata('logged_in');
    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in) {

      $this->lang->load('dashboard');
      $this->lang->load('users');
      $this->lang->load('upload');
      $this->lang->load('erroreseol');
      $this->load->library('parser');
      $createDirectory = lang('GEN_UPLOAD_NOT_CREATE_DIRECTORY');

      if (!is_dir(UPLOAD_PATH . $this->config->item('country'))) {
        if (mkdir(UPLOAD_PATH . $this->config->item('country'), 0755, TRUE)) {
          $createDirectory = lang('GEN_UPLOAD_CREATE_DIRECTORY');
        };
      }

      $config['upload_path'] = UPLOAD_PATH . $this->config->item('country') . '/';

      log_message('DEBUG', 'uploadFiles directory ' . $config['upload_path'] . ' ' . $createDirectory);

      $config['allowed_types'] = '*';
      $this->load->library('upload', $config);
      //VERIFICAR SI NO SUBIO ARCHIVO

      if (!$this->upload->do_upload()) {
        $responseError = ['ERROR' => 'No se puede cargar el archivo. Verifícalo e intenta de nuevo'];
        $responseError = $this->cryptography->encrypt($responseError);
        $this->output->set_content_type('application/json')->set_output(json_encode($responseError));
        //$error = array('ERROR' => 'No se puede cargar el archivo. Verifiquelo e intente de nuevo');// $this->upload->display_errors());
        //echo json_encode($error);
      } else {
        //VALIDO
        $data = array('upload_data' => $this->upload->data());
        $nombreArchivo = $data["upload_data"]["raw_name"]; //NOMBRE ARCHIVO SIN EXTENSION
        $rutaArchivo = $data["upload_data"]["file_path"];
        $extensionArchivo = $data["upload_data"]["file_ext"];
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
        $tipoLote = $dataRequest->data_tipoLote;
        $formatolote = $dataRequest->data_formatolote;
        $ch = curl_init();
        $localfile = $config['upload_path'] . $nombreArchivo . $extensionArchivo;
        $fp = fopen($localfile, 'r');
        $nombreArchivoNuevo = date("YmdHis") . $nombreArchivo . $extensionArchivo;
        $URL_TEMPLOTES = BULK_FTP_URL . $this->config->item('country') . '/';
        $LOTES_USERPASS = BULK_FTP_USERNAME . ':' . BULK_FTP_PASSWORD;

        log_message('DEBUG', 'uploadFiles sftp ' . $URL_TEMPLOTES);

        curl_setopt($ch, CURLOPT_URL, $URL_TEMPLOTES . $nombreArchivoNuevo);
        curl_setopt($ch, CURLOPT_UPLOAD, 1);
        curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_SFTP);
        curl_setopt($ch, CURLOPT_INFILE, $fp);
        curl_setopt($ch, CURLOPT_INFILESIZE, filesize($localfile));
        curl_exec($ch);
        $error_no = curl_errno($ch);
        log_message('ERROR', "subiendo archivo lotes sftp " . $error_no . "/" . lang("SFTP(" . $error_no . ")"));
        curl_close($ch);

        if ($error_no == 0) {
          fclose($fp);
          unlink("$localfile");  //BORRAR ARCHIVO
          $error = 'Archivo Movido.';
          //COLOCAR LLAMADO DE LA FUNCION CUANDO ESTE CORRECTO
          $formatoArchivo = substr($extensionArchivo, 1);
          $username = $this->session->userdata('userName');
          $token = $this->session->userdata('token');
          $idProductoS = $this->session->userdata('idProductoS');
          $idEmpresa = $this->session->userdata('acrifS');
          $cargaLote = $this->callWScargarArchivo($urlCountry, $idProductoS, $formatoArchivo, $nombreArchivo, $nombreArchivoNuevo, $idEmpresa, $tipoLote, $formatolote);
          $cargaLote = $this->cryptography->encrypt($cargaLote);
          $this->output->set_content_type('application/json')->set_output(json_encode($cargaLote));
          //echo json_encode($cargaLote);

        } else {
          $responseError = ['ERROR' => 'Falla al mover archivo.'];
          $responseError = $this->cryptography->encrypt($responseError);
          $this->output->set_content_type('application/json')->set_output(json_encode($responseError));
        }
      }
    } elseif ($paisS != $urlCountry && $paisS != '') {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      $responseError = ['ERROR' => '-29'];
      $responseError = $this->cryptography->encrypt($responseError);
      $this->output->set_content_type('application/json')->set_output(json_encode($responseError));
    } else {
      redirect($urlCountry . '/login');
    }
  }

  /**
   * Método para eliminar el lote pendiente por confirmar.
   * @param  string $urlCountry
   * @return json
   */
  public function eliminarLotes($urlCountry)
  {
    np_hoplite_countryCheck($urlCountry);
    $this->lang->load('erroreseol');
    $this->load->library('parser');

    $menuP = $this->session->userdata('menuArrayPorProducto');
    $funcAct = in_array("tebelc", np_hoplite_modFunciones($menuP));
    $eliminarLotes = '';

    $paisS = $this->session->userdata('pais');
    $logged_in = $this->session->userdata('logged_in');

    if ($this->input->is_ajax_request()) {
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
      $_POST['data-idTicket'] = $dataRequest->data_idTicket;
      $_POST['data-pass'] = $dataRequest->data_pass;

      $this->load->library('form_validation');
      $this->form_validation->set_rules('data-idTicket', 'idTicket',  'required');
      $this->form_validation->set_rules('data-pass', 'pass',  'required');

      if ($this->form_validation->run() == FALSE) {
        $responseError = ['ERROR' => lang('ERROR_(-1)'), "rc" => "-1"];
        $responseError = $this->cryptography->encrypt($responseError);
        $this->output->set_content_type('application/json')->set_output(json_encode($responseError));
        //FALLO VALIDACION
      } else {
        if ($paisS == $urlCountry && $logged_in) {

          $idTicket = $dataRequest->data_idTicket;
          $password = $dataRequest->data_pass;
          $idLote = $dataRequest->data_idLote;
          $username = $this->session->userdata('userName');
          $token = $this->session->userdata('token');
          unset($_POST['data-idTicket'], $_POST['data-pass']);

          if ($funcAct) {
            $eliminarLotes = $this->callWSeliminarLoteNoConfirmado($urlCountry, $idTicket, $idLote, $username, $password, $token);
          } else {
            $responseError = ["ERROR" => lang('SIN_FUNCION')];
            $responseError = $this->cryptography->encrypt($responseError);
            $eliminarLotes = $this->output->set_content_type('application/json')->set_output(json_encode($responseError));
          }
        } elseif ($this->input->is_ajax_request()) {
          $responseError = ['ERROR' => lang('ERROR_(-29)'), "rc" => "-29"];
          $responseError = $this->cryptography->encrypt($responseError);
          $this->output->set_content_type('application/json')->set_output(json_encode($responseError));
        } else {
          redirect($urlCountry . 'login');
        }
      }
    }
    $eliminarLotes = $this->cryptography->encrypt($eliminarLotes);
    $this->output->set_content_type('application/json')->set_output(json_encode($eliminarLotes));
  }

  /**
   * Método que realiza petición al WS para obtener información del detalle del lote por confirmar
   * @param   string $urlCountry
   * @param   string $idTicket
   * @return  array
   */
  private function callWSverDetalleBandeja($urlCountry, $idTicket)
  {
    $this->lang->load('erroreseol');

    if ($idTicket) {

      $this->lang->load('erroreseol');
      $canal = "ceo";
      $modulo = "verDetalleBandeja";
      $function = "verDetalleBandeja";
      $operation = "verDetalleBandeja";
      $className = "com.novo.objects.MO.ConfirmarLoteMO";
      $timeLog = date("m/d/Y H:i");
      $ip = $this->input->ip_address();
      $username = $this->session->userdata('userName');
      $token = $this->session->userdata('token');
      $sessionId = $this->session->userdata('sessionId');
      $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, "verDetalleBandeja", 0, $ip, $timeLog);

      $lotesTO = array(
        "idTicket" => $idTicket
      );

      $usuario = array(
        "userName" => $username
      );

      $data = array(
        "idOperation" => $operation,
        "className" => $className,
        "lotesTO" => $lotesTO,
        "usuario" => $usuario,
        "logAccesoObject" => $logAcceso,
        "token" => $token,
        "pais" => $urlCountry
      );

      $data = json_encode($data, JSON_UNESCAPED_UNICODE);

      $dataEncry = np_Hoplite_Encryption($data, 'callWSverDetalleBandeja');
      $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
      $data = json_encode($data);
      $response = np_Hoplite_GetWS($data);
      $jsonResponse = np_Hoplite_Decrypt($response, 'callWSverDetalleBandeja');
      $response = json_decode($jsonResponse);

      if ($response) {

        log_message('info', 'detalleLote ' . $response->rc . '/' . $response->msg);

        if ($response->rc == 0) {
          log_message('info', 'detalleLote json - ' . $jsonResponse);
          return $response;
        } else {
          if ($response->rc == -61 || $response->rc == -29) {
            $this->session->sess_destroy();
            return array('ERROR' => '-29');
          } else {
            $codigoError = lang('ERROR_(' . $response->rc . ')');
            if (strpos($codigoError, 'Error') !== false) {
              $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
            } else {
              $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'));
            }

            return $codigoError;
          }
        }
      } else {
        log_message('info', 'detalleLote NO WS ');
        return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
      }
    } else {
      redirect($urlCountry . '/login');
    }
  }

  /**
   * Método que realiza la operación de confirmación de lote
   * @param  string $urlCountry
   * @return json
   */
  public function confirmarLote($urlCountry)
  {

    np_hoplite_countryCheck($urlCountry);
    $this->lang->load('erroreseol');

    $menuP = $this->session->userdata('menuArrayPorProducto');
    $funcAct = in_array("tebcon", np_hoplite_modFunciones($menuP));

    $linkAut = np_hoplite_existeLink($menuP, "TEBAUT");

    $logged_in = $this->session->userdata('logged_in');

    //VALIDAR QUE USUARIO ESTE LOGGEDIN
    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in) {
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
      $pass = $dataRequest->pass;
      $embozo1 = isset($dataRequest->embozo1) ? $dataRequest->embozo1 : '';
      $embozo2 = isset($dataRequest->embozo2) ? $dataRequest->embozo2 : '';
      $conceptoAbono = isset($dataRequest->conceptoDim) ? $dataRequest->conceptoDim : '';
      $info = isset($dataRequest->info) ? $dataRequest->info : '';
      $idTipoLote = isset($dataRequest->idTipoLote) ? $dataRequest->idTipoLote : '';

      $username = $this->session->userdata('userName');
      $token = $this->session->userdata('token');

      $info = unserialize(stripslashes($info));
      $info->lineaEmbozo1 = $embozo1;
      $info->lineaEmbozo2 = $embozo2;
      $info->conceptoAbono = $conceptoAbono;
      $info->codCia = $this->session->userdata('accodciaS');

      if (!$embozo1) {
        $info->lineaEmbozo1 = "";
      }
      if (!$embozo2) {
        $info->lineaEmbozo2 = "";
      }
      if (!$conceptoAbono) {
        $info->conceptoAbono = "";
      }

      if ($funcAct) {
        $test = $this->callWSconfirmarLote($urlCountry, $token, $username, $pass, $info, $linkAut, $idTipoLote);
      } else {
        $test = array("ERROR" => lang('SIN_FUNCION'));
      }
      $test = $this->cryptography->encrypt($test);
      $this->output->set_content_type('application/json')->set_output(json_encode($test));
    } elseif ($paisS != $urlCountry && $paisS != '') {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      $responseError = ['ERROR' => lang('ERROR_(-29)'), "rc" => "-29"];
      $responseError = $this->cryptography->encrypt($responseError);
      $this->output->set_content_type('application/json')->set_output(json_encode($responseError));
    } else {
      redirect($urlCountry . '/login');
    }
  }

  /**
   * Método que realiza petición al WS para realizar la operación de confirmación de lote
   * @param  string $urlCountry
   * @param  string $token
   * @param  string $username
   * @param  string $pass
   * @param  string $info
   * @param  string $linkAut
   * @param  string $idTipoLote
   * @return array
   */
  private function callWSconfirmarLote($urlCountry, $token, $username, $pass, $info, $linkAut, $idTipoLote)
  {
    $this->lang->load('erroreseol');
    $operacion = ($idTipoLote == 'L' && $urlCountry != 'Ec-bp') ? 'reprocesarLoteGeneral' : 'confirmarLote';
    $classname = "com.novo.objects.MO.ConfirmarLoteMO";
    $timeLog = date("m/d/Y H:i");
    $ip = $this->input->ip_address();

    $usuario = array(
      'userName' => $username,
      'password' => $pass,
      'codigoGrupo' => $this->session->userdata('accodgrupoeS')
    );

    $sessionId = $this->session->userdata('sessionId');
    $canal = "ceo";
    $modulo = 'confirma';
    $funcion = 'confirmarlote';
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $funcion, $operacion, 0, $ip, $timeLog);

    $data = array(
      "idOperation" => $operacion,
      "className" => $classname,
      "lotesTO" => $info,
      "usuario" => $usuario,
      "logAccesoObject" => $logAcceso,
      "token" => $token,
      "pais" => $urlCountry
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    log_message('info', "Request  confirmarlote======>>>>>>" . $data);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSconfirmarLote');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSconfirmarLote');
    $response = json_decode($jsonResponse);

    log_message('info', "confirmarlote " . $jsonResponse);

    if ($response) {
      log_message('info', 'confirmarlote ' . $response->rc . '/' . $response->msg);

      if ($response->rc == 0) {
        $response->linkAut = $linkAut;
        log_message('info', 'confirmarlote dataDecrip ' . $jsonResponse);
        if ($idTipoLote == 'L' && $urlCountry != 'Ec-bp') {
          return array("ordenes" => serialize($response));
        } else {
          return $response;
        }
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          return array('ERROR' => '-29');
        } else if ($response->rc == -142) {
          $codigoError = array('ERROR' => $response->msg);
        } else if ($response->rc == -1) {
          $codigoError = array('ERROR' => lang('MSG_INVALID_PASS'), "rc" => $response->rc);
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')');
          if (strpos($codigoError, 'Error') !== false) {
            $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'));
          }
        }
        return $codigoError;
      }
    } else {
      log_message('info', 'confirmarlote NO WS');
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }

  /**
   * Pantalla que muestra el cálculo de la orden de servicio a autorizar (preliminar)
   * @param  string $urlCountry
   */
  public function pantallaCalculoOSLote($urlCountry)
  {
    //VALIDATE COUNTRY
    np_hoplite_countryCheck($urlCountry);
    $this->lang->load('dashboard');
    $this->lang->load('lotes');
    $this->lang->load('users');
    $this->lang->load('erroreseol');
    $this->load->library('parser');

    $logged_in = $this->session->userdata('logged_in');
    $token = $this->session->userdata('token');
    $username = $this->session->userdata('userName');
    $idProductoS = $this->session->userdata('idProductoS');
    $menuP = $this->session->userdata('menuArrayPorProducto');
    $paisS = $this->session->userdata('pais');

    $moduloAct = np_hoplite_existeLink($menuP, "TEBAUT");

    $pass = $this->input->post('data-pass');

    if ($paisS == $urlCountry && $logged_in && $moduloAct !== false) {

      $calculoOsLotesW = $this->input->post('data-COS');

      $nombreCompleto = $this->session->userdata('nombreCompleto');
      $lastSessionD = $this->session->userdata('lastSession');
      $FooterCustomInsertJS = ["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js", "jquery.balloon.min.js", "dashboard/widget-empresa.js", "aes.min.js", "aes-json-format.min.js", "header.js", "jquery.dataTables.min.js", "lotes/lotes-orden_servicio.js", "routes.js"];
      $FooterCustomJS = "";
      $titlePage = "Conexión Empresas Online - Lotes";

      $menuHeader = $this->parser->parse('widgets/widget-menuHeader', array(), TRUE);
      $menuFooter = $this->parser->parse('widgets/widget-menuFooter', array(), TRUE);

      $header = $this->parser->parse('layouts/layout-header', array('bodyclass' => '', 'menuHeaderActive' => TRUE, 'menuHeaderMainActive' => TRUE, 'menuHeader' => $menuHeader, 'titlePage' => $titlePage), TRUE);
      $footer = $this->parser->parse('layouts/layout-footer', array('menuFooterActive' => TRUE, 'menuFooter' => $menuFooter, 'FooterCustomInsertJSActive' => TRUE, 'FooterCustomInsertJS' => $FooterCustomInsertJS, 'FooterCustomJSActive' => TRUE, 'FooterCustomJS' => $FooterCustomJS), TRUE);
      $content = $this->parser->parse('lotes/content-calculolote', array(
        'titulo' => $nombreCompleto,
        'lastSession' => $lastSessionD,
        'data' => $calculoOsLotesW
      ), TRUE);
      $sidebarLotes = $this->parser->parse('widgets/widget-publi-5', array('sidebarActive' => TRUE), TRUE);

      $datos = array(
        'header' => $header,
        'content' => $content,
        'footer' => $footer,
        'sidebar' => $sidebarLotes
      );

      $this->parser->parse('layouts/layout-b', $datos);
    } else if ($idProductoS) {
      echo "
			<script>alert('Lote no seleccionado'); location.href = '" . $this->config->item('base_url') . "$urlCountry/lotes/autorizacion';</script>
			";
    } elseif ($paisS != $urlCountry && $paisS != "") {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } else {
      echo "
			<script>alert('Enlace no permitido'); location.href = '" . $this->config->item('base_url') . "$urlCountry/login';</script>
			";
    }
  }


  /**
   * Método para generar el cálculo de la orden de servicio antes de ser autorizada.
   *
   * @param  string $urlCountry
   * @return JSON
   */
  public function preliminarOS($urlCountry)
  {

    np_hoplite_countryCheck($urlCountry);

    $logged_in = $this->session->userdata('logged_in');
    $token = $this->session->userdata('token');
    $username = $this->session->userdata('userName');
    $idProductoS = $this->session->userdata('idProductoS');
    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in) {
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
      $lotes = explode(',', $dataRequest->data_lotes);
      $pass = $dataRequest->data_pass;
      $tipoOrdeServicio = $dataRequest->data_tipoOS;
      $medio = isset($dataRequest->data_medio) ? $dataRequest->data_medio : '';
      $ivanuevo = isset($dataRequest->data_iva) ? (($dataRequest->data_iva == 1) ? true : false) : '';

      array_pop($lotes);
      log_message('info', "Prueba para la toma del país --->>> " . $paisS);
      $calculoOsLotesW = $this->callWScalcularOS(
        $urlCountry,
        $token,
        $username,
        $pass,
        $lotes,
        $tipoOrdeServicio,
        $medio,
        $ivanuevo
      );
      $calculoOsLotesW = $this->cryptography->encrypt($calculoOsLotesW);
      $this->output->set_content_type('application/json')->set_output(json_encode($calculoOsLotesW));
    } elseif ($paisS != $urlCountry && $paisS != '') {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      $responseError = ['ERROR' => lang('ERROR_(-29)'), "rc" => "-29"];
      $responseError = $this->cryptography->encrypt($responseError);
      $this->output->set_content_type('application/json')->set_output(json_encode($responseError));
    } else {
      redirect($urlCountry . '/login');
    }
  }



  /**
   * Método que realiza petición al WS para generar el cálculo de la orden de servicio antes de ser autorizada.
   * @param  string $urlCountry
   * @param  string $token
   * @param  string $username
   * @param  string $pass
   * @param  string $lotes
   * @param  string $tipoOrdenServicio
   * @return array
   */
  private function callWScalcularOS($urlCountry, $token, $username, $pass, $lotes, $tipoOrdeServicio, $medio, $ivanuevo)
  {
    $this->lang->load('erroreseol');
    $this->lang->load('dashboard');
    $this->lang->load('lotes');
    $operacion = "calcularOS";
    $classname = "com.novo.objects.TOs.OrdenServicioTO";
    $timeLog = date("m/d/Y H:i");
    $ip = $this->input->ip_address();

    $idEmpresa = $this->session->userdata('acrifS');
    $idProductoS = $this->session->userdata('idProductoS');

    if ($medio == '1') {
      $descripcion = 'Deposito';
    } else if ($medio == '2') {
      $descripcion = 'Transferencia';
    } else {
      $descripcion = 'Deposito y Transferencia';
    }

    $arraymediopago = array(
      'idPago' => $medio,
      'descripcion' => $descripcion
    );

    if ($ivanuevo == 1) {
      $ivanuevo = 'true';
    } else {
      $ivanuevo = 'false';
    }

    log_message("info", "Tomando id de medio de pago -->> " . $medio);
    log_message("info", "Tomando tipo de medio de pago -->> " . $descripcion);
    log_message("info", "Tomando valor del nuevo iva -->> " . $ivanuevo);

    foreach ($lotes as $key => $value) {
      $lote = array(
        'acidlote' => $value
      );
      $listaL[$key] = $lote;
    }

    $datosEmpresa = array(
      'acrif' => $idEmpresa
    );

    $datosUsuario = array(
      'userName' => $username,
      'password' => $pass
    );

    $sessionId = $this->session->userdata('sessionId');
    $canal = "ceo";
    $modulo = 'confirma';
    $funcion = 'confirmarlote';
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $funcion, $operacion, 0, $ip, $timeLog);

    $data = array(
      "pais" => $urlCountry,
      "idOperation" => $operacion,
      "className" => $classname,
      "datosEmpresa" => $datosEmpresa,
      "acprefix" => $idProductoS,
      "acUsuario" => $username,
      "tipoOrdeServicio" => $tipoOrdeServicio,
      "nuevoIva" => $ivanuevo,
      "medioPago" => $arraymediopago,
      "lotes" => $listaL,
      "usuario" => $datosUsuario,
      "logAccesoObject" => $logAcceso,
      "token" => $token
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    log_message("info", "Array de medios de pago -->> " . $data);

    $dataEncry = np_Hoplite_Encryption($data, 'callWScalcularOS');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWScalcularOS');
    $response = json_decode($jsonResponse);

    log_message("DEBUG", "RESPONSE Calculo OS: " . $jsonResponse);
    if (isset($response->rc)) {
      $title = lang('TITULO_LOTES_AUTORIZACION');
      switch ($response->rc) {
        case 0:
          $code = 0;
          $data = serialize($response);
          break;
        case -1:
          $code = 2;
          $msg = 'Por favor verifica tu contraseña e intentalo de nuevo';
          break;
        case -3:
          $code = 2;
          $msg = lang('ERROR_(-39)');
          break;
        case -51:
          $code = 2;
          $msgVE = 'No fue posible obtener los datos de la empresa para la Orden de Servicio. ';
          $msgVE .= 'Por favor envíe ésta pantalla y su usuario al correo ';
          $msgVE .= '<strong>soporteempresas@tebca.com</strong>';
          $msg = $urlCountry == "Ve" ? $msgVE : $response->msg;
          break;
        case -29:
        case -61:
          $code = 3;
          $title = lang('SYSTEM_NAME');
          $msg = lang('ERROR_(-29)');
          break;
        default:
          $code = 2;
          $msg = lang('ERROR_(' . $response->rc . ')');
      }
    } else {
      $code = 3;
      $title = lang('SYSTEM_NAME');
      $msg = lang('ERROR_GENERICO_USER');
    }

    if ($code === 3) {
      $this->session->sess_destroy();
    }

    $response = [
      'code' => $code,
      'title' => $title,
      'msg' => isset($msg) ? $msg : '',
      'data' => isset($data) ? $data : ''
    ];


    //log_message('DEBUG', 'SENT TO THE VIEW: '.$response);

    return $response;
  }


  /**
   * Método que realiza petición al WS para generar la orden de servicio luego de confirmar el cálculo
   * @param  string $urlCountry
   * @param  string $token
   * @param  string $username
   * @param  string $listaTemp
   * @param  string $tempIdOrdenLNF
   * @param  string $acrifS
   * @return array
   */
  private function callWSgenerarOS($urlCountry, $token, $username, $listaTemp, $tempIdOrdenLNF, $tokenOTP, $acrifS, $moduloOS)
  {
    $this->lang->load('erroreseol');
    $this->lang->load('dashboard');
    $operacion = "generarOS";
    $classname = "com.novo.objects.MO.ListadoOrdenServicioMO";
    $timeLog = date("m/d/Y H:i");
    $ip = $this->input->ip_address();

    $usuario = array(
      'userName' => $username,
      'codigoGrupo' => $this->session->userdata('accodgrupoeS')
    );

    $tempIdOrdenL = unserialize($listaTemp);
    $tempIdOrdenLNF = unserialize($tempIdOrdenLNF);

    $lista = [];
    $listaNF = [];

    if ($tempIdOrdenL) {
      foreach ($tempIdOrdenL as $key => $value) {
        $lote = array(
          'idOrdenTemp' => $value,
          'acprefix' => $this->session->userdata('idProductoS')
        );
        $lista[$key] = $lote;
      }
    } else {
      $lista = array();
    }

    if ($tempIdOrdenLNF) {
      foreach ($tempIdOrdenLNF as $key => $value) {
        $lote = array(
          'acidlote' => $value,
          'acprefix' => $this->session->userdata('idProductoS')
        );
        $listaNF[$key] = $lote;
      }
    } else {
      $listaNF = array();
    }

    $sessionId = $this->session->userdata('sessionId');
    $canal = "ceo";
    $modulo = 'generarOS';
    $funcion = 'generarOS';
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $funcion, $operacion, 0, $ip, $timeLog);

    $data = array(
      "pais" => $urlCountry,
      "idOperation" => $operacion,
      "className" => $classname,
      "rifEmpresa" => $acrifS,
      "lista" => $lista,
      "lotesNF" => $listaNF,
      "tokenOTP" => $tokenOTP,
      "usuario" => $usuario,
      "logAccesoObject" => $logAcceso,
      "token" => $token
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSgenerarOS');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSgenerarOS');
    $response = json_decode($jsonResponse);

    //log_message("DEBUG", "generarOS =====>>>>>> ".json_encode($response));
    log_message("DEBUG", "generarOS =====>>>>>> " . ($jsonResponse));
    if (isset($response->rc)) {
      switch ($response->rc) {
        case 0:
        case -88:
          $response = [
            "moduloOS" => $moduloOS,
            "daysPay" => isset($response->dias) ? $response->dias : '',
            "costoLog" => ($response->lista[0]->aplicaCostD === 'D'),
            "ordenes" => serialize($response)
          ];
          $this->session->unset_userdata('authToken');
          break;
        case -29:
        case -61:
          $this->session->sess_destroy();
          $response = [
            'ERROR' => '-29',
            "title" => Lang('SYSTEM_NAME'),
            "msg" => lang('ERROR_(-29)')
          ];
          break;
        case -56:
          $response = [
            'ERROR' => $response->rc,
            'msg' => lang('ERROR_(-56)')
          ];
          break;
        case -231:
          $response = [
            'ERROR' => $response->rc,
            'msg' => lang('ERROR_(-231)')
          ];
          break;
        case -286:
          $response = [
            'ERROR' => $response->rc,
            'msg' => lang('ERROR_(-286)')
          ];
          break;
        default:
          $response = [
            'ERROR' => lang('ERROR_(' . $response->rc . ')')
          ];
      }
    } else {
      $response = [
        'ERROR' => lang('ERROR_GENERICO_USER')
      ];
    }

    return $response;
  }

  /**
   * Método para generar la orden de servicio (proceso final de autorización del lote)
   * @param  string $urlCountry
   * @return json
   */
  public function callAutorizarLote($urlCountry)
  {
    np_hoplite_countryCheck($urlCountry);
    $this->lang->load('erroreseol');

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
    $tempIdOrdenL = isset($dataRequest->tempIdOrdenL) ? $dataRequest->tempIdOrdenL : FALSE;
    $tempIdOrdenLNF = isset($dataRequest->tempIdOrdenLNF) ? $dataRequest->tempIdOrdenLNF : FALSE;
    $autorizacionOtp = isset($dataRequest->autorizacionOtp) ? $dataRequest->autorizacionOtp : FALSE;

    $token = $this->session->userdata('token');
    $username = $this->session->userdata('userName');
    $acrifS = $this->session->userdata('acrifS');
    $menuP = $this->session->userdata('menuArrayPorProducto');

    $moduloAct = np_hoplite_existeLink($menuP, "TEBAUT");
    $moduloOS = np_hoplite_existeLink($menuP, "TEBORS");

    $logged_in = $this->session->userdata('logged_in');
    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in) {
      if ($moduloAct !== false) {
        $tokenOTP = [
          'authToken' => $this->session->userdata('authToken'),
          'tokenCliente' => $autorizacionOtp
        ];
        $t = $this->callWSgenerarOS($urlCountry, $token, $username, $tempIdOrdenL, $tempIdOrdenLNF, $tokenOTP, $acrifS, $moduloOS);
      } else {
        $t = ['ERROR' => lang('SIN_FUNCION')];
        //$t = json_encode(array("ERROR"=>lang('SIN_FUNCION')));
      }
      $t = $this->cryptography->encrypt($t);
      $this->output->set_content_type('application/json')->set_output(json_encode($t));
    } elseif ($paisS != $urlCountry && $paisS != '') {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      $responseError = ['ERROR' => lang('ERROR_(-29)'), "rc" => "-29"];
      $responseError = $this->cryptography->encrypt($responseError);
      $this->output->set_content_type('application/json')->set_output(json_encode($responseError));
    } else {
      redirect($urlCountry . '/login');
    }
  }


  /**
   * Pantalla para mostrar el módulo de reproceso de lotes (guardería)
   * @param string urlCountry
   */
  public function pantallaReproceso($urlCountry)
  {
    log_message('info', 'Acceso a =====>>> pantallaReproceso ');
    np_hoplite_countryCheck($urlCountry);
    $this->lang->load('lotes');
    $this->lang->load('dashboard');
    $this->lang->load('users');
    $this->lang->load('erroreseol');

    $logged_in = $this->session->userdata('logged_in');

    $menuP = $this->session->userdata('menuArrayPorProducto');
    $moduloAct = np_hoplite_existeLink($menuP, "TEBGUR");

    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in && $moduloAct !== false) {
      $FooterCustomInsertJS = ["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js", "jquery.balloon.min.js", "jquery.paginate.js", "header.js", "aes.min.js", "aes-json-format.min.js", "dashboard/widget-empresa.js", "jquery.fileupload.js", "jquery.iframe-transport.js", "jquery-md5.js", "lotes/lotes-reproceso.js", "routes.js"];
      $FooterCustomJS = "";
      $titlePage = "Reproceso de Datos";

      if ($this->session->userdata('marcaProductoS') === 'Cheque') {
        $programa = $this->session->userdata('nombreProductoS');
      } else {
        $programa = $this->session->userdata('nombreProductoS') . ' / ' . ucwords($this->session->userdata('marcaProductoS'));
      }
      $idProductoS = $this->session->userdata('idProductoS');
      $tiposLotesLista[] = $this->callWSconsultarTipoLote($urlCountry, $idProductoS);
      $menuHeader = $this->parser->parse('widgets/widget-menuHeader', array(), TRUE);
      $menuFooter = $this->parser->parse('widgets/widget-menuFooter', array(), TRUE);

      $header = $this->parser->parse('layouts/layout-header', array('bodyclass' => '', 'menuHeaderActive' => TRUE, 'menuHeaderMainActive' => TRUE, 'menuHeader' => $menuHeader, 'titlePage' => $titlePage), TRUE);
      $footer = $this->parser->parse('layouts/layout-footer', array('menuFooterActive' => TRUE, 'menuFooter' => $menuFooter, 'FooterCustomInsertJSActive' => TRUE, 'FooterCustomInsertJS' => $FooterCustomInsertJS, 'FooterCustomJSActive' => TRUE, 'FooterCustomJS' => $FooterCustomJS), TRUE);
      $content = $this->parser->parse('lotes/content-reproceso', array(
        "titulo" => $titlePage,
        "selectTiposLotes" => $tiposLotesLista,
        "programa" => $programa
      ), TRUE);
      $sidebarLotes = $this->parser->parse('dashboard/widget-empresa', array('sidebarActive' => TRUE), TRUE);

      $datos = array(
        'header'       => $header,
        'content'      => $content,
        'footer'       => $footer,
        'sidebar'      => $sidebarLotes
      );

      $this->parser->parse('layouts/layout-b', $datos);
    } elseif ($paisS != $urlCountry) {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } else {
      redirect($urlCountry . '/login');
    }
  }

  /**
   * Método para crear un nuevo beneficiario a la empresa
   * @param string urlCountry
   * @return json
   */
  public function crearBeneficiario($urlCountry)
  {
    np_hoplite_countryCheck($urlCountry);
    $this->lang->load('erroreseol');


    $menuP = $this->session->userdata('menuArrayPorProducto');
    $moduloAct = np_hoplite_existeLink($menuP, "TEBGUR");

    $logged_in = $this->session->userdata('logged_in');
    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in) {
      if ($moduloAct !== false) {

        $dataPost = array(
          'pass' => $this->input->post('pass'),
          'tipo' => $this->input->post('tipo'),
          'idPersona' => $this->input->post("idPersona"),
          'apellEmpl' => $this->input->post("apellEmpl"),
          'nombEmpl' => $this->input->post("nombEmpl"),
          'emailEmpl' => $this->input->post("emailEmpl"),
          'apellInfant' => $this->input->post("apellInfant"),
          'nombInfant' => $this->input->post("nombInfant"),
          'nombGuard' => $this->input->post("nombGuard"),
          'idfiscalGuard' => $this->input->post("idfiscalGuard"),
          'nroCuentaGuard' => $this->input->post("nroCuentaGuard"),
          'emailGuard' => $this->input->post("emailGuard"),
          'monto' => $this->input->post("monto"),
          'concepto' => $this->input->post("concepto"),
          'paginar' => $this->input->post("paginar"),
          'tamPg' => $this->input->post("tamPg"),
          'pgActual' => $this->input->post("pgActual")
        );

        $response = $this->callWScrearBeneficiario($urlCountry, utf8_decode(json_encode($dataPost, JSON_UNESCAPED_UNICODE)));
      } else {
        $response = array("ERROR" => lang('SIN_FUNCION'));
      }
      $this->output->set_content_type('application/json')->set_output(json_encode($response, JSON_UNESCAPED_UNICODE));
    } elseif ($paisS != $urlCountry && $paisS != '') {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      $this->output->set_content_type('application/json')->set_output(json_encode(array('ERROR' => '-29')));
    } else {
      redirect($urlCountry . '/login');
    }
  }

  /**
   * Método que realiza petición al WS para crear un nuevo beneficiario a la empresa.
   * @param string urlCountry
   * @param string dataPost
   * @return array
   */
  private function callWScrearBeneficiario($urlCountry, $dataPost)
  {
    $this->lang->load('erroreseol');

    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');
    $idEmpresa = $this->session->userdata('acrifS');
    $idProductoS = $this->session->userdata('idProductoS');
    $acodcia = $this->session->userdata('accodciaS');

    $canal = "ceo";
    $modulo = "Reprocesar Lotes";
    $function = "Reprocesar Guarderia";
    $operation = "insertarPlantillaBeneficiario";
    $ip = $this->input->ip_address();
    $timeLog = date("m/d/Y H:i");
    $className = "com.novo.objects.TOs.RegistrosLoteGuarderiaTO";

    $sessionId = $this->session->userdata('sessionId');

    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $operation, 0, $ip, $timeLog);

    $dataPost = json_decode($dataPost);

    $usuario = array(
      'userName' => $username,
      'password' => $dataPost->pass
    );

    $data = array(
      "idOperation" => $operation,
      "className" => $className,
      "id_ext_emp" => $idEmpresa,
      "tipo_lote" => $dataPost->tipo,
      "apellido" => $dataPost->apellEmpl,
      "apellido_infante" => $dataPost->apellInfant,
      "beneficiario" => $dataPost->nombGuard,
      "cobigo_Banco" => "",
      "concepto" => $dataPost->concepto,
      "email_empleado" => $dataPost->emailEmpl,
      "email_guarderia" => $dataPost->emailGuard,
      "empresa_emisora" => "",
      "id_per" => $dataPost->idPersona,
      "id_registro" => "",
      "monto_total" => $dataPost->monto,
      "nombre" => $dataPost->nombEmpl,
      "nombre_infante" => $dataPost->nombInfant,
      "nro_cuenta" => $dataPost->nroCuentaGuard,
      "numlote" => "",
      "rif_guarderia" => $dataPost->idfiscalGuard,
      "tipo_id" => "",
      "formato" => "00",
      "acCodCia" => $acodcia,
      "idProducto" => $idProductoS,
      "status" => "0",
      "usuario" => $usuario,
      "paginar" => $dataPost->paginar,
      "paginaActual" => $dataPost->pgActual,
      "tamanoPagina" => $dataPost->tamPg,
      "logAccesoObject" => $logAcceso,
      "token" => $token,
      "pais" => $urlCountry
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWScrearBeneficiario');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWScrearBeneficiario');
    $response = json_decode($jsonResponse);

    if ($response) {
      log_message('info', 'REPROCESO crear beneficiario ' . $response->rc . '/' . $response->msg);
      if ($response->rc == 0) {
        return $response;
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          return array('ERROR' => '-29');
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')');
          if (strpos($codigoError, 'Error') !== false) {
            $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'));
          }

          return $codigoError;
        }
      }
    } else {
      log_message('info', 'REPROCESO crear beneficiario No WS');
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }

  /**
   * Método para cargar documento Excel de beneficiarios en el módulo de reproceso
   * @param  string $urlCountry
   * @return bytes
   */
  public function cargarMasivoReproceso($urlCountry)
  {

    np_hoplite_countryCheck($urlCountry);

    $logged_in = $this->session->userdata('logged_in');
    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in) {

      $this->lang->load('upload');
      $this->lang->load('erroreseol');
      $createDirectory = lang('GEN_UPLOAD_NOT_CREATE_DIRECTORY');

      if (!is_dir(UPLOAD_PATH . $this->config->item('country'))) {
        if (mkdir(UPLOAD_PATH . $this->config->item('country'), 0755, TRUE)) {
          $createDirectory = lang('GEN_UPLOAD_CREATE_DIRECTORY');
        };
      }

      $config['upload_path'] = UPLOAD_PATH . $this->config->item('country') . '/';

      log_message('DEBUG', 'uploadFiles directory ' . $config['upload_path'] . ' ' . $createDirectory);

      $config['allowed_types'] = '*';
      $this->load->library('upload', $config);
      //VERIFICAR SI NO SUBIO ARCHIVO
      if (!$this->upload->do_upload()) {
        //ERROR
        $error = array('ERROR' => 'No se puede cargar el archivo. Verifícalo e intenta de nuevo'); // $this->upload->display_errors());
        $this->output->set_content_type('application/json')->set_output(json_encode($error));
      } else {
        //VALIDO
        $data = array('upload_data' => $this->upload->data());
        $nombreArchivo = $data["upload_data"]["raw_name"]; //NOMBRE ARCHIVO SIN EXTENSION
        $rutaArchivo = $data["upload_data"]["file_path"];
        $extensionArchivo = $data["upload_data"]["file_ext"];

        $ch = curl_init();
        $localfile = $config['upload_path'] . $nombreArchivo . $extensionArchivo;
        $fp = fopen($localfile, 'r');
        $nombreArchivoNuevo = date("YmdHis") . $nombreArchivo . $extensionArchivo;
        $URL_TEMPLOTES = BULK_FTP_URL . $this->config->item('country') . '/';
        $LOTES_USERPASS = BULK_FTP_USERNAME . ':' . BULK_FTP_PASSWORD;

        log_message('DEBUG', 'uploadFiles sftp ' . $URL_TEMPLOTES);

        curl_setopt($ch, CURLOPT_URL, $URL_TEMPLOTES . $nombreArchivoNuevo);
        curl_setopt($ch, CURLOPT_USERPWD, $LOTES_USERPASS);
        curl_setopt($ch, CURLOPT_UPLOAD, 1);
        curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_SFTP);
        curl_setopt($ch, CURLOPT_INFILE, $fp);
        curl_setopt($ch, CURLOPT_INFILESIZE, filesize($localfile));
        curl_exec($ch);
        $error_no = curl_errno($ch);
        log_message('ERROR', "subiendo archivo lotes sftp " . $error_no . "/" . lang("SFTP(" . $error_no . ")"));
        curl_close($ch);

        if ($error_no == 0) {
          $error = 'Archivo Movido.';
          //COLOCAR LLAMADO DE LA FUNCION CUANDO ESTE CORRECTO
          $formatoArchivo = substr($extensionArchivo, 1);

          $tipoLote = $this->input->post('data-tipoLote');

          $cargaLote = $this->callWScargarArchivoReproceso($urlCountry, $formatoArchivo, $nombreArchivoNuevo, $tipoLote);

          $this->output->set_content_type('application/json')->set_output(json_encode($cargaLote));
        } else {
          $error = array('ERROR' => 'Falla al mover archivo.');
          $this->output->set_content_type('application/json')->set_output(json_encode($error));
        }

        fclose($fp);
        unlink("$localfile"); //BORRAR ARCHIVO
      }
    } elseif ($paisS != $urlCountry && $paisS != '') {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      $this->output->set_content_type('application/json')->set_output(json_encode(array('ERROR' => '-29')));
    } else {
      redirect($urlCountry . '/login');
    }
  }

  /**
   * Método que realiza petición al WS para cargar documento Excel de beneficiarios en el módulo de reproceso
   * @param  string $urlCountry
   * @param  string $formatoArchivo
   * @param  string $nombreArchivo
   * @param  string $tipoLote
   * @return array
   */
  private function callWScargarArchivoReproceso($urlCountry, $formatoArchivo, $nombreArchivo, $tipoLote)
  {
    $this->lang->load('erroreseol');
    $canal = "ceo";
    $modulo = "Reprocesar Lotes";
    $function = "Reprocesar Guarderia";
    $operation = "insertarPlantillaEmpresa";
    $className = "com.novo.objects.TOs.LotePorConfirmarTO";
    $timeLog = date("m/d/Y H:i");
    $ip = $this->input->ip_address();

    $sessionId = $this->session->userdata('sessionId');
    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');
    $idEmpresa = $this->session->userdata('acrifS');
    $idProductoS = $this->session->userdata('idProductoS');

    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $operation, 0, $ip, $timeLog);

    $data = array(
      "idOperation" => $operation,
      "className" => $className,
      "idEmpresa" => $idEmpresa,
      "formato" => $formatoArchivo,
      "usuario" => $username,
      "idTipoLote" => $tipoLote,
      "codProducto" => $idProductoS,
      "logAccesoObject" => $logAcceso,
      "nombreArchivo" => $nombreArchivo,
      "token" => $token,
      "pais" => $urlCountry
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWScargarArchivoReproceso');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWScargarArchivoReproceso');
    $response = json_decode($jsonResponse);

    if ($response) {

      log_message('info', "cargaLoteREPROCESO " . $response->rc . '/' . $response->msg);

      if ($response->rc == 0 || $response->rc == -128) {
        return $response;
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          return array('ERROR' => '-29');
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')');
          if (strpos($codigoError, 'Error') !== false) {
            $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'));
          }

          return $codigoError;
        }
      }
    } else {
      log_message('info', "cargaLoteREPROCESO NO WS");
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }

  /**
   * Método para solicitar el listado de beneficiarios de guarderia
   *@param  string $urlCountry
   *@return json
   */
  public function buscarListaBeneficiarios($urlCountry)
  {

    np_hoplite_countryCheck($urlCountry);
    $this->lang->load('erroreseol');

    $menuP = $this->session->userdata('menuArrayPorProducto');
    $moduloAct = np_hoplite_existeLink($menuP, "TEBGUR");

    $logged_in = $this->session->userdata('logged_in');
    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in) {
      if ($moduloAct !== false) {
        $tipo_lote = $this->input->post('data-tipo');
        $paginar = $this->input->post('data-paginar');
        $pgActual = $this->input->post('data-pgActual');
        $tamPg = $this->input->post('data-tamPg');

        $response = $this->callWSListaBeneficiarios($urlCountry, $tipo_lote, $paginar, $pgActual, $tamPg);
      } else {
        $response = array("ERROR" => lang('SIN_FUNCION'));
      }
      $this->output->set_content_type('application/json')->set_output(json_encode($response, JSON_UNESCAPED_UNICODE));
    } elseif ($paisS != $urlCountry && $paisS != '') {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      $this->output->set_content_type('application/json')->set_output(json_encode(array('ERROR' => '-29')));
    } else {
      redirect($urlCountry . '/login');
    }
  }


  /**
   * Método que realiza petición al WS para solicitar el listado de beneficiarios de guarderia
   * @param  string $urlCountry
   * @return array
   */
  private function callWSListaBeneficiarios($urlCountry, $tipo_lote, $paginar, $pgActual, $tamPg)
  {

    $this->lang->load('erroreseol');

    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');
    $idEmpresa = $this->session->userdata('acrifS');

    $canal = "ceo";
    $modulo = "Reprocesar Lotes";
    $function = "Reprocesar Guarderia";
    $operation = "buscarPlantillaEmpresa";
    $ip = $this->input->ip_address();
    $timeLog = date("m/d/Y H:i");
    $className = "com.novo.objects.TOs.RegistrosLoteGuarderiaTO";

    $sessionId = $this->session->userdata('sessionId');

    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $operation, 0, $ip, $timeLog);

    $data = array(
      "idOperation" => $operation,
      "className" => $className,
      "id_ext_emp" => $idEmpresa,
      "tipo_lote" => $tipo_lote,
      "paginar" => $paginar,
      "paginaActual" => $pgActual,
      "tamanoPagina" => $tamPg,
      "logAccesoObject" => $logAcceso,
      "token" => $token,
      "pais" => $urlCountry
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSListaBeneficiarios');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSListaBeneficiarios');
    $response = json_decode($jsonResponse);

    if ($response) {
      log_message('info', 'REPROCESO buscar lotes ' . $response->rc . '/' . $response->msg);
      if ($response->rc == 0) {
        return $response;
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          return array('ERROR' => '-29');
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')');
          if (strpos($codigoError, 'Error') !== false) {
            $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'));
          }

          return $codigoError;
        }
      }
    } else {
      log_message('info', 'REPROCESO buscar lotes No WS');
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }

  /**
   * Método para modificar los datos del beneficiario en el módulo de reproceso de lotes
   * @param  string $urlCountry
   * @return json
   */
  public function modificarBeneficiario($urlCountry)
  {

    np_hoplite_countryCheck($urlCountry);
    $this->lang->load('erroreseol');

    $menuP = $this->session->userdata('menuArrayPorProducto');
    $moduloAct = np_hoplite_existeLink($menuP, "TEBGUR");

    $logged_in = $this->session->userdata('logged_in');
    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in) {
      if ($moduloAct !== false) {

        $dataPost = array(
          'pass' => $this->input->post('pass'),
          'tipo' => $this->input->post('tipo'),
          'idPersona' => $this->input->post("idPersona"),
          'apellEmpl' => $this->input->post("apellEmpl"),
          'nombEmpl' => $this->input->post("nombEmpl"),
          'emailEmpl' => $this->input->post("emailEmpl"),
          'apellInfant' => $this->input->post("apellInfant"),
          'nombInfant' => $this->input->post("nombInfant"),
          'nombGuard' => $this->input->post("nombGuard"),
          'idfiscalGuard' => $this->input->post("idfiscalGuard"),
          'nroCuentaGuard' => $this->input->post("nroCuentaGuard"),
          'emailGuard' => $this->input->post("emailGuard"),
          'monto' => $this->input->post("monto"),
          'concepto' => $this->input->post("concepto"),
          'id_registro' => $this->input->post("id_registro"),
          'paginar' => $this->input->post("paginar"),
          'pgActual' => $this->input->post("pgActual"),
          'tamPg' => $this->input->post("tamPg")
        );

        $response = $this->callWSmodificarBeneficiario($urlCountry, utf8_decode(json_encode($dataPost, JSON_UNESCAPED_UNICODE)));
        log_message('info', 'Response ' . json_encode($response));
      } else {
        $response = array("ERROR" => lang('SIN_FUNCION'));
      }
      $this->output->set_content_type('application/json')->set_output(json_encode($response, JSON_UNESCAPED_UNICODE));
    } elseif ($paisS != $urlCountry && $paisS != '') {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      $this->output->set_content_type('application/json')->set_output(json_encode(array('ERROR' => '-29')));
    } else {
      redirect($urlCountry . '/login');
    }
  }


  /**
   * Método que realiza petición al WS para modificar los datos del beneficiario en el módulo de reproceso de lotes
   * @param string $urlCountry
   * @param string $dataPost
   * @return json
   */
  private function callWSmodificarBeneficiario($urlCountry, $dataPost)
  {

    $this->lang->load('erroreseol');

    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');
    $idEmpresa = $this->session->userdata('acrifS');
    $idProductoS = $this->session->userdata('idProductoS');
    $acodcia = $this->session->userdata('accodciaS');

    $canal = "ceo";
    $modulo = "Reprocesar Lotes";
    $function = "Reprocesar Guarderia";
    $operation = "actualizarPlantillaBeneficiario";
    $ip = $this->input->ip_address();
    $timeLog = date("m/d/Y H:i");
    $className = "com.novo.objects.TOs.RegistrosLoteGuarderiaTO";

    $sessionId = $this->session->userdata('sessionId');

    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $operation, 0, $ip, $timeLog);

    $dataPost = json_decode($dataPost);

    $usuario = array(
      'userName' => $username,
      'password' => $dataPost->pass
    );

    $data = array(
      "idOperation" => $operation,
      "className" => $className,
      "id_ext_emp" => $idEmpresa,
      "tipo_lote" => $dataPost->tipo,
      "apellido" => $dataPost->apellEmpl,
      "apellido_infante" => $dataPost->apellInfant,
      "beneficiario" => $dataPost->nombGuard,
      "cobigo_Banco" => "",
      "concepto" => $dataPost->concepto,
      "email_empleado" => $dataPost->emailEmpl,
      "email_guarderia" => $dataPost->emailGuard,
      "empresa_emisora" => $this->session->userdata('acnomciaS'),
      "id_per" => $dataPost->idPersona,
      "id_registro" => $dataPost->id_registro,
      "monto_total" => $dataPost->monto,
      "nombre" => $dataPost->nombEmpl,
      "nombre_infante" => $dataPost->nombInfant,
      "nro_cuenta" => $dataPost->nroCuentaGuard,
      "numlote" => "",
      "rif_guarderia" => $dataPost->idfiscalGuard,
      "tipo_id" => "",
      "formato" => "00",
      "acCodCia" => $acodcia,
      "idProducto" => $idProductoS,
      "status" => "0",
      "usuario" => $usuario,
      "paginar" => $dataPost->paginar,
      "paginaActual" => $dataPost->pgActual,
      "tamanoPagina" => $dataPost->tamPg,
      "logAccesoObject" => $logAcceso,
      "token" => $token,
      "pais" => $urlCountry
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);
    log_message('info', 'callWSmodificarBeneficiario =======> ' . $data);
    $dataEncry = np_Hoplite_Encryption($data, 'callWSmodificarBeneficiario');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSmodificarBeneficiario');
    $response = json_decode($jsonResponse);

    if ($response) {
      log_message('info', 'modificar beneficiario ' . $response->rc . '/' . $response->msg);
      if ($response->rc == 0) {
        return $response;
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          return array('ERROR' => '-29');
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')');
          if (strpos($codigoError, 'Error') !== false) {
            $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'));
          }
          return $codigoError;
        }
      }
    } else {
      log_message('info', 'modificar beneficiario No WS');
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }


  /**
   * Método para eliminar beneficiario en el módulo de reproceso de lotes
   * @param  string $urlCountry
   * @return json
   */
  public function eliminarBeneficiario($urlCountry)
  {

    np_hoplite_countryCheck($urlCountry);
    $this->lang->load('erroreseol');

    $menuP = $this->session->userdata('menuArrayPorProducto');
    $moduloAct = np_hoplite_existeLink($menuP, "TEBGUR");

    $logged_in = $this->session->userdata('logged_in');
    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in) {
      if ($moduloAct !== false) {

        $tipo_lote = $this->input->post('data-tipoLote');
        $id_registro = $this->input->post('data-registro');
        $pass = $this->input->post('data-pass');
        $paginar = $this->input->post('data-paginar');
        $pgActual = $this->input->post('data-pgActual');
        $tamPg = $this->input->post('data-tamPg');
        $lista = $this->input->post("data-lista");

        $response = $this->callWSeliminarBeneficiario($urlCountry, $tipo_lote, $lista, $pass, $paginar, $pgActual, $tamPg);
      } else {
        $response = array("ERROR" => lang('SIN_FUNCION'));
      }
      $this->output->set_content_type('application/json')->set_output(json_encode($response));
    } elseif ($paisS != $urlCountry && $paisS != '') {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      $this->output->set_content_type('application/json')->set_output(json_encode(array('ERROR' => '-29')));
    } else {
      redirect($urlCountry . '/login');
    }
  }


  /**
   * Método que realiza petición al WS para eliminar beneficiario en el módulo de reproceso de lotes
   * @param string $urlCountry
   * @param string $tipo_lote, $lista, $pass, $paginar, $pgActual, $tamPg
   * @return array
   */
  private function callWSeliminarBeneficiario($urlCountry, $tipo_lote, $lista, $pass, $paginar, $pgActual, $tamPg)
  {
    $this->lang->load('erroreseol');

    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');
    $idEmpresa = $this->session->userdata('acrifS');
    $idProductoS = $this->session->userdata('idProductoS');
    $acodcia = $this->session->userdata('accodciaS');

    $canal = "ceo";
    $modulo = "Reprocesar Lotes";
    $function = "Reprocesar Guarderia";
    $operation = "eliminarPlantillaBeneficiario";
    $ip = $this->input->ip_address();
    $timeLog = date("m/d/Y H:i");
    $className = "com.novo.objects.MO.PlantillaGuarderiaMO";

    $sessionId = $this->session->userdata('sessionId');

    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $operation, 0, $ip, $timeLog);

    $usuario = [];

    $listado = [];
    if ($lista) {
      $usuario = array(
        "userName" => $username,
        "password" => $pass,
        "idEmpresa" => $idEmpresa
      );
      $idEmpresa = "";
      foreach ($lista as $key => $value) {
        $listado[$key] = array("id_registro" => $value);
      }
    } else {
      $usuario = array(
        "userName" => $username,
        "password" => $pass
      );
    }

    $data = array(
      "idOperation" => $operation,
      "className" => $className,
      "rifEmpresa" => $idEmpresa,
      "tipo_lote" => $tipo_lote,
      "lista" => $listado,
      "usuario" => $usuario,
      "paginar" => $paginar,
      "paginaActual" => $pgActual,
      "tamanoPagina" => $tamPg,
      "logAccesoObject" => $logAcceso,
      "token" => $token,
      "pais" => $urlCountry
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSeliminarBeneficiario');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSeliminarBeneficiario');
    $response = json_decode($jsonResponse);

    if ($response) {
      log_message('info', 'eliminar beneficiario ' . $response->rc . '/' . $response->msg);
      if ($response->rc == 0) {
        return $response;
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          return array('ERROR' => '-29', 'rc' => $response->rc);
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')');
          if (strpos($codigoError, 'Error') !== false) {
            $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'), 'rc' => $response->rc);
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'), 'rc' => $response->rc);
          }

          return $codigoError;
        }
      }
    } else {
      log_message('info', 'eliminar beneficiario No WS');
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }

  /**
   * Método para reprocesar el listado de beneficiarios
   * @param  string $urlCountry
   * @return json
   */
  public function reprocesar($urlCountry)
  {
    np_hoplite_countryCheck($urlCountry);
    $this->lang->load('erroreseol');

    $menuP = $this->session->userdata('menuArrayPorProducto');
    $moduloAct = np_hoplite_existeLink($menuP, "TEBGUR");

    $logged_in = $this->session->userdata('logged_in');
    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in) {
      if ($moduloAct !== false) {

        $tipo_lote = $this->input->post('data-tipoLote');
        $pass = $this->input->post('data-pass');
        $lista = $this->input->post("data-lista");
        $medioPago = $this->input->post("data-medio-pago");

        $nuevoIva = $this->input->post("data-nuevo-iva");

        $response = $this->callWSreprocesar($urlCountry, $lista, $tipo_lote, $pass, $medioPago, $nuevoIva);
      } else {
        $response = json_encode(array("ERROR" => lang('SIN_FUNCION')));
      }
      $this->output->set_content_type('')->set_output($response);
    } elseif ($paisS != $urlCountry && $paisS != '') {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      $this->output->set_content_type('application/json')->set_output(json_encode(array('ERROR' => '-29')));
    } else {
      redirect($urlCountry . '/login');
    }
  }

  public function reprocesarMasivo($urlCountry)
  {

    np_hoplite_countryCheck($urlCountry);

    $this->lang->load('erroreseol');

    $menuP = $this->session->userdata('menuArrayPorProducto');
    $moduloAct = np_hoplite_existeLink($menuP, "TEBGUR");

    $logged_in = $this->session->userdata('logged_in');
    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in) {

      if ($moduloAct !== false) {

        $pass = $this->input->post("pass");
        $monto = $this->input->post("monto");
        $concepto = $this->input->post("concepto");
        $id_registro = $this->input->post("id_registro");

        $response = $this->callWSreprocesarMasivo($urlCountry, $concepto, $monto, $pass, $id_registro);
      } else {
        $response = json_encode(array("ERROR" => lang('SIN_FUNCION')));
      }

      log_message('info', 'callWSreprocesarMasivo Encrypt ====>> ' . json_encode($response));

      $this->output->set_content_type('')->set_output(json_encode($response));
    } elseif ($paisS != $urlCountry && $paisS != '') {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      $this->output->set_content_type('application/json')->set_output(json_encode(array('ERROR' => '-29')));
    } else {
      redirect($urlCountry . '/login');
    }
  }

  private function callWSreprocesarMasivo($urlCountry, $concepto, $monto, $pass, $id_registro)
  {

    $this->lang->load('erroreseol');
    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');
    $idEmpresa = $this->session->userdata('acrifS');
    $idProductoS = $this->session->userdata('idProductoS');
    $acodcia = $this->session->userdata('accodciaS');
    $acgrupo = $this->session->userdata('accodgrupoeS');

    $canal = "ceo";
    $modulo = "Reprocesar Lotes";
    $function = "Reprocesar Guarderia";
    $operation = "actualizarDataMasivaBeneficiario";
    $ip = $this->input->ip_address();
    $timeLog = date("m/d/Y H:i");
    $className = "com.novo.objects.TOs.RegistrosLoteGuarderiaTO";

    $sessionId = $this->session->userdata('sessionId');

    $logAcceso = np_hoplite_log(
      $sessionId,
      $username,
      $canal,
      $modulo,
      $function,
      $operation,
      0,
      $ip,
      $timeLog
    );
    $usuario = array(
      "userName" => $username,
      "password" => $pass
    );

    $data = array(
      "idOperation" => $operation,
      "className" => $className,
      'id_ext_emp' => $idEmpresa,
      'concepto' => $concepto,
      'monto_total' => $monto,
      'id_registro' => $id_registro,
      "logAccesoObject" => $logAcceso,
      "token" => $token,
      "pais" => $urlCountry
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);
    log_message('info', 'callWSreprocesarMasivo ====>> ' . $data);
    $dataEncry = np_Hoplite_Encryption($data, 'callWSreprocesarMasivo');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    log_message('info', 'callWSreprocesarMasivo Encrypt ====>> ' . $data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSreprocesarMasivo');
    $response = json_decode($jsonResponse);
    //log_message('info', 'Response ' . $response);
    if ($response) {
      log_message('info', 'REPROCESAR ' . $response->rc . '/' . $response->msg);
      if ($response->rc == 0) {
        return json_encode($response);
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          $codigoError = array('ERROR' => '-29');
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')');

          $codigoError = (strpos($codigoError, 'Error') !== false) ?
            array('ERROR' => lang('ERROR_GENERICO_USER')) :
            array('ERROR' => lang('ERROR_(' . $response->rc . ')'));
        }
        return json_encode($codigoError);
      }
    } else {
      log_message('info', 'REPROCESAR No WS');
      return json_encode(array('ERROR' => lang('ERROR_GENERICO_USER')));
    }
  }
  /**
   * Método que realiza petición al WS para reprocesar el listado de beneficiarios
   * @param  string $urlCountry
   * @param  string $lista
   * @param  string $tipo_lote
   * @param  string $pass
   * @return array
   */
  private function callWSreprocesar($urlCountry, $lista, $tipo_lote, $pass, $medio_pago, $nuevoIva)
  {
    $this->lang->load('erroreseol');

    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');
    $idEmpresa = $this->session->userdata('acrifS');
    $idProductoS = $this->session->userdata('idProductoS');
    $acodcia = $this->session->userdata('accodciaS');
    $acgrupo = $this->session->userdata('accodgrupoeS');

    $canal = "ceo";
    $modulo = "Reprocesar Lotes";
    $function = "Reprocesar Guarderia";
    $operation = "reprocesarLote";
    $ip = $this->input->ip_address();
    $timeLog = date("m/d/Y H:i");
    $className = "com.novo.objects.MO.PlantillaGuarderiaMO";

    $sessionId = $this->session->userdata('sessionId');

    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $operation, 0, $ip, $timeLog);

    $usuario = array(
      "userName" => $username,
      "password" => $pass
    );

    $listado = [];
    if ($lista) {
      $idEmpresa = "";
      foreach ($lista as $key => $value) {
        $listado[$key] = array("id_registro" => $value);
      }
    }

    $data = array(
      "idOperation" => $operation,
      "className" => $className,
      "rifEmpresa" => $idEmpresa,
      "acCodCia" => $acodcia,
      "acCodGrupo" => $acgrupo,
      "idProducto" => $idProductoS,
      "lista" => $listado,
      "usuario" => $usuario,
      "paginar" => false,
      "tipo_lote" => $tipo_lote,
      "formato" => "00",
      "logAccesoObject" => $logAcceso,
      "token" => $token,
      "pais" => $urlCountry
    );
    if ($nuevoIva == 1) {
      $data["medioPago"] = $medio_pago;
    }
    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSreprocesar');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSreprocesar');
    $response = json_decode($jsonResponse);

    if ($response) {
      log_message('info', 'REPROCESAR ' . $response->rc . '/' . $response->msg);
      if ($response->rc == 0) {
        return serialize($response);
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          $codigoError = array('ERROR' => '-29');
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')');
          if (strpos($codigoError, 'Error') !== false) {
            $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'));
          }
        }
        return json_encode($codigoError);
      }
    } else {
      log_message('info', 'REPROCESAR No WS');
      return json_encode(array('ERROR' => lang('ERROR_GENERICO_USER')));
    }
  }
}
