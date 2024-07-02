<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Novopayment regex Helpers
 *
 * @package CodeIgniter
 * @subpackage Helpers
 * @category Helpers
 * @author desarrolloweb@novopayment.com
 * @date February 9th, 2024
 */
if (!function_exists('setRegex')) {
  /**
   * @info regex variables
   * @author epenaloza
   * @date February 9th, 2024
   * @param string $regexName | regex key
   * @return string $regex | regex Value
   */
  function setRegex($regexName)
  {
    $regex = [
      'integer' => '^[\d]+$',
      'alpha_num' => '^[a-z\d]+$',
      'alpha_num_special' => '^[\w\d\sñÑáéíóúüÁÉÍÓÚÜ\#\(\)\,\.\-\/]+$',
      'address' => '^[a-z\d]{1,}[\w\d\sñÑáéíóúüÁÉÍÓÚÜ\#\(\)\,\.\-\/]*',
      'people_name' => '^[a-z]{2,}([a-z\d\sñÑáéíóúÁÉÍÓÚ\/])*$',
      'fiscal_id_col' => '^([\d]{9,17}$)',
      'fiscal_id_ven' => '^([VEJPGvejpg]{1})-([\d]{8})-([\d]{1}$)',
      'fiscal_id_per' => '^(10|15|16|17|20)(\d){9}$',
      'fiscal_id_usa' => '^([\w\-])+$',
      'fiscal_id_ecu' => '^(00|01|02|03|04|05|06|07|08|09|10|11|12|13|14|15|16|17|18|19|20|21|22|23|24)(6|9)(\d){5,6}(\d){3,4}$',
      'fiscal_id_mex' => '^([\w\d]{8,9}$)',
    ];

    return $regex[$regexName];
  }
}

$lang['REGEX_NAME_PEOPLE'] = '';
