<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Novopayment Language Helpers
 *
 * @package CodeIgniter
 * @subpackage Helpers
 * @category Helpers
 * @author desarrolloweb@novopayment.com
 * @date October 13th 2019
 */

if (!function_exists('novoLang')) {
  /**
   * @info Interpolate strings in language variables
   * @author epenaloza
   * @date November 14th, 2019
   * @param string $line language variable
   * @param array $args arguments to interpolate
   * @return string
   */
  function novoLang($line, $args = [])
  {
    $line = vsprintf($line, (array) $args);

    return $line;
  }
}

if (!function_exists('LoadLangFile')) {
  /**
   * @info Load language file
   * @author epenaloza
   * @date January 25th, 2020
   * @param string $call general | specific
   * @param string $fileLanguage language file
   * @param string $customerLang language custumer
   * @return void
   */
  function LoadLangFile($call, $fileLanguage, $customerLang)
  {
    writeLog('INFO', 'Helper language loaded: LoadLangFile_helper for ' . $call . ' files');

    $CI = &get_instance();
    $languagesFile = [];
    $loadLanguages = FALSE;
    $configLanguage = $CI->config->item('language');
    $customerLang = tenantSameSettings($customerLang);
    $pathLang = APPPATH . 'language' . DIRECTORY_SEPARATOR . $configLanguage . DIRECTORY_SEPARATOR;

    switch ($call) {
      case 'generic':
        if (file_exists($pathLang . 'settings_' . $customerLang . '_lang.php')) {
          $CI->lang->load('settings_' . $customerLang);
        }

        if (file_exists($pathLang . 'images_' . $customerLang . '_lang.php')) {
          $CI->lang->load('images_' . $customerLang);
        }

        if (file_exists($pathLang . 'regexp_' . $customerLang . '_lang.php')) {
          $CI->lang->load('regexp_' . $customerLang);
        }

        $CI->config->set_item('language', BASE_LANGUAGE . '-base');
        array_push($languagesFile, 'general', 'validate');
        $loadLanguages = TRUE;
        $pathLang = APPPATH . 'language' . DIRECTORY_SEPARATOR . BASE_LANGUAGE . '-base' . DIRECTORY_SEPARATOR;
        break;

      case 'specific':
        if (file_exists($pathLang . 'general_lang.php')) {
          array_push($languagesFile, 'general');
          $loadLanguages = TRUE;
        }

        if (file_exists($pathLang . 'validate_lang.php')) {
          array_push($languagesFile, 'validate');
          $loadLanguages = TRUE;
        }

        //Borrar al finalizar la migración
        if (file_exists($pathLang . 'settings_lang.php')) {
          array_push($languagesFile, 'settings');
          $loadLanguages = TRUE;
        }
        break;
    }

    if (file_exists($pathLang . $fileLanguage . '_lang.php')) {
      array_push($languagesFile, $fileLanguage);
      $loadLanguages = TRUE;
    }

    if ($loadLanguages) {
      $CI->lang->load($languagesFile);
    }
  }
}

if (!function_exists('getLanguageValues')) {
  /**
   * @info Get language cookie value.
   * @author epenaloza
   * @date September 20th, 2023
   * @return array $language .language values
   */
  function getLanguageValues()
  {
    $language = lang('SETT_LANGUAGE')['es'];

    if (get_cookie('baseLanguage') !== NULL) {
      delete_cookie('baseLanguage', config_item('cookie_domain'), config_item('cookie_path'));
    }

    $cookieValue = get_cookie('appLanguage', TRUE);
    $cookieLang = ACTIVE_SAFETY ? base64_decode($cookieValue) : $cookieValue;
    $uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    $uriCode = end($uriSegments);

    $validCookie = in_array($cookieLang, lang('SETT_LANGUAGE'));
    $validUri = array_key_exists($uriCode, lang('SETT_LANGUAGE'));

    $cookieLang = ENGLISH_ACTIVE && $validCookie ? $cookieLang : $language;
    $uriLang = ENGLISH_ACTIVE && $validUri ? lang('SETT_LANGUAGE')[$uriCode] : $cookieLang;
    $language = $uriLang;

    if (!$validCookie || $cookieLang !== $uriCode || $cookieValue === NULL) {
      languageCookie(array_search($language, lang('SETT_LANGUAGE')));
    }

    return [
      'lang' => $language,
      'code' =>  array_search($language, lang('SETT_LANGUAGE'))
    ];
  }
}

if (!function_exists('languageCookie')) {
  /**
   * @info Set language cookie value.
   * @author epenaloza
   * @date September 20th, 2023
   * @param string $lang language es | en
   * @return void
   */
  function languageCookie($lang)
  {
    $isValidLang = array_key_exists($lang, lang('SETT_LANGUAGE'));
    $lang = $isValidLang ? $lang : array_search('spanish', lang('SETT_LANGUAGE'));
    $appLanguage = ACTIVE_SAFETY ? base64_encode(lang('SETT_LANGUAGE')[$lang]) : lang('SETT_LANGUAGE')[$lang];
    $appLanguage = [
      'name' => 'appLanguage',
      'value' => $appLanguage,
      'expire' => 1296000,
      'httponly' => TRUE
    ];

    set_cookie($appLanguage);
  }
}

if (!function_exists('BulkAttrEmissionA')) {
  function BulkAttrEmissionA()
  {
    $tableContent = new stdClass();
    $tableContent->header = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_FULL_NAME'), lang('GEN_TABLE_STATUS')];
    $tableContent->body = ['idExtPer', 'nombres', 'apellidos', 'status'];

    switch (config_item('customer_uri')) {
      case 'pb':
      case 'bp':
        $tableContent->header = [
          lang('GEN_TABLE_ID_TYPE'), lang('GEN_TABLE_DNI'), lang('GEN_TABLE_FULL_NAME'), lang('GEN_TABLE_BRANCH_COD'), lang('GEN_TABLE_STATUS')
        ];
        $tableContent->body = ['typeIdentification', 'idExtPer', 'nombres', 'apellidos', 'ubicacion', 'status'];
        break;
    }

    return $tableContent;
  }
}

if (!function_exists('BulkAttrEmissionB')) {
  function BulkAttrEmissionB()
  {
    $tableContent = new stdClass();
    $tableContent->header = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_FULL_NAME'), lang('GEN_TABLE_ACCOUNT_NUMBER')];
    $tableContent->body = ['idExtPer', 'nombres', 'apellidos', 'nroTarjeta'];

    return $tableContent;
  }
}

if (!function_exists('BulkAttrEmissionC')) {
  function BulkAttrEmissionC()
  {
    $tableContent = new stdClass();
    $tableContent->header = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_FULL_NAME'), lang('GEN_EMAIL'), lang('GEN_TABLE_STATUS')];
    $tableContent->body = ['idExtPer', 'nombres', 'apellidos', 'email', 'status'];

    return $tableContent;
  }
}

if (!function_exists('BulkAttrCreditsA')) {
  function BulkAttrCreditsA()
  {
    $tableContent = new stdClass();
    $tableContent->header = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_AMOUNT'), lang('GEN_TABLE_ACCOUNT_NUMBER')];
    $tableContent->body = ['id_ext_per', 'monto', 'nro_cuenta'];

    return $tableContent;
  }
}

if (!function_exists('BulkAttrCreditsB')) {
  function BulkAttrCreditsB()
  {
    $tableContent = new stdClass();
    $tableContent->header = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_AMOUNT'), lang('GEN_TABLE_ACCOUNT_NUMBER'), lang('GEN_TABLE_STATUS')];
    $tableContent->body = ['id_ext_per', 'monto', 'nro_cuenta', 'status'];

    return $tableContent;
  }
}

if (!function_exists('BulkAttrCreditsC')) {
  function BulkAttrCreditsC()
  {
    $tableContent = new stdClass();
    $tableContent->header = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_AMOUNT'), lang('GEN_TABLE_ACCOUNT_NUMBER'), lang('GEN_TABLE_STATUS')];
    $tableContent->body = ['id_ext_per', 'monto', 'nro_cuenta', 'status'];

    return $tableContent;
  }
}

if (!function_exists('BulkAttrKindergastenA')) {
  function BulkAttrKindergastenA()
  {
    $tableContent = new stdClass();
    $tableContent->header = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_EMPLOYEE'), lang('GEN_TABLE_BENEFICIARY'), lang('GEN_TABLE_ACCOUNT_BENEFICIARY'), lang('GEN_TABLE_AMOUNT')];
    $tableContent->body = ['id_per', 'nombre', 'apellido', 'beneficiario', 'nro_cuenta', 'monto_total'];

    return $tableContent;
  }
}

if (!function_exists('BulkAttrKindergastenB')) {
  function BulkAttrKindergastenB()
  {
    $tableContent = new stdClass();
    $tableContent->header = [lang('GEN_TABLE_DNI'), lang('GEN_TABLE_EMPLOYEE'), lang('GEN_TABLE_BENEFICIARY'), lang('GEN_TABLE_AMOUNT')];
    $tableContent->body = ['id_per', 'nombre', 'apellido', 'beneficiario', 'monto_total'];

    return $tableContent;
  }
}

if (!function_exists('BulkAttrReplacementA')) {
  function BulkAttrReplacementA()
  {
    $tableContent = new stdClass();
    $tableContent->header = [lang('GEN_TABLE_ACCOUNT_NUMBER'), lang('GEN_TABLE_DNI')];
    $tableContent->body = ['aced_rif', 'nocuenta'];

    return $tableContent;
  }
}
