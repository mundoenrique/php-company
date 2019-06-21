<?php
defined('BASEPATH') OR exit('No direct script access allowed');
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
		$path_cdn = $CI->config->item('CDN');
		$filepath = $path_cdn . 'media/js/' . $filename;
		$version = '';
		if (file_exists($filepath)) {
			$version = '?v=' . date('Ymd-U', filemtime($filepath));
		}
		$js='<script src="' . $url_cdn.'media/js/' . $filename . $version . '" type="text/javascript"></script>';
		echo "\n";
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
		$path_cdn = $CI->config->item('CDN');
		$filepath = $path_cdn . 'media/css/' . $filename;
		$version = '';
		if (file_exists($filepath)) {
			$version = '?v=' . date('Ymd-U', filemtime($filepath));
		}
		$css='<link rel="stylesheet" type="text/css" href="' . $url_cdn . 'media/css/' . $filename . $version . '"/>';
		echo "\n";
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
		$path_cdn = $CI->config->item('CDN');
		$filepath = $path_cdn . 'media/img/' . $filename;
		$version = '';
		if (file_exists($filepath)) {
			$version = '?v=' . date('Ymd-U', filemtime($filepath));
		}
		$img='<img src="' . $url_cdn . 'media/img/' . $filename . $version . '">';
		return $img;
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
