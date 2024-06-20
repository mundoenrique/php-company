<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * NOVOPAYMENT toolset Helpers
 *
 * @subpackage	Helpers
 * @category		Helpers
 * @author			J. Enrique Peñaloza P
 * @date				Novembre 23th, 2019
 */
if (!function_exists('assetPath')) {
  function assetPath($route = '')
  {
    return config_item('asset_path') . $route;
  }
}

if (!function_exists('assetUrl')) {
  function assetUrl($route = '')
  {
    return config_item('asset_url') . $route;
  }
}

if (!function_exists('clientUrlValidate')) {
  function clientUrlValidate($customer)
  {
    $CI = &get_instance();
    $accessUrl = ACCESS_URL;

    if (!in_array($customer, $accessUrl) || $customer === 0) {
      $baseUrl = str_replace("$customer/", "$accessUrl[0]/", base_url('sign-in'));
      redirect($baseUrl, 'Location', 302);
      exit;
    }

    $CI->config->load('config-' . $customer);
  }
}

if (!function_exists('arrayTrim')) {
  function arrayTrim(&$value)
  {
    $value = trim($value);

    return $value;
  }
}

if (!function_exists('clearSessionsVars')) {
  function clearSessionsVars()
  {
    $CI = &get_instance();
    $isLogged = $CI->session->has_userdata('logged');
    $isUserId = $CI->session->has_userdata('userId');

    if ($isLogged || $isUserId) {
      $CI->session->unset_userdata(['logged', 'userId', 'userName', 'enterpriseInf', 'productInf']);
      $CI->session->sess_destroy();
    }
  }
}

if (!function_exists('accessLog')) {
  function accessLog($dataAccessLog)
  {
    $CI = &get_instance();

    return [
      "sessionId" => $CI->session->userdata('sessionId') ?? '',
      "userName" => $CI->session->userdata('userName') ?? $dataAccessLog->userName,
      "canal" => $CI->config->item('channel'),
      "modulo" => $dataAccessLog->modulo,
      "function" => $dataAccessLog->function,
      "operacion" => $dataAccessLog->operation,
      "RC" => 0,
      "IP" => $CI->input->ip_address(),
      "dttimesstamp" => date('m/d/Y H:i'),
      "lenguaje" => strtoupper(LANGUAGE)
    ];
  }
}

if (!function_exists('maskString')) {
  function maskString($string, $start = 1, $end = 1, $type = NULL)
  {
    $type = $type ? $type : '';
    $length = strlen($string);
    return substr($string, 0, $start) . str_repeat('*', 3) . $type . str_repeat('*', 3) . substr($string, $length - $end, $end);
  }
}

if (!function_exists('setCurrentPage')) {
  function setCurrentPage($currentClass, $menu)
  {
    $cssClass = '';

    switch ($currentClass) {
      case 'Novo_Business':
        if ($menu == lang('GEN_MENU_ENTERPRISE')) {
          $cssClass = 'page-current';
        }
        break;
      case 'Novo_Bulk':
        if ($menu == lang('GEN_MENU_LOTS')) {
          $cssClass = 'page-current';
        }
        break;
      case 'Novo_Inquiries':
        if ($menu == lang('GEN_MENU_CONSULTATIONS')) {
          $cssClass = 'page-current';
        }
        break;
      case 'Novo_Services':
        if ($menu == lang('GEN_MENU_SERVICES')) {
          $cssClass = 'page-current';
        }
        break;
      case 'Novo_Reports':
        if ($menu == lang('GEN_MENU_REPORTS')) {
          $cssClass = 'page-current';
        }
        break;
      case 'Novo_User':
        if ($menu == lang('GEN_MENU_USERS')) {
          $cssClass = 'page-current';
        }
        break;
    }

    return $cssClass;
  }
}

if (!function_exists('exportFile')) {
  function exportFile($file, $typeFile, $filename, $bytes = TRUE)
  {
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

if (!function_exists('convertDate')) {
  function convertDate($date)
  {
    $date = explode('/', $date);
    $date = $date[2] . '-' . $date[1] . '-' . $date[0];

    return $date;
  }
}

if (!function_exists('convertDateMDY')) {
  function convertDateMDY($date)
  {
    $date = explode('/', $date);
    $date = $date[1] . '/' . $date[0] . '/' . $date[2];

    return $date;
  }
}

if (!function_exists('currencyFormat')) {
  function currencyFormat($amount)
  {
    $CI = &get_instance();
    $client = $CI->session->userdata('customerSess');
    $decimalPoint = ['Ve', 'Co', 'Bdb'];
    $amount = (float)$amount;

    if (in_array($client, $decimalPoint)) {
      $amount = number_format($amount, 2, ',', '.');
    } else {
      $amount = number_format($amount, 2);
    }

    return $amount;
  }
}

if (!function_exists('tenantSameSettings')) {
  function tenantSameSettings($customer)
  {
    $pattern = [
      '/bdbo/',
      '/bog/',
      '/bgu/',
      '/bnte/',
      '/bpi/',
      '/bpic/',
      '/col/',
      '/coopc/',
      '/pba/',
      '/per/',
      '/usd/',
      '/ven/',
      '/vgy/',
    ];
    $replace = [
      'bdb',
      'bdb',
      'bg',
      'bnt',
      'bp',
      'bp',
      'co',
      'coop',
      'pb',
      'pe',
      'us',
      've',
      'vg',
    ];
    $customer = preg_replace($pattern, $replace, $customer);

    return $customer;
  }
}

if (!function_exists('normalizeName')) {
  function normalizeName($name)
  {
    $pattern = [
      '/\s+/', '/\(/', '/\)/',
      '/á/', '/à/', '/ä/', '/â/', '/ª/', '/Á/', '/À/', '/Â/', '/Ä/',
      '/é/', '/è/', '/ë/', '/ê/', '/É/', '/È/', '/Ê/', '/Ë/',
      '/í/', '/ì/', '/ï/', '/î/', '/Í/', '/Ì/', '/Ï/', '/Î/',
      '/ó/', '/ò/', '/ö/', '/ô/', '/Ó/', '/Ò/', '/Ö/', '/Ô/',
      '/ú/', '/ù/', '/ü/', '/û/', '/Ú/', '/Ù/', '/Û/', '/Ü/',
      '/ñ/', '/Ñ/', '/ç/', '/Ç/'
    ];
    $replace = [
      '_', '_', '  ',
      'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
      'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
      'i', 'i', 'i', 'i', 'i', 'i', 'i', 'i',
      'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
      'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
      'n', 'n', 'c', 'c'
    ];

    return manageString(preg_replace($pattern, $replace, $name), 'lower', 'none');
  }
}

if (!function_exists('uriRedirect')) {
  /**
   * @info Handling uri redirect
   * @author epenaloza
   * @date May 19th, 2023
   * @return string uri redirec.
   */
  function uriRedirect()
  {
    $CI = &get_instance();
    $redirectLink = getSignSessionType() === lang('SETT_COOKIE_SINGN_IN')
      ? lang('SETT_LINK_SIGNIN')
      : lang('SETT_LINK_SIGNOUT') . lang('SETT_LINK_SIGNOUT_END');

    if ($CI->session->has_userdata('logged')) {
      $redirectLink = lang('SETT_LINK_ENTERPRISES');

      if ($CI->session->has_userdata('enterpriseInf')) {
        $redirectLink = lang('SETT_LINK_PRODUCTS');
      }

      if ($CI->session->has_userdata('productInf')) {
        $redirectLink = lang('SETT_LINK_PRODUCT_DETAIL');
      }
    }

    return $redirectLink;
  }
}

if (!function_exists('manageString')) {
  /**
   * @info Handling strings for trim and special cases
   * @author epenaloza
   * @date September 11th, 2023
   * @param string $string string to manage
   * @param string $case none | upper | lower
   * @param string $upperCase none | first | word
   * @return string the modified string.
   */
  function manageString($string, $case, $upperCase)
  {
    $stringCase = [
      'none' => 'none',
      'upper' => 'upperString',
      'lower' => 'lowerString',
      'first' => 'ucFirst',
      'word' => 'ucWords'
    ];

    $pattern = ['/\s+/', '/_+/'];
    $replace = [' ', '_'];
    $stringConverted = preg_replace($pattern, $replace, trim($string));

    switch ($stringCase[$case]) {
      case 'upperString':
        $stringConverted = mb_strtoupper($stringConverted, 'UTF-8');
        break;

      case 'lowerString':
        $stringConverted = mb_strtolower($stringConverted, 'UTF-8');
        break;
    }

    switch ($stringCase[$upperCase]) {
      case 'ucFirst':
        $stringConverted = ucfirst($stringConverted);
        break;

      case 'ucWords':
        $stringConverted = ucwords($stringConverted);
        break;
    }

    return $stringConverted;
  }
}

if (!function_exists('getSignSessionType ')) {
  /**
   * @info Get sign type cookie
   * @author epenaloza
   * @date September 17th, 2023
   * @return string getSignSessionType value.
   */
  function getSignSessionType()
  {
    $CI = &get_instance();
    $signType = SINGLE_SIGN_ON ? lang('SETT_COOKIE_SINGN_ON') : lang('SETT_COOKIE_SINGN_IN');
    $signType = $CI->router->method === 'signIn' ? lang('SETT_COOKIE_SINGN_IN') : $signType;
    $signType = $CI->router->method === 'singleSignOn' ? lang('SETT_COOKIE_SINGN_ON') : $signType;
    $signValue = get_cookie('signSessionType', TRUE);
    $cookieSign = ACTIVE_SAFETY && $signValue !== NULL ? base64_decode($signValue) : $signValue;
    $validCookie = in_array($cookieSign, [lang('SETT_COOKIE_SINGN_ON'), lang('SETT_COOKIE_SINGN_IN')]);
    $signType = $validCookie ? $cookieSign : $signType;

    if (!$validCookie) {
      setSignSessionType($signType);
    }

    return $signType;
  }
}

if (!function_exists('setSignSessionType')) {
  /**
   * @info Set sign type cookie
   * @author epenaloza
   * @date September 17th, 2023
   * @param string $signType type cookie signInSession | singleSignOnSession
   * @return void
   */
  function setSignSessionType($signType)
  {
    if (get_cookie('singleSession', TRUE) !== NULL) {
      delete_cookie('singleSession', config_item('cookie_domain'), config_item('cookie_path'));
    }

    $signSessionType = ACTIVE_SAFETY ? base64_encode($signType) : $signType;

    $signSessionType = [
      'name' => 'signSessionType',
      'value' => $signSessionType,
      'expire' => 1296000,
      'httponly' => TRUE
    ];

    set_cookie($signSessionType);
  }
}

if (!function_exists('methodWasmigrated')) {
  /**
   * @info Valid if the method was migrated
   * @author epenaloza
   * @date September 18th, 2023
   * @param string $method method to valid
   * @return bool
   */
  function methodWasmigrated($method, $class)
  {
    $CI = &get_instance();
    $methodsIn = [
      'benefitsInf',
      'changeLanguage',
      'finishSession',
      'loadModels',
      'login',
      'ratesInf',
      'signIn',
      'single',
      'singleSignOn',
      'termsInf',
      'terms',
    ];

    $migratedModule = array_search($method, $methodsIn, TRUE) !== FALSE;

    writeLog(
      'INFO',
      '************************ METHOD ' . $method . ' FROM CLASS ' . $class . ' MIGRATED ************************: ' .
        json_encode($migratedModule, JSON_UNESCAPED_UNICODE)
    );
    writeLog('INFO', 'accessAuthorization by uri ****** ' . $CI->uri->uri_string());

    return $migratedModule;
  }
}
