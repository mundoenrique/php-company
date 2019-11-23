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
 * @info		Funcuón para validar un comprtamiento o visualización
 * @author	J. Enrique Peñaloza Piñero
 * @date 		November 23th, 2019
 */
if ( ! function_exists('verifyDisplay'))
{
	function verifyDisplay($partialView, $module, $link)
	{
		switch ($partialView) {
			case 'footer' :
				$display = verifylink($module, $link);
				break;
		}

		return $display;
	}
}
/**
 * @info		Funcuón para validar la visualización de una etiqueta
 * @author	J. Enrique Peñaloza Piñero
 * @date 		November 23th, 2019
 */
if ( ! function_exists('verifylink'))
{
	function verifylink($module, $link)
	{
		$CI = &get_instance();
		$client = $CI->config->item('client');
		$country = $CI->config->item('country');
		$logged = $CI->session->has_userdata('logged');
		$clients = ['novo'];

		switch ($link) {
			case lang('GEN_FOTTER_BENEFITS'):
				$display = ($module !== 'benefits' && $module !== 'change-password' && $module !== 'terms');
				$display = (in_array($client, $clients) && $display);
				break;
			case lang('GEN_FOTTER_TERMS'):
				$display = ($module !== 'terms' && $module !== 'change-password');
				$display = (in_array($client, $clients) && $display);
				break;
			case lang('GEN_FOTTER_RATES'):
				$display = ($module !== 'rates' && $logged && $country == 'Ve');
				$display = (in_array($client, $clients) && $display);
				break;
			case lang('GEN_FOTTER_LOGOUT'):
				$display = ($logged && in_array($client, $clients));
				break;
			case lang('GEN_FOTTER_OWNERSHIP'):
				$display = (in_array($client, $clients));
				break;
		}

		return $display;
	}
}
