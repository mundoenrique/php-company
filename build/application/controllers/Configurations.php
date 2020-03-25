<?php
defined('BASEPATH') OR  exit('No direct script access allowed');
/**
 * @info Controlador para manejar las peticiones referentes a configuraciones
 * @author Luis Molina
 * @date Marz 20Fri, 2020
*/
class Configurations extends NOVO_Controller {

	public function __construct()
	{
		parent :: __construct();
		log_message('INFO', 'NOVO Configurations Controller Class Initialized');
	}

	/**
	 * @info Método para renderizar el modulo de configuración
	 * @author Luis Molina
	 * @date Mar 23Mon, 2020
	 */
	public function configurationDownloads()
	{
		log_message('INFO', 'NOVO Configurations: configurationDownloads Method Initialized');

		$view = lang('GEN_CONFIGURATIONS_VIEW');

		array_push(
			$this->includeAssets->cssFiles,
			"third_party/dataTables-1.10.20"
		);
		array_push(
			$this->includeAssets->jsFiles,
			"third_party/dataTables-1.10.20",
			"configurations/ceo_load_lots",
			"configurations/configurations"
		);

		$this->render->titlePage =lang('GEN_CONFIGURATIONS_TITLE');
		$this->views = ['configurations/'.$view];
		$this->loadView($view);
	}
}
