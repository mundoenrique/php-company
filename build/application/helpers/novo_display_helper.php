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
		log_message('INFO', 'NOVO verifyDisplay partialView= '.$partialView.' module= '.$module.' link= '.$link);

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
		$customerUri = $CI->config->item('customer_uri');
		$logged = $CI->session->has_userdata('logged');

		switch ($link) {
			case lang('GEN_SHOW_HEADER'):
				$show = ['pichincha'];
				$display = ($module !== 'login' && in_array($client, $show));
				$show = ['novo'];
				$display = (in_array($client, $show) || $display);
				break;
			case lang('GEN_SHOW_HEADER_LOGO'):
				$show = ['pichincha'];
				$display = (in_array($client, $show));
				break;
			case lang('GEN_SHOW_CONFIG'):
				$show = ['novo', 'pichincha', 'banco-bog', 'banorte'];
				$display = ($logged && in_array($client, $show));
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
				$display = (in_array($client, $show) && $customerUri == 've');
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
		$customerUri = $CI->config->item('customer_uri');

		switch ($link) {
			case lang('GEN_SIGNIN_TOP'):
				$show = ['pichincha', 'banco-bog'];
				$display = (in_array($client, $show));
				break;
			case lang('GEN_SIGNIN_HEADER'):
				$show = ['novo'];
				$display = (in_array($client, $show));
				break;
			case lang('GEN_TAG_RECOVER_PASS'):
				$show = ['banco-bog'];
				$display = (!in_array($client, $show));
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
				$show = ['novo', 'pichincha', 'banorte'];
				$display = (in_array($client, $show));
				break;
			case lang('GEN_TAG_ORDER_TYPE'):
				$show = ['novo', 'pichincha', 'banorte'];
				$display = (in_array($client, $show) && $customerUri != 've');
				break;
			case lang('GEN_TAG_CANCEL_BUTTON'):
				$show = ['novo', 'pichincha', 'banorte'];
				$display = (in_array($client, $show));
				break;
			case lang('GEN_BTN_USER'):
				$show = ['novo', 'pichincha', 'banorte'];
				$display = (in_array($client, $show));
				break;
			case lang('GEN_BTN_ENTERPRISE'):
				$show = ['novo', 'pichincha', 'banorte'];
				$display = (in_array($client, $show));
				break;
			case lang('GEN_BTN_BRANCH'):
				$show = ['novo', 'pichincha', 'banorte'];
				$display = (in_array($client, $show));
				break;
			case lang('GEN_BTN_DOWNLOADS'):
				$show = ['novo', 'pichincha', 'banco-bog', 'banorte'];
				$display = (in_array($client, $show));
				break;
			case lang('GEN_GL_USER_MANUAL'):
				$show = ['novo', 'pichincha', 'banorte'];
				$display = (in_array($client, $show));
				break;
			case lang('GEN_APPLICATIONS'):
				$show = ['novo', 'pichincha', 'banorte'];
				$display = (in_array($client, $show));
				break;
			case lang('GEN_FILE'):
				$show = ['novo', 'pichincha', 'banorte'];
				$display = (in_array($client, $show));
				break;
			case lang('GEN_IMAGE_LOGIN'):
				$show = ['banorte'];
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
		$customerUri = $CI->config->item('customer_uri');
		$logged = $CI->session->has_userdata('logged');

		switch ($link) {
			case lang('GEN_FOTTER_START'):
				$show = ['novo', 'pichincha'];
				$display = ($module !== 'login' && $module !== lang('CONF_LINK_SUGGESTION') && !$logged);
				$display = (in_array($client, $show) && $display);
				break;
			case lang('GEN_FOTTER_BENEFITS'):
				$show = ['novo', 'pichincha'];
				$display = ($module !== 'benefits' && $module !== 'change-password' && $module !== lang('CONF_LINK_SUGGESTION'));
				$display = (in_array($client, $show) && $display && $customerUri != 'bpi');
				break;
			case lang('GEN_FOTTER_TERMS'):
				$show = ['novo', 'pichincha'];
				$display = ($module !== 'terms' && $module !== 'change-password' && $module !== lang('CONF_LINK_SUGGESTION'));
				$display = (in_array($client, $show) && $display && $customerUri != 'bpi');
				break;
			case lang('GEN_FOTTER_RATES'):
				$display = ($module !== 'rates' && $logged && $customerUri == 've');
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
