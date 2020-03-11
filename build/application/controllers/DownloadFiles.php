<?php
defined('BASEPATH') OR  exit('No direct script access allowed');
/**
 * @info Controlador para manejar las peticiones referentes a consultas de ordenes de servicio
 * @author J. Enrique Peñaloza Piñero
 * @date January 09th, 2019
*/
class DownloadFiles extends NOVO_Controller {

	public function __construct()
	{
		parent :: __construct();
		log_message('INFO', 'NOVO DownloadFiles Controller Class Initialized');
	}

	/**
	 * @info Método Generico para exportar archivos .pdf
	 ** @author Luis Molina
	 * @date Mar 10 Tue, 2020
	 */
	public function exportFiles()
	{
		log_message('INFO', 'NOVO Inquiries: exportFiles Method Initialized');

		$this->model = 'Novo_'.ucfirst($this->request->who).'_Model';
		$this->method = 'callWs_'.ucfirst($this->request->where).'_'.$this->request->who;

		$this->loadModel($this->request);
	}
}
