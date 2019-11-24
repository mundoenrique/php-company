<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * NOVOPAYMENT display Helpers
 *
 * @subpackage	Helpers
 * @category		Helpers
 * @author			J. Enrique Peñaloza P
 * @date				Novembre 23th, 2019
 */

// ------------------------------------------------------------------------
/**
 * @info		funció para validar un comprtamiento o visualización
 * @author	J. Enrique Peñaloza Piñero
 * @date 		November 23th, 2019
 */
if ( ! function_exists('verifyDisplay'))
{
	function verifyDisplay($partialView, $module, $link)
	{
		switch ($partialView) {
			case 'header' :
				$display = verifyHeader($module, $link);
			break;
			case 'body' :
				$display = verifyBody($module, $link);
			break;
		case 'footer' :
			$display = verifyFooter($module, $link);
			break;
		}

		return $display;
	}
}
/**
 * @info		Funcuón para validar la visualización o comportamiento de una etiqueta en el header
 * @author	J. Enrique Peñaloza Piñero
 * @date 		November 23th, 2019
 */
if ( ! function_exists('verifyheader'))
{
	function verifyheader($module, $link)
	{
		$CI = &get_instance();
		$client = $CI->config->item('client');
		$country = $CI->config->item('country');
		$logged = $CI->session->has_userdata('logged');
		$showUs = ['novo'];
		$showThem = ['pichincha'];

		switch ($link) {
			case lang('GEN_SHOW_HEADER'):
				$display = ($module !== lang('GEN_LOGIN') && in_array($client, $showThem));
				$display = (in_array($client, $showUs) || $display);
				break;
			case lang('GEN_SHOW_HEADER_LOGO'):
				$display = (in_array($client, $showThem));
				break;
		}

		return $display;
	}
}
/**
 * @info		Funcuón para validar la visualización o comportamiento de una etiqueta en el body
 * @author	J. Enrique Peñaloza Piñero
 * @date 		November 23th, 2019
 */
if ( ! function_exists('verifyBody'))
{
	function verifyBody($module, $link)
	{
		$CI = &get_instance();
		$client = $CI->config->item('client');
		$country = $CI->config->item('country');
		$logged = $CI->session->has_userdata('logged');
		$showUs = ['novo'];
		$showThem = ['pichincha'];

		switch ($link) {
			case lang('GEN_SIGNIN_TOP'):
				$display = (in_array($client, $showThem));
				break;
			case lang('GEN_SIGNIN_HEADER'):
				$display = (in_array($client, $showUs));
				break;
		}

		return $display;
	}
}
/**
 * @info		Funcuón para validar la visualización o comportamiento de una etiqueta en el footer
 * @author	J. Enrique Peñaloza Piñero
 * @date 		November 23th, 2019
 */
if ( ! function_exists('verifyFooter'))
{
	function verifyFooter($module, $link)
	{
		$CI = &get_instance();
		$client = $CI->config->item('client');
		$country = $CI->config->item('country');
		$logged = $CI->session->has_userdata('logged');
		$show = ['novo'];

		switch ($link) {
			case lang('GEN_FOTTER_START'):
				$display = ($module !== lang('GEN_LOGIN') && !$logged);
				$display = (in_array($client, $show) && $display);
				break;
			case lang('GEN_FOTTER_BENEFITS'):
				$display = ($module !== 'benefits' && $module !== 'change-password');
				$display = (in_array($client, $show) && $display);
				break;
			case lang('GEN_FOTTER_TERMS'):
				$display = ($module !== 'terms' && $module !== 'change-password');
				$display = (in_array($client, $show) && $display);
				break;
			case lang('GEN_FOTTER_RATES'):
				$display = ($module !== 'rates' && $logged && $country == 'Ve');
				$display = (in_array($client, $show) && $display);
				break;
			case lang('GEN_FOTTER_LOGOUT'):
				$display = ($logged && in_array($client, $show));
				break;
			case lang('GEN_FOTTER_OWNERSHIP'):
				$display = (in_array($client, $show));
				break;
		}

		return $display;
	}
}
