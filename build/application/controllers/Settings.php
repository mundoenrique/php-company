<?php
defined('BASEPATH') OR  exit('No direct script access allowed');
/**
 * @info Controlador para manejar las peticiones referentes a configuraciones
 * @author Luis Molina
 * @date Marz 20Fri, 2020
*/
class Settings extends NOVO_Controller {

	public function __construct()
	{
		parent :: __construct();
		log_message('INFO', 'NOVO Settings Controller Class Initialized');
	}

	/**
	 * @info Método para renderizar el modulo de configuración
	 * @author Luis Molina
	 * @date Mar 23Mon, 2020
	 */
	public function options()
	{
		log_message('INFO', 'NOVO Settings: options Method Initialized');

		$view = lang('GEN_SETTINGS_VIEW');

		array_push(
			$this->includeAssets->cssFiles,
			"third_party/dataTables-1.10.20"
		);
		array_push(
			$this->includeAssets->jsFiles,
			"third_party/dataTables-1.10.20",
			"settings/ceo_load_lots",
			"settings/settings"
		);

		$this->render->titlePage =lang('GEN_SETTINGS_TITLE');
		$this->views = ['settings/'.$view];
		$this->loadView($view);
	}
}
