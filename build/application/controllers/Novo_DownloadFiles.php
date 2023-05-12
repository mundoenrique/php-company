<?php
defined('BASEPATH') OR  exit('No direct script access allowed');
/**
 * @info Controlador para manejar las peticiones referentes a a descarga de archivos
 * @author Luis Molina
 * @date March 03rd, 2019
*/
class Novo_DownloadFiles extends NOVO_Controller {

	public function __construct()
	{
		parent :: __construct();
		writeLog('INFO', 'DownloadFiles Controller Class Initialized');
	}

	/**
	 * @info Método Generico para exportar decargar archivos
	 ** @author Luis Molina
	 * @date March 10th, 2020
	 */
	public function exportFiles()
	{
		writeLog('INFO', 'DownloadFiles: exportFiles Method Initialized');

		$this->model = 'Novo_'.ucfirst($this->request->who).'_Model';
		$this->method = 'callWs_'.ucfirst($this->request->where).'_'.$this->request->who;

		$this->loadModel($this->request);
	}
}
