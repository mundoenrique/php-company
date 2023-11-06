<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Clase Users
 *
 * Esta clase realiza las operaciónes relacionadas al usuario como:
 * login, logout, cambio de clave y todo el módulo de configuración.
 *
 * @package    controllers
 * @author     Wilmer Rojas <rojaswilmer@gmail.com>
 * @author     Carla García <neiryerit@gmail.com>
 */
class Users extends CI_Controller
{
  /**
   * Método que carga la pantalla principal del aplicativo
   *
   * @param  string $urlCountry
   */
  public function login($urlCountry)
  {
    //cargar libreria para identificar el navegador
    $this->load->library('user_agent');

    //mostrar en el log el navegador usado y la versión
    log_message("info", $this->agent->browser() . $this->agent->version());

    $browser = strtolower($this->agent->browser());
    $version = (float) $this->agent->version();
    $noBrowser = "internet explorer";
    $sliderbar = true;
    $contentpage = 'users/content-login';

    //si el navegador es IE con versión menor a 8, cargar la pantalla de actualizar el navegador
    if ($browser == $noBrowser && $version < 8.0) {
      $sliderbar = false;
      $contentpage = 'staticpages/content-browser';
    }

    //cargar archivo de configuración del país
    np_hoplite_countryCheck($urlCountry);

    //cargar lenguajes
    $this->lang->load('users');
    $this->lang->load('dashboard');
    $this->lang->load('erroreseol');

    //obtener variable de sesión que indica si el usuario está logueado ó no
    $logged_in = $this->session->userdata('logged_in');
    $newuser = $this->session->userdata('newuser_in');
    $caducoPass = $this->session->userdata('caducoPass');
    //si el usuario ya está loggedin, se redirecciona al dashboard (listado de empresas)
    if ($logged_in && !$newuser && !$caducoPass) {
      redirect($urlCountry . '/dashboard');
    } else {
      redirect(base_url($this->config->item('countryUri') . '/inicio'), 'location', 301);
      //INSTANCIA PARA TITULO DE PAGINA
      $titlePage = "Conexión Empresas Online";
      //INSTANCIA GENERAR  HEADER
      $menuHeader = $this->parser->parse('widgets/widget-menuHeader', array(), TRUE);
      //INSTANCIA GENERAR  FOOTER
      $menuFooter = $this->parser->parse('widgets/widget-menuFooter', array(), TRUE);
      //INSTANCIA DEL CONTENIDO PARA EL HEADER, INCLUYE MENU
      $header = $this->parser->parse('layouts/layout-header', array('menuHeaderActive' => FALSE, 'menuHeaderMainActive' => FALSE, 'menuHeader' => $menuHeader, 'titlePage' => $titlePage, 'is_login' => true), TRUE);
      //JAVASCRIPTS A CARGAR
      if ($urlCountry == 'Ec-bp') {
        $FooterCustomInsertJS = ["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js", "jquery-md5.js", "jquery.ui.sliderbutton.js", "jquery.balloon.min.js", "aes.min.js", "aes-json-format.min.js", "users/login.js", "routes.js"];
      } else {
        $FooterCustomInsertJS = ["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js", "jquery-md5.js", "jquery.kwicks.js", "jquery.ui.sliderbutton.js", "jquery.balloon.min.js", "aes.min.js", "aes-json-format.min.js", "users/login.js", "routes.js"];
      }
      //INSTANCIA DE CÓDIGO JS A AGREGAR
      $FooterCustomJS = "";
      //INSTANCIA PARA EL FOOTER
      $footer = $this->parser->parse('layouts/layout-footer', array('menuFooterActive' => TRUE, 'menuFooter' => $menuFooter, 'FooterCustomInsertJSActive' => $sliderbar, 'FooterCustomInsertJS' => $FooterCustomInsertJS, 'FooterCustomJSActive' => FALSE, 'FooterCustomJS' => $FooterCustomJS), TRUE);
      //INSTANCIA DE PARTE DE CUERPO
      $content = $this->parser->parse($contentpage, array(), TRUE);
      //INSTANCIA DE SIDERBAR
      $sidebarlogin = $this->parser->parse('users/widget-signin', array('sidebarActive' => $sliderbar), TRUE);

      //DATA QUE SE PASA AL LAYOUT EN GENERAL
      //ACA SE INSTANCIA EL HEADER FOOTER CONTENT Y SIDERBAR
      $data = array(
        'header' => $header,
        'content' => $content,
        'footer' => $footer,
        'sidebar' => $sidebarlogin
      );

      $this->parser->parse('layouts/layout-a', $data);
    }
  }

  /**
   * Método que toma los datos de login para ralizar el inicio de sesión
   *
   * @param  string $urlCountry
   * @return string
   */
  public function validationAuth($urlCountry)
  {
    //cargar archivo de configuración del país
    np_hoplite_countryCheck($urlCountry);

    //obtener variable de sesión que indica si el usuario está logueado ó no
    $logged_in = $this->session->userdata('logged_in');

    //si es una petición realizada con ajax
    if ($this->input->is_ajax_request()) {

      $username = $this->input->post('userName');
      $password = $this->input->post('userName');
      $useractive = $this->input->post('user_active');
      $responseLoginFull = $this->callWSLoginFull($username, $password, $urlCountry, $useractive);
      echo $responseLoginFull;
    }
  }

  /**
   * Método que envía la petición LOGIN en formato JSON al servicio web
   *
   * @param  string $username
   * @param  string $password
   * @param  string $pais
   * @return string
   */
  private function callWSLoginFull($username, $password, $pais, $useractive)
  {

    $this->lang->load('erroreseol'); // CARGAR PLANTILLA DE LENGUAJE DE ERRORES
    $this->lang->load('users');     // CARGAR PLANTILLA DE LENGUAJE USUARIO

    $canal = "ceo";
    $modulo = 'login';
    $function = 'login';
    $operation = 'loginFull';
    $className = 'com.novo.objects.TOs.UsuarioTO';
    $timeLog = date('m/d/Y H:i');
    $ip = $this->input->ip_address();
    $logAcceso = np_hoplite_log('', $username, $canal, $modulo, $function, $operation, 0, $ip, $timeLog);

    $data = array(
      'idOperation' => $operation,
      'className' => $className,
      'userName' => $username,
      'password' => $password,
      'logAccesoObject' => $logAcceso,
      'token' => '',
      'pais' => $pais,
      'ctipo' => $useractive
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSLoginFull');
    $data = array('bean' => $dataEncry, 'pais' => $pais);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data); // ENVÍA LA PETICIÓN Y ALMACENA LA RESPUESTA EN $response
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSLoginFull');

    $response = json_decode($jsonResponse);

    if (isset($response)) {

      if ($response->rc == -229) {
        return 'userold'; // EL USUARIO TIENE BANDERA EN 1

      } elseif ($response->rc == -262) {
        return 'desconocido'; // EL USUARIO NO HA SIDO INCORPORADO PARA USAR CEO

      } elseif ($response->rc == -28) {
        return 'conectado'; // EL USUARIO YA SE ENCUENTRA CONECTADO (ha cerrado incorrectamente su sesión)

      } elseif ($response->rc == 0 || $response->rc == -2 || $response->rc == -185) { // solo para los casos donde el usuario establece conexión
        log_message('DEBUG', 'RESPONSE DATAUSER: ' . json_encode($response->usuario));

        $datos['userName'] = $response->usuario->userName;
        $datos['idUsuario'] = $response->usuario->idUsuario;
        $datos['Nombre'] = $response->usuario->primerNombre;
        $datos['Apellido'] = $response->usuario->primerApellido;
        $datos['codigoGrupo'] = $response->usuario->codigoGrupo;
        $datos['sessionId'] = $response->logAccesoObject->sessionId;

        $CI = &get_instance();
        $format_date = $CI->config->item('format_date');
        $format_time = $CI->config->item('format_time');

        $datos['lastSession'] = date("$format_date $format_time", strtotime(str_replace('/', '-', $response->usuario->fechaUltimaConexion)));
        $datos['token'] = $response->token;

        $newdata = array(
          'idUsuario' => $datos['idUsuario'],
          'userName' => $datos['userName'],
          'nombreCompleto'  => mb_strtolower($datos['Nombre']) . ' ' . mb_strtolower($datos['Apellido']),
          'codigoGrupo' => $datos['codigoGrupo'],
          'lastSession' => $datos['lastSession'],
          'token' => $datos['token'],
          'sessionId' => $datos['sessionId'],
          'cl_addr' => np_Hoplite_Encryption($this->input->ip_address()),
          'pais' => $pais
        );

        $this->session->set_userdata($newdata); // ALMACENAR DATOS EN SESIÓN
      } elseif ($response->rc == -8 && $pais == "Ve") {

        return 'bloqueado'; // EL USUARIO esta bloqueado

      }
    }

    if (isset($response) && $response->rc == 0) {

      $this->session->set_userdata('logged_in', TRUE);

      return 'validated'; // EL USUARIO SE HA LOGUEADO CON ÉXITO

    } else {
      if (isset($response) && $response->rc == -2) {

        $this->session->set_userdata('logged_in', TRUE);
        $this->session->set_userdata('newuser_in', TRUE);

        return 'newuser'; // NUEVO USUARIO

      } elseif (isset($response) && $response->rc == -185) {

        $this->session->set_userdata('logged_in', TRUE);
        $this->session->set_userdata('newuser_in', FALSE);
        $this->session->set_userdata('caducoPass', TRUE);

        //log_message('info','Resultado Login -> '.json_encode($response));

        if (isset($response->usuario->ctipo) && $response->usuario->ctipo == 1)
          $this->session->set_userdata('userold', TRUE);
        else
          $this->session->set_userdata('userold', FALSE);

        return 'caducoPass'; // contraseña vencida y debe cambiarla

      } elseif (isset($response) && $response->rc == -1) {

        $codigoError = lang('ERROR_(' . $response->rc . ')');
      } elseif (isset($response) && $response->rc == -263) {

        $codigoError = lang('MSG_BLOQUEAR_USER');
      } elseif (isset($response)) {

        $codigoError = lang('ERROR_(' . $response->rc . ')'); // OBTENER LA DESCRIPCION DEL ERROR (DE LA PLANTILLA DE LENGUAJE)
        if (strpos($codigoError, 'Error') !== false) {        // VERIFICAR SI ES UN ERROR QUE SE PUEDE MOSTRAR
          $codigoError = lang('ERROR_GENERICO_USER'); // ERROR GENERICO
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')');
        }
      } else {
        log_message('info', 'response login NO WS');
        $codigoError = lang('ERROR_GENERICO_USER');
      }

      return $codigoError;
    }
  }

  /**
   * Cierra la sesión del usuario e independientemente del resultado,
   * lo redirecciona a la página principal (login)
   *
   * @param  string $urlCountry
   */
  public function logout($urlCountry)
  {
    //cargar archivo de configuración del país
    np_hoplite_countryCheck($urlCountry);

    // CARGAR PLANTILLA DE LENGUAJE DE ERRORES
    $this->lang->load('erroreseol');

    //obtener variable de sesión que indica si el usuario está logueado ó no
    $logged_in = $this->session->userdata('logged_in');

    //obtener nombre del usuario a desloguear (enviado por post)
    $user = $this->input->post('data-user');

    if ($logged_in || $user) {

      if ($logged_in) {
        $username = $this->session->userdata('userName');
      } else {
        $username = $user;
      }

      $token = $this->session->userdata('token');
      $sessionId = $this->session->userdata('sessionId');
      $codigoGrupo = $this->session->userdata('codigoGrupo');

      $timeLog = date("m/d/Y H:i");
      $ip = $this->input->ip_address();

      $operation = 'desconectarUsuario';
      $classname = 'com.novo.objects.TOs.UsuarioTO';
      $canal = "ceo";
      $modulo = 'logout';
      $function = 'logout';

      $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $operation, 0, $ip, $timeLog);

      $data = array(
        'pais' => $urlCountry,
        'idOperation' => $operation,
        'className' =>  $classname,
        'idUsuario' => $username,
        'sessionId' => $token,
        'codigoGrupo' => $codigoGrupo,
        'logAccesoObject' => $logAcceso,
        'token' => $token
      );

      $data = json_encode($data, JSON_UNESCAPED_UNICODE);

      log_message('DEBUG', 'REQUEST LOGOUT:' . $data);

      $dataEncry = np_Hoplite_Encryption($data, 'logout');
      $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
      $data = json_encode($data);
      $response = np_Hoplite_GetWS($data); // ENVÍA LA PETICIÓN Y ALMACENA LA RESPUESTA EN $response
      $jsonResponse = np_Hoplite_Decrypt($response, 'logout');

      log_message('DEBUG', 'RESPONSE LOGOUT:' . $jsonResponse);

      $response = json_decode($jsonResponse);

      $finsesion = $this->input->post('data-caducada');
      $this->session->sess_destroy();

      if ($finsesion) {
        $finsesion = $urlCountry . '/finsesion'; // pantalla de sesión caducada
      } else {
        $finsesion = $urlCountry . '/login';     // pantalla principal
      }

      if ($response) {
        log_message('info', 'logout ' . $response->rc);
        redirect($finsesion);
      } else {
        log_message('info', 'logout NO WS ');
        redirect($finsesion);
      }
    } else {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    }
  }


  /**
   * Pantalla alusiva a sesión caducada
   *
   * @param  string $urlCountry
   */
  public function pantallaLogout($urlCountry)
  {

    //cargar archivo de configuración del país
    np_hoplite_countryCheck($urlCountry);

    $this->lang->load('users');  // CARGAR PLANTILLA DE LENGUAJE USUARIO
    $this->lang->load('dashboard');  // CARGAR PLANTILLA DE LENGUAJE PARÁMETROS GENERALES

    //INSTANCIA PARA TITULO DE PAGINA
    $titlePage = "Conexión Empresas Online";
    //INSTANCIA GENERAR  HEADER
    $menuHeader = $this->parser->parse('widgets/widget-menuHeader', array(), TRUE);
    //INSTANCIA GENERAR  FOOTER
    $menuFooter = $this->parser->parse('widgets/widget-menuFooter', array(), TRUE);
    //INSTANCIA DEL CONTENIDO PARA EL HEADER, INCLUYE MENU
    $header = $this->parser->parse('layouts/layout-header', array('menuHeaderActive' => FALSE, 'menuHeaderMainActive' => FALSE, 'menuHeader' => $menuHeader, 'titlePage' => $titlePage), TRUE);
    //JAVASCRIPTS A CARGAR
    $FooterCustomInsertJS = [];
    //INSTANCIA DEL FOOTER
    $footer = $this->parser->parse('layouts/layout-footer', array('menuFooterActive' => TRUE, 'menuFooter' => $menuFooter, 'FooterCustomInsertJSActive' => TRUE, 'FooterCustomInsertJS' => $FooterCustomInsertJS, 'FooterCustomJSActive' => FALSE), TRUE);
    //INSTANCIA DE PARTE DE CUERPO
    $content = $this->parser->parse('staticpages/content-sesionCaducada', array(), TRUE);
    //INSTANCIA DE SIDERBAR
    $sidebarlogin = $this->parser->parse('users/widget-signin', array('sidebarActive' => false), TRUE);

    //DATA QUE SE PASA AL LAYOUT EN GENERAL
    //ACA SE INSTANCIA EL HEADER FOOTER CONTENT Y SIDERBAR
    $data = array(
      'header' => $header,
      'content' => $content,
      'footer' => $footer,
      'sidebar' => $sidebarlogin
    );

    $this->parser->parse('layouts/layout-a', $data);
  }
  /**
   * Pantalla que muestra Recuperar contraseña
   *
   * @param  string $urlCountry
   */
  public function pass_recovery($urlCountry)
  {

    //cargar archivo de configuración del país
    np_hoplite_countryCheck($urlCountry);

    $this->lang->load('users');  // CARGAR PLANTILLA DE LENGUAJE USUARIO
    $this->lang->load('dashboard');  // CARGAR PLANTILLA DE LENGUAJE PARÁMETROS GENERALES

    //INSTANCIA PARA TITULO DE PAGINA
    $titlePage = "Conexión Empresas Online";
    //INSTANCIA GENERAR  HEADER
    $menuHeader = $this->parser->parse('widgets/widget-menuHeader', array(), TRUE);
    //INSTANCIA GENERAR  FOOTER
    $menuFooter = $this->parser->parse('widgets/widget-menuFooter', array(), TRUE);
    //INSTANCIA DEL CONTENIDO PARA EL HEADER, INCLUYE MENU
    $header = $this->parser->parse('layouts/layout-header', array('menuHeaderActive' => FALSE, 'menuHeaderMainActive' => FALSE, 'menuHeader' => $menuHeader, 'titlePage' => $titlePage), TRUE);
    //JAVASCRIPTS A CARGAR
    $FooterCustomInsertJS = ["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js", "jquery-md5.js", "jquery.kwicks.js", "jquery.ui.sliderbutton.js", "jquery.balloon.min.js", "aes.min.js", "aes-json-format.min.js", "users/pass-recovery.js", "routes.js"];
    //INSTANCIA DEL FOOTER
    $footer = $this->parser->parse('layouts/layout-footer', array('menuFooterActive' => TRUE, 'menuFooter' => $menuFooter, 'FooterCustomInsertJSActive' => TRUE, 'FooterCustomInsertJS' => $FooterCustomInsertJS, 'FooterCustomJSActive' => FALSE), TRUE);
    //INSTANCIA DE PARTE DE CUERPO
    $content = $this->parser->parse('users/content-recovery', array(), TRUE);
    //INSTANCIA DE SIDERBAR
    $sidebarlogin = $this->parser->parse('users/widget-signin', array('sidebarActive' => false), TRUE);

    //DATA QUE SE PASA AL LAYOUT EN GENERAL
    //ACA SE INSTANCIA EL HEADER FOOTER CONTENT Y SIDERBAR
    $data = array(
      'header' => $header,
      'content' => $content,
      'footer' => $footer,
      'sidebar' => $sidebarlogin
    );

    $this->parser->parse('layouts/layout-a', $data);
  }

  /**
   * Método que recibe los parametros necesarios para recuperar contraseña
   *
   * @param  string $urlCountry
   * @return string
   */
  public function PassRecovery($urlCountry)
  {
    //cargar archivo de configuración del país
    np_hoplite_countryCheck($urlCountry);

    $this->lang->load('erroreseol'); // CARGAR PLANTILLA DE LENGUAJE DE ERRORES

    //SOLO SE PUEDE HACER VIA AJAX EL CAMBIO
    if ($this->input->is_ajax_request()) {

      $username = $this->input->post('userName');
      $email = $this->input->post('email');
      $idEmpresa = $this->input->post('idEmpresa');

      $responsePassRecovery = $this->callWSPassRecovery($username, $email, $idEmpresa, $urlCountry);
      $this->output->set_content_type('application/json')->set_output(json_encode($responsePassRecovery));
    } else {
      $this->output->set_content_type('application/json')->set_output(json_encode(array('ERROR' => lang('ERROR_(-29)'), "rc" => "-29")));
    }
  }
  /**
   * Método que invoca al servicio web para recuperar contraseña
   *
   * @param  string $username
   * @param  string $token
   * @param  string $urlCountry
   * @return string
   */
  private function callWSPassRecovery($username, $email, $idEmpresa, $urlCountry)
  {

    $this->lang->load('erroreseol'); //HOJA DE ERRORES;


    $canal = "ceo";
    $modulo = "listaEmpresas";
    $function = "listarEmpreas";
    $operation = "olvidoClave";
    $operacion = "getPaginar";
    $className = "com.novo.objects.TO.UsuarioTO";
    $timeLog = date("m/d/Y H:i");
    $ip = $this->input->ip_address();
    $sessionId = $this->session->userdata('sessionId');
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $operacion, 0, $ip, $timeLog);

    $data = array(
      "idOperation" => $operation,
      "className" => $className,
      "userName" => $username,
      "email" => $email,
      "idEmpresa" => $idEmpresa,
      "logAccesoObject" => $logAcceso,
      "token" => "",
      "pais" => $urlCountry
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);
    $dataEncry = np_Hoplite_Encryption($data, 'callWSPassRecovery');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data); // ENVÍA LA PETICIÓN Y ALMACENA LA RESPUESTA EN $response
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSPassRecovery');

    $response = json_decode($jsonResponse);
    $dataResponse = json_encode($response);
    log_message('info', 'Response recovery pass ' . $dataResponse);

    if (isset($response) && $response->rc == 0) {

      $this->session->set_userdata('logged_in', TRUE);
      return "validate";
    } elseif (isset($response)) {
      $codigoError = lang('ERROR_(' . $response->rc . ')'); // OBTENER LA DESCRIPCION DEL ERROR (DE LA PLANTILLA DE LENGUAJE)
      if ($response->rc == -158) {

        return "No se pudo actualizar el password";
      } elseif ($response->rc == -150) {
        $dataResponse = [
          'rc' => $response->rc,
          'title' => lang('TITULO_ERROR'),
          'msg' => lang('ERROR_RIF')
        ];
        return json_encode($dataResponse); //$response;//$response->msg;
      } elseif ($response->rc == -159) {
        $dataResponse = [
          'rc' => $response->rc,
          'title' => lang('TITULO_ERROR'),
          'msg' => lang('ERROR_MAIL')
        ];
        return json_encode($dataResponse); //$response;//$response->msg;
      } elseif ($response->rc == -205) {
        $dataResponse = [
          'rc' => $response->rc,
          'title' => lang('TITULO_ERROR'),
          'msg1' => lang('ERROR_USER'),
          'msg2' => lang('ERROR_SUPPORT')
        ];
        return json_encode($dataResponse); //$response;//$response->msg;
      } elseif ($response->rc == -6) {
        return "no-companies";
      } elseif ($response->rc == -20 || $response->rc == -3) {
        return "general-error";
      } elseif ($response->rc == -173) {
        return "error-email";
      } elseif (strpos($codigoError, 'Error') !== false) { // VERIFICAR SI ES UN ERROR QUE SE PUEDE MOSTRAR
        $codigoError =  lang('ERROR_GENERICO_USER'); // ERROR GENERICO
      } else {
        $codigoError = lang('ERROR_(' . $response->rc . ')');
      }
      return $codigoError;
    } else {
      return lang('ERROR_GENERICO_USER');
    }
  }


  /**
   * Pantalla que muestra el texto con las condiciones y terminos de uso
   *
   * @param  string $urlCountry
   */
  public function terminosCondiciones($urlCountry)
  {
    //cargar archivo de configuración del país
    np_hoplite_countryCheck($urlCountry);

    $this->lang->load('users');
    $this->lang->load('dashboard');
    $this->lang->load('erroreseol');

    $newuser_in = $this->session->userdata('newuser_in');
    $logged_in = $this->session->userdata('logged_in');
    $paisS = $this->session->userdata('pais');

    if (1/*$newuser_in && $logged_in && $paisS==$urlCountry*/) {
      $titlePage = "Términos y Condiciones de Uso";

      //INSTANCIA MENU HEADER
      $menuHeader = $this->parser->parse('widgets/widget-menuHeader', array(), TRUE);
      //INSTANCIA MENU FOOTER
      $menuFooter = $this->parser->parse('widgets/widget-menuFooter', array(), TRUE);
      //INSTANCIA DEL CONTENIDO PARA EL HEADER , INCLUYE MENU
      $header = $this->parser->parse('layouts/layout-header', array('bodyclass' => 'full-width', 'menuHeaderActive' => FALSE, 'menuHeaderMainActive' => FALSE, 'menuHeader' => $menuHeader, 'titlePage' => $titlePage), TRUE);
      //JAVASCRIPTS A CARGAR.
      $FooterCustomInsertJS = ["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js", "jquery.balloon.min.js", "aes.min.js", "aes-json-format.min.js", "users/terminos.js", "routes.js"];
      $footer = $this->parser->parse('layouts/layout-footer', array('menuFooterActive' => TRUE, 'menuFooter' => $menuFooter, 'FooterCustomInsertJSActive' => TRUE, 'FooterCustomInsertJS' => $FooterCustomInsertJS, 'FooterCustomJSActive' => FALSE), TRUE);
      $content = $this->parser->parse('users/content-condiciones', array(), TRUE);
      $sidebarlogin = $this->parser->parse('users/widget-signin', array('sidebarActive' => FALSE), TRUE);

      $data = array(
        'header' => $header,
        'content' => $content,
        'footer' => $footer,
        'sidebar' => $sidebarlogin,
        'titleHeading' => 'TITULO ACA',
        'login' => 'LOGIN USUARIO',
        'password' => 'CONTRASEÑA',
        'loginBtn' => 'ENTRAR',
      );
      $this->parser->parse('layouts/layout-a', $data);
    } else {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    }
  }


  /**
   * Pantalla para el cambio de clave cuando es un usuario nuevo
   *
   * @param  string $urlCountry
   */
  public function changePassNewUser($urlCountry)
  {
    //cargar archivo de configuración del país
    np_hoplite_countryCheck($urlCountry);

    $this->lang->load('users');
    $this->lang->load('dashboard');
    $this->lang->load('erroreseol');

    $newuser_in = $this->session->userdata('newuser_in');
    $caducoPass = $this->session->userdata('caducoPass');
    $logged_in = $this->session->userdata('logged_in');
    $paisS = $this->session->userdata('pais');

    if (($newuser_in || $caducoPass) && $logged_in && $paisS == $urlCountry) {

      $nombreCompleto = $this->session->userdata('nombreCompleto');
      $lastSessionD = $this->session->userdata('lastSession');

      if ($newuser_in) {
        $titlePage = "Cambiar contraseña-Nuevo Usuario";
        $mensaje = lang("MSG_NEW_PASS_USER");
      } else {
        $titlePage = "Cambiar contraseña-Clave Vencida";
        $mensaje = lang("MSG_NEW_PASS_CADU");
      }

      //INSTANCIA MENU HEADER
      $menuHeader = $this->parser->parse('widgets/widget-menuHeader', array(), TRUE);
      //INSTANCIA MENU FOOTER
      $menuFooter = $this->parser->parse('widgets/widget-menuFooter', array(), TRUE);
      //INSTANCIA DEL CONTENIDO PARA EL HEADER , INCLUYE MENU
      $header = $this->parser->parse('layouts/layout-header', array('bodyclass' => 'full-width', 'menuHeaderActive' => FALSE, 'menuHeaderMainActive' => FALSE, 'menuHeader' => $menuHeader, 'titlePage' => $titlePage), TRUE);
      //JAVASCRIPTS A CARGAR.
      $FooterCustomInsertJS = ["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js", "jquery.balloon.min.js", "jquery-md5.js", "aes.min.js", "aes-json-format.min.js", "users/clave.js", "header.js", "routes.js"];
      $footer = $this->parser->parse('layouts/layout-footer', array('menuFooterActive' => TRUE, 'menuFooter' => $menuFooter, 'FooterCustomInsertJSActive' => TRUE, 'FooterCustomInsertJS' => $FooterCustomInsertJS, 'FooterCustomJSActive' => FALSE), TRUE);
      $content = $this->parser->parse('users/content-changePassNewUser', array(
        'titulo' => $nombreCompleto,
        'breadcrum' => '',
        'lastSession' => $lastSessionD,
        'mensaje' => $mensaje
      ), TRUE);
      $sidebarlogin = $this->parser->parse('users/widget-signin', array('sidebarActive' => FALSE), TRUE);

      $data = array(
        'header' => $header,
        'content' => $content,
        'footer' => $footer,
        'sidebar' => $sidebarlogin,
        'titleHeading' => 'TITULO ACA',
        'login' => 'LOGIN USUARIO',
        'password' => 'CONTRASEÑA',
        'loginBtn' => 'ENTRAR',
      );
      $this->parser->parse('layouts/layout-a', $data);
    } elseif ($paisS != $urlCountry  && $paisS != "") {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } else {
      redirect($urlCountry . '/login');
    }
  }


  /**
   * Método que recibe los parametros necesarios para el cambio de contraseña
   *
   * @param  string $urlCountry
   * @return string
   */
  public function changePassNewUserAuth($urlCountry)
  {
    //cargar archivo de configuración del país
    np_hoplite_countryCheck($urlCountry);
    // CARGAR PLANTILLA DE LENGUAJE DE ERRORES
    $this->lang->load('erroreseol');
    //obtiene la variable de logueo
    $logged_in = $this->session->userdata('logged_in');
    //obtiene el país
    $paisS = $this->session->userdata('pais');
    //Verifica la sesión y el país
    if ($logged_in && $paisS == $urlCountry) {
      //SOLO SE PUEDE HACER VIA AJAX EL CAMBIO
      if ($this->input->is_ajax_request()) {
        //obtiene el usuario de la sesión
        $username = $this->session->userdata('userName');
        //obtiene el token de seguridad
        $token = $this->session->userdata('token');
        //recibe la clave actual y la nueva
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
        $password = $dataRequest->userpwd;
        $passwordOld = $dataRequest->userpwdOld;
        //obtiene la respuesta del modelo
        $responseCambioClave = $this->callWScambioClave($username, $password, $passwordOld, $token, $urlCountry);
        //Devuelve la respuesta del modelo
        $response = json_decode($responseCambioClave);
        $response = $this->cryptography->encrypt($response);
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
      }
    } elseif ($paisS != $urlCountry && $paisS != '') {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      $responseCambioClave = json_encode(
        $responseCambioClave = [
          'rc' => '-29',
          'msg' => lang('ERROR_(-29)')
        ]
      );
      $response = $this->cryptography->encrypt($responseCambioClave);
      $this->output->set_content_type('')->set_output($response);
    } else {
      redirect($urlCountry . '/login');
    }
  }

  /**
   * Método que invoca al servicio web para el cambio de contraseña
   *
   * @param  string $username
   * @param  string $password
   * @param  string $passwordOld
   * @param  string $token
   * @param  string $pais
   * @return string
   */
  private function callWScambioClave($username, $password, $passwordOld, $token, $pais)
  {

    $this->lang->load('erroreseol'); //language errors
    $this->lang->load('users'); //language users

    $canal = "ceo";
    $modulo = "login";
    $function = "login";
    $operation = "cambioClave";
    $className = "com.novo.objects.TOs.UsuarioTO";
    $timeLog = date("m/d/Y H:i");
    $ip = $this->input->ip_address();
    $sessionId = $this->session->userdata('sessionId');
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, "Login", 0, $ip, $timeLog);

    $data = array(
      "idOperation" => $operation,
      "className" => $className,
      "userName" => $username,
      "passwordOld" => $passwordOld,
      "password" => $password,
      "logAccesoObject" => $logAcceso,
      "token" => $token,
      "pais" => $pais
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWScambioClave');
    $data = array('bean' => $dataEncry, 'pais' => $pais);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data); // ENVÍA LA PETICIÓN Y ALMACENA LA RESPUESTA EN $response
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWScambioClave');

    $response = json_decode($jsonResponse);

    log_message('info', 'cambioClave: ' . $jsonResponse);

    if (isset($response) && $response->rc == 0) {
      $this->session->set_userdata('logged_in', TRUE);
      $dataResponse = [
        'rc' => $response->rc,
        'redirect' => lang('INFO_REDIRECT'),
        'msg' => lang('CHANGE_OK')
      ];
    } elseif (isset($response)) {
      $codigoError = lang('ERROR_(' . $response->rc . ')'); // OBTENER LA DESCRIPCION DEL ERROR (DE LA PLANTILLA DE LENGUAJE)
      if ($response->rc == -22 || $response->rc == -4) {
        $dataResponse = [
          'rc' => $response->rc,
          'msg' => $codigoError
        ];
      } else {
        $dataResponse = [
          'rc' => $response->rc,
          'msg' => lang('ERROR_GENERICO_USER')
        ];
      }
    } else {
      $dataResponse = [
        'rc' => '',
        'msg' => lang('ERROR_GENERICO_USER')
      ];
    }
    return json_encode($dataResponse); //Devuelve la respuesta del servicio
  }


  /**
   * Pantalla del módulo de configuración.
   * Muestra inicialmente la configuración de usuario.
   *
   * @param  string $urlCountry
   *
   */
  public function configUsuario($urlCountry)
  {

    //cargar archivo de configuración del país
    np_hoplite_countryCheck($urlCountry);

    $this->lang->load('dashboard');
    $this->load->library('parser');
    $this->lang->load('users');
    $this->lang->load('erroreseol');

    $logged_in = $this->session->userdata('logged_in');
    $paisS = $this->session->userdata('pais');

    if ($logged_in && $paisS == $urlCountry) {

      $username = $this->session->userdata('userName');
      $token = $this->session->userdata('token');
      $nombreCompleto = $this->session->userdata('nombreCompleto');
      $lastSessionD = $this->session->userdata('lastSession');
      $jsRte = '../../../js/';
      $thirdsJsRte = '../../../js/third_party/';
      $FooterCustomInsertJS = [
        "jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js",
        "jquery.balloon.min.js", "jquery-md5.js", "jquery.paginate.js", "aes.min.js", "aes-json-format.min.js",
        "users/configuracion.js", "header.js", "jquery.fileupload.js", "jquery.iframe-transport.js", "routes.js",
        $thirdsJsRte . "jquery.validate.min.js", $jsRte . "validate-forms.js", $thirdsJsRte . "additional-methods.min.js"
      ];
      $FooterCustomJS = "";
      $titlePage = "Conexión Empresas Online-Configuración";
      $title = "Configuración";
      $idProductoS = $this->session->userdata('idProductoS');
      $idEmpresa = $this->session->userdata('acrifS');
      $programa = $this->session->userdata('nombreProductoS') . ' / ' . $this->session->userdata('marcaProductoS');
      $menuHeader = $this->parser->parse('widgets/widget-menuHeader', array(), TRUE);
      $menuFooter = $this->parser->parse('widgets/widget-menuFooter', array(), TRUE);
      $header = $this->parser->parse('layouts/layout-header', array('bodyclass' => '', 'menuHeaderActive' => TRUE, 'menuHeaderMainActive' => TRUE, 'menuHeader' => $menuHeader, 'titlePage' => $titlePage), TRUE);

      $footer = $this->parser->parse(
        'layouts/layout-footer',
        array(
          'menuFooterActive' => TRUE, 'menuFooter' => $menuFooter,
          'FooterCustomInsertJSActive' => TRUE,
          'FooterCustomInsertJS' => $FooterCustomInsertJS,
          'FooterCustomJSActive' => TRUE,
          'FooterCustomJS' => $FooterCustomJS
        ),
        TRUE
      );

      $content = $this->parser->parse('users/content-userconfig', array(
        'titulo' => $title,
        'lastSession' => $lastSessionD,
        'user' => $username
      ), TRUE);
      $sidebarLotes = $this->parser->parse('dashboard/widget-empresa', array('sidebarActive' => FALSE), TRUE);

      $datos = array(
        'header' => $header,
        'content' => $content,
        'footer' => $footer,
        'sidebar' => $sidebarLotes
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
   * Pantalla del módulo de configuración.
   * Muestra la configuración de empresas.
   *
   * @param  string $urlCountry
   *
   */
  public function configEmpresa($urlCountry)
  {
    //cargar archivo de configuración del país
    np_hoplite_countryCheck($urlCountry);

    $this->lang->load('dashboard');
    $this->load->library('parser');
    $this->lang->load('users');
    $this->lang->load('erroreseol');
    $logged_in = $this->session->userdata('logged_in');
    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in) {

      $username = $this->session->userdata('userName');
      $token = $this->session->userdata('token');
      $nombreCompleto = $this->session->userdata('nombreCompleto');
      $lastSessionD = $this->session->userdata('lastSession');

      $idProductoS = $this->session->userdata('idProductoS');
      $idEmpresa = $this->session->userdata('acrifS');

      $lista[] = $this->callWSListaEmpresas($urlCountry);
      $content = $this->parser->parse('users/content-empresasconfig', array('listaEmpr' => $lista), TRUE);

      $datos = array(
        'content' => $content
      );

      $this->parser->parse('layouts/layout-b', $datos);
    } elseif ($paisS != $urlCountry && $paisS != '') {
      $this->session->sess_destroy();
      echo 'Usuario Desconectado';
    } else {
      echo 'Usuario Desconectado';
    }
  }


  /**
   * Pantalla del módulo de configuración.
   * Muestra la configuración de sucursales.
   *
   * @param  string $urlCountry
   *
   */
  public function configSucursal($urlCountry)
  {

    //cargar archivo de configuración del país
    np_hoplite_countryCheck($urlCountry);

    $this->lang->load('dashboard');
    $this->load->library('parser');
    $this->lang->load('users');
    $this->lang->load('erroreseol');
    $logged_in = $this->session->userdata('logged_in');

    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in) {

      $username = $this->session->userdata('userName');
      $token = $this->session->userdata('token');
      $nombreCompleto = $this->session->userdata('nombreCompleto');
      $lastSessionD = $this->session->userdata('lastSession');

      $lista[] = $this->callWSListaEmpresas($urlCountry);

      $idProductoS = $this->session->userdata('idProductoS');
      $idEmpresa = $this->session->userdata('acrifS');


      $content = $this->parser->parse('users/content-configSucursales', array(
        'listaEmpr' => $lista
      ), TRUE);

      $datos = array(
        'content' => $content
      );

      $this->parser->parse('layouts/layout-b', $datos);
    } elseif ($paisS != $urlCountry && $paisS != "") {
      $this->session->sess_destroy();
      echo 'Usuario Desconectado';
    } else {
      echo 'Usuario Desconectado';
    }
  }

  /**
   * Pantalla del módulo de configuración.
   * Muestra los links de descargas de los gestores archivos y manuales.
   *
   * @param  string $urlCountry
   *
   */
  public function configDescargas($urlCountry)
  {

    //cargar archivo de configuración del país
    np_hoplite_countryCheck($urlCountry);

    $this->lang->load('dashboard');
    $this->load->library('parser');
    $this->lang->load('users');
    $this->lang->load('erroreseol');
    $logged_in = $this->session->userdata('logged_in');

    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in) {

      $content = $this->parser->parse('users/content-descargasconfig', array(), TRUE);

      $datos = array(
        'content' => $content
      );

      $this->parser->parse('layouts/layout-b', $datos);
    } elseif ($paisS != $urlCountry && $paisS != "") {
      $this->session->sess_destroy();
      echo 'Usuario Desconectado';
    } else {
      echo 'Usuario Desconectado';
    }
  }

  /**
   * Pantalla del módulo de configuración.
   * Muestra los links de descargas de los gestores archivos y manuales.
   *
   * @param  string $urlCountry
   *
   */
  public function configNotificaciones($urlCountry)
  {
    log_message('info', ' ==============>>>>>>>>>>>>>> configNotificaciones');

    //cargar archivo de configuración del país
    np_hoplite_countryCheck($urlCountry);

    $this->lang->load('dashboard');
    $this->load->library('parser');
    $this->lang->load('users');
    $this->lang->load('erroreseol');
    $logged_in = $this->session->userdata('logged_in');

    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in) {

      $lista[] = $this->callWSListaEmpresas($urlCountry);
      $content = $this->parser->parse(
        'users/content-notificacionesconfig',
        array('listaEmpr' => $lista),
        TRUE
      );
      $datos = array(
        'content' => $content,
      );

      $this->parser->parse('layouts/layout-b', $datos);
    } elseif ($paisS != $urlCountry && $paisS != "") {
      $this->session->sess_destroy();
      echo 'Usuario Desconectado';
    } else {
      echo 'Usuario Desconectado';
    }
  }

  /**
   * Método que solicita al WS el listado de empresas resumido.
   *
   * @param  string $urlCountry
   *
   */
  private function callWSListaEmpresas($urlCountry)
  {

    $this->lang->load('erroreseol');

    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');

    $idOperation = "getListaEmpresas";
    $classname = "com.novo.objects.TOs.UsuarioTO";
    $canal = "ceo";
    $modulo = "listaEmpresasConfig";
    $funcion = "listaEmpresasConfig";
    $operation = "listarEmpresasConfig";

    $ip = $this->input->ip_address();
    $timeLog = date("m/d/Y H:i");

    $sessionId = $this->session->userdata('sessionId');
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $funcion, $operation, 0, $ip, $timeLog);

    $data = array(
      "pais" => $urlCountry,
      "idOperation" => $idOperation,
      "className" => $classname,
      "idUsuario" => $username,
      "logAccesoObject" => $logAcceso,
      "token" => $token
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSListaEmpresas');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data); // ENVÍA LA PETICIÓN Y ALMACENA LA RESPUESTA EN $response
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSListaEmpresas');
    $response = json_decode($jsonResponse);

    if ($response) {

      log_message('info', 'empr user ' . $response->rc);

      if ($response->rc == 0) {
        return $response;
      } else {

        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          $codigoError = array('ERROR' => '-29', 'msg' => lang('ERROR_(-29)'));
        } else {

          $codigoError = lang('ERROR_(' . $response->rc . ')'); // OBTENER LA DESCRIPCION DEL ERROR (DE LA PLANTILLA DE LENGUAJE)
          if (strpos($codigoError, 'Error') !== false) {        // VERIFICAR SI ES UN ERROR QUE SE PUEDE MOSTRAR
            $codigoError =  lang('ERROR_GENERICO_USER'); // ERROR GENERICO
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'));
          }
        }
        return $codigoError;
      }
    } else {
      log_message('info', 'empr user NO WS');
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }

  /**
   * Método que rotorna info de la inpresa para un accodcia dado.
   *
   * @param  string $urlCountry
   * @return JSON
   */
  public function getInfoEmpresaUser($urlCountry)
  {
    if (!$this->input->is_ajax_request()) {
      redirect(base_url($urlCountry . '/dashboard'), 'location');
      exit();
    }

    //cargar archivo de configuración del país
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
      $acodcia = $dataRequest->data_accodcia;
      $lista = $this->callWSInfoEmpresa($urlCountry, $acodcia);
      $response = $this->cryptography->encrypt($lista);
      $this->output->set_content_type('application/json')->set_output(json_encode($response));
    } elseif ($paisS != $urlCountry && $paisS != '') {

      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      $response = $this->cryptography->encrypt(array('ERROR' => '-29'));
      $this->output->set_content_type('application/json')->set_output(json_encode($response));
    } else {
      redirect($urlCountry . '/login');
    }
  }

  /**
   * Método que solicita al WS la información de una empresa dado su accodcia.
   *
   * @param  string $urlCountry
   * @param  string $acodcia
   * @return JSON
   */
  private function callWSInfoEmpresa($urlCountry, $acodcia)
  {

    $this->lang->load('erroreseol');

    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');

    $idOperation = "getEmpresaXUsuario";
    $classname = "com.novo.objects.MO.ListadoEmpresasMO";
    $canal = "ceo";
    $modulo = "infoEmpresaConfig";
    $funcion = "infoEmpresaConfig";
    $operation = "getInfoEmpresaConfig";

    $ip = $this->input->ip_address();
    $timeLog = date("m/d/Y H:i");

    $sessionId = $this->session->userdata('sessionId');
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $funcion, $operation, 0, $ip, $timeLog);

    $data = array(
      "pais" => $urlCountry,
      "idOperation" => $idOperation,
      "className" => $classname,
      "accodusuario" => $username,
      "acCodCia" => $acodcia,
      "logAccesoObject" => $logAcceso,
      "token" => $token
    );


    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSInfoEmpresa');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);  // ENVÍA LA PETICIÓN Y ALMACENA LA RESPUESTA EN $response
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSInfoEmpresa');
    log_message('debug', 'RESPONSE callWSInfoEmpresa: ' . $jsonResponse);
    $response = json_decode($jsonResponse);

    if ($response) {
      if ($response->rc == 0) {
        if ($urlCountry == 'Ec-bp') {
          $actel = maskString($response->lista[0]->actel, 2, 2);
          $response->lista[0]->actel = $actel;
          $actel2 = maskString($response->lista[0]->actel2, 2, 2);
          $response->lista[0]->actel2 = $actel2;
          $actel3 = maskString($response->lista[0]->actel3, 2, 2);
          $response->lista[0]->actel3 = $actel3;
        }
        return $response;
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          $codigoError = array('ERROR' => '-29');
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')'); // OBTENER LA DESCRIPCION DEL ERROR (DE LA PLANTILLA DE LENGUAJE)
          if (strpos($codigoError, 'Error') !== false) {        // VERIFICAR SI ES UN ERROR QUE SE PUEDE MOSTRAR
            $codigoError =  lang('ERROR_GENERICO_USER'); // ERROR GENERICO
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'));
          }
        }
        return $codigoError;
      }
    } else {
      log_message('info', 'info empr user NO WS');
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }

  /**
   * Método para solicitar los contactos de determinada empresa.
   *
   * @param  string $urlCountry
   * @return JSON
   */
  public function getContactoEmpresa($urlCountry)
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
      $acrif = $dataRequest->data_rif;
      $paginaActual = $dataRequest->paginaActual;
      $paginar = $dataRequest->data_paginar;
      $cantItems = $dataRequest->data_cantItems;

      $lista = $this->callWSContactoEmpresa($urlCountry, $acrif, $paginaActual, $paginar, $cantItems);
      $response = $this->cryptography->encrypt($lista);
      $this->output->set_content_type('application/json')->set_output(json_encode($response));
    } elseif ($paisS != $urlCountry && $paisS != '') {

      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      $response = $this->cryptography->encrypt(array(array('ERROR' => lang('ERROR_(-29)'), "rc" => '-29')));
      $this->output->set_content_type('application/json')->set_output(json_encode($response));
    } else {
      redirect($urlCountry . '/login');
    }
  }

  /**
   * Método que llama al WS para obtener los contactos de un empresa.
   *
   * @param  string $urlCountry
   * @param  string $acrif
   * @param  string $paginaActual
   * @param  string $paginar
   * @param  string $cantItems
   * @return JSON
   */
  private function callWSContactoEmpresa($urlCountry, $acrif, $paginaActual, $paginar, $cantItems)
  {

    $this->lang->load('erroreseol');

    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');
    $sessionId = $this->session->userdata('sessionId');

    $idOperation = "getContactosPorEmpresa";
    $classname = "com.novo.objects.MO.ListadoContactosMO";
    $canal = "ceo";
    $modulo = "getContactosPorEmpresa";
    $funcion = "getContactosPorEmpresa";
    $operation = "getContactosPorEmpresa";

    $ip = $this->input->ip_address();
    $timeLog = date("m/d/Y H:i");

    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $funcion, $operation, 0, $ip, $timeLog);

    $data = array(
      "pais" => $urlCountry,
      "idOperation" => $idOperation,
      "className" => $classname,
      "lista" => array(array("acrif" => $acrif)),
      "paginaActual" => $paginaActual,
      "tamanoPagina" => $cantItems,
      "paginar" => $paginar,
      "logAccesoObject" => $logAcceso,
      "token" => $token
    );


    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSContactoEmpresa');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSContactoEmpresa');
    $response = json_decode($jsonResponse);

    if ($response) {

      log_message('info', 'lista contactos ' . $response->rc . '/' . $response->msg);
      if ($response->rc == 0) {
        return $response;
      } else {

        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          $codigoError = array('ERROR' => lang('ERROR_(-29)'), "rc" => $response->rc);
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')');
          if (strpos($codigoError, 'Error') !== false) {
            $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'), "rc" => $response->rc);
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'), "rc" => $response->rc);
          }
        }
        return $codigoError;
      }
    } else {
      log_message('info', ' contactos NO WS');
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }

  /**
   * Método recibe los parametros para actualizar datos de la empresa.
   *
   * @param  string $urlCountry
   * @return JSON
   */
  public function getActualizarTlfEmpresa($urlCountry)
  {

    np_hoplite_countryCheck($urlCountry);

    $logged_in = $this->session->userdata('logged_in');
    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in) {

      $acrif = $this->input->post('rif');
      $tlf = $this->input->post('tlf');
      $tlf2 = $this->input->post('tlf2');
      $tlf3 = $this->input->post('tlf3');

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
      $acrif = $dataRequest->rif;
      $tlf = $dataRequest->tlf;
      $tlf2 = $dataRequest->tlf2;
      $tlf3 = $dataRequest->tlf3;
      $lista = $this->callWSActualizarTlfEmpresa($urlCountry, $acrif, $tlf, $tlf2, $tlf3);
      $response = $this->cryptography->encrypt($lista);
      $this->output->set_content_type('application/json')->set_output(json_encode($response));
    } elseif ($paisS != $urlCountry && $paisS != '') {

      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      $this->output->set_content_type('application/json')->set_output(json_encode(array('ERROR' => lang('ERROR_(-29)'), "rc" => '-29')));
    } else {
      redirect($urlCountry . '/login');
    }
  }

  /**
   * Método que llama al WS para realizar la modificación de la info de la empresa.
   *
   * @param  string $urlCountry
   * @param  string $acrif
   * @param  string $tlf
   * @param  string $tlf2
   * @param  string $tlf3
   * @return JSON
   */
  private function callWSActualizarTlfEmpresa($urlCountry, $acrif, $tlf, $tlf2, $tlf3)
  {

    $this->lang->load('erroreseol');

    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');

    $idOperation = "getActualizarTLFEmpresa";
    $classname = "com.novo.objects.TOs.EmpresaTO";
    $canal = "ceo";
    $modulo = "getActualizarTLFEmpresa";
    $funcion = "getActualizarTLFEmpresa";
    $operation = "getActualizarTLFEmpresa";

    $ip = $this->input->ip_address();
    $timeLog = date("m/d/Y H:i");

    $sessionId = $this->session->userdata('sessionId');
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $funcion, $operation, 0, $ip, $timeLog);

    $data = array(
      "pais" => $urlCountry,
      "idOperation" => $idOperation,
      "className" => $classname,
      "acrif" => $acrif,
      "actel" => $tlf,
      "actel2" => $tlf2,
      "actel3" => $tlf3,
      "logAccesoObject" => $logAcceso,
      "token" => $token
    );


    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSActualizarTlfEmpresa');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSActualizarTlfEmpresa');
    $response = json_decode($jsonResponse);

    if ($response) {
      log_message('info', ' getActualizarTLFEmpresa ' . $response->rc . '/' . $response->msg);
      if ($response->rc == 0) {
        return $response;
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          $codigoError = array('ERROR' => lang('ERROR_(-29)'), "rc" => $response->rc);
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')');
          if (strpos($codigoError, 'Error') !== false) {
            $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'), "rc" => $response->rc);
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'), "rc" => $response->rc);
          }
        }
        return $codigoError;
      }
    } else {
      log_message('info', ' getActualizarTLFEmpresa no ws');
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }

  /**
   * Método para agregar contacto a la empresa.
   *
   * @param  string $urlCountry
   * @return JSON
   */
  public function getAgregarContactoEmpresa($urlCountry)
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
      $acrif = $dataRequest->rif;
      $cedula = $dataRequest->cedula;
      $nombre = $dataRequest->nombre;
      $apellido = $dataRequest->apellido;
      $cargo = $dataRequest->cargo;
      $email = $dataRequest->email;
      $tipoContacto = $dataRequest->tipoContacto;
      $pass = $dataRequest->pass;

      $lista = $this->callWSAgregarContactoEmpresa($urlCountry, $acrif, $cedula, $nombre, $apellido, $cargo, $email, $tipoContacto, $pass);
      $response = $this->cryptography->encrypt($lista);
      $this->output->set_content_type('application/json')->set_output(json_encode($response));
    } elseif ($paisS != $urlCountry && $paisS != '') {

      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      $response = $this->cryptography->encrypt(array('ERROR' => lang('ERROR_(-29)'), "rc" => '-29'));
      $this->output->set_content_type('application/json')->set_output(json_encode($response));
    } else {
      redirect($urlCountry . '/login');
    }
  }

  /**
   * Método que realiza petición al WS para crear el contacto de la empresa.
   *
   * @param  string $urlCountry
   * @param  string $acrif
   * @param  string $cedula
   * @param  string $nombre
   * @param  string $apellido
   * @param  string $cargo
   * @param  string $email
   * @param  string $tipoContacto
   * @param  string $pass
   * @return JSON
   */
  private function callWSAgregarContactoEmpresa($urlCountry, $acrif, $cedula, $nombre, $apellido, $cargo, $email, $tipoContacto, $pass)
  {

    $this->lang->load('erroreseol');

    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');

    $idOperation = "insertarContactoEmpresa";
    $classname = "com.novo.objects.TOs.ContactoTO";
    $canal = "ceo";
    $modulo = "insertarContactoEmpresa";
    $funcion = "insertarContactoEmpresa";
    $operation = "insertarContactoEmpresa";

    $ip = $this->input->ip_address();
    $timeLog = date("m/d/Y H:i");

    $sessionId = $this->session->userdata('sessionId');
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $funcion, $operation, 0, $ip, $timeLog);

    $data = array(
      "pais" => $urlCountry,
      "idOperation" => $idOperation,
      "className" => $classname,
      "acrif" => $acrif,
      "idExtPer" => $cedula,
      "nombres" => $nombre,
      "apellido" => $apellido,
      "cargo" => $cargo,
      "email" => $email,
      "tipoContacto" => $tipoContacto,
      "usuario" => array(
        "userName" => $username,
        "password" => $pass
      ),
      "logAccesoObject" => $logAcceso,
      "token" => $token
    );


    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSAgregarContactoEmpresa');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSAgregarContactoEmpresa');
    $response = json_decode($jsonResponse);

    if ($response) {
      log_message('info', 'add contact ' . $response->rc);
      if ($response->rc == 0) {
        return $response;
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          $codigoError = array('ERROR' => lang('ERROR_(-29)'), "rc" => $response->rc);
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')');
          if (strpos($codigoError, 'Error') !== false) {
            $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'), "rc" => $response->rc);
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'), "rc" => $response->rc);
          }
        }
        return $codigoError;
      }
    } else {
      log_message('info', 'add contact NO WS');
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }

  /**
   * Método para eliminar contacto de la empresa.
   *
   * @param  string $urlCountry
   * @return JSON
   */
  public function getEliminarContactoEmpresa($urlCountry)
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
      $acrif = $dataRequest->rif;
      $cedula = $dataRequest->cedula;
      $pass = $dataRequest->pass;

      $lista = $this->callWSEliminarContactoEmpresa($urlCountry, $acrif, $cedula, $pass);
      $response = $this->cryptography->encrypt($lista);
      $this->output->set_content_type('application/json')->set_output(json_encode($response));
    } elseif ($paisS != $urlCountry && $paisS != '') {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      $this->output->set_content_type('application/json')->set_output(json_encode(array('ERROR' => lang('ERROR_(-29)'), "rc" => '-29')));
    } else {
      redirect($urlCountry . '/login');
    }
  }

  /**
   * Método que realiza petición al WS para eliminar el contacto de la empresa.
   *
   * @param  string $urlCountry
   * @param  string $acrif
   * @param  string $cedula
   * @param  string $pass
   * @return JSON
   */
  private function callWSEliminarContactoEmpresa($urlCountry, $acrif, $cedula, $pass)
  {

    $this->lang->load('erroreseol');

    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');

    $idOperation = "eliminarContactoEmpresa";
    $classname = "com.novo.objects.TOs.ContactoTO";
    $canal = "ceo";
    $modulo = "insertarContactoEmpresa";
    $funcion = "insertarContactoEmpresa";
    $operation = "insertarContactoEmpresa";

    $ip = $this->input->ip_address();
    $timeLog = date("m/d/Y H:i");

    $sessionId = $this->session->userdata('sessionId');
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $funcion, $operation, 0, $ip, $timeLog);

    $data = array(
      "pais" => $urlCountry,
      "idOperation" => $idOperation,
      "className" => $classname,
      "acrif" => $acrif,
      "idExtPer" => $cedula,
      "usuario" => array(
        "userName" => $username,
        "password" => $pass
      ),
      "logAccesoObject" => $logAcceso,
      "token" => $token
    );


    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSEliminarContactoEmpresa');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSEliminarContactoEmpresa');
    $response = json_decode($jsonResponse);

    if ($response) {
      log_message('info', ' eliminarContactoEmpresa ' . $response->rc . '/' . $response->msg);
      if ($response->rc == 0) {
        return $response;
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          $codigoError = array('ERROR' => lang('ERROR_(-29)'), "rc" => $response->rc);
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')');
          if (strpos($codigoError, 'Error') !== false) {
            $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'), "rc" => $response->rc);
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'), "rc" => $response->rc);
          }
        }
        return $codigoError;
      }
    } else {
      log_message('info', ' eliminarContactoEmpresa NO WS ');
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }

  /**
   * Método para actualizar los datos del contacto de la empresa.
   *
   * @param  string $urlCountry
   * @return JSON
   */
  public function getActualizarContactoEmpresa($urlCountry)
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

      $acrif = $dataRequest->rif;
      $cedula = $dataRequest->cedula;
      $nombre = $dataRequest->nombre;
      $apellido = $dataRequest->apellido;
      $cargo = $dataRequest->cargo;
      $email = $dataRequest->email;
      $tipoContacto = $dataRequest->tipoContacto;
      $pass = $dataRequest->pass;

      $lista = $this->callWSActualizarContactoEmpresa($urlCountry, $acrif, $cedula, $nombre, $apellido, $cargo, $email, $tipoContacto, $pass);
      $response = $this->cryptography->encrypt($lista);
      $this->output->set_content_type('application/json')->set_output(json_encode($response));
    } elseif ($paisS != $urlCountry && $paisS != '') {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      $this->output->set_content_type('application/json')->set_output(json_encode(array('ERROR' => lang('ERROR_(-29)'), "rc" => '-29')));
    } else {
      redirect($urlCountry . '/login');
    }
  }

  /**
   * Método que realiza petición al WS para actualizar
   * los datos del contacto de la empresa.
   *
   * @param  string $urlCountry
   * @param  string $acrif
   * @param  string $cedula
   * @param  string $pass
   * @return JSON
   */
  private function callWSActualizarContactoEmpresa($urlCountry, $acrif, $cedula, $nombre, $apellido, $cargo, $email, $tipoContacto, $pass)
  {

    $this->lang->load('erroreseol');

    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');

    $idOperation = "updateContactoEmpresa";
    $classname = "com.novo.objects.TOs.ContactoTO";
    $canal = "ceo";
    $modulo = "updateContactoEmpresa";
    $funcion = "updateContactoEmpresa";
    $operation = "updateContactoEmpresa";

    $ip = $this->input->ip_address();
    $timeLog = date("m/d/Y H:i");

    $sessionId = $this->session->userdata('sessionId');
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $funcion, $operation, 0, $ip, $timeLog);

    $data = array(
      "pais" => $urlCountry,
      "idOperation" => $idOperation,
      "className" => $classname,
      "nombres" => $nombre,
      "apellido" => $apellido,
      "cargo" => $cargo,
      "email" => $email,
      "tipoContacto" => $tipoContacto,
      "acrif" => $acrif,
      "idExtPer" => $cedula,
      "usuario" => array(
        "userName" => $username,
        "password" => $pass
      ),
      "logAccesoObject" => $logAcceso,
      "token" => $token
    );


    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSActualizarContactoEmpresa');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSActualizarContactoEmpresa');
    $response = json_decode($jsonResponse);

    if ($response) {
      log_message('info', ' ActualizarContactoEmpresa ' . $response->rc . '/' . $response->msg);
      if ($response->rc == 0) {
        return $response;
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          $codigoError = array('ERROR' => lang('ERROR_(-29)'), "rc" => $response->rc);
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')');
          if (strpos($codigoError, 'Error') !== false) {
            $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'), "rc" => $response->rc);
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'), "rc" => $response->rc);
          }
        }
        return $codigoError;
      }
    } else {
      log_message('info', ' ActualizarContactoEmpresa NO WS ');
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }

  /**
   * Método para obtener los datos del usuario conectado.
   *
   * @param  string $urlCountry
   * @return JSON
   */
  public function getPerfilUser($urlCountry)
  {
    if (!$this->input->is_ajax_request()) {
      redirect(base_url($urlCountry . '/dashboard'), 'location');
      exit();
    }

    np_hoplite_countryCheck($urlCountry);

    $logged_in = $this->session->userdata('logged_in');
    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in) {

      $lista = $this->callWSPerfilUser($urlCountry);
      $response = $this->cryptography->encrypt($lista);
      $this->output->set_content_type('application/json')->set_output(json_encode($response));
    } elseif ($paisS != $urlCountry && $paisS != '') {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      $response = $this->cryptography->encrypt(array('ERROR' => lang('ERROR_(-29)'), "rc" => '-29'));
      $this->output->set_content_type('application/json')->set_output(json_encode($response));
    } else {
      redirect($urlCountry . '/login');
    }
  }

  /**
   * Método que realiza petición al WS para
   * obtener los datos del usuario conectado.
   *
   * @param  string $urlCountry
   * @param  string $acrif
   * @param  string $cedula
   * @param  string $pass
   * @return JSON
   */
  private function callWSPerfilUser($urlCountry)
  {

    $this->lang->load('erroreseol');

    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');

    $idOperation = "getPerfilUsuario";
    $classname = "com.novo.objects.TOs.UsuarioTO";
    $canal = "ceo";
    $modulo = "getPerfilUsuario";
    $funcion = "getPerfilUsuario";
    $operation = "getPerfilUsuario";

    $ip = $this->input->ip_address();
    $timeLog = date("m/d/Y H:i");

    $sessionId = $this->session->userdata('sessionId');
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $funcion, $operation, 0, $ip, $timeLog);

    $data = array(
      "pais" => $urlCountry,
      "idOperation" => $idOperation,
      "className" => $classname,
      "idUsuario" => $username,
      "logAccesoObject" => $logAcceso,
      "token" => $token
    );


    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSPerfilUser');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSPerfilUser');
    $response = json_decode($jsonResponse);

    if ($response) {
      log_message('info', ' PERFIL USER ' . $response->rc . '/' . $response->msg);
      if ($response->rc == 0) {
        if ($urlCountry == 'Ec-bp') {
          $email = maskString($response->email, 4, 8, '@');
          $response->email = $email;
        }
        return $response;
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          $codigoError = array('ERROR' => lang('ERROR_(-29)'), "rc" => $response->rc);
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')');
          if (strpos($codigoError, 'Error') !== false) {
            $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'), "rc" => $response->rc);
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'), "rc" => $response->rc);
          }
        }
        return $codigoError;
      }
    } else {
      log_message('info', ' PERFIL USER NO WS ');
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }

  /**
   * Método para actualizar los datos del usuario conectado.
   *
   * @param  string $urlCountry
   * @return JSON
   */
  public function getActualizarPerfilUser($urlCountry)
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
      $email = $dataRequest->email;
      $lista = $this->callWSActualizarPerfilUser($urlCountry, $email);
      $response = $this->cryptography->encrypt($lista);
      $this->output->set_content_type('application/json')->set_output(json_encode($response));
    } elseif ($paisS != $urlCountry && $paisS != '') {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      $response = $this->cryptography->encrypt(array('ERROR' => lang('ERROR_(-29)'), "rc" => '-29'));
      $this->output->set_content_type('application/json')->set_output(json_encode($response));
    } else {
      redirect($urlCountry . '/login');
    }
  }

  /**
   * Método que realiza petición al WS para
   * actualizar los datos del usuario conectado.
   *
   * @param  string $urlCountry
   * @param  string $email
   * @return JSON
   */
  private function callWSActualizarPerfilUser($urlCountry, $email)
  {

    $this->lang->load('erroreseol');

    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');

    $idOperation = "getActualizarUsuario";
    $classname = "com.novo.objects.TOs.UsuarioTO";
    $canal = "ceo";
    $modulo = "getActualizarUsuario";
    $funcion = "getActualizarUsuario";
    $operation = "getActualizarUsuario";

    $ip = $this->input->ip_address();
    $timeLog = date("m/d/Y H:i");

    $sessionId = $this->session->userdata('sessionId');
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $funcion, $operation, 0, $ip, $timeLog);

    $data = array(
      "pais" => $urlCountry,
      "idOperation" => $idOperation,
      "className" => $classname,
      "idUsuario" => $username,
      "email" => $email,
      "logAccesoObject" => $logAcceso,
      "token" => $token
    );


    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSActualizarPerfilUser');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSActualizarPerfilUser');
    $response = json_decode($jsonResponse);

    if ($response) {
      log_message('info', ' ACTUALIZAR PERFIL user ' . $response->rc . '/' . $response->msg);
      if ($response->rc == 0) {
        return $response;
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          $codigoError = array('ERROR' => lang('ERROR_(-29)'), "rc" => $response->rc);
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')');
          if (strpos($codigoError, 'Error') !== false) {
            $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'), "rc" => $response->rc);
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'), "rc" => $response->rc);
          }
        }
        return $codigoError;
      }
    } else {
      log_message('info', ' ACTUALIZAR PERFIL user NO WS');
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }

  /**
   * Método para obtener los datos de las sucursales asociadas a una empresa.
   *
   * @param  string $urlCountry
   * @return JSON
   */
  public function getConsultarSucursales($urlCountry)
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
      $rif = $dataRequest->rif;
      $paginaActual = $dataRequest->paginaActual;
      $cantItems = $dataRequest->data_cantItems;
      $paginar = $dataRequest->data_paginar;

      $lista = $this->callWSConsultarSucursales($urlCountry, $rif, $paginaActual, $cantItems, $paginar);
      $response = $this->cryptography->encrypt($lista);
      $this->output->set_content_type('application/json')->set_output(json_encode($response));
    } elseif ($paisS != $urlCountry && $paisS != '') {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      $response = $this->cryptography->encrypt(array('ERROR' => lang('ERROR_(-29)'), "rc" => '-29'));
      $this->output->set_content_type('application/json')->set_output(json_encode($response));
    } else {
      redirect($urlCountry . '/login');
    }
  }

  /**
   * Método que realiza petición al WS para obtener los datos de las sucursales de una empresa.
   *
   * @param  string $urlCountry
   * @param  string $rif
   * @param  string $paginaActual
   * @param  string $cantItems
   * @param  string $paginar
   * @return JSON
   */
  private function callWSConsultarSucursales($urlCountry, $rif, $paginaActual, $cantItems, $paginar)
  {

    $this->lang->load('erroreseol');

    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');

    $idOperation = "getConsultarSucursales";
    $classname = "com.novo.objects.MO.ListadoSucursalesMO";
    $canal = "ceo";
    $modulo = "getConsultarSucursales";
    $funcion = "getConsultarSucursales";
    $operation = "getConsultarSucursales";

    $ip = $this->input->ip_address();
    $timeLog = date("m/d/Y H:i");

    $sessionId = $this->session->userdata('sessionId');
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $funcion, $operation, 0, $ip, $timeLog);

    $data = array(
      "pais" => $urlCountry,
      "idOperation" => $idOperation,
      "className" => $classname,
      "lista" => array(array(
        "rif" => $rif
      )),
      "paginaActual" => $paginaActual,
      "tamanoPagina" => $cantItems,
      "paginar" => $paginar,
      "logAccesoObject" => $logAcceso,
      "token" => $token
    );


    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSConsultarSucursales');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSConsultarSucursales');
    $response = json_decode($jsonResponse);

    if ($response) {
      log_message('info', 'sucursales ' . $response->rc . '/' . $response->msg);

      if ($response->rc == 0) {
        return $response;
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          $codigoError = array('ERROR' => lang('ERROR_(-29)'), "rc" => $response->rc);
        } else if ($response->rc == -150) {
          $codigoError = array('ERROR' => lang('ERROR_(-150)'), "rc" => $response->rc, 'paisTo' => $response->paisTo);
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')');
          if (strpos($codigoError, 'Error') !== false) {
            $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'), "rc" => $response->rc);
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'), "rc" => $response->rc);
          }
        }
        return $codigoError;
      }
    } else {
      log_message('info', 'sucursales NO WS ');
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }

  /**
   * Método para añadir una sucursal a determinada empresa.
   *
   * @param  string $urlCountry
   * @return JSON
   */
  public function getAgregarSucursales($urlCountry)
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
      $rif = $dataRequest->rif;
      $nombre = $dataRequest->nombre;
      $codigo = $dataRequest->codigo;
      $dir1 = $dataRequest->dir1;
      $dir2 = $dataRequest->dir2;
      $dir3 = $dataRequest->dir3;
      $zona = $dataRequest->zona;
      $pais = $dataRequest->pais;
      $estado = $dataRequest->estado;
      $ciudad = $dataRequest->ciudad;
      $contacto = $dataRequest->contacto;
      $area = $dataRequest->area;
      $tlf = $dataRequest->tlf;
      $pass = $dataRequest->pass;

      $lista = $this->callWSAgregarSucursales($urlCountry, $pass, $rif, $nombre, $codigo, $dir1, $dir2, $dir3, $zona, $pais, $estado, $ciudad, $contacto, $area, $tlf);
      $response = $this->cryptography->encrypt($lista);
      $this->output->set_content_type('application/json')->set_output(json_encode($response));
    } elseif ($paisS != $urlCountry && $paisS != '') {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      $response = $this->cryptography->encrypt(array('ERROR' => lang('ERROR_(-29)'), "rc" => '-29'));
      $this->output->set_content_type('application/json')->set_output(json_encode($response));
    } else {
      redirect($urlCountry . '/login');
    }
  }

  /**
   * Método que realiza petición al WS para añadir una sucursal a determinada empresa.
   *
   * @param  string $urlCountry
   * @param  string $pass,$rif,$nombre,$codigo,$dir1,$dir2,$dir3,$zona,$pais,$estado,$ciudad,$contacto,$area,$tlf
   * @return JSON
   */
  private function callWSAgregarSucursales($urlCountry, $pass, $rif, $nombre, $codigo, $dir1, $dir2, $dir3, $zona, $pais, $estado, $ciudad, $contacto, $area, $tlf)
  {

    $this->lang->load('erroreseol');

    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');

    $idOperation = "getAgregarSucursales";
    $classname = "com.novo.objects.TOs.SucursalTO";
    $canal = "ceo";
    $modulo = "getAgregarSucursales";
    $funcion = "getAgregarSucursales";
    $operation = "getAgregarSucursales";

    $ip = $this->input->ip_address();
    $timeLog = date("m/d/Y H:i");

    $sessionId = $this->session->userdata('sessionId');
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $funcion, $operation, 0, $ip, $timeLog);

    $data = array(
      "pais" => $urlCountry,
      "idOperation" => $idOperation,
      "className" => $classname,
      "rif" => $rif,
      "codigo" => $codigo,
      "nomb_cia" => $nombre,
      "direccion_1" => $dir1,
      "direccion_2" => $dir2,
      "direccion_3" => $dir3,
      "zona" => $zona,
      "codPais" => $pais,
      "estado" => $estado,
      "ciudad" => $ciudad,
      "persona" => $contacto,
      "cod_area" => $area,
      "telefono" => $tlf,
      "costoDistribucion" => "0",
      "costoUnitDistribucion" => "0",
      "costoMinimo" => "0",
      "costoDistribRep" => "0",
      "usuario" => $username,
      "password" => $pass,
      "logAccesoObject" => $logAcceso,
      "token" => $token
    );


    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSAgregarSucursales');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSAgregarSucursales');
    $response = json_decode($jsonResponse);

    if ($response) {
      log_message('info', 'AGREGAR sucursales ' . $response->rc . '/' . $response->msg);
      if ($response->rc == 0) {
        return $response;
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          $codigoError = array('ERROR' => lang('ERROR_(-29)'), "rc" => $response->rc);
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')');
          if (strpos($codigoError, 'Error') !== false) {
            $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'), "rc" => $response->rc);
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'), "rc" => $response->rc);
          }
        }
        return $codigoError;
      }
    } else {
      log_message('info', 'AGREGAR sucursales NO WS');
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }

  /**
   * Método para actualizar los datos de una sucursal.
   *
   * @param  string $urlCountry
   * @return JSON
   */
  public function getActualizarSucursales($urlCountry)
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

      $rif = $dataRequest->rif;
      $nombre = $dataRequest->nombre;
      $codigo = $dataRequest->codigo;
      $dir1 = $dataRequest->dir1;
      $dir2 = $dataRequest->dir2;
      $dir3 = $dataRequest->dir3;
      $zona = $dataRequest->zona;
      $pais = $dataRequest->pais;
      $estado = $dataRequest->estado;
      $ciudad = $dataRequest->ciudad;
      $contacto = $dataRequest->contacto;
      $area = $dataRequest->area;
      $tlf = $dataRequest->tlf;
      $pass = $dataRequest->pass;

      $lista = $this->callWSActualizarSucursales($urlCountry, $pass, $rif, $nombre, $codigo, $dir1, $dir2, $dir3, $zona, $pais, $estado, $ciudad, $contacto, $area, $tlf);
      $response = $this->cryptography->encrypt($lista);
      $this->output->set_content_type('application/json')->set_output(json_encode($response, JSON_UNESCAPED_UNICODE));
    } elseif ($paisS != $urlCountry && $paisS != '') {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      $this->output->set_content_type('application/json')->set_output(json_encode(array('ERROR' => lang('ERROR_(-29)'), "rc" => '-29')));
    } else {
      redirect($urlCountry . '/login');
    }
  }

  /**
   * Método que realiza petición al WS para actualizar los datos de una sucursal.
   *
   * @param  string $urlCountry
   * @param  string $$pass, $rif, $nombre, $codigo, $dir1, $dir2, $dir3, $zona, $pais, $estado, $ciudad, $contacto, $area, $tlf
   * @return JSON
   */
  private function callWSActualizarSucursales($urlCountry, $pass, $rif, $nombre, $codigo, $dir1, $dir2, $dir3, $zona, $pais, $estado, $ciudad, $contacto, $area, $tlf)
  {

    $this->lang->load('erroreseol');

    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');

    $idOperation = "getActualizarSucursal";
    $classname = "com.novo.objects.TOs.SucursalTO";
    $canal = "ceo";
    $modulo = "getActualizarSucursal";
    $funcion = "getActualizarSucursal";
    $operation = "getActualizarSucursal";

    $ip = $this->input->ip_address();
    $timeLog = date("m/d/Y H:i");

    $sessionId = $this->session->userdata('sessionId');
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $funcion, $operation, 0, $ip, $timeLog);

    $data = array(
      "pais" => $urlCountry,
      "idOperation" => $idOperation,
      "className" => $classname,
      "rif" => $rif,
      "cod" => $codigo,
      "nomb_cia" => $nombre,
      "direccion_1" => $dir1,
      "direccion_2" => $dir2,
      "direccion_3" => $dir3,
      "zona" => $zona,
      "CodPais" => $pais,
      "estado" => $estado,
      "ciudad" => $ciudad,
      "persona" => $contacto,
      "cod_area" => $area,
      "telefono" => $tlf,
      "usuario" => $username,
      "password" => $pass,
      "logAccesoObject" => $logAcceso,
      "token" => $token
    );


    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSActualizarSucursales');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSActualizarSucursales');
    $response = json_decode($jsonResponse);

    if ($response) {
      log_message('info', 'ACTUALIZAR sucursales ' . $response->rc);
      if ($response->rc == 0) {
        return $response;
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          $codigoError = array('ERROR' => lang('ERROR_(-29)'), "rc" => $response->rc);
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')');
          if (strpos($codigoError, 'Error') !== false) {
            $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'), "rc" => $response->rc);
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'), "rc" => $response->rc);
          }
        }
        return $codigoError;
      }
    } else {
      log_message('info', 'ACTUALIZAR sucursales NO WS');
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }

  /**
   * Método para cargar archivo masivo de sucursales para determinada empresa.
   *
   * @param  string $urlCountry
   * @return JSON
   */
  public function cargarSucursales($urlCountry)
  {

    np_hoplite_countryCheck($urlCountry);

    $logged_in = $this->session->userdata('logged_in');
    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in) {

      $this->load->library('parser');
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
        $error = ['ERROR' => 'Falla Al mover archivo.'];
        $response = $this->cryptography->encrypt($error);
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
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

        curl_setopt($ch, CURLOPT_URL, $URL_TEMPLOTES . $nombreArchivoNuevo);
        curl_setopt($ch, CURLOPT_USERPWD, $LOTES_USERPASS);
        curl_setopt($ch, CURLOPT_UPLOAD, 1);
        curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_SFTP);
        curl_setopt($ch, CURLOPT_INFILE, $fp);
        curl_setopt($ch, CURLOPT_INFILESIZE, filesize($localfile));
        curl_exec($ch);
        $error_no = curl_errno($ch);
        log_message('ERROR', "subiendo archivo sftp " . $error_no . "/" . lang("SFTP(" . $error_no . ")"));
        curl_close($ch);
        if ($error_no == 0) {
          unlink("$localfile"); //BORRAR ARCHIVO
          $error = 'Archivo Movido.';

          //COLOCAR LLAMADO DE LA FUNCION CUANDO ESTE CORRECTO
          $formatoArchivo = substr($extensionArchivo, 1);
          $username = $this->session->userdata('userName');
          $token = $this->session->userdata('token');

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
          $idEmpresa = $dataRequest->data_rif;
          $cargaSuc = $this->callWScargarSucursales($urlCountry, $formatoArchivo, $nombreArchivo, $nombreArchivoNuevo, $idEmpresa);
          $response = $this->cryptography->encrypt($cargaSuc);
          $this->output->set_content_type('application/json')->set_output(json_encode($response));
        } else {
          $error = ['ERROR' => 'Falla Al mover archivo.'];
          $response = $this->cryptography->encrypt($error);
          $this->output->set_content_type('application/json')->set_output(json_encode($response));
        }
      }
    } elseif ($paisS != $urlCountry && $paisS != '') {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      $response = $this->cryptography->encrypt(array('ERROR' => lang('ERROR_(-29)'), "rc" => '-29'));
      $this->output->set_content_type('application/json')->set_output(json_encode($response));
    } else {
      redirect($urlCountry . '/login');
    }
  }

  /**
   * Método que realiza petición al WS para cargar archivo masivo de sucursales
   *
   * @param  string $urlCountry
   * @param  string $formatoArchivo
   * @param  string $nombreArchivo
   * @param  string $nombreArchivoNuevo
   * @param  string $idEmpresa
   * @return JSON
   */
  private function callWScargarSucursales($urlCountry, $formatoArchivo, $nombreArchivo, $nombreArchivoNuevo, $idEmpresa)
  {
    $this->lang->load('erroreseol');

    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');

    $idOperation = "getSucursalTxt";
    $classname = "com.novo.objects.TOs.SucursalTO";
    $canal = "ceo";
    $modulo = "getSucursalTxt";
    $funcion = "getSucursalTxt";
    $operation = "getSucursalTxt";

    $ip = $this->input->ip_address();
    $timeLog = date("m/d/Y H:i");

    $sessionId = $this->session->userdata('sessionId');
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $funcion, $operation, 0, $ip, $timeLog);

    $data = array(
      "pais" => $urlCountry,
      "idOperation" => $idOperation,
      "className" => $classname,
      "rif" => $idEmpresa,
      "url" => $nombreArchivoNuevo,
      "idTipoLote" => "7",
      "usuario" => $username,
      "logAccesoObject" => $logAcceso,
      "token" => $token
    );


    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWScargarSucursales');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWScargarSucursales');
    $response = json_decode($jsonResponse);

    if ($response) {
      log_message('info', 'CARGAR MASIVO sucursales ' . $response->rc . '/' . $response->msg);

      if ($response->rc == 0 || $response->rc == -166) {
        return $response;
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          $codigoError = array('ERROR' => lang('ERROR_(-29)'), "rc" => $response->rc);
        } elseif ($response->rc == -167) {
          $codigoError = array('ERROR' => $response->errores, "rc" => $response->rc);
        } else {
          $codigoError = lang('ERROR_(' . $response->rc . ')');
          if (strpos($codigoError, 'Error') !== false) {
            $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'), "rc" => $response->rc);
          } else {
            $codigoError = array('ERROR' => lang('ERROR_(' . $response->rc . ')'), "rc" => $response->rc);
          }
        }
        return $codigoError;
      }
    } else {
      log_message('info', 'CARGAR MASIVO sucursales NO WS');
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }


  public function Notificaciones($urlCountry)
  {

    np_hoplite_countryCheck($urlCountry);

    $this->lang->load('erroreseol');
    $this->lang->load('users');
    $menuP = $this->session->userdata('menuArrayPorProducto');

    $logged_in = $this->session->userdata('logged_in');
    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in) {

      $acrif = $this->input->post("acrif");
      $response = $this->callWSNotificaciones($urlCountry, $acrif);

      log_message('info', ' ====>> ' . json_encode($response));

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

  private function callWSNotificaciones($urlCountry, $acrif)
  {

    $this->lang->load('erroreseol');
    $canal = "ceo";
    $modulo = "Reporte por Producto";
    $function = "Notificaciones Usuario";
    $operation = "buscarNotificacionesCeo";

    $className = "com.novo.objects.TOs.ContactoTO";
    $timeLog = date("m/d/Y H:i");
    $ip = $this->input->ip_address();
    $sessionId = $this->session->userdata('sessionId');
    $username = $this->session->userdata('userName');
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
    $token = $this->session->userdata('token');

    $data = array(
      "idOperation" => $operation,
      "className" => $className,
      "accodusuario" => $username,
      "acrif" => $acrif,
      "logAccesoObject" => $logAcceso,
      "token" => $token,
      "pais" => $urlCountry
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);
    log_message('info', 'Estatus Notificacion : ' . $data);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSNotificaciones');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSNotificaciones');
    $response =  json_decode($jsonResponse);
    $data1 = json_encode($response);

    if ($response) {
      log_message('info', 'Estatus Notificacion : ' . $response->rc . "/" . $response->msg);
      if ($response->rc == 0) {
        return $response;
      } else {
        if ($response->rc == -61 || $response->rc == -29) {

          $codigoError = array('mensaje' => lang('ERROR_(-29)'), "rc" => "-29");
          $this->session->sess_destroy();
          return $codigoError;
        } else {

          $codigoError = lang('ERROR_(' . $response->rc . ')');

          $codigoError = (strpos($codigoError, 'Error') !== false) ?
            array('mensaje' => lang('ERROR_GENERICO_USER'), "rc" => $response->rc) :
            array('mensaje' => lang('ERROR_(' . $response->rc . ')'), "rc" => $response->rc);

          return $codigoError;
        }
      }
    } else {
      log_message('info', 'Estatus Notificacion NO WS ');
      return $codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'));
    }
  }

  public function NotificacionesEnvio($urlCountry)
  {

    np_hoplite_countryCheck($urlCountry);

    $this->lang->load('erroreseol');
    $this->lang->load('users');
    $menuP = $this->session->userdata('menuArrayPorProducto');

    $logged_in = $this->session->userdata('logged_in');
    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in) {

      $notificaciones = $this->input->post("notificaciones");
      log_message('info', ' notificaciones ====>> ' . json_encode($notificaciones));
      $response = $this->callWSNotificacionesEnvio($urlCountry, $notificaciones);
      log_message('info', 'response ====>> ' . json_encode($response));
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
  private function callWSNotificacionesEnvio($urlCountry, $notificaciones)
  {

    $this->lang->load('erroreseol');
    $canal = "ceo";
    $modulo = "Notificaciones";
    $function = "Notificaciones Usuario";
    $operation = "actualizarNotificacionesCeo";

    $className = "com.novo.objects.MO.NotificacionMO";
    $timeLog = date("m/d/Y H:i");
    $ip = $this->input->ip_address();
    $sessionId = $this->session->userdata('sessionId');
    $username = $this->session->userdata('userName');
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
    $token = $this->session->userdata('token');

    $data = array(
      "idOperation" => $operation,
      "className" => $className,
      "accodusuario" => $username,
      "notificaciones" => $notificaciones,
      "logAccesoObject" => $logAcceso,
      "token" => $token,
      "pais" => $urlCountry
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);
    log_message('info', 'Estatus Notificacion : ' . $data);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSNotificacionesEnvio');
    $data = array('bean' => $dataEncry, 'pais' => $urlCountry);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSNotificacionesEnvio');
    $response =  json_decode($jsonResponse);
    $data1 = json_encode($response);

    if ($response) {
      if ($response->rc == 0) {
        return $response;
      } else {
        if ($response->rc == -61 || $response->rc == -29) {

          $codigoError = array('mensaje' => lang('ERROR_(-29)'), "rc" => "-29");
          $this->session->sess_destroy();
          return $codigoError;
        } else {

          $codigoError = lang('ERROR_(' . $response->rc . ')');

          $codigoError = (strpos($codigoError, 'Error') !== false) ?
            array('mensaje' => lang('ERROR_GENERICO_USER'), "rc" => $response->rc) :
            array('mensaje' => lang('ERROR_(' . $response->rc . ')'), "rc" => $response->rc);

          return $codigoError;
        }
      }
    } else {
      log_message('info', 'Estatus Notificacion NO WS ');
      return $codigoError = array('mensaje' => lang('ERROR_GENERICO_USER'));
    }
  }
}
