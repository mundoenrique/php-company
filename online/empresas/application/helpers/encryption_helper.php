<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter XML Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/xml_helper.html
 */

// ------------------------------------------------------------------------

if ( ! function_exists('np_hoplite_Encryption'))
{
	/**
	 * Encripta el texto seleccionado con el algoritmo DES
	 * @param  string $data
	 * @return string
	 */
	function np_Hoplite_Encryption($data)
	{
		$CI =& get_instance();
		
		 $dataB = base64_encode($data);
		 while( (strlen($dataB)%8) != 0) {
                $dataB .= " ";
         }
		$cryptData = @mcrypt_encrypt(MCRYPT_DES, $CI->config->item('keyNovo'), $dataB, MCRYPT_MODE_CBC,$iv);
	    return base64_encode($cryptData);
	}
}

if ( ! function_exists('np_hoplite_Decrypt'))
{
	/**
	 * Desencripta el texto seleccionado con el algoritmo DES
	 * @param  string $cryptDataBase64
	 * @return string
	 */
	function np_Hoplite_Decrypt($cryptDataBase64)
	{
		$CI =& get_instance();
		$a = base64_decode($cryptDataBase64);
    	$descryptData = @mcrypt_decrypt(MCRYPT_DES, $CI->config->item('keyNovo'), $a, MCRYPT_MODE_CBC);
    	$decryptData = trim($descryptData); 
    	return base64_decode($decryptData);
	}
}