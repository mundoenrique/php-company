<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Controlador para la vistas de información general alusuario
 * @author J. Enrique Peñaloza Piñero.
 */
class Novo_Information extends NOVO_Controller {

	public function __construct()
	{
		parent:: __construct();
		writeLog('INFO', 'Information Class Initialized');
	}
	/**
	 * @info Método que renderiza la vista de beneficios de la aplicación
	 * @author Jennifer Cadiz
	 * @date September 3rd, 2021
	 */
	public function benefitsInf()
	{
		$view = 'benefits_info';

		array_push(
			$this->includeAssets->jsFiles,
			"user/benefitsInf",
		);

		writeLog('INFO', 'Information: benefitsInf Method Initialized');

		$this->render->titlePage =lang('GEN_FOTTER_BENEFITS');
		$this->render->activeHeader = TRUE;
		$this->views = ['information/'.$view];
		$this->loadView($view);
	}
	/**
	 * @info Método que renderiza la vista terminos y condiciones
	 * @author J. Enrique Peñaloza Piñero.
	 * @date April 16th, 2019
	 */
	public function termsInf()
	{
		writeLog('INFO', 'Information: termsInf Method Initialized');
		$newUser = FALSE;
		$view = 'terms';

		if($this->session->flashdata('changePassword')) {
			array_push(
				$this->includeAssets->jsFiles,
				"user/terms"
			);

			$newUser = TRUE;
			$this->session->keep_flashdata('changePassword');
			$this->session->keep_flashdata('userType');
			$this->render->message = lang('TERMS_MESSAGE');
		}

		$this->render->titlePage =lang('GEN_FOTTER_TERMS');
		$this->render->activeHeader = TRUE;
		$this->render->referer = $this->input->server('HTTP_REFERER');
		$baseReferer = substr($this->render->referer, 0, strlen(base_url()));
		$this->render->newUser = $newUser;
		$this->render->goBack = ($baseReferer === base_url()) && !$newUser;
		$this->views = ['information/'.$view];
		$this->loadView($view);
	}
	/**
	 * @info Método que renderiza la vista de tarifas de la aplicación
	 * @author Jennifer Cadiz
	 * @date September 3rd, 2021
	 */
	public function ratesInf()
	{
		writeLog('INFO', 'Information: ratesInf Method Initialized');

		$view = 'rates_info';
		$json_file = file_get_contents(ASSET_PATH . 'data/ve/rates-info.json');
		$json_data = json_decode($json_file);

		$rates_currency = $json_data->currency;
		$rates_currency_symbol = $json_data->currency_symbol;
		$rates_last_update = $json_data->last_update;
		$rates_refs = $json_data->refs;
		$rates_data = $json_data->data;

		$format_decimals = 2;
		$format_dec_point = '.';
		$format_thousands_sep = ',';
		if ($rates_currency === 'cop' || $rates_currency === 'ves') {
			$format_dec_point = ',';
			$format_thousands_sep = '.';
		}
		$format_params = (object)[
			'currency_symbol' => $rates_currency_symbol,
			'decimals' => $format_decimals,
			'dec_point' => $format_dec_point,
			'thousands_sep' => $format_thousands_sep
		];

		array_push(
			$this->includeAssets->cssFiles,
			"third_party/dataTables-1.10.20"
		);
		array_push(
			$this->includeAssets->jsFiles,
			"third_party/dataTables-1.10.20",
			"user/ratesInf"
		);

		$this->render->titlePage =lang('GEN_FOTTER_RATES');
		$this->render->json_data = $json_data;
		$this->render->rates_refs = $rates_refs;
		$this->render->rates_data = $rates_data;
		$this->render->format_params = $format_params;
		$this->render->referer = $this->input->server('HTTP_REFERER');
		$baseReferer = substr($this->render->referer, 0, strlen(base_url()));
		$this->render->goBack = $baseReferer === base_url();
		$this->views = ['information/'.$view];
		$this->loadView($view);
	}
	/*
	|--------------------------------------------------------------------------
	| TEMPORAL METHODS
	|--------------------------------------------------------------------------
	*/
	/**
	 * @info Método que renderiza la vista de beneficios de la aplicación
	 * @author J. Enrique Peñaloza Piñero.
	 * @date April 16th, 2019
	 */
	public function benefits()
	{
		writeLog('INFO', 'Information: benefits Method Initialized');

		$view = 'benefits';

		$this->render->titlePage =lang('GEN_FOTTER_BENEFITS');
		$this->views = ['information/'.$view];
		$this->loadView($view);
	}
	/**
	 * @info Método que renderiza la vista terminos y condiciones
	 * @author J. Enrique Peñaloza Piñero.
	 * @date April 16th, 2019
	 */
	public function terms()
	{
		writeLog('INFO', 'Information: terms Method Initialized');
		$newUser = FALSE;
		$view = 'terms';

		if($this->session->flashdata('changePassword')) {
			array_push(
				$this->includeAssets->jsFiles,
				"user/terms"
			);

			$newUser = TRUE;
			$this->session->set_flashdata('changePassword', $this->session->flashdata('changePassword'));
			$this->session->set_flashdata('userType', $this->session->flashdata('userType'));
			$this->render->message = lang('TERMS_MESSAGE');
		}
		$this->render->titlePage =lang('GEN_FOTTER_TERMS');
		$this->render->activeHeader = TRUE;
		$this->render->referer = $this->input->server('HTTP_REFERER');
		$baseReferer = substr($this->render->referer, 0, strlen(base_url()));
		$this->render->newUser = $newUser;
		$this->render->goBack = ($baseReferer === base_url()) && !$newUser;
		$this->views = ['information/'.$view];
		$this->loadView($view);
	}
}
