<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * CodeIgniter XML Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/xml_helper.html
 */
if (!function_exists('np_hoplite_log')) {
  /**
   * Helper que lanza la descarga de un documento que arma el objeto logAccesoObject y lo retorna
   *
   * @param  string $username
   * @param  string $canal
   * @param  string $modulo
   * @param  string $function
   * @param  string $operacion
   * @param  int $rc
   * @param  string $ip
   * @param  date $timeLog
   * @return array
   */
  function np_hoplite_log($sessionId, $username, $canal, $modulo, $function, $operacion, $rc, $ip, $timeLog)
  {
    $logAcceso = array(
      "sessionId" => $sessionId,
      "userName" => $username,
      "canal" => $canal,
      "modulo" => $modulo,
      "function" => $function,
      "operacion" => $operacion,
      "RC" => $rc,
      "IP" => $ip,
      "dttimesstamp" => $timeLog,
      "lenguaje" => "ES"
    );
    return $logAcceso;
  }
}

if (!function_exists('np_hoplite_countryCheck')) {
  /**
   * Helper que lanza la descarga de un documento para emplear el archivo de configuración adecuado, dependiendo del país.
   * El archivo de configuración indica el lenguaje, los paths y URLs a emplear
   *
   * @param  string $countryISO
   */
  function np_hoplite_countryCheck($countryISO)
  {
    $CI = &get_instance();

    switch ($countryISO) {
      case 'Ve':
      case 'ven':
        $CI->config->load('conf-ve-config');
        break;
      case 'Co':
      case 'col':
        $CI->config->load('conf-co-config');
        break;
      case 'Pe':
      case 'per':
        $CI->config->load('conf-pe-config');
        break;
      case 'Usd':
      case 'usd':
        $CI->config->load('conf-usd-config');
        break;
      case 'Ec-bp':
      case 'bpi':
        $CI->config->load('conf-ec-bp-config');
        break;
      default:
        redirect('pe/inicio');
    }
  }
}

if (!function_exists('np_hoplite_byteArrayToFile')) {
  /**
   * Helper que lanza al navegador la descarga de un documento.
   * Recibe como parametros los bytes del documento, el nombre y tipo de archivo.
   *
   * @param  byte $bytes
   * @return document
   */
  function np_hoplite_byteArrayToFile($file, $typeFile, $filename, $bytes = TRUE)
  {
    $CI = &get_instance();

    switch ($typeFile) {
      case 'pdf':
        header('Content-type: application/pdf');
        header('Content-Disposition: attachment; filename=' . $filename . '.pdf');
        header('Pragma: no-cache');
        header('Expires: 0');
        break;
      case 'xls':
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=' . $filename . '.xls');
        header('Pragma: no-cache');
        header('Expires: 0');
        break;
      case 'xlsx':
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename=' . $filename . '.xlsx');
        header('Pragma: no-cache');
        header('Expires: 0');
        break;
      default:
        break;
    }

    if ($bytes) {
      foreach ($file as $chr) {
        echo chr($chr);
      }
    } else {
      echo $file;
    }
  }
}

if (!function_exists('np_hoplite_jsontoiconsector')) {
  /**
   * Helper para obtener el nombre del icono que representa el sector económico de la empresa.
   * <actualmente no se hace uso de este helper>
   *
   * @param  string $nroIcon
   * @return string
   */
  function np_hoplite_jsontoiconsector($nroIcon)
  {
    $string = file_get_contents("/opt/httpd-2.4.4/vhost/online/application/uploads/sector.json");
    $json_a = json_decode($string);
    $icon = $json_a->pe->{$nroIcon};
    return $icon;
  }
}

if (!function_exists('createMenu')) {
  function createMenu($userAccess, $seralize = FALSE)
  {
    $menuData = $seralize ? unserialize($userAccess) : $userAccess;
    $levelOneOpts = [];
    if ($menuData == NULL || !isset($menuData))
      return $levelOneOpts;
    foreach ($menuData as $function) {
      $levelTwoOpts = [];
      $levelThreeOpts = [];
      $seeLotFact = FALSE;
      foreach ($function->modulos as $module) {
        if ($module->idModulo === 'TEBAUT')
          $seeLotFact = TRUE;
        if ($module->idModulo === 'LOTFAC' && !$seeLotFact)
          continue;
        $moduleOpt = [
          'route' => menuRoute($module->idModulo, $seeLotFact),
          'text' => lang($module->idModulo)
        ];
        if ($module->idModulo === 'TICARG' || $module->idModulo === 'TIINVN')
          $levelThreeOpts[] = $moduleOpt;
        else
          $levelTwoOpts[] = $moduleOpt;
      }
      if (!empty($levelThreeOpts))
        $levelTwoOpts[] = [
          'route' => '#',
          'text' => 'Cuentas innominadas',
          'suboptions' => $levelThreeOpts
        ];
      $levelOneOpts[] = [
        'icon' => menuIcon($function->idPerfil),
        'text' => lang($function->idPerfil),
        'suboptions' => $levelTwoOpts
      ];
    }
    return $levelOneOpts;
  }
}

if (!function_exists('menuIcon')) {
  function menuIcon($functionId)
  {
    switch ($functionId) {
      case 'CONSUL':
        return "&#xe072;";
      case 'GESLOT':
        return "&#xe03c;";
      case 'SERVIC':
        return "&#xe019;";
      case 'GESREP':
        return "&#xe021;";
      case 'COMBUS':
        return "&#xe08e;";
    }
    return '';
  }
}

if (!function_exists('menuRoute')) {
  function menuRoute($functionId, $seeLotFact)
  {
    $CI = &get_instance();
    $country = $CI->config->item('country');
    $countryUri = $CI->config->item('countryUri');
    switch ($functionId) {
      case 'TEBCAR':
        return base_url($country . "/lotes/carga");
      case 'TEBAUT':
        return base_url($country . "/lotes/autorizacion");
      case 'TEBGUR':
        return base_url($country . "/lotes/reproceso");
      case 'TICARG':
        return base_url($country . "/lotes/innominada");
      case 'TIINVN':
        return base_url($country . "/lotes/innominada/afiliacion");
      case 'TEBTHA':
        return base_url($country . "/reportes/tarjetahabientes");
      case 'TEBORS':
        return base_url($country . "/consulta/ordenes-de-servicio");
      case 'TRAMAE':
        return base_url($country . "/servicios/transferencia-maestra");
      case 'COPELO':
        return base_url($country . "/servicios/consulta-tarjetas");
      case 'CONVIS':
        return base_url($country . "/controles/visa");
      case 'PAGPRO':
        return base_url($country . "/pagos");
      case 'TEBPOL':
        return base_url($country . "/servicios/actualizar-datos");
      case 'CMBCON':
        return base_url($country . "/trayectos/conductores");
      case 'CMBVHI':
        return base_url($country . "/trayectos/gruposVehiculos");
      case 'CMBCTA':
        return base_url($country . "/trayectos/cuentas");
      case 'CMBVJE':
        return base_url($country . "/trayectos/viajes");
      case 'REPTAR':
        return base_url($country . "/reportes/tarjetas-emitidas");
      case 'REPPRO':
        return base_url($country . "/reportes/recargas-realizadas");
      case 'REPLOT':
        return base_url($country . "/reportes/estatus-lotes");
      case 'REPUSU':
        return base_url($country . "/reportes/actividad-por-usuario");
      case 'REPCON':
        return base_url($country . "/reportes/cuenta-concentradora");
      case 'REPSAL':
        return base_url($country . "/reportes/saldos-al-cierre");
      case 'REPREP':
        return base_url($country . "/reportes/reposiciones");
      case 'REPCAT':
        return base_url($country . "/reportes/gastos-por-categorias");
      case 'REPEDO':
        return base_url($country . "/reportes/estados-de-cuenta");
      case 'REPPGE':
        return base_url($country . "/reportes/guarderia");
      case 'REPRTH':
        return base_url($country . "/reportes/comisiones");
      case 'LOTFAC':
        if ($seeLotFact) return base_url($country . "/consulta/lotes-por-facturar");
    }
    return '#';
  }
}

if (!function_exists('np_hoplite_existeLink')) {
  /**
   * Helper empleado para saber si determinado módulo se encuentra habilitado para el usuario desde el menú.
   * retorna un entero positivo en caso de que exista el módulo y false en caso contrario.
   *
   * @param  string $menuP  menú enviado por el WS
   * @param  módulo $link   módulo a buscar
   * @return int, boolean   int si lo encuentra, false si no
   */
  function np_hoplite_existeLink($menuP, $link)
  {
    $arrayMenu = unserialize($menuP);
    $modulos = "";

    if ($arrayMenu != "") {

      foreach ($arrayMenu as $value) {
        foreach ($value->modulos as $modulo) {
          $modulos .= strtolower($modulo->idModulo) . ",";
        }
      }

      return strrpos($modulos, strtolower($link));
    } else {
      return false;
    }
  }
}

if (!function_exists('np_hoplite_modFunciones')) {
  /**
   * Helper que retorna un arreglo con las todas las funciones a las que está autorizado un usuario.
   *
   * @param  string $menuP    menú enviado por el ws
   * @return array            arreglo con las funciones
   */
  function np_hoplite_modFunciones($menuP)
  {

    $arrayMenu = unserialize($menuP);
    $funciones = "";

    if ($arrayMenu != "") {

      foreach ($arrayMenu as $value) {
        foreach ($value->modulos as $modulo) {
          foreach ($modulo->funciones as $func) {
            $funciones .= strtolower($func->accodfuncion) . ",";
          }
        }
      }

      return explode(',', strtolower($funciones));
    } else {
      return false;
    }
  }
}

if (!function_exists('amount_format')) {

  function amount_format($amount)
  {
    $CI = &get_instance();
    $country = $CI->session->userdata('pais');
    if ($country == 'Ve' || $country == 'Co') {

      return number_format($amount, 2, ",", ".");
      # code...
      // return $country;
    } else {

      return number_format($amount, 2);
    }

    // return $CI->session->userdata('pais');
  }
}

if (!function_exists('mask_account')) {
  function mask_account($account, $start = 1, $end = 1)
  {
    $CI = &get_instance();
    $len = strlen($account);
    return substr($account, 0, $start) . str_repeat('*', $len - ($start + $end)) . substr($account, $len - $end, $end);
  }
}
