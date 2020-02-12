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
		log_message('INFO', 'NOVO verifyDisplay HELPER Initialized');

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

		log_message('INFO', 'NOVO verifyDisplay '.$module. ' '.$link.': '.json_encode($display));

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
		log_message('INFO', 'NOVO verifyheader HELPER Initialized');

		$CI = &get_instance();
		$client = $CI->config->item('client');
		$countryUri = $CI->config->item('country-uri');
		$logged = $CI->session->has_userdata('logged');

		switch ($link) {
			case lang('GEN_SHOW_HEADER'):
				$show = ['pichincha'];
				$display = ($module !== lang('GEN_LOGIN') && in_array($client, $show));
				$show = ['novo'];
				$display = (in_array($client, $show) || $display);
				break;
			case lang('GEN_SHOW_HEADER_LOGO'):
				$show = ['pichincha'];
				$display = (in_array($client, $show));
				break;
			case lang('GEN_SHOW_CONFIG'):
				$show = ['novo', 'pichincha'];
				$display = (in_array($client, $show));
				break;
			case lang('GEN_TAG_GOUT_MENU'):
				$show = ['novo'];
				$display = (in_array($client, $show));
				break;
			case lang('GEN_TAG_REPORT_MENU'):
				$show = ['banco-bog'];
				$display = (in_array($client, $show));
				break;
			case lang('GEN_TAG_LINK_UNIC'):
				$show = ['novo'];
				$display = (in_array($client, $show));
				break;
			case lang('GEN_TAG_HELPER'):
				$show = ['novo'];
				$display = (in_array($client, $show) && $countryUri == 've');
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
		log_message('INFO', 'NOVO verifyBody HELPER Initialized');

		$CI = &get_instance();
		$client = $CI->config->item('client');
		$country = $CI->config->item('country-uri');

		switch ($link) {
			case lang('GEN_SIGNIN_TOP'):
				$show = ['pichincha', 'banco-bog'];
				$display = (in_array($client, $show));
				break;
			case lang('GEN_SIGNIN_HEADER'):
				$show = ['novo'];
				$display = (in_array($client, $show));
				break;
			case lang('GEN_TAG_WELCOME_MESSAGE'):
				$show = ['pichincha'];
				$display = (in_array($client, $show));
				break;
			case lang('GEN_TAG_SEARCH_CAT'):
				$show = ['novo', 'pichincha'];
				$display = (in_array($client, $show));
				break;
			case lang('GEN_TAG_BRANCHOFFICE'):
				$show = ['banco-bog'];
				$display = (in_array($client, $show));
				break;
			case lang('GEN_TAG_ALL_BULK'):
				$show = ['novo', 'pichincha'];
				$display = (in_array($client, $show));
				break;
			case lang('GEN_TAG_ORDER_TYPE'):
				$show = ['novo', 'pichincha'];
				$display = (in_array($client, $show) && $country != 've');
				break;
			case lang('GEN_TAG_CANCEL_BUTTON'):
				$show = ['novo', 'pichincha'];
				$display = (in_array($client, $show));
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
		log_message('INFO', 'NOVO verifyFooter HELPER Initialized');

		$CI = &get_instance();
		$client = $CI->config->item('client');
		$countryUri = $CI->config->item('country-uri');
		$logged = $CI->session->has_userdata('logged');

		switch ($link) {
			case lang('GEN_FOTTER_START'):
				$show = ['novo', 'pichincha'];
				$display = ($module !== lang('GEN_LOGIN') && !$logged);
				$display = (in_array($client, $show) && $display);
				break;
			case lang('GEN_FOTTER_BENEFITS'):
				$show = ['novo', 'pichincha'];
				$display = ($module !== 'benefits' && $module !== 'change-password');
				$display = (in_array($client, $show) && $display && $countryUri != 'bp');
				break;
			case lang('GEN_FOTTER_TERMS'):
				$show = ['novo', 'pichincha'];
				$display = ($module !== 'terms' && $module !== 'change-password');
				$display = (in_array($client, $show) && $display && $countryUri != 'bp');
				break;
			case lang('GEN_FOTTER_RATES'):
				$display = ($module !== 'rates' && $logged && $countryUri == 've');
				break;
			case lang('GEN_FOTTER_LOGOUT'):
				$show = ['novo'];
				$display = ($logged && in_array($client, $show));
				break;
			case lang('GEN_FOTTER_OWNERSHIP'):
				$show = ['novo'];
				$display = (in_array($client, $show));
				break;
		}

		return $display;
	}
}
