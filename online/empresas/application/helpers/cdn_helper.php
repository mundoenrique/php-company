<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

if ( ! function_exists('insert_js_cdn'))
{
	/**
	 * Retorna cadena de texto con la sentencia para la inserción de un documento JavaScript
	 * @param  string $filename
	 * @return string
	 */
	function insert_js_cdn($filename = '')
	{
		$CI =& get_instance();
		$url_cdn = $CI->config->item('base_url_cdn');
		$js='<script src="'.$url_cdn.'media/js/'.$filename.'?20171101" type="text/javascript"></script>';
		return $js;
	}
}


if ( ! function_exists('insert_css_cdn'))
{
	/**
	 * Retorna cadena de texto con la sentencia para la inserción de una hoja de estilos.
	 * @param  string $filename
	 * @return string
	 */
	function insert_css_cdn($filename = '')
	{
		$CI =& get_instance();
		$url_cdn = $CI->config->item('base_url_cdn');
		$css='<link rel="stylesheet" type="text/css" href="'.$url_cdn.'media/css/'.$filename.'?20171101"/>';
		return $css;
	}
}

if ( ! function_exists('insert_image_cdn'))
{
	/**
	 * Retorna cadena de texto con la sentencia para la inserción de etiqueta de imágen HTML
	 * @param  string $filename
	 * @return string
	 */
	function insert_image_cdn($filename = '')
	{
		$CI =& get_instance();
		$url_cdn = $CI->config->item('base_url_cdn');
		$css='<img src="'.$url_cdn.'media/img/'.$filename.'?20170701">';
		return $css;
	}
}

if ( ! function_exists('get_cdn'))
{
	/**
	 * Obtener la URL del CDN denotada en el archivo de configuración
	 * @param  string $uri
	 * @return string
	 */
	//function get_base_cdn()
	function get_cdn()
	{
		$CI =& get_instance();
		$url_cdn = $CI->config->item('base_url_cdn');
		return $url_cdn;
	}
}
