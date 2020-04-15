<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @info Controlador para manejar los reportes
 * @author J. Enrique Peñaloza Piñero
 * @date December 7th, 2019
*/
class Novo_Reports extends NOVO_Controller {

	public function __construct()
	{
		parent:: __construct();
		log_message('INFO', 'Novo_Reports Controller Class Initialized');
	}
	/**
	 * @info Método para renderizar la lista de reportes
	 * @author J. Enrique Peñaloza Piñero
	 * @date February 6th, 2020
	 */
	public function getReportsList()
	{
		log_message('INFO', 'Novo_Reports: getReportsList Method Initialized');

		$view = 'reports';
		array_push(
			$this->includeAssets->cssFiles,
			"third_party/dataTables-1.10.20"
		);
		array_push(
			$this->includeAssets->jsFiles,
			"third_party/dataTables-1.10.20",
			"third_party/jquery.validate",
			"validate".$this->render->newViews."-forms",
			"third_party/additional-methods",
			"reports/reports"
		);
		$responseReports = $this->loadModel($this->request);
		$this->responseAttr($responseReports);

		foreach($responseReports->data AS $index => $render) {
			if($index !== 'resp') {
				$this->render->$index = $render;
			}
		}

		$this->render->titlePage =lang('GEN_MENU_REPORTS');
		$this->views = ['reports/'.$view];
		$this->loadView($view);
	}
}
