<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Clase Dashboard
 *
 * Esta clase realiza las operaciónes relacionadas a listado de empresas y empresa-producto
 *
 * @package    controllers
 * @author     Wilmer Rojas <rojaswilmer@gmail.com>
 * @author     Carla García <neiryerit@gmail.com>
 */
class Dashboard extends CI_Controller
{
  /**
   * Pantalla que muestra el listado de empresas asociadas al usuario (pantalla siguiente al login)
   * @param  string $urlCountry
   */
  public function index($urlCountry)
  {

    //VALIDATE COUNTRY
    np_hoplite_countryCheck($urlCountry);
    $this->lang->load('dashboard');
    $this->lang->load('sectorfinanciero');
    $this->lang->load('users');
    $this->lang->load('erroreseol');

    $this->load->library('parser');
    $logged_in = $this->session->userdata('logged_in');

    $menu = [
      'menuArrayPorProducto',
      'acrifS',
      'acnomciaS',
      'acrazonsocialS',
      'acdescS',
      'accodciaS',
      'accodgrupoeS',
      'idProductoS',
      'nombreProductoS',
      'marcaProductoS',
      'mesesVencimiento'
    ];
    $this->session->unset_userdata($menu);

    if ($this->session->userdata('cl_addr') != np_Hoplite_Encryption($_SERVER["REMOTE_ADDR"])) {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    }

    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in) {
      $nombreCompleto = $this->session->userdata('nombreCompleto');
      $lastSessionD = $this->session->userdata('lastSession');

      $titlePage = "Conexión Empresas Online - Dashboard";
      $FooterCustomJS = "";
      //INSTANCIA MENU HEADER
      $menuHeader = $this->parser->parse('widgets/widget-menuHeader', array(), TRUE);
      //INSTANCIA MENU FOOTER
      $menuFooter = $this->parser->parse('widgets/widget-menuFooter', array(), TRUE);
      $header = $this->parser->parse('layouts/layout-header', array('bodyclass' => 'full-width', 'menuHeaderActive' => TRUE, 'menuHeaderMainActive' => TRUE, 'menuHeader' => $menuHeader, 'titlePage' => $titlePage), TRUE);
      $FooterCustomInsertJS = ["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js", "jquery.balloon.min.js", "jquery.paginate.js", "jquery.isotope.min.js", "aes.min.js", "aes-json-format.min.js", "dashboard/dashboard.js", "header.js", "routes.js"];
      $footer = $this->parser->parse('layouts/layout-footer', array('menuFooterActive' => TRUE, 'menuFooter' => $menuFooter, 'FooterCustomInsertJSActive' => TRUE, 'FooterCustomInsertJS' => $FooterCustomInsertJS, 'FooterCustomJSActive' => TRUE, 'FooterCustomJS' => $FooterCustomJS), TRUE);

      $content = $this->parser->parse('dashboard/content-dashboard', array('titulo' => $nombreCompleto, 'lastSession' => $lastSessionD), TRUE);

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
    } elseif ($paisS != $urlCountry && $paisS != "") {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } else {
      redirect($urlCountry . '/login');
    }
  }


  /**
   * Método para obtener el listado de empresas asociadas a un usuario
   * @param  string $urlCountry
   * @return json
   */
  public function getListaEmpresasUsuariosJSON($urlCountry)
  {
    np_hoplite_countryCheck($urlCountry);
    $logged_in = $this->session->userdata('logged_in');
    $paisS = $this->session->userdata('pais');
    //VALIDAR QUE USUARIO ESTE LOGGEDIN
    if ($paisS == $urlCountry && $logged_in) {

      if ($this->input->post('request')) {


        $paginar = $this->input->post('data-paginar');
        $tamanoPagina = $this->input->post('data-tamanoPagina');
        $paginaActual = $this->input->post('data-paginaActual');
        $filtroEmpresas = $this->input->post('data-filtroEmpresas');
        $rTest = $this->callWSListaEmpresasUsuario($paginar, $paginaActual, $tamanoPagina, $urlCountry); // solicitud sin paginar (obtiene todas las empresas), el filtrado se realiza desde js

        //$rTest = $this->callWSListaEmpresasPaginar($paginar,$tamanoPagina,$paginaActual,$filtroEmpresas,$urlCountry); // solicitud paginada y con filtro de búsqueda

        $lista = $rTest;
      } else {
        $paginar = FALSE;
        $lista = $this->callWSListaEmpresasPaginar($paginar, $tamanoPagina = null, $paginaActual = null, $filtroEmpresas = null, $urlCountry);
      }
      $response = $this->cryptography->encrypt($lista);
      $this->output->set_content_type('application/json')->set_output(json_encode($response, JSON_UNESCAPED_UNICODE));
    } elseif ($paisS != $urlCountry && $paisS != "") {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      log_message('info', 'ajax call');
      $responseError = ['ERROR' => lang('ERROR_(-29)'), "rc" => "-29"];
      $responseError = $this->cryptography->encrypt($responseError);
      $this->output->set_content_type('application/json')->set_output(json_encode($responseError));
    } else {
      redirect($urlCountry . '/login');
    }
  }


  /**
   * Método que realiza petición al WS para obtener el listado de empresas por usuario
   * @param  string $paginar
   * @param  string $paginaActual
   * @param  string $tamanoPagina
   * @param  string $pais
   * @return array
   */
  private function callWSListaEmpresasUsuario($paginar, $paginaActual, $tamanoPagina, $pais)
  {
    $this->lang->load('erroreseol');
    $this->lang->load('dashboard');
    $canal = "ceo";
    $modulo = "login";
    $function = "login";
    $operation = "getPaginar";
    $className = "com.novo.objects.MO.ListadoEmpresasMO";
    $timeLog = date("m/d/Y H:i");
    $ip = $this->input->ip_address();
    $sessionId = $this->session->userdata('sessionId');
    $username = "TEBCART"; //$this->session->userdata('userName');
    $token = $this->session->userdata('token');
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $operation, 0, $ip, $timeLog);

    $data = array(
      "idOperation" => $operation,
      "className" => $className,
      "accodusuario" => $username,
      "paginaActual" => $paginaActual,
      "paginar" => $paginar,
      "tamanoPagina" => $tamanoPagina,
      "filtroEmpresas" => null,
      "logAccesoObject" => $logAcceso,
      "token" => $token,
      "pais" => $pais
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);
    $dataEncry = np_Hoplite_Encryption($data, 'callWSListaEmpresasUsuario');
    $data = array('bean' => $dataEncry, 'pais' => $pais);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSListaEmpresasUsuario');
    $response = json_decode($jsonResponse);

    if ($response) {
      log_message('info', 'dashb_empr ' . $response->rc);
      if ($response->rc == 0) {
        return $response;
      } else {

        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          return array('ERROR' => '-29');
        } elseif ($response->rc == -150) {
          $codigoError = array('ERROR' => lang('DASH150'));
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
      log_message('info', 'dashb_empr NO WS');
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }




  /**
   * Método para obtener el listado de empresas asociadas a un usuario
   * @param  string $urlCountry
   * @return json
   */
  public function getListaEmpresasJSON($urlCountry)
  {
    if (!$this->input->is_ajax_request()) {
      redirect(base_url($urlCountry . '/dashboard'), 'location');
      exit();
    }
    np_hoplite_countryCheck($urlCountry);
    $logged_in = $this->session->userdata('logged_in');
    $paisS = $this->session->userdata('pais');

    //VALIDAR QUE USUARIO ESTE LOGGEDIN
    if ($paisS == $urlCountry && $logged_in) {

      if ($this->input->post('request')) {

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
        $paginar = $dataRequest->data_paginar;
        $tamanoPagina = $dataRequest->data_tamanoPagina;
        $paginaActual = $dataRequest->data_paginaActual;
        $filtroEmpresas = $dataRequest->data_filtroEmpresas;
        $lista = $this->callWSListaEmpresas($paginar, $paginaActual, $tamanoPagina, $urlCountry);
      } else {
        $paginar = FALSE;
        $lista = $this->callWSListaEmpresasPaginar($paginar, $tamanoPagina = null, $paginaActual = null, $filtroEmpresas = null, $urlCountry);
      }
      $response = $this->cryptography->encrypt($lista);
      $this->output->set_content_type('application/json')->set_output(json_encode($response, JSON_UNESCAPED_UNICODE));
    } elseif ($paisS != $urlCountry && $paisS != "") {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      $response = $this->cryptography->encrypt(['ERROR' => '-29']);
      $this->output->set_content_type('application/json')->set_output(json_encode($response));
    } else {
      redirect($urlCountry . '/login');
    }
  }
  /**
   * Método para obtener los productos asociados a determinada empresa para un usuario dado
   * @param  string $urlCountry
   * @return json
   */
  public function getListaProductosJSON($urlCountry)
  {
    if (!$this->input->is_ajax_request()) {
      redirect(base_url($urlCountry . '/dashboard'), 'location');
      exit();
    }

    np_hoplite_countryCheck($urlCountry);
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
      $acrifPost = $dataRequest->acrif;
      if ($acrifPost) {

        //$acrifPost = $this->input->post('acrif');

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
        $acrifPost = $dataRequest->acrif;
        $responseMenuEmpresas = $this->callWSMenuEmpresa($acrifPost, $urlCountry, $ctipo = 'false');
        if (array_key_exists('ERROR', $responseMenuEmpresas)) {
          $productos = $responseMenuEmpresas;
        } else {
          $productos = $responseMenuEmpresas->productos;
        }
      } else {
        $productos = null;
      }
      $productos = $this->cryptography->encrypt($productos);
      $this->output->set_content_type('application/json')->set_output(json_encode($productos, JSON_UNESCAPED_UNICODE));
    } elseif ($paisS != $urlCountry && $paisS != '') {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      log_message('info', 'ajax call');
      $responseError = ['ERROR' => lang('ERROR_(-29)'), "rc" => "-29"];
      $responseError = $this->cryptography->encrypt($responseError);
      $this->output->set_content_type('application/json')->set_output(json_encode($responseError));
    } else {
      redirect($urlCountry . '/login');
    }
  }


  /**
   * Método para Consulta de Productos por Empresa para el Combo de Productos (Tarjeta Hambiente)
   * @param  string $urlCountry
   * @return json
   */
  public function callWSListaProductosUsuarioJSON($urlCountry)
  {

    np_hoplite_countryCheck($urlCountry);
    $logged_in = $this->session->userdata('logged_in');
    //VALIDAR QUE USUARIO ESTE LOGGEDIN
    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in) {
      if ($this->input->post('request')) {
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
        $acrifPost = $dataRequest->acrif;
        $responseMenuEmpresas = $this->callWSMenuEmpresaTarjetaHambiente($acrifPost, $urlCountry, $ctipo = 'false');
        if (array_key_exists('ERROR', $responseMenuEmpresas)) {
          $productos = $responseMenuEmpresas;
        } else {
          $productos = $responseMenuEmpresas->productos;
        }
      } else {
        $productos = null;
      }
      $productos = $this->cryptography->encrypt($productos);
      $this->output->set_content_type('application/json')->set_output(json_encode($productos, JSON_UNESCAPED_UNICODE));
    } elseif ($paisS != $urlCountry && $paisS != '') {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } elseif ($this->input->is_ajax_request()) {
      log_message('info', 'ajax call');
      $responseError = ['ERROR' => lang('ERROR_(-29)'), "rc" => "-29"];
      $responseError = $this->cryptography->encrypt($responseError);
      $this->output->set_content_type('application/json')->set_output(json_encode($responseError));
    } else {
      redirect($urlCountry . '/login');
    }
  }

  /**
   * Método para cambiar los valores de las variables almacenadas en sesión
   * cuando el usuario decide cambiar de empresa o producto desde el sidebar
   * @param  string $urlCountry
   * @return array
   */
  public function postCambiarEmpresaProducto($urlCountry)
  {

    np_hoplite_countryCheck($urlCountry);
    $logged_in = $this->session->userdata('logged_in');

    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in) {
      //VALIDAMOS QUE RECIBA EL POST

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
        $llamada = $dataRequest->llamada;
        $accodgrupoe = $dataRequest->data_accodgrupoe;
        $acrifPost = $dataRequest->data_acrif;
        $acnomciaPost = $dataRequest->data_acnomcia;
        $acrazonsocialPost = $dataRequest->data_acrazonsocial;
        $acdescPost = $dataRequest->data_acdesc;
        $accodciaPost = $dataRequest->data_accodcia;

        $_POST['group'] = $accodgrupoe;
        $_POST['fiscal-inf'] = $acrifPost;
        $_POST['name'] = $acnomciaPost;
        $_POST['business-name'] = $acrazonsocialPost;
        $_POST['description'] = $acdescPost;
        $_POST['code'] = $accodciaPost;

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '---');
        $result = $this->form_validation->run('enterprise');
        log_message('DEBUG', 'NOVO VALIDATION FORM enterprise: ' . json_encode($result));
        $respuesta = 0;

        unset(
          $_POST['group'],
          $_POST['fiscal-inf'],
          $_POST['name'],
          $_POST['business-name'],
          $_POST['description'],
          $_POST['code']
        );

        if ($llamada == 'soloEmpresa' && $result) {

          $newdata = array(
            'acrifS' => $acrifPost,
            'acnomciaS' => $acnomciaPost,
            'acrazonsocialS' => $acrazonsocialPost,
            'acdescS' => $acdescPost,
            'accodciaS' => $accodciaPost,
            'accodgrupoeS' => $accodgrupoe,
            'idProductoS' => " ",
            'nombreProductoS' => " ",
            'marcaProductoS' => " "
          );
          $this->session->set_userdata($newdata);

          $respuesta = 1;
        } elseif ($llamada == 'productos' && $result) {
          $this->form_validation->reset_validation();
          $idProductoPost = $dataRequest->data_idproducto;
          $nomProduc = $dataRequest->data_nomProd;
          $marcProduc = $dataRequest->data_marcProd;

          $_POST['idProductoPost'] = $idProductoPost;
          $_POST['nomProduc'] = $nomProduc;
          $_POST['marcProduc'] = $marcProduc;

          $result = $this->form_validation->run('products');
          log_message('DEBUG', 'NOVO VALIDATION FORM products: ' . json_encode($result));

          if ($result) {
            $newdata = array(
              'acrifS' => $acrifPost,
              'acnomciaS' => $acnomciaPost,
              'acrazonsocialS' => $acrazonsocialPost,
              'acdescS' => $acdescPost,
              'accodciaS' => $accodciaPost,
              'accodgrupoeS' => $accodgrupoe,
              'idProductoS' => $idProductoPost,
              'nombreProductoS' => $nomProduc,
              'marcaProductoS' => $marcProduc
            );
            $this->session->set_userdata($newdata);
            $respuesta = 1;
          } else {
            log_message('DEBUG', 'NOVO VALIDATION ERRORS: ' . json_encode(validation_errors()));
          }
        } else {
          log_message('DEBUG', 'NOVO VALIDATION ERRORS: ' . json_encode(validation_errors()));
        }
      }
      $response = $this->cryptography->encrypt($respuesta);
      $this->output->set_content_type('application/json')->set_output(json_encode($response, JSON_UNESCAPED_UNICODE));
    } elseif ($paisS != $urlCountry && $paisS != "") {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } else {
      redirect($urlCountry . '/login');
    }
  }


  /**
   * Pantalla que muestra listado de productos asociados para la relación empresa-usuario
   * @param  string $urlCountry
   */
  public function dashboardProductos($urlCountry)
  {

    //VALIDATE COUNTRY
    np_hoplite_countryCheck($urlCountry);
    $this->lang->load('dashboard');
    $this->lang->load('users');
    $this->load->library('parser');
    $this->lang->load('erroreseol');
    $logged_in = $this->session->userdata('logged_in');

    $menu = ['menuArrayPorProducto'];
    $this->session->unset_userdata($menu);

    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in) {
      if ($this->input->post()) {
        $acdescPost = $this->input->post('data-acdesc');
        $acrifPost = $this->input->post('data-acrif');
        $acnomciaPost = $this->input->post('data-acnomcia');
        $acrazonsocialPost = $this->input->post('data-acrazonsocial');
        $accodciaPost = $this->input->post('data-accodcia');
        $accodgrupoePost = $this->input->post('data-accodgrupoe');

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '---');
        $result = $this->form_validation->run('dash-products');
        log_message('DEBUG', 'NOVO VALIDATION FORM dash-products: ' . json_encode($result));

        if (!$result) {
          log_message('DEBUG', 'NOVO VALIDATION ERRORS: ' . json_encode(validation_errors()));
          redirect(base_url($urlCountry . '/dashboard'), 'location');
          exit();
        }

        $newdata = array(
          'acrifS' => $acrifPost,
          'acnomciaS' => $acnomciaPost,
          'acrazonsocialS' => $acrazonsocialPost,
          'acdescS' => $acdescPost,
          'accodciaS' => $accodciaPost,
          'accodgrupoeS' => $accodgrupoePost
        );
        $this->session->set_userdata($newdata);
      }

      $acrifS = $this->session->userdata('acrifS');

      if ($acrifS) {

        $responseMenuEmpresas = $this->callWSMenuEmpresa($acrifS, $urlCountry, $ctipo = 'A');
        $listaCat = null;
        $listaMarc = null;

        if (array_key_exists('productos', $responseMenuEmpresas)) {
          $productos = $responseMenuEmpresas->productos;
          $listaCat = $responseMenuEmpresas->listaCategorias;
          $listaMarc = $responseMenuEmpresas->listaMarcas;
        } else {
          $productos = $responseMenuEmpresas;
        }

        $titulo = "Selección del producto";
        $lastSessionD = $this->session->userdata('lastSession');
        $titlePage = "Conexión Empresas Online - Productos";
        $FooterCustomInsertJS = [];
        $FooterCustomJS = "";
        //INSTANCIA MENU HEADER
        $menuHeader = $this->parser->parse('widgets/widget-menuHeader', array(), TRUE);
        //INSTANCIA MENU FOOTER
        $menuFooter = $this->parser->parse('widgets/widget-menuFooter', array(), TRUE);

        $header = $this->parser->parse('layouts/layout-header', array('bodyclass' => '', 'menuHeaderActive' => TRUE, 'menuHeaderMainActive' => TRUE, 'menuHeader' => $menuHeader, 'titlePage' => $titlePage), TRUE);
        $FooterCustomInsertJS = ["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js", "jquery.isotope.min.js", "jquery.balloon.min.js", "aes.min.js", "aes-json-format.min.js", "dashboard/productos.js", "header.js", "routes.js"];
        $footer = $this->parser->parse('layouts/layout-footer', array('menuFooterActive' => TRUE, 'menuFooter' => $menuFooter, 'FooterCustomInsertJSActive' => TRUE, 'FooterCustomInsertJS' => $FooterCustomInsertJS, 'FooterCustomJSActive' => TRUE, 'FooterCustomJS' => $FooterCustomJS), TRUE);
        $content = $this->parser->parse('dashboard/content-productos', array(
          'titulo' => $titulo,
          'breadcrum' => '',
          'productos' => $productos,
          'listaCategorias' => $listaCat,
          'listaMarcas' => $listaMarc,
          'lastSession' => $lastSessionD
        ), TRUE);

        $sidebarEmpresa = $this->parser->parse('dashboard/widget-empresa', array('sidebarActive' => TRUE), TRUE);

        $datos = array(
          'header' => $header,
          'content' => $content,
          'footer' => $footer,
          'sidebar' => $sidebarEmpresa,
          'content' => $content,
          'titleHeading' => 'TITULO ACA',
          'login' => 'LOGIN USUARIO',
          'password' => 'CONTRASEÑA',
          'loginBtn' => 'ENTRAR',
        );

        $this->parser->parse('layouts/layout-b', $datos);
      } else {
        redirect($urlCountry . '/login');
      }
    } elseif ($paisS != $urlCountry && $paisS != "") {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } else {
      redirect($urlCountry . '/login');
    }
  }

  /**
   * Pantalla que muestra los estadiscos del producto seleccionado y el menú con las funciones del usuario
   * @param  string $urlCountry
   */
  public function dashboardProductosDetalle($urlCountry)
  {
    //VALIDATE COUNTRY
    np_hoplite_countryCheck($urlCountry);
    $this->lang->load('dashboard');
    $this->lang->load('erroreseol');
    $this->lang->load('users');
    $this->load->library('parser');
    $logged_in = $this->session->userdata('logged_in');
    $acrifS = $this->session->userdata('acrifS');

    //SE VALIDA SI EL USUARIO ESTA LOGGEDIN
    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in) {
      //SE OBTIENEN LAS VARIABLES DE SESSION QUE QUIERO USAR
      $nombreCompleto = $this->session->userdata('nombreCompleto');
      $lastSessionD = $this->session->userdata('lastSession');

      $FooterCustomInsertJS = "";
      $FooterCustomJS = "";

      //SE VALIDA SI VIENE LA CONSULTA POR POST
      if ($this->input->post()) {
        //SE OBTIENEN VARIABLES DE POST
        $idProductoPost = $this->input->post('data-idproducto');
        $nombreProductoPost = $this->input->post('data-nombreProducto');
        $marcaProductoPost = $this->input->post('data-marcaProducto');

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '---');
        $result = $this->form_validation->run('products-detail');

        log_message('DEBUG', 'NOVO VALIDATION FORM products-detail: ' . json_encode($result));

        if (!$result) {
          log_message('DEBUG', 'NOVO VALIDATION ERRORS: ' . json_encode(validation_errors()));
          redirect(base_url($urlCountry . '/dashboard/productos'), 'location');
          exit();
        }

        $newdata = array(
          'idProductoS' => $idProductoPost,
          'nombreProductoS' => $nombreProductoPost,
          'marcaProductoS' => $marcaProductoPost
        );
        //SE INSERTAN LAS VARIABLES EN LA SESSION
        $this->session->set_userdata($newdata);
      }

      //SE OBTIENEN VARIABLES DE POST
      $idProducto = $this->session->userdata('idProductoS');
      $cid = $this->session->userdata('acrifS');
      $accodcia = $this->session->userdata('accodciaS');
      $codgrupoe = $this->session->userdata("accodgrupoeS");

      $responseMenuPorProducto = $this->callWSMenuPorProducto($idProducto, $cid, $accodcia, $codgrupoe, $urlCountry);

      if (!array_key_exists('ERROR', $responseMenuPorProducto)) {
        //PERMISOS Y OPCIONES DE MENU DISPONIBLES DE ACUERDO AL USUARIO Y PRODUCTO SERIALIZADO
        $OpcionesMenu = serialize($responseMenuPorProducto->lista);

        $menu = [
          'menuArrayPorProducto' => $OpcionesMenu,
          'user_access' => $responseMenuPorProducto->lista
        ];
        $this->session->set_userdata($menu);

        $estadisticas[] = $responseMenuPorProducto->estadistica;
        $nombreEmpresaT = $responseMenuPorProducto->estadistica->producto->descripcion;
        $mesesVencimiento = $responseMenuPorProducto->estadistica->producto->mesesVencimiento;
        $actualDate = date('Y-m');
        $newDate = strtotime('+' . $mesesVencimiento . ' month', strtotime($actualDate));
        $expireDate = date('m/Y', $newDate);

        $maxTarjetas = $responseMenuPorProducto->estadistica->producto->maxTarjetas;

        $expMax = [
          'mesesVencimiento' => $expireDate,
          'maxTarjetas' => $maxTarjetas
        ];
        $this->session->set_userdata($expMax);

        $responseMenuPorProducto->estadistica->producto->descripcion;
        $titlePage = "Conexión Empresas Online - " . $nombreEmpresaT;
        $msgError = FALSE;
      } else {
        /*	VACIAR MENU 	 */
        $menu = array(
          'menuArrayPorProducto' => null
        );
        $this->session->set_userdata($menu);

        $estadisticas = FALSE;

        $msgError = $responseMenuPorProducto['ERROR'];
        $titlePage = "Conexión Empresas Online - Productos Detalle";
      }

      $FooterCustomInsertJS = ["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js", "jquery.balloon.min.js", "aes.min.js", "aes-json-format.min.js", "dashboard/widget-empresa.js", "header.js", "routes.js"];
      //INSTANCIA MENU HEADER
      $menuHeader = $this->parser->parse('widgets/widget-menuHeader', array(), TRUE);
      //INSTANCIA MENU FOOTER
      $menuFooter = $this->parser->parse('widgets/widget-menuFooter', array(), TRUE);
      $header = $this->parser->parse('layouts/layout-header', array('bodyclass' => '', 'menuHeaderActive' => TRUE, 'menuHeaderMainActive' => TRUE, 'menuHeader' => $menuHeader, 'titlePage' => $titlePage), TRUE);
      $footer = $this->parser->parse('layouts/layout-footer', array('menuFooterActive' => TRUE, 'menuFooter' => $menuFooter, 'FooterCustomInsertJSActive' => TRUE, 'FooterCustomInsertJS' => $FooterCustomInsertJS, 'FooterCustomJSActive' => TRUE, 'FooterCustomJS' => $FooterCustomJS), TRUE);

      $content = $this->parser->parse('dashboard/content-detalleProducto', array(
        'lastSession' => $lastSessionD,
        'producto' => $estadisticas,
        'msgError' => $msgError
      ), TRUE);

      $sidebarEmpresa = $this->parser->parse('dashboard/widget-empresa', array('sidebarActive' => TRUE), TRUE);

      $datos = array(
        'header' => $header,
        'content' => $content,
        'footer' => $footer,
        'sidebar' => $sidebarEmpresa
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
   * Método que realiza petición al WS para obtener el listado de empresas sin filtrado
   * @param  string $paginar
   * @param  string $paginaActual
   * @param  string $tamanoPagina
   * @param  string $pais
   * @return array
   */
  private function callWSListaEmpresas($paginar, $paginaActual, $tamanoPagina, $pais)
  {
    $this->lang->load('erroreseol');
    $this->lang->load('dashboard');
    $canal = "ceo";
    $modulo = "login";
    $function = "login";
    $operation = "listaEmpresas";
    $className = "com.novo.objects.MO.ListadoEmpresasMO";
    $timeLog = date("m/d/Y H:i");
    $ip = $this->input->ip_address();
    $sessionId = $this->session->userdata('sessionId');
    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $operation, 0, $ip, $timeLog);

    $data = array(
      "idOperation" => $operation,
      "className" => $className,
      "accodusuario" => $username,
      "paginaActual" => $paginaActual,
      "paginar" => $paginar,
      "tamanoPagina" => $tamanoPagina,
      "logAccesoObject" => $logAcceso,
      "token" => $token,
      "pais" => $pais
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSListaEmpresas');
    $data = array('bean' => $dataEncry, 'pais' => $pais);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSListaEmpresas');
    $response = json_decode($jsonResponse);

    if ($response) {
      if ($response->rc == 0) {
        return $response;
      } else {

        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          return array('ERROR' => '-29');
        } elseif ($response->rc == -150) {
          $codigoError = array('ERROR' => lang('DASH150'));
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
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }

  /**
   * Método que realiza petición al WS para obtener el listado de empresas filtrado segun parámetro de busqueda
   * @param  string $paginar
   * @param  string $tamanoPagina
   * @param  string $paginaActual
   * @param  string $filtroEmpresas
   * @param  string $pais
   * @return array
   */
  private function callWSListaEmpresasPaginar($paginar, $tamanoPagina, $paginaActual, $filtroEmpresas, $pais)
  {
    $this->lang->load('erroreseol');
    $canal = "ceo";
    $modulo = "listaEmpresas";
    $function = "listarEmpreas";
    $operation = "getPaginar";
    $className = "com.novo.objects.MO.ListadoEmpresasMO";
    $timeLog = date("m/d/Y H:i");
    $ip = $this->input->ip_address();
    $sessionId = $this->session->userdata('sessionId');
    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');

    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, "getPaginar", 0, $ip, $timeLog);

    $data = array(
      "idOperation" => $operation,
      "className" => $className,
      "accodusuario" => $username,
      "paginaActual" => $paginaActual,
      "tamanoPagina" => $tamanoPagina,
      "paginar" => $paginar,
      "filtroEmpresas" => $filtroEmpresas,
      "logAccesoObject" => $logAcceso,
      "token" => $token,
      "pais" => $pais
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSListaEmpresasPaginar');
    $data = array('bean' => $dataEncry, 'pais' => $pais);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSListaEmpresasPaginar');

    $response = json_decode($jsonResponse);

    if ($response) {
      log_message('info', 'dash_empr_filt ' . $response->rc);
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
      log_message('info', 'dash_empr_filt NO WS');
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }
  /**
   * Método que realiza petición al WS para obtener la lista de productos asociados a una empresa-usuario
   * @param  string $rif
   * @param  string $pais
   * @return array
   */
  private function callWSMenuEmpresa($rif, $pais, $ctipo)
  {
    $this->lang->load('erroreseol');
    $this->lang->load('dashboard');
    $canal = "ceo";
    $modulo = "login";
    $function = "login";
    $operation = "menuEmpresa";
    $className = "com.novo.objects.TOs.UsuarioTO";
    $timeLog = date("m/d/Y H:i");
    $ip = $this->input->ip_address();
    $sessionId = $this->session->userdata('sessionId');
    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $operation, 0, $ip, $timeLog);

    $data = array(
      "idOperation" => $operation,
      "className" => $className,
      "userName" => $username,
      "ctipo" => $ctipo,
      "idEmpresa" => $rif,
      "logAccesoObject" => $logAcceso,
      "token" => $token,
      "pais" => $pais
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSMenuEmpresa');
    $data = array('bean' => $dataEncry, 'pais' => $pais);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSMenuEmpresa');
    $response = json_decode($jsonResponse);

    if ($response) {
      log_message("info", "productos " . $response->rc . '/' . $response->msg);
      log_message("info", "productos " . json_encode($response));

      if ($response->rc == 0) {
        return $response;
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          return array('ERROR' => '-29');
        } elseif ($response->rc == -138) {
          $codigoError = array('ERROR' => lang('PRODUCTOS-138') . ucwords(mb_strtolower($this->session->userdata('acnomciaS'))));
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
      log_message("info", "productos NO WS");
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }

  /**
   * Método que realiza petición al WS para obtener la lista de productos asociados a una empresa-usuario (reporte Tarjeta Hambiente)
   * @param  string $rif
   * @param  string $pais
   * @return array
   */
  private function callWSMenuEmpresaTarjetaHambiente($rif, $pais, $ctipo)
  {
    $this->lang->load('erroreseol');
    $this->lang->load('dashboard');
    $canal = "ceo";
    $modulo = "login";
    $function = "login";
    $operation = "menuEmpresa";
    $className = "com.novo.objects.TOs.UsuarioTO";
    $timeLog = date("m/d/Y H:i");
    $ip = $this->input->ip_address();
    $sessionId = $this->session->userdata('sessionId');
    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $operation, 0, $ip, $timeLog);

    $data = array(
      "idOperation" => $operation,
      "className" => $className,
      "userName" => $username,
      "ctipo" =>  $ctipo,
      "idEmpresa" => $rif,
      "logAccesoObject" => $logAcceso,
      "token" => $token,
      "pais" => $pais
    );

    $data = json_encode($data, JSON_UNESCAPED_UNICODE);
    $dataEncry = np_Hoplite_Encryption($data, 'callWSMenuEmpresaTarjetaHambiente');
    $data = array('bean' => $dataEncry, 'pais' => $pais);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSMenuEmpresaTarjetaHambiente');
    $response = json_decode($jsonResponse);

    if ($response) {
      log_message("info", "productos " . $response->rc . '/' . $response->msg);

      if ($response->rc == 0) {
        return $response;
      } else {
        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          return array('ERROR' => '-29');
        } elseif ($response->rc == -138) {
          $codigoError = array('ERROR' => lang('PRODUCTOS-138') . ucwords(mb_strtolower($this->session->userdata('acnomciaS'))));
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
      log_message("info", "productos NO WS");
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }


  /**
   * Método que realiza petición al WS para obtener el menú del usuario y sus funciones asociadas
   * y los estadisticos del producto para la empresa seleccionada.
   *
   * @param  string $prefijo
   * @param  string $rif
   * @param  string $acCodCia
   * @param  string $pais
   * @return array
   */
  private function callWSMenuPorProducto($prefijo, $rif, $acCodCia, $codgrupoe, $pais)
  {
    $this->lang->load('erroreseol');
    $canal = "ceo";
    $modulo = "login";
    $function = "login";
    $operation = "menuPorProducto";
    $className = "com.novo.objects.MO.ListadoMenuMO";
    $timeLog = date("m/d/Y H:i");
    $ip = $this->input->ip_address();
    $sessionId = $this->session->userdata('sessionId');
    $username = $this->session->userdata('userName');
    $token = $this->session->userdata('token');
    $logAcceso = np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $operation, 0, $ip, $timeLog);

    $menus = array(array(
      "app" => "EOL",
      "prod" => "$prefijo",
      "idUsuario" => "$username",
      "idEmpresa" => "$rif"
    ));
    $estadistica = array(
      "producto" => array(
        "prefijo" => "$prefijo",
        "rifEmpresa" => "$rif",
        "acCodCia" => "$acCodCia",
        "acCodGrupo" => "$codgrupoe"
      )
    );

    $data = array(
      "idOperation" => $operation,
      "className" => $className,
      "menus" => $menus,
      "estadistica" => $estadistica,
      "logAccesoObject" => $logAcceso,
      "token" => $token,
      "pais" => $pais
    );
    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $dataEncry = np_Hoplite_Encryption($data, 'callWSMenuPorProducto');

    $data = array('bean' => $dataEncry, 'pais' => $pais);
    $data = json_encode($data);
    $response = np_Hoplite_GetWS($data);
    $jsonResponse = np_Hoplite_Decrypt($response, 'callWSMenuPorProducto');
    $response = json_decode($jsonResponse);

    if ($response) {
      log_message('info', 'detalle produc ' . $response->rc . "/" . $response->msg);
      if ($response->rc == 0) {

        return $response;
      } else {

        if ($response->rc == -61 || $response->rc == -29) {
          $this->session->sess_destroy();
          return $codigoError = array('ERROR' => '-29');
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
      log_message('info', 'detalle produc NO WS');
      return $codigoError = array('ERROR' => lang('ERROR_GENERICO_USER'));
    }
  }

  /**
   * Pantalla que muestra los programas disponibles para determinado país
   * @param  string $urlCountry
   */
  public function programas($urlCountry)
  {

    //VALIDATE COUNTRY
    np_hoplite_countryCheck($urlCountry);
    $this->lang->load('dashboard');
    $this->lang->load('erroreseol');
    $this->lang->load('users');
    $this->load->library('parser');
    $logged_in = $this->session->userdata('logged_in');

    //SE VALIDA SI EL USUARIO ESTA LOGGEDIN
    $paisS = $this->session->userdata('pais');

    if ($paisS == $urlCountry && $logged_in) {

      $FooterCustomJS = "";

      $titlePage = "Otros programas";
      $FooterCustomInsertJS = ["jquery-3.6.0.min.js", "jquery-ui-1.13.1.min.js", "jquery.balloon.min.js", "aes.min.js", "aes-json-format.min.js", "dashboard/widget-empresa.js", "dashboard/other-products.js", "header.js", "routes.js"];
      //INSTANCIA MENU HEADER
      $menuHeader = $this->parser->parse('widgets/widget-menuHeader', array(), TRUE);
      //INSTANCIA MENU FOOTER
      $menuFooter = $this->parser->parse('widgets/widget-menuFooter', array(), TRUE);
      $header = $this->parser->parse('layouts/layout-header', array('bodyclass' => '', 'menuHeaderActive' => TRUE, 'menuHeaderMainActive' => TRUE, 'menuHeader' => $menuHeader, 'titlePage' => $titlePage), TRUE);
      $footer = $this->parser->parse('layouts/layout-footer', array('menuFooterActive' => TRUE, 'menuFooter' => $menuFooter, 'FooterCustomInsertJSActive' => TRUE, 'FooterCustomInsertJS' => $FooterCustomInsertJS, 'FooterCustomJSActive' => TRUE, 'FooterCustomJS' => $FooterCustomJS), TRUE);

      $content = $this->parser->parse('dashboard/dashboard-other-products-' . $urlCountry, array(
        'titulo' => $titlePage
      ), TRUE);

      $sidebarEmpresa = $this->parser->parse('widgets/widget-publi-2', array('sidebarActive' => TRUE), TRUE);

      $datos = array(
        'header' => $header,
        'content' => $content,
        'footer' => $footer,
        'sidebar' => $sidebarEmpresa
      );

      $this->parser->parse('layouts/layout-b', $datos);
    } elseif ($paisS != $urlCountry && $paisS != "") {
      $this->session->sess_destroy();
      redirect($urlCountry . '/login');
    } else {
      redirect($urlCountry . '/login');
    }
  }
}
